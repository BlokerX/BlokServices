<?php
// chat-api.php - API dla operacji chatu

// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Wczytanie konfiguracji bazy danych
require_once '../../config/database.php';

// Nagłówki CORS i JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

class ChatAPI {
    private $db;
    private $currentUserId;

    public function __construct($database) {
        $this->db = $database;
        $this->currentUserId = $_SESSION['user_id'];
    }

    public function handleRequest() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['action'])) {
            return $this->sendError('Brak akcji w żądaniu');
        }

        switch ($input['action']) {
            case 'get_current_user':
                return $this->getCurrentUser();
            case 'get_contacts':
                return $this->getContacts();
            case 'get_messages':
                return $this->getMessages($input['contact_id'] ?? null);
            case 'send_message':
                return $this->sendMessage($input['reciver_id'] ?? null, $input['content'] ?? null);
            case 'mark_as_read':
                return $this->markAsRead($input['contact_id'] ?? null);
            case 'typing_status':
                return $this->updateTypingStatus($input['contact_id'] ?? null, $input['is_typing'] ?? false);
            case 'get_online_users':
                return $this->getOnlineUsers();
            default:
                return $this->sendError('Nieznana akcja');
        }
    }

    private function getCurrentUser() {
        try {
            $stmt = $this->db->prepare("
                SELECT id, login, name, last_name, avatar, email 
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$this->currentUserId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                return $this->sendSuccess(['user' => $user]);
            } else {
                return $this->sendError('Użytkownik nie został znaleziony');
            }
        } catch (Exception $e) {
            return $this->sendError('Błąd bazy danych: ' . $e->getMessage());
        }
    }

    private function getContacts() {
        try {
            // Pobierz wszystkich użytkowników oprócz aktualnego użytkownika
            // wraz z informacjami o ostatnich wiadomościach
            $stmt = $this->db->prepare("
                SELECT DISTINCT
                    u.id,
                    u.name,
                    u.last_name,
                    u.avatar,
                    u.login,
                    CASE 
                        WHEN u.last_login_date > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online'
                        WHEN u.last_login_date > DATE_SUB(NOW(), INTERVAL 30 MINUTE) THEN 'away'
                        ELSE 'offline'
                    END as status,
                    (SELECT content 
                     FROM messages m 
                     WHERE (m.sender_user_id = u.id AND m.reciver_user_id = ?) 
                        OR (m.sender_user_id = ? AND m.reciver_user_id = u.id)
                     ORDER BY m.creation_date DESC 
                     LIMIT 1
                    ) as last_message,
                    (SELECT creation_date 
                     FROM messages m 
                     WHERE (m.sender_user_id = u.id AND m.reciver_user_id = ?) 
                        OR (m.sender_user_id = ? AND m.reciver_user_id = u.id)
                     ORDER BY m.creation_date DESC 
                     LIMIT 1
                    ) as last_message_date,
                    (SELECT COUNT(*) 
                     FROM messages m 
                     WHERE m.sender_user_id = u.id 
                       AND m.reciver_user_id = ? 
                       AND m.read_date IS NULL
                    ) as unread_count
                FROM users u
                WHERE u.id != ?
                  AND EXISTS (
                      SELECT 1 FROM messages m 
                      WHERE (m.sender_user_id = u.id AND m.reciver_user_id = ?) 
                         OR (m.sender_user_id = ? AND m.reciver_user_id = u.id)
                  )
                ORDER BY last_message_date DESC, u.name ASC
            ");
            
            $stmt->execute([
                $this->currentUserId, $this->currentUserId, // dla last_message
                $this->currentUserId, $this->currentUserId, // dla last_message_date
                $this->currentUserId, // dla unread_count
                $this->currentUserId, // WHERE u.id != ?
                $this->currentUserId, $this->currentUserId  // dla EXISTS
            ]);
            
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Jeśli brak kontaktów z wiadomościami, pobierz wszystkich użytkowników
            if (empty($contacts)) {
                $stmt = $this->db->prepare("
                    SELECT 
                        id, name, last_name, avatar, login,
                        CASE 
                            WHEN last_login_date > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online'
                            WHEN last_login_date > DATE_SUB(NOW(), INTERVAL 30 MINUTE) THEN 'away'
                            ELSE 'offline'
                        END as status,
                        NULL as last_message,
                        NULL as last_message_date,
                        0 as unread_count
                    FROM users 
                    WHERE id != ? 
                    ORDER BY name ASC
                    LIMIT 10
                ");
                $stmt->execute([$this->currentUserId]);
                $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $this->sendSuccess(['contacts' => $contacts]);
        } catch (Exception $e) {
            return $this->sendError('Błąd podczas pobierania kontaktów: ' . $e->getMessage());
        }
    }

    private function getMessages($contactId) {
        if (!$contactId) {
            return $this->sendError('Brak ID kontaktu');
        }

        try {
            $stmt = $this->db->prepare("
                SELECT 
                    m.*,
                    sender.name as sender_name,
                    sender.last_name as sender_last_name,
                    sender.avatar as sender_avatar
                FROM messages m
                JOIN users sender ON m.sender_user_id = sender.id
                WHERE (m.sender_user_id = ? AND m.reciver_user_id = ?)
                   OR (m.sender_user_id = ? AND m.reciver_user_id = ?)
                ORDER BY m.creation_date ASC
                LIMIT 100
            ");
            
            $stmt->execute([
                $this->currentUserId, $contactId,
                $contactId, $this->currentUserId
            ]);
            
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->sendSuccess(['messages' => $messages]);
        } catch (Exception $e) {
            return $this->sendError('Błąd podczas pobierania wiadomości: ' . $e->getMessage());
        }
    }

    private function sendMessage($reciverId, $content) {
        if (!$reciverId || !$content) {
            return $this->sendError('Brak odbiorcy lub treści wiadomości');
        }

        // Sprawdź czy odbiorca istnieje
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$reciverId]);
        if (!$stmt->fetch()) {
            return $this->sendError('Odbiorca nie został znaleziony');
        }

        try {
            $stmt = $this->db->prepare("
                INSERT INTO messages (sender_user_id, reciver_user_id, content, creation_date)
                VALUES (?, ?, ?, NOW())
            ");
            
            $stmt->execute([$this->currentUserId, $reciverId, trim($content)]);
            
            $messageId = $this->db->lastInsertId();

            return $this->sendSuccess([
                'message_id' => $messageId,
                'message' => 'Wiadomość została wysłana'
            ]);
        } catch (Exception $e) {
            return $this->sendError('Błąd podczas wysyłania wiadomości: ' . $e->getMessage());
        }
    }

    private function markAsRead($contactId) {
        if (!$contactId) {
            return $this->sendError('Brak ID kontaktu');
        }

        try {
            $stmt = $this->db->prepare("
                UPDATE messages 
                SET read_date = NOW() 
                WHERE sender_user_id = ? 
                  AND reciver_user_id = ? 
                  AND read_date IS NULL
            ");
            
            $stmt->execute([$contactId, $this->currentUserId]);
            
            return $this->sendSuccess(['message' => 'Wiadomości oznaczone jako przeczytane']);
        } catch (Exception $e) {
            return $this->sendError('Błąd podczas oznaczania wiadomości: ' . $e->getMessage());
        }
    }

    private function updateTypingStatus($contactId, $isTyping) {
        // Ta funkcja może być rozszerzona o system cache/Redis dla lepszej wydajności
        // Na razie zwracamy sukces bez implementacji
        return $this->sendSuccess(['typing_status' => $isTyping ? 'typing' : 'stopped']);
    }

    private function getOnlineUsers() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id, name, last_name, avatar,
                    CASE 
                        WHEN last_login_date > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 'online'
                        WHEN last_login_date > DATE_SUB(NOW(), INTERVAL 30 MINUTE) THEN 'away'
                        ELSE 'offline'
                    END as status
                FROM users 
                WHERE id != ? 
                ORDER BY last_login_date DESC
                LIMIT 20
            ");
            
            $stmt->execute([$this->currentUserId]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->sendSuccess(['online_users' => $users]);
        } catch (Exception $e) {
            return $this->sendError('Błąd podczas pobierania użytkowników online: ' . $e->getMessage());
        }
    }

    private function sendSuccess($data = []) {
        echo json_encode(array_merge(['success' => true], $data));
        exit;
    }

    private function sendError($message) {
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }
}

// Inicjalizacja i obsługa żądania
try {
    $chatAPI = new ChatAPI($pdo);
    $chatAPI->handleRequest();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd serwera: ' . $e->getMessage()]);
}
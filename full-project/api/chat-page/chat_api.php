<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit(0);

class ChatAPI {
    private $db;
    private $currentUserId;

    public function __construct($database) {
        $this->db = $database;
        $this->currentUserId = $_SESSION['user_id'];
    }

    public function handleRequest() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $action = $_GET['action'] ?? null;
        } else {
            $action = $input['action'] ?? null;
        }

        if (!$action) return $this->sendError('Brak akcji');

        switch ($action) {
            case 'get_current_user':
                return $this->getCurrentUser();
            case 'get_contacts':
                return $this->getContacts();
            case 'get_messages':
                $contactId = $_GET['contact_id'] ?? ($input['contact_id'] ?? null);
                return $this->getMessages($contactId);
            case 'send_message':
                return $this->sendMessage($input['receiver_id'] ?? null, $input['content'] ?? null);
            default:
                return $this->sendError('Nieznana akcja');
        }
    }

    private function getCurrentUser() {
        try {
            $stmt = $this->db->prepare("SELECT id, login, name, last_name, avatar FROM users WHERE id = ?");
            $stmt->execute([$this->currentUserId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $user['avatar'] = $user['avatar'] ?: './assets/img/avatars/default.png';
                return $this->sendSuccess(['user' => $user]);
            }
            return $this->sendError('Użytkownik nie znaleziony');
        } catch (Exception $e) {
            return $this->sendError('Błąd bazy: ' . $e->getMessage());
        }
    }

    private function getContacts() {
        try {
            $stmt = $this->db->prepare("
                SELECT id, login, name, last_name, avatar 
                FROM users 
                WHERE id != ?
                ORDER BY name ASC
            ");
            $stmt->execute([$this->currentUserId]);
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($contacts as &$contact) {
                $contact['avatar'] = $contact['avatar'] ?: './assets/img/avatars/default.png';
            }

            return $this->sendSuccess(['contacts' => $contacts]);
        } catch (Exception $e) {
            return $this->sendError('Błąd kontaktów: ' . $e->getMessage());
        }
    }

    private function getMessages($contactId) {
        if (!$contactId) return $this->sendError('Brak ID kontaktu');

        try {
            $stmt = $this->db->prepare("
                SELECT m.*, u.name as sender_name, u.last_name as sender_last_name
                FROM messages m
                JOIN users u ON m.sender_user_id = u.id
                WHERE (m.sender_user_id = ? AND m.reciver_user_id = ?)
                OR (m.sender_user_id = ? AND m.reciver_user_id = ?)
                ORDER BY m.creation_date ASC
            ");
            $stmt->execute([
                $this->currentUserId, $contactId,
                $contactId, $this->currentUserId
            ]);
            return $this->sendSuccess(['messages' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            return $this->sendError('Błąd wiadomości: ' . $e->getMessage());
        }
    }

    private function sendMessage($receiverId, $content) {
        if (!$receiverId || !$content) return $this->sendError('Brak danych');

        try {
            $stmt = $this->db->prepare("
                INSERT INTO messages (sender_user_id, reciver_user_id, content, creation_date)
                VALUES (?, ?, ?, NOW())
            ");
            $stmt->execute([$this->currentUserId, $receiverId, trim($content)]);
            return $this->sendSuccess(['message' => 'Wysłano!']);
        } catch (Exception $e) {
            return $this->sendError('Błąd wysyłania: ' . $e->getMessage());
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

try {
    $chatAPI = new ChatAPI($pdo);
    $chatAPI->handleRequest();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Błąd serwera: ' . $e->getMessage()]);
}
<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Ustawienie nagłówków dla JSON
header('Content-Type: application/json');

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['targetUserId']) || !isset($_POST['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try {
    $targetUserId = (int)$_POST['targetUserId'];
    $action = $_POST['action']; // 'toggle' - przełącza stan znajomości
    $currentUserId = (int)$_SESSION['user_id'];

    // Walidacja danych
    if ($targetUserId <= 0 || $currentUserId <= 0 || $currentUserId === $targetUserId) {
        throw new Exception('Nieprawidłowe dane użytkowników');
    }

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy użytkownik docelowy istnieje
    $userCheckQuery = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($userCheckQuery);
    $stmt->bind_param('i', $targetUserId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception('Użytkownik nie istnieje');
    }
    $stmt->close();

    // Sprawdź obecny stan znajomości
    $friendshipStatus = checkFriendshipStatus($conn, $currentUserId, $targetUserId);
    
    $response = ['status' => 'success'];
    
    switch ($friendshipStatus['status']) {
        case 'none':
            // Brak relacji - wyślij zaproszenie
            sendFriendRequest($conn, $currentUserId, $targetUserId);
            $response['message'] = 'Zaproszenie do znajomych zostało wysłane';
            $response['newStatus'] = 'request_sent';
            break;
            
        case 'request_sent':
            // Użytkownik wysłał zaproszenie - anuluj je
            cancelFriendRequest($conn, $currentUserId, $targetUserId);
            $response['message'] = 'Zaproszenie do znajomych zostało anulowane';
            $response['newStatus'] = 'none';
            break;
            
        case 'request_received':
            // Użytkownik otrzymał zaproszenie - zaakceptuj je
            acceptFriendRequest($conn, $friendshipStatus['request_id']);
            $response['message'] = 'Zaproszenie do znajomych zostało zaakceptowane';
            $response['newStatus'] = 'friends';
            break;
            
        case 'friends':
            // Już są znajomymi - usuń znajomość
            removeFriendship($conn, $currentUserId, $targetUserId);
            $response['message'] = 'Znajomość została usunięta';
            $response['newStatus'] = 'none';
            break;
            
        default:
            throw new Exception('Nieznany status znajomości');
    }

    close_db(); // Zamknij połączenie z bazą danych
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

/**
 * Sprawdza obecny stan znajomości między dwoma użytkownikami
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $userId1 ID pierwszego użytkownika
 * @param int $userId2 ID drugiego użytkownika
 * @return array Status znajomości
 */
function checkFriendshipStatus($conn, $userId1, $userId2) {
    // Sprawdź czy istnieje zaproszenie w jedną stronę
    $query = "SELECT id, sender_user_id, reciver_user_id, status 
              FROM friend_requests 
              WHERE ((sender_user_id = ? AND reciver_user_id = ?) 
                     OR (sender_user_id = ? AND reciver_user_id = ?))
              ORDER BY creation_date DESC 
              LIMIT 1";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $userId1, $userId2, $userId2, $userId1);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return ['status' => 'none'];
    }
    
    $request = $result->fetch_assoc();
    $stmt->close();
    
    switch ($request['status']) {
        case 'accepted':
            return ['status' => 'friends', 'request_id' => $request['id']];
            
        case 'pending':
            if ($request['sender_user_id'] == $userId1) {
                return ['status' => 'request_sent', 'request_id' => $request['id']];
            } else {
                return ['status' => 'request_received', 'request_id' => $request['id']];
            }
            
        case 'rejected':
            return ['status' => 'none'];
            
        default:
            return ['status' => 'none'];
    }
}

/**
 * Wysyła zaproszenie do znajomych
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $senderId ID wysyłającego
 * @param int $receiverId ID odbiorcy
 */
function sendFriendRequest($conn, $senderId, $receiverId) {
    $query = "INSERT INTO friend_requests (sender_user_id, reciver_user_id, status, creation_date) VALUES (?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $senderId, $receiverId);
    
    if (!$stmt->execute()) {
        throw new Exception('Nie udało się wysłać zaproszenia');
    }
    
    $stmt->close();
}

/**
 * Anuluje wysłane zaproszenie do znajomych
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $senderId ID wysyłającego
 * @param int $receiverId ID odbiorcy
 */
function cancelFriendRequest($conn, $senderId, $receiverId) {
    $query = "DELETE FROM friend_requests 
              WHERE sender_user_id = ? AND reciver_user_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $senderId, $receiverId);
    
    if (!$stmt->execute()) {
        throw new Exception('Nie udało się anulować zaproszenia');
    }
    
    $stmt->close();
}

/**
 * Akceptuje zaproszenie do znajomych
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $requestId ID zaproszenia
 */
function acceptFriendRequest($conn, $requestId) {
    $query = "UPDATE friend_requests SET status = 'accepted' WHERE id = ? AND status = 'pending'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $requestId);
    
    if (!$stmt->execute()) {
        throw new Exception('Nie udało się zaakceptować zaproszenia');
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('Zaproszenie nie istnieje lub zostało już przetworzone');
    }
    
    $stmt->close();
}

/**
 * Usuwa znajomość między użytkownikami
 * @param mysqli $conn Połączenie z bazą danych
 * @param int $userId1 ID pierwszego użytkownika
 * @param int $userId2 ID drugiego użytkownika
 */
function removeFriendship($conn, $userId1, $userId2) {
    $query = "DELETE FROM friend_requests 
              WHERE ((sender_user_id = ? AND reciver_user_id = ?) 
                     OR (sender_user_id = ? AND reciver_user_id = ?)) 
              AND status = 'accepted'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiii', $userId1, $userId2, $userId2, $userId1);
    
    if (!$stmt->execute()) {
        throw new Exception('Nie udało się usunąć znajomości');
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('Znajomość nie istnieje');
    }
    
    $stmt->close();
}
?>
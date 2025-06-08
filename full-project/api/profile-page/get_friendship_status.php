<?php
// get_friendship_status.php - Pobieranie statusu znajomości między użytkownikami

// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Ustawienie nagłówków dla JSON
header('Content-Type: application/json');

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['targetUserId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try {
    $targetUserId = (int)$_POST['targetUserId'];
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
    
    close_db(); // Zamknij połączenie z bazą danych
    
    echo json_encode([
        'status' => 'success',
        'friendshipStatus' => $friendshipStatus['status'],
        'requestId' => isset($friendshipStatus['request_id']) ? $friendshipStatus['request_id'] : null
    ]);

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
?>
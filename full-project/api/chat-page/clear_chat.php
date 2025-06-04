<?php
include '../script_template.php'; // Wczytaj szablon skryptu

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

$current_user_id = $_SESSION['user_id'];
$recipient_id = isset($_GET['recipient_id']) ? (int)$_GET['recipient_id'] : 0;

if (!$recipient_id) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID odbiorcy']);
    exit;
}

try {
    $conn = connect_db($config);
    
    // Usuń wszystkie wiadomości między użytkownikami
    $stmt = $conn->prepare("
        DELETE FROM messages
        WHERE 
            (sender_user_id = ? AND reciver_user_id = ?) OR 
            (sender_user_id = ? AND reciver_user_id = ?)
    ");
    $stmt->bind_param('iiii', $current_user_id, $recipient_id, $recipient_id, $current_user_id);
    $stmt->execute();
    
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
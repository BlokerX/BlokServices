<?php

include '../script_template.php'; // Wczytaj szablon skryptu

$current_user_id = $_SESSION['user_id'];
$recipient_id = isset($_GET['recipient_id']) ? (int)$_GET['recipient_id'] : 0;

if (!$recipient_id) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID odbiorcy']);
    exit;
}

try {
    $conn = connect_db($config);
    
    // Pobierz wiadomości między dwoma użytkownikami
    $stmt = $conn->prepare("
        SELECT m.id, m.sender_user_id, m.reciver_user_id, m.content, m.creation_date
        FROM messages m
        WHERE 
            (m.sender_user_id = ? AND m.reciver_user_id = ?) OR 
            (m.sender_user_id = ? AND m.reciver_user_id = ?)
        ORDER BY m.creation_date ASC
    ");
    $stmt->bind_param('iiii', $current_user_id, $recipient_id, $recipient_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    
    echo json_encode(['status' => 'success', 'messages' => $messages]);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
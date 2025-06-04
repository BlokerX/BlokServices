<?php

include '../script_template.php'; // Wczytaj szablon skryptu

$current_user_id = $_SESSION['user_id'];
$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;

if (!$message_id) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID wiadomości']);
    exit;
}

try {
    $conn = connect_db($config);
    
    // Sprawdź czy wiadomość należy do użytkownika
    $stmt = $conn->prepare("
        SELECT sender_user_id 
        FROM messages 
        WHERE id = ? AND sender_user_id = ?
    ");
    $stmt->bind_param('ii', $message_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nie masz uprawnień do usunięcia tej wiadomości']);
        exit;
    }
    
    // Usuń wiadomość
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param('i', $message_id);
    $stmt->execute();
    
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
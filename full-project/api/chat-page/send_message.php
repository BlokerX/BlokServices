<?php

include '../script_template.php'; // Wczytaj szablon skryptu

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

$sender_id = $_SESSION['user_id'];
$recipient_id = isset($_POST['recipient_id']) ? (int)$_POST['recipient_id'] : 0;
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if (!$recipient_id) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID odbiorcy']);
    exit;
}

if (empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Treść wiadomości nie może być pusta']);
    exit;
}

try {
    $conn = connect_db($config);
    
    // Wstaw nową wiadomość
    $stmt = $conn->prepare("
        INSERT INTO messages (sender_user_id, reciver_user_id, content, creation_date)
        VALUES (?, ?, ?, NOW())
    ");
    $stmt->bind_param('iis', $sender_id, $recipient_id, $content);
    $stmt->execute();
    
    echo json_encode(['status' => 'success']);
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
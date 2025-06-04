<?php
include '../script_template.php'; // Wczytaj szablon skryptu

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

$current_user_id = $_SESSION['user_id'];
$message_id = isset($_POST['message_id']) ? (int)$_POST['message_id'] : 0;
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

if (!$message_id) {
    echo json_encode(['status' => 'error', 'message' => 'Brak ID wiadomości']);
    exit;
}

if (empty($content)) {
    echo json_encode(['status' => 'error', 'message' => 'Treść wiadomości nie może być pusta']);
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
        echo json_encode(['status' => 'error', 'message' => 'Nie masz uprawnień do edycji tej wiadomości']);
        exit;
    }
    
    // Aktualizuj wiadomość
    $stmt = $conn->prepare("
        UPDATE messages 
        SET content = ?
        WHERE id = ?
    ");
    $stmt->bind_param('si', $content, $message_id);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Wiadomość została zaktualizowana']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nie udało się zaktualizować wiadomości']);
    }
    
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
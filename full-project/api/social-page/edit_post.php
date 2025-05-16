<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['postId']) && !isset($_POST['newContent'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try{
    $postId = $_POST['postId'];
    $newContent = $_POST['newContent'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy post należy do użytkownika
    $query = "UPDATE posts SET content = ?, last_modification_date= CURRENT_TIMESTAMP() WHERE id = ? AND owner_user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $newContent, $postId, $userId);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Post został zaktualizowany']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nie można zaktualizować posta']);
    }

    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>
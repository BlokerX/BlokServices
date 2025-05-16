<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['accessLevel']) || !isset($_POST['isCommentable'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}


try {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $accessLevel = $_POST['accessLevel'];
    $isCommentable = ($_POST['isCommentable'] === 'true') ? 1 : ($_POST['isCommentable'] === 'false' ? 0 : -1);
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy polubienie już istnieje
    $query = "INSERT INTO posts (title, content, access_level, is_commentable, owner_user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssis', $title, $content, $accessLevel, $isCommentable, $userId);
    
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    echo json_encode(['status' => 'success', 'message' => 'Komentarz został dodany']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
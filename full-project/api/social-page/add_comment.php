<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['postId']) || !isset($_POST['commentText'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try {
    $postId = $_POST['postId'];
    $commentText = $_POST['commentText'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy polubienie już istnieje
    $query = "INSERT INTO posts_comments (post_id, comment_author_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $postId, $userId, $commentText);
    
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    echo json_encode(['status' => 'success', 'message' => 'Komentarz został dodany']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
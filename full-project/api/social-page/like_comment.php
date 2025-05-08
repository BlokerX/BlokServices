<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
require_once 'script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['commentId']) || !isset($_POST['isLiked'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try {
    $commentId = $_POST['commentId'];
    $isLiked = $_POST['isLiked'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy polubienie już istnieje
    $checkQuery = "SELECT * FROM posts_comments_likes WHERE post_comment_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ii', $commentId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $stmt = null; // Ustawienie na null po zamknięciu
    $query = "";

    echo json_encode(['is liked' => $isLiked, 'num_rows' => $result->num_rows]);


    if ($isLiked === "true") {
        // Dodaj polubienie jeśli nie istnieje
        if ($result->num_rows == 0) {
            $query = "INSERT INTO posts_comments_likes (post_comment_id, user_id) VALUES (?, ?)";
        }
    } else if($isLiked === "false") {
        // Usuń polubienie jeśli istnieje
        if ($result->num_rows > 0) {
            $query = "DELETE FROM posts_comments_likes WHERE post_comment_id = ? AND user_id = ?";
        }
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $commentId, $userId);
    
    echo json_encode(['query' => $query . " " . $commentId." " . $userId]);

    $stmt->execute();
    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    echo json_encode(['status' => 'success', 'message' => 'Status polubienia zaktualizowany']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
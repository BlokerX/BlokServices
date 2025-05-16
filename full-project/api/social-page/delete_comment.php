<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['commentId'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

try {
    $commentId = $_POST['commentId'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy komentarz należy do użytkownika
    $query = "DELETE FROM posts_comments WHERE id = ? AND comment_author_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $commentId, $userId);
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Komentarz został usunięty']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Nie można usunąć komentarza']);
    }

    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>

<?php
// Wczytanie konfiguracji JSON
include '../../page-container/json-config-load.php';

// Ten skrypt obsługuje funkcjonalność polubień/odlubień postów w aplikacji społecznościowej.
// Sprawdza, czy użytkownik jest zalogowany, pobiera ID posta i status polubienia z żądania,
// i odpowiednio aktualizuje bazę danych.
// Zwraca odpowiedź JSON wskazującą na sukces lub niepowodzenie operacji.
header('Content-Type: application/json');

// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sprawdź czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['postId']) || !isset($_POST['commentText'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

require_once '../../page-container/db.php';

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
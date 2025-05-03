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
if (!isset($_POST['postId']) || !isset($_POST['isLiked'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

require_once '../../page-container/db.php';

try {
    $postId = $_POST['postId'];
    $isLiked = $_POST['isLiked'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Sprawdź czy polubienie już istnieje
    $checkQuery = "SELECT * FROM posts_likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ii', $postId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $stmt = null; // Ustawienie na null po zamknięciu
    $query = "";

    echo json_encode(['is liked' => $isLiked, 'num_rows' => $result->num_rows]);


    if ($isLiked === "true") {
        // Dodaj polubienie jeśli nie istnieje
        if ($result->num_rows == 0) {
            $query = "INSERT INTO posts_likes (post_id, user_id) VALUES (?, ?)";
        }
    } else if($isLiked === "false") {
        // Usuń polubienie jeśli istnieje
        if ($result->num_rows > 0) {
            $query = "DELETE FROM posts_likes WHERE post_id = ? AND user_id = ?";
        }
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $postId, $userId);
    
    echo json_encode(['query' => $query]);

    $stmt->execute();
    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    echo json_encode(['status' => 'success', 'message' => 'Status polubienia zaktualizowany']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
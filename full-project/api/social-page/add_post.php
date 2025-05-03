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
if (!isset($_POST['title']) || !isset($_POST['content']) || !isset($_POST['accessLevel']) || !isset($_POST['isCommentable'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

require_once '../../page-container/db.php';

try {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $accessLevel = $_POST['accessLevel'];
    $isCommentable = $_POST['isCommentable'];
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
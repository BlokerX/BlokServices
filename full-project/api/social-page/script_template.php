<?php
// Wczytanie konfiguracji JSON
require_once '../../page-container/json-config-load.php';
require_once '../../page-container/db.php';

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
?>

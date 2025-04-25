<?php
// Wczytanie struktury strony
include '../../page-container/json-config-load.php';

// Sprawdź, czy urzytkownik nie jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    // Użytkownik nie jest zalogowany, przekieruj go do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=not_logged_in");
    exit;
}

// Sprawdź, czy użytkownik chce usunąć konto

    // Pobierz ID użytkownika z sesji
    $user_id = $_SESSION['user_id'];

    // Połączenie z bazą danych
    $conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // na podstawie ID użytkownika, usuń konto z bazy danych
    $query = "DELETE FROM users WHERE id ='$user_id'";

    // wykonaj zapytanie bez walidacji
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    // Zniszcz sesję
    session_destroy();
    // Ustaw cookie na 0, aby wylogować użytkownika
    setcookie('login', '', time() - 3600, '/');
    setcookie('password', '', time() - 3600, '/');
    // Przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?success=account_deleted");
    exit;
?>
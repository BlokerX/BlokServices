<?php // Wylogowanie użytkownika

// Wczytanie struktury strony
include '../../page-container/json-config-load.php';

// Sprawdź, czy użytkownik jest zalogowany
session_start();

if (isset($_SESSION['user_id'])) {
    // Usuń wszystkie zmienne sesyjne
    session_unset();
    
    // Zniszcz sesję
    session_destroy();

    session_start();
    // Ustaw cookie na 0, aby wylogować użytkownika
    setcookie('login', '', time() - 3600, '/');
    setcookie('password', '', time() - 3600, '/');
    
    // Przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?success=logout");
    exit;
} else {
    // Użytkownik nie jest zalogowany, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=not_logged_in");
    exit;
}

?>
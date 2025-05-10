<?php
// Wczytanie struktury strony
include '../../page-container/json-config-load.php';

// Sprawdź, czy użytkownik jest już zalogowany
session_start();
if (isset($_SESSION['user_id'])) {
    // Użytkownik jest już zalogowany, przekieruj go do strony profilu
    header("Location: " . $config['pages']['profile-page']['path']. "?user_name=" . $_SESSION['user_name']);
    exit;
}

$login = '';
$password = '';
$remember = false;

if (isset($_POST['login'])) {
    $login = $_POST['login'];
}
else {
    // Jeśli nie podano loginu, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=missing_login");
    exit;
}

if (isset($_POST['password'])) {
    $password = $_POST['password'];
}
else {
    // Jeśli nie podano hasła, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=missing_password");
    exit;
}

if (isset($_POST['remember'])) {
    $remember = $_POST['remember'];
}
else {
    $remember = false;
}

$login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
$remember = htmlspecialchars($remember, ENT_QUOTES, 'UTF-8');

// Sprawdź czy login i hasło są poprawne z bazy danych:

// Połączenie z bazą danych
$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Użyj przygotowanych zapytań, aby uniknąć SQL Injection
$query = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Użytkownik istnieje, zaloguj go
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['login'];
    $_SESSION['user_avatar'] = $user['avatar'];
    $_SESSION['user_is_admin'] = $user['is_admin'];

    // Zaktualizuj datę ostatniego logowania
    $update_query = "UPDATE users SET last_login_date = NOW() WHERE id = " . $user['id'];
    mysqli_query($conn, $update_query);

    // Jeśli zaznaczone "Zapamiętaj mnie", ustaw cookie
    if ($remember) {
        setcookie('login', $login, time() + (86400 * 30), "/"); // 86400 = 1 dzień
        setcookie('password', $password, time() + (86400 * 30), "/");
    }

    mysqli_close($conn);
    // Przekierowanie do strony profilu
    header("Location: " . $config['pages']['profile-page']['path']. "?user_name=" . $_SESSION['user_name']);
} else {
    mysqli_close($conn);
    // Błędne dane logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=invalid_credentials");
}
// Zamknij połączenie z bazą danych
mysqli_close($conn);
?>
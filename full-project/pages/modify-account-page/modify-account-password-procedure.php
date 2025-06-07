<?php
include '../../page-container/json-config-load.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
}

$user_id = $_SESSION['user_id'];

$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pobierz stare i nowe hasła z formularza
$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$new_password_repeated = $_POST['new_password_repeated'] ?? '';

// Sprawdź czy wszystkie pola są wypełnione
if (empty($old_password) || empty($new_password) || empty($new_password_repeated)) {
    header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=empty_fields");
    exit;
}

// Sprawdź czy nowe hasła się zgadzają
if ($new_password !== $new_password_repeated) {
    header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=passwords_not_match");
    exit;
}

// Pobierz aktualne hasło z bazy
$sql = "SELECT password FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$old_password_hashed = hash('sha256', $old_password);

if (!$row || !password_verify($old_password_hashed, $row['password'])) {
    header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=wrong_old_password");
    exit;
}

// Zaktualizuj hasło
$new_password_hashed = hash('sha256', $new_password);
$update_sql = "UPDATE users SET password = '$new_password_hashed' WHERE id = $user_id";

if (mysqli_query($conn, $update_sql)) {
    header("Location: " . $config['pages']['profile-page']['path'] . "?success=password_changed");
} else {
    header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=update_failed");
}

mysqli_close($conn);
?>

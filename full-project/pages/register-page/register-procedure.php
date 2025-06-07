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
if(isset($_POST['login'])) {
    $login = $_POST['login'];
}
else {
    // Jeśli nie podano loginu, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_login");
    exit;
}

$password = '';
if(isset($_POST['password'])) {
    $password = $_POST['password'];
}
else {
    // Jeśli nie podano hasła, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_password");
    exit;
}

$email = '';
if(isset($_POST['email'])) {
    $email = $_POST['email'];
}
else {
    // Jeśli nie podano emaila, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_email");
    exit;
}

$phone_number = '';
if(isset($_POST['phone_number'])) {
    $phone_number = $_POST['phone_number'];
}
else {
    // Jeśli nie podano numeru telefonu, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_phone_number");
    exit;
}

$avatar = '';
if(isset($_POST['avatar'])) {
    $avatar = $_POST['avatar'];
}
else {
    // Jeśli nie podano awatara, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_avatar");
    exit;
}

$name = '';
if(isset($_POST['name'])) {
    $name = $_POST['name'];
}
else {
    // Jeśli nie podano imienia, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_name");
    exit;
}

$last_name = '';
if(isset($_POST['last_name'])) {
    $last_name = $_POST['last_name'];
}
else {
    // Jeśli nie podano nazwiska, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_last_name");
    exit;
}

$gender = '';
if(isset($_POST['gender'])) {
    $gender = $_POST['gender'];
}
else {
    // Jeśli nie podano płci, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_gender");
    exit;
}

$birth_date = '';
if(isset($_POST['birth_date'])) {
    $birth_date = $_POST['birth_date'];
}
else {
    // Jeśli nie podano daty urodzenia, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_birth_date");
    exit;
}

$description = '';
if(isset($_POST['description'])) {
    $description = $_POST['description'];
}
else {
    // Jeśli nie podano opisu, przekieruj do strony rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=missing_description");
    exit;
}

// Sprawdź, czy parametry są poprawne
$login = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$phone_number = htmlspecialchars($phone_number, ENT_QUOTES, 'UTF-8');
$avatar = htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8');
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$last_name = htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8');
$gender = htmlspecialchars($gender, ENT_QUOTES, 'UTF-8');
$birth_date = htmlspecialchars($birth_date, ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');

// Sprawdź, czy login, email i numer telefonu istnieją w bazie danych
$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Zapytanie do bazy danych, aby sprawdzić, czy login, email i numer telefonu są już zajęte
$query = "SELECT * FROM users WHERE login = '$login' OR email = '$email' OR phone_number = '$phone_number'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Użytkownik już istnieje, przekieruj do strony rejestracji z błędem
    header("Location: " . $config['pages']['register-page']['path'] . "?error=user_exists");
    exit;
}


$password_hashed = hash('sha256', $password);

// Użytkownik nie istnieje, dodaj go do bazy danych
$query = "INSERT INTO users (login, email, phone_number, password, avatar, name, last_name, gender, birth_date, description, register_date, is_admin)
          VALUES ('$login', '$email', '$phone_number', '$password_hashed', '$avatar', '$name', '$last_name', '$gender', '$birth_date', '$description', current_timestamp(), 0)";

if (mysqli_query($conn, $query)) {
    mysqli_close($conn);
    // Rejestracja udana, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?success=registration");
    exit;
} else {
    mysqli_close($conn);
    // Błąd podczas rejestracji
    header("Location: " . $config['pages']['register-page']['path'] . "?error=registration_failed");
    exit;
}

mysqli_close($conn);

?>
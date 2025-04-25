<?php
include '../../page-container/json-config-load.php';

// Rozpoczęcie sesji i sprawdzenie czy użytkownik jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Połączenie z bazą danych
$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pobranie aktualnych danych użytkownika
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Przygotowanie danych do aktualizacji
$updates = array();
$params = ['login', 'email', 'phone_number', 'avatar', 'name', 'last_name', 'gender', 'birth_date', 'description'];

// Sprawdzenie które pola zostały wypełnione
foreach ($params as $param) {
    if (isset($_POST[$param]) && !empty($_POST[$param])) {
        $value = mysqli_real_escape_string($conn, $_POST[$param]);
        $updates[] = "$param = '$value'";
        
        // Zapisanie zmienionych wartości do późniejszej aktualizacji sesji
        $changed_values[$param] = $_POST[$param];
    }
}

// Sprawdzenie czy login/email/telefon już istnieją w bazie
if (isset($_POST['login']) || isset($_POST['email']) || isset($_POST['phone_number'])) {
    $check_query = "SELECT * FROM users WHERE (";
    $conditions = [];
    
    if (isset($_POST['login'])) {
        $conditions[] = "login = '" . mysqli_real_escape_string($conn, $_POST['login']) . "'";
    }
    if (isset($_POST['email'])) {
        $conditions[] = "email = '" . mysqli_real_escape_string($conn, $_POST['email']) . "'";
    }
    if (isset($_POST['phone_number'])) {
        $conditions[] = "phone_number = '" . mysqli_real_escape_string($conn, $_POST['phone_number']) . "'";
    }
    
    $check_query .= implode(" OR ", $conditions) . ") AND id != $user_id";
    
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
        header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=exists");
        exit;
    }
}

// Aktualizacja danych użytkownika jeśli są jakieś zmiany
if (!empty($updates)) {
    $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = $user_id";
    if (mysqli_query($conn, $sql)) {
        // Aktualizacja tylko zmienionych zmiennych sesyjnych
        foreach ($changed_values as $key => $value) {
            if ($key === 'login') {
                $_SESSION['user_name'] = $value;
            }
            if ($key === 'avatar') {
                $_SESSION['user_avatar'] = $value;
            }
            // Tutaj możesz dodać więcej zmiennych sesyjnych do aktualizacji
        }
        
        header("Location: " . $config['pages']['profile-page']['path'] . "?success=updated");
    } else {
        header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=update_failed");
    }
} else {
    header("Location: " . $config['pages']['modify-account-page']['path'] . "?error=no_changes");
}

// Zamknięcie połączenia z bazą danych
mysqli_close($conn);
?>

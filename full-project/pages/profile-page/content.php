<?php 
$user_name = '';
if(isset($_GET['user_name']))
{
    $user_name = $_GET['user_name'];
} else {
    // Jeśli nie podano nazwy użytkownika, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=missing_user_name");
    exit;
}

$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM users WHERE Nick = '" . $user_name . "'";

$result = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($result);
mysqli_close($conn);

echo '<main>';
echo '<h1>Witaj, ' . htmlspecialchars($user['Nick']) . '</h1><br>';
echo '<img src="' . htmlspecialchars($user['Avatar']) . '" alt="Avatar" class="user-avatar"><br>';
echo '<p>ID użytkownika: ' . htmlspecialchars($user['ID']) . '</p><br>';
echo '<p>To jest strona profilu użytkownika.</p><br>';
echo '<p>Imię: ' . htmlspecialchars($user['Name']) . '</p><br>';
echo '<p>Nazwisko: ' . htmlspecialchars($user['Surname']) . '</p><br>';
echo '<p>Email: ' . htmlspecialchars($user['Email']) . '</p><br>';
echo '<p>Numer telefonu: ' . htmlspecialchars($user['PhoneNumber']) . '</p><br>';
echo '<p>Data rejestracji: ' . htmlspecialchars($user['RegisterDate']) . '</p><br>';
echo '<p>Ostatnie logowanie: ' . htmlspecialchars($user['LastLoginDate']) . '</p><br>';
echo '<p>Opis: ' . htmlspecialchars($user['Description']) . '</p><br>';
echo '<p>Czy admin: ' . htmlspecialchars($user['IsAdmin']) . '</p><br>';
echo '<p>Połączone konta: ' . htmlspecialchars($user['LinkedAccounts']) . '</p><br>';
echo '</main>';
?>
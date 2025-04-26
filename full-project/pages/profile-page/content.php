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

$query = "SELECT * FROM users WHERE login = '" . $user_name . "'";

$result = mysqli_query($conn, $query);

$user = mysqli_fetch_assoc($result);
mysqli_close($conn);

// Walidacja danych użytkownika

switch ($user['gender']) {
    case 'Male':
        $user['gender'] = 'Mężczyzna';
        break;
    case 'Female':
        $user['gender'] = 'Kobieta';
        break;
    case 'Other':
        $user['gender'] = 'Inne';
        break;
    default:
        $user['gender'] = 'Nieznany';
        break;
}

switch ($user['is_admin']) {
    case 0:
        $user['is_admin'] = 'Nie';
        break;
    case 1:
        $user['is_admin'] = 'Tak';
        break;
    default:
        $user['is_admin'] = 'Nieznany';
        break;
}

echo '<main>';
echo '<h1>Witaj, ' . htmlspecialchars($user['login']) . '</h1><br>';
echo '<img src="' . htmlspecialchars($user['avatar']) . '" alt="Avatar" class="user-avatar"><br>';
echo '<p>ID użytkownika: ' . htmlspecialchars($user['id']) . '</p><br>';
echo '<p>To jest strona profilu użytkownika.</p><br>';
echo '<p>Imię: ' . htmlspecialchars($user['name']) . '</p><br>';
echo '<p>Nazwisko: ' . htmlspecialchars($user['last_name']) . '</p><br>';
echo '<p>Płeć: ' . htmlspecialchars($user['gender']) . '</p><br>';
echo '<p>Data urodzenia: ' . htmlspecialchars($user['birth_date']) . '</p><br>';
echo '<p>Email: ' . htmlspecialchars($user['email']) . '</p><br>';
echo '<p>Numer telefonu: ' . htmlspecialchars($user['phone_number']) . '</p><br>';
echo '<p>Data rejestracji: ' . htmlspecialchars($user['register_date']) . '</p><br>';
echo '<p>Ostatnie logowanie: ' . htmlspecialchars($user['last_login_date']) . '</p><br>';
echo '<p>Ostatnie wylogowanie: ' . htmlspecialchars($user['last_logout_date']) . '</p><br>';
echo '<p>Opis: ' . htmlspecialchars($user['description']) . '</p><br>';
echo '<p>Czy admin: ' . htmlspecialchars($user['is_admin']) . '</p><br>';

if($_SESSION['user_id'] == $user['id']) {
echo '

<form action="'.$config['pages']['modify-account-page']['path'].'" method="POST" class="delete-form">
    <button type="submit" class="modify-button">Edytuj profil</button>
</form>

<form action="delete-account-procedure.php" method="POST" class="delete-form">
    <button type="submit" class="delete-button">Usuń profil</button>
</form>

';}
else {
    echo '<p>Dodaj do znajomych.</p><br>';
}

echo '</main>';
?>
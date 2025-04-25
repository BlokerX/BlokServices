<?php
// Wczytanie struktury strony
include '../../page-container/json-config-load.php';

// Sprawdź, czy użytkownik nie jest zalogowany
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['profile-page']['path'] . "?user_name=" . $_SESSION['user_name']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edycja profilu</title>
</head>
<body>
    <h2>Edytuj profil</h2>
    <form action="<?php echo $config['pages']['modify-account-page']['modify-account-procedure-path'];?>" method="POST">
        <div>
            <label for="login">Login:</label>
            <input type="text" id="login" name="login">
        </div>
        <!-- <div>
            <label for="old_password">Stare hasło:</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div>
            <label for="new_password">Nowe hasło:</label>
            <input type="password" id="new_password" name="new_password">
        </div>
        <div>
            <label for="new_password_repeated">Hasło:</label>
            <input type="password" id="new_password_repeated" name="new_password_repeated">
        </div> -->
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
        </div>
        <div>
            <label for="phone_number">Numer telefonu:</label>
            <input type="tel" id="phone_number" name="phone_number">
        </div>
        <div>
            <label for="avatar">Avatar URL:</label>
            <input type="text" id="avatar" name="avatar">
        </div>
        <div>
            <label for="name">Imię:</label>
            <input type="text" id="name" name="name">
        </div>
        <div>
            <label for="last_name">Nazwisko:</label>
            <input type="text" id="last_name" name="last_name">
        </div>
        <div>
            <label for="gender">Płeć:</label>
            <select id="gender" name="gender">
                <option value="M">Mężczyzna</option>
                <option value="K">Kobieta</option>
                <option value="O">Inne</option>
            </select>
        </div>
        <div>
            <label for="birth_date">Data urodzenia:</label>
            <input type="date" id="birth_date" name="birth_date">
        </div>
        <div>
            <label for="description">Opis:</label>
            <textarea id="description" name="description"></textarea>
        </div>
        <button type="submit">Zaktualizuj profil</button>
    </form>
</body>
</html>
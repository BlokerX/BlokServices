<?php
// Wczytanie struktury strony
include '../../page-container/json-config-load.php';

// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Rozpocznij sesję, jeśli nie jest już rozpoczęta
}

$user = null;

// Sprawdź, czy użytkownik nie jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
} else {
    // Połącz z bazą danych
    $conn = new mysqli($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);

    // Sprawdź połączenie
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Pobierz dane użytkownika
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<main class="auth-main">
    <div class="auth-container">

        <div class="auth-form-container">
            <h1>Edytuj profil</h1>
            <p class="auth-subtitle">Dołącz do nas! Utwórz swoje konto, aby korzystać z wszystkich funkcji.</p>

            <form action="<?php echo $config['pages']['modify-account-page']['modify-account-procedure-path']; ?>" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="login">Nazwa użytkownika</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="login" name="login" placeholder="Wprowadź nazwę użytkownika" value="<?php echo $user['login']; ?>" required>
                    </div>
                </div>

                <!-- <div class="form-group">
            <label for="old_password">Stare hasło:</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Nowe hasło:</label>
            <input type="password" id="new_password" name="new_password">
        </div>
        <div class="form-group">
            <label for="new_password_repeated">Hasło:</label>
            <input type="password" id="new_password_repeated" name="new_password_repeated">
        </div> -->

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Wprowadź adres email" value="<?php echo $user['email']; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone_number">Numer telefonu</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="Wprowadź numer telefonu" value="<?php echo $user['phone_number']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="avatar">Link do avatara (opcjonalnie)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-image"></i>
                        <input type="text" id="avatar" name="avatar" placeholder="Wprowadź URL do obrazu avatara" value="<?php echo $user['avatar']; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Imię</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="name" name="name" placeholder="Wprowadź imię" value="<?php echo $user['name']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="last_name">Nazwisko</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="last_name" name="last_name" placeholder="Wprowadź nazwisko" value="<?php echo $user['last_name']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Płeć</label>
                    <div class="input-with-icon">
                        <i class="fas fa-venus-mars"></i>
                        <select id="gender" name="gender" required>
                            <option value="" disabled <?php if ($user['gender'] === NULL) echo 'selected'; ?>>Wybierz płeć</option>
                            <option value="Male" <?php if ($user['gender'] === 'Male') echo 'selected'; ?>>Mężczyzna</option>
                            <option value="Female" <?php if ($user['gender'] === 'Female') echo 'selected'; ?>>Kobieta</option>
                            <option value="Other" <?php if ($user['gender'] === 'Other') echo 'selected'; ?>>Inne</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="birth_date">Data urodzenia</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="birth_date" name="birth_date" value="<?php echo $user['birth_date']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Opis profilu (opcjonalnie)</label>
                    <textarea id="description" name="description" placeholder="Napisz coś o sobie" rows="3"><?php echo $user['description']; ?></textarea>
                </div>

                <button type="submit" class="auth-button">Zaktualizuj profil</button>

                <button class="auth-button back-button" href="<?php echo $config['pages']['profile-page']['path'] . "?user_name=" . $_SESSION['user_name']; ?>">Anuluj (Wróć do strony profilu)</button>

            </form>
        </div>
    </div>
</main>

<?php
// Zamknij połączenie z bazą danych
$stmt->close();
$conn->close();
?>
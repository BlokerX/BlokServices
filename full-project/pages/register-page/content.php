<?php
// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sprawdź, czy użytkownik jest już zalogowany
if (isset($_SESSION['user_id'])) {
    // Użytkownik jest już zalogowany, przekieruj go do strony profilu
    header("Location: " . $config['pages']['profile-page']['path'] . "?user_name=" . $_SESSION['user_name']);
    exit;
}
?>

<!-- #region Zawartość podstrony -->

<main class="auth-main">
    <div class="auth-container">
        <div class="auth-form-container">
            <h1>Rejestracja</h1>
            <p class="auth-subtitle">Dołącz do nas! Utwórz swoje konto, aby korzystać z wszystkich funkcji.</p>

            <form method="post" action="register-procedure.php" id="register-form" class="auth-form">
                <div class="form-group">
                    <label for="login">Nazwa użytkownika</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="login" name="login" placeholder="Wprowadź nazwę użytkownika" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Wprowadź adres email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Hasło</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="name">Imię</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="name" name="name" placeholder="Wprowadź imię" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="last_name">Nazwisko</label>
                    <div class="input-with-icon">
                        <i class="fas fa-id-card"></i>
                        <input type="text" id="last_name" name="last_name" placeholder="Wprowadź nazwisko" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="phone_number">Numer telefonu</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="phone_number" name="phone_number" placeholder="Wprowadź numer telefonu">
                    </div>
                </div>

                <div class="form-group">
                    <label for="gender">Płeć</label>
                    <div class="input-with-icon">
                        <i class="fas fa-venus-mars"></i>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>Wybierz płeć</option>
                            <option value="M">Mężczyzna</option>
                            <option value="K">Kobieta</option>
                            <option value="O">Inne</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="birth_date">Data urodzenia</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="birth_date" name="birth_date" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="avatar">Link do avatara (opcjonalnie)</label>
                    <div class="input-with-icon">
                        <i class="fas fa-image"></i>
                        <input type="text" id="avatar" name="avatar" placeholder="Wprowadź URL do obrazu avatara">
                    </div>
                </div>

                <div class="form-group">
                    <label for="description">Opis profilu (opcjonalnie)</label>
                    <textarea id="description" name="description" placeholder="Napisz coś o sobie" rows="3"></textarea>
                </div>

                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">Akceptuję <a href="<?php echo $config['documents']['terms-of-service']; ?>">Regulamin</a> oraz <a href="<?php echo $config['documents']['privacy-policy']; ?>">Politykę prywatności</a></label>
                </div>

                <button type="submit" class="auth-button">Zarejestruj się</button>

                <?php
                // Sprawdzanie czy próba rejestracji zakończyła się błędem
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    if ($error === 'user_exists') {
                        echo '<label class="error-message">Użytkownik o podanym loginie, emailu lub numerze telefonu już istnieje.</label>';
                    } elseif ($error === 'registration_failed') {
                        echo '<label class="error-message">Rejestracja nie powiodła się. Spróbuj ponownie później.</label>';
                    } elseif (strpos($error, 'missing_') !== false) {
                        $field = str_replace('missing_', '', $error);
                        echo '<label class="error-message">Pole ' . $field . ' jest wymagane.</label>';
                    }
                }
                ?>

                <div class="social-login">
                    <p>Lub zarejestruj się przez:</p>
                    <div class="social-buttons">
                        <button type="button" class="social-button google">
                            <i class="fab fa-google"></i> Google
                        </button>
                        <button type="button" class="social-button facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </button>
                    </div>
                </div>
            </form>

            <div class="auth-footer">
                <p>Masz już konto? <a href="<?php echo $config['pages']['login-page']['path']; ?>">Zaloguj się</a></p>
            </div>
        </div>


    </div>
</main>

<!-- #endregion -->
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
            <h2>Logowanie</h2>
            <p class="auth-subtitle">Witaj z powrotem! Zaloguj się, aby kontynuować.</p>

            <form method="post" action="login-procedure.php" id="login-form" class="auth-form">

                <div class="form-group">
                    <label for="email">Email lub nazwa użytkownika</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="email" name="login" placeholder="Wprowadź email lub nazwę użytkownika" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Hasło</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
                    </div>
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Zapamiętaj mnie</label>
                    </div>
                    <a href="<?php echo $config['pages']['reset-password-page']['path']; ?>" class="forgot-password">Zapomniałeś hasła?</a>
                </div>

                <button type="submit" class="auth-button">Zaloguj się</button>

                <?php
                // Sprawdzanie czy próba logowania zakończyła się błędem
                if (isset($_GET['error']) && $_GET['error'] === 'invalid_credentials') {
                    echo '<label class="error-message">Sprawdź swoją nazwę konta oraz hasło i spróbuj ponownie.</label>';
                }
                ?>

                <div class="social-login">
                    <p>Lub zaloguj się przez:</p>
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
                <p>Nie masz jeszcze konta? <a href="<?php echo $config['pages']['register-page']['path']; ?>">Zarejestruj się</a></p>
            </div>
        </div>

        <div class="auth-image">
            <div class="auth-image-overlay">
                <div class="auth-image-content">
                    <h2>Witaj w BlokerCompany™</h2>
                    <p>Twoje centrum multimedialne, społecznościowe i organizacyjne w jednym miejscu.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- #endregion -->

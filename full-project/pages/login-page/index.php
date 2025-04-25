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

// Sprawdzanie błędów wczytywania pliku JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Błąd wczytywania pliku konfiguracyjnego JSON.');
}

// Sprawdzenie, czy użytkownik jest już zalogowany
?>

<!-- Kod HTML -->
<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $config['pages']['login-page']['title']; ?></title>

    <meta name="description" content="<?php echo $config['app']['description']; ?>">
    <meta name="author" content="<?php echo $config['app']['author']; ?>">
    <meta name="application-name" content="<?php echo $config['app']['name']; ?>">

    <link rel="icon" href="<?php echo $config['images']['favicon'] ?>" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $config['styles']['main']; ?>">
    <link rel="stylesheet" href="<?php echo $config['styles']['themes']; ?>">
    <link rel="stylesheet" href="styles/login-page.css">

</head>

<body data-theme="light">

    <header>
        <div class="logo-container">
            <img src="<?php echo $config['images']['logo']; ?>" alt="Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>

        <!-- Gość -->
        <div class="header-actions guest-actions">
            <!-- <button class="login-button">Zaloguj</button> -->
            <button class="register-button">Zarejestruj</button>
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>

    </header>

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
                    <p>Nie masz jeszcze konta? <a href="<?php echo $config['pages']['register-page']['path'];?>">Zarejestruj się</a></p>
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

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['app']['author']; ?>. Wszelkie prawa zastrzeżone.</p>
            </div>
            <div class="footer-links">
                <a href="<?php echo $config['documents']['terms-of-service']; ?>">Regulamin</a>
                <a href="<?php echo $config['documents']['privacy-policy']; ?>">Polityka prywatności</a>
                <a>Kontakt</a>
            </div>
        </div>
    </footer>

    <!-- Wczytywanie skryptów JS -->
    <script src="<?php echo $config['scripts']['main']; ?>"></script>
    <script src="scripts/login-page.js"></script>
    <script src="<?php echo $config['scripts']['theme']; ?>"></script>

</body>

</html>
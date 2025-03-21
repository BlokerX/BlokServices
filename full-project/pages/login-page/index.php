<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - BlokerCompany™</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="/styles/themes.css">
    <link rel="stylesheet" href="/styles/auth.css">
</head>
<body data-theme="light">
    
    <header>
        <div class="logo-container">
            <img src="/images/logo.png" alt="BlokerCompany Logo" class="logo">
            <h1>BlokerCompany™</h1>
        </div>
        <div class="header-actions">
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </header>

    <main class="auth-main">
        <div class="auth-container">
            <div class="auth-form-container">
                <h2>Logowanie</h2>
                <p class="auth-subtitle">Witaj z powrotem! Zaloguj się, aby kontynuować.</p>
                
                <form id="login-form" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email lub nazwa użytkownika</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="email" name="email" placeholder="Wprowadź email lub nazwę użytkownika" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Hasło</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" placeholder="Wprowadź hasło" required>
                            <button type="button" class="toggle-password" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Zapamiętaj mnie</label>
                        </div>
                        <a href="reset-password.html" class="forgot-password">Zapomniałeś hasła?</a>
                    </div>
                    
                    <button type="submit" class="auth-button">Zaloguj się</button>
                    
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
                    <p>Nie masz jeszcze konta? <a href="register.html">Zarejestruj się</a></p>
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

    <footer>
        <div class="footer-content">
            <div class="copyright">
                Author: Jakub Michalik © / BlokerCompany™ 2025
            </div>
            <div class="footer-links">
                <a href="#">Regulamin</a>
                <a href="#">Polityka prywatności</a>
                <a href="#">Kontakt</a>
            </div>
        </div>
    </footer>

    <script src="/scripts/main.js"></script>
    <script src="/scripts/theme.js"></script>
    <script src="/scripts/auth.js"></script>
</body>
</html>

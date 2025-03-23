<?php

// Wczytywanie z danych z pliku config.json
$jsonData = file_get_contents('../../config.json');
$config = json_decode($jsonData, true);

// Sprawdzanie błędów wczytywania pliku JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Błąd wczytywania pliku konfiguracyjnego JSON.');
}
?>

<!-- Kod HTML -->
<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $config['pages'][$actualPage]['title']; ?></title>

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
    <link rel="stylesheet" href="<?php echo 'styles/' . $actualPage . ".css"; ?>">

</head>

<body data-theme="light">

    <header>
        <div class="logo-container">
            <img src="<?php echo $config['images']['logo']; ?>" alt="Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>
        <div class="search-container">
            <input type="text" id="search_bar_input_text" placeholder="Wyszukaj...">
            <button onclick="search()" class="search-button"><i class="fas fa-search"></i> Szukaj</button>
        </div>

        <!-- Gość -->
        <div class="header-actions guest-actions">
            <button class="login-button">Zaloguj</button>
            <button class="register-button">Zarejestruj</button>
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <!-- Zalogowany użytkownik -->
        <div class="header-actions user-actions" style="display: none;">
            <a href="<?php echo $config['pages']['profile-page']['path']; ?>" class="profile-link">
                <img src="<?php echo $config['images']['default-avatar']; ?>" alt="Avatar" class="user-avatar">
                <span class="username">Użytkownik</span>
            </a>
            <button class="logout-button">Wyloguj</button>
            <button id="theme-toggle-user" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>

    </header>

    <nav>
        <ul class="main-nav">
            <?php
            $navItems = [
                'welcome-page' => 'Strona główna',
                'drive-page' => 'Dysk',
                'social-page' => 'Społeczność',
                'newsletters-page' => 'Wiadomości',
                'gallery-page' => 'Galeria',
                'applications-page' => 'Aplikacje',
                'games-page' => 'Gry',
                'media-page' => 'Media',
                'settings-page' => 'Ustawienia'
            ];

            foreach ($navItems as $page => $label) {
                $isActive = ($page === $actualPage) ? ' class="active"' : '';
                echo '<li><a href="' . $config['pages'][$page]['path'] . '"' . $isActive . '>' . $label . '</a></li>';
            }
            ?>
        </ul>
    </nav>

    <!-- Zawartość podstrony -->

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['app']['author']; ?>. Wszelkie prawa zastrzeżone.</p>
            </div>
            <div class="footer-links">
                <a href="<?php echo $config['documents']['terms-of-service']; ?>">Regulamin</a>
                <a href="<?php echo $config['documents']['privacy-policy']; ?>">Polityka prywatności</a>
                <a href="<?php echo $config['documents']['contact']; ?>">Kontakt</a>
            </div>
        </div>
    </footer>

    <!-- Wczytywanie skryptów JS -->
    <script src="<?php echo $config['scripts']['main']; ?>"></script>
    <script src="<?php echo $config['scripts']['theme']; ?>"></script>
    <script src="<?php echo 'scripts/' . $actualPage . ".js"; ?>"></script>

</body>

</html>
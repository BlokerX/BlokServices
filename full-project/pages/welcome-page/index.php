<?php
// Dane bieżącej podstrony
$actualPage = 'welcome-page';

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
    <link rel="stylesheet" href="styles/welcome-page.css">

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

    <!-- #region Zawartość podstrony -->

    <aside class="left-sidebar">
        <div class="sidebar-header">
            <h3>Menu poboczne</h3>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#"><i class="fas fa-home"></i> Pulpit</a></li>
                <li><a href="<?php echo $config['pages']['profile-page']['path']; ?>"><i class="fas fa-user"></i> Profil</a></li>
                <li><a href="#"><i class="fas fa-bell"></i> Powiadomienia</a></li>
                <hr>
                <li><a href="#"><i class="fas fa-cog"></i> Preferencje</a></li>
            </ul>
        </div>
    </aside>

    <main>

        <section class="welcome-section">
            <h2>Witaj w <?php echo $config['app']['name']; ?>!</h2>
            <p>Twoje centrum multimedialne, społecznościowe i organizacyjne w jednym miejscu.</p>
            <div class="cta-buttons">
                <button class="cta-primary">Rozpocznij teraz</button>
                <button class="cta-secondary">Dowiedz się więcej</button>
            </div>
        </section>

        <section class="features-section">
            <h2>Odkryj nasze możliwości</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-cloud"></i>
                    <h3>Dysk</h3>
                    <p>Przechowuj i zarządzaj swoimi plikami z dowolnego miejsca.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>Społeczność</h3>
                    <p>Łącz się ze znajomymi i udostępniaj swoje doświadczenia.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-comment"></i>
                    <h3>Wiadomości</h3>
                    <p>Komunikuj się błyskawicznie poprzez nasz wbudowany komunikator.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-images"></i>
                    <h3>Galeria</h3>
                    <p>Organizuj i prezentuj swoje zdjęcia w eleganckiej galerii.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-calculator"></i>
                    <h3>Aplikacje</h3>
                    <p>Korzystaj z praktycznych narzędzi do codziennej organizacji.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-gamepad"></i>
                    <h3>Gry</h3>
                    <p>Odpręż się grając w nasze gry online.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-film"></i>
                    <h3>Media</h3>
                    <p>Oglądaj filmy, słuchaj muzyki i czytaj książki w jednym miejscu.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-cog"></i>
                    <h3>Ustawienia</h3>
                    <p>Dostosuj platformę do swoich preferencji.</p>
                </div>
            </div>
        </section>
        
    </main>

    <aside class="right-sidebar">

        <div class="sidebar-header">
            <h3>Twoje skróty</h3>
        </div>

        <div class="sidebar-content">
            <div class="trending-posts">

                <div class="trending-post">
                    <h4>Nowy album muzyczny</h4>
                    <p>Sprawdź najnowsze utwory</p>
                </div>

                <div class="trending-post">
                    <h4>Aktualizacja platformy</h4>
                    <p>Zobacz co nowego</p>
                </div>

                <div class="trending-post">
                    <h4>Wydarzenia w tym tygodniu</h4>
                    <p>Zaplanuj swój czas</p>
                </div>

            </div>
        </div>

    </aside>

    <!-- #endregion -->

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
    <script src="scripts/welcome-page.js"></script>
    <script src="<?php echo $config['scripts']['theme']; ?>"></script>

</body>

</html>
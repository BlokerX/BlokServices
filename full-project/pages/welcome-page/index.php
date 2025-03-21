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

    <title><?php echo $config['pages']['welcome-page']['title']; ?></title>

    <?php echo '<meta name="description" content="' . $config['app']['description'] . '">' ?>
    <?php echo '<meta name="author" content="' . $config['app']['author'] . '">' ?>
    <?php echo '<meta name="application-name" content="' . $config['app']['name'] . '">' ?>

    <link rel="icon" href="images/favicon.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php echo '<link rel="stylesheet" href="' . $config['pages']['welcome-page']['styles']['main'] . '">' ?>
    <?php echo '<link rel="stylesheet" href="' . $config['pages']['welcome-page']['styles']['themes'] . '">' ?>

</head>

<body data-theme="light">

    <header>
        <div class="logo-container">
            <img src="images/logo.png" alt="BlokerCompany Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>
        <div class="search-container">
            <input type="text" id="search_bar_input_text" placeholder="Wyszukaj...">
            <button onclick="search()" class="search-button"><i class="fas fa-search"></i> Szukaj</button>
        </div>
        <div class="header-actions">
            <button class="login-button">Zaloguj</button>
            <button class="register-button">Zarejestruj</button>
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </header>

    <nav>
        <ul class="main-nav">
            <?php
            echo '<li><a href="' . $config['pages']['welcome-page']['path'] . '" class="active">Strona główna</a></li>';
            echo '<li><a href="' . $config['pages']['drive-page']['path'] . '">Dysk</a></li>';
            echo '<li><a href="' . $config['pages']['social-page']['path'] . '">Social</a></li>';
            echo '<li><a href="' . $config['pages']['newsletters-page']['path'] . '">Wiadomości</a></li>';
            echo '<li><a href="' . $config['pages']['gallery-page']['path'] . '">Galeria</a></li>';
            echo '<li><a href="' . $config['pages']['applications-page']['path'] . '">Aplikacje</a></li>';
            echo '<li><a href="' . $config['pages']['games-page']['path'] . '">Gry</a></li>';
            echo '<li><a href="' . $config['pages']['media-page']['path'] . '">Media</a></li>';
            echo '<li><a href="' . $config['pages']['settings-page']['path'] . '">Ustawienia</a></li>';
            ?>
        </ul>
    </nav>

    <aside class="left-sidebar">
        <div class="sidebar-header">
            <h3>Menu poboczne</h3>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#"><i class="fas fa-home"></i> Pulpit</a></li>
                <li><a href="#"><i class="fas fa-user"></i> Profil</a></li>
                <li><a href="#"><i class="fas fa-bell"></i> Powiadomienia</a></li>
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
            <h3>Popularne</h3>
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

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <?php echo $config['app']['author']; ?>
            </div>
            <div class="footer-links">
                <?php
                echo '<a href="' . $config['documents']['terms-of-service'] . '">Regulamin</a>';
                echo '<a href="' . $config['documents']['privacy-policy'] . '">Polityka prywatności</a>';
                echo '<a href="' . $config['documents']['contact'] . '">Kontakt</a>';
                ?>
            </div>
        </div>
    </footer>

    <?php
    // Wczytywanie skryptów JS
    echo '<script src="' . $config['pages']['welcome-page']['scripts']['main'] . '"></script>';
    echo '<script src="' . $config['pages']['welcome-page']['scripts']['theme'] . '"></script>';
    ?>

</body>

</html>
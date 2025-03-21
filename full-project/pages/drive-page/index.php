<?php
// Wczytywanie danych z pliku config.json
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

    <title><?php echo $config['pages']['drive-page']['title']; ?></title>

    <?php echo '<meta name="description" content="' . $config['app']['description'] . '">' ?>
    <?php echo '<meta name="author" content="' . $config['app']['author'] . '">' ?>
    <?php echo '<meta name="application-name" content="' . $config['app']['name'] . '">' ?>

    <link rel="icon" href="images/favicon.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <?php echo '<link rel="stylesheet" href="' . $config['pages']['drive-page']['styles']['main'] . '">' ?>
    <?php echo '<link rel="stylesheet" href="' . $config['pages']['drive-page']['styles']['themes'] . '">' ?>

</head>

<body data-theme="light">

    <!-- Nagłówek -->
    <header>
        <div class="logo-container">
            <img src="images/logo.png" alt="BlokerCompany Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>
        <div class="search-container">
            <input type="text" id="search_bar_input_text" placeholder="Wyszukaj pliki...">
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

    <!-- Nawigacja -->
    <nav>
        <ul class="main-nav">
            <?php
            echo '<li><a href="' . $config['pages']['drive-page']['path'] . '" class="active">Moje pliki</a></li>';
            echo '<li><a href="' . $config['pages']['social-page']['path'] . '">Społeczność</a></li>';
            echo '<li><a href="' . $config['pages']['newsletters-page']['path'] . '">Wiadomości</a></li>';
            echo '<li><a href="' . $config['pages']['gallery-page']['path'] . '">Galeria</a></li>';
            echo '<li><a href="' . $config['pages']['applications-page']['path'] . '">Aplikacje</a></li>';
            echo '<li><a href="' . $config['pages']['games-page']['path'] . '">Gry</a></li>';
            echo '<li><a href="' . $config['pages']['media-page']['path'] . '">Media</a></li>';
            echo '<li><a href="' . $config['pages']['settings-page']['path'] . '">Ustawienia</a></li>';
            ?>
        </ul>
    </nav>

    <!-- Lewa Strona - Menu -->
    <aside class="left-sidebar">
        <div class="sidebar-header">
            <h3>Foldery</h3>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#"><i class="fas fa-folder"></i> Moje pliki</a></li>
                <li><a href="#"><i class="fas fa-folder-open"></i> Udostępnione ze mną</a></li>
                <li><a href="#"><i class="fas fa-trash"></i> Kosz</a></li>
                <li><a href="#"><i class="fas fa-cogs"></i> Ustawienia</a></li>
            </ul>
        </div>
    </aside>

    <!-- Główna Sekcja -->
    <main>
        <section class="drive-section">
            <h2>Moje Pliki</h2>

            <!-- Panel narzędzi do tworzenia nowych plików -->
            <div class="tools-panel">
                <button class="cta-primary"><i class="fas fa-plus"></i> Nowy folder</button>
                <button class="cta-secondary"><i class="fas fa-upload"></i> Wgraj pliki</button>
            </div>

            <!-- Siatka plików -->
            <div class="files-grid">
                <div class="file-card">
                    <i class="fas fa-folder"></i>
                    <h3>Folder Dokumenty</h3>
                    <p>3 pliki</p>
                </div>
                <div class="file-card">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Raport.pdf</h3>
                    <p>2MB</p>
                </div>
                <div class="file-card">
                    <i class="fas fa-file-excel"></i>
                    <h3>Budżet.xlsx</h3>
                    <p>1.5MB</p>
                </div>
                <div class="file-card">
                    <i class="fas fa-image"></i>
                    <h3>Obrazek.jpg</h3>
                    <p>500KB</p>
                </div>
                <div class="file-card">
                    <i class="fas fa-file-video"></i>
                    <h3>Wideo.mp4</h3>
                    <p>30MB</p>
                </div>
                <div class="file-card">
                    <i class="fas fa-file-word"></i>
                    <h3>Oferta.docx</h3>
                    <p>600KB</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Prawa Strona - Popularne -->
    <aside class="right-sidebar">
        <div class="sidebar-header">
            <h3>Popularne</h3>
        </div>
        <div class="sidebar-content">
            <div class="trending-posts">
                <div class="trending-post">
                    <h4>Współdzielone pliki</h4>
                    <p>Sprawdź pliki udostępnione przez innych.</p>
                </div>
                <div class="trending-post">
                    <h4>Wiadomości</h4>
                    <p>Nowe wiadomości od znajomych.</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Stopka -->
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

    <!-- Wczytywanie skryptów JS -->
    <?php
    echo '<script src="' . $config['pages']['drive-page']['scripts']['main'] . '"></script>';
    echo '<script src="' . $config['pages']['drive-page']['scripts']['theme'] . '"></script>';
    ?>

</body>

</html>

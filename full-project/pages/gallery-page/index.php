<?php
// Wczytywanie z danych z pliku config.json
$jsonData = file_get_contents('../../config.json');
$config = json_decode($jsonData, true);

// Sprawdzanie błędów wczytywania pliku JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Błąd wczytywania pliku konfiguracyjnego JSON.');
}

// Wczytywanie zdjęć z galerii (przykładowe dane w pliku JSON)
$galleryImages = $config['gallery']['images']; // zakładając, że zdjęcia są zapisane w config.json
?>

<!-- Kod HTML -->
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['pages']['gallery-page']['title']; ?></title>
    <?php echo '<meta name="description" content="' . $config['app']['description'] . '">' ?>
    <?php echo '<meta name="author" content="' . $config['app']['author'] . '">' ?>
    <?php echo '<meta name="application-name" content="' . $config['app']['name'] . '">' ?>
    <link rel="icon" href="images/favicon.png" type="image/png">
    <link rel="stylesheet" href="<?php echo $config['pages']['gallery-page']['styles']['main']; ?>">
    <link rel="stylesheet" href="<?php echo $config['pages']['gallery-page']['styles']['themes']; ?>">
    <link rel="stylesheet" href="styles/gallery-page.css">
</head>

<body data-theme="light">

    <header>
        <div class="logo-container">
            <img src="images/logo.png" alt="BlokerCompany Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>
        <!-- Search and other actions here -->
    </header>

    <nav>
        <!-- Navigation Links Here -->
    </nav>

    <main>
        <section class="gallery-section">
            <h2>Galeria</h2>
            <div class="gallery-grid">
                <?php foreach ($galleryImages as $image): ?>
                    <div class="gallery-item">
                        <img src="<?php echo $image['src']; ?>" alt="<?php echo $image['alt']; ?>">
                        <p><?php echo $image['caption']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <!-- Footer Content Here -->
    </footer>

    <script src="<?php echo $config['pages']['gallery-page']['scripts']['main']; ?>"></script>
    <script src="<?php echo $config['pages']['gallery-page']['scripts']['theme']; ?>"></script>

</body>
</html>

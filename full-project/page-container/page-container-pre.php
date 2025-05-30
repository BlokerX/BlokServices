<?php
// Sprawdź czy użytkownik jest zalogowany
$is_logged_in = false; // Zmienna do sprawdzenia, czy użytkownik jest zalogowany

// Sprawdź, czy sesja jest już rozpoczęta
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Rozpocznij sesję, jeśli nie jest już rozpoczęta
}

if (!isset($_SESSION['user_id'])) {
    // // Jeśli nie jest zalogowany, przekieruj na stronę logowania
    // header('Location: ' . $config['pages']['login-page']['path']);
    // exit;
    $is_logged_in = false;
}
else {
    $is_logged_in = true;
}

?>

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

    <!-- Wczytywanie JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Wczytywanie styli CSS -->
    <link rel="stylesheet" href="<?php echo $config['styles']['main']; ?>">
    <link rel="stylesheet" href="<?php echo '../../controls/popup-window/popup-window.css'; ?>">
    <link rel="stylesheet" href="<?php echo $config['styles']['themes']; ?>">
    <link rel="stylesheet" href="<?php echo 'styles/' . $actualPage . ".css"; ?>">

</head>

<body data-theme="light">

    <header>
        <div class="logo-container">
            <img src="<?php echo $config['images']['logo']; ?>" alt="Logo" class="logo">
            <h1><?php echo $config['app']['name']; ?></h1>
        </div>

        <?php

        // Search bar
        if ($is_logged_in == true) {
            echo '
        <div class="search-container">
            <input type="text" id="search_bar_input_text" placeholder="Wyszukaj...">
            <button onclick="search()" class="search-button"><i class="fas fa-search"></i> Szukaj</button>
        </div>';
        }

        // Header actions
        if ($is_logged_in == false) {
            echo '
        <!-- Gość -->
        <div class="header-actions guest-actions">
            <button class="login-button">Zaloguj</button>
            <button class="register-button">Zarejestruj</button>
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>';
        }
        else if ($is_logged_in == true) {
            echo '
        <!-- Zalogowany użytkownik -->
        <div class="header-actions user-actions">
            <a href="'.$config['pages']['profile-page']['path'].'" class="profile-link">
                <img src="'.$_SESSION['user_avatar'].'" alt="Avatar" class="mini-user-avatar">
            </a>
            <button class="logout-button">Wyloguj</button>
            <button id="theme-toggle" class="theme-toggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>
        ';
        }
        
        ?>

    </header>

<?php
// Navigation bar
if($is_logged_in == true) {
include 'navigation/nav.php';
}
?>
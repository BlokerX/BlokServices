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

<?php include 'navigation/nav.php'; ?>
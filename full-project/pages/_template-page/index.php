<?php
// Informacje indywidualne dla obecnej podstrony:
$actualPage = '_template-page';

// _przestarzałe:
// // Główna struktura strony (szablon)
// include '../../page-container/page-container.php';
// // Zawartość strony
// include 'content.php';

// Wczytanie konfiguracji JSON
include '../../page-container/json-config-load.php';

// Wczytanie struktury strony
include '../../page-container/page-container-pre.php';
include 'content.php';
include '../../page-container/page-container-post.php';
?>
<?php
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Informacje indywidualne dla obecnej podstrony:
$actualPage = 'search-page';

// Wczytanie konfiguracji JSON
include '../../page-container/json-config-load.php';

// Wczytanie struktury strony
include '../../page-container/page-container-pre.php';
include 'content.php';
include '../../page-container/page-container-post.php';
?>
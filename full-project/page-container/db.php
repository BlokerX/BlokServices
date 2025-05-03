<?php
$conn = null; // Inicjalizacja zmiennej połączenia

function connect_db($config) {
    global $conn;
    // Sprawdzenie, czy połączenie już istnieje
    if ($conn) {
        return $conn;
    }
    // Połączenie z bazą danych
    $conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
    if (!$conn) {
        $conn = null; // Ustawienie na null w przypadku błędu
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function close_db() {
    global $conn;
    mysqli_close($conn);
}

?>
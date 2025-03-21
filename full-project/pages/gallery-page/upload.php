<?php
// Sprawdzanie, czy plik został przesłany
if (isset($_FILES['image'])) {
    $uploadDirectory = "uploads/";
    $uploadedFile = $uploadDirectory . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($uploadedFile, PATHINFO_EXTENSION));

    // Sprawdzanie, czy plik jest obrazem
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        echo "To nie jest plik graficzny.";
        exit;
    }

    // Sprawdzanie, czy plik już istnieje
    if (file_exists($uploadedFile)) {
        echo "Plik o tej nazwie już istnieje.";
        exit;
    }

    // Sprawdzanie rozmiaru pliku (do 5MB)
    if ($_FILES['image']['size'] > 5000000) {
        echo "Plik jest za duży.";
        exit;
    }

    // Dozwolone formaty plików
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Dozwolone pliki to JPG, JPEG, PNG i GIF.";
        exit;
    }

    // Przeniesienie pliku do folderu docelowego
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFile)) {
        echo "Plik " . basename($_FILES['image']['name']) . " został przesłany.";
        header("Location: gallery-page.php"); // Przekierowanie do strony galerii
    } else {
        echo "Wystąpił błąd podczas przesyłania pliku.";
    }
}
?>

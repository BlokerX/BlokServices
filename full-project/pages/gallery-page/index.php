<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaawansowana Galeria Obrazów</title>
    <link rel="stylesheet" href="style-css.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Zaawansowana Galeria Obrazów</h1>
            <p>Kliknij na dowolny obraz, aby wyświetlić go w trybie pełnoekranowym</p>
        </header>
        
        <div class="controls">
            <button class="filter-btn active" data-filter="all">Wszystkie</button>
            <button class="filter-btn" data-filter="natura">Natura</button>
            <button class="filter-btn" data-filter="architektura">Architektura</button>
            <button class="filter-btn" data-filter="podroze">Podróże</button>
        </div>
        
        <div class="gallery" id="gallery">
            <!-- Elementy galerii będą generowane dynamicznie -->
        </div>
    </div>
    
    <div class="lightbox" id="lightbox">
        <div class="lightbox-content">
            <button class="lightbox-close" id="lightbox-close">&times;</button>
            <img src="" alt="" class="lightbox-img" id="lightbox-img">
            <div class="lightbox-nav">
                <button id="prev-btn">&lt;</button>
                <button id="next-btn">&gt;</button>
            </div>
            <div class="lightbox-caption" id="lightbox-caption"></div>
            <div class="thumbnails" id="thumbnails">
                <!-- Miniatury będą generowane dynamicznie -->
            </div>
        </div>
    </div>

    <script src="gallery-data-js.js"></script>
    <script src="script-js.js"></script>
</body>
</html>

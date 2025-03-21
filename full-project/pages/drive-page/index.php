<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dysk - Przechowywanie plików</title>
    <link rel="stylesheet" href="styles-css.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="logo">
                <img src="/api/placeholder/24/24" alt="Logo" />
                <span>Dysk</span>
            </div>
            
            <button class="create-btn">
                <img src="/api/placeholder/24/24" alt="Plus" />
                <span>Utwórz</span>
            </button>
            
            <nav>
                <div class="nav-item active">
                    <img src="/api/placeholder/20/20" alt="Mój dysk" />
                    <span>Mój dysk</span>
                </div>
                <div class="nav-item">
                    <img src="/api/placeholder/20/20" alt="Udostępnione dla mnie" />
                    <span>Udostępnione dla mnie</span>
                </div>
                <div class="nav-item">
                    <img src="/api/placeholder/20/20" alt="Ostatnie" />
                    <span>Ostatnie</span>
                </div>
                <div class="nav-item">
                    <img src="/api/placeholder/20/20" alt="Oznaczone gwiazdką" />
                    <span>Oznaczone gwiazdką</span>
                </div>
                <div class="nav-item">
                    <img src="/api/placeholder/20/20" alt="Kosz" />
                    <span>Kosz</span>
                </div>
            </nav>
            
            <div class="divider"></div>
            
            <div class="storage-info">
                <span>Miejsce w pamięci: 15% z 15 GB wykorzystane</span>
                <div class="storage-bar">
                    <div class="storage-used"></div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <div class="header">
                <div class="search-bar">
                    <img src="/api/placeholder/20/20" alt="Szukaj" class="search-icon" />
                    <input type="text" placeholder="Szukaj w Dysku" />
                </div>
                <div class="header-icons">
                    <div class="header-icon">
                        <img src="/api/placeholder/20/20" alt="Ustawienia" />
                    </div>
                    <div class="header-icon">
                        <img src="/api/placeholder/20/20" alt="Aplikacje" />
                    </div>
                    <div class="header-icon">
                        <img src="/api/placeholder/20/20" alt="Profil" />
                    </div>
                </div>
            </div>
            
            <div class="view-options">
                <div class="view-selector">
                    <div class="view-btn active" id="gridView">
                        <img src="/api/placeholder/20/20" alt="Widok siatki" />
                    </div>
                    <div class="view-btn" id="listView">
                        <img src="/api/placeholder/20/20" alt="Widok listy" />
                    </div>
                </div>
                <div class="sort-options">
                    <span>Sortuj wg:</span>
                    <span class="sort-by" id="sortName">Nazwa</span>
                </div>
            </div>
            
            <div class="files-section">
                <h2 class="section-header">Ostatnio otwierane</h2>
                <div class="files-grid" id="recentFiles">
                    <!-- Files will be added dynamically by JavaScript -->
                </div>
            </div>
            
            <div class="files-section">
                <h2 class="section-header">Wszystkie pliki</h2>
                <div class="files-grid" id="allFiles">
                    <!-- Files will be added dynamically by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Context Menu -->
    <div class="context-menu" id="contextMenu">
        <div class="menu-item">
            <img src="/api/placeholder/16/16" alt="Pobierz" />
            <span>Pobierz</span>
        </div>
        <div class="menu-item">
            <img src="/api/placeholder/16/16" alt="Udostępnij" />
            <span>Udostępnij</span>
        </div>
        <div class="menu-item">
            <img src="/api/placeholder/16/16" alt="Zmień nazwę" />
            <span>Zmień nazwę</span>
        </div>
        <div class="menu-item">
            <img src="/api/placeholder/16/16" alt="Przenieś do kosza" />
            <span>Przenieś do kosza</span>
        </div>
    </div>

    <!-- Modal for file upload -->
    <div class="modal" id="uploadModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Prześlij plik</h3>
                <span class="close-modal">&times;</span>
            </div>
            <div class="modal-body">
                <div class="upload-area" id="dropArea">
                    <img src="/api/placeholder/48/48" alt="Upload" />
                    <p>Przeciągnij i upuść pliki tutaj lub</p>
                    <label for="fileInput" class="upload-btn">Wybierz pliki</label>
                    <input type="file" id="fileInput" multiple hidden />
                </div>
                <div class="upload-progress" id="uploadProgress">
                    <div class="progress-bar">
                        <div class="progress"></div>
                    </div>
                    <span class="progress-text">0%</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn">Anuluj</button>
                <button class="upload-submit-btn">Prześlij</button>
            </div>
        </div>
    </div>

    <script src="script-js.js"></script>
</body>
</html>

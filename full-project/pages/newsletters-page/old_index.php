<?php
// Dane bieżącej podstrony
$actualPage = 'newsletters-page';

// Wczytywanie z danych z pliku config.json
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

    <title><?php echo $config['pages'][$actualPage]['title']; ?></title>

    <meta name="description" content="<?php echo $config['app']['description']; ?>">
    <meta name="author" content="<?php echo $config['app']['author']; ?>">
    <meta name="application-name" content="<?php echo $config['app']['name']; ?>">

    <link rel="icon" href="<?php echo $config['images']['favicon'] ?>" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $config['styles']['main']; ?>">
    <link rel="stylesheet" href="<?php echo $config['styles']['themes']; ?>">
    <link rel="stylesheet" href="styles/newsletters-page.css">

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

    <nav>
        <ul class="main-nav">
            <?php
            $navItems = [
                'welcome-page' => 'Strona główna',
                'drive-page' => 'Dysk',
                'social-page' => 'Społeczność',
                'newsletters-page' => 'Wiadomości',
                'gallery-page' => 'Galeria',
                'applications-page' => 'Aplikacje',
                'games-page' => 'Gry',
                'media-page' => 'Media',
                'settings-page' => 'Ustawienia'
            ];

            foreach ($navItems as $page => $label) {
                $isActive = ($page === $actualPage) ? ' class="active"' : '';
                echo '<li><a href="' . $config['pages'][$page]['path'] . '"' . $isActive . '>' . $label . '</a></li>';
            }
            ?>
        </ul>
    </nav>

    <!-- #region Zawartość podstrony -->

    <aside class="left-sidebar">
        <div class="sidebar-header">
            <h3>Kategorie</h3>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#" class="category-link" data-category="all"><i class="fas fa-inbox"></i> Wszystkie wiadomości</a></li>
                <li><a href="#" class="category-link" data-category="unread"><i class="fas fa-envelope"></i> Nieprzeczytane</a></li>
                <li><a href="#" class="category-link" data-category="important"><i class="fas fa-star"></i> Ważne</a></li>
                <hr>
                <li><a href="#" class="category-link" data-category="announcements"><i class="fas fa-bullhorn"></i> Ogłoszenia</a></li>
                <li><a href="#" class="category-link" data-category="promotion"><i class="fas fa-tags"></i> Promocje</a></li>
                <li><a href="#" class="category-link" data-category="updates"><i class="fas fa-sync"></i> Aktualizacje</a></li>
                <li><a href="#" class="category-link" data-category="social"><i class="fas fa-users"></i> Społeczność</a></li>
                <hr>
                <li><a href="#" id="subscribe-button"><i class="fas fa-plus-circle"></i> Subskrybuj nowy</a></li>
                <li><a href="#" id="manage-button"><i class="fas fa-cog"></i> Zarządzaj subskrypcjami</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <section class="newsletters-header">
            <h2>Wiadomości</h2>
            <div class="newsletters-actions">
                <button class="refresh-button"><i class="fas fa-sync-alt"></i> Odśwież</button>
                <div class="view-options">
                    <button class="view-option active" data-view="cards"><i class="fas fa-th-large"></i></button>
                    <button class="view-option" data-view="list"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </section>

        <section class="newsletter-filters">
            <div class="filter-container">
                <label for="filter-sort">Sortuj:</label>
                <select id="filter-sort">
                    <option value="newest">Najnowsze</option>
                    <option value="oldest">Najstarsze</option>
                    <option value="alphabetical">Alfabetycznie</option>
                </select>
            </div>
            <div class="search-newsletters">
                <input type="text" id="newsletter-search" placeholder="Filtruj wiadomości...">
                <button class="search-newsletters-btn"><i class="fas fa-search"></i></button>
            </div>
        </section>

        <section class="newsletters-content">
            <div class="newsletters-grid" id="newsletters-container">
                <!-- Przykładowe newslettery -->
                <div class="newsletter-item" data-category="announcements">
                    <div class="newsletter-label important">Ważne</div>
                    <div class="newsletter-badge unread"></div>
                    <div class="newsletter-header">
                        <i class="fas fa-bullhorn newsletter-icon"></i>
                        <span class="newsletter-date">23.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Aktualizacja regulaminu platformy</h3>
                    <p class="newsletter-excerpt">Informujemy o wprowadzeniu zmian w regulaminie korzystania z naszej platformy, które wejdą w życie od 1 kwietnia 2025...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="newsletter-item" data-category="updates">
                    <div class="newsletter-badge unread"></div>
                    <div class="newsletter-header">
                        <i class="fas fa-sync newsletter-icon"></i>
                        <span class="newsletter-date">22.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Nowe funkcje aplikacji</h3>
                    <p class="newsletter-excerpt">Sprawdź nowe funkcje, które dodaliśmy w ostatniej aktualizacji. Teraz możesz korzystać z zaawansowanych opcji...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="newsletter-item" data-category="promotion">
                    <div class="newsletter-label sale">Promocja</div>
                    <div class="newsletter-header">
                        <i class="fas fa-tags newsletter-icon"></i>
                        <span class="newsletter-date">20.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Wiosenna promocja -50%</h3>
                    <p class="newsletter-excerpt">Skorzystaj z naszej wiosennej promocji i otrzymaj 50% zniżki na subskrypcję premium przez pierwszy miesiąc...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="newsletter-item" data-category="social">
                    <div class="newsletter-header">
                        <i class="fas fa-users newsletter-icon"></i>
                        <span class="newsletter-date">18.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Trendy tygodnia w społeczności</h3>
                    <p class="newsletter-excerpt">Sprawdź, co było najbardziej popularne w naszej społeczności w ostatnim tygodniu. Odkryj nowe trendy...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="newsletter-item" data-category="announcements">
                    <div class="newsletter-header">
                        <i class="fas fa-bullhorn newsletter-icon"></i>
                        <span class="newsletter-date">15.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Nowe partnerstwo z Przykładową Firmą</h3>
                    <p class="newsletter-excerpt">Z przyjemnością informujemy o rozpoczęciu współpracy z Przykładową Firmą, dzięki czemu będziemy mogli zaoferować...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>

                <div class="newsletter-item" data-category="promotion">
                    <div class="newsletter-label exclusive">Ekskluzywne</div>
                    <div class="newsletter-header">
                        <i class="fas fa-tags newsletter-icon"></i>
                        <span class="newsletter-date">12.03.2025</span>
                    </div>
                    <h3 class="newsletter-title">Oferta tylko dla stałych użytkowników</h3>
                    <p class="newsletter-excerpt">Przygotowaliśmy specjalną ofertę dla naszych stałych użytkowników. Sprawdź, jakie korzyści możesz uzyskać...</p>
                    <div class="newsletter-footer">
                        <button class="read-more-btn">Czytaj więcej</button>
                        <div class="newsletter-actions">
                            <button class="star-btn"><i class="far fa-star"></i></button>
                            <button class="save-btn"><i class="far fa-bookmark"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pagination">
                <button class="pagination-prev" disabled><i class="fas fa-chevron-left"></i></button>
                <span class="pagination-info">Strona 1 z 3</span>
                <button class="pagination-next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>

        <div class="newsletter-modal" id="newsletter-detail-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="fas fa-bullhorn newsletter-icon"></i>
                        <h3>Aktualizacja regulaminu platformy</h3>
                    </div>
                    <button class="modal-close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="newsletter-info">
                        <span class="newsletter-author">Zespół Administracji</span>
                        <span class="newsletter-date">23.03.2025</span>
                    </div>
                    <div class="newsletter-content">
                        <p>Szanowni Użytkownicy,</p>
                        <p>Informujemy o wprowadzeniu zmian w regulaminie korzystania z naszej platformy, które wejdą w życie od 1 kwietnia 2025 roku.</p>
                        <p>Najważniejsze zmiany obejmują:</p>
                        <ul>
                            <li>Aktualizację polityki prywatności zgodnie z najnowszymi przepisami</li>
                            <li>Nowe zasady dotyczące treści publikowanych przez użytkowników</li>
                            <li>Zmiany w warunkach korzystania z funkcji premium</li>
                        </ul>
                        <p>Zachęcamy do zapoznania się z pełną treścią zaktualizowanego regulaminu, dostępnego pod adresem: <a href="#">link do regulaminu</a>.</p>
                        <p>Jeśli masz jakiekolwiek pytania dotyczące wprowadzonych zmian, skontaktuj się z naszym działem obsługi klienta.</p>
                        <p>Z poważaniem,<br>Zespół Administracji</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="modal-action-btn primary-btn">Zaakceptuj zmiany</button>
                    <button class="modal-action-btn secondary-btn">Zapisz do przeczytania później</button>
                </div>
            </div>
        </div>

        <div class="newsletter-modal" id="subscribe-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <i class="fas fa-plus-circle"></i>
                        <h3>Subskrybuj nowy newsletter</h3>
                    </div>
                    <button class="modal-close"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="newsletter-category">Kategoria:</label>
                        <select id="newsletter-category">
                            <option value="announcements">Ogłoszenia</option>
                            <option value="promotion">Promocje</option>
                            <option value="updates">Aktualizacje</option>
                            <option value="social">Społeczność</option>
                            <option value="technology">Technologia</option>
                            <option value="science">Nauka</option>
                            <option value="culture">Kultura</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newsletter-frequency">Częstotliwość:</label>
                        <select id="newsletter-frequency">
                            <option value="daily">Codziennie</option>
                            <option value="weekly" selected>Co tydzień</option>
                            <option value="monthly">Co miesiąc</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notification-preferences">Powiadomienia:</label>
                        <div class="checkbox-group">
                            <label>
                                <input type="checkbox" checked> Powiadomienia e-mail
                            </label>
                            <label>
                                <input type="checkbox" checked> Powiadomienia w aplikacji
                            </label>
                            <label>
                                <input type="checkbox"> Powiadomienia push
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="modal-action-btn primary-btn">Subskrybuj</button>
                    <button class="modal-action-btn secondary-btn modal-close-btn">Anuluj</button>
                </div>
            </div>
        </div>
    </main>

    <aside class="right-sidebar">
        <div class="sidebar-header">
            <h3>Popularne wiadomości</h3>
        </div>
        <div class="sidebar-content">
            <div class="trending-posts">
                <div class="trending-post">
                    <h4>Aktualizacja aplikacji mobilnej</h4>
                    <p>45 komentarzy</p>
                </div>

                <div class="trending-post">
                    <h4>Nowe funkcje w galerii</h4>
                    <p>32 komentarze</p>
                </div>

                <div class="trending-post">
                    <h4>Konkurs dla społeczności</h4>
                    <p>27 komentarzy</p>
                </div>
            </div>

            <div class="sidebar-section">
                <h4>Statystyki subskrypcji</h4>
                <div class="subscription-stats">
                    <div class="stat-item">
                        <span class="stat-label">Subskrypcje:</span>
                        <span class="stat-value">8</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Nieprzeczytane:</span>
                        <span class="stat-value">2</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Ostatnia aktualizacja:</span>
                        <span class="stat-value">Dzisiaj, 08:45</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- #endregion -->

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['app']['author']; ?>. Wszelkie prawa zastrzeżone.</p>
            </div>
            <div class="footer-links">
                <a href="<?php echo $config['documents']['terms-of-service']; ?>">Regulamin</a>
                <a href="<?php echo $config['documents']['privacy-policy']; ?>">Polityka prywatności</a>
                <a href="">Kontakt</a>
            </div>
        </div>
    </footer>

    <!-- Wczytywanie skryptów JS -->
    <script src="<?php echo $config['scripts']['main']; ?>"></script>
    <script src="scripts/newsletters-page.js"></script>
    <script src="<?php echo $config['scripts']['theme']; ?>"></script>

</body>

</html>
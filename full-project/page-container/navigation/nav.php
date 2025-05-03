<nav>
    <ul class="main-nav">
        <?php
        $navItems = [
            'welcome-page' => 'Strona główna',
            'drive-page' => 'Dysk',
            'social-page' => 'Społeczność',
            'newsletters-page' => 'Newslettery',
            'chat-page' => 'Wiadomości',
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
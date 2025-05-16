<!-- #region Zawartość podstrony -->

<aside class="left-sidebar">
    <div class="sidebar-header">
        <h3>Szukaj na stronie</h3>
    </div>
    <div class="sidebar-content">
        <ul class="sidebar-menu">
            <li><a href="https://www.google.com"><i class="fab fa-google"></i> Google</a></li>
            <li><a href="https://www.bing.com"><i class="fab fa-microsoft"></i> Bing</a></li>
            <li><a href="https://www.yahoo.com"><i class="fab fa-yahoo"></i> Yahoo</a></li>
        </ul>
    </div>
</aside>

<main>

    <section class="search-bar-section">
        <h2>Wyszukaj w zasobach serwisu</h2>
        <div class="search-container">
            <form action="index.php" method="get">
                <input type="text" id="search_bar_input_text" placeholder="Wpisz szukaną frazę..." name="search" value="<?php echo $search; ?>">
                <button type="submit" class="search-button"><i class="fas fa-search"></i> Szukaj</button>
            </form>
        </div>
    </section>

    <?php
    if ($search != '') {
        echo '<section class="search-suggestion">
            <div class="search-header">
            <a href="https://www.google.com/search?q=' . $search . '"><h3>Wyszukaj "' . htmlspecialchars($search) . '" w Google</h3></a>
            </div>
        </section>';
    }
    ?>

    <section class="search-results-section">
        <h2>Wyniki wyszukiwania:</h2>
        <div class="search-results-grid">

            <?php
            // Połączenie z bazą danych
            $conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $search = $_GET['search'] ?? '';
            $search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); // zabezpieczenie przed XSS
            $search = trim($search); // usunięcie zbędnych spacji
            $search = preg_replace('/\s+/', ' ', $search); // usunięcie nadmiarowych spacji
            $search = strtolower($search); // zamiana na małe litery
            $search = mysqli_real_escape_string($conn, $search); // zabezpieczenie przed SQL Injection
            
            // Wyszukaj w użytkownikach
            $query = "SELECT * FROM users 
            WHERE login LIKE '%$search%' 
            OR name LIKE '%$search%'
            OR last_name LIKE '%$search%'
            OR description LIKE '%$search%'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="search-results-grid"><a href="'.$config['pages']['profile-page']['path'].'?user_name=' . $row['login'] . '">';
                    echo '<h3><i class="fas fa-search"></i> Użytkownik: ' . htmlspecialchars($row['login']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '</a></div>';
                }
            }

            // Wyszukaj w postach
            $query = "SELECT * FROM posts LEFT JOIN users ON posts.owner_user_id = users.id
            WHERE title LIKE '%$search%'
            OR users.login LIKE '$search'
            OR content LIKE '%$search%'";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="search-results-grid"><a href="'.$config['pages']['post-page']['path'].'?post_id=' . $row['id'] . '">';
                    echo '<h3><i class="fas fa-search"></i> Post: ' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['content']) . '</p>';
                    echo '</a></div>';
                }
            }

            // Wyszukaj w komentarzach
            $query = "SELECT * FROM posts_comments LEFT JOIN users ON posts_comments.comment_author_id = users.id
            WHERE posts_comments.content LIKE '%$search%' OR users.login LIKE '$search'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="search-results-grid"><a href="'.$config['pages']['post-page']['path'].'?post_id=' . $row['post_id'] . '">';
                    echo '<h3><i class="fas fa-search"></i> Komentarz do posta: ' . htmlspecialchars($row['content']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['content']) . '</p>';
                    echo '</a></div>';
                }
            }

            // Wyszukaj w grach
            $query = "SELECT * FROM games 
            WHERE title LIKE '%$search%'
            OR description LIKE '%$search%'
            OR type LIKE '%$search%'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="search-results-grid"><a href="'.$config['pages']['games-page']['path'].'?game_id=' . $row['id'] . '">';
                    echo '<h3><i class="fas fa-search"></i> Gra: ' . htmlspecialchars($row['title']) . '</h3>';
                    echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                    echo '</a></div>';
                }
            }

            // Zamknięcie połączenia z bazą danych
            mysqli_close($conn);
            ?>
        </div>
    </section>

</main>

<aside class="right-sidebar">

    <div class="sidebar-header">
        <h3>Twoje skróty</h3>
    </div>

    <div class="sidebar-content">
        <div class="trending-posts">

            <div class="trending-post">
                <h4>Nowy album muzyczny</h4>
                <p>Sprawdź najnowsze utwory</p>
            </div>

            <div class="trending-post">
                <h4>Aktualizacja platformy</h4>
                <p>Zobacz co nowego</p>
            </div>

            <div class="trending-post">
                <h4>Wydarzenia w tym tygodniu</h4>
                <p>Zaplanuj swój czas</p>
            </div>

        </div>
    </div>

</aside>

<!-- #endregion -->
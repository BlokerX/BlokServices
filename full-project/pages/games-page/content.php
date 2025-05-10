<?php
require_once '../../page-container/db.php';

// Nawiązanie połączenia z bazą danych
$conn = connect_db($config);

// Pobieranie gier z bazy danych
$sql = "SELECT * FROM games ORDER BY average_rating DESC";
$result = mysqli_query($conn, $sql);

// Sprawdzenie błędów zapytania
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

    <!-- #region Zawartość podstrony -->

    <aside class="left-sidebar">
        <!-- Sekcja sidebar (pozostaje bez zmian) -->
        <!-- ... -->
    </aside>

    <main>
        <section class="games-header">
            <!-- Nagłówek sekcji gier (pozostaje bez zmian) -->
            <!-- ... -->
        </section>

        <!-- Sekcja polecanych gier -->
        <section class="featured-games">
            <h3>Polecane gry</h3>
            <div class="featured-carousel">
                <?php
                $featured_query = "SELECT * FROM games ORDER BY average_rating DESC LIMIT 3";
                $featured_result = mysqli_query($conn, $featured_query);
                
                while($featured_game = mysqli_fetch_assoc($featured_result)):
                ?>
                <div class="featured-game">
                    <div class="featured-game-image">
                        <img src="<?= htmlspecialchars($featured_game['image_path']) ?>" 
                             alt="<?= htmlspecialchars($featured_game['title']) ?>">
                        <div class="game-overlay">
                            <a href="<?= htmlspecialchars($featured_game['game_path']) ?>" 
                               class="play-now-btn"
                               target="_blank">
                               Graj teraz
                            </a>
                        </div>
                    </div>
                    <div class="featured-game-info">
                        <h4><?= htmlspecialchars($featured_game['title']) ?></h4>
                        <div class="game-rating">
                            <?= generate_stars($featured_game['average_rating']) ?>
                            <span class="rating-value">
                                <?= number_format($featured_game['average_rating'], 1) ?>
                            </span>
                        </div>
                        <p><?= htmlspecialchars($featured_game['description']) ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>

        <!-- Sekcja z gridem gier -->
        <section class="games-grid">
            <?php while($game = mysqli_fetch_assoc($result)): ?>
            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="<?= htmlspecialchars($game['image_path']) ?>" 
                         alt="<?= htmlspecialchars($game['title']) ?>">
                    <div class="game-actions">
                        <a href="<?= htmlspecialchars($game['game_path']) ?>" 
                           class="play-btn"
                           target="_blank">
                           <i class="fas fa-play"></i> Graj
                        </a>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4><?= htmlspecialchars($game['title']) ?></h4>
                    <div class="game-meta">
                        <span class="game-category">
                            <i class="<?= get_category_icon($game['type']) ?>"></i>
                            <?= htmlspecialchars($game['type']) ?>
                        </span>
                        <span class="game-plays">
                            <i class="fas fa-gamepad"></i>
                            <?= format_plays(rand(1000, 10000)) ?>
                        </span>
                    </div>
                    <div class="game-rating">
                        <?= generate_stars($game['average_rating']) ?>
                        <span class="rating-value">
                            <?= number_format($game['average_rating'], 1) ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </section>
    </main>

    <?php
    // Funkcje pomocnicze
    function generate_stars($rating) {
        $output = '<span class="stars">';
        $full_stars = floor($rating);
        $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
        
        $output .= str_repeat('<i class="fas fa-star"></i>', $full_stars);
        $output .= $half_star ? '<i class="fas fa-star-half-alt"></i>' : '';
        $output .= '</span>';
        return $output;
    }

    function get_category_icon($type) {
        return match($type) {
            'Action'       => 'fas fa-fist-raised',
            'Strategy'     => 'fas fa-chess',
            'Puzzle'       => 'fas fa-puzzle-piece',
            'FPS'          => 'fas fa-crosshairs',
            'MMORPG'       => 'fas fa-dragon',
            'MOBA'         => 'fas fa-users',
            'Survival'    => 'fas fa-campground',
            default       => 'fas fa-gamepad'
        };
    }

    function format_plays($plays) {
        return ($plays > 1000) ? round($plays/1000, 1) . 'K' : $plays;
    }

    // Zamknięcie połączenia
    close_db();
    ?>
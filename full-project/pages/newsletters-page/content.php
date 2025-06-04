<?php
require_once '../../page-container/db.php';
$conn = connect_db($config);

// Sprawdź czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Pobieranie artykułów z bazy danych
$articles_query = "SELECT na.*, u.name AS author_name 
                   FROM newsletters_articles na
                   JOIN users u ON na.author_id = u.id
                   ORDER BY na.creation_date DESC";
$articles_result = mysqli_query($conn, $articles_query);

// Sprawdzenie błędów zapytania
if (!$articles_result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

        <aside class="news-sidebar">
            <div class="news-header">
                <i class="fas fa-newspaper"></i>
                <h2>Nowości</h2>
            </div>
            
            <div class="news-filters">
                <div class="filter-title">
                    <i class="fas fa-filter"></i>
                    Filtruj kategorie
                </div>
                <div class="filter-options">
                    <button class="filter-btn active">Wszystkie</button>
                    <button class="filter-btn">Aktualności</button>
                    <button class="filter-btn">Ostrzeżenia</button>
                    <button class="filter-btn">Oferty</button>
                    <button class="filter-btn">Promocje</button>
                </div>
            </div>
            
            <div class="popular-articles">
                <div class="popular-title">
                    <i class="fas fa-fire"></i>
                    Popularne artykuły
                </div>
                <div class="popular-list">
                    <div class="popular-item">
                        <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Popularny artykuł">
                        <div class="popular-item-content">
                            <div class="popular-item-title">Nowe funkcje w serwisie</div>
                            <div class="popular-item-date">10 maja 2025</div>
                        </div>
                    </div>
                    <div class="popular-item">
                        <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Popularny artykuł">
                        <div class="popular-item-content">
                            <div class="popular-item-title">Najlepsze gry miesiąca</div>
                            <div class="popular-item-date">5 maja 2025</div>
                        </div>
                    </div>
                    <div class="popular-item">
                        <img src="https://images.unsplash.com/photo-1545235617-9465d2a55698?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="Popularny artykuł">
                        <div class="popular-item-content">
                            <div class="popular-item-title">Poradnik dla nowych użytkowników</div>
                            <div class="popular-item-date">28 kwietnia 2025</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Główna zawartość z listą artykułów -->
        <main class="news-main">
            <div class="page-header">
                <h1 class="page-title">Najnowsze artykuły</h1>
                <div class="search-container">
                    <input type="text" placeholder="Szukaj artykułów...">
                    <button><i class="fas fa-search"></i></button>
                </div>
            </div>
            
            <div class="articles-grid">
                <?php if (mysqli_num_rows($articles_result) > 0): ?>
                    <?php while($article = mysqli_fetch_assoc($articles_result)): ?>
                        <div class="article-card">
                            <div class="article-image">
                                <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=600&q=80" alt="<?= htmlspecialchars($article['title']) ?>">
                            </div>
                            <div class="article-content">
                                <span class="article-category"><?= htmlspecialchars($article['type']) ?></span>
                                <h3 class="article-title"><?= htmlspecialchars($article['title']) ?></h3>
                                <p class="article-excerpt"><?= htmlspecialchars(substr($article['content'], 0, 120)) ?>...</p>
                                <div class="article-meta">
                                    <div class="article-author">
                                        <div class="author-avatar"><?= substr($article['author_name'], 0, 1) ?></div>
                                        <?= htmlspecialchars($article['author_name']) ?>
                                    </div>
                                    <div class="article-date">
                                        <i class="far fa-calendar"></i>
                                        <?= date('d.m.Y', strtotime($article['creation_date'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-articles">
                        <i class="far fa-newspaper"></i>
                        <h3>Brak artykułów do wyświetlenia</h3>
                        <p>Sprawdź później, aby zobaczyć nowe artykuły.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
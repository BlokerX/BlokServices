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
            <div class="search-results-grid">
                <h3><i class="fas fa-search"></i> Rower</h3>
                <p>Przejedź się na rowerze i ciesz się wolnością.</p>
            </div>
            <div class="search-results-grid">
                <h3><i class="fas fa-search"></i> Rower</h3>
                <p>Wypożycz rower i zwiedź miasto.</p>
            </div>
            <div class="search-results-grid">
                <h3><i class="fas fa-search"></i> Rower</h3>
                <p>Nowe modele rowerów w atrakcyjnych cenach.</p>
            </div>
            <div class="search-results-grid">
                <h3><i class="fas fa-search"></i> Rower</h3>
                <p>Wypożycz rower i zwiedź okolicę.</p>
            </div>
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
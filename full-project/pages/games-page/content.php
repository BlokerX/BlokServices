    <!-- #region Zawartość podstrony -->

    <aside class="left-sidebar">
        <div class="sidebar-header">
            <h3>Kategorie gier</h3>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li><a href="#" class="active"><i class="fas fa-star"></i> Popularne</a></li>
                <li><a href="#"><i class="fas fa-fire"></i> Nowe</a></li>
                <li><a href="#"><i class="fas fa-gamepad"></i> Akcji</a></li>
                <li><a href="#"><i class="fas fa-chess"></i> Strategiczne</a></li>
                <li><a href="#"><i class="fas fa-puzzle-piece"></i> Logiczne</a></li>
                <li><a href="#"><i class="fas fa-running"></i> Zręcznościowe</a></li>
                <li><a href="#"><i class="fas fa-users"></i> Multiplayer</a></li>
                <li><a href="#"><i class="fas fa-heart"></i> Ulubione</a></li>
                <hr>
                <li><a href="#"><i class="fas fa-history"></i> Ostatnio grane</a></li>
                <li><a href="#"><i class="fas fa-trophy"></i> Moje osiągnięcia</a></li>
            </ul>
        </div>
    </aside>

    <main>
        <section class="games-header">
            <h2>Biblioteka gier</h2>
            <div class="games-controls">
                <div class="search-games">
                    <input type="text" id="games-search" placeholder="Szukaj gier...">
                    <button class="search-button"><i class="fas fa-search"></i></button>
                </div>
                <div class="games-filters">
                    <select id="games-sort">
                        <option value="popular">Popularność</option>
                        <option value="newest">Najnowsze</option>
                        <option value="rating">Najwyżej oceniane</option>
                        <option value="a-z">Alfabetycznie</option>
                    </select>
                    <button id="grid-view" class="view-toggle active"><i class="fas fa-th"></i></button>
                    <button id="list-view" class="view-toggle"><i class="fas fa-list"></i></button>
                </div>
            </div>
        </section>

        <section class="featured-games">
            <h3>Polecane gry</h3>
            <div class="featured-carousel">
                <div class="featured-game">
                    <div class="featured-game-image">
                        <img src="../../images/games/featured1.jpg" alt="Gra 1">
                        <div class="game-overlay">
                            <button class="play-now-btn">Graj teraz</button>
                        </div>
                    </div>
                    <div class="featured-game-info">
                        <h4>Super Platformer</h4>
                        <div class="game-rating">
                            <span class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </span>
                            <span class="rating-value">4.5</span>
                        </div>
                        <p>Dynamiczna gra platformowa z unikalnymi poziomami i wyzwaniami.</p>
                    </div>
                </div>
                <div class="carousel-controls">
                    <button class="carousel-prev"><i class="fas fa-chevron-left"></i></button>
                    <div class="carousel-dots">
                        <span class="dot active"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <button class="carousel-next"><i class="fas fa-chevron-right"></i></button>
                </div>
            </div>
        </section>

        <section class="games-grid">
            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game1.jpg" alt="Gra 1">
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Puzzle Master</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-puzzle-piece"></i> Logiczna</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 1.2K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </span>
                        <span class="rating-value">4.0</span>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game2.jpg" alt="Gra 2">
                    <span class="game-badge new">Nowa</span>
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Space Shooter</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-rocket"></i> Akcja</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 3.5K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </span>
                        <span class="rating-value">4.5</span>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game3.jpg" alt="Gra 3">
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="fas fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Tower Defense</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-chess-rook"></i> Strategia</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 5.8K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </span>
                        <span class="rating-value">5.0</span>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game4.jpg" alt="Gra 4">
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Memory Match</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-brain"></i> Pamięciowa</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 2.3K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                        </span>
                        <span class="rating-value">3.5</span>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game5.jpg" alt="Gra 5">
                    <span class="game-badge hot">Hot</span>
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Racing 3D</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-car"></i> Wyścigi</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 8.7K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </span>
                        <span class="rating-value">4.0</span>
                    </div>
                </div>
            </div>

            <div class="game-card">
                <div class="game-thumbnail">
                    <img src="../../images/games/game6.jpg" alt="Gra 6">
                    <div class="game-actions">
                        <button class="play-btn"><i class="fas fa-play"></i> Graj</button>
                        <button class="favorite-btn"><i class="far fa-heart"></i></button>
                    </div>
                </div>
                <div class="game-info">
                    <h4>Word Connect</h4>
                    <div class="game-meta">
                        <span class="game-category"><i class="fas fa-font"></i> Słowna</span>
                        <span class="game-plays"><i class="fas fa-gamepad"></i> 3.1K</span>
                    </div>
                    <div class="game-rating">
                        <span class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </span>
                        <span class="rating-value">3.0</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="pagination">
            <button class="page-prev"><i class="fas fa-chevron-left"></i></button>
            <button class="page-number active">1</button>
            <button class="page-number">2</button>
            <button class="page-number">3</button>
            <span class="page-ellipsis">...</span>
            <button class="page-number">10</button>
            <button class="page-next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </main>

    <aside class="right-sidebar">
        <div class="sidebar-header">
            <h3>Statystyki graczy</h3>
        </div>
        <div class="sidebar-content">
            <div class="top-players">
                <h4>Najlepsi gracze</h4>
                <ul class="player-list">
                    <li>
                        <img src="../../images/avatars/player1.jpg" alt="Gracz 1" class="player-avatar">
                        <div class="player-info">
                            <span class="player-name">GamerPro123</span>
                            <span class="player-score">9850 pkt</span>
                        </div>
                        <span class="player-rank">1</span>
                    </li>
                    <li>
                        <img src="../../images/avatars/player2.jpg" alt="Gracz 2" class="player-avatar">
                        <div class="player-info">
                            <span class="player-name">SuperPlayer</span>
                            <span class="player-score">9340 pkt</span>
                        </div>
                        <span class="player-rank">2</span>
                    </li>
                    <li>
                        <img src="../../images/avatars/player3.jpg" alt="Gracz 3" class="player-avatar">
                        <div class="player-info">
                            <span class="player-name">GameMaster</span>
                            <span class="player-score">8920 pkt</span>
                        </div>
                        <span class="player-rank">3</span>
                    </li>
                </ul>
            </div>

            <div class="trending-games">
                <h4>Popularne teraz</h4>
                <ul class="trending-list">
                    <li>
                        <img src="../../images/games/trending1.jpg" alt="Gra 1" class="trending-thumbnail">
                        <div class="trending-info">
                            <span class="trending-name">Zombie Survival</span>
                            <div class="trending-stats">
                                <span><i class="fas fa-users"></i> 356</span>
                                <span><i class="fas fa-arrow-up"></i> 25%</span>
                            </div>
                        </div>
                    </li>
                    <li>
                        <img src="../../images/games/trending2.jpg" alt="Gra 2" class="trending-thumbnail">
                        <div class="trending-info">
                            <span class="trending-name">Farm Simulator</span>
                            <div class="trending-stats">
                                <span><i class="fas fa-users"></i> 289</span>
                                <span><i class="fas fa-arrow-up"></i> 18%</span>
                            </div>
                        </div>
                    </li>
                    <li>
                        <img src="../../images/games/trending3.jpg" alt="Gra 3" class="trending-thumbnail">
                        <div class="trending-info">
                            <span class="trending-name">Candy Crush Clone</span>
                            <div class="trending-stats">
                                <span><i class="fas fa-users"></i> 245</span>
                                <span><i class="fas fa-arrow-up"></i> 12%</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="game-achievements">
                <h4>Ostatnie osiągnięcia</h4>
                <ul class="achievement-list">
                    <li>
                        <div class="achievement-icon"><i class="fas fa-trophy"></i></div>
                        <div class="achievement-info">
                            <span class="achievement-name">Mistrz Logiki</span>
                            <span class="achievement-game">Puzzle Master</span>
                        </div>
                    </li>
                    <li>
                        <div class="achievement-icon"><i class="fas fa-medal"></i></div>
                        <div class="achievement-info">
                            <span class="achievement-name">Szybkie Palce</span>
                            <span class="achievement-game">Word Connect</span>
                        </div>
                    </li>
                    <li>
                        <div class="achievement-icon"><i class="fas fa-star"></i></div>
                        <div class="achievement-info">
                            <span class="achievement-name">Pierwszy Milion</span>
                            <span class="achievement-game">Clicker Heroes</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- #endregion -->
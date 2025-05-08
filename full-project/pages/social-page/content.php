<?php
// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


// Sprawdź, czy użytkownik nie jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
}

?>

<!-- #region Zawartość podstrony -->

<aside class="left-sidebar">
    <div class="sidebar-header">
        <h3>Menu społecznościowe</h3>
    </div>
    <div class="sidebar-content">
        <ul class="sidebar-menu">
            <li><a href="<?php echo $config['pages']['welcome-page']['path']; ?>"><i class="fas fa-home"></i> Strona główna</a></li>
            <li><a href="<?php echo $config['pages']['profile-page']['path']; ?>"><i class="fas fa-user"></i> Mój profil</a></li>
            <li class="active"><a href="#"><i class="fas fa-users"></i> Społeczność</a></li>
            <li><a href="#"><i class="fas fa-user-friends"></i> Znajomi</a></li>
            <li><a href="<?php echo $config['pages']['gallery-page']['path']; ?>"><i class="fas fa-images"></i> Zdjęcia</a></li>
            <hr>
            <li><a href="#"><i class="fas fa-cog"></i> Ustawienia społecznościowe</a></li>
        </ul>
    </div>
</aside>

<main>
    <section class="create-post-section">
        <h2>Co słychać?</h2>
        <div class="post-creator">
                <img src="<?php echo $_SESSION['user_avatar']; ?>" alt="Twój avatar" class="user-avatar">
            <div class="post-form">
                <textarea id="post-content" class="new-post-content-textarea" placeholder="Podziel się swoimi myślami..."></textarea>
                <div class="post-controls">
                    <div class="post-attachments">
                        <button class="attachment-btn"><i class="fas fa-image"></i> Zdjęcie</button>
                        <button class="attachment-btn"><i class="fas fa-video"></i> Film</button>
                        <button class="attachment-btn"><i class="fas fa-link"></i> Link</button>
                    </div>
                    <button id="add-post-btn" class="publish-btn">Opublikuj</button>
                </div>
            </div>
        </div>
    </section>

    <section class="posts-feed">
        <div class="feed-filter">
            <button id="all-filter" class="filter-btn active" data-filter="all">Wszystkie</button>
            <button id="recent-filter" class="filter-btn" data-filter="recent">Najnowsze</button>
            <button id="friends-filter" class="filter-btn" data-filter="friends">Znajomi</button>
            <button id="popular-filter" class="filter-btn" data-filter="popular">Popularne</button>
        </div>

        <div class="posts-container">

            

            <!-- Post 1 -->
            <!-- <div class="post-card" data-post-id="1">
                <div class="post-header">
                    <div class="post-author">
                        <img src="../../assets/img/avatars/user1.png" alt="Avatar użytkownika">
                        <div class="author-info">
                            <h4>Anna Kowalska</h4>
                            <span class="post-time">2 godziny temu</span>
                        </div>
                    </div>
                    <div class="post-options">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
                <div class="post-content">
                    <p>Dzisiaj wspaniały dzień! Właśnie ukończyłam swój pierwszy projekt programistyczny. Jestem bardzo zadowolona z rezultatów.</p>
                    <div class="post-image">
                        <img src="../../assets/img/posts/coding-project.jpg" alt="Projekt programistyczny">
                    </div>
                </div>
                <div class="post-interactions">
                    <div class="interaction-stats">
                        <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>24</span></span>
                        <span class="comments-count">12 komentarzy</span>
                    </div>
                    <div class="interaction-buttons">
                        <button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>
                        <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
                        <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comments-container">
                        <div class="comment">
                            <img src="../../assets/img/avatars/user2.png" alt="Avatar komentującego">
                            <div class="comment-content">
                                <h5>Marek Nowak</h5>
                                <p>Gratuluję! Jaki to projekt?</p>
                                <div class="comment-actions">
                                    <span class="comment-time">1 godzinę temu</span>
                                    <button class="comment-like-btn">Lubię to</button>
                                    <button class="comment-reply-btn">Odpowiedz</button>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <img src="../../assets/img/avatars/user1.png" alt="Avatar komentującego">
                            <div class="comment-content">
                                <h5>Anna Kowalska</h5>
                                <p>Dziękuję! To aplikacja do zarządzania zadaniami zbudowana w React i Node.js.</p>
                                <div class="comment-actions">
                                    <span class="comment-time">45 minut temu</span>
                                    <button class="comment-like-btn">Lubię to</button>
                                    <button class="comment-reply-btn">Odpowiedz</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="add-comment">
                        <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
                        <input type="text" placeholder="Napisz komentarz...">
                        <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div> -->

            <!-- Post 2 -->
            <!-- <div class="post-card" data-post-id="2">
                <div class="post-header">
                    <div class="post-author">
                        <img src="../../assets/img/avatars/user3.png" alt="Avatar użytkownika">
                        <div class="author-info">
                            <h4>Piotr Wiśniewski</h4>
                            <span class="post-time">5 godzin temu</span>
                        </div>
                    </div>
                    <div class="post-options">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
                <div class="post-content">
                    <p>Kto chciałby dołączyć do naszej grupy dyskusyjnej na temat nowych technologii? Spotykamy się online w każdy wtorek o 18:00.</p>
                </div>
                <div class="post-interactions">
                    <div class="interaction-stats">
                        <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>18</span></span>
                        <span class="comments-count">7 komentarzy</span>
                    </div>
                    <div class="interaction-buttons">
                        <button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>
                        <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
                        <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comments-container">
                        <div class="comment">
                            <img src="../../assets/img/avatars/user4.png" alt="Avatar komentującego">
                            <div class="comment-content">
                                <h5>Katarzyna Zielińska</h5>
                                <p>Chętnie dołączę! Czy mógłbyś podać więcej szczegółów?</p>
                                <div class="comment-actions">
                                    <span class="comment-time">2 godziny temu</span>
                                    <button class="comment-like-btn">Lubię to</button>
                                    <button class="comment-reply-btn">Odpowiedz</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="add-comment">
                        <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
                        <input type="text" placeholder="Napisz komentarz...">
                        <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div> -->

            <!-- Post 3 -->
            <!-- <div class="post-card" data-post-id="3">
                <div class="post-header">
                    <div class="post-author">
                        <img src="../../assets/img/avatars/user5.png" alt="Avatar użytkownika">
                        <div class="author-info">
                            <h4>Tomasz Jankowski</h4>
                            <span class="post-time">1 dzień temu</span>
                        </div>
                    </div>
                    <div class="post-options">
                        <i class="fas fa-ellipsis-h"></i>
                    </div>
                </div>
                <div class="post-content">
                    <p>Polecam nową kawiarenkę, która otworzyła się przy ul. Długiej. Mają świetną kawę i domowe ciasta!</p>
                    <div class="post-image">
                        <img src="../../assets/img/posts/cafe.jpg" alt="Kawiarnia">
                    </div>
                </div>
                <div class="post-interactions">
                    <div class="interaction-stats">
                        <span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>42</span></span>
                        <span class="comments-count">15 komentarzy</span>
                    </div>
                    <div class="interaction-buttons">
                        <button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>
                        <button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>
                        <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="comments-container"></div>
                    <div class="add-comment">
                        <img src="../../assets/img/avatars/default-avatar.png" alt="Twój avatar">
                        <input type="text" placeholder="Napisz komentarz...">
                        <button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div> -->
        </div>

        <div class="load-more">
            <button id="load-more-btn">Załaduj więcej</button>
        </div>
    </section>
</main>

<aside class="right-sidebar">
    <div class="sidebar-header">
        <h3>Sugestie dla Ciebie</h3>
    </div>
    <div class="sidebar-content">
        <div class="suggestions-container">
            <div class="suggestion-card">
                <img src="../../assets/img/avatars/user6.png" alt="Avatar użytkownika">
                <div class="suggestion-info">
                    <h4>Jan Kowalczyk</h4>
                    <p>12 wspólnych znajomych</p>
                </div>
                <button class="add-friend-btn"><i class="fas fa-user-plus"></i></button>
            </div>

            <div class="suggestion-card">
                <img src="../../assets/img/avatars/user7.png" alt="Avatar użytkownika">
                <div class="suggestion-info">
                    <h4>Agnieszka Nowakowska</h4>
                    <p>8 wspólnych znajomych</p>
                </div>
                <button class="add-friend-btn"><i class="fas fa-user-plus"></i></button>
            </div>

            <div class="suggestion-card">
                <img src="../../assets/img/avatars/user8.png" alt="Avatar użytkownika">
                <div class="suggestion-info">
                    <h4>Michał Wiśniewski</h4>
                    <p>5 wspólnych znajomych</p>
                </div>
                <button class="add-friend-btn"><i class="fas fa-user-plus"></i></button>
            </div>
        </div>
    </div>

    <div class="sidebar-header">
        <h3>Nadchodzące wydarzenia</h3>
    </div>
    <div class="sidebar-content">
        <div class="events-container">
            <div class="event-card">
                <div class="event-date">
                    <span class="event-day">15</span>
                    <span class="event-month">Maj</span>
                </div>
                <div class="event-info">
                    <h4>Warsztaty programistyczne</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Centrum Technologii</p>
                    <p><i class="fas fa-users"></i> 12 znajomych weźmie udział</p>
                </div>
            </div>

            <div class="event-card">
                <div class="event-date">
                    <span class="event-day">22</span>
                    <span class="event-month">Maj</span>
                </div>
                <div class="event-info">
                    <h4>Spotkanie networkingowe</h4>
                    <p><i class="fas fa-map-marker-alt"></i> Biurowiec Alfa</p>
                    <p><i class="fas fa-users"></i> 8 znajomych weźmie udział</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- #endregion -->
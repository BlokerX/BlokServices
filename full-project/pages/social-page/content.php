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
            <button class="filter-btn active" data-filter="all">Wszystkie</button>
            <button class="filter-btn" data-filter="friends">Znajomi</button>
            <button class="filter-btn" data-filter="popular">Popularne</button>
            <button class="filter-btn" data-filter="recent">Najnowsze</button>
        </div>

        <div class="posts-container">

            <?php
            // Wczytanie postów z bazy danych

            $conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            // Zapytanie do bazy danych o posty
            $sql = "SELECT * FROM posts ORDER BY creation_date DESC LIMIT 10"; // Przykładowe zapytanie

            // Wykonanie zapytania
            $posts = $conn->query($sql);

            // Sprawdzenie, czy są posty
            if ($posts->num_rows > 0) {

                // Wyświetlenie postów
                while ($post = $posts->fetch_assoc()) {

                    // Zapytaj o autora postów
                    $authorSql = "SELECT * FROM users WHERE id = ?";
                    $stmt = $conn->prepare($authorSql);
                    $stmt->bind_param("i", $post['owner_user_id']);

                    $stmt->execute();
                    $authorResult = $stmt->get_result();
                    $author = $authorResult->fetch_assoc();
                    $stmt->close();

                    echo '<div class="post-card" data-post-id="' . $post['id'] . '">';
                    echo '<div class="post-header">';
                    echo '<div class="post-author">';
                    echo '<img src="' . $author['avatar'] . '" alt="Avatar użytkownika">';
                    echo '<div class="author-info">';
                    echo '<h4>' . $author['login'] . '</h4>';
                    echo '<span class="post-time">' . $post['creation_date'] . '</span>';
                    echo '</div></div>';
                    echo '<div class="post-options"><i class="fas fa-ellipsis-h"></i></div></div>';
                    echo '<div class="post-content"><p>' . $post['content'] . '</p></div>';
                    echo '<div class="post-interactions">';
                    echo '<div class="interaction-stats">';

                    // Zapytaj o liczbę polubień
                    $likesSql = "SELECT COUNT(*) as likes_count FROM posts_likes WHERE post_id = ?";
                    $stmt = $conn->prepare($likesSql);
                    $stmt->bind_param("i", $post['id']);
                    $stmt->execute();
                    $likesResult = $stmt->get_result();
                    $likes = $likesResult->fetch_assoc();
                    $likes = $likes['likes_count'];
                    $stmt->close();

                    // Zapytaj o liczbę komentarzy
                    $commentsSql = "SELECT COUNT(*) as comments_count FROM posts_comments WHERE post_id = ?";
                    $stmt = $conn->prepare($commentsSql);
                    $stmt->bind_param("i", $post['id']);
                    $stmt->execute();
                    $commentsResult = $stmt->get_result();
                    $comments = $commentsResult->fetch_assoc();
                    $stmt->close();
                    $comments = $comments['comments_count'];

                    // Sprawdź, czy twója osoba polubiła post
                    $likedSql = "SELECT * FROM posts_likes WHERE post_id = ? AND user_id = ?";
                    $stmt = $conn->prepare($likedSql);
                    $stmt->bind_param("ii", $post['id'], $_SESSION['user_id']);
                    $stmt->execute();
                    $likedResult = $stmt->get_result();
                    $liked = $likedResult->num_rows > 0;
                    $stmt->close();

                    echo '<span class="likes-count"><i class="fas fa-thumbs-up"></i> <span>' . $likes . '</span></span>';
                    echo '<span class="comments-count">' . $comments . ' komentarzy</span>';
                    echo '</div>';
                    echo '<div class="interaction-buttons">';

                    if ($liked) {
                        echo '<button class="like-btn like-btn-currently-liked" data-liked="true"><i class="fas fa-thumbs-up"></i> Lubię to</button>';
                    } else {
                        echo '<button class="like-btn" data-liked="false"><i class="far fa-thumbs-up"></i> Lubię to</button>';
                    }
                    
                    echo '<button class="comment-btn"><i class="far fa-comment"></i> Komentuj</button>';
                    echo '<button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>';
                    echo '</div></div>';
                    echo '<div class="comments-section">';
                    echo '<div class="comments-container">';
                    // Wczytanie komentarzy do postu
                    $postId = $post['id'];
                    $commentsSql = "SELECT * FROM posts_comments WHERE post_id = $postId ORDER BY creation_date DESC";
                    $comments = $conn->query($commentsSql);
                    if ($comments->num_rows > 0) {
                        while ($comment = $comments->fetch_assoc()) {

                            // Zapytanie o autora komentarza
                            $commentAuthorSql = "SELECT * FROM users WHERE id = ?";
                            $stmt = $conn->prepare($commentAuthorSql);
                            $stmt->bind_param("i", $comment['comment_author_id']);
                            $stmt->execute();
                            $commentAuthorResult = $stmt->get_result();
                            $commentAuthor = $commentAuthorResult->fetch_assoc();
                            $stmt->close();


                            echo '<div class="comment">';
                            echo '<img src="' . $commentAuthor['avatar'] . '" alt="Avatar komentującego">';
                            echo '<div class="comment-content">';
                            echo '<h5>' . $commentAuthor['login'] . '</h5>';
                            echo '<p>' . $comment['content'] . '</p>';
                            echo '<div class="comment-actions">';
                            echo '<span class="comment-time">' . $comment['creation_date'] . '</span>';
                            echo '<button class="comment-like-btn">Lubię to</button>';
                            echo '<button class="comment-reply-btn">Odpowiedz</button>';
                            echo '</div></div></div>';
                        }
                    }
                    echo '</div>'; // comments-container
                    echo '<div class="add-comment">';
                    echo '<img src="'. $_SESSION['user_avatar'] .'" alt="Twój avatar">';
                    echo '<input type="text" placeholder="Napisz komentarz...">';
                    echo '<button class="submit-comment-btn"><i class="fas fa-paper-plane"></i></button>';
                    echo '</div>'; // add-comment
                    echo '</div>'; // comments-section
                    echo '</div>'; // post-card
                }
            } else {
                echo '<p>Brak postów do wyświetlenia.</p>';
            }
            // Zamknięcie połączenia z bazą danych
            $conn->close();

            ?>

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
<?php
// Wczytanie konfiguracji JSON
include '../../page-container/json-config-load.php';

// Ten skrypt obsługuje funkcjonalność filtrowania postów na stronie społecznościowej.
header('Content-Type: application/json');

// Rozpoczęcie sesji jeśli jeszcze nie została rozpoczęta
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sprawdź czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Użytkownik nie jest zalogowany']);
    exit;
}

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['criterion'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganych danych']);
    exit;
}

require_once '../../page-container/db.php';

try {
    $criterion = $_POST['criterion'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Pobierz posty z danej kategorii
    $query = "";
    $stmt = null; // Ustawienie na null po zamknięciu
    $posts = [];

    switch ($criterion) {
        case "friends":
            $query = "SELECT 
                        posts.*,
                        users.login AS author_login,
                        users.avatar AS author_avatar,
                        (SELECT COUNT(*) FROM posts_likes WHERE post_id = posts.id) AS like_count,
                        (SELECT COUNT(*) FROM posts_comments WHERE post_id = posts.id) AS comment_count
                    FROM 
                        posts
                    JOIN
                        users ON users.id = posts.owner_user_id
                    WHERE
                        posts.access_level LIKE 'friends' 
                            AND
                                (
                                posts.owner_user_id IN (SELECT sender_user_id FROM friend_requests WHERE reciver_user_id = ? AND status = 'accepted') 
                                OR
                                posts.owner_user_id IN (SELECT reciver_user_id FROM friend_requests WHERE sender_user_id = ? AND status = 'accepted')
                                )
                    ORDER BY 
                        posts.creation_date DESC;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ii', $userId, $userId);
            break;

        case "popular":
            $query = "SELECT 
                        posts.*,
                        users.login AS author_login,
                        users.avatar AS author_avatar,
                        (SELECT COUNT(*) FROM posts_likes WHERE post_id = posts.id) AS like_count,
                        (SELECT COUNT(*) FROM posts_comments WHERE post_id = posts.id) AS comment_count
                    FROM 
                        posts
                    JOIN
                        users ON users.id = posts.owner_user_id
                    WHERE
                        posts.access_level = 'public'
                        OR (posts.access_level = 'private' AND posts.owner_user_id = ?)
                        OR (posts.access_level = 'friends' 
                            AND
                                (
                                    posts.owner_user_id IN (SELECT sender_user_id FROM friend_requests WHERE reciver_user_id = ? AND status = 'accepted') 
                                    OR
                                    posts.owner_user_id IN (SELECT reciver_user_id FROM friend_requests WHERE sender_user_id = ? AND status = 'accepted')
                                )
                        )
                    ORDER BY 
                        like_count DESC,
                        comment_count DESC,
                        posts.creation_date DESC;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iii', $userId, $userId, $userId);
            break;

        case "all":
        case "recent":
        default:
            $query = "SELECT 
                        posts.*,
                        users.login AS author_login,
                        users.avatar AS author_avatar,
                        (SELECT COUNT(*) FROM posts_likes WHERE post_id = posts.id) AS like_count,
                        (SELECT COUNT(*) FROM posts_comments WHERE post_id = posts.id) AS comment_count
                    FROM 
                        posts
                    JOIN
                        users ON users.id = posts.owner_user_id
                    WHERE
                        posts.access_level = 'public'
                        OR (posts.access_level = 'private' AND posts.owner_user_id = ?)
                        OR (posts.access_level = 'friends' 
                            AND
                                (
                                    posts.owner_user_id IN (SELECT sender_user_id FROM friend_requests WHERE reciver_user_id = ? AND status = 'accepted') 
                                    OR
                                    posts.owner_user_id IN (SELECT reciver_user_id FROM friend_requests WHERE sender_user_id = ? AND status = 'accepted')
                                )
                        )
                    ORDER BY 
                        posts.creation_date DESC;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iii', $userId, $userId, $userId);
            break;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    while ($post = $result->fetch_assoc()) {
        // Sprawdź czy post jest polubiony przez aktualnego użytkownika
        $is_liked_by_current_user = false;
        $likeQuery = "SELECT * FROM posts_likes WHERE post_id = ? AND user_id = ?";
        $likeStmt = $conn->prepare($likeQuery);
        $likeStmt->bind_param('ii', $post['id'], $userId);
        $likeStmt->execute();
        $likeResult = $likeStmt->get_result();
        if ($likeResult->num_rows > 0) {
            $is_liked_by_current_user = true;
        }
        $likeStmt->close();

        // Pobierz komentarze do posta
        $commentsQuery = "SELECT posts_comments.*, users.login AS commenter_login, users.avatar AS commenter_avatar 
                 FROM posts_comments 
                 JOIN users ON users.id = posts_comments.comment_author_id 
                 WHERE post_id = ? 
                 ORDER BY creation_date DESC;";
        $commentsStmt = $conn->prepare($commentsQuery);
        $commentsStmt->bind_param('i', $post['id']);
        $commentsStmt->execute();
        $commentsResult = $commentsStmt->get_result();
        $comments = [];
        while ($comment = $commentsResult->fetch_assoc()) {
            $comments[] = $comment;
        }
        $commentsStmt->close();

        // Alternatywnie w jsonie można użyć:
        $posts[] = [
            'post' => $post,
            'is_liked_by_current_user' => $is_liked_by_current_user,
            'comments' => $comments
        ];
    }
    $stmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    $current_user = [
        'id' => $_SESSION['user_id'],
        'login' => $_SESSION['user_name'],
        'avatar' => $_SESSION['user_avatar']
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Posty pobrane pomyślnie',
        'posts' => $posts,
        'current_user' => $current_user
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd: ' . $e->getMessage()]);
    exit;
}

<?php
// Wczytanie bazy danych i konfiguracji oraz sprawdzenie sesji i zalogowania
include '../script_template.php'; // Wczytaj szablon skryptu

// Sprawdź czy wymagane dane są dostępne
if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Brak wymaganego ID posta lub nieprawidłowy format']);
    exit;
}

try {
    $postId = (int)$_POST['post_id'];
    $userId = $_SESSION['user_id'];

    $conn = connect_db($config); // Połącz z bazą danych
    if (!$conn) {
        throw new Exception('Połączenie z bazą danych nie powiodło się');
    }

    // Pobierz pojedynczy post po ID
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
                posts.id = ?
                AND (
                    posts.access_level = 'public'
                    OR (posts.access_level = 'private' AND posts.owner_user_id = ?)
                    OR (posts.access_level = 'friends' 
                        AND
                            (
                                posts.owner_user_id IN (SELECT sender_user_id FROM friend_requests WHERE reciver_user_id = ? AND status = 'accepted') 
                                OR
                                posts.owner_user_id IN (SELECT reciver_user_id FROM friend_requests WHERE sender_user_id = ? AND status = 'accepted')
                                OR posts.owner_user_id = ?
                            )
                    )
                )";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiiii', $postId, $userId, $userId, $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Post nie istnieje lub nie masz do niego dostępu']);
        exit;
    }
    
    $post = $result->fetch_assoc();
    $stmt->close();
    
    // Sprawdź czy post jest polubiony przez aktualnego użytkownika
    $is_liked_by_current_user = false;
    $likeQuery = "SELECT * FROM posts_likes WHERE post_id = ? AND user_id = ?";
    $likeStmt = $conn->prepare($likeQuery);
    $likeStmt->bind_param('ii', $postId, $userId);
    $likeStmt->execute();
    $likeResult = $likeStmt->get_result();
    if ($likeResult->num_rows > 0) {
        $is_liked_by_current_user = true;
    }
    $likeStmt->close();

    // Pobierz komentarze do posta
    $commentsQuery = "SELECT posts_comments.*, users.login AS commenter_login, users.avatar AS commenter_avatar,
                        (SELECT COUNT(*) FROM posts_comments_likes WHERE post_comment_id = posts_comments.id) AS like_count, 
                        (SELECT COUNT(*) FROM posts_comments_likes WHERE post_comment_id = posts_comments.id AND user_id = ?) AS is_liked_by_current_user
             FROM posts_comments 
             LEFT JOIN users ON users.id = posts_comments.comment_author_id 
             WHERE post_id = ? 
             GROUP BY posts_comments.id
             ORDER BY creation_date DESC;";
    $commentsStmt = $conn->prepare($commentsQuery);
    $commentsStmt->bind_param('ii', $userId, $postId);
    $commentsStmt->execute();
    $commentsResult = $commentsStmt->get_result();
    $comments = [];
    while ($comment = $commentsResult->fetch_assoc()) {
        $comments[] = $comment;
    }
    $commentsStmt->close();
    close_db(); // Zamknij połączenie z bazą danych

    $current_user = [
        'id' => $_SESSION['user_id'],
        'login' => $_SESSION['user_name'],
        'avatar' => $_SESSION['user_avatar']
    ];

    echo json_encode([
        'status' => 'success',
        'message' => 'Post pobrany pomyślnie',
        'post' => $post,
        'is_liked_by_current_user' => $is_liked_by_current_user,
        'comments' => $comments,
        'current_user' => $current_user
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Wystąpił błąd: ' . $e->getMessage()]);
    exit;
}
?>
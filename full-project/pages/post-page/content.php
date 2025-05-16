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

if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    header("Location: " . $config['pages']['welcome-page']['path']);
    exit;
}
$postId = (int)$_GET['post_id'];
// Zapisz na stronie w kodzie js do zmiennej const
echo "<script>const _postId = $postId;</script>";

?>

<!-- #region Zawartość podstrony -->

<main class="main-content">

    <section class="posts-feed">

        <div class="posts-container">

        </div>

    </section>
</main>

<!-- #endregion -->
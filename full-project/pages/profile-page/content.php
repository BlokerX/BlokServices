<?php 
// Pobieranie nazwy użytkownika z parametru URL
$user_name = '';
if(isset($_GET['user_name'])) {
    $user_name = $_GET['user_name'];
} else {
    // Jeśli nie podano nazwy użytkownika, przekieruj do strony logowania
    header("Location: " . $config['pages']['login-page']['path'] . "?error=missing_user_name");
    exit;
}

// Połączenie z bazą danych
$conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pobranie danych użytkownika
$query = "SELECT * FROM users WHERE login = '" . $user_name . "'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
mysqli_close($conn);

// Walidacja danych użytkownika
switch ($user['gender']) {
    case 'Male':
        $user['gender'] = 'Mężczyzna';
        break;
    case 'Female':
        $user['gender'] = 'Kobieta';
        break;
    case 'Other':
        $user['gender'] = 'Inne';
        break;
    default:
        $user['gender'] = 'Nieznany';
        break;
}

// Sprawdzenie czy użytkownik jest administratorem
$is_admin = ($user['is_admin'] == 1);
$is_own_profile = ($_SESSION['user_id'] == $user['id']);

// Formatowanie daty ostatniego logowania i wylogowania
$last_login = new DateTime($user['last_login_date']);
$last_logout = new DateTime($user['last_logout_date']);
$register_date = new DateTime($user['register_date']);
$birth_date = new DateTime($user['birth_date']);

?>

<main>
    <div class="profile-container">
        <!-- Nagłówek profilu z avatarem i podstawowymi informacjami -->
        <div class="profile-header">
            <div class="profile-avatar-container">
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="user-avatar">
            </div>
            
            <div class="profile-user-info">
                <h1>Profil użytkownika: <?php echo htmlspecialchars($user['login']); ?></h1>
                
                <?php if($is_admin): ?>
                <span class="user-status admin-status">
                    <i class="fas fa-shield-alt"></i> Administrator
                </span>
                <?php endif; ?>
                
                <span class="user-status">
                    <i class="fas fa-user"></i> Użytkownik
                </span>
                
                <p>ID użytkownika: <?php echo htmlspecialchars($user['id']); ?></p>
                <p>Data dołączenia: <?php echo $register_date->format('d.m.Y'); ?></p>
            </div>
        </div>
        
        <!-- Szczegóły profilu -->
        <div class="profile-details">
            <!-- Sekcja danych osobowych -->
            <div class="profile-section">
                <h2><i class="fas fa-id-card"></i> Dane osobowe</h2>
                
                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <span class="label">Imię</span>
                        <span class="value"><?php echo htmlspecialchars($user['name']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="label">Nazwisko</span>
                        <span class="value"><?php echo htmlspecialchars($user['last_name']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="label">Płeć</span>
                        <span class="value"><?php echo htmlspecialchars($user['gender']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="label">Data urodzenia</span>
                        <span class="value"><?php echo $birth_date->format('d.m.Y'); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Sekcja kontaktu -->
            <div class="profile-section">
                <h2><i class="fas fa-envelope"></i> Kontakt</h2>
                
                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <span class="label">Email</span>
                        <span class="value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="label">Numer telefonu</span>
                        <span class="value"><?php echo htmlspecialchars($user['phone_number']); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Sekcja aktywności -->
            <div class="profile-section">
                <h2><i class="fas fa-clock"></i> Aktywność</h2>
                
                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <span class="label">Ostatnie logowanie</span>
                        <span class="value"><?php echo $last_login->format('d.m.Y H:i:s'); ?></span>
                    </div>
                    
                    <div class="profile-info-item">
                        <span class="label">Ostatnie wylogowanie</span>
                        <span class="value"><?php echo $last_logout->format('d.m.Y H:i:s'); ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Sekcja opisu -->
            <div class="profile-section">
                <h2><i class="fas fa-comment-alt"></i> O mnie</h2>
                
                <div class="description">
                    <?php if(!empty($user['description'])): ?>
                        <?php echo htmlspecialchars($user['description']); ?>
                    <?php else: ?>
                        <em>Użytkownik nie dodał jeszcze opisu.</em>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Akcje profilu -->
            <?php if($is_own_profile): ?>
                <div class="profile-actions">
                    <form action="<?php echo $config['pages']['modify-account-page']['path']; ?>" method="POST">
                        <button type="submit" class="modify-button">
                            <i class="fas fa-edit"></i> Edytuj profil
                        </button>
                    </form>
                    
                    <form action="delete-account-procedure.php" method="POST" class="delete-form">
                        <button type="submit" class="delete-button">
                            <i class="fas fa-trash-alt"></i> Usuń profil
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="profile-actions">
                    <button class="add-friend-button">
                        <i class="fas fa-user-plus"></i> Dodaj do znajomych
                    </button>
                </div>
            <?php endif; ?>
            <div class="profile-actions"><button class="search-button" 
        onclick="window.location.href='<?= htmlspecialchars($config['pages']['search-page']['path'], ENT_QUOTES) ?>?search=<?= urlencode($user['login']) ?>'">
    <i class="fas fa-search"></i> Wyszukaj w aktywnościach
</button>
            </div>
            
        </div>
    </div>

    <!-- Sekcja postów użytkownika -->
    <!-- <div class="user-posts-section">
        <h2><i class="fas fa-newspaper"></i> Posty użytkownika</h2>
        
        <?php
        /*
        $posts_per_page = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $posts_per_page;
        
        $conn = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
        
        // Pobierz całkowitą liczbę postów
        $count_query = "SELECT COUNT(*) as total FROM posts WHERE owner_user_id = " . $user['id'];
        $count_result = mysqli_query($conn, $count_query);
        $total_posts = mysqli_fetch_assoc($count_result)['total'];
        
        // Pobierz posty dla bieżącej strony z dodatkowymi informacjami o użytkowniku
        $posts_query = "SELECT p.*, u.login, u.avatar 
                       FROM posts p 
                       JOIN users u ON p.owner_user_id = u.id 
                       WHERE p.owner_user_id = " . $user['id'] . " 
                       ORDER BY p.creation_date DESC 
                       LIMIT $offset, $posts_per_page";
        $posts_result = mysqli_query($conn, $posts_query);
        
        if (mysqli_num_rows($posts_result) > 0):
            while ($post = mysqli_fetch_assoc($posts_result)): */
        ?>
                <!-- <div class="post">
                    <div class="post-header">
                        <img src="<?php //echo htmlspecialchars($post['avatar']); ?>" alt="Avatar" class="post-avatar">
                        <div class="post-info">
                            <span class="post-author"><?php //echo htmlspecialchars($post['login']); ?></span>
                            <span class="post-date">
                                <i class="far fa-clock"></i> 
                                <?php //echo (new DateTime($post['creation_date']))->format('d.m.Y H:i'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="post-content">
                        <h3 class="post-title"><?php //echo htmlspecialchars($post['title']); ?></h3>
                        <p class="post-text"><?php //echo htmlspecialchars($post['content']); ?></p>
                    </div>
                    <div class="post-footer">
                        <div class="post-actions">
                            <button class="like-btn"><i class="far fa-heart"></i> Lubię to</button>
                            <button class="comment-btn"><i class="far fa-comment"></i> Komentarz</button>
                            <button class="share-btn"><i class="far fa-share-square"></i> Udostępnij</button>
                        </div>
                    </div>
                </div> -->
            <?php //endwhile;

            // Wyświetl przyciski nawigacji jeśli jest więcej postów
            /*if ($total_posts > $posts_per_page): */?>
                <!-- <div class="pagination">
                    <?php //if ($page > 1): ?>
                        <a href="?user_name=<?php //echo urlencode($user_name); ?>&page=<?php //echo $page-1; ?>" class="page-btn">
                            <i class="fas fa-chevron-left"></i> Poprzednia
                        </a>
                    <?php //endif; ?>
                    
                    <?php //if ($total_posts > ($offset + $posts_per_page)): ?>
                        <a href="?user_name=<?php //echo urlencode($user_name); ?>&page=<?php //echo $page+1; ?>" class="page-btn">
                            Następna <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php //endif; ?>
                </div> -->
            <?php //endif;
        /*else: */?>
            <!-- <p class="no-posts">Użytkownik nie dodał jeszcze żadnych postów.</p> -->
        <?php //endif;
        
        //mysqli_close($conn);
        ?>
    <!-- </div> -->

</main>

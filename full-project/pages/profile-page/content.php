<?php
// Pobieranie nazwy użytkownika z parametru URL
$user_name = '';
if (isset($_GET['user_name'])) {
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

// Pobranie danych użytkownika z wykorzystaniem prepared statement
$query = "SELECT * FROM users WHERE login = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 's', $user_name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Sprawdź czy użytkownik istnieje
if (!$user) {
    mysqli_close($conn);
    header("Location: " . $config['pages']['login-page']['path'] . "?error=user_not_found");
    exit;
}

// Pobieranie listy znajomych użytkownika
$friendsQuery = "SELECT u.id, u.login, u.name, u.last_name, u.avatar, u.last_login_date, u.is_admin
                FROM users u
                INNER JOIN friend_requests fr ON 
                    (fr.sender_user_id = u.id AND fr.reciver_user_id = ? AND fr.status = 'accepted') OR
                    (fr.reciver_user_id = u.id AND fr.sender_user_id = ? AND fr.status = 'accepted')
                ORDER BY u.login ASC";

$friendsStmt = mysqli_prepare($conn, $friendsQuery);
mysqli_stmt_bind_param($friendsStmt, 'ii', $user['id'], $user['id']);
mysqli_stmt_execute($friendsStmt);
$friendsResult = mysqli_stmt_get_result($friendsStmt);
$friends = mysqli_fetch_all($friendsResult, MYSQLI_ASSOC);
mysqli_stmt_close($friendsStmt);

// Pobieranie liczby znajomych
$friendsCount = count($friends);

// Pobieranie zaproszeń oczekujących na zaakceptowanie (otrzymane przez użytkownika)
$pendingInvitationsQuery = "SELECT u.id, u.login, u.name, u.last_name, u.avatar, fr.creation_date
                           FROM users u
                           INNER JOIN friend_requests fr ON fr.sender_user_id = u.id
                           WHERE fr.reciver_user_id = ? AND fr.status = 'pending'
                           ORDER BY fr.creation_date DESC";

$pendingStmt = mysqli_prepare($conn, $pendingInvitationsQuery);
mysqli_stmt_bind_param($pendingStmt, 'i', $user['id']);
mysqli_stmt_execute($pendingStmt);
$pendingResult = mysqli_stmt_get_result($pendingStmt);
$pendingInvitations = mysqli_fetch_all($pendingResult, MYSQLI_ASSOC);
mysqli_stmt_close($pendingStmt);

// Pobieranie wysłanych zaproszeń oczekujących na potwierdzenie
$sentInvitationsQuery = "SELECT u.id, u.login, u.name, u.last_name, u.avatar, fr.creation_date
                        FROM users u
                        INNER JOIN friend_requests fr ON fr.reciver_user_id = u.id
                        WHERE fr.sender_user_id = ? AND fr.status = 'pending'
                        ORDER BY fr.creation_date DESC";

$sentStmt = mysqli_prepare($conn, $sentInvitationsQuery);
mysqli_stmt_bind_param($sentStmt, 'i', $user['id']);
mysqli_stmt_execute($sentStmt);
$sentResult = mysqli_stmt_get_result($sentStmt);
$sentInvitations = mysqli_fetch_all($sentResult, MYSQLI_ASSOC);
mysqli_stmt_close($sentStmt);

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

// Sprawdzenie czy data urodzenia istnieje przed stworzeniem obiektu DateTime
$birth_date = null;
if (!empty($user['birth_date'])) {
    $birth_date = new DateTime($user['birth_date']);
}

?>

<main>
    <div class="profile-container">
        <!-- Nagłówek profilu z avatarem i podstawowymi informacjami -->
        <div class="profile-header">
            <div class="profile-avatar-container">
                <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'default-avatar.png'); ?>" alt="Avatar" class="user-avatar">
            </div>

            <div class="profile-user-info">
                <h1>Profil użytkownika: <?php echo htmlspecialchars($user['login']); ?></h1>

                <?php if ($is_admin): ?>
                    <span class="user-status admin-status">
                        <i class="fas fa-shield-alt"></i> Administrator
                    </span>
                <?php endif; ?>

                <span class="user-status">
                    <i class="fas fa-user"></i> Użytkownik
                </span>

                <p id="user-id-display">ID użytkownika: <?php echo htmlspecialchars($user['id']); ?></p>
                <p>Data dołączenia: <?php echo $register_date->format('d.m.Y'); ?></p>
                <p>Liczba znajomych: <?php echo $friendsCount; ?></p>
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
                        <span class="value">
                            <?php
                            if ($birth_date) {
                                echo $birth_date->format('d.m.Y');
                            } else {
                                echo 'Nie podano';
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Sekcja kontaktu -->
            <div class="profile-section">
                <h2><i class="fas fa-envelope"></i> Kontakt</h2>

                <div class="profile-info-grid">
                    <div class="profile-info-item">
                        <span class="label">Email</span>
                        <span class="value"><?php echo htmlspecialchars($user['email'] ?? 'Nie podano'); ?></span>
                    </div>

                    <div class="profile-info-item">
                        <span class="label">Numer telefonu</span>
                        <span class="value"><?php echo htmlspecialchars($user['phone_number'] ?? 'Nie podano'); ?></span>
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
                    <?php if (!empty($user['description'])): ?>
                        <?php echo nl2br(htmlspecialchars($user['description'])); ?>
                    <?php else: ?>
                        <em>Użytkownik nie dodał jeszcze opisu.</em>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sekcja zaproszeń oczekujących (tylko dla własnego profilu) -->
            <?php if ($is_own_profile && count($pendingInvitations) > 0): ?>
                <div class="profile-section">
                    <h2><i class="fas fa-user-plus"></i> Zaproszenia oczekujące (<?php echo count($pendingInvitations); ?>)</h2>

                    <div class="friends-list">
                        <?php foreach ($pendingInvitations as $invitation): ?>
                            <?php
                            $invitationDate = new DateTime($invitation['creation_date']);
                            ?>
                            <div class="friend-item">
                                <div class="friend-avatar-container">
                                    <img src="<?php echo htmlspecialchars($invitation['avatar'] ?? 'default-avatar.png'); ?>"
                                        alt="Avatar <?php echo htmlspecialchars($invitation['login']); ?>"
                                        class="friend-avatar">
                                </div>

                                <div class="friend-info">
                                    <div class="friend-name">
                                        <a href="<?php echo htmlspecialchars($config['pages']['profile-page']['path']); ?>?user_name=<?php echo urlencode($invitation['login']); ?>"
                                            class="friend-link">
                                            <?php echo htmlspecialchars($invitation['login']); ?>
                                        </a>
                                    </div>
                                    <div class="friend-real-name">
                                        <?php echo htmlspecialchars($invitation['name'] . ' ' . $invitation['last_name']); ?>
                                    </div>
                                    <div class="friend-last-seen">
                                        Wysłano: <?php echo $invitationDate->format('d.m.Y H:i'); ?>
                                    </div>
                                </div>

                                <div class="friend-actions">
                                    <button class="accept-invitation-btn invitation-action-btn"
                                        data-user-id="<?php echo htmlspecialchars($invitation['id']); ?>"
                                        title="Zaakceptuj zaproszenie">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="reject-invitation-btn invitation-action-btn"
                                        data-user-id="<?php echo htmlspecialchars($invitation['id']); ?>"
                                        title="Odrzuć zaproszenie">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sekcja wysłanych zaproszeń (tylko dla własnego profilu) -->
            <?php if ($is_own_profile && count($sentInvitations) > 0): ?>
                <div class="profile-section">
                    <h2><i class="fas fa-paper-plane"></i> Wysłane zaproszenia (<?php echo count($sentInvitations); ?>)</h2>

                    <div class="friends-list">
                        <?php foreach ($sentInvitations as $invitation): ?>
                            <?php
                            $invitationDate = new DateTime($invitation['creation_date']);
                            ?>
                            <div class="friend-item">
                                <div class="friend-avatar-container">
                                    <img src="<?php echo htmlspecialchars($invitation['avatar'] ?? 'default-avatar.png'); ?>"
                                        alt="Avatar <?php echo htmlspecialchars($invitation['login']); ?>"
                                        class="friend-avatar">
                                </div>

                                <div class="friend-info">
                                    <div class="friend-name">
                                        <a href="<?php echo htmlspecialchars($config['pages']['profile-page']['path']); ?>?user_name=<?php echo urlencode($invitation['login']); ?>"
                                            class="friend-link">
                                            <?php echo htmlspecialchars($invitation['login']); ?>
                                        </a>
                                    </div>
                                    <div class="friend-real-name">
                                        <?php echo htmlspecialchars($invitation['name'] . ' ' . $invitation['last_name']); ?>
                                    </div>
                                    <div class="friend-last-seen">
                                        Wysłano: <?php echo $invitationDate->format('d.m.Y H:i'); ?>
                                    </div>
                                    <div class="friend-last-seen">
                                        <i class="fas fa-clock"></i> Oczekuje na odpowiedź
                                    </div>
                                </div>

                                <div class="friend-actions">
                                    <button class="cancel-invitation-btn invitation-action-btn"
                                        data-user-id="<?php echo htmlspecialchars($invitation['id']); ?>"
                                        title="Anuluj zaproszenie">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Sekcja znajomych -->
            <div class="profile-section">
                <h2><i class="fas fa-users"></i> Znajomi (<?php echo $friendsCount; ?>)</h2>

                <?php if ($friendsCount > 0): ?>
                    <div class="friends-list">
                        <?php foreach ($friends as $friend): ?>
                            <?php
                            $friendLastLogin = new DateTime($friend['last_login_date']);
                            $now = new DateTime();
                            $timeDiff = $now->diff($friendLastLogin);

                            // Określenie statusu online (jeśli ostatnie logowanie było w ciągu ostatnich 5 minut)
                            $isOnline = ($timeDiff->i < 5 && $timeDiff->h == 0 && $timeDiff->d == 0);
                            ?>
                            <div class="friend-item">
                                <div class="friend-avatar-container">
                                    <img src="<?php echo htmlspecialchars($friend['avatar'] ?? 'default-avatar.png'); ?>"
                                        alt="Avatar <?php echo htmlspecialchars($friend['login']); ?>"
                                        class="friend-avatar">
                                    <?php if ($isOnline): ?>
                                        <span class="online-indicator" title="Online"></span>
                                    <?php endif; ?>
                                </div>

                                <div class="friend-info">
                                    <div class="friend-name">
                                        <a href="<?php echo htmlspecialchars($config['pages']['profile-page']['path']); ?>?user_name=<?php echo urlencode($friend['login']); ?>"
                                            class="friend-link">
                                            <?php echo htmlspecialchars($friend['login']); ?>
                                            <?php if ($friend['is_admin'] == 1): ?>
                                                <i class="fas fa-shield-alt admin-badge" title="Administrator"></i>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="friend-real-name">
                                        <?php echo htmlspecialchars($friend['name'] . ' ' . $friend['last_name']); ?>
                                    </div>
                                    <div class="friend-last-seen">
                                        <?php if ($isOnline): ?>
                                            <span class="online-status">Online</span>
                                        <?php else: ?>
                                            Ostatnio widziany: <?php echo $friendLastLogin->format('d.m.Y H:i'); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="friend-actions">
                                    <a href="<?php echo htmlspecialchars($config['pages']['profile-page']['path']); ?>?user_name=<?php echo urlencode($friend['login']); ?>"
                                        class="view-profile-btn" title="Zobacz profil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-friends-message">
                        <i class="fas fa-user-friends"></i>
                        <p><?php echo $is_own_profile ? 'Nie masz jeszcze znajomych.' : 'Ten użytkownik nie ma jeszcze znajomych.'; ?></p>
                        <?php if ($is_own_profile): ?>
                            <p>Rozpocznij dodawanie znajomych, aby rozszerzyć swoją sieć kontaktów!</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Akcje profilu -->
            <?php if ($is_own_profile): ?>
                <div class="profile-actions">
                    <form action="<?php echo htmlspecialchars($config['pages']['modify-account-page']['path']); ?>" method="POST">
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
                    <button class="friend-action-button loading-button"
                        data-user-id="<?php echo htmlspecialchars($user['id']); ?>"
                        style="opacity: 0.7; pointer-events: none; background-color: #6c757d; color: white;">
                        <i class="fas fa-spinner fa-spin"></i> Ładowanie...
                    </button>
                </div>
            <?php endif; ?>

            <div class="profile-actions">
                <button class="search-button"
                    onclick="window.location.href='<?= htmlspecialchars($config['pages']['search-page']['path'], ENT_QUOTES) ?>?search=<?= urlencode($user['login']) ?>'">
                    <i class="fas fa-search"></i> Wyszukaj w aktywnościach
                </button>
            </div>
        </div>
    </div>

    <!-- Notyfikacje -->
    <div id="notification-container"></div>
</main>
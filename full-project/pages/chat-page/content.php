<?php
// Sprawdź czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $config['pages']['login-page']['path']);
    exit;
}

$current_user_id = $_SESSION['user_id'];
?>
<aside class="chat-container">
<div class="chat-sidebar">
    <div class="chat-header">
        <h2><i class="fas fa-comments"></i> Nowości</h2>
        <div class="chat-search-container">
            <input type="text" id="user-search" placeholder="Wyszukaj użytkownika...">
            <i class="fas fa-search"></i>
        </div>
    </div>

    <div class="users-list" id="users-list">
        <!-- Lista użytkowników będzie wczytana przez AJAX -->
        <div class="loading-users">
            <i class="fas fa-spinner fa-spin"></i> Ładowanie użytkowników...
        </div>
    </div>
</div>
</aside>
<main class="chat-container">
<main class="chat-main">
    <div class="chat-info" id="chat-info">
        <div class="empty-chat">
            <i class="fas fa-comment-alt"></i>
            <p>Wybierz użytkownika, aby rozpocząć rozmowę</p>
        </div>
    </div>

    <div class="chat-messages" id="chat-messages" style="display: none;">
        <div class="messages-header">
            <div class="recipient-info" id="recipient-info"></div>
            <button class="clear-chat" id="clear-chat-btn">
                <i class="fas fa-trash-alt"></i> Wyczyść historię
            </button>
        </div>

        <div class="messages-container" id="messages-container">
            <!-- Wiadomości będą wczytywane przez AJAX -->
        </div>

        <div class="message-input">
            <div class="input-container">
                <input type="text" id="message-input" placeholder="Napisz wiadomość...">
                <div class="input-actions">
                    <button id="send-message-btn">
                        <i class="fas fa-paper-plane"></i> Wyślij
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
</main>
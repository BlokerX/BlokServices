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

<!-- #region Zawartość podstrony chatu -->

<aside class="left-sidebar">
    <div class="sidebar-header">
        <h3>Menu społecznościowe</h3>
    </div>
    <div class="sidebar-content">
        <ul class="sidebar-menu">
            <li><a href="<?php echo $config['pages']['welcome-page']['path']; ?>"><i class="fas fa-home"></i> Strona główna</a></li>
            <li><a href="<?php echo $config['pages']['profile-page']['path']; ?>"><i class="fas fa-user"></i> Mój profil</a></li>
            <li><a href="<?php echo $config['pages']['social-page']['path']; ?>"><i class="fas fa-users"></i> Społeczność</a></li>
            <li><a href="#"><i class="fas fa-user-friends"></i> Znajomi</a></li>
            <li><a href="<?php echo $config['pages']['gallery-page']['path']; ?>"><i class="fas fa-images"></i> Zdjęcia</a></li>
            <li class="active"><a href="#"><i class="fas fa-comments"></i> Chat</a></li>
            <hr>
            <li><a href="#"><i class="fas fa-cog"></i> Ustawienia</a></li>
        </ul>
    </div>
</aside>

<main class="chat-main">
    <div class="chat-container">
        <!-- Lista kontaktów -->
        <aside class="contacts-sidebar">
            <div class="contacts-header">
                <h3>Rozmowy</h3>
                <div class="search-container">
                    <input type="text" id="contact-search" class="contact-search" placeholder="Szukaj rozmów...">
                    <i class="fas fa-search search-icon"></i>
                </div>
            </div>
            
            <div class="contacts-list" id="contacts-list">
                <!-- Kontakty będą ładowane dynamicznie przez JavaScript -->
                <div class="loading-contacts" style="text-align: center; padding: 20px; color: #666;">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Ładowanie kontaktów...</p>
                </div>
            </div>
        </aside>

        <!-- Główny obszar chatu -->
        <div class="chat-content">
            <div class="chat-header">
                <div class="chat-user-info">
                    <img src="./assets/img/avatars/default.png" alt="Kontakt" class="chat-avatar">
                    <div class="chat-user-details">
                        <h3>Wybierz kontakt</h3>
                        <span class="user-status offline">Offline</span>
                    </div>
                </div>
                <div class="chat-actions">
                    <button class="chat-action-btn" title="Połączenie głosowe"><i class="fas fa-phone"></i></button>
                    <button class="chat-action-btn" title="Połączenie wideo"><i class="fas fa-video"></i></button>
                    <button class="chat-action-btn" title="Informacje"><i class="fas fa-info-circle"></i></button>
                </div>
            </div>

            <div class="messages-container" id="messages-container">
                <div class="welcome-message" style="text-align: center; padding: 50px; color: #666;">
                    <i class="fas fa-comments" style="font-size: 48px; margin-bottom: 20px; opacity: 0.5;"></i>
                    <h3>Witaj w chacie!</h3>
                    <p>Wybierz kontakt z listy po lewej stronie, aby rozpocząć rozmowę.</p>
                </div>
            </div>

            <div class="message-input-container">
                <div class="message-input-wrapper">
                    <button class="attachment-btn" title="Załącz plik" disabled><i class="fas fa-paperclip"></i></button>
                    <button class="emoji-btn" title="Emoji" disabled><i class="fas fa-smile"></i></button>
                    <textarea id="message-input" class="message-input" placeholder="Napisz wiadomość..." rows="1" disabled></textarea>
                    <button id="send-message-btn" class="send-message-btn" title="Wyślij" disabled><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
</main>

<aside class="right-sidebar">
    <div class="sidebar-header">
        <h3>Online teraz</h3>
    </div>
    <div class="sidebar-content">
        <div class="online-users-container" id="online-users-container">
            <div class="loading-online" style="text-align: center; padding: 20px; color: #666;">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Ładowanie...</p>
            </div>
        </div>
    </div>

    <div class="sidebar-header">
        <h3>Grupy</h3>
    </div>
    <div class="sidebar-content">
        <div class="groups-container">
            <div class="group-item">
                <div class="group-avatar">
                    <i class="fas fa-users"></i>
                </div>
                <div class="group-info">
                    <h4>Programiści</h4>
                    <p>12 członków</p>
                </div>
            </div>

            <div class="group-item">
                <div class="group-avatar">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="group-info">
                    <h4>Web Development</h4>
                    <p>8 członków</p>
                </div>
            </div>

            <div class="group-item">
                <div class="group-avatar">
                    <i class="fas fa-coffee"></i>
                </div>
                <div class="group-info">
                    <h4>Kawka o 15:00</h4>
                    <p>5 członków</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Dodatkowe elementy UI -->
<div class="typing-indicator" id="typing-indicator" style="display: none;">
    <img src="./assets/img/avatars/default.png" alt="Kontakt" class="message-avatar">
    <div class="typing-bubble">
        <div class="typing-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>

<!-- Ładowanie skryptu JavaScript -->
<script src="./chat-page.js"></script>

<style>
/* Dodatkowe style dla lepszego UX */
.loading-contacts, .loading-online {
    opacity: 0.7;
}

.welcome-message {
    background: #f8f9fa;
    border-radius: 10px;
    margin: 20px;
}

.message-input-container button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.message-input:disabled {
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.contact-item {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.contact-item:hover {
    background-color: #f0f0f0;
}

.contact-item.active {
    background-color: #007bff;
    color: white;
}

.contact-item.active .contact-info p {
    color: rgba(255, 255, 255, 0.8);
}

.unread-count {
    background: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    min-width: 18px;
    text-align: center;
}

.online-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
}

.online-indicator.online {
    background-color: #28a745;
}

.online-indicator.away {
    background-color: #ffc107;
}

.online-indicator.offline {
    background-color: #6c757d;
}

.message-group {
    margin: 10px 0;
}

.message-group.sent {
    text-align: right;
}

.message-bubble {
    display: inline-block;
    padding: 8px 12px;
    border-radius: 18px;
    max-width: 70%;
    word-wrap: break-word;
}

.message.sent .message-bubble {
    background-color: #007bff;
    color: white;
}

.message.received .message-bubble {
    background-color: #e9ecef;
    color: #333;
}

.message-timestamp {
    font-size: 11px;
    opacity: 0.7;
    margin-left: 8px;
}

.typing-dots {
    display: flex;
    align-items: center;
    gap: 3px;
    padding: 8px 12px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #999;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% {
        transform: translateY(0);
    }
    30% {
        transform: translateY(-10px);
    }
}

.chat-action-btn {
    background: none;
    border: none;
    padding: 8px;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.chat-action-btn:hover {
    background-color: #f0f0f0;
}

.send-message-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<!-- #endregion -->
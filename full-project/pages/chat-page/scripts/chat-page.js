// Plik JavaScript obsługujący podstronę chatu
document.addEventListener('DOMContentLoaded', () => {
    // Inicjalizacja podstrony chatu
    initializeChatPage();
    
    // Pobierz listę użytkowników
    loadUsers();
    
    // Ustawienie obsługi zdarzeń
    setupEventListeners();
    
    // Ustawienie interwału do odświeżania wiadomości
    setInterval(() => {
        if (currentRecipientId) {
            loadMessages(currentRecipientId);
        }
    }, 5000); // Odświeżaj co 5 sekund
});

let currentRecipientId = null;
let currentUserId = null;
let usersData = {}; // Przechowuje dane użytkowników dla szybkiego dostępu

// Inicjalizacja podstrony chatu
function initializeChatPage() {
    console.log("Inicjalizacja podstrony chatu...");
    // Pobierz ID aktualnego użytkownika z PHP sesji
    getCurrentUserId();
}

// Pobierz ID aktualnego użytkownika
function getCurrentUserId() {
    // Dodaj ukryte pole w HTML z ID użytkownika lub pobierz z API
    fetch('../../api/chat-page/get_users.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Zakładamy, że serwer zwraca także current_user_id
                // Jeśli nie, dodaj to do get_users.php
                if (data.current_user_id) {
                    currentUserId = data.current_user_id;
                }
            }
        })
        .catch(error => {
            console.error('Błąd podczas pobierania ID użytkownika:', error);
        });
}

// Ustawienie obsługi zdarzeń
function setupEventListeners() {
    // Wyszukiwanie użytkowników
    const searchInput = document.getElementById('user-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterUsers(this.value);
        });
    }
    
    // Wysyłanie wiadomości
    const sendBtn = document.getElementById('send-message-btn');
    if (sendBtn) {
        sendBtn.addEventListener('click', sendMessage);
    }
    
    // Wysyłanie wiadomości na Enter
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
    
    // Czyszczenie historii czatu
    const clearBtn = document.getElementById('clear-chat-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (currentRecipientId) {
                clearChatHistory(currentRecipientId);
            }
        });
    }
}

// Pobierz listę użytkowników
function loadUsers() {
    const usersList = document.getElementById('users-list');
    if (usersList) {
        usersList.innerHTML = '<div class="loading-users"><i class="fas fa-spinner fa-spin"></i> Ładowanie użytkowników...</div>';
    }

    fetch('../../api/chat-page/get_users.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.status === 'success') {
                    // Zapisz dane użytkowników dla późniejszego użycia
                    data.users.forEach(user => {
                        usersData[user.id] = user;
                    });
                    renderUsers(data.users);
                } else {
                    throw new Error(data.message || 'Nieznany błąd serwera');
                }
            } catch (parseError) {
                console.error('Błąd parsowania JSON:', text);
                throw new Error('Nieprawidłowa odpowiedź serwera');
            }
        })
        .catch(error => {
            console.error('Błąd:', error);
            showNotification('Wystąpił błąd podczas ładowania użytkowników: ' + error.message, 'error');
            const usersList = document.getElementById('users-list');
            if (usersList) {
                usersList.innerHTML = '<div class="error-message">Błąd ładowania użytkowników</div>';
            }
        });
}

// Wyświetl listę użytkowników
function renderUsers(users) {
    const usersList = document.getElementById('users-list');
    if (!usersList) return;
    
    usersList.innerHTML = '';
    
    if (users.length === 0) {
        usersList.innerHTML = '<div class="no-users">Brak innych użytkowników</div>';
        return;
    }
    
    users.forEach(user => {
        const userElement = document.createElement('div');
        userElement.className = 'user-item';
        userElement.dataset.userId = user.id;
        
        const avatarSrc = user.avatar && user.avatar.trim() !== '' ? user.avatar : 'assets/default-avatar.png';
        const fullName = `${user.name || ''} ${user.last_name || ''}`.trim();
        const displayName = fullName || user.login;
        
        userElement.innerHTML = `
            <div class="chat-user-avatar">
                <img class="mini-user-avatar" src="${avatarSrc}" alt="${displayName}" onerror="this.src='assets/default-avatar.png'">
            </div>
            <div class="user-info">
                <div class="user-name">${displayName}</div>
                <div class="user-login">@${user.login}</div>
            </div>
            <div class="user-status">
                ${parseInt(user.is_online) ? '<i class="fas fa-circle online"></i> Online' : '<i class="fas fa-circle offline"></i> Offline'}
            </div>
        `;
        
        userElement.addEventListener('click', () => {
            // Usuń klasę active z wszystkich elementów
            document.querySelectorAll('.user-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Dodaj klasę active do klikniętego elementu
            userElement.classList.add('active');
            
            // Wyświetl czat z wybranym użytkownikiem
            selectUser(user.id, user.name || '', user.last_name || '', user.login, avatarSrc);
        });
        
        usersList.appendChild(userElement);
    });
}

// Filtruj użytkowników
function filterUsers(searchTerm) {
    const userItems = document.querySelectorAll('.user-item');
    const searchTermLower = searchTerm.toLowerCase();
    
    userItems.forEach(item => {
        const userName = item.querySelector('.user-name').textContent.toLowerCase();
        const userLogin = item.querySelector('.user-login').textContent.toLowerCase();
        
        if (userName.includes(searchTermLower) || userLogin.includes(searchTermLower)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Wybierz użytkownika do rozmowy
function selectUser(userId, firstName, lastName, login, avatarSrc) {
    currentRecipientId = userId;
    
    const fullName = `${firstName} ${lastName}`.trim();
    const displayName = fullName || login;
    
    // Aktualizuj informacje o odbiorcy
    const recipientInfo = document.getElementById('recipient-info');
    if (recipientInfo) {
        recipientInfo.innerHTML = `
            <div class="avatar">
                <img src="${avatarSrc}" alt="${displayName}" onerror="this.src='assets/default-avatar.png'">
            </div>
            <div class="info">
                <div class="name">${displayName}</div>
                <div class="status">Rozmawiasz</div>
            </div>
        `;
    }
    
    // Pokaż okno wiadomości
    const chatInfo = document.getElementById('chat-info');
    const chatMessages = document.getElementById('chat-messages');
    
    if (chatInfo) chatInfo.style.display = 'none';
    if (chatMessages) chatMessages.style.display = 'block';
    
    // Załaduj wiadomości
    loadMessages(userId);
    
    // Ustaw focus na polu wiadomości
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        setTimeout(() => messageInput.focus(), 100);
    }
}

// Pobierz wiadomości z danym użytkownikiem
async function loadMessages(recipientId) {
    console.log(`Ładowanie wiadomości dla odbiorcy ID: ${recipientId}`);
    
    try {
        const response = await fetch(`../../api/chat-page/get_messages.php?recipient_id=${recipientId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const text = await response.text();
        let data;
        
        try {
            data = JSON.parse(text);
        } catch (parseError) {
            console.error('Invalid JSON:', text);
            throw new Error('Nieprawidłowa odpowiedź serwera');
        }
        
        if (data.status === 'success') {
            renderMessages(data.messages);
        } else {
            throw new Error(data.message || 'Nieznany błąd podczas ładowania wiadomości');
        }
    } catch (error) {
        console.error('Błąd:', error);
        showNotification('Wystąpił błąd podczas ładowania wiadomości: ' + error.message, 'error');
    }
}

// Wyświetl wiadomości
function renderMessages(messages) {
    const messagesContainer = document.getElementById('messages-container');
    if (!messagesContainer) return;
    
    messagesContainer.innerHTML = '';
    
    if (messages.length === 0) {
        messagesContainer.innerHTML = `
            <div class="no-messages">
                <i class="fas fa-comment-slash"></i>
                <p>Brak wiadomości. Rozpocznij rozmowę!</p>
            </div>
        `;
        return;
    }
    
    messages.forEach(message => {
        const isCurrentUser = parseInt(message.sender_user_id) === parseInt(currentUserId);
        const messageElement = document.createElement('div');
        messageElement.className = `message ${isCurrentUser ? 'sent' : 'received'}`;
        messageElement.dataset.messageId = message.id;
        
        // Pobierz dane nadawcy
        const senderData = usersData[message.sender_user_id];
        const senderName = senderData ? 
            `${senderData.name || ''} ${senderData.last_name || ''}`.trim() || senderData.login :
            'Nieznany użytkownik';
        
        messageElement.innerHTML = `
            <div class="message-content" title="Wiadomość od: ${senderName}">
                <div class="text">${escapeHtml(message.content)}</div>
                <div class="time">${formatTime(message.creation_date)}</div>
            </div>
            ${isCurrentUser ? `
            <div class="message-actions">
                <button class="edit-message-btn" title="Edytuj wiadomość">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="delete-message-btn" title="Usuń wiadomość">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            ` : ''}
        `;
        
        // Dodaj obsługę edycji i usuwania dla własnych wiadomości
        if (isCurrentUser) {
            const editBtn = messageElement.querySelector('.edit-message-btn');
            const deleteBtn = messageElement.querySelector('.delete-message-btn');
            
            if (editBtn) {
                editBtn.addEventListener('click', () => editMessage(message.id, message.content));
            }
            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => deleteMessage(message.id));
            }
        }
        
        messagesContainer.appendChild(messageElement);
    });
    
    // Przewiń do ostatniej wiadomości
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Funkcja do escapowania HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Formatuj czas wiadomości
function formatTime(dateString) {
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return 'Nieznana data';
        }
        
        const now = new Date();
        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const messageDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        
        if (messageDate.getTime() === today.getTime()) {
            // Dzisiaj - pokaż tylko czas
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else if (messageDate.getTime() === today.getTime() - 86400000) {
            // Wczoraj
            return 'Wczoraj ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        } else {
            // Starsze - pokaż datę i czas
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    } catch (error) {
        console.error('Błąd formatowania daty:', error);
        return 'Nieznana data';
    }
}

// Wyślij wiadomość
function sendMessage() {
    const messageInput = document.getElementById('message-input');
    if (!messageInput) return;
    
    const content = messageInput.value.trim();
    
    if (!content) {
        showNotification('Wiadomość nie może być pusta', 'warning');
        return;
    }
    
    if (!currentRecipientId) {
        showNotification('Wybierz użytkownika, aby wysłać wiadomość', 'warning');
        return;
    }
    
    // Zablokuj przycisk wysyłania
    const sendBtn = document.getElementById('send-message-btn');
    if (sendBtn) {
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Wysyłanie...';
    }
    
    const formData = new FormData();
    formData.append('recipient_id', currentRecipientId);
    formData.append('content', content);
    
    fetch('../../api/chat-page/send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Wyczyść pole wejściowe
            messageInput.value = '';
            
            // Odśwież wiadomości
            loadMessages(currentRecipientId);
            
            showNotification('Wiadomość została wysłana', 'success');
        } else {
            throw new Error(data.message || 'Nieznany błąd podczas wysyłania');
        }
    })
    .catch(error => {
        console.error('Błąd:', error);
        showNotification('Wystąpił błąd podczas wysyłania wiadomości: ' + error.message, 'error');
    })
    .finally(() => {
        // Odblokuj przycisk wysyłania
        if (sendBtn) {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Wyślij';
        }
    });
}

// Edytuj wiadomość
function editMessage(messageId, currentContent) {
    const newContent = prompt('Edytuj wiadomość:', currentContent);
    
    if (newContent === null || newContent.trim() === currentContent.trim()) {
        return; // Anulowano lub bez zmian
    }
    
    if (!newContent.trim()) {
        showNotification('Wiadomość nie może być pusta', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('message_id', messageId);
    formData.append('content', newContent.trim());
    
    fetch('../../api/chat-page/update_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            loadMessages(currentRecipientId);
            showNotification('Wiadomość została zaktualizowana', 'success');
        } else {
            throw new Error(data.message || 'Nieznany błąd podczas edycji');
        }
    })
    .catch(error => {
        console.error('Błąd:', error);
        showNotification('Wystąpił błąd podczas edycji wiadomości: ' + error.message, 'error');
    });
}

// Usuń wiadomość
function deleteMessage(messageId) {
    if (!confirm('Czy na pewno chcesz usunąć tę wiadomość?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('message_id', messageId);
    
    fetch('../../api/chat-page/delete_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            loadMessages(currentRecipientId);
            showNotification('Wiadomość została usunięta', 'success');
        } else {
            throw new Error(data.message || 'Nieznany błąd podczas usuwania');
        }
    })
    .catch(error => {
        console.error('Błąd:', error);
        showNotification('Wystąpił błąd podczas usuwania wiadomości: ' + error.message, 'error');
    });
}

// Wyczyść historię czatu
function clearChatHistory(recipientId) {
    if (!confirm('Czy na pewno chcesz wyczyścić całą historię rozmowy? Tej operacji nie można cofnąć.')) {
        return;
    }
    
    fetch(`../../api/chat-page/clear_chat.php?recipient_id=${recipientId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showNotification('Historia rozmowy została wyczyszczona', 'success');
            loadMessages(recipientId);
        } else {
            throw new Error(data.message || 'Nieznany błąd podczas czyszczenia');
        }
    })
    .catch(error => {
        console.error('Błąd:', error);
        showNotification('Wystąpił błąd podczas czyszczenia historii: ' + error.message, 'error');
    });
}

// Ulepszona funkcja wyświetlająca powiadomienie
function showNotification(message, type = 'info') {
    // Sprawdź czy istnieje kontener na powiadomienia
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        // Stwórz kontener jeśli nie istnieje
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
        `;
        document.body.appendChild(notificationContainer);
    }
    
    // Stwórz element powiadomienia
    const notification = document.createElement('div');
    notification.style.cssText = `
        padding: 12px 16px;
        margin-bottom: 10px;
        border-radius: 6px;
        color: white;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Ustaw kolor na podstawie typu
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    notification.textContent = message;
    
    // Dodaj do kontenera
    notificationContainer.appendChild(notification);
    
    // Animacja wejścia
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Usuń po 5 sekundach
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}
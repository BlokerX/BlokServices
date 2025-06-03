// chat-page.js - Główny plik JavaScript dla funkcjonalności chatu

class ChatManager {
    constructor() {
        this.currentContactId = null;
        this.currentUserId = null;
        this.isTyping = false;
        this.typingTimeout = null;
        this.messagesContainer = document.getElementById('messages-container');
        this.messageInput = document.getElementById('message-input');
        this.sendButton = document.getElementById('send-message-btn');
        this.contactsList = document.getElementById('contacts-list');
        this.contactSearch = document.getElementById('contact-search');
        this.typingIndicator = document.getElementById('typing-indicator');
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.getCurrentUser().then(() => {
            this.loadContacts().then(() => {
                // Ustaw pierwszego kontaktu jako aktywnego po załadowaniu danych
                const firstContact = document.querySelector('.contact-item[data-contact-id]');
                if (firstContact) {
                    this.selectContact(firstContact);
                }
            });
        });
        this.startPolling();
    }

    setupEventListeners() {
        // Wysyłanie wiadomości
        if (this.sendButton) {
            this.sendButton.addEventListener('click', () => this.sendMessage());
        }
        
        if (this.messageInput) {
            this.messageInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }
            });

            // Auto-resize textarea
            this.messageInput.addEventListener('input', () => {
                this.autoResizeTextarea();
                this.handleTyping();
            });
        }

        // Wybór kontaktu
        if (this.contactsList) {
            this.contactsList.addEventListener('click', (e) => {
                const contactItem = e.target.closest('.contact-item');
                if (contactItem && contactItem.dataset.contactId) {
                    this.selectContact(contactItem);
                }
            });
        }

        // Wyszukiwanie kontaktów
        if (this.contactSearch) {
            this.contactSearch.addEventListener('input', (e) => {
                this.searchContacts(e.target.value);
            });
        }

        // Obsługa przycisków akcji
        document.querySelectorAll('.chat-action-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const icon = e.currentTarget.querySelector('i');
                if (icon.classList.contains('fa-phone')) {
                    this.startVoiceCall();
                } else if (icon.classList.contains('fa-video')) {
                    this.startVideoCall();
                } else if (icon.classList.contains('fa-info-circle')) {
                    this.showContactInfo();
                }
            });
        });
    }

    async getCurrentUser() {
        try {
            const response = await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_current_user'
                })
            });

            const data = await response.json();
            if (data.success) {
                this.currentUserId = data.user.id;
                console.log('Current user ID:', this.currentUserId);
            } else {
                console.error('Błąd pobierania użytkownika:', data.message);
            }
        } catch (error) {
            console.error('Błąd podczas pobierania danych użytkownika:', error);
        }
    }

    async loadContacts() {
        try {
            const response = await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_contacts'
                })
            });

            const data = await response.json();
            if (data.success) {
                this.renderContacts(data.contacts);
            } else {
                console.error('Błąd ładowania kontaktów:', data.message);
            }
        } catch (error) {
            console.error('Błąd podczas ładowania kontaktów:', error);
        }
    }

    renderContacts(contacts) {
        if (!this.contactsList) return;
        
        this.contactsList.innerHTML = '';
        contacts.forEach((contact, index) => {
            const contactElement = this.createContactElement(contact, index === 0);
            this.contactsList.appendChild(contactElement);
        });
    }

    createContactElement(contact, isActive = false) {
        const div = document.createElement('div');
        div.className = `contact-item${isActive ? ' active' : ''}`;
        div.dataset.contactId = contact.id;
        
        const avatarSrc = contact.avatar || './assets/img/avatars/default.png';
        const lastMessage = contact.last_message ? 
            (contact.last_message.length > 30 ? contact.last_message.substring(0, 30) + '...' : contact.last_message) : 
            'Brak wiadomości';
        
        div.innerHTML = `
            <img src="${avatarSrc}" 
                 alt="${contact.name} ${contact.last_name}" class="contact-avatar">
            <div class="contact-info">
                <h4>${contact.name} ${contact.last_name}</h4>
                <p class="last-message">${lastMessage}</p>
            </div>
            <div class="contact-status">
                <div class="online-indicator ${contact.status || 'offline'}"></div>
                ${contact.unread_count && contact.unread_count > 0 ? 
                    `<span class="unread-count">${contact.unread_count}</span>` : ''}
            </div>
        `;
        
        return div;
    }

    selectContact(contactElement) {
        // Usuń aktywny stan z wszystkich kontaktów
        document.querySelectorAll('.contact-item').forEach(item => {
            item.classList.remove('active');
        });
        
        // Dodaj aktywny stan do wybranego kontaktu
        contactElement.classList.add('active');
        
        // Pobierz ID kontaktu i załaduj wiadomości
        this.currentContactId = contactElement.dataset.contactId;
        console.log('Selected contact ID:', this.currentContactId);
        
        this.loadMessages(this.currentContactId);
        this.updateChatHeader(contactElement);
        
        // Oznacz wiadomości jako przeczytane
        this.markMessagesAsRead(this.currentContactId);
    }

    updateChatHeader(contactElement) {
        const contactInfo = contactElement.querySelector('.contact-info h4').textContent;
        const contactAvatar = contactElement.querySelector('.contact-avatar').src;
        const onlineStatus = contactElement.querySelector('.online-indicator').classList;
        
        const chatUserDetails = document.querySelector('.chat-user-details h3');
        const chatAvatar = document.querySelector('.chat-avatar');
        const statusElement = document.querySelector('.user-status');
        
        if (chatUserDetails) chatUserDetails.textContent = contactInfo;
        if (chatAvatar) chatAvatar.src = contactAvatar;
        
        if (statusElement) {
            statusElement.className = 'user-status';
            if (onlineStatus.contains('online')) {
                statusElement.classList.add('online');
                statusElement.textContent = 'Online';
            } else if (onlineStatus.contains('away')) {
                statusElement.classList.add('away');
                statusElement.textContent = 'Zaraz wracam';
            } else {
                statusElement.classList.add('offline');
                statusElement.textContent = 'Offline';
            }
        }
    }

    async loadMessages(contactId) {
        if (!contactId) return;
        
        try {
            const response = await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'get_messages',
                    contact_id: contactId
                })
            });

            const data = await response.json();
            if (data.success) {
                this.renderMessages(data.messages);
                this.scrollToBottom();
            } else {
                console.error('Błąd ładowania wiadomości:', data.message);
            }
        } catch (error) {
            console.error('Błąd podczas ładowania wiadomości:', error);
        }
    }

    renderMessages(messages) {
        if (!this.messagesContainer) return;
        
        this.messagesContainer.innerHTML = '';
        
        let currentGroup = null;
        let currentSender = null;
        
        messages.forEach(message => {
            const isCurrentUser = parseInt(message.sender_user_id) === parseInt(this.currentUserId);
            
            // Sprawdź czy tworzyć nową grupę wiadomości
            if (currentSender !== message.sender_user_id) {
                currentGroup = document.createElement('div');
                currentGroup.className = `message-group${isCurrentUser ? ' sent' : ''}`;
                this.messagesContainer.appendChild(currentGroup);
                currentSender = message.sender_user_id;
            }
            
            const messageElement = this.createMessageElement(message, isCurrentUser);
            currentGroup.appendChild(messageElement);
        });
    }

    createMessageElement(message, isCurrentUser) {
        const div = document.createElement('div');
        div.className = `message${isCurrentUser ? ' sent' : ' received'}`;
        
        const timestamp = new Date(message.creation_date).toLocaleTimeString('pl-PL', {
            hour: '2-digit',
            minute: '2-digit'
        });
        
        const avatarSrc = message.sender_avatar || './assets/img/avatars/default.png';
        
        div.innerHTML = `
            ${!isCurrentUser ? `<img src="${avatarSrc}" 
                                     alt="${message.sender_name}" class="message-avatar">` : ''}
            <div class="message-content">
                <div class="message-bubble">
                    <p>${this.formatMessage(message.content)}</p>
                    <span class="message-timestamp">${timestamp}</span>
                </div>
            </div>
        `;
        
        return div;
    }

    formatMessage(content) {
        // Podstawowe formatowanie wiadomości (linki, emotikony, itp.)
        return content
            .replace(/\n/g, '<br>')
            .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank" rel="noopener">$1</a>')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    async sendMessage() {
        if (!this.messageInput) return;
        
        const content = this.messageInput.value.trim();
        if (!content || !this.currentContactId) return;
        
        // Wyłącz przycisk podczas wysyłania
        if (this.sendButton) {
            this.sendButton.disabled = true;
        }
        
        try {
            const response = await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'send_message',
                    receiver_id: this.currentContactId,
                    content: content
                })
            });

            const data = await response.json();
            if (data.success) {
                this.messageInput.value = '';
                this.autoResizeTextarea();
                this.loadMessages(this.currentContactId);
                this.loadContacts(); // Odśwież listę kontaktów
            } else {
                alert('Błąd podczas wysyłania wiadomości: ' + data.message);
            }
        } catch (error) {
            console.error('Błąd podczas wysyłania wiadomości:', error);
            alert('Wystąpił błąd podczas wysyłania wiadomości');
        } finally {
            // Włącz przycisk z powrotem
            if (this.sendButton) {
                this.sendButton.disabled = false;
            }
        }
    }

    async markMessagesAsRead(contactId) {
        if (!contactId) return;
        
        try {
            await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'mark_as_read',
                    contact_id: contactId
                })
            });
        } catch (error) {
            console.error('Błąd podczas oznaczania wiadomości jako przeczytane:', error);
        }
    }

    handleTyping() {
        if (!this.isTyping && this.currentContactId) {
            this.isTyping = true;
            this.sendTypingStatus(true);
        }
        
        // Reset timera
        clearTimeout(this.typingTimeout);
        this.typingTimeout = setTimeout(() => {
            this.isTyping = false;
            this.sendTypingStatus(false);
        }, 1000);
    }

    async sendTypingStatus(isTyping) {
        if (!this.currentContactId) return;
        
        try {
            await fetch('../../api/chat-page/chat_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'typing_status',
                    contact_id: this.currentContactId,
                    is_typing: isTyping
                })
            });
        } catch (error) {
            console.error('Błąd podczas wysyłania statusu pisania:', error);
        }
    }

    searchContacts(query) {
        const contactItems = document.querySelectorAll('.contact-item');
        contactItems.forEach(item => {
            const nameElement = item.querySelector('.contact-info h4');
            if (nameElement) {
                const name = nameElement.textContent.toLowerCase();
                if (name.includes(query.toLowerCase())) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    }

    autoResizeTextarea() {
        if (!this.messageInput) return;
        
        this.messageInput.style.height = 'auto';
        this.messageInput.style.height = Math.min(this.messageInput.scrollHeight, 100) + 'px';
    }

    scrollToBottom() {
        if (this.messagesContainer) {
            this.messagesContainer.scrollTop = this.messagesContainer.scrollHeight;
        }
    }

    startPolling() {
        // Odświeżaj wiadomości co 5 sekund
        setInterval(() => {
            if (this.currentContactId) {
                this.loadMessages(this.currentContactId);
            }
            this.loadContacts();
        }, 5000);
    }

    // Funkcje dla przycisków akcji (do implementacji w przyszłości)
    startVoiceCall() {
        if (this.currentContactId) {
            console.log('Rozpoczynanie połączenia głosowego z kontaktem:', this.currentContactId);
            alert('Funkcja połączeń głosowych będzie dostępna wkrótce!');
        }
    }

    startVideoCall() {
        if (this.currentContactId) {
            console.log('Rozpoczynanie połączenia wideo z kontaktem:', this.currentContactId);
            alert('Funkcja połączeń wideo będzie dostępna wkrótce!');
        }
    }

    showContactInfo() {
        if (this.currentContactId) {
            console.log('Wyświetlanie informacji o kontakcie:', this.currentContactId);
            alert('Szczegółowe informacje o kontakcie będą dostępne wkrótce!');
        }
    }
}

// Inicjalizacja po załadowaniu strony
document.addEventListener('DOMContentLoaded', () => {
    console.log('Inicjalizacja ChatManager...');
    window.chatManager = new ChatManager();
});
/**
 * Główna funkcja inicjalizacji podstrony profilu
 */
function initializeSubpage() {
    console.log("Inicjalizacja podstrony profilu...");
    
    // Inicjalizacja przycisków profilu
    initializeProfileActions();
    
    // Inicjalizacja modala potwierdzenia usunięcia konta
    initializeDeleteConfirmation();
    
    // Inicjalizacja statusu znajomości
    initializeFriendshipStatus();
    
    // Inicjalizacja systemu notyfikacji
    initializeNotificationSystem();
    
}

/**
 * Inicjalizacja systemu notyfikacji
 */
function initializeNotificationSystem() {
    // Sprawdź czy kontener notyfikacji istnieje, jeśli nie - stwórz go
    if (!document.getElementById('notification-container')) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 300px;
        `;
        document.body.appendChild(container);
    }
}

/**
 * Inicjalizacja stanu przycisku znajomości przy ładowaniu strony
 */
function initializeFriendshipStatus() {
    const friendButton = document.querySelector('.friend-action-button');
    if (!friendButton) return;
    
    const userId = friendButton.getAttribute('data-user-id');
    if (!userId) {
        console.error('Nie można znaleźć ID użytkownika');
        return;
    }
    
    const targetUserId = parseInt(userId);
    
    // Pobierz aktualny status znajomości
    fetch('../../api/profile-page/get_friendship_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `targetUserId=${targetUserId}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            updateFriendButton(data.friendshipStatus);
        } else {
            console.error('Błąd pobierania statusu:', data.message);
            updateFriendButton('none'); // Domyślny stan
        }
    })
    .catch(error => {
        console.error('Błąd przy pobieraniu statusu znajomości:', error);
        updateFriendButton('none'); // Domyślny stan
    });
}

/**
 * Aktualizuje wygląd przycisku znajomego na podstawie statusu
 * @param {string} status - Nowy status znajomości
 */
function updateFriendButton(status) {
    const friendButton = document.querySelector('.friend-action-button');
    if (!friendButton) return;
    
    // Definicje stanów przycisku
    const buttonStates = {
        'none': {
            html: '<i class="fas fa-user-plus"></i> Dodaj do znajomych',
            className: 'add-friend-button',
            style: { 
                opacity: '1', 
                pointerEvents: 'auto',
                backgroundColor: '#007bff',
                color: 'white'
            }
        },
        'request_sent': {
            html: '<i class="fas fa-user-times"></i> Anuluj zaproszenie',
            className: 'cancel-request-button',
            style: { 
                opacity: '1', 
                pointerEvents: 'auto',
                backgroundColor: '#ffc107',
                color: 'white'
            }
        },
        'request_received': {
            html: '<i class="fas fa-user-check"></i> Zaakceptuj zaproszenie',
            className: 'accept-request-button',
            style: { 
                opacity: '1', 
                pointerEvents: 'auto',
                backgroundColor: '#28a745',
                color: 'white'
            }
        },
        'friends': {
            html: '<i class="fas fa-user-minus"></i> Usuń ze znajomych',
            className: 'remove-friend-button',
            style: { 
                opacity: '1', 
                pointerEvents: 'auto',
                backgroundColor: '#dc3545',
                color: 'white'
            }
        },
        'loading': {
            html: '<i class="fas fa-spinner fa-spin"></i> Ładowanie...',
            className: 'loading-button',
            style: { 
                opacity: '0.7', 
                pointerEvents: 'none',
                backgroundColor: '#6c757d',
                color: 'white'
            }
        }
    };
    
    const buttonState = buttonStates[status];
    if (!buttonState) {
        console.error('Nieznany status przycisku:', status);
        return;
    }
    
    // Aktualizuj treść przycisku
    friendButton.innerHTML = buttonState.html;
    
    // Usuń wszystkie poprzednie klasy związane z przyciskiem znajomości
    friendButton.className = 'friend-action-button ' + buttonState.className;
    
    // Ustaw style
    Object.assign(friendButton.style, buttonState.style);
    
    // Ustaw stan disabled
    friendButton.disabled = (status === 'loading');
}

/**
 * Funkcja obsługująca zmianę statusu znajomości
 * @param {number} targetUserId - ID użytkownika docelowego
 */
function toggleFriendship(targetUserId) {
    // Ustaw stan ładowania
    updateFriendButton('loading');
    
    // Wyślij żądanie AJAX
    fetch('../../api/profile-page/add_friend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `targetUserId=${targetUserId}&action=toggle`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showNotification(data.message, 'success');
            updateFriendButton(data.newStatus);
        } else {
            showNotification(data.message || 'Wystąpił błąd', 'error');
            // W przypadku błędu, odśwież status przycisku
            initializeFriendshipStatus();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Wystąpił błąd podczas wysyłania żądania', 'error');
        // W przypadku błędu, odśwież status przycisku
        initializeFriendshipStatus();
    });
}

/**
 * Ulepszona funkcja dodająca użytkownika do znajomych z obsługą stanów
 * @param {string} userName - Nazwa użytkownika do dodania
 */
function addFriend(userName) {
    // Ustaw stan ładowania
    updateFriendButton('loading');
    
    // Pobierz ID użytkownika z strony
    const userIdElement = document.querySelector('.profile-user-info p');
    const userIdText = userIdElement ? userIdElement.textContent : '';
    const userId = userIdText.match(/ID użytkownika: (\d+)/);
    
    if (!userId || !userId[1]) {
        showNotification('Nie można znaleźć ID użytkownika', 'error');
        updateFriendButton('none'); // Przywróć domyślny stan
        return;
    }
    
    const targetUserId = parseInt(userId[1]);
    
    // Wyślij żądanie AJAX
    fetch('../../api/profile-page/add_friend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `targetUserId=${targetUserId}&action=toggle`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showNotification(data.message, 'success');
            updateFriendButton(data.newStatus);
        } else {
            showNotification(data.message || 'Wystąpił błąd', 'error');
            // W przypadku błędu, odśwież status przycisku
            initializeFriendshipStatus();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Wystąpił błąd podczas wysyłania żądania', 'error');
        // W przypadku błędu, odśwież status przycisku
        initializeFriendshipStatus();
    });
}

function initializeDeleteConfirmation() {
        const deleteButton = document.querySelector('.delete-account-button');
        const deleteModal = document.querySelector('#deleteConfirmationModal');
        const confirmDeleteButton = document.querySelector('#confirmDelete');
        const cancelDeleteButton = document.querySelector('#cancelDelete');

        if (deleteButton && deleteModal) {
            // Show modal when delete button is clicked
            deleteButton.addEventListener('click', (e) => {
                e.preventDefault();
                deleteModal.style.display = 'block';
            });

            // Handle confirm delete
            if (confirmDeleteButton) {
                confirmDeleteButton.addEventListener('click', () => {
                    const form = document.querySelector('#deleteAccountForm');
                    if (form) form.submit();
                });
            }

            // Handle cancel delete
            if (cancelDeleteButton) {
                cancelDeleteButton.addEventListener('click', () => {
                    deleteModal.style.display = 'none';
                });
            }

            // Close modal when clicking outside
            window.addEventListener('click', (e) => {
                if (e.target === deleteModal) {
                    deleteModal.style.display = 'none';
                }
            });
        }
    }

/**
 * Aktualizowana funkcja inicjalizacji przycisków akcji profilu
 */
function initializeProfileActions() {
    // Przycisk edycji profilu
    const modifyButton = document.querySelector('.modify-button');
    if (modifyButton) {
        modifyButton.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    }
    
    // Delegacja zdarzeń dla przycisku znajomości (obsługuje wszystkie warianty)
    document.addEventListener('click', function(e) {
        if (e.target.matches('.add-friend-button, .cancel-request-button, .accept-request-button, .remove-friend-button')) {
            e.preventDefault();
            const userName = document.querySelector('.profile-user-info h1').textContent.replace('Profil użytkownika: ', '');
            addFriend(userName);
        }
    });
}

// Dodatkowe funkcje pomocnicze

/**
 * Funkcja do wyświetlania animacji ładowania na przycisku
 * @param {HTMLElement} button - Element przycisku
 * @param {boolean} show - Czy pokazać animację ładowania
 */
function toggleButtonLoading(button, show) {
    if (show) {
        button.setAttribute('data-original-content', button.innerHTML);
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Ładowanie...';
        button.disabled = true;
        button.style.opacity = '0.7';
    } else {
        const originalContent = button.getAttribute('data-original-content');
        if (originalContent) {
            button.innerHTML = originalContent;
            button.removeAttribute('data-original-content');
        }
        button.disabled = false;
        button.style.opacity = '1';
    }
}

/**
 * Funkcja do pobierania aktualnego statusu znajomości
 * @param {number} targetUserId - ID użytkownika docelowego
 * @returns {Promise} Promise z statusem znajomości
 */
function getFriendshipStatus(targetUserId) {
    return fetch('../../api/profile-page/get_friendship_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `targetUserId=${targetUserId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            return data.friendshipStatus;
        } else {
            throw new Error(data.message || 'Błąd pobierania statusu');
        }
    });
}
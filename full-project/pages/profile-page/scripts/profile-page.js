// Plik JavaScript obsługujący podstronę profilu

/**
 * Inicjalizacja podstrony profilu
 */
function initializeSubpage() {
    console.log("Inicjalizacja podstrony profilu...");
    
    // Inicjalizacja przycisków profilu
    initializeProfileActions();
    
    // Inicjalizacja modala potwierdzenia usunięcia konta
    initializeDeleteConfirmation();
}

/**
 * Inicjalizacja przycisków akcji profilu
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
    
    // Przycisk dodania do znajomych
    const addFriendButton = document.querySelector('.add-friend-button');
    if (addFriendButton) {
        addFriendButton.addEventListener('click', function() {
            const userName = document.querySelector('.profile-user-info h1').textContent.replace('Profil użytkownika: ', '');
            addFriend(userName);
        });
    }
}

/**
 * Inicjalizacja potwierdzenia usunięcia konta
 */
function initializeDeleteConfirmation() {
    // Przycisk usunięcia profilu
    const deleteButton = document.querySelector('.delete-button');
    if (deleteButton) {
        deleteButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Wyświetl popup potwierdzenia
            showConfirmationPopup(
                'Usunięcie konta',
                'Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna.',
                () => {
                    // Po potwierdzeniu wyślij formularz
                    const form = this.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            );
        });
    }
}

/**
 * Funkcja do wyświetlania popupu potwierdzenia
 * @param {string} title - Tytuł popupu
 * @param {string} message - Wiadomość w popupie
 * @param {Function} onConfirm - Funkcja wykonywana po potwierdzeniu
 */
function showConfirmationPopup(title, message, onConfirm) {
    // Sprawdź czy funkcja popupu istnieje (z głównego pliku JS)
    if (typeof showPopupWindow === 'function') {
        showPopupWindow(title, message, [
            {
                text: 'Anuluj',
                class: 'popup-button-cancel',
                onClick: () => {}
            },
            {
                text: 'Potwierdź',
                class: 'popup-button-confirm popup-button-danger',
                onClick: onConfirm
            }
        ]);
    } else {
        // Fallback jeśli funkcja popupu nie istnieje
        if (confirm(message)) {
            onConfirm();
        }
    }
}

/**
 * Funkcja dodająca użytkownika do znajomych
 * @param {string} userName - Nazwa użytkownika do dodania
 */
function addFriend(userName) {
    // W prawdziwej implementacji tutaj byłoby wysłanie żądania AJAX
    // Na potrzeby demonstracji wyświetlamy powiadomienie
    showNotification(`Wysłano zaproszenie do użytkownika ${userName}`, 'success');
    
    // Zmień tekst przycisku
    const addFriendButton = document.querySelector('.add-friend-button');
    if (addFriendButton) {
        addFriendButton.textContent = 'Zaproszenie wysłane';
        addFriendButton.disabled = true;
        addFriendButton.style.opacity = '0.7';
    }
}

/**
 * Funkcja wyświetlająca powiadomienie
 * @param {string} message - Treść powiadomienia
 * @param {string} type - Typ powiadomienia (success, error, info)
 */
function showNotification(message, type = 'info') {
    // Sprawdź czy funkcja istnieje w głównym pliku JS
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Prosty fallback dla powiadomień
        alert(message);
    }
}
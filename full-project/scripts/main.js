// Główny plik JavaScript

/**
 * Funkcja do wyszukiwania treści
 */
function search() {
    const searchQuery = document.getElementById('search_bar_input_text').value.trim();

    if (searchQuery.length === 0) {
        showNotification('Proszę wprowadzić tekst do wyszukania', 'warning');
        return;
    }

    // W przypadku rzeczywistej implementacji tutaj byłoby odwołanie do API wyszukiwania
    console.log('Wyszukiwanie:', searchQuery);
    showNotification(`Wyszukiwanie "${searchQuery}" w toku...`, 'info');

    // Symulacja wyszukiwania
    setTimeout(() => {
        showNotification(`Znaleziono wyniki dla "${searchQuery}"`, 'success');
        // Przekierowanie do strony z wynikami
        window.location.href = `../search-page/index.php?search=${searchQuery}`;
    }, 500);
}

//#region Powiadomienia

/**
 * Funkcja do wyświetlania przyjaznych komunikatów dla użytkownika
 * @param {string} message - Treść komunikatu
 * @param {string} type - Typ komunikatu (success, error, warning, info)
 */
function showNotification(message, type = 'info') {
    // Sprawdź, czy kontener powiadomień już istnieje
    let notificationContainer = document.getElementById('notification-container');

    // Jeśli nie, utwórz go
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.bottom = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '1000';
        document.body.appendChild(notificationContainer);
    }

    // Utwórz nowe powiadomienie
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;

    // Style dla powiadomienia
    notification.style.backgroundColor = getNotificationColor(type);
    notification.style.color = 'white';
    notification.style.padding = '15px 20px';
    notification.style.borderRadius = '10px';
    notification.style.marginBottom = '10px';
    notification.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    notification.style.display = 'flex';
    notification.style.justifyContent = 'space-between';
    notification.style.alignItems = 'center';
    notification.style.minWidth = '300px';
    notification.style.maxWidth = '400px';
    notification.style.transition = 'all 0.3s ease';
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(20px)';

    // Przycisk zamknięcia
    const closeButton = notification.querySelector('.notification-close');
    closeButton.style.background = 'none';
    closeButton.style.border = 'none';
    closeButton.style.color = 'white';
    closeButton.style.fontSize = '20px';
    closeButton.style.cursor = 'pointer';
    closeButton.style.marginLeft = '10px';

    // Dodaj powiadomienie do kontenera
    notificationContainer.appendChild(notification);

    // Pokaż powiadomienie (animacja)
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);

    // Zamknij powiadomienie po kliknięciu przycisku
    closeButton.addEventListener('click', () => {
        closeNotification(notification);
    });

    // Automatycznie zamknij powiadomienie po 5 sekundach
    setTimeout(() => {
        closeNotification(notification);
    }, 5000);
}

/**
 * Funkcja do zamykania powiadomienia z animacją
 * @param {HTMLElement} notification - Element powiadomienia do zamknięcia
 */
function closeNotification(notification) {
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(20px)';

    setTimeout(() => {
        notification.remove();
    }, 300);
}

/**
 * Funkcja do uzyskania koloru powiadomienia na podstawie typu
 * @param {string} type - Typ powiadomienia
 * @returns {string} - Kolor w formacie CSS
 */
function getNotificationColor(type) {
    switch (type) {
        case 'success':
            return '#28a745';
        case 'error':
            return '#dc3545';
        case 'warning':
            return '#ffc107';
        case 'info':
        default:
            return '#17a2b8';
    }
}

//#endregion

/**
 * Inicjalizacja funkcji po załadowaniu strony
 */
document.addEventListener('DOMContentLoaded', function () {
    // Inicjalizacja przycisków i interakcji
    initializeButtons();
    initializeLinks();

    // Inicjalizacja podstrony
    initializeSubpage();

    console.log('Aplikacja Blok Services została zainicjowana pomyślnie!');
});

/**
 * Inicjalizacja przycisków i interakcji
 */
function initializeButtons() {
    // Przyciski logowania i rejestracji
    const loginButton = document.querySelector('.login-button');
    const registerButton = document.querySelector('.register-button');

    if (loginButton) {
        loginButton.addEventListener('click', function () {
            window.location.href = '../login-page/index.php';
        });
    }

    if (registerButton) {
        registerButton.addEventListener('click', function () {
            window.location.href = '../register-page/index.php';
        });
    }
}

/**
 * Inicjalizacja linków do dokumentów 
 */
function initializeLinks() {
    // Linki do dokumentów
    const termsOfServiceLink = document.querySelector('.terms-of-service-link');
    const privacyPolicyLink = document.querySelector('.privacy-policy-link');

    if (termsOfServiceLink) {
        termsOfServiceLink.addEventListener('click', function () {
            fetch('../../documents/terms-of-service.txt')
                .then(response => response.text())
                .then(text => {
                    console.log(text);  // Zawartość pliku
                    // Możesz przypisać tekst do zmiennej
                    let fileContent = text;

                    showPopup({
                        title: 'Regulamin',
                        content: text,
                        type: 'html',
                        autoCloseTime: 0

                    });
                })
                .catch(error => console.error('Error:', error));
        });
    }

    if (privacyPolicyLink) {
        privacyPolicyLink.addEventListener('click', function () {
            fetch('../../documents/privacy-policy.txt')
                .then(response => response.text())
                .then(text => {
                    console.log(text);  // Zawartość pliku
                    // Możesz przypisać tekst do zmiennej
                    let fileContent = text;

                    showPopup({
                        title: 'Polityka prywatności',
                        content: text,
                        type: 'html',
                        autoCloseTime: 0

                    });
                })
                .catch(error => console.error('Error:', error));
        });
    }
}

/*
 * Inicjalizacja motywu przy załadowaniu strony
 */
function initializeSubpage() { }

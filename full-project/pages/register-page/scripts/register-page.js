/**
 * Skrypt dla strony rejestracji
 */

document.addEventListener('DOMContentLoaded', function() {
    // Pobranie formularza rejestracji
    const registerForm = document.getElementById('register-form');
    
    if (registerForm) {
        // Dodanie obsługi zdarzenia wysłania formularza
        registerForm.addEventListener('submit', validateForm);
        
        // Dodanie obsługi zdarzeń dla pól formularza
        document.getElementById('login').addEventListener('blur', validateLogin);
        document.getElementById('email').addEventListener('blur', validateEmail);
        document.getElementById('password').addEventListener('blur', validatePassword);
        document.getElementById('phone_number').addEventListener('blur', validatePhoneNumber);
    }
    
    // Nawigacja do strony logowania po kliknięciu przycisku
    const loginButton = document.querySelector('.login-button');
    if (loginButton) {
        loginButton.addEventListener('click', function() {
            window.location.href = '../login-page/index.php';
        });
    }
});

/**
 * Walidacja całego formularza przed wysłaniem
 * @param {Event} event - Zdarzenie wysłania formularza
 */
function validateForm(event) {
    // Pobranie wartości wszystkich pól
    const login = document.getElementById('login').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const name = document.getElementById('name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const terms = document.getElementById('terms').checked;
    
    let isValid = true;
    
    // Sprawdzenie długości loginu
    if (login.length < 3 || login.length > 30) {
        showError('login', 'Login musi mieć od 3 do 30 znaków');
        isValid = false;
    }
    
    // Sprawdzenie formatu adresu email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showError('email', 'Podaj poprawny adres email');
        isValid = false;
    }
    
    // Sprawdzenie długości i złożoności hasła
    if (password.length < 8) {
        showError('password', 'Hasło musi mieć co najmniej 8 znaków');
        isValid = false;
    }
    
    // Sprawdzenie imienia i nazwiska
    if (name.length < 2 || lastName.length < 2) {
        showError(name.length < 2 ? 'name' : 'last_name', 'Imię i nazwisko muszą mieć co najmniej 2 znaki');
        isValid = false;
    }
    
    // Sprawdzenie akceptacji regulaminu
    if (!terms) {
        const termsError = document.createElement('span');
        termsError.className = 'error-message';
        termsError.textContent = 'Musisz zaakceptować regulamin i politykę prywatności';
        
        const termsContainer = document.querySelector('.terms-checkbox');
        if (!termsContainer.querySelector('.error-message')) {
            termsContainer.appendChild(termsError);
        }
        
        isValid = false;
    }
    
    // Jeśli formularz nie jest prawidłowy, zatrzymaj wysyłanie
    if (!isValid) {
        event.preventDefault();
        showNotification('Proszę poprawić błędy w formularzu', 'error');
    } else {
        showNotification('Przetwarzanie rejestracji...', 'info');
    }
}

/**
 * Walidacja loginu
 */
function validateLogin() {
    const login = document.getElementById('login').value.trim();
    
    // Sprawdzenie długości loginu
    if (login.length < 3 || login.length > 30) {
        showError('login', 'Login musi mieć od 3 do 30 znaków');
    } else {
        clearError('login');
    }
}

/**
 * Walidacja adresu email
 */
function validateEmail() {
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (!emailRegex.test(email)) {
        showError('email', 'Podaj poprawny adres email');
    } else {
        clearError('email');
    }
}

/**
 * Walidacja hasła
 */
function validatePassword() {
    const password = document.getElementById('password').value.trim();
    
    if (password.length < 8) {
        showError('password', 'Hasło musi mieć co najmniej 8 znaków');
    } else if (!/[A-Z]/.test(password)) {
        showError('password', 'Hasło musi zawierać co najmniej jedną wielką literę');
    } else if (!/[0-9]/.test(password)) {
        showError('password', 'Hasło musi zawierać co najmniej jedną cyfrę');
    } else {
        clearError('password');
    }
}

/**
 * Walidacja numeru telefonu
 */
function validatePhoneNumber() {
    const phoneNumber = document.getElementById('phone_number').value.trim();
    
    if (phoneNumber !== '') {
        const phoneRegex = /^\+?[\d\s-]{9,15}$/;
        
        if (!phoneRegex.test(phoneNumber)) {
            showError('phone_number', 'Podaj poprawny numer telefonu');
        } else {
            clearError('phone_number');
        }
    } else {
        clearError('phone_number');
    }
}

/**
 * Wyświetlanie komunikatu o błędzie dla danego pola
 * @param {string} fieldId - Identyfikator pola
 * @param {string} message - Komunikat o błędzie
 */
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = document.createElement('span');
    errorElement.className = 'error-message';
    errorElement.textContent = message;
    
    // Usuń istniejący komunikat o błędzie, jeśli istnieje
    clearError(fieldId);
    
    // Dodaj nowy komunikat o błędzie
    field.parentNode.appendChild(errorElement);
    field.style.borderColor = '#dc3545';
}

/**
 * Usuwanie komunikatu o błędzie dla danego pola
 * @param {string} fieldId - Identyfikator pola
 */
function clearError(fieldId) {
    const field = document.getElementById(fieldId);
    const parent = field.parentNode;
    
    // Usuń wszystkie komunikaty o błędach
    const errors = parent.querySelectorAll('.error-message');
    errors.forEach(error => error.remove());
    
    // Przywróć domyślny styl obramowania
    field.style.borderColor = '';
}

/**
 * Funkcja pomocnicza dostępu do funkcji powiadomień z głównego skryptu
 * Jeśli główny skrypt nie jest załadowany, definiujemy własną funkcję
 * @param {string} message - Komunikat
 * @param {string} type - Typ powiadomienia
 */
function showNotification(message, type) {
    // Sprawdź, czy funkcja istnieje w głównym skrypcie
    if (typeof window.showNotification === 'function') {
        window.showNotification(message, type);
    } else {
        // Prosta implementacja, jeśli główna funkcja nie jest dostępna
        alert(message);
    }
}
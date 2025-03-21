/**
 * Plik JavaScript obsługujący przełączanie motywów
 */

/**
 * Inicjalizacja motywu na podstawie preferencji użytkownika
 */
function initializeTheme() {
    // Sprawdź zapisany motyw w localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
    updateThemeToggleIcon(savedTheme);
    
    // Dodaj obsługę przycisku przełączania motywu
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
}

/**
 * Przełączanie między motywami (jasny/ciemny)
 */
function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme');
    let newTheme = 'light';
    
    if (currentTheme === 'light') {
        newTheme = 'dark';
    } else if (currentTheme === 'dark') {
        newTheme = 'light';
    }
    
    // Zastosuj nowy motyw
    applyTheme(newTheme);
}

/**
 * Zastosowanie określonego motywu
 * @param {string} theme - Nazwa motywu do zastosowania
 */
function applyTheme(theme) {
    document.body.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    updateThemeToggleIcon(theme);
    
    // Powiadomienie o zmianie motywu
    const themeNames = {
        'light': 'jasny',
        'dark': 'ciemny',
        'custom-purple': 'fioletowy',
        'disk-orange': 'pomarańczowy',
        'settings-blue': 'niebieski',
        'gallery-bw': 'czarno-biały',
        'admin-gray': 'administracyjny'
    };
    
    showNotification(`Zastosowano motyw: ${themeNames[theme] || theme}`, 'info');
}

/**
 * Aktualizuje ikonę przycisku przełączania motywu
 * @param {string} theme - Aktualny motyw
 */
function updateThemeToggleIcon(theme) {
    const themeToggle = document.getElementById('theme-toggle');
    if (!themeToggle) return;
    
    // Zmiana ikony w zależności od motywu
    if (theme === 'dark') {
        themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    } else {
        themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    }
}

// #region Eksperymentalne funkcje motywów na serwisie

/**
 * Zastosowanie motywu serwisowego dla określonej podstrony
 * @param {string} pageName - Nazwa podstrony
 */
function applyServiceTheme(pageName) {
    let themeToApply = 'light';
    
    switch (pageName.toLowerCase()) {
        case 'disk':
            themeToApply = 'disk-orange';
            break;
        case 'social':
        case 'messages':
            themeToApply = 'custom-purple';
            break;
        case 'gallery':
            themeToApply = 'gallery-bw';
            break;
        case 'settings':
            themeToApply = 'settings-blue';
            break;
        case 'admin':
            themeToApply = 'admin-gray';
            break;
        default:
            // Sprawdź, czy użytkownik ma zapisany własny motyw
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                themeToApply = savedTheme;
            }
    }
    
    applyTheme(themeToApply);
}

/**
 * Pobiera dostępne motywy dla selektora motywów
 * @returns {Array} - Tablica dostępnych motywów
 */
function getAvailableThemes() {
    return [
        { id: 'light', name: 'Jasny' },
        { id: 'dark', name: 'Ciemny' },
        { id: 'custom-purple', name: 'Fioletowy' },
        { id: 'disk-orange', name: 'Pomarańczowy' },
        { id: 'settings-blue', name: 'Niebieski' },
        { id: 'gallery-bw', name: 'Czarno-biały' }
    ];
}

/**
 * Generuje selektor motywów dla strony ustawień
 * @param {string} containerId - ID kontenera, w którym ma być wygenerowany selektor
 */
function generateThemeSelector(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const themes = getAvailableThemes();
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    let html = '<div class="theme-selector">';
    html += '<h3>Wybierz motyw</h3>';
    html += '<div class="theme-options">';
    
    themes.forEach(theme => {
        const isActive = theme.id === currentTheme;
        html += `
            <div class="theme-option ${isActive ? 'active' : ''}" data-theme="${theme.id}">
                <div class="theme-preview" style="background-color: var(--primary-color);"></div>
                <div class="theme-name">${theme.name}</div>
            </div>
        `;
    });
    
    html += '</div></div>';
    
    container.innerHTML = html;
    
    // Dodaj obsługę zdarzeń dla opcji motywów
    const themeOptions = container.querySelectorAll('.theme-option');
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            const themeId = this.getAttribute('data-theme');
            applyTheme(themeId);
            
            // Aktualizuj aktywną klasę
            themeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

//#endregion

// Inicjalizacja motywu przy załadowaniu skryptu
document.addEventListener('DOMContentLoaded', initializeTheme);

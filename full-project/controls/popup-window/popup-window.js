/**
 * Funkcja do wyświetlania okienka popup
 * @param {object} options - Konfiguracja okienka popup
 * @param {string} options.title - Tytuł okienka
 * @param {string|HTMLElement} options.content - Treść okienka (tekst, HTML lub element DOM)
 * @param {string} options.type - Typ zawartości ('text', 'image', 'html', 'element')
 * @param {number} options.width - Szerokość okienka (px lub %)
 * @param {number} options.height - Wysokość okienka (px lub %)
 * @param {boolean} options.closeOnOutsideClick - Czy zamykać okienko po kliknięciu poza nim
 * @param {number} options.autoCloseTime - Czas w ms po którym okienko zostanie automatycznie zamknięte (0 = brak)
 * @param {Function} options.onOpen - Callback wykonywany po otwarciu okienka
 * @param {Function} options.onClose - Callback wykonywany po zamknięciu okienka
 */
function showPopup(options = {}) {
    // Domyślne opcje
    const defaultOptions = {
        title: 'Informacja',
        content: '',
        type: 'text',
        width: 'auto',
        height: 'auto',
        closeOnOutsideClick: true,
        autoCloseTime: 0,
        onOpen: null,
        onClose: null
    };

    // Połącz opcje z domyślnymi
    const settings = { ...defaultOptions, ...options };

    // Utwórz overlay (tło)
    const overlay = document.createElement('div');
    overlay.className = 'popup-overlay';
    document.body.appendChild(overlay);

    // Utwórz kontener popup
    const popup = document.createElement('div');
    popup.className = 'popup-container';
    popup.style.width = typeof settings.width === 'number' ? `${settings.width}px` : settings.width;
    popup.style.height = typeof settings.height === 'number' ? `${settings.height}px` : settings.height;
    
    // Utwórz nagłówek
    const header = document.createElement('div');
    header.className = 'popup-header';
    
    // Dodaj tytuł
    const title = document.createElement('h3');
    title.className = 'popup-title';
    title.textContent = settings.title;
    header.appendChild(title);
    
    // Dodaj przycisk zamykania
    const closeButton = document.createElement('button');
    closeButton.className = 'popup-close';
    closeButton.innerHTML = '&times;';
    closeButton.setAttribute('aria-label', 'Zamknij');
    header.appendChild(closeButton);
    
    // Dodaj treść
    const content = document.createElement('div');
    content.className = 'popup-content';
    
    // Obsługa różnych typów treści
    switch (settings.type) {
        case 'text':
            content.textContent = settings.content;
            break;
            
        case 'image':
            const img = document.createElement('img');
            img.src = settings.content;
            img.alt = settings.title || 'Obrazek';
            img.className = 'popup-image';
            content.appendChild(img);
            break;
            
        case 'html':
            content.innerHTML = settings.content;
            break;
            
        case 'element':
            if (settings.content instanceof HTMLElement) {
                content.appendChild(settings.content);
            } else {
                content.textContent = 'Nieprawidłowa zawartość';
            }
            break;
            
        default:
            content.textContent = settings.content;
    }
    
    // Złożenie popupu
    popup.appendChild(header);
    popup.appendChild(content);
    document.body.appendChild(popup);
    
    // Funkcja zamykająca popup
    const closePopup = () => {
        // Animacja zamykania
        popup.classList.add('popup-closing');
        overlay.classList.add('popup-overlay-closing');
        
        setTimeout(() => {
            // Usuń elementy z DOM
            popup.remove();
            overlay.remove();
            
            // Wywołaj callback onClose
            if (typeof settings.onClose === 'function') {
                settings.onClose();
            }
        }, 300); // czas trwania animacji
    };
    
    // Obsługa kliknięcia przycisku zamykania
    closeButton.addEventListener('click', closePopup);
    
    // Obsługa kliknięcia poza popupem
    if (settings.closeOnOutsideClick) {
        overlay.addEventListener('click', closePopup);
    }
    
    // Automatyczne zamykanie
    if (settings.autoCloseTime > 0) {
        setTimeout(closePopup, settings.autoCloseTime);
    }
    
    // Animacja otwierania
    setTimeout(() => {
        overlay.classList.add('popup-overlay-visible');
        popup.classList.add('popup-visible');
        
        // Wywołaj callback onOpen
        if (typeof settings.onOpen === 'function') {
            settings.onOpen();
        }
    }, 10);
    
    // Zwróć referencje do elementów i funkcję zamykającą
    return {
        popup,
        overlay,
        close: closePopup
    };
}

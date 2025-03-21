// Plik JavaScript obsługujący podstronę powitalną

/*
 * Inicjalizacja podstrony
 */
function initializeSubpage() {
    // Inicjalizacja funkcji nawigacyjnych
    initializeNavigation();

    // Inicjalizacja przycisków CTA
    initializeCTAButtons();
}

/*
 * Inicjalizacja przycisków CTA
 */
function initializeCTAButtons() {
    // Przyciski CTA
    const ctaPrimary = document.querySelector('.cta-primary');
    const ctaSecondary = document.querySelector('.cta-secondary');
    
    if (ctaPrimary) {
        ctaPrimary.addEventListener('click', function() {
            window.location.href = '../register-page/index.php';
        });
    }
    
    if (ctaSecondary) {
        ctaSecondary.addEventListener('click', function() {
            showFeatureDetails();
        });
    }
    
    // Karty funkcji
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('click', function() {
            const featureName = this.querySelector('h3').textContent;
            navigateToFeature(featureName);
        });
    });
}

/**
 * Nawigacja do odpowiedniej funkcji na podstawie nazwy
 * @param {string} featureName - Nazwa funkcji/podstrony
 */
function navigateToFeature(featureName) {
    let url = 'pages/';
    
    switch (featureName.toLowerCase()) {
        case 'dysk':
            url += '../../drive-page/index.php';
            break;
        case 'społeczność':
            url += '../../social-page/index.php';
            break;
        case 'wiadomości':
            url += '../../newsletters-page/index.php';
            break;
        case 'galeria':
            url += '../../gallery-page/index.php';
            break;
        case 'aplikacje':
            url += '../../applications-page/index.php';
            break;
        case 'gry':
            url += '../../games-page/index.php';
            break;
        case 'media':
            url += '../../media-page/index.php';
            break;
        case 'ustawienia':
            url += '../../settings-page/index.php';
            break;
        default:
            url = '#';
            showNotification('Ta funkcja jest obecnie niedostępna', 'info');
            return;
    }
    
    window.location.href = url;
}

/**
 * Wyświetlanie szczegółów funkcji
 */
function showFeatureDetails() {
    // W prawdziwej implementacji tutaj mogłaby być animacja przewijania do sekcji z funkcjami
    // lub otwarcie okna modalnego z bardziej szczegółowym opisem
    window.scrollTo({
        top: document.querySelector('.features-section').offsetTop - 100,
        behavior: 'smooth'
    });
    
    showNotification('Odkryj nasze funkcje poniżej!', 'info');
}

/**
 * Inicjalizacja nawigacji
 */
function initializeNavigation() {
    // Aktywacja bieżącej pozycji nawigacji
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.main-nav li a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href').split('/').pop();
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
    
    // Obsługa zdarzeń trendy w prawym sidebarze
    const trendingPosts = document.querySelectorAll('.trending-post');
    trendingPosts.forEach(post => {
        post.addEventListener('click', function() {
            const title = this.querySelector('h4').textContent;
            showNotification(`Wybrałeś: ${title}`, 'info');
            // W rzeczywistej implementacji tutaj byłoby przekierowanie do wybranego trendu
        });
    });
}
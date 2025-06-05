   /**
 * Nawigacja do odpowiedniej aplikacji na podstawie nazwy
 * @param {string} appName - Nazwa aplikacji
*/
function navigateToFeature(appName) {
    let url = '';
    console.log(`Navigating to ${appName.toLowerCase()}`);

    switch (appName.toLowerCase()) {
        case 'kalkulator':
            url += '../calculator-page/index.php';
            break;
        case 'kalendarz':
            url += 'https://calendar.google.com';
            break;
        case 'zegar':
            url += 'https://zegaronline.pl/czas/';
            break;
        case 'pogoda':
            url += 'https://pogoda.interia.pl';
            break;
        default:
            url = '#';
            showNotification('Ta funkcja jest obecnie niedostępna', 'info');
            return;
    }
    window.location.href = url;
}

function initializeAppCards() {
    // Add click handlers for launch buttons
    document.querySelectorAll('.launch-app').forEach(button => {
        button.addEventListener('click', (e) => {
            // Get the app name from the parent card's h3 element
            const appCard = e.target.closest('.app-card');
            const appName = appCard.querySelector('h3').textContent;
            console.log(`Launching ${appName}`);
            navigateToFeature(appName);
        });
    });
}

function initializeSubpage(){
    initializeAppCards();
}
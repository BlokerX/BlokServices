// Plik JavaScript obsługujący podstronę gier

/*
 * Inicjalizacja podstrony
 */
function initializeSubpage() {
    // Inicjalizacja komponentów strony gier
    initializeGamesComponents();
    
    // Inicjalizacja przycisków interakcji z grami
    initializeGameInteractions();
    
    // Inicjalizacja funkcji nawigacyjnych
    initializeNavigation();
}

/*
 * Inicjalizacja podstawowych komponentów strony gier
 */
function initializeGamesComponents() {
    // Inicjalizacja wyszukiwania i sortowania
    initializeSearchAndSort();
    
    // Inicjalizacja widoków (siatka/lista)
    initializeViewOptions();
    
    // Inicjalizacja karuzeli
    initializeCarousel();
    
    // Inicjalizacja paginacji
    initializePagination();
    
    // Inicjalizacja tooltipów dla ocen gier
    initializeRatingTooltips();
}

/**
 * Inicjalizacja wyszukiwania i sortowania
 */
function initializeSearchAndSort() {
    const gamesSearch = document.getElementById('games-search');
    const gamesSort = document.getElementById('games-sort');
    
    // Wyszukiwanie
    if (gamesSearch) {
        gamesSearch.addEventListener('input', searchGames);
    }
    
    // Sortowanie
    if (gamesSort) {
        gamesSort.addEventListener('change', sortGames);
    }
}

/**
 * Funkcja wyszukiwania gier
 */
function searchGames() {
    const searchTerm = document.getElementById('games-search').value.toLowerCase();
    const gameCards = document.querySelectorAll('.game-card');
    
    gameCards.forEach(card => {
        const gameTitle = card.querySelector('h4').textContent.toLowerCase();
        const gameCategory = card.querySelector('.game-category').textContent.toLowerCase();
        
        if (gameTitle.includes(searchTerm) || gameCategory.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

/**
 * Obsługa sortowania gier
 */
function sortGames() {
    const sortValue = document.getElementById('games-sort').value;
    const gamesGrid = document.querySelector('.games-grid');
    const gameCards = document.querySelectorAll('.game-card');
    const gamesArray = Array.from(gameCards);
    
    gamesArray.sort((a, b) => {
        if (sortValue === 'a-z') {
            const titleA = a.querySelector('h4').textContent;
            const titleB = b.querySelector('h4').textContent;
            return titleA.localeCompare(titleB);
        } else if (sortValue === 'rating') {
            const ratingA = parseFloat(a.querySelector('.rating-value').textContent);
            const ratingB = parseFloat(b.querySelector('.rating-value').textContent);
            return ratingB - ratingA;
        } else if (sortValue === 'popular') {
            const playsA = parseInt(a.querySelector('.game-plays').textContent.match(/\d+(\.\d+)?/)[0]);
            const playsB = parseInt(b.querySelector('.game-plays').textContent.match(/\d+(\.\d+)?/)[0]);
            return playsB - playsA;
        }
        
        // Domyślnie sortuj według najnowszych
        return Array.from(gameCards).indexOf(a) - Array.from(gameCards).indexOf(b);
    });
    
    // Usuwamy wszystkie karty i dodajemy je ponownie w nowej kolejności
    gamesArray.forEach(card => {
        gamesGrid.appendChild(card);
    });
    
    showNotification(`Posortowano gry: ${getSortingName(sortValue)}`, 'success');
}

/**
 * Pobierz nazwę sortowania
 * @param {string} sortValue - Wartość sortowania
 * @returns {string} - Nazwa sortowania
 */
function getSortingName(sortValue) {
    switch(sortValue) {
        case 'a-z': return 'alfabetycznie';
        case 'rating': return 'według ocen';
        case 'popular': return 'według popularności';
        default: return 'według najnowszych';
    }
}

/**
 * Inicjalizacja opcji widoku (siatka/lista)
 */
function initializeViewOptions() {
    const gridViewButton = document.getElementById('grid-view');
    const listViewButton = document.getElementById('list-view');
    const gamesGrid = document.querySelector('.games-grid');
    
    // Domyślny widok siatki
    if (gamesGrid) {
        gamesGrid.classList.add('grid-view');
    }
    
    // Zmiana widoku
    if (gridViewButton && listViewButton) {
        gridViewButton.addEventListener('click', () => changeView('grid'));
        listViewButton.addEventListener('click', () => changeView('list'));
    }
}

/**
 * Zmiana widoku (siatka/lista)
 * @param {string} view - Typ widoku ('grid' lub 'list')
 */
function changeView(view) {
    const gamesGrid = document.querySelector('.games-grid');
    const gridViewButton = document.getElementById('grid-view');
    const listViewButton = document.getElementById('list-view');
    
    if (view === 'grid') {
        gamesGrid.classList.remove('list-view');
        gamesGrid.classList.add('grid-view');
        gridViewButton.classList.add('active');
        listViewButton.classList.remove('active');
        
        showNotification('Przełączono na widok siatki', 'info');
    } else {
        gamesGrid.classList.remove('grid-view');
        gamesGrid.classList.add('list-view');
        gridViewButton.classList.remove('active');
        listViewButton.classList.add('active');
        
        // Dodajemy style dla widoku listy
        document.documentElement.style.setProperty('--game-card-display', 'flex');
        document.documentElement.style.setProperty('--game-thumbnail-width', '200px');
        
        showNotification('Przełączono na widok listy', 'info');
    }
}

/**
 * Inicjalizacja karuzeli
 */
function initializeCarousel() {
    const carouselPrev = document.querySelector('.carousel-prev');
    const carouselNext = document.querySelector('.carousel-next');
    const carouselDots = document.querySelectorAll('.carousel-dots .dot');
    
    if (!carouselDots.length) return;
    
    let currentSlide = 0;
    const totalSlides = carouselDots.length;
    
    // Funkcja pokazująca slajd
    function showSlide(index) {
        carouselDots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        currentSlide = index;
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % totalSlides);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + totalSlides) % totalSlides);
    }
    
    // Dodajemy nasłuchiwacze zdarzeń dla karuzeli
    if (carouselPrev && carouselNext) {
        carouselPrev.addEventListener('click', prevSlide);
        carouselNext.addEventListener('click', nextSlide);
        
        carouselDots.forEach((dot, index) => {
            dot.addEventListener('click', () => showSlide(index));
        });
    }
}

/**
 * Inicjalizacja paginacji
 */
function initializePagination() {
    const paginationButtons = document.querySelectorAll('.pagination button.page-number');
    const paginationPrev = document.querySelector('.pagination .page-prev');
    const paginationNext = document.querySelector('.pagination .page-next');
    
    if (paginationButtons.length === 0) return;
    
    // Zmiana strony
    function changePage(pageNumber) {
        paginationButtons.forEach((button, index) => {
            button.classList.toggle('active', index === pageNumber - 1);
        });
        
        // W rzeczywistej implementacji tutaj byłoby ładowanie nowych danych
        console.log(`Zmiana strony na: ${pageNumber}`);
        showNotification(`Ładowanie strony ${pageNumber}...`, 'info');
    }
    
    // Dodajemy nasłuchiwacze zdarzeń dla paginacji
    paginationButtons.forEach(button => {
        button.addEventListener('click', () => {
            const pageNumber = parseInt(button.textContent);
            changePage(pageNumber);
        });
    });
    
    if (paginationPrev) {
        paginationPrev.addEventListener('click', () => {
            const currentPage = Array.from(paginationButtons).findIndex(btn => btn.classList.contains('active')) + 1;
            if (currentPage > 1) {
                changePage(currentPage - 1);
            }
        });
    }
    
    if (paginationNext) {
        paginationNext.addEventListener('click', () => {
            const currentPage = Array.from(paginationButtons).findIndex(btn => btn.classList.contains('active')) + 1;
            if (currentPage < paginationButtons.length) {
                changePage(currentPage + 1);
            }
        });
    }
}

/**
 * Inicjalizacja tooltipów dla ocen gier
 */
function initializeRatingTooltips() {
    const ratingElements = document.querySelectorAll('.game-rating');
    
    ratingElements.forEach(rating => {
        const ratingValue = rating.querySelector('.rating-value').textContent;
        rating.title = `Ocena: ${ratingValue}/5.0`;
    });
}

/*
 * Inicjalizacja interakcji z grami
 */
function initializeGameInteractions() {
    // Przyciski ulubione
    const favoriteButtons = document.querySelectorAll('.favorite-btn');
    favoriteButtons.forEach(button => {
        button.addEventListener('click', () => toggleFavorite(button));
    });
    
    // Przyciski graj
    const playButtons = document.querySelectorAll('.play-btn');
    playButtons.forEach(button => {
        button.addEventListener('click', () => {
            const gameCard = button.closest('.game-card');
            const gameTitle = gameCard.querySelector('h4').textContent;
            playGame(gameTitle);
        });
    });
    
    // Przyciski graj teraz
    const playNowButtons = document.querySelectorAll('.play-now-btn');
    playNowButtons.forEach(button => {
        button.addEventListener('click', () => {
            const featuredGame = button.closest('.featured-game');
            const gameTitle = featuredGame.querySelector('h4').textContent;
            playGame(gameTitle);
        });
    });
}

/**
 * Obsługa przycisków ulubione
 * @param {HTMLElement} button - Przycisk ulubionych
 */
function toggleFavorite(button) {
    const iconElement = button.querySelector('i');
    if (iconElement.classList.contains('far')) {
        iconElement.classList.remove('far');
        iconElement.classList.add('fas');
        showNotification('Gra dodana do ulubionych!', 'success');
    } else {
        iconElement.classList.remove('fas');
        iconElement.classList.add('far');
        showNotification('Gra usunięta z ulubionych!', 'info');
    }
}

/**
 * Obsługa przycisku graj
 * @param {string} gameTitle - Tytuł gry
 */
function playGame(gameTitle) {
    showNotification(`Uruchamianie gry: ${gameTitle}`, 'success');
    // W rzeczywistej implementacji tutaj byłoby przekierowanie do gry
}

/**
 * Inicjalizacja nawigacji
 */
function initializeNavigation() {    
    // Obsługa menu kategorii gier
    const sidebarMenuItems = document.querySelectorAll('.sidebar-menu li a');
    
    sidebarMenuItems.forEach(item => {
        // Efekt hover
        item.addEventListener('mouseenter', () => {
            if (!item.classList.contains('active')) {
                item.style.backgroundColor = 'rgba(var(--primary-rgb), 0.1)';
            }
        });
        
        item.addEventListener('mouseleave', () => {
            if (!item.classList.contains('active')) {
                item.style.backgroundColor = '';
            }
        });
        
        // Obsługa kliknięcia
        item.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Usuń aktywną klasę z wszystkich elementów
            sidebarMenuItems.forEach(menuItem => {
                menuItem.classList.remove('active');
                menuItem.style.backgroundColor = '';
            });
            
            // Dodaj aktywną klasę do klikniętego elementu
            item.classList.add('active');
            
            // Filtrowanie gier według kategorii
            const category = item.textContent.trim();
            console.log(`Filtrowanie gier według kategorii: ${category}`);
            showNotification(`Wybrano kategorię: ${category}`, 'info');
            filterGamesByCategory(category);
        });
    });
}

/**
 * Filtrowanie gier według kategorii
 * @param {string} category - Nazwa kategorii
 */
function filterGamesByCategory(category) {
    // W rzeczywistej implementacji tutaj byłoby filtrowanie gier
    // Symulacja filtrowania - dla przykładu
    const gameCards = document.querySelectorAll('.game-card');
    
    if (category.toLowerCase() === 'wszystkie gry') {
        gameCards.forEach(card => {
            card.style.display = 'block';
        });
        return;
    }
    
    gameCards.forEach(card => {
        const gameCategory = card.querySelector('.game-category').textContent.toLowerCase();
        if (gameCategory.includes(category.toLowerCase())) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
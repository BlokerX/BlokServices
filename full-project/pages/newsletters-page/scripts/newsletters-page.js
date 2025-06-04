// Prosta obsługa filtrów
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');
    });
});

// Symulacja wyszukiwania
document.querySelector('.search-container button').addEventListener('click', function() {
    const searchTerm = document.querySelector('.search-container input').value;
    if (searchTerm) {
        alert(`Wyszukiwanie frazy: ${searchTerm}`);
    }
});

// Obsługa popularnych artykułów
document.querySelectorAll('.popular-item').forEach(item => {
    item.addEventListener('click', function() {
        const title = this.querySelector('.popular-item-title').textContent;
        alert(`Wybrano artykuł: ${title}`);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.getElementById('gallery');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const thumbnailsContainer = document.getElementById('thumbnails');
    const filterBtns = document.querySelectorAll('.filter-btn');
    
    let currentIndex = 0;
    
    // Generowanie elementów galerii
    function generujGalerie() {
        gallery.innerHTML = '';
        
        galleryData.forEach(item => {
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item';
            galleryItem.dataset.category = item.category;
            galleryItem.dataset.id = item.id;
            
            galleryItem.innerHTML = `
                <img src="${item.src}" alt="${item.title}">
                <div class="item-info">
                    <h3 class="item-title">${item.title}</h3>
                    <p>${item.description}</p>
                </div>
            `;
            
            galleryItem.addEventListener('click', () => otworzLightbox(item.id - 1));
            
            gallery.appendChild(galleryItem);
        });
    }
    
    // Generowanie miniatur
    function generujMiniatury() {
        thumbnailsContainer.innerHTML = '';
        
        galleryData.forEach((item, index) => {
            const thumbnail = document.createElement('div');
            thumbnail.className = 'thumbnail';
            thumbnail.innerHTML = `<img src="${item.thumbnail}" alt="${item.title}">`;
            
            thumbnail.addEventListener('click', () => {
                currentIndex = index;
                aktualizujLightbox();
            });
            
            thumbnailsContainer.appendChild(thumbnail);
        });
    }
    
    // Otwieranie lightboxa
    function otworzLightbox(index) {
        currentIndex = index;
        aktualizujLightbox();
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    // Zamykanie lightboxa
    function zamknijLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Aktualizacja zawartości lightboxa
    function aktualizujLightbox() {
        const item = galleryData[currentIndex];
        lightboxImg.src = item.src;
        lightboxImg.alt = item.title;
        lightboxCaption.textContent = `${item.title} - ${item.description}`;
        
        // Aktualizacja miniatur
        document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
            if (index === currentIndex) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }
    
    // Następny obraz
    function nastepnyObraz() {
        currentIndex = (currentIndex + 1) % galleryData.length;
        aktualizujLightbox();
    }
    
    // Poprzedni obraz
    function poprzedniObraz() {
        currentIndex = (currentIndex - 1 + galleryData.length) % galleryData.length;
        aktualizujLightbox();
    }
    
    // Filtrowanie galerii
    function filtrujGalerie(category) {
        const items = document.querySelectorAll('.gallery-item');
        
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.classList.remove('hide');
            } else {
                item.classList.add('hide');
            }
        });
    }
    
    // Nasłuchiwanie zdarzeń
    lightboxClose.addEventListener('click', zamknijLightbox);
    prevBtn.addEventListener('click', poprzedniObraz);
    nextBtn.addEventListener('click', nastepnyObraz);
    
    // Zamykanie lightboxa po kliknięciu poza obrazem
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            zamknijLightbox();
        }
    });
    
    // Nawigacja za pomocą klawiatury
    document.addEventListener('keydown', function(e) {
        if (!lightbox.classList.contains('active')) return;
        
        if (e.key === 'Escape') {
            zamknijLightbox();
        } else if (e.key === 'ArrowRight') {
            nastepnyObraz();
        } else if (e.key === 'ArrowLeft') {
            poprzedniObraz();
        }
    });
    
    // Przyciski filtrowania
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.filter;
            filtrujGalerie(category);
        });
    });
    
    // Inicjalizacja
    generujGalerie();
    generujMiniatury();
    filtrujGalerie('all');
});

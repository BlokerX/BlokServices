// #region Dane galerii
const galleryData = [
    {
        id: 1,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg", // Zastąp własnymi ścieżkami do obrazów
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Górski krajobraz",
        category: "natura",
        description: "Piękny górski krajobraz z ośnieżonymi szczytami"
    },
    {
        id: 2,
        src: "https://images.pexels.com/photos/1054655/pexels-photo-1054655.jpeg?cs=srgb&dl=pexels-hsapir-1054655.jpg&fm=jpg",
        thumbnail: "https://images.pexels.com/photos/1054655/pexels-photo-1054655.jpeg?cs=srgb&dl=pexels-hsapir-1054655.jpg&fm=jpg",
        title: "Nowoczesny budynek",
        category: "architektura",
        description: "Współczesne arcydzieło architektury w centrum miasta"
    },
    {
        id: 3,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Zachód słońca na plaży",
        category: "natura",
        description: "Spektakularny zachód słońca nad tropikalną plażą"
    },
    {
        id: 4,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Historyczna świątynia",
        category: "podroze",
        description: "Starożytna świątynia z misternymi kamiennymi rzeźbami"
    },
    {
        id: 5,
        src: "https://plus.unsplash.com/premium_photo-1673306778968-5aab577a7365?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmFja2dyb3VuZCUyMGltYWdlfGVufDB8fDB8fHww",
        thumbnail: "https://plus.unsplash.com/premium_photo-1673306778968-5aab577a7365?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8YmFja2dyb3VuZCUyMGltYWdlfGVufDB8fDB8fHww",
        title: "Panorama miasta",
        category: "architektura",
        description: "Panoramiczny widok na panoramę miasta o zmierzchu"
    },
    {
        id: 6,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Leśna ścieżka",
        category: "natura",
        description: "Spokojna ścieżka przez jesienny las"
    },
    {
        id: 7,
        src: "https://www.adobe.com/acrobat/hub/media_1291c3bf4f93e2dad9a76c32a053ea35af516e15d.jpeg?width=750&format=jpeg&optimize=medium",
        thumbnail: "https://www.adobe.com/acrobat/hub/media_1291c3bf4f93e2dad9a76c32a053ea35af516e15d.jpeg?width=750&format=jpeg&optimize=medium",
        title: "Kanały Wenecji",
        category: "podroze",
        description: "Malownicze kanały Wenecji we Włoszech"
    },
    {
        id: 8,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Wieżowiec",
        category: "architektura",
        description: "Wysoki szklany wieżowiec odbijający chmury"
    },
    {
        id: 9,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Tropikalny wodospad",
        category: "natura",
        description: "Ukryty wodospad w bujnym tropikalnym lesie"
    },
    {
        id: 10,
        src: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        thumbnail: "https://image-processor-storage.s3.us-west-2.amazonaws.com/images/866759932dc5358cee86f6552d1250f2/inside-bubble-spheres.jpg",
        title: "Ulica handlowa",
        category: "podroze",
        description: "Tętniąca życiem ulica targowa z kolorowymi straganami"
    },
    {
        id: 11,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Krajobraz pustyni",
        category: "natura",
        description: "Rozległy krajobraz pustyni z wydmami"
    },
    {
        id: 12,
        src: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        thumbnail: "https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg",
        title: "Stary most",
        category: "architektura",
        description: "Kamienny most z wielowiekową historią"
    }
];
// #endregion

// KOD Programu:
document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.getElementById('gallery');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.getElementById('lightbox-close');
    const lightboxCaption = document.getElementById('lightbox-caption');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const thumbnailsContainer = document.getElementById('thumbnails');
    
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
    
    // Inicjalizacja
    generujGalerie();
    generujMiniatury();
});


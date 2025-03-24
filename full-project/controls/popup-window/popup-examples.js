// Przykłady użycia funkcji showPopup

/**
 * Przykład 1: Prosty popup tekstowy
 */
function pokazPopupTekstowy() {
    // showPopup({
    //     title: 'Informacja',
    //     content: 'To jest przykładowy tekst w okienku popup.',
    //     type: 'text',
    //     width: 400,
    //     autoCloseTime: 3000 // zamknie się po 3 sekundach
    // });
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
        autoCloseTime: 0 // zamknie się po 3 sekundach

    });
  })
  .catch(error => console.error('Error:', error));
}

/**
 * Przykład 2: Popup z obrazkiem
 */
function pokazPopupZObrazkiem() {
    showPopup({
        title: 'Obrazek',
        content: 'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885_1280.jpg', // adres URL obrazka
        type: 'image'
    });
}

/**
 * Przykład 3: Popup z kodem HTML
 */
function pokazPopupHTML() {
    const htmlContent = `
        <div style="text-align: center;">
            <h2>Tytuł treści</h2>
            <p>To jest <strong>sformatowany</strong> tekst z <em>tagami HTML</em>.</p>
            <button onclick="alert('Kliknięto przycisk!')">Kliknij mnie</button>
        </div>
    `;
    
    showPopup({
        title: 'Treść HTML',
        content: htmlContent,
        type: 'html',
        width: 500,
        height: 300
    });
}

/**
 * Przykład 4: Popup z elementem DOM
 */
function pokazPopupZElementem() {
    // Tworzenie formularza
    const form = document.createElement('form');
    
    const nameLabel = document.createElement('label');
    nameLabel.textContent = 'Imię: ';
    const nameInput = document.createElement('input');
    nameInput.type = 'text';
    nameInput.name = 'name';
    nameLabel.appendChild(nameInput);
    
    const emailLabel = document.createElement('label');
    emailLabel.textContent = 'Email: ';
    const emailInput = document.createElement('input');
    emailInput.type = 'email';
    emailInput.name = 'email';
    emailLabel.appendChild(emailInput);
    
    const submitButton = document.createElement('button');
    submitButton.type = 'submit';
    submitButton.textContent = 'Wyślij';
    
    form.style.display = 'flex';
    form.style.flexDirection = 'column';
    form.style.gap = '10px';
    
    form.appendChild(nameLabel);
    form.appendChild(emailLabel);
    form.appendChild(submitButton);
    
    // Obsługa wysyłania formularza
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert(`Formularz wysłany: ${nameInput.value}, ${emailInput.value}`);
        popupInstance.close();
    });
    
    // Wyświetl popup z formularzem
    const popupInstance = showPopup({
        title: 'Formularz',
        content: form,
        type: 'element',
        width: 400,
        closeOnOutsideClick: false
    });
}

/**
 * Przykład 5: Popup z motywem
 */
function pokazPopupZMotywem() {
    const popup = showPopup({
        title: 'Ostrzeżenie',
        content: 'Uwaga! Ta operacja jest nieodwracalna.',
        type: 'text',
        width: 400
    });
    
    // Dodaj klasę motywu do popupu
    popup.popup.classList.add('popup-theme-warning');
}

/**
 * Przykład 6: Popup z callbackami
 */
function pokazPopupZCallbackami() {
    showPopup({
        title: 'Z callbackami',
        content: 'Ten popup ma zdefiniowane funkcje callback.',
        type: 'text',
        onOpen: function() {
            console.log('Popup został otwarty!');
        },
        onClose: function() {
            console.log('Popup został zamknięty!');
        }
    });
}

/**
 * Przykład 7: Popup z treścią multimedialna (wideo)
 */
function pokazPopupZWideo() {
    const videoHTML = `
        <div class="popup-media-container">
            <video controls>
                <source src="https://www.youtube.com/watch?v=TlBIa8z_Mts&list=RDTlBIa8z_Mts&start_radio=1&ab_channel=GenesisVEVO" type="video/mp4">
                Twoja przeglądarka nie obsługuje odtwarzania wideo.
            </video>
        </div>
    `;
    
    showPopup({
        title: 'Odtwarzacz wideo',
        content: videoHTML,
        type: 'html',
        width: 800,
        height: 500
    });
}

/**
 * Przykład 8: Sekwencja popupów
 */
function pokazSekwencjePopupow() {
    showPopup({
        title: 'Krok 1 z 3',
        content: 'To jest pierwszy popup w sekwencji.',
        type: 'text',
        closeOnOutsideClick: false,
        onClose: function() {
            // Po zamknięciu pierwszego popupu, pokaż drugi
            showPopup({
                title: 'Krok 2 z 3',
                content: 'To jest drugi popup w sekwencji.',
                type: 'text',
                closeOnOutsideClick: false,
                onClose: function() {
                    // Po zamknięciu drugiego popupu, pokaż trzeci
                    showPopup({
                        title: 'Krok 3 z 3',
                        content: 'To jest ostatni popup w sekwencji.',
                        type: 'text'
                    });
                }
            });
        }
    });
}

// uruchom każdą funkcję na starcie
window.addEventListener('load', () => {
    pokazSekwencjePopupow();
});

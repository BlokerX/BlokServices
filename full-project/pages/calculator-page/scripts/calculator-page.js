// Funkcja do dodawania operacji do historii
function addToHistory(operation) {
    const historyList = document.getElementById('historyList');
    const listItem = document.createElement('li');
    listItem.className = 'operation_history_block';
    listItem.innerHTML = `<p class="operation_history_block_content">${operation}</p><div id="operation_history_block_cb">X</div>`;
    historyList.appendChild(listItem);

    // Dodanie nasłuchiwacza zdarzeń do przycisku usuwania
    listItem.querySelector('#operation_history_block_cb').addEventListener('click', function () {
        historyList.removeChild(listItem);
    });
}

// Funkcja do obliczania wyniku na podstawie wybranej operacji
function calculate() {
    const input1 = parseFloat(document.getElementById('input1').value);
    const input2 = parseFloat(document.getElementById('input2').value);
    const operation = document.getElementById('operation').value;
    let result;

    switch (operation) {
        case 'add':
            result = input1 + input2;
            break;
        case 'subtract':
            result = input1 - input2;
            break;
        case 'multiply':
            result = input1 * input2;
            break;
        case 'divide':
            if (input2 !== 0) {
                result = input1 / input2;
            } else {
                result = 'Nie można dzielić przez zero!';
            }
            break;
        case 'modulus':
            result = input1 % input2;
            break;
        case 'exponent':
            result = Math.pow(input1, input2);
            break;
        case 'squareRoot':
            result = Math.sqrt(input1);
            break;
        case 'factorial':
            function factorial(n) {
                return n <= 1 ? 1 : n * factorial(n - 1);
            }
            result = factorial(input1);
            break;
        case 'log':
            result = Math.log10(input1);
            break;
        case 'ln':
            result = Math.log(input1);
            break;
        case 'sin':
            result = Math.sin(input1);
            break;
        case 'cos':
            result = Math.cos(input1);
            break;
        case 'tan':
            result = Math.tan(input1);
            break;
        case 'cot':
            if (Math.tan(input1) !== 0) {
                result = 1 / Math.tan(input1);
            } else {
                result = 'Nie można dzielić przez zero!';
            }
            break;
        default:
            result = 'Nieznana operacja!';
    }

    document.getElementById('result').innerText = result;

    // Dodanie operacji do historii
    const operationText = `${input1} ${operations[operation]} ${input2} = ${result}`;
    addToHistory(operationText);
}

// Funkcja do czyszczenia historii operacji
document.getElementById('clearHistory').addEventListener('click', function () {
    const historyList = document.getElementById('historyList');
    historyList.innerHTML = ''; // Czyści historię
});

// Funkcja do zmiany operacji na podstawie wybranego elementu z listy rozwijanej
function changeOperation() {
    const operation = document.getElementById('operation').value;
    const input2 = document.getElementById('input2');

    // Lista operacji jednoargumentowych
    const singleArgumentOperations = [
        'squareRoot',
        'factorial',
        'log',
        'ln',
        'sin',
        'cos',
        'tan',
        'cot'
    ];

    // Ukrycie lub pokazanie drugiego pola w zależności od wybranej operacji
    input2.style.display = singleArgumentOperations.includes(operation) ? 'none' : 'block';
}

function copyResult()
{
    const result = document.getElementById('result').innerText;
    navigator.clipboard.writeText(result).then(() => {
        alert('Wynik skopiowany do schowka!');
    }).catch(err => {
        console.error('Nie można skopiować wyniku: ', err);
    });
}

// Dodanie nasłuchiwacza zdarzeń do przycisku
document.getElementById('calculate').addEventListener('click', calculate);

// Wyliczenie dla operacji (jako 'add' na '+', 'subtract' na '-', itd.):
const operations = {
    add: '+',
    subtract: '-',
    multiply: '*',
    divide: '/',
    modulus: '%',
    exponent: '^',
    squareRoot: 'sqrt',
    factorial: 'factorial',
    log: 'log',
    ln: 'ln',
    sin: 'sin',
    cos: 'cos',
    tan: 'tan',
    cot: 'cot'
};
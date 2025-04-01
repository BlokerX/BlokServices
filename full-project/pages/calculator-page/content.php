<main>
    <section class="calculator-section">
    <h1>Kalkulator</h1>
    <div id="calculator-container">
        <input type="number" id="input1" placeholder="Liczba 1">
        <input type="number" id="input2" placeholder="Liczba 2">
        <select id="operation" onchange="changeOperation()">
            <option value="add">Dodawanie</option>
            <option value="subtract">Odejmowanie</option>
            <option value="multiply">Mnożenie</option>
            <option value="divide">Dzielenie</option>

            <option value="modulus">Modulo</option>
            <option value="exponent">Potęgowanie</option>
            <option value="squareRoot">Pierwiastek kwadratowy</option>

            <option value="factorial">Silnia</option>

            <option value="log">Logarytm</option>
            <option value="ln">Logarytm naturalny</option>

            <option value="sin">Sinus</option>
            <option value="cos">Cosinus</option>
            <option value="tan">Tangens</option>
            <option value="cot">Cotangens</option>
        </select>
        <button id="calculate">Oblicz</button>
        <h2>Wynik: <span id="result"></span></h2>
        <button id="copyResult" onclick="copyResult()">Kopiuj wynik</button>
    </div>
    </section>

    <section id="history">
        <h3>Historia operacji</h3>
        <button id="clearHistory">Wyczyść historię</button>
        <ul id="historyList">
            <!-- Historia operacji będzie dodawana tutaj za pomocą JavaScript -->

            <!-- <li class="operation_history_block">
                <p class="operation_history_block_content">5 + 3 = 8</p>
                <div id="operation_history_block_cb">X</div>
            </li> -->

        </ul>
    </section>
</main>

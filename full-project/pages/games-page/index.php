<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gry Przeglądarkowe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        .game-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .game-item {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: calc(33.333% - 20px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s;
        }
        .game-item:hover {
            transform: translateY(-5px);
        }
        .game-item img {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }
        .game-item a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Popularne Gry Przeglądarkowe</h1>
        <div class="game-list">
            <div class="game-item">
                <img src="images/agario.jpg" alt="Agar.io">
                <a href="https://agar.io" target="_blank">Agar.io</a>
            </div>
            <div class="game-item">
                <img src="images/slitherio.jpg" alt="Slither.io">
                <a href="https://slither.io" target="_blank">Slither.io</a>
            </div>
            <div class="game-item">
                <img src="images/diep.jpg" alt="Diep.io">
                <a href="https://diep.io" target="_blank">Diep.io</a>
            </div>
            <div class="game-item">
                <img src="images/krunker.jpg" alt="Krunker.io">
                <a href="https://krunker.io" target="_blank">Krunker.io</a>
            </div>
            <div class="game-item">
                <img src="images/surviv.jpg" alt="Surviv.io">
                <a href="https://surviv.io" target="_blank">Surviv.io</a>
            </div>
            <div class="game-item">
                <img src="images/powerline.jpg" alt="Powerline.io">
                <a href="https://powerline.io" target="_blank">Powerline.io</a>
            </div>
            <div class="game-item">
                <img src="images/zombsroyale.jpg" alt="ZombsRoyale.io">
                <a href="https://zombsroyale.io" target="_blank">ZombsRoyale.io</a>
            </div>
            <div class="game-item">
                <img src="images/tankio.jpg" alt="Tanks.io">
                <a href="https://tanks.io" target="_blank">Tanks.io</a>
            </div>
            <div class="game-item">
                <img src="images/paper.jpg" alt="Paper.io">
                <a href="https://paper.io" target="_blank">Paper.io</a>
            </div>
            <div class="game-item">
                <img src="images/hole.jpg" alt="Hole.io">
                <a href="https://hole.io" target="_blank">Hole.io</a>
            </div>
        </div>
    </div>
</body>
</html>
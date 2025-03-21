<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header, footer {
            background-color: #f8f8f8;
            text-align: center;
            padding: 1em 0;
        }
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2em;
        }
        form {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 2em;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 0.5em;
            font-weight: bold;
        }
        input, textarea {
            width: 100%;
            padding: 0.5em;
            margin-bottom: 1em;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button {
            width: 100%;
            padding: 0.75em;
            border: none;
            border-radius: 3px;
            background-color: #007BFF;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Skontaktuj Się Z Naszym Zespołem</h1>
    </header>
    <main>
        <form action="submit_contact.php" method="post">
            <label for="name">Imię:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Wiadomość:</label>
            <textarea id="message" name="message" rows="4" required></textarea>
            
            <button type="submit">Wyślij</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 BlokerCompany™ / Jakub Michalik. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
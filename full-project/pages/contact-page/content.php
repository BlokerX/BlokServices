<main>
    <form class="contact-form" action="submit_contact.php" method="post">
        <h2>Skontaktuj sie z nami</h2>
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Wiadomość:</label>
        <textarea id="message" name="message" rows="4" required></textarea>

        <button type="submit">Wyślij</button>
    </form>
</main>
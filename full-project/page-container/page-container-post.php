
<footer>
        <div class="footer-content">
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> <?php echo $config['app']['author']; ?>. Wszelkie prawa zastrzeżone.</p>
            </div>
            <div class="footer-links">
                <a href="<?php echo $config['documents']['terms-of-service']; ?>">Regulamin</a>
                <a href="<?php echo $config['documents']['privacy-policy']; ?>">Polityka prywatności</a>
                <a href="<?php echo $config['pages']['contact-page']['path']; ?>">Kontakt</a>
            </div>
        </div>
    </footer>

    <!-- Wczytywanie skryptów JS -->
    <script src="<?php echo $config['scripts']['main']; ?>"></script>
    <script src="<?php echo '../../controls/popup-window/popup-window.js'; ?>"></script>
    <script src="<?php echo $config['scripts']['theme']; ?>"></script>
    <script src="<?php echo 'scripts/' . $actualPage . ".js"; ?>"></script>

</body>

</html>
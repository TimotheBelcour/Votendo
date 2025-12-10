<?php
// footer.php : pied de page + fermeture de la page

// Déterminer le chemin de base en fonction du dossier actuel
$scriptPath = $_SERVER['SCRIPT_NAME'];
$basePath = '';
if (strpos($scriptPath, '/Votendo/Admin/') !== false) {
    $basePath = '../../'; // Pour les fichiers dans Votendo/Admin/
} elseif (strpos($scriptPath, '/Votendo/Compte/') !== false) {
    $basePath = '../../'; // Pour les fichiers dans Votendo/Compte/
} elseif (strpos($scriptPath, '/Votendo/') !== false) {
    $basePath = '../'; // Pour les fichiers dans Votendo/
} else {
    $basePath = ''; // Pour les fichiers à la racine
}
?>
  <footer class="site-footer">
    <div class="container footer__content">
      <nav class="footer-nav">
        <a href="<?= htmlspecialchars($basePath) ?>Votendo/mentionsLegales.php" class="footer-nav__link">Mentions légales</a>
        <a href="<?= htmlspecialchars($basePath) ?>Votendo/apropos.php" class="footer-nav__link">À propos</a>
        <a href="<?= htmlspecialchars($basePath) ?>Votendo/contact.php" class="footer-nav__link">Contact</a>
      </nav>

      <p class="footer__brand">
        © Votendo <?= date('Y') ?>
      </p>
    </div>
  </footer>
</body>
</html>
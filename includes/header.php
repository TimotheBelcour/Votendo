<?php
// header.php : début de la page + barre de navigation
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Votendo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header__content">
      <a href="index.php" class="logo">
        <img src="assets/img/Votendo.svg" alt="Logo Votendo" class="logo__img">
      </a>

      <nav class="main-nav">
        <ul class="main-nav__list">
          <li><a href="index.php" class="main-nav__link">Accueil</a></li>
          <li><a href="vote.php" class="main-nav__link">Vote</a></li>
          <li><a href="resultats.php" class="main-nav__link">Résultats</a></li>
          <li><a href="apropos.php" class="main-nav__link">À propos</a></li>
          <li><a href="contact.php" class="main-nav__link">Contact</a></li>
          <li><a href="login.php" class="main-nav__link">Se connecter</a></li>
        </ul>
      </nav>
    </div>
</header>
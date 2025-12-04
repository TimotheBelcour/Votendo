<?php
$host = '127.0.0.1';      // or 'localhost'
$user = 'root';           // your MySQL username
$password = '';  // your root password
$database = 'votendo';    // the name of your database

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
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
          <li><a href="inscription.php" class="main-nav__link">S'inscrire</a></li>
        </ul>
      </nav>
    </div>
</header>
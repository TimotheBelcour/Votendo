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

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$userId = $_SESSION['user_id'] ?? '';

// Fonction pour déterminer le type d'utilisateur et la page de redirection
$userPageUrl = '';
if ($isLoggedIn) {
    // Vérifier si c'est un administrateur
    $stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $adminResult = $stmt->get_result();
    
    if ($adminResult->num_rows > 0) {
        $userPageUrl = 'espaceAdministrateur.php';
    } else {
        // Vérifier si c'est un candidat
        $stmt = $conn->prepare("SELECT idCandidats FROM candidats WHERE idUtilisateur = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $candidatResult = $stmt->get_result();
        
        if ($candidatResult->num_rows > 0) {
            $userPageUrl = 'espaceCandidat.php';
        } else {
            // C'est un membre normal
            $userPageUrl = 'espaceMembre.php';
        }
    }
    $stmt->close();
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
          
          <?php if (!$isLoggedIn): ?>
            <!-- Utilisateur non connecté -->
            <li><a href="login.php" class="main-nav__link">Se connecter</a></li>
            <li><a href="inscription.php" class="main-nav__link">S'inscrire</a></li>
          <?php else: ?>
            <!-- Utilisateur connecté -->
            <li><a href="<?= htmlspecialchars($userPageUrl) ?>" class="main-nav__link">
                <?= htmlspecialchars($userName) ?>
              </a></li>
            <li><a href="logout.php" class="main-nav__link">Déconnexion</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
</header>
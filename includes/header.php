<?php
// Inclure le fichier de configuration de la base de données
include __DIR__ . '/config.php';

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['user_id']); // Vérifier si l'utilisateur est connecté
$userId     = $_SESSION['user_id'] ?? 0; // Récupérer l'ID utilisateur
$userName   = $_SESSION['user_name']   ?? ''; // Nom utilisateur
$isAdmin    = false; // Initialiser la variable isAdmin

// Vérifier si l'utilisateur est administrateur
if ($isLoggedIn) {
  // Préparer la requête pour vérifier si l'utilisateur est administrateur
  $stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
  $stmt->execute([$userId]);
  $adminResult = $stmt->fetch();
  if ($adminResult) {
    $isAdmin = true;
  }
}

// Fonction pour déterminer le type d'utilisateur et la page de redirection
$userPageUrl = '';
if ($isLoggedIn) {
  // Vérifier si c'est un administrateur
  $stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
  $stmt->execute([$userId]);
  $adminResult = $stmt->fetch();
  if ($adminResult) {
    $userPageUrl = $basePath . 'Votendo/Admin/espaceAdmin.php';
  } else {
    // Vérifier si c'est un candidat
    $stmt = $conn->prepare("SELECT idCandidats FROM candidats WHERE idUtilisateur = ?");
    $stmt->execute([$userId]);
    $candidatResult = $stmt->fetch();
    if ($candidatResult) {
      $userPageUrl = $basePath . 'Votendo/espaceCandidat.php';
    } else {
      // C'est un membre normal
      $userPageUrl = $basePath . 'Votendo/espaceMembre.php';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Votendo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="<?= htmlspecialchars($basePath) ?>assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header__content">
      <a href="<?= htmlspecialchars($basePath) ?>Votendo/index.php" class="logo">
        <img src="<?= htmlspecialchars($basePath) ?>assets/img/Votendo.svg" alt="Logo Votendo" class="logo__img">
      </a>

      <nav class="main-nav">
        <ul class="main-nav__list">
          <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/index.php" class="main-nav__link">Accueil</a></li>
          <?php if ($isLoggedIn): ?>
            <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/vote.php" class="main-nav__link">Vote</a></li>
          <?php endif; ?>
          <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/resultats.php" class="main-nav__link">Résultats</a></li>
          <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/apropos.php" class="main-nav__link">À propos</a></li>
          <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/contact.php" class="main-nav__link">Contact</a></li>

          <?php if (!$isLoggedIn): ?>
            <!-- Utilisateur non connecté -->
            <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/Compte/login.php" class="main-nav__link">Se connecter</a></li>
            <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/Compte/inscription.php" class="main-nav__link">S'inscrire</a></li>
          <?php else: ?>
            <!-- Utilisateur connecté -->
            <li><a href="<?= htmlspecialchars($userPageUrl) ?>" class="main-nav__link">
                <?= htmlspecialchars($userName) ?>
              </a></li>
            <li><a href="<?= htmlspecialchars($basePath) ?>Votendo/Compte/logout.php" class="main-nav__link">Déconnexion</a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
</header>
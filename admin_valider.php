<?php
require_once 'includes/config.php';
session_start();

// Vérifier que seul un admin peut valider
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Vérifier si admin

$stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$res = $stmt->fetch();
if (!$res) {
    echo "Accès interdit.";
    exit;
}

// Récupérer l'id du jeu
$idJeu = $_GET['id'] ?? 0;


// Mettre isValide = 1
$stmt = $conn->prepare("UPDATE jeux SET isValide = 1 WHERE idJeu = ?");
$stmt->execute([$idJeu]);

header("Location: espaceAdmin.php");
exit;
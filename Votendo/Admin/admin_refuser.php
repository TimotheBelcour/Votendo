<?php
require_once '../../includes/config.php';
session_start();

// Vérifier admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Compte/login.php");
    exit;
}

$userId = $_SESSION['user_id'];


$stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
$stmt->execute([$userId]);
$res = $stmt->fetch();
if (!$res) {
    echo "Accès interdit.";
    exit;
}

// Récupérer id du jeu
$idJeu = $_GET['id'] ?? 0;


// Suppression
$stmt = $conn->prepare("DELETE FROM jeux WHERE idJeu = ?");
$stmt->execute([$idJeu]);

header("Location: espaceAdmin.php");
exit;
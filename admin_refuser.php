<?php
require_once 'includes/config.php';
session_start();

// Vérifier admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo "Accès interdit.";
    exit;
}

$stmt->close();

// Récupérer id du jeu
$idJeu = $_GET['id'] ?? 0;

// Suppression
$stmt = $conn->prepare("DELETE FROM jeux WHERE idJeu = ?");
$stmt->bind_param("i", $idJeu);
$stmt->execute();
$stmt->close();

header("Location: admin_jeux.php");
exit;
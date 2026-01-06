<?php
// admin_valider.php : page pour valider une candidature (mettre isValide = 1)
// Nécessite que l'utilisateur soit connecté et soit administrateur
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/config.php';

require_role('admin', '../accesRefuse.php'); // depuis /Votendo/Admin -> ../accesRefuse.php
// Récupérer l'idJeu depuis les paramètres GET
$idJeu = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($idJeu <= 0) {
    header('Location: espaceAdmin.php');
    exit;
}
// Récupérer l'idUtilisateur depuis la session
$userId = $_SESSION['user_id'];

// Valider le jeu (isValide = 1)
$stmt = $conn->prepare("UPDATE jeux SET isValide = 1 WHERE idJeu = ?");
$stmt->execute([$idJeu]);
header('Location: espaceAdmin.php');
exit;
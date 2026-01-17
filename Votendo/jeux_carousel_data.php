<?php
// Génère un tableau JSON des jeux validés pour le carrousel d'accueil
require_once __DIR__ . '/../includes/config.php';

$stmt = $conn->query("SELECT titre, imagePath, resume FROM jeux WHERE isValide = 1 ORDER BY idJeu DESC LIMIT 10");
$jeux = [];
foreach ($stmt as $row) {
    $img = $row['imagePath'] ?? '';
    if ($img && !preg_match('#^https?://#', $img)) {
        $img = '../' . ltrim($img, '/');
    }
    $jeux[] = [
        'title' => $row['titre'],
        'img' => $img,
        'subtitle' => $row['resume'] ?: '',
    ];
}
header('Content-Type: application/json');
echo json_encode($jeux);

<?php
/**
 * Exemple de configuration locale MySQL
 * Chaque développeur doit copier ce fichier en "config.php"
 * puis renseigner ses propres identifiants.
 */

$host     = '127.0.0.1';   // Adresse du serveur MySQL (généralement localhost)
$user     = 'root';        // Nom d'utilisateur MySQL
$password = '';            // Mot de passe MySQL (à adapter selon votre machine)
$database = 'votendo';     // Nom de la base de données
$port     = 3306;          // Port MySQL

// Connexion MySQL
$conn = new mysqli($host, $user, $password, $database, $port);

// Vérification d'erreur
if ($conn->connect_error) {
    die("Erreur connexion MySQL : " . $conn->connect_error);
}

// Définir le charset pour éviter les bugs d'accents
$conn->set_charset("utf8mb4");
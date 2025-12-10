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

// Connexion PDO
try {
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $conn = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die("Erreur connexion MySQL : " . $e->getMessage());
}
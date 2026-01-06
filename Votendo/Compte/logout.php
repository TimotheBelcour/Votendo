<?php
// logout.php : page de déconnexion
// Inclure les fichiers nécessaires
require_once __DIR__ . '/../../includes/auth.php';
ensure_session_started();
// Vider toutes les variables de session
session_unset();

// Détruire le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Redirection vers l'accueil
header("Location: ../index.php");
exit;

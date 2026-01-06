<?php
// includes/auth.php : fonctions d'authentification et de gestion des accès
// Assure que la session est démarrée
function ensure_session_started(): void {
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
}

// Redirige vers un chemin relatif donné et évite que le code continue après le header
function redirect_to(string $relativePath): void {
  header('Location: ' . $relativePath);
  exit;
}

// Redirige vers la page de login si pas connecté
function require_login(string $loginPath = '/Votendo/Compte/login.php'): void {
  ensure_session_started();

  if (empty($_SESSION['user_id'])) {
    redirect_to($loginPath);
  }
}

// Redirige vers une page d'accès refusé si le rôle ne correspond pas
function require_role(string $role, string $forbiddenPath = '/Votendo/accesRefuse.php'): void {
  require_login(); // vérifie user_id

  if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
    http_response_code(403);
    redirect_to($forbiddenPath);
  }
}

// Redirige vers une page d'accès refusé si le rôle ne fait pas partie des rôles autorisés
function require_any_role(array $roles, string $forbiddenPath = '/Votendo/accesRefuse.php'): void {
  require_login();

  if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles, true)) {
    http_response_code(403);
    redirect_to($forbiddenPath);
  }
}

// Redirige vers une page de login si une clé spécifique de session n'est pas définie
function require_session_key(string $key, string $redirectPath = '/Votendo/Compte/login.php'): void {
    ensure_session_started();
    if (empty($_SESSION[$key])) {
        redirect_to($redirectPath);
    }
}
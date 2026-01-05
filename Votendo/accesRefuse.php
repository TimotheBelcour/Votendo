<?php
http_response_code(403);
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/header.php';
?>

<main class="page page--error">
  <section class="section">
    <div class="container">
      <h1>Accès refusé</h1>
      <p>Vous n’avez pas les droits nécessaires pour accéder à cette page.</p>
      <a href="index.php" class="btn btn--primary">Retour à l’accueil</a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
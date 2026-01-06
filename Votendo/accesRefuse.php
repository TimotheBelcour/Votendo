<?php
// accesRefuse.php : page affichée lorsqu'un utilisateur tente d'accéder à une page sans les droits nécessaires
http_response_code(403);
require_once __DIR__ . '/../includes/auth.php';
ensure_session_started();
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
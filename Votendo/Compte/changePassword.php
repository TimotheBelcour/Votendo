<?php
// changePassword.php : page pour changer le mot de passe

include '../../includes/header.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$idUtilisateur = (int) $_SESSION['user_id'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['oldPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Récupérer le hash actuel
    $stmt = $conn->prepare("SELECT passwordHash FROM utilisateur WHERE idutilisateur = ?");
    $stmt->execute([$idUtilisateur]);
    $user = $stmt->fetch();

    if (!$user) {
        $errors['general'] = 'Utilisateur introuvable.';
    } else {
        // Vérifier l'ancien mot de passe
        if (!password_verify($oldPassword, $user['passwordHash'])) {
            $errors['oldPassword'] = 'Ancien mot de passe incorrect.';
        }

        // Validation du nouveau
        if (empty($newPassword)) {
            $errors['newPassword'] = 'Le nouveau mot de passe est requis.';
        } elseif (strlen($newPassword) < 6) {
            $errors['newPassword'] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }

        if ($newPassword !== $confirmPassword) {
            $errors['confirmPassword'] = 'Les nouveaux mots de passe ne correspondent pas.';
        }

        if (empty($errors)) {
            // Hash le nouveau mot de passe
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Mettre à jour le mot de passe et firstLogin = 0
            $stmt = $conn->prepare("UPDATE utilisateur SET passwordHash = ?, firstLogin = 0 WHERE idutilisateur = ?");
            if ($stmt->execute([$passwordHash, $idUtilisateur])) {
                $success = true;
                // Rediriger vers index après 2 secondes
                header("refresh:2;url=../index.php");
            } else {
                $errors['general'] = 'Erreur lors de la mise à jour du mot de passe.';
            }
        }
    }
}
?>

<main class="page page--login">
  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Changer de mot de passe</p>
      <h1 class="hero__title">Modifier votre mot de passe</h1>
      <p class="hero__subtitle">
        Entrez votre ancien mot de passe et choisissez-en un nouveau.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            ✓ Mot de passe changé avec succès!<br>
            Redirection en cours...
          </div>
        <?php endif; ?>

        <?php if (isset($errors['general'])): ?>
          <div class="alert alert--error">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Ancien mot de passe -->
          <div class="form__field">
            <label for="oldPassword">Ancien mot de passe</label>
            <input
              type="password"
              id="oldPassword"
              name="oldPassword"
              placeholder="••••••••"
              required
            >
            <?php if (isset($errors['oldPassword'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['oldPassword'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <!-- Nouveau mot de passe -->
          <div class="form__field">
            <label for="newPassword">Nouveau mot de passe</label>
            <input
              type="password"
              id="newPassword"
              name="newPassword"
              placeholder="••••••••"
              required
            >
            <?php if (isset($errors['newPassword'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['newPassword'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <!-- Confirmer nouveau mot de passe -->
          <div class="form__field">
            <label for="confirmPassword">Confirmer le nouveau mot de passe</label>
            <input
              type="password"
              id="confirmPassword"
              name="confirmPassword"
              placeholder="••••••••"
              required
            >
            <?php if (isset($errors['confirmPassword'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['confirmPassword'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              Changer le mot de passe
            </button>
          </div>
        </form>

      </div>

    </div>
  </section>

</main>

<?php include '../../includes/footer.php'; ?>
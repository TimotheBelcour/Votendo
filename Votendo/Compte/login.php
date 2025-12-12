<?php
// login.php : page de connexion avec authentification réelle

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../includes/config.php';

$errors  = [];
$success = false;

$email    = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération + nettoyage
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Rechercher l'utilisateur par email
    $stmt = $conn->prepare("SELECT idutilisateur, nomUtilisateur, passwordHash, firstLogin FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {

        // Vérifier le mot de passe avec password_verify
        if (password_verify($password, $user['passwordHash'])) {
            // Authentification réussie
            $success = true;
            // Créer la session
            $_SESSION['user_id'] = $user['idutilisateur'];
            $_SESSION['user_name'] = $user['nomUtilisateur'];
            $_SESSION['user_email'] = $email;
            // Vérifier si première connexion
            if ($user['firstLogin'] == 1) {
                // Rediriger vers changement de mot de passe
                header("Location: changePassword.php");
                exit;
            } else {
                // Redirection après 2 secondes
                header("refresh:2;url=../index.php");
            }
        } else {
            // Mot de passe incorrect
            $errors['general'] = 'Email ou mot de passe incorrect.';
        }
    } else {
        // Email non trouvé
        $errors['general'] = 'Email ou mot de passe incorrect.';
    }
}

include '../../includes/header.php';
?>

<main class="page page--login">

  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Se connecter</p>
      <h1 class="hero__title">Connexion à Votendo</h1>
      <p class="hero__subtitle">
        Connectez-vous pour participer à nos votes et découvrir les favoris de la communauté.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            ✓ Connexion réussie!<br>
            Redirection en cours...
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="alert alert--error">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Email -->
          <div class="form__field">
            <label for="email">Adresse e-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
              placeholder="Ex : timothe@example.com"
              required
            >
          </div>

          <!-- Mot de passe -->
          <div class="form__field">
            <label for="password">Mot de passe</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="••••••••"
              required
            >
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              Se connecter
            </button>
          </div>

          <p class="login-hint">
            Vous n'avez pas de compte? <a href="inscription.php">S'inscrire</a>
          </p>
        </form>

      </div>

    </div>
  </section>

</main>

<?php include '../../includes/footer.php'; ?>

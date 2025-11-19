<?php
// login.php : page de connexion
// Ici, pas de vraie authentification : on vérifie juste que les champs sont remplis.

$errors  = [];
$success = false;

$email    = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération + nettoyage
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validation e-mail
    if ($email === '') {
        $errors['email'] = 'Merci d’indiquer votre adresse e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L’adresse e-mail n’est pas valide.';
    }

    // Validation mot de passe
    if ($password === '') {
        $errors['password'] = 'Merci d’indiquer votre mot de passe.';
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }

    // En MVP1 : si pas d’erreur, on considère la "connexion" comme réussie
    if (empty($errors)) {
        $success = true;
        // Ici, plus tard : vérification en base de données + création de session
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="page page--login">

  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Se connecter</p>
      <h1 class="hero__title">Connexion à Votendo</h1>
      <p class="hero__subtitle">
        Cette page illustre l’écran de connexion. En MVP1, aucune authentification réelle n’est encore mise en place.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            Connexion simulée avec succès<br>
            (Pour la MVP1, les comptes utilisateurs ne sont pas encore gérés.)
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Email -->
          <div class="form__field <?= isset($errors['email']) ? 'form__field--error' : '' ?>">
            <label for="email">Adresse e-mail</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
              placeholder="Ex : timothe@example.com"
              required
            >
            <?php if (isset($errors['email'])): ?>
              <p class="form__error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
          </div>

          <!-- Mot de passe -->
          <div class="form__field <?= isset($errors['password']) ? 'form__field--error' : '' ?>">
            <label for="password">Mot de passe</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="••••••••"
              required
            >
            <?php if (isset($errors['password'])): ?>
              <p class="form__error"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              Se connecter
            </button>
          </div>

          <p class="login-hint">
            Pour le MVP1, cette page ne fait qu’illustrer la future authentification.
          </p>
        </form>

      </div>

    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
<?php
include 'includes/header.php';

$errors = [];
$success = false;

$nomUtilisateur = '';
$email = '';
$password = '';
$passwordConfirm = '';

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomUtilisateur = trim($_POST['nomUtilisateur'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['passwordConfirm'] ?? '';

    // Validation du nom d'utilisateur
    if ($nomUtilisateur === '') {
        $errors['nomUtilisateur'] = "Le nom d'utilisateur est requis.";
    } elseif (strlen($nomUtilisateur) < 3) {
        $errors['nomUtilisateur'] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
    }

    // Validation de l'e-mail
    if ($email === '') {
        $errors['email'] = "L'adresse e-mail est requise.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'adresse e-mail n'est pas valide.";
    }

    // Validation du mot de passe
    if ($password === '') {
        $errors['password'] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $passwordConfirm) {
        $errors['password'] = "Les mots de passe ne correspondent pas.";
    }

    // Si pas d'erreurs, vérifier si l'e-mail existe déjà et insérer l'utilisateur
    if (empty($errors)) {
        // Vérifier si l'e-mail existe déjà
        $stmt = $conn->prepare("SELECT idutilisateur FROM utilisateur WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors['email'] = "Cette adresse e-mail est déjà utilisée.";
        } else {
            // Hash du mot de passe avec PASSWORD_DEFAULT (bcrypt)
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur
            $stmt = $conn->prepare("INSERT INTO utilisateur (nomUtilisateur, email, passwordHash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nomUtilisateur, $email, $passwordHash);

            if ($stmt->execute()) {
                $success = true;
                $nomUtilisateur = '';
                $email = '';
                $password = '';
                $passwordConfirm = '';
            } else {
                $errors['general'] = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }

        $stmt->close();
    }
}
?>

<main class="page page--inscription">

  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Créer un compte</p>
      <h1 class="hero__title">Inscription à Votendo</h1>
      <p class="hero__subtitle">
        Rejoignez-nous et participez à nos votes exclusifs sur vos jeux préférés.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            ✓ Inscription réussie!<br>
            Vous pouvez maintenant <a href="login.php">vous connecter</a>.
          </div>
        <?php endif; ?>

        <?php if (isset($errors['general'])): ?>
          <div class="alert alert--error">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Nom d'utilisateur -->
          <div class="form__field <?= isset($errors['nomUtilisateur']) ? 'form__field--error' : '' ?>">
            <label for="nomUtilisateur">Nom d'utilisateur</label>
            <input
              type="text"
              id="nomUtilisateur"
              name="nomUtilisateur"
              value="<?= htmlspecialchars($nomUtilisateur, ENT_QUOTES, 'UTF-8') ?>"
              placeholder="Ex : Timothe"
              required
            >
            <?php if (isset($errors['nomUtilisateur'])): ?>
              <p class="form__error"><?= htmlspecialchars($errors['nomUtilisateur'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
          </div>

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

          <!-- Confirmer mot de passe -->
          <div class="form__field">
            <label for="passwordConfirm">Confirmer le mot de passe</label>
            <input
              type="password"
              id="passwordConfirm"
              name="passwordConfirm"
              placeholder="••••••••"
              required
            >
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              S'inscrire
            </button>
          </div>

          <p class="login-hint">
            Vous avez déjà un compte? <a href="login.php">Se connecter</a>
          </p>
        </form>

      </div>

    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>

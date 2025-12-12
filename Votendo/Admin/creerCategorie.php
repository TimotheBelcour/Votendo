<?php
// creerCategorie.php : page pour créer une nouvelle catégorie

// Démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../includes/header.php';

// 1) Sécurité : vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Compte/login.php');
    exit;
}

$idUtilisateur = (int) $_SESSION['user_id'];

// 2) Vérifier qu'il est administrateur
$isAdmin = false;

$stmt = $conn->prepare("SELECT idAdministrateur FROM administrateur WHERE idUtilisateur = ?");
$stmt->execute([$idUtilisateur]);
$adminResult = $stmt->fetch();

if ($adminResult) {
    $isAdmin = true;
}

if (!$isAdmin) {
    // On bloque l'accès si ce n'est pas un admin
    http_response_code(403);
    ?>
    <main class="page page--small">
        <section class="hero hero--small">
            <div class="container hero__content">
                <h1 class="hero__title">Accès refusé</h1>
                <p class="hero__subtitle">Cette page est réservée aux administrateurs.</p>
            </div>
        </section>
    </main>
    <?php
    include '../../includes/footer.php';
    exit;
}

$errors  = [];
$success = false;

$nomCategorie = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération + nettoyage
    $nomCategorie = trim($_POST['nomCategorie'] ?? '');

    // Validation
    if (empty($nomCategorie)) {
        $errors['nomCategorie'] = 'Le nom de la catégorie est requis.';
    } elseif (strlen($nomCategorie) > 45) {
        $errors['nomCategorie'] = 'Le nom de la catégorie ne doit pas dépasser 45 caractères.';
    } else {
        // Vérifier si la catégorie existe déjà
        $stmt = $conn->prepare("SELECT idCategorie FROM categorie WHERE nomCategorie = ?");
        $stmt->execute([$nomCategorie]);
        if ($stmt->fetch()) {
            $errors['nomCategorie'] = 'Cette catégorie existe déjà.';
        }
    }

    if (empty($errors)) {
        // Insérer la nouvelle catégorie
        $stmt = $conn->prepare("INSERT INTO categorie (nomCategorie) VALUES (?)");
        if ($stmt->execute([$nomCategorie])) {
            $success = true;
            $nomCategorie = ''; // Reset le champ
        } else {
            $errors['general'] = 'Erreur lors de la création de la catégorie.';
        }
    }
}
?>

<main class="page page--login">
  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Administration</p>
      <h1 class="hero__title">Créer une nouvelle catégorie</h1>
      <p class="hero__subtitle">
        Ajoutez une nouvelle catégorie pour les jeux.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            ✓ Catégorie créée avec succès!
          </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
          <div class="alert alert--error">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Nom de la catégorie -->
          <div class="form__field">
            <label for="nomCategorie">Nom de la catégorie</label>
            <input
              type="text"
              id="nomCategorie"
              name="nomCategorie"
              value="<?= htmlspecialchars($nomCategorie, ENT_QUOTES, 'UTF-8') ?>"
              placeholder="Ex : Action, Aventure, RPG"
              required
            >
            <?php if (isset($errors['nomCategorie'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['nomCategorie'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              Créer la catégorie
            </button>
          </div>
        </form>

      </div>

    </div>
  </section>

</main>

<?php include '../../includes/footer.php'; ?>
<?php
// creerNomination.php : page pour créer une nouvelle nomination
// Nécessite que l'utilisateur soit connecté et soit administrateur
require_once __DIR__ . '/../../includes/auth.php';
require_role('admin', '../accesRefuse.php');
require_once __DIR__ . '/../../includes/header.php';
// Connexion à la base de données
$idUtilisateur = (int) $_SESSION['user_id'];
// Vérifier si admin
$errors  = [];
$success = false;
// Initialisation des variables
$idJeu = '';
$idCategorie = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération
    $idJeu = (int) ($_POST['idJeu'] ?? 0);
    $idCategorie = (int) ($_POST['idCategorie'] ?? 0);

    // Validation
    if ($idJeu <= 0) {
        $errors['idJeu'] = 'Veuillez sélectionner un jeu.';
    }
    if ($idCategorie <= 0) {
        $errors['idCategorie'] = 'Veuillez sélectionner une catégorie.';
    }

    // Vérifier si la nomination existe déjà
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT idNominations FROM nominations WHERE idJeux = ? AND idCategories = ?");
        $stmt->execute([$idJeu, $idCategorie]);
        if ($stmt->fetch()) {
            $errors['general'] = 'Cette nomination existe déjà.';
        }
    }

    if (empty($errors)) {
        // Insérer la nouvelle nomination
        $stmt = $conn->prepare("INSERT INTO nominations (idCategories, idJeux) VALUES (?, ?)");
        if ($stmt->execute([$idCategorie, $idJeu])) {
            $success = true;
            $idJeu = '';
            $idCategorie = '';
        } else {
            $errors['general'] = 'Erreur lors de la création de la nomination.';
        }
    }
}

// Récupérer les jeux valides
$jeux = $conn->query("SELECT idJeu, titre FROM jeux WHERE isValide = 1 ORDER BY titre")->fetchAll();

// Récupérer les catégories
$categories = $conn->query("SELECT idCategorie, nomCategorie FROM categorie ORDER BY nomCategorie")->fetchAll();
?>

<main class="page page--login">
  <!-- Hero / intro -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Administration</p>
      <h1 class="hero__title">Créer une nouvelle nomination</h1>
      <p class="hero__subtitle">
        Associez un jeu à une catégorie pour créer une nomination.
      </p>
    </div>
  </section>

  <section class="section">
    <div class="container">

      <div class="login-card">

        <?php if ($success): ?>
          <div class="alert alert--success">
            ✓ Nomination créée avec succès!
          </div>
        <?php endif; ?>

        <?php if (!empty($errors['general'])): ?>
          <div class="alert alert--error">
            <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" class="login-form" novalidate>
          <!-- Jeu -->
          <div class="form__field">
            <label for="idJeu">Jeu</label>
            <select id="idJeu" name="idJeu" required>
              <option value="">Sélectionnez un jeu</option>
              <?php foreach ($jeux as $jeu): ?>
                <option value="<?= (int) $jeu['idJeu'] ?>" <?= $idJeu == $jeu['idJeu'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($jeu['titre'], ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['idJeu'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['idJeu'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <!-- Catégorie -->
          <div class="form__field">
            <label for="idCategorie">Catégorie</label>
            <select id="idCategorie" name="idCategorie" required>
              <option value="">Sélectionnez une catégorie</option>
              <?php foreach ($categories as $categorie): ?>
                <option value="<?= (int) $categorie['idCategorie'] ?>" <?= $idCategorie == $categorie['idCategorie'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($categorie['nomCategorie'], ENT_QUOTES, 'UTF-8') ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if (isset($errors['idCategorie'])): ?>
              <span class="error-message"><?= htmlspecialchars($errors['idCategorie'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
          </div>

          <div class="form__actions">
            <button type="submit" class="btn btn--primary">
              Créer la nomination
            </button>
          </div>
        </form>

      </div>

    </div>
  </section>

</main>

<?php require_once __DIR__. '/../../includes/footer.php'; ?>
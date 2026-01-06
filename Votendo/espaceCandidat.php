<?php
// proposer_jeu.php : espace candidat pour proposer un jeu

require_once __DIR__ . '/../includes/auth.php';
require_role('candidat', 'accesRefuse.php');
require_once __DIR__ . '/../includes/header.php';

// Récupérer l'idUtilisateur depuis la session
$idUtilisateur = $_SESSION['user_id'];
// Initialisation des variables
$errors = [];
$success = false;

// 1) Récupérer l'id du candidat correspondant à cet utilisateur
$stmt = $conn->prepare("SELECT idCandidats FROM candidats WHERE idUtilisateur = ?");
$stmt->execute([$idUtilisateur]);
$row = $stmt->fetch();

if (!$row) {
    // L'utilisateur n'est pas déclaré comme candidat
    $errors['general'] = "Votre compte n'est pas associé à un profil candidat. Contactez l'administrateur.";
} else {
    $idCandidat = (int) $row['idCandidats'];
}

// 2) Traitement du formulaire si on a un POST et si on a bien un idCandidat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {

    // Récupération + nettoyage
    $titre       = trim($_POST['titre'] ?? '');
    $studio      = trim($_POST['studio'] ?? '');
    $dateSortie  = trim($_POST['date_sortie'] ?? '');
    $imagePath   = trim($_POST['image'] ?? '');
    $videoUrl    = trim($_POST['video'] ?? '');
    $resume      = trim($_POST['resume'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Petites validations
    if ($titre === '')       { $errors['titre'] = "Le titre du jeu est obligatoire."; }
    if ($studio === '')      { $errors['studio'] = "Le nom du studio est obligatoire."; }
    if ($dateSortie === '')  { $errors['date_sortie'] = "La date de sortie est obligatoire."; }
    if ($imagePath === '')   { $errors['image'] = "L'URL de l'image (jaquette) est obligatoire."; }
    if ($videoUrl === '')    { $errors['video'] = "L'URL de la vidéo de présentation est obligatoire."; }
    if ($resume === '')      { $errors['resume'] = "Le résumé court est obligatoire."; }
    if ($description === '') { $errors['description'] = "La description complète est obligatoire."; }

    if (empty($errors)) {
        // 3) Insertion du jeu en BDD avec isValide = 0 (en attente de validation)
        $stmt = $conn->prepare("
            INSERT INTO jeux (titre, studio, dateSortie, idCandidat, imagePath, videoUrl, resume, description, isValide)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)
        ");

        if ($stmt->execute([$titre, $studio, $dateSortie, $idCandidat, $imagePath, $videoUrl, $resume, $description])) {
            $success = true;

            // Réinitialiser les champs du formulaire
            $titre = $studio = $dateSortie = $imagePath = $videoUrl = $resume = $description = '';
        } else {
            $errors['general'] = "Erreur lors de l'enregistrement du jeu. Réessayez plus tard.";
        }
    }
}
?>

<main class="page page--login">
    <!-- On réutilise les mêmes classes CSS que login.php pour garder le style -->

    <section class="hero hero--small">
        <div class="container hero__content">
            <p class="hero__eyebrow">Espace candidat</p>
            <h1 class="hero__title">Proposer un nouveau jeu</h1>
            <p class="hero__subtitle">
                Remplissez cette fiche pour soumettre votre jeu au concours. Un administrateur devra ensuite valider votre participation.
            </p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="login-card"><!-- on réutilise la même carte que pour le login -->

                <?php if ($success): ?>
                    <div class="alert alert--success">
                        ✔ Jeu proposé avec succès !<br>
                        Il sera visible sur le site dès qu'un administrateur l'aura validé.
                    </div>
                <?php endif; ?>

                <?php if (!empty($errors['general'])): ?>
                    <div class="alert alert--error">
                        <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="post" class="login-form" novalidate>
                    <!-- Titre -->
                    <div class="form__field">
                        <label for="titre">Titre du jeu</label>
                        <input type="text" id="titre" name="titre"
                               value="<?= htmlspecialchars($titre ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($errors['titre'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['titre'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Studio -->
                    <div class="form__field">
                        <label for="studio">Studio</label>
                        <input type="text" id="studio" name="studio"
                               value="<?= htmlspecialchars($studio ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($errors['studio'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['studio'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Date de sortie -->
                    <div class="form__field">
                        <label for="date_sortie">Date de sortie</label>
                        <input type="date" id="date_sortie" name="date_sortie"
                               value="<?= htmlspecialchars($dateSortie ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($errors['date_sortie'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['date_sortie'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- URL image -->
                    <div class="form__field">
                        <label for="image">URL de l'image (jaquette)</label>
                        <input type="url" id="image" name="image"
                               placeholder="https://..."
                               value="<?= htmlspecialchars($imagePath ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($errors['image'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['image'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- URL vidéo -->
                    <div class="form__field">
                        <label for="video">URL de la vidéo de présentation (YouTube, etc.)</label>
                        <input type="url" id="video" name="video"
                               placeholder="https://www.youtube.com/..."
                               value="<?= htmlspecialchars($videoUrl ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        <?php if (!empty($errors['video'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['video'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Résumé -->
                    <div class="form__field">
                        <label for="resume">Résumé court (pour la carte de vote)</label>
                        <textarea id="resume" name="resume" rows="3" required><?= htmlspecialchars($resume ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <?php if (!empty($errors['resume'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['resume'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Description complète -->
                    <div class="form__field">
                        <label for="description">Description complète (pour la page détaillée)</label>
                        <textarea id="description" name="description" rows="6" required><?= htmlspecialchars($description ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        <?php if (!empty($errors['description'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="form__actions">
                        <button type="submit" class="btn btn--primary">
                            Soumettre le jeu à validation
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
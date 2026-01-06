<?php
// contact.php : page de contact avec traitement du formulaire
require_once __DIR__ . '/../includes/header.php';
// Initialisation des variables
$errors  = [];
$success = false;
// Champs du formulaire
$name    = '';
$email   = '';
$description = '';

// Si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération + nettoyage des données
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // Validation : Nom
    if ($name === '') {
        $errors['name'] = 'Merci d’indiquer votre nom.';
    } elseif (strlen($name) > 100) {
        $errors['name'] = 'Le nom est trop long (100 caractères max).';
    }

    // Validation : Email
    if ($email === '') {
        $errors['email'] = 'Merci d’indiquer votre adresse e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'L’adresse e-mail n’est pas valide.';
    }

    // Validation : Description
    if ($description === '') {
        $errors['description'] = 'Merci de décrire votre demande.';
    } elseif (strlen($description) < 10) {
        $errors['description'] = 'La description est trop courte (10 caractères minimum).';
    }

    // Si tout est OK
    if (empty($errors)) {
        // Insérer dans la table demandes_candidature
        $stmt = $conn->prepare("INSERT INTO demandes_candidature (nom, email, description) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $description])) {
            $success = true;
            // On vide les champs après envoi
            $name = '';
            $email = '';
            $description = '';
        } else {
            $errors['general'] = 'Erreur lors de la soumission. Veuillez réessayer.';
        }
    }
}
?>

<main class="page page--contact">
    <!-- Hero / introduction -->
    <section class="hero hero--small">
        <div class="container hero__content">
            <p class="hero__eyebrow">Candidature</p>
            <h1 class="hero__title">Soumettre une candidature</h1>
            <p class="hero__subtitle">
                Soumets ta candidature pour proposer un jeu sur Votendo.
                Décris ton studio, ton jeu et pourquoi il mérite d'être nominé.
            </p>
        </div>
    </section>

    <!-- Bloc de contexte rapide (rappel du site) -->
    <section class="section section--context">
        <div class="container">
            <h2 class="section__title">Rappel du contexte</h2>
            <p class="section__text">
                Votendo est une plateforme de vote pour élire le <strong>jeu vidéo de l’année</strong>.
                Chaque joueur peut consulter la liste des jeux nominés, voter pour son favori
                et consulter les résultats du scrutin.
            </p>
            <p class="section__text">
                Le mode de scrutin choisi est un <strong>vote simple</strong> :
                <em>un seul vote par utilisateur et par édition</em>.
            </p>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="section section--contact-form">
        <div class="container">

            <?php if ($success): ?>
                <div class="alert alert--success">
                    Merci pour ton message &nbsp;! Nous reviendrons vers toi dès que possible.
                </div>
            <?php endif; ?>

            <form method="post" class="form contact-form" novalidate>
                <div class="form__grid">
                    <!-- Nom -->
                    <div class="form__field <?= isset($errors['name']) ? 'form__field--error' : '' ?>">
                        <label for="name">Nom / Pseudo</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Ex : Timothé"
                            required
                        >
                        <?php if (isset($errors['name'])): ?>
                            <p class="form__error"><?= htmlspecialchars($errors['name'], ENT_QUOTES, 'UTF-8') ?></p>
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
                </div>

                <!-- Description -->
                <div class="form__field form__field--full <?= isset($errors['description']) ? 'form__field--error' : '' ?>">
                    <label for="description">Description de la demande</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Décris ton studio, ton jeu et pourquoi il mérite d'être nominé…"
                        required
                    ><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></textarea>
                    <?php if (isset($errors['description'])): ?>
                        <p class="form__error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Bouton envoyer -->
                <div class="form__actions">
                    <button type="submit" class="btn btn--primary">
                        Envoyer le message
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
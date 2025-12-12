<?php
// espaceAdmin.php : espace d'administration pour valider les jeux proposés

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

// 3) Gestion des actions (valider / refuser)
$successMessage = '';
$errorMessage   = '';

if (isset($_GET['action'], $_GET['idJeu'])) {
    $action = $_GET['action'];
    $idJeu  = (int) $_GET['idJeu'];

    if ($action === 'valider') {
        $stmt = $conn->prepare("UPDATE jeux SET isValide = 1 WHERE idJeu = ?");
        if ($stmt->execute([$idJeu])) {
            $successMessage = "Le jeu #{$idJeu} a été validé.";
        } else {
            $errorMessage = "Erreur lors de la validation du jeu.";
        }
    } elseif ($action === 'refuser') {
        // Ici on supprime complètement le jeu refusé.
        // Si tu veux plus tard un état "refusé", il faudra ajouter un statut dans la BDD.
        $stmt = $conn->prepare("DELETE FROM jeux WHERE idJeu = ?");
        if ($stmt->execute([$idJeu])) {
            $successMessage = "Le jeu #{$idJeu} a été supprimé (refusé).";
        } else {
            $errorMessage = "Erreur lors de la suppression du jeu.";
        }
    }
}

// 4) Récupérer la liste des jeux en attente
$sql = "
    SELECT 
        j.idJeu,
        j.titre,
        j.studio,
        j.dateSortie,
        j.imagePath,
        j.videoUrl,
        j.resume,
        u.nomUtilisateur AS nomCandidat,
        u.email          AS emailCandidat
    FROM jeux j
    LEFT JOIN candidats c    ON j.idCandidat = c.idCandidats
    LEFT JOIN utilisateur u  ON c.idUtilisateur = u.idUtilisateur
    WHERE j.isValide = 0
    ORDER BY j.idJeu DESC
";

$pendingGamesResult = $conn->query($sql);
?>
<main class="page page--admin">
    <section class="hero hero--small">
        <div class="container hero__content">
            <p class="hero__eyebrow">Espace administrateur</p>
            <h1 class="hero__title">Validation des jeux proposés</h1>
            <p class="hero__subtitle">
                Validez ou refusez les jeux soumis par les studios / candidats avant leur publication.
            </p>
            <div style="margin-top: 1rem;">
                <a href="creerCategorie.php" class="btn btn--primary" style="margin-right: 1rem;">Créer une nouvelle catégorie</a>
                <a href="creerNomination.php" class="btn btn--primary" style="margin-right: 1rem;">Créer une nouvelle nomination</a>
                <a href="validerCandidature.php" class="btn btn--primary">Valider les candidatures</a>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php if ($successMessage): ?>
                <div class="alert alert--success">
                    <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($errorMessage): ?>
                <div class="alert alert--error">
                    <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($pendingGamesResult && $pendingGamesResult->rowCount() > 0): ?>
                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Titre</th>
                            <th>Studio</th>
                            <th>Candidat</th>
                            <th>Date de sortie</th>
                            <th>Résumé</th>
                            <th>Liens</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($game = $pendingGamesResult->fetch()): ?>
                            <tr>
                                <td><?= (int) $game['idJeu'] ?></td>
                                <td><?= htmlspecialchars($game['titre'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($game['studio'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?= htmlspecialchars($game['nomCandidat'] ?? '—', ENT_QUOTES, 'UTF-8') ?><br>
                                    <small><?= htmlspecialchars($game['emailCandidat'] ?? '', ENT_QUOTES, 'UTF-8') ?></small>
                                </td>
                                <td>
                                    <?= htmlspecialchars($game['dateSortie'], ENT_QUOTES, 'UTF-8') ?>
                                </td>
                                <td>
                                    <?php
                                    $short = $game['resume'] ?? '';
                                    if (strlen($short) > 120) {
                                        $short = substr($short, 0, 117) . '...';
                                    }
                                    echo htmlspecialchars($short, ENT_QUOTES, 'UTF-8');
                                    ?>
                                </td>
                                <td>
                                    <?php if (!empty($game['imagePath'])): ?>
                                        <a href="<?= htmlspecialchars($game['imagePath'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Jaquette</a><br>
                                    <?php endif; ?>
                                    <?php if (!empty($game['videoUrl'])): ?>
                                        <a href="<?= htmlspecialchars($game['videoUrl'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Vidéo</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn--small btn--success"
                                       href="espaceAdmin.php?action=valider&idJeu=<?= (int) $game['idJeu'] ?>">
                                        Valider
                                    </a>
                                    <a class="btn btn--small btn--danger"
                                       href="espaceAdmin.php?action=refuser&idJeu=<?= (int) $game['idJeu'] ?>"
                                       onclick="return confirm('Refuser et supprimer ce jeu ?');">
                                        Refuser
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Aucun jeu en attente de validation pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../../includes/footer.php'; ?>
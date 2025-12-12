<?php
// validerCandidature.php : page pour valider les candidatures

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

if (isset($_GET['action'], $_GET['idDemande'])) {
    $action = $_GET['action'];
    $idDemande = (int) $_GET['idDemande'];

    if ($action === 'valider') {
        // Récupérer la demande
        $stmt = $conn->prepare("SELECT nom, email, description FROM demandes_candidature WHERE idDemande = ?");
        $stmt->execute([$idDemande]);
        $demande = $stmt->fetch();

        if ($demande) {
            // Générer nomUtilisateur : nettoyer le nom
            $nomUtilisateur = preg_replace('/[^a-zA-Z0-9]/', '', $demande['nom']);
            $nomUtilisateur = substr($nomUtilisateur, 0, 20); // max 20
            if (empty($nomUtilisateur)) $nomUtilisateur = 'Candidat' . $idDemande;

            // Vérifier unicité
            $original = $nomUtilisateur;
            $counter = 1;
            while (true) {
                $stmtCheck = $conn->prepare("SELECT idutilisateur FROM utilisateur WHERE nomUtilisateur = ?");
                $stmtCheck->execute([$nomUtilisateur]);
                if (!$stmtCheck->fetch()) break;
                $nomUtilisateur = $original . $counter;
                $counter++;
            }

            // Générer mot de passe
            $password = bin2hex(random_bytes(8)); // 16 caractères
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insérer utilisateur
            $stmtInsert = $conn->prepare("INSERT INTO utilisateur (nomUtilisateur, email, passwordHash, firstLogin) VALUES (?, ?, ?, 1)");
            if ($stmtInsert->execute([$nomUtilisateur, $demande['email'], $passwordHash])) {
                $newIdUtilisateur = $conn->lastInsertId();

                // Insérer dans candidats
                $stmtCandidat = $conn->prepare("INSERT INTO candidats (idUtilisateur) VALUES (?)");
                $stmtCandidat->execute([$newIdUtilisateur]);

                // Insérer dans votants
                $tokenVotants = substr(bin2hex(random_bytes(23)), 0, 45);
                $stmtVotant = $conn->prepare("INSERT INTO votants (idUtilisateur, tokenVotants) VALUES (?, ?)");
                $stmtVotant->execute([$newIdUtilisateur, $tokenVotants]);

                // Supprimer la demande
                $stmtDelete = $conn->prepare("DELETE FROM demandes_candidature WHERE idDemande = ?");
                $stmtDelete->execute([$idDemande]);

                $successMessage = "Candidature validée. Nom d'utilisateur : $nomUtilisateur, Mot de passe : $password";
            } else {
                $errorMessage = "Erreur lors de la création du compte.";
            }
        } else {
            $errorMessage = "Demande introuvable.";
        }
    } elseif ($action === 'refuser') {
        // Supprimer la demande
        $stmt = $conn->prepare("DELETE FROM demandes_candidature WHERE idDemande = ?");
        if ($stmt->execute([$idDemande])) {
            $successMessage = "Candidature refusée.";
        } else {
            $errorMessage = "Erreur lors du refus.";
        }
    }
}

// 4) Récupérer les demandes en attente
$sql = "SELECT idDemande, nom, email, description, dateSoumission FROM demandes_candidature ORDER BY dateSoumission DESC";
$pendingCandidatures = $conn->query($sql);
?>

<main class="page page--admin">
    <section class="hero hero--small">
        <div class="container hero__content">
            <p class="hero__eyebrow">Espace administrateur</p>
            <h1 class="hero__title">Validation des candidatures</h1>
            <p class="hero__subtitle">
                Validez ou refusez les candidatures soumises pour devenir candidat.
            </p>
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

            <?php if ($pendingCandidatures->rowCount() > 0): ?>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($candidature = $pendingCandidatures->fetch()): ?>
                                <tr>
                                    <td><?= (int) $candidature['idDemande'] ?></td>
                                    <td><?= htmlspecialchars($candidature['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($candidature['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td class="description-cell" data-full="<?= htmlspecialchars($candidature['description'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(substr($candidature['description'], 0, 100), ENT_QUOTES, 'UTF-8') ?>...</td>
                                    <td><?= htmlspecialchars($candidature['dateSoumission'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a class="btn btn--small btn--success"
                                           href="validerCandidature.php?action=valider&idDemande=<?= (int) $candidature['idDemande'] ?>">
                                            Valider
                                        </a>
                                        <a class="btn btn--small btn--danger"
                                           href="validerCandidature.php?action=refuser&idDemande=<?= (int) $candidature['idDemande'] ?>"
                                           onclick="return confirm('Refuser cette candidature ?');">
                                            Refuser
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>Aucune candidature en attente pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cells = document.querySelectorAll('.description-cell');
            cells.forEach(cell => {
                const truncated = cell.textContent;
                const full = cell.getAttribute('data-full');
                cell.addEventListener('mouseover', function() {
                    cell.textContent = full;
                    cell.style.whiteSpace = 'normal';
                    cell.style.maxHeight = 'none';
                    cell.parentElement.style.height = 'auto';
                });
                cell.addEventListener('mouseout', function() {
                    cell.textContent = truncated;
                    cell.style.whiteSpace = '';
                    cell.style.maxHeight = '';
                    cell.parentElement.style.height = '';
                });
            });
        });
    </script>
</main>

<?php include '../../includes/footer.php'; ?>
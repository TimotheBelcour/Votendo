<?php
// vote.php : page de vote pour les utilisateurs connectés avec un token de votant valide
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

ensure_session_started();
require_login('Compte/login.php');

$userId = (int)($_SESSION['user_id'] ?? 0);
$role   = $_SESSION['role'] ?? '';

// Seuls les membres peuvent voter
$canVote = ($role === 'membre');

// Token votant uniquement si membre
$token = null;
if ($canVote) {
    $stmt = $conn->prepare("SELECT tokenVotants FROM votants WHERE idUtilisateur = ? LIMIT 1");
    $stmt->execute([$userId]);
    $token = $stmt->fetchColumn();

    // Si un membre n'a pas de token -> accès refusé (cas anormal)
    if (!$token) {
        header('Location: accesRefuse.php');
        exit;
    }
    // 2) Mettre/rafraîchir le token en session (évite token périmé)
    $_SESSION['tokenVotants'] = $token;
} else {
    // Sécurité : admin/candidat ne doit pas garder un token en session
    unset($_SESSION['tokenVotants']);
}
require_once __DIR__ . '/../includes/header.php';
// Initialisation des messages
$successMsg = '';
$errorMsg = '';

// Traitement du vote
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idJeu'])) {
    // Si pas membre => pas de vote
    if (!$canVote) {
        $errorMsg = "Tu peux consulter les jeux, mais seuls les membres peuvent voter.";
    } else {
        $idJeu = (int)($_POST['idJeu'] ?? 0);
        $token = $_SESSION['tokenVotants'] ?? null;

        if (!$token) {
            // sécurité (ne devrait pas arriver si $canVote)
            $errorMsg = "Token de vote manquant.";
        } else {
            try {
                // Vérifier que le jeu est valide
                $stmt = $conn->prepare("SELECT COUNT(*) FROM jeux WHERE idJeu = ? AND isValide = 1");
                $stmt->execute([$idJeu]);

                if ((int)$stmt->fetchColumn() === 0) {
                    $errorMsg = "Jeu invalide.";
                } else {
                    // Insert : si déjà voté, l’unicité doit bloquer en BDD (unique sur tokenVotants, ou tokenVotants+édition)
                    $stmt = $conn->prepare("INSERT INTO votes (tokenVotants, idJeu, `timestamp`) VALUES (?, ?, NOW())");
                    $stmt->execute([$token, $idJeu]);

                    $successMsg = "Vote enregistré ! Merci.";
                }
            } catch (PDOException $e) {
                // 1062 = duplicate entry (déjà voté)
                if (isset($e->errorInfo[1]) && (int)$e->errorInfo[1] === 1062) {
                    $errorMsg = "Tu as déjà voté pour cette édition.";
                } else {
                    $errorMsg = "Erreur lors de l'enregistrement du vote.";
                }
            }
        }
    }
}
// --- Vérifier si l'utilisateur a déjà voté ---
// On ne peut vérifier que si membre + token
$hasVoted = false;
$alreadyVotedGameId = null;
if ($canVote && !empty($_SESSION['tokenVotants'])) {
  $stmt = $conn->prepare("SELECT idJeu FROM votes WHERE tokenVotants = ? LIMIT 1");
  $stmt->execute([$_SESSION['tokenVotants']]);
  $alreadyVotedGameId = $stmt->fetchColumn();
  $hasVoted = ($alreadyVotedGameId !== false);
}
?>


<?php
// Récupérer toutes les catégories
$categories = $conn->query("SELECT idCategorie, nomCategorie FROM categorie ORDER BY nomCategorie")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les jeux par catégorie
$catJeux = [];
foreach ($categories as $cat) {
    $stmt = $conn->prepare("SELECT j.idJeu, j.titre, j.studio, j.dateSortie, j.imagePath, j.resume FROM jeux j INNER JOIN nominations n ON n.idJeux = j.idJeu WHERE j.isValide = 1 AND n.idCategories = ?");
    $stmt->execute([$cat['idCategorie']]);
    $catJeux[$cat['idCategorie']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<main class="page page--vote">
  <!-- Bandeau d’intro -->
  <section class="vote-hero">
    <div class="container">
      <p class="hero__eyebrow">Vote</p>
      <h1 class="hero__title">Choisis ton jeu de l’année</h1>
      <p class="hero__subtitle">
        Parcours les jeux nominés et sélectionne celui que tu considères comme le meilleur GOTY dans chaque catégorie.
      </p>
    </div>
  </section>
  <!-- Messages de succès ou d'erreur -->
  <?php if (!empty($successMsg)): ?>
    <div class="alert alert--success"><?php echo htmlspecialchars($successMsg); ?></div>
  <?php endif; ?>
  <?php if (!empty($errorMsg)): ?>
    <div class="alert alert--error"><?php echo htmlspecialchars($errorMsg); ?></div>
  <?php endif; ?>

  <!-- Liste des jeux par catégorie -->
  <section class="game-list">
    <div class="container">
      <header class="game-list__header">
        <h2>Jeux en compétition par catégorie</h2>
        <p>Fais ton choix dans chaque catégorie !</p>
      </header>

      <?php foreach ($categories as $cat): ?>
        <div class="category-row">
          <h3 class="category-title"> <?= htmlspecialchars($cat['nomCategorie']) ?> </h3>
          <div class="category-games-scroll">
            <?php if (!empty($catJeux[$cat['idCategorie']])): ?>
              <?php foreach ($catJeux[$cat['idCategorie']] as $jeu): ?>
                <?php
                  $imageSrc = $jeu['imagePath'] ?? '';
                  $gameUrl = 'jeu.php?id=' . (int)$jeu['idJeu'];
                  if ($imageSrc && !preg_match('#^https?://#', $imageSrc)) {
                    $imageSrc = '../' . ltrim($imageSrc, '/');
                  }
                ?>
                <article class="game-card">
                  <a class="game-card__link" href="<?= htmlspecialchars($gameUrl) ?>">
                    <div class="game-card__image-wrapper">
                      <img
                        src="<?= htmlspecialchars($imageSrc) ?>"
                        alt="<?= htmlspecialchars($jeu['titre']) ?>"
                        class="game-card__image"
                      >
                      <span class="game-card__tag"><?= htmlspecialchars($jeu['studio']) ?></span>
                    </div>
                    <div class="game-card__body">
                      <h3 class="game-card__title"><?= htmlspecialchars($jeu['titre']) ?></h3>
                      <p class="game-card__meta"><?= htmlspecialchars($jeu['dateSortie']) ?></p>
                      <p class="game-card__description">
                        <?= htmlspecialchars($jeu['resume']) ?>
                      </p>
                    </div>
                  </a>
                  <!-- Formulaire de vote -->
                  <form method="POST" action="vote.php">
                    <input type="hidden" name="idJeu" value="<?= (int)$jeu['idJeu'] ?>">
                    <?php if (!$canVote): ?>
                      <button class="btn btn--primary game-card__button" type="button" disabled>
                        Vote réservé aux membres
                      </button>
                    <?php elseif ($hasVoted): ?>
                      <button class="btn btn--primary game-card__button" type="button" disabled>
                        Déjà voté
                      </button>
                    <?php else: ?>
                      <button class="btn btn--primary game-card__button" type="submit">
                        Voter pour ce jeu
                      </button>
                    <?php endif; ?>
                  </form>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Aucun jeu dans cette catégorie.</p>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
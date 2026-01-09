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
// Récupérer tous les jeux validés
$sql = "SELECT idJeu, titre, studio, dateSortie, imagePath, resume 
        FROM jeux
        WHERE isValide = 1";
$result = $conn->query($sql);
?>

<main class="page page--vote">

  <!-- Bandeau d’intro -->
  <section class="vote-hero">
    <div class="container">
      <p class="hero__eyebrow">Vote</p>
      <h1 class="hero__title">Choisis ton jeu de l’année</h1>
      <p class="hero__subtitle">
        Parcours les jeux nominés et sélectionne celui que tu considères comme le meilleur GOTY.
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

  <!-- Liste des jeux -->
  <section class="game-list">
    <div class="container">
      <header class="game-list__header">
        <h2>Jeux en compétition</h2>
        <p>Voici la sélection Votendo pour cette édition.</p>
      </header>

        <div class="game-list__grid">
            <?php if ($result && $result->rowCount() > 0): ?>
              <?php while ($jeu = $result->fetch()): ?>
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

                      <?php if ($hasVoted && (int)$alreadyVotedGameId === (int)$jeu['idJeu']): ?>
                        <p class="game-card__badge">Ton vote</p>
                      <?php endif; ?>

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
                  <!-- Désactiver le bouton si l'utilisateur a déjà voté -->
                  <?php elseif ($hasVoted): ?>
                    <button class="btn btn--primary game-card__button" type="button" disabled>
                      Déjà voté
                    </button>
                  <!-- Sinon, afficher le bouton de vote -->  
                  <?php else: ?>
                    <button class="btn btn--primary game-card__button" type="submit">
                      Voter pour ce jeu
                    </button>
                  <?php endif; ?>
                </form>
              </article>
            <?php endwhile; ?>
          <?php else: ?>
            <p>Aucun jeu en compétition pour le moment.</p>
          <?php endif; ?>
        </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
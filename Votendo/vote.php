<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../includes/config.php';

$successMsg = '';
$errorMsg = '';

// Accès réservé aux connectés
if (!isset($_SESSION['tokenVotants'])) {
    header('Location: Compte/login.php');
    exit;
}

// Traitement du vote
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idJeu'])) {
    $idJeu = (int)$_POST['idJeu'];
    $token = $_SESSION['tokenVotants'];

    try {
        // Vérifier que le jeu est valide
        $stmt = $conn->prepare("SELECT COUNT(*) FROM jeux WHERE idJeu = ? AND isValide = 1");
        $stmt->execute([$idJeu]);

        if ((int)$stmt->fetchColumn() === 0) {
            $errorMsg = "Jeu invalide.";
        } else {
            // Insert : si l'utilisateur a déjà voté, ça déclenche l'unicité
            $stmt = $conn->prepare("INSERT INTO votes (tokenVotants, idJeu, `timestamp`) VALUES (?, ?, CURDATE())");
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
// --- Vérifier si l'utilisateur a déjà voté ---
$stmt = $conn->prepare("SELECT idJeu FROM votes WHERE tokenVotants = ? LIMIT 1");
$stmt->execute([$_SESSION['tokenVotants']]);
$alreadyVotedGameId = $stmt->fetchColumn();
$hasVoted = ($alreadyVotedGameId !== false);
?>

<?php include '../includes/header.php'; ?>

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
                  if ($imageSrc && !preg_match('#^https?://#', $imageSrc)) {
                    $imageSrc = '../' . ltrim($imageSrc, '/');
                  }
                ?>
                    <article class="game-card">
                        <div class="game-card__image-wrapper">
                            <img
                                src="<?php echo htmlspecialchars($imageSrc); ?>"
                                alt="<?php echo htmlspecialchars($jeu['titre']); ?>"
                                class="game-card__image"
                            >
                            <span class="game-card__tag">
                                <?php echo htmlspecialchars($jeu['studio']); ?>
                            </span>
                        </div>

                        <div class="game-card__body">
                          <h3 class="game-card__title">
                            <?php echo htmlspecialchars($jeu['titre']); ?>
                          </h3>
                          <!-- Indiquer si c'est le jeu pour lequel l'utilisateur a voté -->
                          <?php if ($hasVoted && (int)$alreadyVotedGameId === (int)$jeu['idJeu']): ?>
                            <p class="game-card__badge">Ton vote</p>
                          <?php endif; ?>

                            <p class="game-card__meta">
                                <?php echo htmlspecialchars($jeu['dateSortie']); ?>
                            </p>

                            <p class="game-card__description">
                                <?php echo htmlspecialchars($jeu['resume']); ?>
                            </p>
                            <!-- Formulaire de vote -->
                            <form method="POST" action="vote.php">
                              <input type="hidden" name="idJeu" value="<?= (int)$jeu['idJeu'] ?>">
                              <!-- Désactiver le bouton si l'utilisateur a déjà voté -->
                              <?php if ($hasVoted): ?>
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
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Aucun jeu en compétition pour le moment.</p>
            <?php endif; ?>
        </div>
      </div>
  </section>

</main>

<?php include '../includes/footer.php'; ?>
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
                    <article class="game-card">
                        <div class="game-card__image-wrapper">
                            <img
                                src="<?php echo htmlspecialchars($jeu['imagePath']); ?>"
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

                            <p class="game-card__meta">
                                <?php echo htmlspecialchars($jeu['dateSortie']); ?>
                            </p>

                            <p class="game-card__description">
                                <?php echo htmlspecialchars($jeu['resume']); ?>
                            </p>

                            <button class="btn btn--primary game-card__button" type="button">
                                Voter pour ce jeu
                            </button>
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
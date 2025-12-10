<?php
// resultats.php : page d’affichage des résultats du vote
// Pour l’instant les données sont en dur dans le code.
// Plus tard, on pourra les récupérer en BDD.

// Liste des jeux avec un nombre de votes simulé.
$games = [
    [
        'title'   => 'Super Mario Odyssey',
        'studio'  => 'Nintendo',
        'votes'   => 120,
    ],
    [
        'title'   => 'Zelda: Tears of the Kingdom',
        'studio'  => 'Nintendo',
        'votes'   => 180,
    ],
    [
        'title'   => 'Mario Kart 8 Deluxe',
        'studio'  => 'Nintendo',
        'votes'   => 90,
    ],
];

// Calcul du total des votes et du nombre de votes maximum (pour le gagnant)
$totalVotes = 0;
$maxVotes   = 0;

foreach ($games as $game) {
    $totalVotes += $game['votes'];
    if ($game['votes'] > $maxVotes) {
        $maxVotes = $game['votes'];
    }
}
?>

<?php include '../includes/header.php'; ?>

<main class="page page--results">

  <!-- Hero / introduction -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">Résultats</p>
      <h1 class="hero__title">Résultats du vote GOTY</h1>
      <p class="hero__subtitle">
        Découvre le classement provisoire des jeux en compétition sur Votendo.
      </p>
    </div>
  </section>

  <!-- Bloc explication -->
  <section class="section section--results">
    <div class="container">
      <h2 class="section__title">Classement de l’édition en cours</h2>
      <p class="section__text">
        Les pourcentages sont calculés sur le nombre total de votes enregistrés
        pour cette édition. Cette page reste purement indicative dans le cadre du MVP&nbsp;1
        (les données ne sont pas encore stockées en base de données).
      </p>

      <!-- Liste des résultats -->
      <ol class="results-list">
        <?php foreach ($games as $game): ?>
          <?php
            // Calcul du pourcentage de votes pour ce jeu
            $percent = $totalVotes > 0
              ? round(($game['votes'] / $totalVotes) * 100)
              : 0;

            // On marque le ou les jeux ayant le maximum de votes
            $isWinner = ($game['votes'] === $maxVotes);
          ?>

          <li class="results-item <?php if ($isWinner) echo 'results-item--winner'; ?>">
            <div class="results-item__header">
              <div>
                <span class="results-item__title">
                  <?= htmlspecialchars($game['title']) ?>
                </span>
                <span class="results-item__studio">
                  • <?= htmlspecialchars($game['studio']) ?>
                </span>
              </div>
              <div class="results-item__votes">
                <?= $game['votes'] ?> vote<?= $game['votes'] > 1 ? 's' : '' ?> • <?= $percent ?>%
              </div>
            </div>

            <div class="results-item__bar">
              <div class="results-item__bar-fill" style="width: <?= $percent ?>%;"></div>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

      <?php if ($totalVotes === 0): ?>
        <p class="section__text">
          Aucun vote n’a encore été enregistré. Reviens plus tard ou
          commence par <a href="vote.php">voter pour ton jeu préféré</a> !
        </p>
      <?php endif; ?>
    </div>
  </section>

</main>

<?php include '../includes/footer.php'; ?>
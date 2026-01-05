<?php
// resultats.php : page d'affichage des résultats du vote

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/config.php'; // doit fournir $conn (PDO)

// 1) Récupérer les jeux validés + nombre de votes réels
$sql = "
    SELECT 
        j.idJeu,
        j.titre AS title,
        j.studio AS studio,
        COUNT(v.idVotes) AS votes
    FROM jeux j
    LEFT JOIN votes v ON v.idJeu = j.idJeu
    WHERE j.isValide = 1
    GROUP BY j.idJeu, j.titre, j.studio
    ORDER BY votes DESC, j.titre ASC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2) Total des votes + maxVotes (pour l'étoile gagnant)
$totalVotes = 0;
$maxVotes = 0;

foreach ($games as $game) {
    $totalVotes += (int)$game['votes'];
    if ((int)$game['votes'] > $maxVotes) {
        $maxVotes = (int)$game['votes'];
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
        Les résultats sont calculés à partir des votes enregistrés sur Votendo.
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
              <div class="results-item__main">
                <span class="results-item__title">
                  <?= htmlspecialchars($game['title']) ?>
                </span>
                <span class="results-item__studio">
                  <?= htmlspecialchars($game['studio']) ?>
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
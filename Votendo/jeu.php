<?php
// jeu.php : Page de détail d'un jeu
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
ensure_session_started();
require_login('Compte/login.php');

// Récupération de l'ID
$idJeu = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idJeu) {
    http_response_code(400);
    die('ID de jeu invalide.');
}

// Récupération des infos du jeu
$stmt = $conn->prepare("
    SELECT idJeu, titre, studio, dateSortie, imagePath, resume, description, videoUrl
    FROM jeux
    WHERE idJeu = ? AND isValide = 1
    LIMIT 1
");
$stmt->execute([$idJeu]);
$jeu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$jeu) {
    http_response_code(404);
    die('Jeu introuvable ou non validé.');
}

// Nettoyage image
$imageSrc = $jeu['imagePath'] ?? '';
if ($imageSrc && !preg_match('#^https?://#', $imageSrc)) {
    $imageSrc = '../' . ltrim($imageSrc, '/');
}

// Fonction pour convertir une URL YouTube en URL d'embed
function youtube_embed_url(?string $url): ?string
{
    if (!$url) return null;

    // Déjà en embed
    if (preg_match('#youtube\.com/embed/([^?]+)#', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }

    // URL classique
    if (preg_match('#v=([^&]+)#', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }

    // URL courte youtu.be
    if (preg_match('#youtu\.be/([^?]+)#', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }

    return null;
}
// Générer l'URL d'embed pour la vidéo
$videoEmbedUrl = youtube_embed_url($jeu['videoUrl'] ?? null);

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page page--game">
  <div class="container">
    <section class="game-detail">

      <!-- En-tête -->
      <header class="game-detail__header">
        <p class="game-detail__eyebrow">
          Candidat : <?= htmlspecialchars($jeu['studio']) ?>
        </p>

        <h1 class="game-detail__title">
          <?= htmlspecialchars($jeu['titre']) ?>
        </h1>

        <?php if (!empty($jeu['resume'])): ?>
          <p class="game-detail__subtitle">
            <?= htmlspecialchars($jeu['resume']) ?>
          </p>
        <?php endif; ?>
      </header>

      <div class="game-detail__layout">

        <!-- Colonne gauche : image + vidéo -->
        <div class="game-detail__media">

          <?php if ($imageSrc): ?>
            <div class="game-detail__image-wrapper">
              <img
                src="<?= htmlspecialchars($imageSrc) ?>"
                alt="Jaquette du jeu <?= htmlspecialchars($jeu['titre']) ?>"
                class="game-detail__image"
              >
            </div>
          <?php endif; ?>

          <div class="game-detail__video">
            <h2 class="game-detail__section-title">Vidéo de présentation</h2>

            <?php if ($videoEmbedUrl): ?>
              <div class="game-detail__video-frame">
                <iframe
                  src="<?= htmlspecialchars($videoEmbedUrl) ?>"
                  title="Vidéo de présentation du jeu"
                  loading="lazy"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                ></iframe>
              </div>
            <?php else: ?>
              <p class="game-detail__video-empty">
                Aucune vidéo disponible pour ce jeu.
              </p>
            <?php endif; ?>
          </div>

        </div>

        <!-- Colonne droite : description -->
        <div class="game-detail__content">
          <h2 class="game-detail__section-title">Description complète</h2>

          <?php if (!empty($jeu['description'])): ?>
            <p class="game-detail__description">
              <?= nl2br(htmlspecialchars($jeu['description'])) ?>
            </p>
          <?php else: ?>
            <p>Aucune description disponible.</p>
          <?php endif; ?>

          <p class="game-detail__meta">
            Date de sortie :
            <?= htmlspecialchars($jeu['dateSortie']) ?>
          </p>

          <a href="vote.php" class="btn btn--primary game-detail__back-btn">
            ← Retour à la page de vote
          </a>
        </div>
      </div>
    </section>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
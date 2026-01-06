<?php
// jeu.php
// Plus tard : récupérer l'id dans l'URL et charger le jeu depuis la BDD
// $id = $_GET['id'] ?? null;

require_once __DIR__ . '/../includes/header.php';
?>

<main class="page page--game">
  <div class="container">
    <section class="game-detail">
      <header class="game-detail__header">
        <p class="game-detail__eyebrow">Candidat : Studio Nintendo</p>
        <h1 class="game-detail__title">Mario Kart 8 Deluxe</h1>
        <p class="game-detail__subtitle">
          Un jeu de course fun et accessible, parfait pour jouer en famille ou entre amis.
        </p>
      </header>

      <div class="game-detail__layout">
        <!-- Colonne gauche : image + vidéo -->
        <div class="game-detail__media">
          <div class="game-detail__image-wrapper">
            <img
              src="../assets/img/jeux/mario-kart-8-deluxe.jpg"
              alt="Jaquette du jeu Mario Kart 8 Deluxe"
              class="game-detail__image"
            >
          </div>

          <div class="game-detail__video">
            <h2 class="game-detail__section-title">Vidéo de présentation</h2>
            <div class="game-detail__video-frame">
              <!-- Remplace l'ID de la vidéo plus tard par celle stockée en BDD -->
              <iframe
                src="https://www.youtube.com/embed/tKlRN2YpxRE"
                title="Vidéo de présentation du jeu"
                loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
              ></iframe>
            </div>
          </div>
        </div>

        <!-- Colonne droite : description -->
        <div class="game-detail__content">
          <h2 class="game-detail__section-title">Description complète</h2>

          <p>
            Plongez dans un monde ouvert vaste et dangereux, où chaque région cache ses secrets,
            ses boss et ses récompenses. Elden Chroniques vous met dans la peau d’un vagabond
            maudit, chargé de rétablir l’équilibre d’un royaume en ruines.
          </p>

          <p>
            Le jeu propose un système de combat technique et exigeant, basé sur le timing,
            l’esquive et la gestion de l’endurance. Chaque arme possède ses propres capacités
            spéciales et peut être améliorée pour s’adapter à votre style de jeu.
          </p>

          <p>
            En parallèle de l’exploration, de nombreuses quêtes secondaires vous permettent de
            découvrir les histoires tragiques des habitants, de faire des choix moraux et
            d’influencer la fin de votre aventure.
          </p>

          <p>
            Ce jeu a été imaginé et développé par le candidat pour le concours GOTY de Votendo,
            afin de proposer une expérience immersive et intense aux joueurs.
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
<?php include 'includes/header.php'; ?>

<main class="page page--vote">

  <!-- Bandeau d’intro -->
  <section class="vote-hero">
    <div class="container">
      <p class="hero__eyebrow">🗳️ Vote</p>
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

        <!-- Carte jeu 1 -->
        <article class="game-card">
          <div class="game-card__image-wrapper">
            <img src="assets/img/jeux/mario-odyssey.jpeg" alt="Super Mario Odyssey" class="game-card__image">
            <span class="game-card__tag">Action / Aventure</span>
          </div>
          <div class="game-card__body">
            <h3 class="game-card__title">Super Mario Odyssey</h3>
            <p class="game-card__meta">Nintendo Switch • 2017</p>
            <p class="game-card__description">
              Un voyage coloré autour du monde avec Mario et Cappy, mélangeant plateforme et exploration.
            </p>
            <button class="btn btn--primary game-card__button" type="button">
              Voter pour ce jeu
            </button>
          </div>
        </article>

        <!-- Carte jeu 2 -->
        <article class="game-card">
          <div class="game-card__image-wrapper">
            <img src="assets/img/jeux/zelda-totk.jpeg" alt="Zelda: Tears of the Kingdom" class="game-card__image">
            <span class="game-card__tag">Action / RPG</span>
          </div>
          <div class="game-card__body">
            <h3 class="game-card__title">Zelda: Tears of the Kingdom</h3>
            <p class="game-card__meta">Nintendo Switch • 2023</p>
            <p class="game-card__description">
              Explore Hyrule et les cieux grâce à une liberté de gameplay incroyable et des pouvoirs uniques.
            </p>
            <button class="btn btn--primary game-card__button" type="button">
              Voter pour ce jeu
            </button>
          </div>
        </article>

        <!-- Carte jeu 3 -->
        <article class="game-card">
          <div class="game-card__image-wrapper">
            <img src="assets/img/jeux/mk8-deluxe.jpeg" alt="Mario Kart 8 Deluxe" class="game-card__image">
            <span class="game-card__tag">Course</span>
          </div>
          <div class="game-card__body">
            <h3 class="game-card__title">Mario Kart 8 Deluxe</h3>
            <p class="game-card__meta">Nintendo Switch • 2017</p>
            <p class="game-card__description">
              Des courses endiablées entre amis, de nouveaux circuits et un mode bataille amélioré.
            </p>
            <button class="btn btn--primary game-card__button" type="button">
              Voter pour ce jeu
            </button>
          </div>
        </article>

        <!-- Pour ajouter d’autres jeux, il suffit de dupliquer ce bloc <article> -->

      </div>
    </div>
  </section>

</main>

<?php include 'includes/footer.php'; ?>
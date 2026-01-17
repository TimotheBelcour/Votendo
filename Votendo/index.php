
<?php require_once __DIR__ . '/../includes/header.php'; ?>

<main class="page page--home">
  <section class="carousel">
    <div class="carousel__container">
      <button class="carousel__arrow carousel__prev" aria-label="Image précédente">&#10094;</button>
      <!-- Les slides seront générés dynamiquement par JS -->
      <button class="carousel__arrow carousel__next" aria-label="Image suivante">&#10095;</button>
    </div>
  </section>

  <section class="hero">
    <div class="container hero__content">
      <h1 class="hero__title">Vote pour le meilleur jeu vidéo de l'année !</h1>
      <p class="hero__subtitle">Un seul vote par joueur — choisis bien !</p>
      <a href="vote.php" class="btn btn--primary">Voter maintenant</a>
    </div>
  </section>

  <section class="intro">
    <div class="container intro__content">
      <h2 class="section-title">Présentation du site</h2>
      <p>
        Votendo est une plateforme de vote en ligne dédiée à l'élection du <strong>jeu vidéo de l'année (GOTY)</strong>.
        Les joueurs peuvent consulter une sélection de jeux, voter pour leur favori et suivre les résultats en direct.
      </p>
      <p>
        Ce site est réalisé dans le cadre d'un projet universitaire en développement web. L'objectif est de proposer une expérience de vote claire, accessible et proche des vrais sites d'awards de jeux vidéo.
      </p>
    </div>
  </section>

  <section class="intro intro--grid">
    <div class="container intro__grid">
      <div class="intro__block">
        <h3>Comment ça marche ?</h3>
        <ol class="intro__steps">
          <li>Parcourir la liste des jeux sur la page <strong>Vote</strong></li>
          <li>Se connecter ou créer un compte</li>
          <li>Choisir son jeu préféré et valider son vote</li>
          <li>Consulter le classement sur la page <strong>Résultats</strong></li>
        </ol>
      </div>
      <div class="intro__block">
        <h3>Mode de scrutin</h3>
        <ul class="intro__list">
          <li><strong>1 compte utilisateur = 1 vote</strong></li>
          <li>Chaque utilisateur choisit un seul jeu parmi la liste proposée</li>
          <li>Le jeu qui obtient le plus de voix est élu GOTY Votendo</li>
        </ul>
        <p>Ce mode de scrutin est simple à comprendre et adapté à un vote en ligne où l'on cherche un <strong>gagnant unique</strong>.</p>
      </div>
    </div>
  </section>

  <section class="site-map">
    <div class="container site-map__content">
      <h2 class="section-title">Organisation du site</h2>
      <ul class="site-map__list">
        <li><strong>Accueil</strong> — Présentation du projet, du contexte et accès rapide au vote.</li>
        <li><strong>Vote</strong> — Liste des jeux nominés avec un bouton pour voter.</li>
        <li><strong>Résultats</strong> — Classement des jeux en fonction du nombre de voix.</li>
        <li><strong>Se connecter</strong> — Page de connexion / espace utilisateur.</li>
        <li><strong>À propos</strong> — Informations sur le projet, l'équipe et les règles de vote.</li>
        <li><strong>Contact</strong> — Formulaire pour poser une question ou signaler un problème.</li>
      </ul>
    </div>
  </section>
</main>

<script src="../assets/js/carousel.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
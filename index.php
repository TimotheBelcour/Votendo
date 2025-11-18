<?php include 'includes/header.php'; ?>

<main class="page page--home">
  <section class="hero">
    <div class="container hero__content">
      <p class="hero__eyebrow">🎮 Votendo Awards 2025</p>
      <h1 class="hero__title">Vote pour le meilleur jeu vidéo de l'année !</h1>
      <p class="hero__subtitle">Un seul vote par joueur — choisis bien</p>
      <a href="vote.php" class="btn btn--primary">Voter maintenant</a>
    </div>
  </section>
  <!-- 1. Présentation du site / contexte --> 
  <section class="intro">
    <div class ="container intro__content">
      <h2 class ="section-title">Présentation du site</h2>
      <p>
        Votendo est une plateforme de vote en ligne dédiée à l'élection du
        <strong>jeu vidéo de l'année (GOTY)</strong>. Les joueurs peuvent consulter
        une sélection de jeux, voter pour leur favori et suivre les résultats.
      </p>
      <p>
        Ce site est réalisé dans le cadre de la <strong>SAE S3 - Développment Web
        (BUT Informatique)</strong>. L'objectif est de proposer une expérience
        de vote claire, accessible et proche des vrais sites d'awards de jeux vidéo.
      </p>
    </div>
  </section>
  <!-- 2. Contexte & mode de scrutin -->
  <section class="intro intro--grid">
    <div class="container intro__grid">
      <div class ="intro__block">
        <h3>Contexte</h3>
        <p>
          Chaque année, de nombreux jeux sortent sur différentes plateformes.
          Votendo permet aux joueurs de la communauté de 
          <strong>mettre en avant leurs coups de coeur</strong> et de 
          comparer leurs avis.
        </p>
    </div>
    <div class="intro__block">
      <h3>Mode de scrutin choisi</h3>
      <p>
        Le site utilise un <strong>scrutin majoritaire à un tour </strong>:
      </p>
      <ul class ="intro__list">
        <li>📌 <strong>1 compte utilisateur = 1 vote</strong></li>
        <li>📌 Chaque utilisateur choisit un seul jeu parmi la liste proposée</li>
        <li>📌 Le jeu qui obtient le plus de voix est élu GOTY Votendo</li>
      </ul>
      <p>
        Ce mode de scrutin est simple à comprendre et adapté à un vote en ligne
        où l'on cherche un <strong>gagnant unique</strong>.
      </p>
    </div>

    <div class="intro__block">
      <h3>Comment ça marche ?</h3>
      <ol class="intro__steps">
        <li>Parcourir la liste des jeux sur la page <strong>Vote</strong></li>
        <li>Se connecter / créer un compte (plus tard dans le projet)</li>
        <li>Choisir son jeu préféré et valider son vote</li>
        <li>Consulter le classement sur la page <strong>Résultats</strong></li>
      </ol>
    </div>
  </div>
  </section>

  <!-- 3. Arborescence du site (vue "fonctionnelle" pour l'utilisateur) -->
  <section class="site-map">
    <div class ="container site-map__content">
      <h2 class="section-title">Organisation du site</h2>

      <ul class="site-map__list">
        <li>
          <strong>Accueil</strong> -Présentation du projet, du contexte et accès rapide au vote.
        </li>
        <li>
          <strong>Vote</strong> - Liste des jeux nominés avec un bouton pour voter.
        </li>
        <li>
          <strong>Résultats</strong> - Classement des jeux en fonction du nombre de voix.
        </li>
        <li>
          <strong>Se connecter</strong> - Page de connexion / futur espace utilisateur.
        </li>
        <li>
          <strong>À propos</strong> - Informations sur le projet, l'équipe et les règles de vote.
        </li>
        <li>
          <strong>Contact</strong> - Formulaire pour poser une question ou signaler un problème.
        </li>
      </ul>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
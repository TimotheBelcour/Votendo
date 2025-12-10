<?php
// apropos.php : page de présentation du projet Votendo
?>

<?php include '../includes/header.php'; ?>

<main class="page page--contact"><!-- on réutilise les marges de la page contact -->

  <!-- Hero / introduction -->
  <section class="hero hero--small">
    <div class="container hero__content">
      <p class="hero__eyebrow">À propos</p>
      <h1 class="hero__title">À propos de Votendo</h1>
      <p class="hero__subtitle">
        Projet réalisé dans le cadre de la SAE S3 – Développement Web (BUT Informatique).
      </p>
    </div>
  </section>

  <div class="container">

    <!-- Section : Le projet -->
    <section class="section">
      <h2 class="section__title">Le projet Votendo</h2>
      <p class="section__text">
        Votendo est une plateforme de vote en ligne permettant de désigner le
        <strong>jeu vidéo de l’année</strong>. Les utilisateurs peuvent consulter une
        sélection de jeux, lire une courte description et voter pour leur favori.
      </p>
      <p class="section__text">
        Le mode de scrutin choisi pour cette première version est un
        <strong>vote simple</strong> : un seul vote par utilisateur et par édition
        (la sécurisation et la gestion avancée des comptes seront traitées dans
        une version ultérieure du projet).
      </p>
    </section>

    <!-- Section : Contexte pédagogique -->
    <section class="section">
      <h2 class="section__title">Contexte pédagogique</h2>
      <p class="section__text">
        Ce site a été développé dans le cadre de la
        <strong>SAE S3 – Développement Web</strong> du BUT Informatique.
        L’objectif est de mettre en pratique les notions vues en cours&nbsp;:
      </p>
      <ul class="section__text">
        <li>Structurer un projet web avec une arborescence claire ;</li>
        <li>Utiliser HTML et CSS pour créer une interface lisible et responsive ;</li>
        <li>Mettre en place des pages dynamiques en <strong>PHP</strong> ;</li>
        <li>Gérer le projet avec <strong>Git &amp; GitHub</strong>.</li>
      </ul>
    </section>

    <!-- Section : Équipe -->
    <section class="section">
      <h2 class="section__title">Équipe du projet</h2>
      <p class="section__text">
        Le développement de Votendo est réalisé par :
      </p>
      <ul class="section__text">
        <li><strong>Timothé Belcour</strong> – BUT Informatique, IUT Saint-Dié-des-Vosges</li>
        <li><strong>Lucas Charbonnel</strong> – BUT Informatique, IUT Saint-Dié-des-Vosges</li>
      </ul>
      <p class="section__text">
        La répartition du travail concerne aussi bien la conception (maquettes,
        arborescence, rédaction du README) que l’intégration front-end et la
        mise en place des pages PHP.
      </p>
    </section>

    <!-- Section : Évolutions prévues -->
    <section class="section">
      <h2 class="section__title">Évolutions prévues (au-delà du MVP1)</h2>
      <p class="section__text">
        Cette version correspond au <strong>MVP1</strong> du projet. Parmi les
        pistes d’amélioration envisagées pour les prochaines itérations :
      </p>
      <ul class="section__text">
        <li>Création de comptes utilisateurs et authentification ;</li>
        <li>Sécurisation et unicité stricte des votes via une base de données ;</li>
        <li>Page de résultats dynamique alimentée par les vrais votes ;</li>
        <li>Interface d’administration pour gérer les jeux nominés et les éditions.</li>
      </ul>
    </section>

  </div>
</main>

<?php include '../includes/footer.php'; ?>
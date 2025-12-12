-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 05 déc. 2025 à 17:04
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `votendo`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `idAdministrateur` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidats`
--

CREATE TABLE `candidats` (
  `idCandidats` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `idCategorie` int(11) NOT NULL,
  `nomCategorie` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jeux`
--

CREATE TABLE `jeux` (
  `idJeu` int(11) NOT NULL,
  `idCandidat` int(11) DEFAULT NULL,
  `titre` varchar(45) NOT NULL,
  `studio` varchar(45) NOT NULL,
  `dateSortie` date NOT NULL,
  `imagePath` varchar(255) DEFAULT NULL,
  `videoUrl` varchar(255) DEFAULT NULL,
  `resume` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `isValide` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nominations`
--

CREATE TABLE `nominations` (
  `idNominations` int(11) NOT NULL,
  `idCategories` int(11) NOT NULL,
  `idJeux` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idutilisateur` int(11) NOT NULL,
  `nomUtilisateur` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `firstLogin` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `votants`
--

CREATE TABLE `votants` (
  `idVotants` int(11) NOT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `tokenVotants` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_candidature`
--

CREATE TABLE `demandes_candidature` (
  `idDemande` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `dateSoumission` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE `votes` (
  `idVotes` int(11) NOT NULL,
  `tokenVotants` varchar(45) NOT NULL,
  `timestamp` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`idAdministrateur`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD PRIMARY KEY (`idCandidats`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD PRIMARY KEY (`idJeu`),
  ADD KEY `fk_jeux_candidats` (`idCandidat`);

--
-- Index pour la table `nominations`
--
ALTER TABLE `nominations`
  ADD PRIMARY KEY (`idNominations`),
  ADD KEY `idCategories` (`idCategories`),
  ADD KEY `idJeux` (`idJeux`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idutilisateur`),
  ADD UNIQUE KEY `uniq_email` (`email`),
  ADD UNIQUE KEY `nomUtilisateur` (`nomUtilisateur`);

--
-- Index pour la table `votants`
--
ALTER TABLE `votants`
  ADD PRIMARY KEY (`idVotants`),
  ADD UNIQUE KEY `tokenVotants` (`tokenVotants`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`idVotes`),
  ADD KEY `tokenVotants` (`tokenVotants`);

--
-- Index pour la table `demandes_candidature`
--
ALTER TABLE `demandes_candidature`
  ADD PRIMARY KEY (`idDemande`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `idAdministrateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `candidats`
--
ALTER TABLE `candidats`
  MODIFY `idCandidats` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idCategorie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jeux`
--
ALTER TABLE `jeux`
  MODIFY `idJeu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `nominations`
--
ALTER TABLE `nominations`
  MODIFY `idNominations` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demandes_candidature`
--
ALTER TABLE `demandes_candidature`
  MODIFY `idDemande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idutilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `votants`
--
ALTER TABLE `votants`
  MODIFY `idVotants` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `votes`
--
ALTER TABLE `votes`
  MODIFY `idVotes` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idutilisateur`);

--
-- Contraintes pour la table `candidats`
--
ALTER TABLE `candidats`
  ADD CONSTRAINT `candidats_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idutilisateur`);

--
-- Contraintes pour la table `jeux`
--
ALTER TABLE `jeux`
  ADD CONSTRAINT `fk_jeux_candidats` FOREIGN KEY (`idCandidat`) REFERENCES `candidats` (`idCandidats`);

--
-- Contraintes pour la table `nominations`
--
ALTER TABLE `nominations`
  ADD CONSTRAINT `nominations_ibfk_1` FOREIGN KEY (`idCategories`) REFERENCES `categorie` (`idCategorie`),
  ADD CONSTRAINT `nominations_ibfk_2` FOREIGN KEY (`idJeux`) REFERENCES `jeux` (`idJeu`);

--
-- Contraintes pour la table `votants`
--
ALTER TABLE `votants`
  ADD CONSTRAINT `votants_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idutilisateur`);

--
-- Contraintes pour la table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`tokenVotants`) REFERENCES `votants` (`tokenVotants`);
COMMIT;

-- Ajouter la colonne firstLogin si elle n'existe pas
ALTER TABLE utilisateur ADD COLUMN firstLogin TINYINT(1) NOT NULL DEFAULT 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

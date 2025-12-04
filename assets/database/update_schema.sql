-- =========================================================
-- Script de mise à jour du schéma pour la base 'votendo'
-- À exécuter APRÈS l'import du dump initial votendo.sql
-- =========================================================


-- ==========================================
-- 1. Amélioration de la table 'utilisateur'
-- ==========================================
-- - Agrandir le champ passwordHash pour accepter
--   les hash générés par password_hash() (≈ 60 caractères)
-- - Rendre l'email unique pour éviter les doublons de comptes

ALTER TABLE utilisateur
    MODIFY passwordHash VARCHAR(255) NOT NULL,
    ADD UNIQUE KEY uniq_email (email);



-- ==================================
-- 2. Enrichissement de la table 'jeux'
-- ==================================
-- Objectif : stocker toutes les infos nécessaires
-- pour la fiche jeu (page jeu.php) + savoir
-- quel candidat a proposé le jeu et s'il est validé.

ALTER TABLE jeux
    -- Lien vers le candidat qui a soumis ce jeu
    ADD idCandidat INT(11) NULL AFTER idJeu,
    -- Chemin de l'image (jaquette)
    ADD imagePath VARCHAR(255) NULL AFTER dateSortie,
    -- URL de la vidéo (YouTube, etc.)
    ADD videoUrl VARCHAR(255) NULL AFTER imagePath,
    -- Résumé court (pour la carte dans vote.php)
    ADD resume TEXT NULL AFTER videoUrl,
    -- Description longue (pour jeu.php)
    ADD description TEXT NULL AFTER resume,
    -- Statut de validation par l'administrateur
    -- 0 = en attente / refusé, 1 = validé (affiché pour le vote)
    ADD isValide TINYINT(1) NOT NULL DEFAULT 0 AFTER description;

-- Ajout de la contrainte de clé étrangère :
-- chaque jeu peut être rattaché à un candidat
ALTER TABLE jeux
    ADD CONSTRAINT fk_jeux_candidats
        FOREIGN KEY (idCandidat) REFERENCES candidats(idCandidats);



-- ===================================
-- 3. Amélioration de la table 'votes'
-- ===================================
-- Objectif :
-- - savoir pour QUEL jeu l'utilisateur a voté
-- - conserver date + heure du vote
-- - garantir 1 seul vote par token pour le GOTY

ALTER TABLE votes
    -- Id du jeu pour lequel le vote est exprimé
    ADD idJeu INT(11) NOT NULL AFTER tokenVotants,
    -- Date et heure précise du vote
    MODIFY `timestamp` DATETIME NOT NULL,

-- Lien vers la table 'jeux' :
-- chaque vote concerne un jeu précis
ALTER TABLE votes
    ADD CONSTRAINT fk_votes_jeux
        FOREIGN KEY (idJeu) REFERENCES jeux(idJeu);

-- Unicité : un token ne peut apparaître qu'une seule
-- fois dans la table 'votes' → 1 vote max par votant
ALTER TABLE votes
    ADD UNIQUE KEY uniq_vote_token (tokenVotants);
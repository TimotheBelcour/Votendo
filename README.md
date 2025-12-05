![GitHub repo size](https://img.shields.io/github/repo-size/TimotheBelcour/Votendo)
![GitHub last commit](https://img.shields.io/github/last-commit/TimotheBelcour/Votendo)
![PHP](https://img.shields.io/badge/PHP-8.2+-violet?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue?logo=mysql)
![Status](https://img.shields.io/badge/Status-MVP_in_progress-yellow)
![Platform](https://img.shields.io/badge/Target-GOTY_2025-red)

<p align="center">
  <img src="assets/img/Votendo.svg" width="120">
</p>

# 🕹️ Votendo – Plateforme de vote GOTY  
Projet réalisé dans le cadre de la **SAE S3 – Développement Web (BUT Informatique)**.  
L’objectif est de concevoir une plateforme où les utilisateurs peuvent voter pour le meilleur jeu vidéo de l'année.

---

## 📁 Sommaire
- [🎯 Objectif du projet](#-objectif-du-projet)
- [📁 Structure du projet](#-structure-du-projet-mvp)
- [🛠️ Technologies utilisées](#️-technologies-utilisées)
- [📌 État actuel](#-état-actuel)
- [🎮 Fonctionnalités (MVP1)](#-fonctionnalités-mvp1)
- [🔐 Fonctionnalités administrateur](#-fonctionnalités-administrateur)
- [🗄️ Base de données](#️-base-de-données)
- [🧭 Roadmap MVP](#-roadmap-mvp)
- [🚀 Installation & lancement](#-installation--lancement)
- [🖼️ Captures d’écran du site](#️-captures-décran-du-site)
- [🖼️ Capture d'écran de la maquette Figma](#-capture-décran-de-la-maquette-figma)
- [👤 Auteur](#-auteur)

---

## 🎯 Objectif du projet

Créer un site web permettant aux utilisateurs de :

- Consulter une liste de jeux  
- Voter pour leur jeu préféré  
- Proposer un jeu via un formulaire complet  
- Voir les résultats  
- Se connecter et gérer un compte  
- Accéder à un espace administrateur pour valider ou refuser les jeux proposés  

---

## 📁 Structure du projet (MVP)
``` 
/assets
├── css/style.css
├── js/main.js
├── img/
│    ├── Votendo.svg
│    ├── jeux/
│    └── screenshots/

/includes
├── config.php
├── header.php
└── footer.php

/jeu
├── admin_jeux.php
├── admin_valider.php
├── admin_refuser.php

index.php
vote.php
resultats.php
proposer_jeu.php
login.php
logout.php
inscription.php
apropos.php
contact.php
``` 
---

## 🛠️ Technologies utilisées

### **Frontend**
- HTML
- CSS
- JavaScript

### **Backend**
- PHP (sessions, includes, pages dynamiques)  
- MySQL (structure relationnelle + relations utilisateurs/jeux)  

### **Outils**
- MySQL Workbench  
- Git & GitHub  
- Terminal (serveur PHP local)  

---

## 📌 État actuel

- ✔️ Arborescence terminée  
- ✔️ Intégration Figma → HTML/CSS  
- ✔️ Header & Footer dynamiques avec include  
- ✔️ Page Vote dynamique connectée à MySQL  
- ✔️ Espace Administrateur fonctionnel  
- ✔️ Validation / Suppression de jeux  
- ✔️ Proposition de jeux avec insertion en BDD  
- ✔️ Système de sessions / login / inscription  
- ⏳ Page résultats (vote réel non encore activé)  
- ⏳ Sécurisation complète (CSRF, limiter 1 vote réel…)  

---

## ✨ Fonctionnalités (MVP1)

### 🔍 Consultation & navigation
- Affichage dynamique des jeux validés (BDD → PHP → HTML)
- Cartes responsive
- Mise en forme automatique (image, studio, résumé, date)

### 🗳️ Vote
- Bouton de vote (composant visuel)
- Préparation du système de vote (BDD prête)

### 📝 Création de compte
- Inscription avec `password_hash`
- Connexion + gestion des sessions
- Affichage du nom dans le header

### ➕ Proposer un jeu  
Formulaire complet qui enregistre un jeu comme *en attente d'approbation* (`isValide = 0`).

---

## 🔐 Fonctionnalités administrateur

Accessible uniquement si l'utilisateur est dans la table `administrateur`.

### Admin peut :
- Voir les jeux proposés *non validés*
- Valider un jeu → il apparaît sur la page Vote
- Refuser un jeu → suppression en BDD
- Accéder à une page dédiée : `/jeu/admin_jeux.php`

### Sécurisation :
- Vérification via session  
- Double vérification via requête SQL  
- Redirection ou message d’erreur si accès interdit  

---

## 🗄️ Base de données

### Table `utilisateur`
idUtilisateur (PK)
nomUtilisateur
email
passwordHash

### Table `jeux`
idJeu (PK)
idCandidat (FK → utilisateur)
titre
studio
dateSortie
imagePath
videoUrl
resume
description
isValide (0 = en attente, 1 = validé)

### Table `administrateur`
idAdministrateur (PK)
idUtilisateur (FK)

---

## 🧭 Roadmap MVP

### ✔️ Déjà réalisé
- Structure du projet  
- Pages principales  
- Header/Footer dynamiques  
- Page de vote dynamique  
- Espace admin fonctionnel  
- Système de connexion / inscription  
- Proposition de jeux avec gestion admin  

### 🔜 À venir
- Page détail d’un jeu  
- Système de vote finalisé (1 vote / utilisateur)  
- Page résultats avec classement  
- Upload d’images via formulaire  
- Sécurisation CSRF + input validation  
- Messages flash (succès / erreur)  

---

## 🚀 Installation & lancement

### 1️⃣ Cloner le dépôt
```bash
git clone https://github.com/TimotheBelcour/Votendo.git
```

### 2 Lancer un serveur PHP local
php -S localhost:8000

---

## 🖼️ Captures d’écran du site

### 🏠 Page d’accueil

<img width="1280" height="721" alt="image" src="https://github.com/user-attachments/assets/eb8820a1-4b9d-4590-9ef1-b26c4e2c8082" />

### 🗳️ Page de vote

<img width="1280" height="714" alt="image" src="https://github.com/user-attachments/assets/95de408d-2f3b-4d25-992a-f9da6d6ab626" />

### 📊 Page résultats

<img width="1280" height="716" alt="image" src="https://github.com/user-attachments/assets/7da3f4c7-7730-4596-a30f-2d257b5e97b5" />

### 🔐 Page connexion

<img width="1280" height="721" alt="image" src="https://github.com/user-attachments/assets/1cef1e43-3602-4a0f-9baa-44647a1fcb06" />

### 📝 Page inscription

<img width="1280" height="721" alt="image" src="https://github.com/user-attachments/assets/76d46140-9c40-445e-b92d-037f4c8c2855" />

### 🎮 Proposer un jeu

<img width="1280" height="713" alt="image" src="https://github.com/user-attachments/assets/86b6ff6a-eab3-45b2-8bf3-7cbc3b1701fe" />

### 🛡️ Espace administrateur

<img width="1280" height="719" alt="image" src="https://github.com/user-attachments/assets/68cbdaf5-df60-4d00-8a34-53fe58350669" />

---

## 🖼️ Capture d’écran de la maquette Figma

<img width="319" height="570" alt="image" src="https://github.com/user-attachments/assets/1ed45557-3ba3-43be-aa8a-69e92966fab5" />

---

## 👤 Auteur

Timothé Belcour
Étudiant en BUT Informatique – IUT Saint-Dié-des-Vosges

---

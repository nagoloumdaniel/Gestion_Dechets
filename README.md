# EcoPro — Gestion des Déchets

![PHP](https://skillicons.dev/icons?i=php) ![MySQL](https://skillicons.dev/icons?i=mysql) ![JavaScript](https://skillicons.dev/icons?i=js) ![CSS](https://skillicons.dev/icons?i=css) ![Bootstrap](https://skillicons.dev/icons?i=bootstrap)

Plateforme de signalement et de suivi de collecte des déchets, avec localisation des dépôts sur carte.

## Description

Projet réalisé dans le cadre d'une formation, visant à digitaliser le signalement de déchets sauvages par les habitants et leur prise en charge par une équipe de collecte. L'application est développée en PHP procédural avec une base de données MySQL, sans framework.

## Fonctionnalités

- **Signalement public** : tout visiteur peut signaler un déchet via un formulaire (catégorie, description, photo, géolocalisation GPS du navigateur, numéro de téléphone), sans création de compte.
- **Espace administrateur** (authentifié) : liste des déchets signalés, visualisation de leur position sur une carte (Google Maps intégré en iframe), et attribution de chaque signalement à un agent de collecte disponible.
- **Espace agent de collecte** (authentifié) : liste des tâches attribuées, visualisation de la localisation sur la carte, et validation de la collecte avec envoi d'une photo de preuve.
- Gestion des agents et des utilisateurs côté administration (`Admin/Agents.php`, `Admin/Users.php`, `Admin/Recupere.php`).

## Stack technique

- **Backend** : PHP (extension `mysqli`)
- **Base de données** : MySQL
- **Frontend** : HTML, CSS, JavaScript, Bootstrap
- **Cartographie** : affichage Google Maps en iframe (`google.com/maps?q=...&output=embed`) — ne nécessite pas de clé API

## Installation locale

### Prérequis
- Serveur PHP (ex. WAMP, XAMPP ou `php -S`)
- MySQL

### Étapes

1. Cloner le dépôt :
   ```bash
   git clone https://github.com/nagoloumdaniel/Gestion_Dechets.git
   cd Gestion_Dechets
   ```

2. Importer le schéma de base de données :
   ```bash
   mysql -u root -p < database/schema.sql
   ```
   Ce script crée la base `EcoPro` et ses tables (`usager`, `administrateur`, `dechets`, `signaler`, `agent_collecte`, `recuperer`).

3. Configurer la connexion à la base dans `config/db.php` (hôte, utilisateur, mot de passe) si différente des valeurs par défaut (`127.0.0.1` / `root` / sans mot de passe).

4. Lancer le serveur PHP :
   ```bash
   php -S localhost:8000
   ```
   puis ouvrir `http://localhost:8000/index.html`.

5. Créer manuellement un compte administrateur directement en base (table `administrateur`) pour accéder à `Admin/index.php` — les mots de passe sont vérifiés avec `password_verify()`, donc la colonne `mot_de_passe` doit contenir un hash `password_hash()`, pas une valeur en clair :
   ```php
   php -r "echo password_hash('votre_mdp', PASSWORD_DEFAULT), PHP_EOL;"
   ```
   Les comptes agent de collecte (table `agent_collecte`) se créent ensuite depuis l'espace admin (`Admin/Agents.php`), qui hache déjà le mot de passe à la création.

Aucune démonstration publique n'est disponible : le projet nécessite une base MySQL locale.

## Auteur

**Daniel Nagoloum Talla**
[GitHub](https://github.com/nagoloumdaniel) · [Portfolio](https://nagoloum.vercel.app)

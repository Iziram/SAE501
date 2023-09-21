# Projet SAE 501 IUT LANNION <!-- omit in toc -->

> Par Matthias HARTMANN / Iziram
> Groupe 3B1 - IUT Lannion R&T

## Sommaire <!-- omit in toc -->

1. [Avancement](#avancement)
   1. [21/09/2023](#21092023)
2. [Consignes](#consignes)
   1. [Etape 1](#etape-1)
   2. [Etape 2](#etape-2)
   3. [Etape 3](#etape-3)
   4. [Etape 4](#etape-4)
   5. [Etape 5A](#etape-5a)
   6. [Etape 5B](#etape-5b)

## Avancement

### 21/09/2023

> branche : [main](https://github.com/Iziram/SAE501/tree/main)

- Mise en place du repository git
- Vérificatif du site web et correction.
- Mise en place du conteneur `Etape 1`
- Vérification du bon fonctionnement
- Création d'un script d'installation
- Guide d'installation `Etape 1` [GuideInstallation](https://github.com/Iziram/SAE501/blob/main/GuideInstallation.md)
- Documentation du script d'installation

## Consignes

### Etape 1

> les fichiers liés à l'étape 1 se trouve sur la branche [main](https://github.com/Iziram/SAE501/tree/main) de ce git.

1. Reprenez et corrigez votre application afin de la rendre opérationnelle dans la VM.
2. Créez une seconde base de données pour les utilisateurs pouvant se connecter
   avec les droits (admin ou simple utilisateur). Il faut une BDD de données et une BDD pour
   l'authentification.
3. Utilisez GitLab pour piloter le dépôt de vos codes sources.
4. Déployez l'application avec Docker en utilisant un Dockerfile.

### Etape 2

> les fichiers liés à l'étape 1 se trouve sur la branche [etape_2](https://github.com/Iziram/SAE501/tree/etape_2) de ce git.

1. Modifiez la version 1 en remplaçant SQLite par PostgreSQL pour la BDD de données et MariaDB pour la
   BDD authentification.
2. Utilisez GitLab pour piloter le dépôt de vos codes sources.
3. Déployez l'application avec Docker en utilisant un Dockerfile pour PHP, un autre pour PostgreSQL et un dernier pour MariaDB

### Etape 3

> les fichiers liés à l'étape 1 se trouve sur la branche [etape_3](https://github.com/Iziram/SAE501/tree/etape_3) de ce git.

1. Créez une API Python avec FastAPi qui permet de réaliser les opérations CRUD sur au moins une table de votre base de données.
2. Créez une API Python avec FastAPi pour gérer l'authentification simple (sans jeton)
3. Modifiez votre programme PHP afin de réaliser l'interaction avec les BDD via vos API en utilisant CURL.
4. Utilisez GitLab pour piloter le dépôt de vos codes sources.
5. Déployez l'application avec Docker en utilisant Docker Compose.

### Etape 4

> les fichiers liés à l'étape 1 se trouve sur la branche [etape_3](https://github.com/Iziram/SAE501/tree/etape_4) de ce git.

1. Modifier l'API Python avec FastAPi qui permet l'authentification et la délivrance de Jeton JWT
2. Modifier votre programme PHP pour affiner les sessions déjà mise en place en fonction du jeton JWT
3. Utiliser GitLab pour piloter le dépot de vos codes sources
4. Déployez l'application avec Docker en utilisant Docker Compose

### Etape 5A

> les fichiers liés à l'étape 1 se trouve sur la branche [etape_5A](https://github.com/Iziram/SAE501/tree/etape_5A) de ce git.

1. Modifiez votre programme PHP afin d'utiliser l'approche POO et le modèle MVC.
2. Utilisez GitLab pour piloter le dépôt de vos codes sources.
3. Déployez l'application avec Docker en utilisant Docker Compose.

### Etape 5B

> les fichiers liés à l'étape 1 se trouve sur la branche [etape_5B](https://github.com/Iziram/SAE501/tree/etape_5B) de ce git.

1. Suivez le tutoriel fourni pour découvrir la programmation WEB en Python avec Flask.
2. Faites évoluer votre application en remplaçant les codes PHP par un codage en Python avec Flask.
3. Utilisez GitLab pour piloter le dépôt de vos codes sources.
4. Déployez l'application avec Docker en utilisant Docker Compose.

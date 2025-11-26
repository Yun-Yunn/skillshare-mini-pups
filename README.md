# SkillShare Mini – Projet Symfony

Ce projet est une application Symfony permettant aux utilisateurs de :
- créer un compte
- gérer leurs compétences offertes et recherchées
- consulter les profils des autres utilisateurs

## Installation (Docker)

1. Cloner le dépôt :
   git clone https://github.com/Yun-Yunn/skillshare-mini-pups.git

2. Copier le fichier d'environnement :
   cp .env .env.local

3. Lancer les conteneurs Docker :
   docker compose up -d

4. Accéder à l’application :
   http://localhost:8111

## Base de données

Le conteneur MySQL est automatiquement créé et configuré.  
Si nécessaire, exécuter les migrations :

   php bin/console doctrine:migrations:migrate

## Identifiants par défaut

Aucun utilisateur n'est créé par défaut.  
Créer un compte directement via l'interface.


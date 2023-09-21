# Guide d'installation Etape 1 <!-- omit in toc -->

Le but de ce guide est de vous permettre d'installer rapidement et simplement l'étape 1 du projet de SAE 501

## Sommaire <!-- omit in toc -->

1. [Récupération du code](#r%C3%A9cup%C3%A9ration-du-code)
2. [Installation du containers](#installation-du-containers)
   1. [Fonctionnement du script](#fonctionnement-du-script)
   2. [Fonctionnement du DockerFile](#fonctionnement-du-dockerfile)

## Récupération du code

Pour récupérer le code vous pouvez directement faire un clone du git dans le dossier de votre choix :

```bash
git clone https://github.com/Iziram/SAE501.git
```

Ou bien télécharger le projet au format zip et le deziper.

## Installation du containers

Une fois le projet télécharger, rendez vous dans le dossier SAE501 :

```bash
cd SAE501
```

Puis faites

```bash
bash install_containers.sh
```

Par défaut un conteneur `501_etape_1` sera créé puis lancé.
Vous pourrez donc accéder au site avec l'adresse suivante : `http://localhost`.

Le script `install_containers.sh` accepte 2 options :

1. `-t` : Permet de directement se connecter au conteneur en bash après sa création.
2. `-p PORT` : Permet de choisir le port de votre machine qui sera dédiée au conteneur. (`-p 8080` => `http://localhost:8080`)

### Fonctionnement du script

```bash

# Fonction de création du conteneur
function buildContainer (){

    # On récupère le port qui sera utilisé
    port=$2
    # On récupère le booleen qui détermine si on lie le terminal ou non
    terminal=$1

    # On construit l'image du conteneur à partir du dockerfile (situé à la racine du repo)
    docker build -f ./dockerfile -t 501_etape_1 .

    # Une fois l'image créée on lance le conteneur avec l'image et sur le bon port
    docker run -dit --name 501_etape_1 -p $port:80 501_etape_1

    if $terminal
    then
        # On lie le terminal au conteneur.
        docker exec -it 501_etape_1 bash;
    fi
}

# Comportement par défaut
t=false # on ne lie pas le terminal au conteneur
p=80 # on exporte le site sur le port 80 de ma machine hôte

# Vérification des options
while getopts tp: flag
do
    case "${flag}" in
        t) t=true ;; # On lie le terminal au conteneur
        p) p=$OPTARG ;; # On change le port qui sera utilisé sur la machine hôte
    esac
done

# On construit puis on lance le conteneur
buildContainer $t $p
```

### Fonctionnement du DockerFile

```dockerfile
# Récupération d'une image avec php 8.2 et apache2
FROM php:8.2-apache
# Copie des fichier du site dans le répertoire du conteneur.
COPY --chown=www-data:www-data ./site /var/www/html/

# Installation de GD pour le captcha
RUN apt update && apt install -y zlib1g-dev libpng-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install gd
```

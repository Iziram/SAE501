# Récupération d'une image avec php 8.2 et apache2
FROM php:8.2-apache
# Copie des fichier du site dans le répertoire du conteneur.
COPY --chown=www-data:www-data ./site /var/www/html/

# Installation de GD pour le captcha 
RUN apt update && apt install -y zlib1g-dev libpng-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install gd
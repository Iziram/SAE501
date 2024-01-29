#Récupération de l'image de base (MariaDB)
FROM mariadb:latest
#Configuration des variables d'environnement
ENV MARIADB_ROOT_PASSWORD=1234
ENV MARIADB_DATABASE="jawelry"
ENV MARIADB_USER="iziram"
ENV MARIADB_PASSWORD=1234
# Copie des fichiers SQL dans le dossier d'initialisation de la base de données
COPY sql/auth/ /docker-entrypoint-initdb.d/
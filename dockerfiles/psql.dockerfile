#Récupération de l'image de base (PostgreSQL)
FROM postgres:latest
#Configuration des variables d'environnement
ENV POSTGRES_USER="iziram"
ENV POSTGRES_PASSWORD=1234
ENV POSTGRES_DB="jawelry"
# Copie des fichiers SQL dans le dossier d'initialisation de la base de données
COPY sql/produits/ /docker-entrypoint-initdb.d/
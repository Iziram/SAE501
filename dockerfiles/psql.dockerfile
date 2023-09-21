FROM postgres:latest
ENV POSTGRES_USER="iziram"
ENV POSTGRES_PASSWORD=1234
ENV POSTGRES_DB="jawelry"
COPY sql/produits/ /docker-entrypoint-initdb.d/
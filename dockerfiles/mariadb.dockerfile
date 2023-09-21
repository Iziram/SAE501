FROM mariadb:latest
ENV MARIADB_ROOT_PASSWORD=1234
ENV MARIADB_DATABASE="jawelry"
ENV MARIADB_USER="iziram"
ENV MARIADB_PASSWORD=1234

COPY sql/auth/ /docker-entrypoint-initdb.d/
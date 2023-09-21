function buildNetwork(){
    # Création d'un réseau pour y placer les conteneurs
    docker network create --driver bridge 501_etape_2_network || echo "Réseau déjà installé"
}

# Fonction de création du conteneur du site web
function buildSiteContainer (){

    # On récupère le port qui sera utilisé
    port=$2
    # On récupère le booleen qui détermine si on lie le terminal ou non
    terminal=$1

    # On construit l'image du conteneur à partir du dockerfile (situé à la racine du repo)
    docker build -f ./dockerfiles/site.dockerfile -t 501_etape_2_site .

    # Une fois l'image créée on lance le conteneur avec l'image et sur le bon port
    docker run -dit --name 501_etape_2_site -p $port:80 --network 501_etape_2_network 501_etape_2_site

    if $terminal
    then
        # On lie le terminal au conteneur.
        docker exec -it 501_etape_2_site bash;
    fi
}

# Fonction de création du conteneur de PostgreSQL
function buildPSQLContainer (){

    # On récupère le port qui sera utilisé
    port=$1

    # On construit l'image du conteneur à partir du dockerfile (situé à la racine du repo)
    docker build -f ./dockerfiles/psql.dockerfile -t 501_etape_2_psql .

    # Une fois l'image créée on lance le conteneur avec l'image et sur le bon port
    docker run -tdi --name 501_etape_2_psql -p $port:5432 --network 501_etape_2_network 501_etape_2_psql
}

# Fonction de création du conteneur de MariaDB
function buildMariaDBContainer (){

    # On récupère le port qui sera utilisé
    port=$1

    # On construit l'image du conteneur à partir du dockerfile (situé à la racine du repo)
    docker build -f ./dockerfiles/mariadb.dockerfile -t 501_etape_2_mariadb .

    # Une fois l'image créée on lance le conteneur avec l'image et sur le bon port
    docker run -tdi --name 501_etape_2_mariadb -p $port:3306 --network 501_etape_2_network 501_etape_2_mariadb 
}

function buildAll(){
    buildNetwork
    buildMariaDBContainer $1
    buildPSQLContainer $2
    buildSiteContainer $3 $4
}

# Comportement par défaut container Site
t=false # on ne lie pas le terminal au conteneur
p=80 # on exporte le site sur le port 80 de ma machine hôte

# Port par défaut Postgresql
ps=5432
# Port par défaut MariaDB
pm=3306

# Vérification des options
while getopts tp:m:s: flag
do
    case "${flag}" in
        t) t=true ;; # On lie le terminal au conteneur
        p) p=$OPTARG ;; # Port http de la machine hôte
        m) pm=$OPTARG ;; # Port MariaDB de la machine hôte
        s) ps=$OPTARG ;; # Port Postgres de la machine hôte
    esac
done

PS3="Que faire ? :"

items=("Installer réseau" "Installer Site" "Installer PSQL" "Installer MariaDB", "Tout installer")

while true; do
    select item in "${items[@]}" Quitter
    do
        case $REPLY in
            1) buildNetwork ; break;;
            2) buildSiteContainer $t $p ; break;;
            3) buildPSQLContainer $ps ; break;;
            4) buildMariaDBContainer $ps ; break;;
            5) buildAll $pm $ps $t $p; break;;
            $((${#items[@]}+1))) echo "Fin du script" ; break 2;;
            *) echo "Choix inconnu $REPLY"; break;
        esac
    done
done
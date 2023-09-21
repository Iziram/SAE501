
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
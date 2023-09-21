

function buildContainer (){
    port=$2
    terminal=$1
    docker build -f ./dockerfile -t 501_etape_1 .
    docker run -dit --name 501_etape_1 -p $port:80 501_etape_1

    if $terminal
    then
        docker exec -it 501_etape_1 bash;
    fi
}

t=false
p=80

while getopts tp: flag
do
    case "${flag}" in
        t) t=true ;;
        p) p=$OPTARG ;;
    esac
done

buildContainer $t $p
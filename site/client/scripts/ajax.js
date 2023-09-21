/** 
 * Cette fonction ajax a été programmée par HARTMANN Matthias dans le cadre du projet Web2
 * Son but est de simplifer les échanges client <-> serveur à l'aide d'AJAX
 * Ici notre client enverras des requetes avec du json et receverra un objet json en réponse.
 * l'objet json de réponse est formé de la façon suivante : 
 * {
 *  "type" : string,
 *  "content" : object
 * }
 * 
 * Par défaut une réponse renvera un type => "Error"
 * 
 * Pour plus d'informations sur la fonction fetch : 
 * https://developer.mozilla.org/fr/docs/Web/API/Fetch_API/Using_Fetch
 * https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Global_Objects/Promise
 * 
 */
/**
 * 
 * @param {*} params Un objet représentant ce qui va être envoyé au serveur (ici un json mais un FormData peut être utilisé pour du post)
 * @param {*} success Une fonction (annonyme ou non) qui sera executée si la réponse du serveur est valide
 * @param {*} error Une fonction (annonyme ou non) qui sera exécutée si la réponse du serveur est invalide
 * @param {*} url Une chaine de caractère représentant l'url du fichier serveur à contacter 
 * @param {*} method Une chaine de caractère représentant la méthode (Get ou Post) à utiliser
 * @param {*} contentType Une chaine de caractère représentant le content type des paramètres que l'on envoie (ici application/json)
 */
 function ajax(
    params = {
        type: "error"
    },
    success, 
    error, 
    url = 'server/scripts/ajax.php', 
    method = 'post', 
    contentType = 'application/json') {

    if (window.fetch) {
        //On créé un entête qui informera le navigateur des paramètres à utiliser pour faire la requète au serveur
        let header = {
            method: method,
            body: JSON.stringify(params),
            headers: {
                'Content-Type': contentType
            }
        };
        //On utilise la méthode fetch ou window.fetch pour envoyer la requête. Cette methode renvoie une Promise de réponse
        // Pour utiliser cette Promise il faut utiliser les methodes associées "then()" et "catch()"
        
        //Dans notre cas on passe dans un premier then la réponse en reponse json.
        //Puis on vérifie dans l'objet json si la clé "type" est égal à une erreur puis on exécute les fonctions associées.
        fetch(url,
                header
            ).then(ans => ans.json())
            .then(ans => {
                if (ans.type != "Error") {
                    success(ans);
                } else {
                    error(ans);
                }
            }).catch(err => {
                //Si la requète ne peut pas s'exécuter.
                console.log(err);
            })

    } else {
        //Même chose avec XMLHttpRequest si le navigateur ne peux pas utiliser fetch
        let req = null;
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (typeof ActiveXObject != "undefined") {
            //Pour le cas d'internet explorer
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            alert("votre navigateur ne supporte pas l'ajax");
            return;
        }

        if (req) {
            req.onreadystatechange = function() {
                //Fonction annonyme qui sera executée à chaque changement de state
                let ready = req.readyState;
                if (ready == 4) {
                    //Si le code est à 4 cela veut dire que la réponse à la requête a été reçue.
                    const status = req.status;
                    //on vérifie que la requête à une réponse correcte (code 200)
                    if (status == 200) {
                        const json = JSON.parse(req.responseText);
                        if(json.type == "Error"){
                            error(json);
                        }else{
                            success(json);
                        }
                    }
                }
            }

            //On prépare la requête avec la method et l'url 
            req.open(method, url, true);

            //Si la method est get alors on envoit la requête sans paramètres. 
            if (method == "get")
                req.send(null);
            else{
                //Sinon on set le content type de la requête 
                req.setRequestHeader("Content-Type", contentType);
                //Et on envoie la requête avec les paramètres.

                /**
                 * Dans le cadre d'une requête post, on doit envoyer un objet FormData
                 * 
                 * const paramPost = new FormData();
		         * paramPost.append('clé',valeur);
		         * paramPost.append('clé2',valeur2);
                 * req.send(paramPost);
                 */
                req.send(JSON.stringify(params));
            }

        }
    }


}
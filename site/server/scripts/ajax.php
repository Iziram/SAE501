<?php if (empty(session_id())) session_start(); ?>
<?php

/**
 * Le but de ce fichier est de fournir les réponses aux requêtes AJAX (réponses sous le format JSON)
 */

//On récupère le content type de la requête.
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
//On inclue les fonctions liées à la base de donnée
include $_SERVER['DOCUMENT_ROOT'] . '/server/scripts/api.php';
include $_SERVER['DOCUMENT_ROOT'] . '/server/scripts/bdd.php';

//Si le content type est bien en JSON (type d'objet envoyé en requête)
if ($contentType === "application/json") {

    //On récupère le contenu de la requête
    $content = trim(file_get_contents("php://input"));
    //On décode le contenu afin d'en faire un tableau[clé : valeur]
    $decoded = json_decode($content, true);

    //On défini le content type de la réponse du serveur
    header("Content-Type: application/json; charset=UTF-8");

    //On vérifie que le json décodé possède bien un "type"
    if (isset($decoded["type"])) {

        //En fonction du "type" du JSON le serveur va faire des actions différentes
        switch ($decoded["type"]) {

            case "getProducts":
                //On récupère tous les produits 
                $ans = callDataApi("/produits");
                if ($ans)
                    answerCreator($ans, false);
                else
                    answerCreator("Erreur API Data");
                break;

            case "getUniqueProduct":
                //On récupère un produit à partir de l'id donné dans l'objet JSON
                $ans = callDataApi("/produits/" . $decoded["id"]);
                if ($ans)
                    answerCreator($ans, false);
                else
                    answerCreator("Erreur SQL");
                break;

            case "updateProduct":
                //Ici on met à jour un produit, on récupère alors un objet json correspondant au produit qu'on décode
                $content = $decoded["product"];

                $promo = $content["promo"];

                $produit = [
                    "nomp" => $content["nom"],
                    "prix" => $content["prix"],
                    "image" => $content["img"],
                    "type" => $content["type"],
                    "materiaux" => $content["mat"],
                    "idp" => $content["id"],
                    "promo" => $content["promo"]
                ];

                $ans = callDataApi("/produits", $produit, null, "PUT");
                if ($ans["status"] == 200)
                    answerCreator($content, false);
                else
                    answerCreator("Erreur API -> " . $ans["response"]["detail"]);
                break;

            case "categoProduits":

                $rep = callDataApi("/catego_produit");

                if ($rep["status"] == 200) {
                    //On génère un tableau qui sera transformé en objet JSON
                    $types = $rep["response"]["types"];
                    $prices = $rep["response"]["prix"];
                    $materiaux = $rep["response"]["materiaux"];
                    $ans = array(
                        "types" => $types,
                        "materiaux" => $materiaux,
                        "prix" => $prices
                    );
                    answerCreator($ans, false);
                } else
                    answerCreator("Erreur SQL");
                break;

            default:
                //Si le type n'est pas filtré au dessus, on renvoie un objet erreur avec le message suivant
                answerCreator("Le type donné n'est pas reconnu.");
                break;
        }
        return;
    } else {
        answerCreator("Requete invalide, le paramètre Type est manquant.");
        return;
    }
} else {
    answerCreator("Le contenu de la requête est invalide.");
    return;
}

/**
 * Cette fonction permet de générer un objet JSON et de l'envoyer au client
 * @param content -> un objet qui correspond au contenu de la réponse (une chaine de caractère, un tableau...)
 * @param error -> un booleen qui indique si l'objet JSON renvoyé correspond à une erreur ou non, par défaut Vrai (la réponse est une erreur)
 */
function answerCreator($content, $error = true)
{
    echo json_encode(array(
        "type" => $error ? "Error" : "Success",
        "content" => $content
    ));
}

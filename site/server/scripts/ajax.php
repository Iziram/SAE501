<?php

/**
 * Le but de ce fichier est de fournir les réponses aux requêtes AJAX (réponses sous le format JSON)
 */

//On récupère le content type de la requête.
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
//On inclue les fonctions liées à la base de donnée
include $_SERVER['DOCUMENT_ROOT'] . '/server/scripts/api.php';

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
                $ans = callDataApi("/products");
                if ($ans)
                    answerCreator($ans, false);
                else
                    answerCreator("Erreur SQL");
                break;

            case "getUniqueProduct":
                //On récupère un produit à partir de l'id donné dans l'objet JSON
                $ans = callDatabase("select * from Produits where idP= " . $decoded["id"], true, true);
                if ($ans)
                    answerCreator($ans, false);
                else
                    answerCreator("Erreur SQL");
                break;

            case "updateProduct":
                //Ici on met à jour un produit, on récupère alors un objet json correspondant au produit qu'on décode
                $content = $decoded["product"];

                $promo = $content["promo"];

                //On génère une requête sql grâce à l'objet JSON
                $sql = "update Produits set NomP = " . quote($content["nom"]) .
                    ", Prix = " . $content["prix"] . ", type = " . quote($content["type"]) .
                    ", image = " . quote($content["img"]) .
                    ", materiaux = " . quote($content["mat"]) . ", Promo = " .
                    "'$promo'" . "where idP = " . $content["id"];

                $ans = callDatabase(
                    $sql,
                    false
                );
                if ($ans)
                    answerCreator($content, false);
                else
                    answerCreator("Erreur SQL -> '" . $sql . ";'");
                break;

            case "categoProduits":
                //Ici on récupère toutes les catégories (type et materiaux et les bornes(max et min) de prix)
                $types = callDatabase("select distinct type from Produits");
                $materiaux = callDatabase("select distinct materiaux from Produits");
                $prices = callDatabase("select max(prix)as maxi, min(prix) as mini from Produits", true, true);

                if ($types && $materiaux && $prices) {
                    //On génère un tableau qui sera transformé en objet JSON
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

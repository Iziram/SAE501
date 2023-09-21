<?php

/**
 * Cette fonction permet de contacter la base de données et de lui envoyer une requête sql
 * @param sql -> une chaine de caractère représentant la requête sql à envoyer
 * @param select -> un booleen qui indique si la requête est en select ou non, par défaut à Vrai
 * @param unique -> un boolean qui indique si la requête select renverra un seul tuple ou non. par défaut à Vrai
 * 
 * @return | Dans le cas d'une requête select : 
 *          -> Un booleen false si la requête est incorrecte ou si la réponse de la base de donnée est vide
 *          -> Un tableau de tableau[clé:valeur] si la réponse n'est pas unique
 *          -> Un tableau[clé:valeur] si la réponse est unique
 *         | Dans le cas d'une requête autre que select :
 *          -> Un booleen Vrai si la requête est correcte, Faux sinon.  
 */
function callDatabase($sql, $select = true, $unique = false, $auth = false)
{
    //Connexion à la base de donnée
    if ($auth) {
        $db = new PDO('sqlite:' . $_SERVER['DOCUMENT_ROOT'] . '/server/bdd/auth.db');
    } else {
        $db = new PDO('sqlite:' . $_SERVER['DOCUMENT_ROOT'] . '/server/bdd/data.db');
    }

    //Initialisation de la variable res à Faux
    $res = false;


    if ($select) {
        //Si la requête est un select, on utilise la methode "query" de l'objet PDO
        $res = $db->query($sql);
    } else {
        //Sinon on utilise la methode "exec"
        $res = $db->exec($sql);
    }

    //Si la requête est correcte (pas d'erreur de syntaxe, un retour de la base de donnée etc)
    if ($res) {
        if ($select) {
            //Si la requête est en select
            if ($unique) {
                //Si la requête est unique
                return $res->fetch(PDO::FETCH_ASSOC);
            } else {
                //Si la requête n'est pas unique
                return $res->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            //Si la requête n'est pas en select
            return true;
        }
    } else {
        //Si la requête n'est pas correcte
        return false;
    }
}

/**
 * Fonction afin de pouvoir utiliser quote dans d'autres fichier sans avoir à d'objet PDO à créer à chaque fois.
 * @param param -> Une chaine de caractère à mettre en forme afin de sécuriser le requête sql
 */
function quote($param)
{
    $db = new PDO('sqlite:' . $_SERVER['DOCUMENT_ROOT'] . '/server/bdd/bdd.db');
    return $db->quote($param);
}
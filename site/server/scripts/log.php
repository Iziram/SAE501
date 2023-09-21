<?php
/**
 * Fonction qui permet d'écrirer dans le fichier de log un message au format :
 * [dd-mm-yyyy h:m:s] @ip STATUT_CONNEXION : UTILISATEUR [statut :STATUT] 
 */
function writeLog($message){
    //On ouvre le fichier de log
    $log = fopen($_SERVER['DOCUMENT_ROOT'].'/server/logs/access.log','a+');
    //On récupère l'ip du client
    $ip = $_SERVER['REMOTE_ADDR'];
    //On écrit le message dans le fichier
    fputs(
        $log, 
        "[".date("d-m-Y H:i:s")."] {$ip}".
        " $message \n"
    );
    //On ferme le fichier
    fclose($log);
}

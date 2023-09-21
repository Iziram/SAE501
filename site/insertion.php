<?php if (empty(session_id())) session_start(); ?>
<?php

include('client/pages/header.html');
include('server/scripts/fonctions.php');
blockerPage(true, "index.php");
?>

<?php sideBar() ?>
<script src="client/scripts/products.js"></script>

<?php

//Initialisation des valeurs par défaut du formulaire
$nomPdt = "";
$prixPdt = "";
$type = "";
$mat = "";
$path = "";
$promoPdt = "off";


// On vérifie si on a une requête post. Cela permettra de garder les valeurs affichées
// Utile dans le cas où l'insertion n'a pas pu se faire.
if (!empty($_POST)) {

    // On initialise les variables avec leurs valeurs ou une valeur par défaut
    $nomPdt = isset($_POST["nom"]) ? $_POST["nom"] : "";
    $prixPdt = isset($_POST["prix"]) ? $_POST["prix"] : "";
    $type = isset($_POST["selectType"]) ? $_POST["selectType"] : "";
    $mat = isset($_POST["selectMat"]) ? $_POST["selectMat"] : "";
    $promoPdt = isset($_POST["promo"]) ? $_POST["promo"] : "off";
    $path = isset($_POST["filePath"]) ? $_POST["filePath"] : "";


    //On vérifie que le captcha est correcte et que les inputs du formulaires ne sont pas à leur valeur par défaut
    if (isset($_POST["captcha"]) && $_POST["captcha"] == $_SESSION["captcha"]) {
        if ($prixPdt != "" && $nomPdt != "" && $type != "" && $mat != "") {
            //Si c'est le cas on ajoute le produit à la base de donnée
            $call = addProduct($nomPdt, $prixPdt, $promoPdt, $type, $mat, $path);
        }
    } else {
        //Sinon on affiche un message d'erreur 
?>
<script>
Swal.fire({
    title: "Malheur une erreur !",
    text: "Le captcha n'a pas été entré / est incorrect ou vous n'avez pas correctement rempli le formulaire",
    icon: "error",
    button: "J'ai compris",
    timer: 5000,
    timerProgressBar: true,
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    }
});
</script>
<?php
    }
}

//On récupère la liste des types et des matériaux pour afficher dynamiquement les selects.
$types = callDatabase("select distinct type from Produits order by type ASC");
$materiaux = callDatabase("select distinct materiaux from Produits order by materiaux ASC");
?>


<h1 class="center">Créer un nouveau produit</h1>

<!-- Formualaire d'insertion -->
<form class="container" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <!-- Première ligne : Nom et Prix du produit -->
    <div class="row">
        <div class="input-field col s5">
            <label for="nomPdt">Nom du produit : </label>
            <input id="nomPdt" name="nom" type="text" placeholder="Collier avec Emeraude"
                value="<?php echo $nomPdt; ?>" />
        </div>
        <div class="input-field col s5">
            <label for="prixPdt">Prix du produit : </label>
            <input id="prixPdt" name="prix" type="number" step="0.01" placeholder="6940.69"
                value="<?php echo $prixPdt; ?>" />
        </div>
    </div>
    <!-- Deuxième ligne : type et materiaux du produit -->
    <div class="row">
        <div class="input-field col s5">
            <select id="selectType" name="selectType" class="selectable">
                <option value="" disabled selected>Type de produit</option>
                <?php
                //affichage dynamique des types
                if ($types) {
                    foreach ($types as $value) {
                        echo '<option value="' . $value["type"] . '">' . $value["type"] . '</option>';
                    }
                }
                ?>
            </select>
            <label>Type</label>
        </div>
        <div class="input-field col s5">
            <select id="selectMat" name="selectMat" class="selectable">
                <option value="" disabled selected>Type de Matériaux</option>
                <?php
                //affichage dynamique des materiaux
                if ($materiaux) {
                    foreach ($materiaux as $value) {
                        echo '<option value="' . $value["materiaux"] . '">' . $value["materiaux"] . '</option>';
                    }
                }
                ?>
            </select>
            <label>Matériaux</label>
        </div>
    </div>
    <!-- Troisième ligne : Lien de l'image et case à cocher pour la promo -->
    <div class="row">
        <div class="input-field col s5">
            <label for="filePath">Lien vers l'image : </label>
            <input id="filePath" name="filePath" type="text" placeholder="image.jpg" value="<?php echo $path; ?>" />
        </div>
        <div class="input-field col s5 center">
            <p>
                <label>
                    <?php
                    if ($promoPdt == "on") {
                    ?>
                    <input type="checkbox" class="filled-in" name="promo" checked />
                    <?php
                    } else {
                    ?>
                    <input type="checkbox" class="filled-in" name="promo" />
                    <?php
                    }

                    ?>
                    <span>En Promotion</span>
                </label>
            </p>
        </div>
    </div>
    <!-- Affichage du captcha -->
    <div class="center">
        <img src="server/scripts/captcha.php" style="width:12.5%;" alt="captcha">
    </div>
    <!-- Input du formulaire pour entrer le captcha -->
    <div class="container row">
        <label for="captcha">Entrez le captcha: </label>
        <input id="captcha" name="captcha" type="text" />
    </div>
    <!-- Bouton pour ajouter le produit -->
    <div class="container row">
        <button class="btn col s12 center-align purple">Ajouter le produit</button>
    </div>
</form>

<!-- Initilialisation du javascript qui gère les select de MaterializeCss -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let elems = document.querySelectorAll('.selectable')
    M.FormSelect.init(elems);
});
</script>
<?php
include('client/pages/footer.html');
?>
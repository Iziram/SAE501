<?php
include("server/scripts/bdd.php");
include("server/scripts/api.php");
/**
 * Fonction qui en à l'aide d'un couple login / mdp renvoie Vrai si l'utilisateur peut se connecter sinon Faux
 */
function connexionToApp($login, $pass)
{
    //On protège la base de donnée d'une injection sql 
    $res = callAuthApi("/auth/token");


    //Si la réponse est vraie alors on créer une session et on écrit dans les log la bonne connexion puis on renvoit Vrai
    if ($res) {
        $_SESSION["login"] = $login;
        $_SESSION["statut"] = $res["statut"];
        writeLog("Connexion réussie pour l'utilisateur : $login [statut :" . $res["statut"] . "]");
        return true;
    }
    //Sinon on écrit une erreur dans le fichier de log et on renvoie faux
    writeLog("Connexion refusée pour l'utilisateur : $login");

    return false;
}
/**
 * Fonction qui permet d'ajouter un produit dans la base de donnée
 * Renvoie Vrai si le produit a bien été ajouté faux Sinon
 */
function addProduct($nom, $prix, $promo, $type, $mat, $path)
{
    //On protège la base de donnée d'une injection sql 
    $qnom = quote($nom);
    $qtype = quote($type);
    $qmat = quote($mat);
    $qpath = quote($path);
    $qpromo = $promo == "on" ? "'1'" : "'0'";
    //On écrit la requête sql
    $sql = "insert into Produits (NomP, Prix, Promo, type, materiaux, image) values ($qnom, $prix, $qpromo, $qtype, $qmat, $qpath)";
    //On appelle la base de donnée
    $res = callDatabase(
        $sql,
        false,
        true
    );
    //Si on a une réponse vrai alors on affiche que le produit à bien été ajouté
    if ($res) {
?>
        <script>
            Swal.fire({
                title: "Le produit a bien été ajouté",
                icon: "success",
                toast: true,
                timer: 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })
        </script>

    <?php
        //Après avoir affiché le message on renvoie vrai 
        return true;
    } else {
        //Sinon on a une réponse faux alors on affiche que le produit n'a pas été ajouté
    ?>
        <script>
            Swal.fire({
                title: "Le produit n'a pas pu être ajouté",
                icon: "error",
                toast: true,
                timer: 5000,
                showConfirmButton: false,
                timerProgressBar: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            })
        </script>

    <?php
    }

    //Et on renvoie Faux
    return false;
}
/**
 * Petite fonction qui permet de formater une chaine de caractère pour éviter les failles XSS
 */
function formatInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Fonction qui permet de bloquer une page si le client ne possède pas de session
 * en donnant un booléen vrai comme paramètre on exige du client d'avoir une session Admin
 */
function blockerPage($needAdmin = false)
{
    if (empty($_SESSION) || !isset($_SESSION["login"])) {
    ?>
        <!-- Si le client n'est pas connecté affichage d'un message et redirection vers connexion.php -->
        <script>
            Swal.fire({
                title: "Vous n'êtes pas connecté",
                text: "Vous allez être redirigé vers la page de connexion.",
                icon: "error",
                button: "OK",
                timer: 5000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(
                () => {
                    window.location = "connexion.php";
                }
            );
        </script>

    <?php
    } else if ($needAdmin && $_SESSION["statut"] != "admin") {
    ?>
        <!-- Si le client est connecté mais qu'il n'est pas admin et qu'il faut être admin on affiche un message et on redirige vers index.php -->
        <script>
            Swal.fire({
                title: "Vous êtes n'êtes pas autorisé à être sur cette page",
                text: "Vous allez être redirigé vers la page index.",
                icon: "error",
                button: "OK",
                timer: 5000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(
                () => {
                    window.location = "index.php";
                }
            );
        </script>

    <?php
    }
}

/**
 * Fonction qui permet de générer le menu de profil
 */
function sideBar()
{
    //On récupère le statut et on change légèrement ce qui écrit (admin => Administrateur, autre => Utilisateur)
    $statut =  $_SESSION["statut"] == "admin" ? "Administrateur" : "Utilisateur";
    ?>

    <ul id="profil" class="sidenav">
        <li style="height: 25%;">
        </li>
        <li>
            <!-- On affiche le login et le statut -->
            <div class="user-view center">
                <a href="#email"><span class="dark-text email"><?php echo $_SESSION["login"]; ?></span></a>
                <a href="#name"><span class="dark-text name"><?php echo $statut; ?></span></a>
            </div>
        </li>
        <!-- On affiche un lien pour accéder à la page index.php -->
        <li><a href="index.php"><i class="material-icons">home</i>Accueil</a></li>
        <?php
        //Si le statut est 'Administrateur' alors on ajoute deux lien pour la page Insertion et Modification
        if ($statut == "Administrateur") {
        ?>
            <li><a href="insertion.php"><i class="material-icons">add_box</i>Créer Nouveau Produit</a></li>
            <li><a href="modification.php"><i class="material-icons">edit</i>Modifier un Produit</a></li>
        <?php
        }
        ?>
        <li>
            <div class="divider"></div>
        </li>
        <!-- On affiche aussi un bouton déconnexion -->
        <li>
            <div class="center">
                <a class="btn purple" href="deconnexion.php"><i class="material-icons">logout</i>deconnexion</a>
            </div>
        </li>
    </ul>
    <!-- Initialisation du "sidenav" pour qu'il puisse sortir de la droite de l'écran -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var elem = document.getElementById('profil');
            var instances = M.Sidenav.init(elem, {
                edge: "right"
            });
        });
    </script>
<?php
}

/**
 * Fonction qui permet de générer le panel de filtrage et met en place l'affichage du carousel de promotion
 * et des produits (filtré ou non)
 */
function generateDynamicProductList()
{
?>
    <!-- HTML du sidenav du panel de filtrage , avec aucune valeur dedans (ajoutées plus tard avec du js)-->
    <ul id="searchSlide" class="sidenav">
        <li style="height: 25%;">
        </li>
        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <!-- Liste de case à cocher pour les types -->
                <li>
                    <a class="collapsible-header">Type<i class="material-icons">arrow_drop_down</i></a>
                    <div class="collapsible-body">
                        <ul id="type">

                        </ul>
                    </div>
                </li>
                <!-- Liste de case à cocher pour les matériaux -->
                <li>
                    <a class="collapsible-header">Matériaux<i class="material-icons">arrow_drop_down</i></a>
                    <div class="collapsible-body">
                        <ul id="mat">

                        </ul>
                    </div>
                </li>
                <!-- Slider pour les prix (avec min et max) -->
                <li>
                    <a class="collapsible-header">Prix<i class="material-icons">arrow_drop_down</i></a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="container center">
                                <br>
                                <div id="sliderPrix" class="slider-round"></div>
                                <span id="min" class="hoverable">A</span><span>|</span><span id="max" class="hoverable">B</span>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
    <!-- Container qui contiendra le carousel des promotions -->
    <hr class="container">
    <div id="carouselPlacer" class="container">
        <h2 class="title center">Promotions</h2>
        <div class="carousel" id="carousel">

        </div>
    </div>

    <hr class="container">
    <h2 class="title center">Tous les produits (filtrés)</h2>
    <!-- Container qui contiendra le tableau de produit filtré(ou non) -->
    <div class="row container" id="placer">

    </div>
    <hr class="container">
    <h2 class="title center">Tous les produits (non filtrés)</h2>
    <!-- Container qui contiendra le tableau de produit filtré(ou non) -->
    <div class="row container" id="noFiltre">

    </div>
    <script>
        // Initialisation du javascript lié au sidenav
        var elems = document.getElementById('searchSlide');
        var instances = M.Sidenav.init(elems);
        var collapsibleElem = document.querySelector('.collapsible');
        var collapsibleInstance = M.Collapsible.init(collapsibleElem);

        //Mise à jour du panel de filtrage avec les types, produits et prix dynamique
        dynamicFilter();
        //Récupération des produits et affichage des produits filtrés
        fullProducts().then((value) => {
            showProducts(value);
            showProducts(value, 'noFiltre');
        }, (value) => console.error(value))


        //Changement du produit affiché par le carousel, toutes les 3 secondes
        setInterval(() => {
            const carousel = M.Carousel.getInstance(document.getElementById('carousel'));
            carousel.next();
        }, 3000)
    </script>
<?php
}

?>
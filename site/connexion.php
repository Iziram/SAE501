<?php if (empty(session_id())) session_start(); ?>

<?php
include('client/pages/header.html');
include("server/scripts/fonctions.php");
include("server/scripts/log.php");

// Gestion du formulaire de connexion.
if (!empty($_POST) && isset($_POST["login"]) && isset($_POST["pass"])) {
    $login = formatInput($_POST["login"]);
    $pass = formatInput($_POST["pass"]);
    $res = connexionToApp($login, $pass);
    if ($res) {
        //Si le couple login/mdp est correcte alors on affiche un message et on redirige vers Index.php
?>
        <script>
            Swal.fire({
                title: "Vous êtes désormais connecté.",
                text: "Vous allez être redirigé sur le site",
                icon: "success",
                button: "OK",
                timer: 5000,
                timerProgressBar: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((value) => {
                window.location = "index.php";
            });
        </script>
    <?php
    } else {
        //Sinon on indique une erreur (login/mdp incorrect)
    ?>
        <script>
            Swal.fire({
                title: "Connexion Refusée",
                text: "Le couple identifiant / mot de passe n'existe pas ou est incorrecte.",
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
} else if (!empty($_SESSION) && isset($_SESSION["login"])) {
    //On vérifie le cas où l'utilisateur est déjà connecté, si c'est le cas alors on le redirige directement vers index.php
    ?>
    <script>
        Swal.fire({
            title: "Vous êtes déjà connecté.",
            text: "Vous allez être redirigé sur le site",
            icon: "success",
            button: "OK",
            timer: 5000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((value) => {
            window.location = "index.php";
        });
    </script>
<?php
}
?>

<script src="client/scripts/verifications.js"></script>
<!-- On génère un container qui est par défaut centré au milieu de la page et qui prend 87.2% de la hauteur de l'écran -->
<div class="container valign-wrapper" style="height: 87.2vh;">

    <!-- On affiche un container qui contient le formulaire de connexion -->
    <div class="container z-depth-4" style="padding:1vw 1vw;border-radius:1rem;">
        <h2 class="title center"><b>Connexion</b></h2>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" onsubmit="return testConnectForm()">
            <label for="login">Login: </label>
            <input type="text" name="login" id="login" placeholder="Nom d'utilisateur" required onkeyup="verifierLogin(this.value)" />

            <!-- Gestion de la progresse bar (en cas de login non conforme) par défaut elle n'est pas affichée -->
            <div id="progressBar_login_div" class="progress hid progressBar">
                <div id="progressBar_login_bar" class="determinate" style="width: 50%"></div>
            </div>

            <p id="progressBar_login_text" class="hid progressBar"></p>

            <label for="pass">Mot de passe: </label>
            <input type="password" name="pass" id="pass" required />

            <br /><br />

            <div class="row">
                <input class="btn col s3 offset-s9 purple lighten-3 right" type="submit" value="Se connecter" />
            </div>
        </form>
    </div>
</div>

<?php
include('client/pages/footer.html');
?>
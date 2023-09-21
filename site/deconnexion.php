<?php if (empty(session_id())) session_start(); ?>

<?php

include('client/pages/header.html');
// Si l'utilisateur à une session :
if (!empty($_SESSION)) {
    //On détruit la session et on affiche un message avant de le rediriger vers la page index
    $_SESSION = [];
    session_destroy();
?>

<script>
Swal.fire({
    title: "Vous avez été déconnecté",
    text: "Vous allez être redirigé vers la page de connexion.",
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
}).then(
    () => {
        window.location = "connexion.php";
    }
);
</script>

<?php
} else {
    //Si l'utilisateur n'a pas de session alors on lui affiche le message correspondant et on le redirige vers connexion.php
?>

<script>
Swal.fire({
    title: "Vous êtes déjà déconnecté",
    text: "Vous allez être redirigé vers la page de connexion.",
    icon: "info",
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
}


?>
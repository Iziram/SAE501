<?php
include('client/pages/header.html');
?>
<!-- Gestion de la page 404 avec un petit message et un Lottie -->
<section class="container">
    <div class="center">
        <!-- Affichage d'une image animée (Lottie)  -->
        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
        <lottie-player src="https://assets7.lottiefiles.com/packages/lf20_sdd6siet.json" background="transparent" speed="1" style="width: 75%;" loop autoplay></lottie-player>
        
        <h2>
            Il semblerait que vous vous soyez perdu.
        </h2>
        <h3>
            Cliquez sur le bouton pour revenir à la page index.
        </h3>
        <a href="index.php" class="btn purple">Retour à la page index</a>
    </div>
</section>
<?php

include('client/pages/footer.html');
?>
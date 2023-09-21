<?php if (empty(session_id())) session_start(); ?>

<?php
include('client/pages/header.html');
include('server/scripts/fonctions.php');
blockerPage();
echo '<h1 class="center-align">Liste des produits</h1>';
?>

<script src="client/scripts/ajax.js"></script>
<script src="client/scripts/products.js"></script>
<script src="client/scripts/pages/index.js"></script>

<?php
sideBar();
//Affichage du carousel des produits en promotion, du tableau de produit et du menu de filtrage (voir fonction)
generateDynamicProductList();
?>

<?php
include('client/pages/footer.html');
?>
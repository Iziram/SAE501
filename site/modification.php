<?php if (empty(session_id())) session_start(); ?>
<?php

include('client/pages/header.html');
include('server/scripts/fonctions.php');
blockerPage(true, "index.php");
?>

<?php
sideBar();
?>
<script src="client/scripts/products.js"></script>
<script src="client/scripts/pages/modif.js"></script>

<h1 class="center">Modifier un produit</h1>

<!-- select avec le nom des produits et en value leur id
ajax qui change la page en un formulaire de modification  -->
<br>
<br>
<!-- Container qui contiendra d'abord le select puis qui sera remplacé par le formulaire -->
<div class="container" id="placer">
    <!-- Une search bar qui permet de filtrer les produits dans le select -->
    <div class="row">
        <!-- Actualisation du filtre à lettre ajoutée ou retirée -->
        <input class="col s6 offset-s3 center" type="text" placeholder="Filtrer les noms de produits" id="selector"
            onkeyup="modificationProductList(this.value)" />
    </div>
    <br>
    <!-- Le select qui contiendra les produits. 
    Lorsque ça valeur est changée (clic sur un produit) de l'ajax est exécuté et un formlaire est affiché -->
    <div class="row">
        <br>
        <div class="input-field col s6 offset-s3">
            <select id="products" class="selectable" onchange="generateModificationForm(this.value);">
            </select>
            <label>Matériaux</label>
        </div>
    </div>
</div>
<!-- Lancement de la fonction "modificationProductList" afin d'afficher dynamiquement le select -->
<script>
modificationProductList();
</script>

<?php
include('client/pages/footer.html');
?>
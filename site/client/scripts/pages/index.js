/**
 * Fonction qui permet d'afficher dynamiquement le carousel en fonction des produits filtrés en promotion
 * @param {Array} products Liste des produits (filtrés)
 */
function promotionCarousel(products) {
    
    //On récupère l'élement DOM du carousel
    const carousel = document.getElementById('carousel')
    //On le vide
    carousel.innerHTML = ""
    //On filtre les produits pour n'avoir que les produits en promotion
    const promos = products.filter((el) => el.Promo == 1);
    //Si il y a des produits en promotion:
    if (promos.length > 0) {
        promos.forEach(element => {
            const a = document.createElement('a');
            a.classList.add('carousel-item');
            a.appendChild(productCard(element, true));

            carousel.appendChild(a);
        });
    } else {
        //Sinon on génère une carte vide (obligatoire sinon )
        const a = document.createElement('a');
        a.classList.add('carousel-item');
        a.appendChild(productCard({}, true));

        carousel.appendChild(a);
    }
    //On réinitialise le carousel pour que les changements soient effectifs
    M.Carousel.init(carousel, {
        dist: 0,
    });

}
/**
 * Fonction qui génère une carte (div) représentant le produit
 * @param {object} el le produit sous forme d'objet js
 * @param {bool} carousel un booléen indiquant si on génère pour le carousel ou non, car l'affichage est légèrement différent
 * @returns {HTMLDivElement} la carte générée
 */
function productCard(el, carousel = false) {
    //On créer un div qui contiendra la carte à la bonne taille
    const col = document.createElement('div');
    //Si c'est un carousel, pas besoin de changer la taille
    if (carousel) {
        col.classList.add('col');
    } else {
        col.classList.add('col', 's3');
    }
    //On vérifie que le prix est présent dans l'objet, on le considère comme l'objet vide, qui est utilisé quand il n'y a pas de promotion disponible
    //dans les produits filtrés
    if(el.Prix){
        /**
         Génération du code html suivant :

        <div class="card small">
            <div class="card-image waves-effect waves-block waves-light">
                <img class="activator pdtImg" src="server/images/URL_IMG" alt="L'image n'a pas pu être chargée">
            </div>
            <div class="card-content">
                <span class="card-title activator grey-text text-darken-4 center">NOM_PRODUIT</span>
            </div>
            <div class="card-reveal">
                <div class="row">
                    <span class="card-title grey-text text-darken-4 col s12 center">NOM_PRODUIT</span>
                </div>
                <hr>
                <div class="row">
                    <p class="col s12 center">PRIX_PRODUIT€</p>
                </div>
                <hr>
                <div class="row">
                    <p class="col s6 center">TYPE_PRODUIT</p>
                    <p class="col s6 center">MATERIAUX_PRODUIT</p>
                </div>
            </div>
        </div>
         */
        const card = document.createElement('div');
        card.classList.add('card', 'small');

        //Génération de la première partie : L'image
        const cardImg = document.createElement('div');
        cardImg.classList.add('card-image', 'waves-effect', 'waves-block', 'waves-light');
        const img = document.createElement('img');
        img.classList.add('activator', 'pdtImg');
        img.src = "server/images/" + el.image;
        img.alt = "L'image n'a pas pu être chargée";
        cardImg.appendChild(img);
        card.appendChild(cardImg);

        //Génération de la deuxième partie : le titre
        const cardContent = document.createElement('div');
        cardContent.classList.add('card-content');
        const contentTitle = document.createElement('span');
        contentTitle.textContent = el.NomP;
        contentTitle.classList.add('card-title', 'activator', 'grey-text', 'text-darken-4', 'center');
        cardContent.appendChild(contentTitle);
        card.appendChild(cardContent);


        //Génération de la troisième partie : Les informations complètes
        let types = `<div class="row">
                <p class="col s6 center">${el.type}</p>
                <p class="col s6 center">${el.materiaux}</p>
            </div>`;
        
        //Si la carte est dans le carousel alors on affiche pas ni types ni materiaux
        if (carousel) types = "";

        //On ajoute un petit badge rouge si un produit est en promotion
        let promo = `<span class="new badge red" data-badge-caption="Promotion"></span>`;
        if (!el.Promo) promo = "";

            const html = `
                    <div class="card-reveal">
                    ${promo}
                    <div class="row">
                        <span class="card-title grey-text text-darken-4 col s12 center">${el.NomP}</span>
                    </div>
                    <hr>
                    <div class="row">
                        <p class="col s12 center">${el.Prix.toFixed(2)}€</p>
                    </div>
                    <hr>
                    ${types}
                    </div>
                    `;
        
        card.appendChild(HTMLParser(html));
        col.appendChild(card);
    }else{
        // si l'objet est vide alors on affiche une carte indiquant qu'il n'y a pas de promotion sur les produits filtrés
        const html = `
            <div class="card small">
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4 center">Aucune Promotion sur les produits filtrés</span>
                </div>
                <div class="card-reveal">
                    <span class="new badge red" data-badge-caption="Promotion"></span>
                    <div class="row">
                        <span class="card-title grey-text text-darken-4 col s12 center">Aucune Promotion</span>
                    </div>
                    <hr>
                </div>
            </div>`;
        col.appendChild(HTMLParser(html));

    }

    //On renvoi l'objet DOM généré
    return col;
}
/**
 * Fonction permettant de mettre dynamiquement les cases des types / matériaux et le slider des prix
 */
function dynamicFilter() {
    //On récupère les types, materiaux et prix (max/min) à partir d'une requête ajax et d'une promesse jS
    const cat = productCategory();
    cat.then((value) => {

        //On filtre pour n'avoir que les types/materiaux et non des liste de "el["type"]"
        const types = value.types.map((el) => el["type"]);
        const mat = value.materiaux.map((el) => el["materiaux"]);

        //Gestion des checkbox des materiaux

        //On récupère l'élement et on le vide
        const ulMat = document.getElementById('mat');
        ulMat.innerHTML = "";
        //Pour chaque materiaux on génère une checkbox materialize et on l'ajoute à notre élément html
        mat.forEach(function (el) {
            const p = document.createElement('p');
            p.classList.add("container");
            const label = document.createElement('label');
            const input = document.createElement('input');
            input.type = "checkbox";
            input.classList.add('filled-in');
            const span = document.createElement('span');
            span.textContent = el;
            label.appendChild(input);
            label.appendChild(span);
            p.appendChild(label);
            ulMat.appendChild(p);
        });

        //Gestion des checkbox de type

        //On récupère l'élement et on le vide
        const ulType = document.getElementById('type');
        ulType.innerHTML = "";

        //Pour chaque type on génère une checkbox materialize et on l'ajoute à notre élément html
        types.forEach(function (el) {
            const p = document.createElement('p');
            p.classList.add("container");
            const label = document.createElement('label');
            const input = document.createElement('input');
            input.type = "checkbox";
            input.classList.add('filled-in');
            const span = document.createElement('span');
            span.textContent = el;
            label.appendChild(input);
            label.appendChild(span);
            p.appendChild(label);
            ulType.appendChild(p);
        });

        //Gestion du slider des prix

        //On récupère le prix max et le prix min (en les arrondissant à 2 chiffres après la virgule)
        const prixMax = parseFloat(value.prix.maxi.toFixed(2));
        const prixMin = parseFloat(value.prix.mini.toFixed(2));

        //On récupère l'élément html du slider
        const slider = document.getElementById('sliderPrix');

        //On créé un slider à l'aide de noUISlider en lui indiquant le prix max, min
        noUiSlider.create(slider, {
            start: [prixMin, prixMax],
            connect: true,
            orientation: 'horizontal',
            range: {
                'min': prixMin,
                'max': prixMax
            },
        });
        
        //On met à jour les petits label qui permettent de voir quelle est la valeur min et max du prix des éléments qu'on filtre
        const mini = document.getElementById('min');
        const maxi = document.getElementById('max');
        mini.textContent = prixMin;
        maxi.textContent = prixMax;

        
        //On applique une fonction à chaque mise à jour du slider
        slider.noUiSlider.on('update', function (values, handle) {
            //On récupère les valeurs du slider et on mets à jour les labels
            const value = values[handle];
            if (handle) {
                maxi.textContent = value + "€";
            } else {
                mini.textContent = value + "€";
            }
            //On mets à jour la liste des produits en fonction du filtre
            filterProducts(slider);
        });


        const checkboxes = Array.from(document.getElementsByClassName('filled-in'));
        //Lorsqu'on clique sur une case à cocher (du filtre)
        checkboxes.forEach((el) => {
            //On mets à jour la liste des produits en fonction du filtre
            el.addEventListener('click', function() {filterProducts(slider);});
        });

    }, (value) => {
        console.error(value)
    })
}
/**
 * Fonction qui actualise l'affichage des produits en fonctions des filtres à appliquer
 * @param {*} slider le slider des prix
 */
function filterProducts(slider) {

    //Récupération des filtres à appliquer et de la liste des produits
    const filter = getFilter(slider);
    const products = fullProducts();

    products.then(
        function (value) {
            // On filtre la liste des produits 
            const filteredProducts = value.filter(function (el) {

                //Initialisation d'une variable t qui reste à true tant que l'élément correspond à tous les filtres
                let t = true;
                //filtrage du type
                if (filter.types.length > 0) t = t && filter.types.includes(el.type);
                //filtrage du materiaux
                if (filter.materiaux.length > 0) t = t && filter.materiaux.includes(el.materiaux);
                //filtrage du prix
                t = t && (filter.prix[0] <= el.Prix) && (filter.prix[1] >= el.Prix);
                return t
            });
            //On affiche les produits nouvellement filtrés
            showProducts(filteredProducts);
        },
        (value) => {
            console.error(value);
        }
    );
}

/**
 * Cette fonction permet d'afficher les produits (dans le carousel et dans la grille de produits)
 * @param {Array} prods -> tableau des objets javascript représentant les produits
 * @param {String} placer -> une chaine de caractères correspondant à l'id du div où seront placer les produits 
 */
function showProducts(prods, placer = 'placer') {

    //On récupère l'objet DOM correspondant à la grille de produits
    const place = document.getElementById(placer);
    //On la vide
    place.innerHTML = "";

    //Pour chaque produit dans le tableau de produits
    prods.forEach((el) => {
        //On ajoute dans la grille une carte représentant le produit
        place.appendChild(productCard(el));
    });
    //On affiche le carousel des promotions
    promotionCarousel(prods);
}
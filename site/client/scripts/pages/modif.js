/**
 * Fonction qui génère un formulaire prérempli à partir d'un id de produit
 * @param {number} idP l'id d'un produit
 */
function generateModificationForm(idP) {
    // On récupère le produit
    const promise = getProduct(idP);

    promise.then(
        (item) => {
            //On défini un html template que l'on modifiera avec les informations du produit
            const html = `
        <div>
            <div class="row">
                <span class="FormValue hid" id="idPdt"></span>
                <div class="input-field col s5">
                    <label for="nomPdt" class="active">Nom du produit : </label>
                    <input class="FormValue" id="nomPdt" name="nom" type="text" placeholder="Collier avec Emeraude"
                        value="" />
                </div>
                <div class="input-field col s5">
                    <label for="prixPdt" class="active">Prix du produit : </label>
                    <input class="FormValue" id="prixPdt" name="prix" type="number" step="0.01" placeholder="6940.69" />
                </div>
            </div>
            <div class="row">
                <div class="input-field col s5">
                    <select id="selectType" name="selectType" class="selectable FormValue">
                    </select>
                    <label class="active">Type</label>
                </div>
                <div class="input-field col s5">
                    <select id="selectMat" name="selectMat" class="selectable FormValue">
                    </select>
                    <label class="active">Matériaux</label>
                </div>
                <div class="row">
                    <div class="input-field col s5">
                        <label class="active" for="filePath">Lien vers l'image : </label>
                        <input class="FormValue" id="filePath" name="filePath" type="text" placeholder="image.jpg" />
                    </div>
                    <div class="input-field col s5 center">
                        <p>
        
                            <label for="promoP">
                                <input class="FormValue" type="checkbox" class="filled-in" id="promoP" />
                                <span>En Promotion</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="container row">
                    <button class="btn col s12 center-align purple" onclick="updateProduct();">Modifier le produit</button>
                </div>
                <div class="container row">
                    <button class="btn col s6 offset-s3 center-align purple" onclick="location.reload();">Choisir un autre
                        produit</button>
                </div>
            </div>
        </div>`;
            
            //On parse l'html de façon à avoir un element DOM
            const doc = HTMLParser(html);
            //On récupère l'emplacement ou se trouvera le formulaire et on le vide puis on ajoute l'élément DOM
            const placer = document.getElementById('placer');
            placer.innerHTML = "";
            placer.appendChild(doc);

            //Ensuite pour chaque valeur du produit on modifie la partie dans le formulaire (version DOM)
            const nomP = document.getElementById('nomPdt');
            nomP.value = item.NomP;
            const prixP = document.getElementById('prixPdt');
            prixP.value = item.Prix.toFixed('2');
            const file = document.getElementById('filePath');
            file.value = item.image;
            const id = document.getElementById('idPdt');
            id.textContent = item.idP;
            const promo = document.getElementById('promoP');
            promo.checked = item.Promo ? "checked" : "";

            //Pour les types et matériaux c'est légérement différent car ce sont des selects
            //on récupère les selects et les catégories (type/matériaux)
            const type = document.getElementById('selectType');
            const mat = document.getElementById('selectMat');
            const cat = productCategory();

            cat.then(function (value) {
                //On modifie les array de façon à avoir juste les types/matériaux et à les avoir par ordre alphanumérique
                const types = value.types.map((el) => el["type"]).sort();
                const mats = value.materiaux.map((el) => el["materiaux"]).sort();
                //Pour chaque type on ajoute une option avec comme value le type, comme affichage le type, par défaut non sélectionné, et on vérifie si le type courant 
                //est égal au type du produit qu'on affiche. Si c'est le cas alors l'option sera selectionnée sinon non.
                types.forEach((el) => {
                    type.options.add(new Option(el, el, false, el == item.type));
                });
                //même principe pour les matériaux
                mats.forEach((el) => {
                    mat.options.add(new Option(el, el, false, el == item.materiaux));
                });
                
                //On réinitialise les selects pour mettre à jour les modifications
                const elems = document.querySelectorAll('.selectable');
                M.FormSelect.init(elems);

            },
            (value) => console.error(value));

        },
        (value) => { console.error(value); }
    );
}
/**
 * Fonction qui permet de mettre à jour un produit
 */
function updateProduct() {
    //On créé un objet par défaut
    const obj = {
        id: 0,
        nom: "",
        type: "",
        mat: "",
        prix: 0.0,
        promo: 0,
        img: ""
    };
    //On récupère les valeurs du formulaire (à l'aide de la classe "FormValue")
    const values = Array.from(document.getElementsByClassName('FormValue'));
    values.forEach(function (el) {
        //Pour chaque valeur on actualise notre objet
        switch (el.id) {
            case "nomPdt":
                obj.nom = el.value;
                break;
            case "prixPdt":
                obj.prix = el.value;
                break;
            case "selectType":
                obj.type = el.value;
                break;
            case "selectMat":
                obj.mat = el.value;
                break;
            case "filePath":
                obj.img = el.value;
                break;
            case "promoP":
                obj.promo = el.checked ? 1 : 0;
                break;
            case "idPdt":
                obj.id = el.textContent;
                break;
        }
    })
    //Lorsque notre objet est construit on appelle notre fonction ajax
    // On effectue alors une requête ajax de type "updateProduct" et on donne l'objet
    ajax(
        {
            type: "updateProduct",
            product: obj
        },
        (json) => {
            // Lors de la réponse du serveur (Success) on récupère le contenu de la réponse
            // Et on génère un affichage HTML
            const prod = json.content;
            const promo = prod.promo ? "l'article est en promotion" : "l'article n'est pas en promotion";
            const html = `
            <div class="center">
                <div clas="row">
                    <img class="col s6 img" alt="photo" src="server/images/${prod.img}"/>
                </div>
                <div class="row">
                    <h5 class="flow-text">${prod.nom}</h5>
                </div>
                <div class="row">
                    <p class="flow-text col s4">
                        ${prod.prix}€
                    </p>
                    <p class="flow-text col s4">
                        ${prod.type}
                    </p>
                    <p class="flow-text col s4">
                        ${prod.mat}
                    </p>
                </div>
                <div class="row center">
                    <p class="flow-text col s6">${promo}</p>
                </div>
            </div>
            `;

            //On affiche ensuite un swal avec l'html précédemment généré
            Swal.fire({
                title: "Le produit a bien été modifié",
                html: html,
                icon: "success",
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        },
        (json) => {
            //En cas d'erreur on affiche une erreur dans la console
            console.error(json);
        }
    );
}

/**
 * Fonction qui permet de mettre à jour la liste de produits (le select) en fonction d'un filtre de nom
 * @param {string} selector une chaine de caractère qui doit être trouvé dans le début du nom des produits
 */
function modificationProductList(selector = "") {
    const products = fullProducts();
    products.then(
        function (json) {
            //On récupère l'élément select et on le vide
            const placer = document.getElementById("products");
            placer.innerHTML = "";
            //On récupère la liste des produits
            const produits = json;

            //On ajoute un option désactivée qui sert de placeholder en attendant que le client clique sur un produit
            const disabled = new Option("Produit à Modifier", '', true, true);
            disabled.disabled = "disabled";
            placer.add(disabled);

            //Pour chaque produit dont le nom commence par le selecteur, on ajoute une option dans le select
            produits.forEach(element => {
                if (element.NomP.toLowerCase().startsWith(selector.toLowerCase())) {
                    placer.add(new Option(element.NomP, element.idP));
                }
            });

            //On réinitialise le select afin d'enregistrer les modifications 
            let elems = document.querySelectorAll('.selectable');
            M.FormSelect.init(elems);
        },
        (json) => {
            console.error(json);
        }
    );
    
        
        

}
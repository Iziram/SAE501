/**
 * Cette fonction permet d'exécuter une requête AJAX vers le serveur pour récupérer les catégories de produit
 * @returns {Promise} une promesse JS (élement intéractif) 
 */
function productCategory() {
    const promise = new Promise((resolve, reject) => {
        ajax(
            {
                type: "categoProduits",
            },
            function (json) {
                //Si la requête se passe bien
                resolve(json.content);
            },
            function (json) {
                //si la requête ne se passe pas bien
                reject(json.content);
            }
        );
    })

    return promise;
}
/**
 * Cette fonction permet d'exécuter une requête AJAX vers le serveur pour récupérer tous les produits
 * @returns {Promise} une promesse JS (élement intéractif) 
 */
function fullProducts() {
    const promise = new Promise((resolve, reject) => {
        ajax(
            {
                type: "getProducts",
            },
            function (json) {
                resolve(json.content);
            },
            function (json) {
                reject(json.content);
            }
        );
    })

    return promise;
}
/**
 * Cette fonction permet de récupérer les types, les matériaux et les prix filtrés
 * @param {NoUISlider} slider le slider des prix 
 * @returns {object} un objet contenant les types, les matériaux et les prix filtrés
 */
function getFilter(slider) {
    //On récupère les cases cochées pour les types et les matériaux
    const ulTypeChecks = Array.from(document.getElementById('type').childNodes).filter((el) => {
        return el.firstChild.firstChild.checked;
    });
    const ulMatChecks = Array.from(document.getElementById('mat').childNodes).filter((el) => {
        return el.firstChild.firstChild.checked;
    });

    //On renvoie un objet 
    return {
        //On map les cases cochées afin de n'avoir qu'une liste de type et pas tous les éléments DOM
        "types": ulTypeChecks.map((el) => {
            return el.firstChild.lastChild.textContent;
        }),
        //On map les cases cochées afin de n'avoir qu'une liste de materiaux et pas tous les éléments DOM
        "materiaux": ulMatChecks.map((el) => {
            return el.firstChild.lastChild.textContent;
        }),
        //On récupère les handles du slider donc le prix min et max
        "prix": slider.noUiSlider.get()
    };
}

/**
 * Cette fonction permet d'exécuter une requête AJAX vers le serveur pour récupérer un produit à partir de son id
 * @param {number} idP l'id du produit à récupérer
 * @returns {Promise} une promesse JS (élement intéractif) 
 */
function getProduct(idP) {
    const promise = new Promise((resolve, reject) => {
        ajax(
            {
                type: "getUniqueProduct",
                id: idP
            },
            function (json) {
                resolve(json.content);
            },
            function (json) {
                reject(json.content);
            }
        );
    })

    return promise;
}
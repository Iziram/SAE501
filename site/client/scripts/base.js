/**
 * Fonction qui permet d'ajouter les icones des sliders sur les pages qui en ont
 */
function populateNavBarIcons() {
    //On récupère le nom de la page actuelle
    const page = window.location.pathname.substring(1);

    //On créer une petite fonction anonyme qui sert à générer une icone en fonction uniquement d'un nom d'icône et d'une fonction associée
    const link = (func, icon) => {
        const a = document.createElement('a');
        a.onclick = func;
        const i = document.createElement('i');
        i.classList.add('material-icons');
        i.textContent = icon
        a.appendChild(i);
        return a;
    };
    //En fonction du nom de la page on ajoute (ou non) une icône dans les "li" respectif
    switch (page) {
        case "index.php":
            document.getElementById('searchLI').appendChild(link(function () { openSlide('searchSlide'); }, 'search'));
            document.getElementById('profileLI').appendChild(link(function () { openSlide(); }, 'account_circle'));
            break;
        case "insertion.php":
            document.getElementById('profileLI').appendChild(link(function () { openSlide(); }, 'account_circle'));
            break;
        case "modification.php":
            document.getElementById('profileLI').appendChild(link(function () { openSlide(); }, 'account_circle'));
            break;
        default:
            break
    }
}
/**
 * Fonction qui ouvre un slider
 * @param {string} slideId id du slider à ouvrir, par défaut le slider du profil
 */
function openSlide(slideId = "profil") {
    const slide = document.getElementById(slideId);
    if (slide) {
        const instance = M.Sidenav.getInstance(slide);
        instance.open();
    }
}

/**
 * Cette fonction convertie une chaine de caractère (représentant des éléments html) en élément DOM utilisable par JavaScript
 * @param {String} html la chaine de caractère représentant les élémments html
 * @param {String} mode = "text/html" Le mode par défaut est en text/html mais il existe aussi d'autres modes comme le text/XML qui peut être utilisé
 * @returns un élément DOM 
 */
function HTMLParser(html, mode = "text/html") {
    if (mode === "text/html") {
        /** le mode text/html nous renverras un document entier (balise html + balise body + balise éléments)
        * On doit donc ruser un peu pour récuppérer l'élément que l'on souhaite :
        * On prend le premier enfant du document : ( document -> <html/> )
        * Puis le dernier enfant de l'html : ( <html/> -> <body/> )
        * Et enfin le premier enfant du body : ( <body/> -> <element/> )
        */
        return new DOMParser().parseFromString(html, mode).firstChild.lastChild.firstChild
    } else {
        //Dans les autres cas le premier enfant sera directement l'élément
        return new DOMParser().parseFromString(html, mode).firstChild
    }
}
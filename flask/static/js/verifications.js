/**
 * Fonction qui vérifie que le login est correcte (majuscule + caractère spécial)
 * et qui affiche une barre de progression au client
 * @param {string} login le login écrit
 */
function verifierLogin(login) {
    //On a un objet avec les messages d'erreur
    const message = {
        maj: "il vous manque une majuscule",
        spe: "il vous manque un caractère spécial (. @ & * - _ $ )"
    }
    //On définie une variable progression qui correspond au pourcentage de la barre de progression
    let progression = 0;
    //On vérifie que le login contient une majuscule
    if (/[A-Z]/.test(login)) {
        //si c'est le cas on ajoute 50% à la barre de progression en on retire le message d'erreur des majuscules
        progression += 50;
        message.maj = "";
    }
    //On vérifie que le login contient un caractère spécial
    if (/[.@&*\-_$]{1,}/.test(login)) {
        //si c'est le cas on ajoute 50% à la barre de progression en on retire le message d'erreur des caractères spéciaux
        progression += 50;
        message.spe = "";
    }
    //On récupère la barre de progression et on actualise son pourcentage
    const bar = document.getElementById('progressBar_login_bar');
    bar.style.width = `${progression}%`;

    //On récupère les éléments concernés par la barre de progression et on récupère l'élément du texte d'erreur
    const progr_div = Array.from(document.getElementsByClassName("progressBar"));
    const text = document.getElementById("progressBar_login_text");

    //Si la progression n'est pas à 100%
    if (progression < 100) {
        //On affiche la barre de progression 
        progr_div.forEach(el => {
            el.classList.remove('hid');
            //on met le message d'erreur en rouge et la barre de progression en rouge
            if (el.tagName == "P")
                el.classList.add('red-text');
            else
                el.classList.add('red');
        });
        //On créer une variable texte avec les deux messages d'erreurs (vide ou non)
        let txt = message.maj + " " + message.spe;
        //On affiche l'erreur, en ayant la première lettre en majuscule
        text.textContent = txt.trim()[0].toUpperCase() + txt.trim().substring(1);
    } else {
        //Si la progression est à 100% alors on cache la barre de progression
        progr_div.forEach(el => {
            el.classList.add('hid');
        });
    }

}
/**
 * Cette fonction permet de vérifier que le login et mdp est correcte (pour bloquer l'envoie du formulaire si ce n'est pas le cas)
 * @returns {bool} Vrai si le login est correcte (Majuscule + caractère spécial) et que le mdp est correct (au moins 8 caractères)
 */
function testConnectForm() {
    //On récupère le login et le mdp
    const login = document.getElementById("login").value;
    const pass = document.getElementById("pass").value;

    //On teste le login et la value avec du regex
    const testLogin = /[A-Z]/.test(login) && /[.@&*-_$]/.test(login);
    const testPass = /^[A-z0-9._\-&@]{8,}$/.test(pass);

    //Si ni le login ni le mdp est correcte
    if (!testLogin && !testPass) {
        Swal.fire({
            title: "Oh non des erreurs",
            html: "<p>Attention votre nom d'utilisateur n'est pas valide.</p><p>Attention votre mot de passe n'est pas valide. Il doit avoir au minimum 8 caractères ( lettres, chiffres, caractère spéciaux : @.-_& ).</p>",
            icon: "error",
            button: "J'ai compris",
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }
    //Si le login n'est pas correcte mais que le mdp est correcte
    else if (!testLogin) {
        Swal.fire({
            title: "Oh non une erreur",
            text: "Attention votre nom d'utilisateur n'est pas valide.",
            icon: "error",
            button: "J'ai compris",
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }
    //Si le login est correcte mais que le mdp n'est pas correcte
    else if (!testPass) {
        Swal.fire({
            title: "Oh non une erreur",
            text: "Attention votre mot de passe n'est pas valide. Il doit avoir au minimum 8 caractères ( lettres, chiffres, caractère spéciaux : @.-_& ).",
            icon: "error",
            button: "J'ai compris",
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    }
    return testLogin && testPass;
}
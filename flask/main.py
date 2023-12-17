from flask import (
    Flask,
    render_template,
    session,
    redirect,
    request,
    jsonify,
    send_file,
    url_for,
)
from api import callAuthApi, callDataApi
import logging

app = Flask(__name__)
app.secret_key = "azertyuiopqsdfghjklmwxcvbn"

logging.basicConfig(
    level=logging.DEBUG,
    format="%(asctime)s %(levelname)s: %(message)s [in %(pathname)s:%(lineno)d]",
)


def has_session() -> bool:
    return len(session) > 0


# index
@app.route("/")
def index():
    if not has_session():
        return redirect("connexion")
    return render_template("index.j2", sidebar=sidebar())


# connexion
@app.route("/connexion", methods=["POST", "GET"])
def connexion():
    if has_session():
        return redirect("index")

    if request.method == "GET":
        return render_template("connexion.j2", post=False)

    login = request.form.get("login")
    passw = request.form.get("pass")

    credits_ = {"login": login, "passwd": passw}

    res = callAuthApi("/auth/token?", data=credits_, method="POST")
    connected = res["status"] == 200
    app.logger.debug(res)
    if connected:
        session["login"] = login
        session["credits"] = credits_
        rep = callAuthApi("/auth/test", None, res["response"]["access_token"], "GET")
        payload = rep["response"]["payload"]
        session["statut"] = payload["statut"]

    return render_template("connexion.j2", connected=connected, post=True)


# déconnexion
@app.route("/deconnexion")
def deconnexion():
    had_session: bool = has_session()
    session.clear()
    return render_template("deconnexion.j2", had_session=had_session)


@app.errorhandler(404)
def page_404(_):
    return render_template("404.j2"), 404


# insertion
@app.route("/insertion", methods=["GET", "POST"])
def insertion():
    if not has_session():
        return redirect("connexion")

    if session.get("statut") != "admin":
        return redirect("index")

    # Définir les valeurs par défaut du formulaire

    formulaire = {
        "err": False,
        "nom": "",
        "prix": "",
        "mat": "",
        "type": "",
        "promo": False,
        "img": "",
    }

    if request.method == "POST":
        formulaire = {
            "err": False,
            "nom": request.form.get("nom", ""),
            "prix": request.form.get("prix", ""),
            "mat": request.form.get("selectMat", ""),
            "type": request.form.get("selectType", ""),
            "promo": request.form.get("promo") == "on",
            "img": request.form.get("filePath", ""),
        }

        if any(
            [
                not formulaire["nom"],
                not formulaire["prix"],
                not formulaire["type"],
                not formulaire["mat"],
            ]
        ):
            formulaire["err"] = True
        else:
            # Ajouter produit

            produit = {
                "nomp": formulaire["nom"],
                "prix": formulaire["prix"],
                "image": formulaire["img"],
                "type": formulaire["type"],
                "materiaux": formulaire["mat"],
                "promo": formulaire["promo"],
            }

            res = callDataApi("/produits", data=produit, method="POST")

            formulaire["success"] = res["status"] == 200

    rep = callDataApi("/catego_produit")
    if rep["status"] == 200:
        formulaire["cat"] = {
            "types": rep["response"]["types"],
            "materiaux": rep["response"]["materiaux"],
            "prix": rep["response"]["prix"],
        }

    return render_template("insertion.j2", form=formulaire, sidebar=sidebar())


# modification
@app.route("/modification")
def modification():
    if not has_session():
        return redirect("connexion")

    if session.get("statut") != "admin":
        return redirect("index")

    return render_template("modification.j2", sidebar=sidebar())


@app.route("/ajax", methods=["POST"])
def ajax_handler():
    data = request.json

    if data and "type" in data:
        response_content = None
        error = False

        if data["type"] == "getProducts":
            response = callDataApi("/produits")
            response_content = response if response else "Erreur API Data"

        elif data["type"] == "getUniqueProduct":
            response = callDataApi(f"/produits/{data['id']}")
            response_content = response if response else "Erreur SQL"

        elif data["type"] == "updateProduct":
            app.logger.debug(data)
            produit = {
                "nomp": data["product"]["nom"],
                "prix": data["product"]["prix"],
                "image": data["product"]["img"],
                "type": data["product"]["type"],
                "materiaux": data["product"]["mat"],
                "idp": data["product"]["id"],
                "promo": data["product"]["promo"],
            }

            response = callDataApi("/produits", produit, None, "PUT")
            response_content = (
                produit
                if response["status"] == 200
                else "Erreur API -> " + str(response["response"]["detail"])
            )

        elif data["type"] == "categoProduits":
            rep = callDataApi("/catego_produit")
            if rep["status"] == 200:
                ans = {
                    "types": rep["response"]["types"],
                    "materiaux": rep["response"]["materiaux"],
                    "prix": rep["response"]["prix"],
                }
                response_content = ans
            else:
                response_content = "Erreur SQL"

        else:
            response_content = "Le type donné n'est pas reconnu."

        return jsonify(
            {"type": "Error" if error else "Success", "content": response_content}
        )

    else:
        return (
            jsonify(
                {
                    "type": "Error",
                    "content": "Requête invalide, le paramètre Type est manquant.",
                }
            ),
            400,
        )


@app.route("/image/<src>")
def image(src: str):
    return send_file(
        "/code/app" + url_for("static", filename="img/" + src), mimetype="image/png"
    )


def sidebar():
    return render_template(
        "sidebar.j2",
        statut="Administrateur" if session.get("statut") == "admin" else "Utilisateur",
        login=session.get("login"),
    )


if __name__ == "__main__":
    app.run()

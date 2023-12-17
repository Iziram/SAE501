from flask import Flask, request, jsonify, render_template

app = Flask(__name__)


# index
@app.route("/")
def index():
    return render_template("base.j2")


# connexion
# déconnexion
# 404
# index
# insertion
# modification


@app.route("/flask-health-check")
def flask_health_check():
    """
    flask_health_check Vérifie le bon fonctionnement du serveur avec NGINX

    Returns:
        str: success
    """
    return "success"

import requests
import json
from flask import session

DATA_URL = "http://api-data:8080"
AUTH_URL = "http://api-auth:8081"


def _curl(url, method="GET", data=None, token=None):
    headers = {"Content-Type": "application/json"}

    if token is not None:
        headers["Authorization"] = f"Bearer {token}"

    response = None

    if method == "GET":
        response = requests.get(url, headers=headers)
    elif method == "POST":
        response = requests.post(url, headers=headers, json=data)
    elif method == "PUT":
        response = requests.put(url, headers=headers, json=data)
    elif method == "DELETE":
        response = requests.delete(url, headers=headers)

    if response is not None:
        return {"status": response.status_code, "response": response.json()}
    return {"status": 404, "response": "Error: No response"}


def callDataApi(path, data=None, token=None, method="GET"):
    # Gestion du token si non fourni
    if (
        token is None and "credits" in session
    ):  # Remplacer session par votre gestion de session
        credits_ = session["credits"]
        res = callAuthApi("/auth/token?", data=credits_, method="POST")
        if res["status"] == 200:
            token = res["response"]["access_token"]

    return _curl(DATA_URL + path, method, data, token)


def callAuthApi(path, data=None, token=None, method="GET"):
    return _curl(AUTH_URL + path, method, data, token)

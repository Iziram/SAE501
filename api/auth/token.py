"""
    Basé sur le TP2 Microservice BUT2 RT
"""
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from fastapi import Depends, HTTPException
import jwt

from .crud import get_compte
from .models import Compte
from datetime import datetime, timedelta

# Création d'un objet HTTPBearer pour gérer la sécurité via un token
security = HTTPBearer()
# clé à partager entre l'API et API de validation
# On peut générer une clé secrète avec openssl rand -hex 32
SECRET_KEY = "09d25e094faa6ca2556c818166b7a9563b93f7099f6f0f4caa6cf63b88e8d3e7"
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30


def createTokenAccess(db, login: str, password: str) -> str:
    compte: Compte = get_compte(db, login, password)

    if compte is None:
        raise HTTPException(status_code=403, detail="Invalid credentials")
    iat: int = datetime.now()
    payload = {
        "iss": "http://api-data",
        "sub": compte.login,
        "aud": "http://site",
        "iat": iat,
        "exp": iat + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES),
        "statut": compte.statut,
    }

    access_token = jwt.encode(payload, SECRET_KEY, algorithm=ALGORITHM)

    return {"access_token": access_token, "token_type": "bearer"}


def verifierTokenAccess(credits: HTTPAuthorizationCredentials = Depends(security)):
    token = credits.credentials  # on récupère le token
    print(token)
    try:
        payload = jwt.decode(  # on décode le token pour récupérer le payload
            token,
            SECRET_KEY,
            algorithms=["HS256"],
            options={"verify_aud": False, "verify_iat": False},
        )
        if payload is None:
            raise HTTPException(
                status_code=401, detail="Could not validate credentials"
            )
        print(payload["login"], payload["statut"])
        return payload  # on retourne le payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Token expired")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

from fastapi import Depends, FastAPI, HTTPException
from sqlalchemy.orm import Session

from . import crud, models, schemas
from .database import SessionDB

app = FastAPI()


def get_db():
    db = SessionDB()
    try:
        yield db
    finally:
        db.close()


@app.post("/auth")
def try_connection(user: schemas.Compte, db: Session = Depends(get_db)):
    user_logged: schemas.Compte = crud.get_compte(db, user.login, user.passwd)
    if user_logged is None:
        return HTTPException(status_code=403, detail="Invalid Credentials")

    return {
        "status_code": 200,
        "login": user_logged.login,
        "statut": user_logged.statut,
    }

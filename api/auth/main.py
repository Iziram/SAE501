from fastapi import Depends, FastAPI, HTTPException
from sqlalchemy.orm import Session

from . import crud, models, schemas
from .database import SessionDB
from .token import createTokenAccess, verifierTokenAccess

app = FastAPI()


def get_db():
    db = SessionDB()
    try:
        yield db
    finally:
        db.close()


@app.post("/auth/token")
def get_token(user: schemas.Compte, db: Session = Depends(get_db)):
    return createTokenAccess(db, user.login, user.passwd)


@app.get("/auth/test")
def verify_token(payload=Depends(verifierTokenAccess)):
    return {"payload": payload}

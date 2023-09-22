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
    return crud.get_compte(db, user.login, user.passwd) is not None

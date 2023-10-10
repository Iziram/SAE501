from fastapi import Depends, FastAPI, HTTPException
from sqlalchemy.orm import Session

from . import crud, models, schemas
from .database import SessionDB

from .token import verifierTokenAccess

app = FastAPI()


def get_db():
    db = SessionDB()
    try:
        yield db
    finally:
        db.close()


@app.get("/produits", response_model=list[schemas.Produit])
def get_produits(db: Session = Depends(get_db)):
    return [schemas.Produit.from_orm(pdt) for pdt in crud.get_produits(db)]


@app.get("/produits/{idP}", response_model=schemas.Produit)
def get_produit(idP: int, db: Session = Depends(get_db)):
    db_product = crud.get_produit(db, idp=idP)
    if db_product is None:
        raise HTTPException(status_code=404, detail="Produit inconnu")
    return schemas.Produit.from_orm(db_product)


@app.post("/produits", response_model=schemas.Produit)
def create_produit(
    produit: schemas.Produit,
    db: Session = Depends(get_db),
    payload=Depends(verifierTokenAccess),
):
    if payload is None or "statut" not in payload or payload["statut"] != "admin":
        raise HTTPException(
            status_code=403,
            detail="Vous ne possédez pas les droits nécessaires pour cette action",
        )
    db_product = crud.create_produit(db, produit=produit)
    return schemas.Produit.from_orm(db_product)


@app.put("/produits", response_model=schemas.Produit)
def update_produit(
    produit: schemas.Produit,
    db: Session = Depends(get_db),
    payload=Depends(verifierTokenAccess),
):
    if payload is None or "statut" not in payload or payload["statut"] != "admin":
        raise HTTPException(
            status_code=403,
            detail="Vous ne possédez pas les droits nécessaires pour cette action",
        )
    db_product = crud.update_produit(db, produit=produit)
    if not db_product:
        raise HTTPException(status_code=404, detail="Produit inconnu")
    return schemas.Produit.from_orm(db_product)


@app.delete("/produits/{idP}")
def delete_produit(
    idP: int,
    db: Session = Depends(get_db),
    payload=Depends(verifierTokenAccess),
):
    if payload is None or "statut" not in payload or payload["statut"] != "admin":
        raise HTTPException(
            status_code=403,
            detail="Vous ne possédez pas les droits nécessaires pour cette action",
        )
    db_product = crud.delete_produit(db, idP)
    if not db_product:
        raise HTTPException(status_code=404, detail="Produit inconnu")
    return "Produit Supprimé"

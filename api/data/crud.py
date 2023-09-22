from sqlalchemy.orm import Session
from . import models, schemas, database
from typing import Union

#  == Gestion des data ==


#  -- CREATE --
def create_produit(db: Session, produit: schemas.Produit) -> models.Produit:
    product: models.Produit = models.Produit(
        nomp=produit.nomp,
        prix=produit.prix,
        promo=produit.promo,
        type=produit.type,
        image=produit.image,
        materiaux=produit.materiaux,
    )

    db.add(product)
    db.commit()
    db.refresh(product)

    return product


#  --  READ  --


def get_produit(db: Session, idp: int) -> Union[models.Produit, None]:
    """
    get_produit Récupère un produit unique à partir de son id

    Args:
        db (Session): La session ORM SQL
        idp (int): L'identifiant du produit

    Returns:
        models.Produit | None: Un Objet produit ou None si inexistant
    """
    return db.query(models.Produit).filter_by(idp=idp).first()


def get_produits(db: Session) -> list[models.Produit]:
    """
    get_produits Récupère tous les produits

    Args:
        db (Session): La session ORM SQL
    Returns:
        list[models.Produit]: Une liste de produits
    """
    return db.query(models.Produit).all()


#  -- UPDATE --
def update_produit(
    db: Session, produit: schemas.Produit
) -> Union[models.Produit, False]:
    """
    update_produit Met à jour un produit

    Args:
        db (Session): Session ORM SQL
        produit (schemas.Produit): La nouvelle version du produit

    Returns:
        models.Produit | False: Faux si le produit n'existe pas, sinon le produit modifié
    """
    product: models.Produit = (
        db.query(models.Produit).filter_by(idp=produit.idp).first()
    )

    if not product:
        return False

    product.nomp = produit.nomp
    product.image = produit.image
    product.materiaux = produit.materiaux
    product.prix = produit.prix
    product.promo = produit.promo
    product.type = produit.type

    db.add(product)
    db.commit()
    db.refresh(product)
    return product


#  -- DELETE --
def delete_produit(db: Session, idP: int) -> bool:
    """
    delete_produit Supprime un produit à partir de son identifiant

    Args:
        db (Session): La Session ORM SQL
        idP (int): L'identifiant du produit

    Returns:
        bool: _description_
    """
    product: models.Produit = db.query(models.Produit).filter_by(idp=idP).first()

    if product:
        db.delete(product)
        db.commit()
        return True

    return False

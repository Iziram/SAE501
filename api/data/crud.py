from sqlalchemy.orm import Session
from sqlalchemy import distinct, func
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


def get_catego_produits(db: Session) -> dict:
    distinct_materiaux = db.query(distinct(models.Produit.materiaux)).all()
    distinct_types = db.query(distinct(models.Produit.type)).all()
    max_min_prix = db.query(
        func.max(models.Produit.prix).label("maxi"),
        func.min(models.Produit.prix).label("mini"),
    ).one()

    # Conversion des types en types Pythons simple
    materiaux_list = [materiau[0] for materiau in distinct_materiaux]
    types_list = [type_produit[0] for type_produit in distinct_types]
    prix_dict = {
        "maxi": float(max_min_prix.maxi) if max_min_prix.maxi is not None else None,
        "mini": float(max_min_prix.mini) if max_min_prix.mini is not None else None,
    }

    return {
        "types": types_list,
        "materiaux": materiaux_list,
        "prix": prix_dict,
    }


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

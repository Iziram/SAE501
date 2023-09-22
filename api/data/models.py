from sqlalchemy import Boolean, Column, Integer, String, Numeric

from .database import Base


class Produit(Base):
    __tablename__ = "produits"
    idp = Column(Integer, primary_key=True, autoincrement=True)
    nomp = Column(String)
    prix = Column(Numeric)
    type = Column(String, default="Bague")
    materiaux = Column(String, default="Or")
    promo = Column(Boolean, default=False)
    image = Column(String, default=None)

from sqlalchemy import Boolean, Column, Integer, String, Numeric

from .database import Base


class Compte(Base):
    __tablename__ = "Comptes"
    login = Column(String, primary_key=True)
    passwd = Column(String)
    statut = Column(String)

from pydantic import BaseModel


class Produit(BaseModel):
    idp: int | None
    nomp: str
    prix: float
    type: str
    materiaux: str
    promo: bool
    image: str | None

    class Config:
        orm_mode = True

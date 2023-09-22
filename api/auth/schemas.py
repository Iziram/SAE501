from pydantic import BaseModel


class Compte(BaseModel):
    login: str
    passwd: str
    statut: str | None

    class Config:
        orm_mode = True

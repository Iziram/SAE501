from sqlalchemy.orm import Session
from . import models, schemas, database
from typing import Union

#  == Gestion des data ==


def get_compte(db: Session, login: str, passwd: str) -> Union[models.Compte, None]:
    return db.query(models.Compte).filter_by(login=login, passwd=passwd).first()

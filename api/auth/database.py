from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

DATA_URL = "mariadb://iziram:1234@mariadb:3306/jawelry"


Base = declarative_base()

SessionDB = sessionmaker()
engine = create_engine(DATA_URL)
SessionDB.configure(bind=engine)

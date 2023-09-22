from sqlalchemy import create_engine
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker

DATA_URL = "postgresql+psycopg2://iziram:1234@psql:5432/jawelry"


Base = declarative_base()

SessionDB = sessionmaker()
engine = create_engine(DATA_URL)
SessionDB.configure(bind=engine)

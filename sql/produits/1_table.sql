CREATE TABLE Produits (
    idP SERIAL,
    NomP TEXT,
    Prix NUMERIC,
    type TEXT DEFAULT 'Bague',
    materiaux TEXT DEFAULT 'Or',
    Promo BOOLEAN DEFAULT FALSE,
    image TEXT DEFAULT NULL,
    PRIMARY KEY(idP)
);
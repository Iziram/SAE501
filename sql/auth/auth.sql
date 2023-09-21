USE jawelry;
CREATE TABLE IF NOT EXISTS Comptes (
    login varchar(128),
    pass TEXT,
    statut varchar(64),
    PRIMARY KEY(login)
);
INSERT INTO Comptes
VALUES('Benoit&amp;', 'azertyuiop', 'utilisateur');
INSERT INTO Comptes
VALUES('Admin*', 'qsdfghjklm', 'admin');
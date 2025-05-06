-- Suppression des anciennes tables
DROP TABLE IF EXISTS G19_Regroupent, G19_Plateforme, G19_Offre CASCADE;

-- Création des nouvelle table
CREATE TABLE G19_Plateforme
(
    plat_id SERIAL PRIMARY KEY,
    plat_nom VARCHAR(100) NOT NULL
);

CREATE TABLE G19_Offre
(
    offre_id SERIAL PRIMARY KEY,
    plat_id INTEGER,
    offre_nom VARCHAR(100) NOT NULL,
    offre_prix NUMERIC(100,2) NOT NULL,
    offre_code_ISO VARCHAR(3) DEFAULT 'EUR',
    offre_engagement INTEGER DEFAULT NULL,
    offre_audio BOOLEAN NOT NULL,
    offre_video BOOLEAN NOT NULL,
    FOREIGN KEY (plat_id) REFERENCES G19_Plateforme(plat_id) ON DELETE CASCADE,
    CHECK (offre_prix >= 0)
);

CREATE TABLE G19_Regroupent
(
    offre_mere_id INTEGER, 
    offre_fille_id INTEGER, 
    FOREIGN KEY (offre_mere_id) REFERENCES G19_Offre(offre_id) ON DELETE CASCADE,
    FOREIGN KEY (offre_fille_id) REFERENCES G19_Offre(offre_id) ON DELETE CASCADE,
    PRIMARY KEY (offre_mere_id, offre_fille_id)
);


-- Allimentation des tables 
INSERT INTO G19_Plateforme (plat_nom)
VALUES
    ('MyCanal'),
    ('Netflix'),
    ('Disney+'),
    ('Paramount+'),
    ('Apple TV+'),
    ('Spotify'),
    ('Amazon'),
    ('Deezer')
;

INSERT INTO G19_Offre (offre_nom, offre_prix, offre_engagement, offre_audio, offre_video, plat_id)
VALUES
    ('Standard avec pub',       5.99, 1, TRUE,TRUE,   (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Netflix')),
    ('Standard',                13.49, 1, TRUE,TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Netflix')),
    ('Premium',                 19.99, 1, TRUE,TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Netflix')),
    ('Standard avec pub',       5.99, 1, TRUE,TRUE,   (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Disney+')),
    ('Standard',                9.99, 1, TRUE,TRUE,   (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Disney+')),
    ('Standard annuel',         99.90, 12, TRUE,TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Disney+')),
    ('Premium',                 13.99, 1, TRUE,TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Disney+')),
    ('Premium annuel',          139.90, 12, TRUE,TRUE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Disney+')),
    ('Prime',                   6.99, 1, TRUE,TRUE,   (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Amazon')),
    ('Offre spéciale 40 ans',   40.00, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('Rat+ Ciné Series',        19.99, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('TV+',                     2.00, 1, TRUE, TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('Ciné+ OCS',               12.99, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('PMU',                     54.99, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('Bar/Restaurant/Commerce', 44.99, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal')),
    ('Sans Abonnement',         0.00, NULL,TRUE,FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Spotify')),
    ('Personnel',               11.12, 1, TRUE, FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Spotify')),
    ('Étudiants',               6.06, 1, TRUE, FALSE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Spotify')),
    ('Famille',                 18.21, 1, TRUE, FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Spotify')),
    ('Premium',                 11.99, 1, TRUE, FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Deezer')),
    ('Duo',                     15.99, 1, TRUE, FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Deezer')),
    ('Famille',                 19.99, 1, TRUE, FALSE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Deezer')),
    ('Apple TV+',               9.99, 1, TRUE, TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Apple TV+')),
    ('Standard',                7.99, 1, TRUE, TRUE,  (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Paramount+')),
    ('Standard annuel',         79.90, 12, TRUE, TRUE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Paramount+')),
    ('Premium',                 10.99, 1, TRUE, TRUE, (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Paramount+')),
    ('Premium annuel',          97.99, 12, TRUE, TRUE,(SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Paramount+'))
;

INSERT INTO G19_Regroupent (offre_mere_id, offre_fille_id)
VALUES
    (
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Rat+ Ciné Series' AND plat_id = ((SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal'))),
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Standard' AND plat_id = (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Netflix'))
    ),
    (
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Rat+ Ciné Series' AND plat_id = ((SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal'))),
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Standard' AND plat_id = (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Paramount+'))
    ),
    (
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Rat+ Ciné Series' AND plat_id = ((SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal'))),
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Apple TV+' AND plat_id = (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'Apple TV+'))
    ),
    (
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Rat+ Ciné Series' AND plat_id = ((SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal'))),
        (SELECT offre_id FROM G19_Offre WHERE offre_nom = 'Ciné+ OCS' AND plat_id = (SELECT plat_id FROM G19_Plateforme WHERE plat_nom = 'MyCanal'))
    )
;

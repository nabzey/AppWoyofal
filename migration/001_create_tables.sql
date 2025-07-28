-- Table client
CREATE TABLE IF NOT EXISTS client(
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(20),
    adresse VARCHAR(255)
);

-- Table compteur
CREATE TABLE IF NOT EXISTS compteur (
    id SERIAL PRIMARY KEY,
    numero VARCHAR(50) UNIQUE,
    client_id INTEGER REFERENCES clients(id)
);

-- Table tranche
CREATE TABLE IF NOT EXISTS tranche (
    id SERIAL PRIMARY KEY,
    libelle VARCHAR(50),
    prix_kw NUMERIC,
    limite_kw INTEGER
);

-- Table achat
CREATE TABLE IF NOT EXISTS achat (
    id SERIAL PRIMARY KEY,
    reference VARCHAR(50),
    code VARCHAR(50),
    nbre_kwt NUMERIC,
    date TIMESTAMP,
    tranche_id INTEGER REFERENCES tranches(id),
    prix_kw NUMERIC,
    client_id INTEGER REFERENCES client(id),
    compteur_id INTEGER REFERENCES compteur(id)
);

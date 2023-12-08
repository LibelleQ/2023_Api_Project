-- Table des utilisateurs
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL, 
);

-- Table des appartements
CREATE TABLE appartements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    superficie INT NOT NULL,
    nombre_personnes INT NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    disponibilite BOOLEAN NOT NULL DEFAULT true, 
    prix_nuit DECIMAL(10, 2) NOT NULL,
    proprietaire_id INT, 
    FOREIGN KEY (proprietaire_id) REFERENCES utilisateurs(id)
);

-- Table des réservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    prix_total DECIMAL(10, 2) NOT NULL,
    appartement_id INT,
    client_id INT, 
    FOREIGN KEY (appartement_id) REFERENCES appartements(id),
    FOREIGN KEY (client_id) REFERENCES utilisateurs(id)
);


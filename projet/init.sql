-- Création de la base de données
CREATE DATABASE apartment_project;

-- Utilisation de la base de données
\c apartment_project;

-- Création de la table des appartements
CREATE TABLE apartments (
    id SERIAL PRIMARY KEY,
    surface_area INT,
    capacity INT,
    address TEXT,
    availability BOOLEAN,
    night_price DECIMAL
);

-- Création de la table des réservations
CREATE TABLE reservations (
    id SERIAL PRIMARY KEY,
    start_date DATE,
    end_date DATE,
    customer_id INT,
    apartment_id INT,
    price DECIMAL
);

-- Création de la table des utilisateurs
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(50)
);

-- Création de la table des propriétaires
CREATE TABLE owners (
    id SERIAL PRIMARY KEY,
    user_id INT,
    apartment_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (apartment_id) REFERENCES apartments(id)
);

-- Création de la table des permissions
CREATE TABLE permissions (
    id SERIAL PRIMARY KEY,
    role VARCHAR(50),
    action VARCHAR(50)
);

-- Insertion des permissions par défaut
INSERT INTO permissions (role, action) VALUES
('admin', 'manage_apartments'),
('admin', 'manage_prices'),
('admin', 'manage_availability'),
('admin', 'manage_reservations'),
('customer', 'make_reservation'),
('customer', 'view_apartments'),
('intern', 'manage_apartments'),
('intern', 'manage_prices'),
('intern', 'manage_availability'),
('owner', 'block_apartment');

-- Création de la table des rôles des utilisateurs
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES permissions(id)
);

-- Insertion des rôles par défaut pour les utilisateurs
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- admin
(2, 2), -- customer
(3, 3), -- intern
(4, 4); -- owner


CREATE DATABASE apartment_project;
\c apartment_project;

CREATE TABLE apartments (
    id SERIAL PRIMARY KEY,
    surface_area INT,
    capacity INT,
    address TEXT,
    availability BOOLEAN,
    night_price DECIMAL
);
CREATE TABLE reservations (
    id SERIAL PRIMARY KEY,
    start_date DATE,
    end_date DATE,
    customer_id INT,
    apartment_id INT,
    price DECIMAL
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(50)
);

CREATE TABLE owners (
    id SERIAL PRIMARY KEY,
    user_id INT,
    apartment_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (apartment_id) REFERENCES apartments(id)
);

CREATE TABLE permissions (
    id SERIAL PRIMARY KEY,
    role VARCHAR(50),
    action VARCHAR(50)
);

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
CREATE TABLE user_roles (
    user_id INT,
    role_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (role_id) REFERENCES permissions(id)
);
INSERT INTO user_roles (user_id, role_id) VALUES
(1, 1), -- admin
(2, 2), -- customer
(3, 3), -- intern
(4, 4); -- owner

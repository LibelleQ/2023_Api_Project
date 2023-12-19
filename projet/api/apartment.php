<?php

include_once "exceptions.php";

class ApartmentModel {

    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=apartment_project user=user password=password");
            if ($this->connection == null) {
                throw new Exception("Could not connect to the database.");
            }
        } catch (Exception $e) {
            throw new HTTPException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getApartments(): array {
        $result = pg_query($this->connection, "SELECT * FROM apartments ORDER BY id");
        $apartments = [];

        if (!$result) {
            throw new HttpException(pg_last_error());
        }

        while ($row = pg_fetch_assoc($result)) {
            $apartments[] = $row;
        }

        return $apartments;
    }

    public function getApartment($id): mixed {
        $query = pg_prepare($this->connection, "getApartment", "SELECT * FROM apartments WHERE id = $1");
        $result = pg_execute($this->connection, "getApartment", [$id]);

        if (!$result) {
            throw new HttpException(pg_last_error());
        }

        $apartment = pg_fetch_assoc($result);

        if ($apartment == null) {
            throw new NotFoundException("Apartment not found.");
        }

        return $apartment;
    }

    public function deleteApartment($id): void {
        $query = pg_prepare($this->connection, "deleteApartment", "DELETE FROM apartments WHERE id = $1");
        $result = pg_execute($this->connection, "deleteApartment", [$id]);

        if (!$result) {
            throw new HttpException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Apartment not found.");
        }
    }

    public function addApartment($surface_area, $capacity, $address, $availability, $night_price): void {
        // Vérification de la connexion à la base de données
        if (!$this->connection) {
            throw new HttpException("Database connection is not established.");
        }
    
        // Requête préparée
        $query = "INSERT INTO apartments (surface_area, capacity, address, availability, night_price) VALUES ($1, $2, $3, $4, $5)";
        $preparedQuery = pg_prepare($this->connection, "addApartment", $query);
    
        // Vérification de la préparation de la requête
        if (!$preparedQuery) {
            throw new HttpException("Failed to prepare the database query.");
        }
    
        // Exécution de la requête
        $result = pg_execute($this->connection, "addApartment", [$surface_area, $capacity, $address, $availability, $night_price]);
    
        // Vérification de l'exécution de la requête
        if (!$result) {
            throw new HttpException("Failed to execute the database query.");
        }
    }
    

    public function updateApartment($id, $apartment_object): void {
        $query = "UPDATE apartments SET ";
        $query .= isset($apartment_object->surface_area) ? "surface_area = '$apartment_object->surface_area', " : "";
        $query .= isset($apartment_object->capacity) ? "capacity = '$apartment_object->capacity', " : "";
        $query .= isset($apartment_object->address) ? "address = '$apartment_object->address', " : "";
        $query .= isset($apartment_object->availability) ? "availability = " . ($apartment_object->availability ? 'true' : 'false') . ", " : "";
        $query .= isset($apartment_object->night_price) ? "night_price = '$apartment_object->night_price', " : "";

        // Supprimer la virgule finale si elle existe
        $query = rtrim($query, ", ");

        $query .= " WHERE id = $id";

        $result = pg_query($this->connection, $query);
        if (!$result) {
            throw new HttpException(pg_last_error());
        }
        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Apartment not found.");
        }
    }

    public function getApartmentReservations($apartmentId): array {
        $query = pg_prepare($this->connection, "getApartmentReservations", "SELECT * FROM reservations WHERE apartment_id = $1");
        $result = pg_execute($this->connection, "getApartmentReservations", [$apartmentId]);

        if (!$result) {
            throw new HttpException(pg_last_error());
        }

        $reservations = [];
        while ($row = pg_fetch_assoc($result)) {
            $reservations[] = $row;
        }

        return $reservations;
    }
}

class UserModel {
    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=apartment_project user=user password=password");
            if ($this->connection == null) {
                throw new Exception("Could not connect to the database.");
            }
        } catch (Exception $e) {
            throw new HTTPException("Database connection failed: " . $e->getMessage());
        }
    }

    public function addUser($username, $password, $role): void {
    // Vérifie si l'utilisateur avec le même "username" existe déjà
    if ($this->isUsernameExists($username)) {
        throw new HTTPException("Username already exists. Choose a different username.", 400);
    }

    // Si l'utilisateur n'existe pas, ajoutez-le à la base de données
    $query = pg_prepare($this->connection, "addUser", "INSERT INTO users (username, password, role) VALUES ($1, $2, $3)");
    $result = pg_execute($this->connection, "addUser", [$username, $password, $role]);

    if (!$result) {
        throw new HTTPException(pg_last_error(), 500);
    }
}
public function signinUser($username, $password): mixed {
    // Requête préparée pour vérifier l'utilisateur lors de la connexion
    $query = "SELECT * FROM users WHERE username = $1 AND password = $2";
    $preparedQuery = pg_prepare($this->connection, "signinUser", $query);

    // Vérification de la préparation de la requête
    if (!$preparedQuery) {
        throw new HttpException("Failed to prepare the database query.");
    }

    // Exécution de la requête
    $result = pg_execute($this->connection, "signinUser", [$username, $password]);

    // Vérification de l'exécution de la requête
    if (!$result) {
        throw new HttpException("Failed to execute the database query.");
    }

    $user = pg_fetch_assoc($result);

    if ($user == null) {
        throw new NotFoundException("Invalid username or password.");
    }

    return $user;
}

private function isUsernameExists($username): bool {
    $query = pg_prepare($this->connection, "checkUsername", "SELECT COUNT(*) FROM users WHERE username = $1");
    $result = pg_execute($this->connection, "checkUsername", [$username]);

    if (!$result) {
        throw new HTTPException(pg_last_error(), 500);
    }

    $count = pg_fetch_result($result, 0, 0);

    return $count > 0;
    }
}

class ReservationModel {

    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=apartment_project user=user password=password");
            if ($this->connection == null) {
                throw new Exception("Could not connect to the database.");
            }
        } catch (Exception $e) {
            throw new HTTPException("Database connection failed: " . $e->getMessage());
        }
    }

    public function addReservation($start_date, $end_date, $customer_id, $apartment_id, $price): void {
        // Vérification de la connexion à la base de données
        if (!$this->connection) {
            throw new HttpException("Database connection is not established.");
        }
    
        // Requête préparée pour l'ajout d'une réservation
        $query = "INSERT INTO reservations (start_date, end_date, customer_id, apartment_id, price) VALUES ($1, $2, $3, $4, $5)";
        $preparedQuery = pg_prepare($this->connection, "addReservation", $query);
    
        // Vérification de la préparation de la requête
        if (!$preparedQuery) {
            throw new HttpException("Failed to prepare the database query.");
        }
    
        // Exécution de la requête
        $result = pg_execute($this->connection, "addReservation", [$start_date, $end_date, $customer_id, $apartment_id, $price]);
    
        // Vérification de l'exécution de la requête
        if (!$result) {
            throw new HttpException("Failed to execute the database query.");
        }
        error_log("Reservation added successfully.");
    }
}
?>
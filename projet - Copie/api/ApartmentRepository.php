<?php

include_once "exceptions.php";
include_once "ApartmentModel.php";

class ApartmentRepository
{
    private $connection;

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
    public function getConnection() {
        return $this->connection;
    }


    public function getApartments(): array
    {
        $result = pg_query($this->connection, "SELECT * FROM apartments ORDER BY id");
        $apartments = [];

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }

        while ($row = pg_fetch_assoc($result)) {
            $apartments[] = $row;
        }

        return $apartments;
    }

    public function getApartment($id): mixed
    {
        $query = "SELECT * FROM apartments WHERE id = $1";
        $result = pg_query_params($this->connection, $query, [$id]);

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }

        $apartment = pg_fetch_assoc($result);

        if ($apartment == null) {
            throw new NotFoundException("Apartment not found.");
        }

        return $apartment;
    }

    public function deleteApartment($id): void
    {
        $query = "DELETE FROM apartments WHERE id = $1";
        $result = pg_query_params($this->connection, $query, [$id]);

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Apartment not found.");
        }
    }

    public function addApartment($surface_area, $capacity, $address, $availability, $night_price): void
    {
        $query = "INSERT INTO apartments (surface_area, capacity, address, availability, night_price) VALUES ($1, $2, $3, $4, $5)";
        $result = pg_query_params($this->connection, $query, [$surface_area, $capacity, $address, $availability, $night_price]);

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }
    }

    public function updateApartment($id, $apartment_object): void
    {
        $query = "UPDATE apartments SET ";
        $params = [];

        if (isset($apartment_object->surface_area)) {
            $query .= "surface_area = $1, ";
            $params[] = $apartment_object->surface_area;
        }
        // Ajoutez des clauses pour les autres champs...

        $query = rtrim($query, ", ");
        $query .= " WHERE id = $1";
        $params[] = $id;

        $result = pg_query_params($this->connection, $query, $params);

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Apartment not found.");
        }
    }


    public function getApartmentsByUserId($userId): array {
        $query = "SELECT * FROM apartments WHERE id IN (SELECT apartment_id FROM reservations WHERE customer_id = $1)";
        $result = pg_query_params($this->connection, $query, [$userId]);

        if (!$result) {
            throw new HTTPException(pg_last_error());
        }

        $apartments = [];

        while ($row = pg_fetch_assoc($result)) {
            $apartments[] = $row;
        }

        return $apartments;
    }

}
?>

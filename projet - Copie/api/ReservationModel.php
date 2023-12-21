<?php

include_once "exceptions.php";



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
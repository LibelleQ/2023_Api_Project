<?php
/*
Le rôle du repository est de gérer l'accès aux données, généralement la base de données. 
Cela inclut la création, la lecture, la mise à jour et la suppression (CRUD). 
*/
include_once 'ApartmentModel.php';

class ApartmentRepository {

    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=todo_db user=todo password=password");
            
            if ($this->connection === false) {
                throw new Exception("Could not connect to the database.");
            }
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    /*
    // Ajoutez ici des méthodes pour interagir avec la base de données en fonction de vos besoins
    // Par exemple, des méthodes pour récupérer des appartements, des réservations, etc.

    // Exemple de méthode pour exécuter une requête*/
    public function executeQuery($query) {
        $result = pg_query($this->connection, $query);

        if ($result === false) {
            throw new Exception("Query execution failed: " . pg_last_error($this->connection));
        }

        return $result;
    }


    
    public function getApartments(): array {
        $query = "SELECT * FROM appartements ORDER BY created_at DESC";
        $result = $this->query($query);

        $apartments = [];
        while ($row = pg_fetch_assoc($result)) {
            $apartments[] = new ApartmentModel($row['superficie'], $row['nombre_personnes'], $row['adresse'], $row['disponibilite'], $row['prix_nuit'], $row['id'], $row['created_at']);
        }

        return $apartments;
    }

    public function getApartment(int $id): ApartmentModel {
        $query = "SELECT * FROM appartements WHERE id = $1";
        $result = $this->query($query, $id);

        if (!$result) {
            throw new BDDException(pg_last_error());
        }

        $apartment = pg_fetch_assoc($result);

        if ($apartment == null) {
            throw new BDDNotFoundException("Apartment not found.");
        }

        return new ApartmentModel($apartment['superficie'], $apartment['nombre_personnes'], $apartment['adresse'], $apartment['disponibilite'], $apartment['prix_nuit'], $apartment['id'], $apartment['created_at']);
    }

    public function createApartment(ApartmentModel $apartmentObject): ApartmentModel {
        $query = "INSERT INTO appartements (superficie, nombre_personnes, adresse, disponibilite, prix_nuit) VALUES ($1, $2, $3, $4, $5) RETURNING id, superficie, nombre_personnes, adresse, disponibilite, prix_nuit, created_at";

        $result = $this->query($query, $apartmentObject->superficie, $apartmentObject->nombre_personnes, $apartmentObject->adresse, $apartmentObject->disponibilite, $apartmentObject->prix_nuit);

        $created = pg_fetch_assoc($result);
        return new ApartmentModel($created["superficie"], $created["nombre_personnes"], $created["adresse"], $created["disponibilite"], $created["prix_nuit"], $created["id"], $created["created_at"]);
    }

    public function updateApartment(int $id, ApartmentModel $apartmentObject): ApartmentModel {
        $values = [];

        $query = "UPDATE appartements SET ";

        if (isset($apartmentObject->superficie)) {
            $values[] = $apartmentObject->superficie;
            $query .= "superficie = $".sizeof($values);
        }

        if (isset($apartmentObject->nombre_personnes)) {
            $values[] = $apartmentObject->nombre_personnes;
            $query .= ", nombre_personnes = $".sizeof($values);
        }

        if (isset($apartmentObject->adresse)) {
            $values[] = $apartmentObject->adresse;
            $query .= ", adresse = $".sizeof($values);
        }

        if (isset($apartmentObject->disponibilite)) {
            $values[] = $apartmentObject->disponibilite;
            $query .= ", disponibilite = $".sizeof($values);
        }

        if (isset($apartmentObject->prix_nuit)) {
            $values[] = $apartmentObject->prix_nuit;
            $query .= ", prix_nuit = $".sizeof($values);
        }

        $query .= " WHERE id = $id RETURNING id, superficie, nombre_personnes, adresse, disponibilite, prix_nuit, created_at;";

        $result = $this->query($query, ...$values);
        if (!$result) {
            throw new BDDException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new BDDNotFoundException("Apartment not found.");
        }

        $modified = pg_fetch_assoc($result);

        return new ApartmentModel($modified['superficie'], $modified['nombre_personnes'], $modified['adresse'], $modified['disponibilite'], $modified['prix_nuit'], $modified['id'], $modified['created_at']);
    }

    public function deleteApartment(int $id): void {
        $query = "DELETE FROM appartements WHERE id = $1";
        $result = $this->query($query, $id);

        if (!$result) {
            throw new BDDException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new BDDNotFoundException("Apartment with ID ".$id." was not found.");
        }
    }
    
    
    
    
}

?>

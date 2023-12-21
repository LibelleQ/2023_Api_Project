<?php

include_once "ApartmentRepository.php";
include_once "exceptions.php";
include_once "ApartmentModel.php";
include_once "Request.php";
include_once "Response.php";

class ApartmentService {

    private $apartmentRepository;

    public function __construct() {
        $this->apartmentRepository = new ApartmentRepository();
    }

    public function getApartments(): array {
        return $this->apartmentRepository->getApartments();
    }

    public function getApartment(int $id): ApartmentModel {
        return $this->apartmentRepository->getApartment($id);
    }

    public function addApartment(stdclass $body): void {
        $query = "INSERT INTO apartments (surface_area, capacity, address, availability, night_price) VALUES ($1, $2, $3, $4, $5)";
    
        // Assurez-vous que les propriétés nécessaires sont présentes dans $body
        if (!isset($body->surface_area, $body->capacity, $body->address, $body->availability, $body->night_price)) {
            throw new ValidationException("Invalid request body");
        }
    
        // Utilisation des paramètres nommés pour les requêtes préparées
        $params = [
            'surface_area' => $body->surface_area,
            'capacity' => $body->capacity,
            'address' => $body->address,
            'availability' => $body->availability,
            'night_price' => $body->night_price,
        ];
    
        // Utilisation de pg_query_params pour la requête préparée
        $result = pg_query_params($this->apartmentRepository->getConnection(), $query, $params);
    
        if (!$result) {
            throw new HTTPException(pg_last_error());
        }
    }
    
      

    public function updateApartment(int $id, stdclass $body): void {
        if (sizeof(get_object_vars($body)) == 0) {
            throw new ValidationException("No apartment selected");
        }

        if (!isset($body->url)) {
            throw new ValidationException("Please provide a url");
        }

        return $this->apartmentRepository->updateApartment($id, new ApartmentModel($body->url));
    }

    function deleteApartment(int $id): void {
        $this->apartmentRepository->deleteApartment($id);
    }



    public function getApartmentsByUserId(int $userId): array {
        return $this->apartmentRepository->getApartmentsByUserId($userId);
    }


}
?>

<?php

/*
La couche de service contient la logique métier de votre application. 
Elle utilise les repositories pour accéder aux données. 
Par exemple, un service ApartmentService pourrait contenir des méthodes pour 
récupérer la liste des appartements, créer une nouvelle réservation, etc.
*/

include_once 'ApartmentModel.php';
include_once 'ApartmentRepository.php';

class ApartmentService {
    private $apartmentRepository;

    public function __construct(ApartmentRepository $apartmentRepository) {
        $this->apartmentRepository = $apartmentRepository;
    }


    function getApartments(): array {

        return $this->repository->getApartments();
    }

    function getApartment(int $id): ApartmentModel {
        
        return $this->repository->getApartment($id);
    }

    function createApartment($superficie, $nombre_personnes, $adresse, $disponibilite, $prix_nuit) {
        if ($superficie <= 0 || $prix_nuit <= 0) {
            throw new Exception("La superficie et le prix par nuit doivent être des nombres positifs.");
        }        
        if (empty($adresse)) {
            throw new Exception("L'adresse ne peut pas être vide.");
        }
        $this->apartmentRepository->createApartment($superficie, $nombre_personnes, $adresse, $disponibilite, $prix_nuit);
    }

    function deleteApartment(int $id): void {
        $this->repository->deleteApartment($id);
    }

    function updateApartment(): ApartmentModel {

    }
    // Ajoutez d'autres méthodes pour les opérations métier en fonction de vos besoins
}
?>

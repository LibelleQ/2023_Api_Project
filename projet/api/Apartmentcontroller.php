<?php
include_once 'ApartmentModel.php';
include_once 'ApartmentService.php';
include_once 'ApartmentRepository.php';

class ApartmentController {
    private $apartmentModel;
    private $apartmentService;
    private $apartmentRepository;

    public function __construct() {
        $this->apartmentModel = new ApartmentModel();
        $this->apartmentService = new ApartmentService();
        $this->apartmentRepository = new ApartmentRepository();
    }

    public function handleRequest($uri) {
        // Définissez ici la logique pour traiter les requêtes en fonction de l'URI
    }

    // Vous devriez définir les méthodes pour traiter les requêtes liées aux appartements ici
}
?>

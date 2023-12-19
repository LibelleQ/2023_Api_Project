<?php

// ApartmentService.php

include_once 'ApartmentModel.php';

class ApartmentService {
    private $apartmentModel;

    public function __construct() {
        $this->apartmentModel = new ApartmentModel();
    }

    // Vous devriez définir les méthodes pour la logique métier des appartements ici
}
?>

<?php 

// Ici, on crée des exceptions custom permettant de gérer les erreurs côté repository.
// custom'
// c'est ici que nous allons définir les exceptions liées aux interractions avec la BDD
class BDDException extends Exception {
    public function __construct($message = "An error occured with the database.") {
        parent::__construct($message);
    }
}

class BDDNotFoundException extends Exception {
    public function __construct($message = "Could not find object.") {
        parent::__construct($message);
    }
}

?>

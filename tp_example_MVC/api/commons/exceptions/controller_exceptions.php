<?php 

// Ici, on créer des exceptions custom permettant de gérer les erreurs HTTP
// De cette façon, on abstrait l'emission d'erreurs en retour des requêtes HTTP
// Dans le cadre d'une API, il est important que les utilisateurs ne se retrouvent pas face à des stacktraces d'erreurs

class HTTPException extends Exception {

    public function __construct($message = "An error occured.", $code = 500) {
        parent::__construct($message, $code);
    }
}


class NotFoundException extends HTTPException {
    public function __construct($message = "Not Found") {
        parent::__construct(message: $message, code: 404);
    }
}

class BadRequestException extends HTTPException {
    public function __construct($message = "Bad Request") {
        parent::__construct(message: $message, code: 400);
    }
}
?>

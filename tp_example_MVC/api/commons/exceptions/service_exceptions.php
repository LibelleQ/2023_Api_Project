<?php 

// Ici, on créer des exceptions custom permettant de gérer les erreurs côté service.
// Ici les exception n'ont pas de rapport avec les erreurs HTTP ou leur code d'erreur: autrement, pn parlerait de "fuite d'abstraction"

class ServiceException extends Exception {

    public function __construct($message = "An error occured.") {
        parent::__construct($message);
    }
}

class ValueTakenExcepiton extends ServiceException {
    public function __construct($message = "Can't create object: one of its value is taken already.") {
        parent::__construct(message: $message);
    }
}

class EntityNotFoundException extends ServiceException {
    public function __construct($message = "Not Found") {
        parent::__construct(message: $message);
    }
}

class ValidationException extends ServiceException {
    public function __construct($message = "Wrong data provided!") {
        parent::__construct(message: $message);
    }
}
?>

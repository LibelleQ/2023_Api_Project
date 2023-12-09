<?php
include_once 'musics/musics_controller.php';
include_once './commons/request.php';
include_once './commons/response.php';
include_once './commons/middlewares/json_middleware.php';
include_once './commons/exceptions/controller_exceptions.php';
// error_reporting(E_ERROR | E_PARSE);

class GeneralController {
    function dispatch (Request $req,Response $res): void {
        $res->setMessage("Welcome to the music API !");
    }
}

function router(Request $req, Response $res): void {
    $controller = null;
    switch($req->getPathAt(2)) {
        case(null):
            $controller = new GeneralController();
            break;

        case 'musics':
            $controller = new MusicsController();
            break;
        default:
            // Si la ressource demandée n'existe pas, alors on renvoie une erreur 404
            throw new NotFoundException("Ce point d'entrée n'existe pas !");
            break;
    }

        $controller->dispatch($req, $res);
}

// On instancie req et res
$req = new Request();
$res = new Response();

// Chainer les middlewares et le controlleur pouir les appeler tour a tour 
try {
    json_middleware($req, $res);

    router($req, $res);
} catch (NotFoundException | EntityNotFoundException | BDDNotFoundException $e) {
    $res->setMessage($e->getMessage(), 404);
} catch (ValidationException | ValueTakenExcepiton | BadRequestException $e) {
    $res->setMessage($e->getMessage(), 400);
} catch (Exception $e) {
    $res->setMessage("An error occured with the server.", 500);
}


// On envoie la réponse
$res->send();
?>

<?php
include_once 'ApartmentController.php';
include_once 'UserController.php';
include_once 'ReservationController.php';
include_once 'Request.php';
include_once 'Response.php';

class ApiController {
    public static function router(Request $req, Response $res): void {
        $controller = null;
        switch($req->getPathAt(2)) {
            case 'apartments':
                $controller = new ApartmentController();
                break;
            case 'reservations':
                $controller = new ReservationController();
                break;
            case 'signin':
                // signin_controller($uri);
                break;
            case 'users':
                $controller = new UserController();
                break;
            default:
                // Si la ressource demandée n'existe pas, alors on renvoie une erreur 404
                $res->setMessage("Not Found", 404);
                $res->send();
                exit();
        }

        // Appel de la fonction dispatch du contrôleur
        $controller->dispatch($req, $res);
    }

    public static function main(): void {
        $req = new Request();
        $res = new Response();

        // Chainer les middlewares et le contrôleur pour les appeler tour à tour 
        try {
            // Ajoutez vos middlewares ici si nécessaire

            self::router($req, $res);
        } catch (Exception $e) {
            $res->setMessage("An error occurred with the server.", 500);
        }

        // On envoie la réponse
        $res->send();
    }
}

ApiController::main();
?>

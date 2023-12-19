<?php
include_once 'apartment.php';

// Skipper les warnings, pour la production (vos exceptions devront être gérées proprement)
error_reporting(E_ERROR | E_PARSE);

// le contenu renvoyé par le serveur sera du JSON
header("Content-Type: application/json; charset=utf8");
// Autorise les requêtes depuis localhost
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS,PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// On récupère l'URI de la requête et on le découpe en fonction des /
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri); // On obtient un tableau de la forme ['index.php', 'apartments', '1']

// Si on a moins de 3 éléments dans l'URI, c'est que l'on est sur l'index de l'API
if (sizeof($uri) < 3) {
    header("HTTP/1.1 200 OK");
    echo '{"message": "Welcome to the API"}';
    exit();
}

// Ces fonctions nous permettent de centraliser la gestion des headers et du body de la réponse HTTP

function exit_with_message($message = "Internal Server Error", $code = 500) {
    http_response_code($code);
    echo '{"message": "' . $message . '"}';
    exit();
}

function exit_with_content($content = null, $code = 200) {
    http_response_code($code);
    echo json_encode($content);
    exit();
}

// Ajoutez une nouvelle condition pour gérer les utilisateurs
function controller($uri) {
    switch ($uri[2]) {
        case 'apartments':
            apartment_controller($uri);
            break;
        case 'reservations':
            reservation_controller($uri);
            break;
        case 'signin':
            signin_controller($uri);
            break;
        case 'users':
            user_controller($uri);
            break;
        default:
            // Si la ressource demandée n'existe pas, alors on renvoie une erreur 404
            header("HTTP/1.1 404 Not Found");
            echo '{"message": "Not Found"}';
            break;
    }
}


// Le contrôleur de la ressource apartment a pour responsabilité de gérer les requêtes relatives à la ressource apartment
function apartment_controller($uri) {
    $apartmentModel = new ApartmentModel();

    // redirection en fonction de la méthode HTTP
    switch ($_SERVER['REQUEST_METHOD']) {
        // Si on a une requête GET, on renvoie la liste des apartments ou un apartment en particulier
        case 'GET':
            if (sizeof($uri) == 4) {
                try {
                    $result = $apartmentModel->getApartment($uri[3]);
                } catch (HTTPException $e) {
                    exit_with_message($e->getMessage(), $e->getCode());
                }
            } else {
                $result = $apartmentModel->getApartments();
            }

            exit_with_content($result);
            break;

        // Si on a une requête POST, on ajoute un apartment
        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body);
        
            // Assurez-vous d'avoir les champs nécessaires pour un appartement
            $requiredFields = ['surface_area', 'capacity', 'address', 'availability', 'night_price'];
        
            foreach ($requiredFields as $field) {
                if (!isset($json->$field)) {
                    exit_with_message("Bad Request: $field is missing", 400);
                }
            }
        
            try {
                $result = $apartmentModel->addApartment(
                    $json->surface_area,
                    $json->capacity,
                    $json->address,
                    $json->availability,
                    $json->night_price
                );
                exit_with_message("Created!", 201);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            break;
        

        // Si on a une requête PATCH, on met à jour un apartment
        case 'PATCH':
            if (sizeof($uri) < 4) {
                exit_with_message("Bad Request", 400);
            }

            $body = file_get_contents("php://input");
            $json = json_decode($body);

            if (!isset($json->surface_area) && !isset($json->capacity) && !isset($json->address) && !isset($json->availability) && !isset($json->night_price)) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $apartmentModel->updateApartment($uri[3], $json);
                exit_with_message("Updated", 200);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }

            break;

        // Si on a une requête DELETE, on supprime un apartment
        case 'DELETE':
            if (sizeof($uri) < 4) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $apartmentModel->deleteApartment($uri[3]);
                exit_with_message("Deleted", 200);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }

            break;

        // On gère les requêtes OPTIONS pour permettre le CORS
        default:
            header("HTTP/1.1 200 OK");
            exit();
    }
}

function signin_controller($uri) {
    $userModel = new UserModel();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body);

            // Assurez-vous d'avoir les champs nécessaires pour la connexion
            $requiredFields = ['username', 'password'];

            foreach ($requiredFields as $field) {
                if (!isset($json->$field)) {
                    exit_with_message("Bad Request: $field is missing", 400);
                }
            }

            try {
                $result = $userModel->signinUser(
                    $json->username,
                    $json->password
                );
                exit_with_content($result);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            break;

        default:
            header("HTTP/1.1 200 OK");
            exit();
    }
}

// Le contrôleur de la ressource user a pour responsabilité de gérer les requêtes relatives à la ressource user
function user_controller($uri) {
    $userModel = new UserModel(); // Assurez-vous d'avoir une classe UserModel pour gérer les utilisateurs

    // redirection en fonction de la méthode HTTP
    switch ($_SERVER['REQUEST_METHOD']) {
        // Si on a une requête POST, on ajoute un utilisateur
        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body);

            if (!isset($json->username) || !isset($json->password) || !isset($json->role)) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $result = $userModel->addUser(
                    $json->username,
                    $json->password,
                    $json->role
                );
                exit_with_message("User Created!", 201);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            break;

        default:
            header("HTTP/1.1 200 OK");
            exit();
    }
}

function reservation_controller($uri) {
    $reservationModel = new ReservationModel();

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body);
    
            // Ajoutez cette ligne pour déboguer les données reçues
            var_dump($json);
    
            // Assurez-vous d'avoir les champs nécessaires pour une réservation
            $requiredFields = ['start_date', 'end_date', 'customer_id', 'apartment_id', 'price'];
    
            foreach ($requiredFields as $field) {
                if (!isset($json->$field)) {
                    exit_with_message("Bad Request: $field is missing", 400);
                }
            }
    
            try {
                $result = $reservationModel->addReservation(
                    $json->start_date,
                    $json->end_date,
                    $json->customer_id,
                    $json->apartment_id,
                    $json->price
                );
                exit_with_message("Reservation Created!", 201);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            break;

        default:
            header("HTTP/1.1 200 OK");
            exit();
    }
}

// On appelle le contrôleur principal
controller($uri);

return;
?>

<?php
include_once 'ApartmentModel.php';
include_once 'ApartmentService.php';
include_once 'ApartmentRepository.php';
include_once 'Request.php';
include_once 'Response.php';

function exit_with_message($message = "Internal Server Error", $code = 500, $res): void {
    http_response_code($code);
    $res->setMessage($message);
    $res->send();
    exit();
}

class ApartmentController {
    private $apartmentService;

    public function __construct() {
        $this->apartmentService = new ApartmentService();
    }

    public function dispatch(Request $req, Response $res): void {
        // Votre logique de traitement des requêtes ici
        // Utilisez $req pour accéder aux données de la requête et $res pour générer la réponse

        // Exemple: Obtenez le chemin dans la requête
        $uri = $req->getPathAt(2);

        // redirection en fonction de la méthode HTTP
        switch ($req->getMethod()) {
            // Si on a une requête GET, on renvoie la liste des apartments ou un apartment en particulier
            case 'GET':
                if ($uri !== null && $uri != "") {
                    try {
                        $result = $this->getApartment($uri);
                    } catch (HTTPException $e) {
                        exit_with_message($e->getMessage(), $e->getCode(), $res);
                    }
                } else {
                    $result = $this->getApartments();
                }

                $res->setCode(200);
                $res->setContent($result);
                $res->send();
                break;

            // Si on a une requête POST, on ajoute un apartment
            case 'POST':
                $body = $req->getBody();
                $json = json_decode($body);

                // Assurez-vous d'avoir les champs nécessaires pour un appartement
                $requiredFields = ['surface_area', 'capacity', 'address', 'availability', 'night_price'];

                foreach ($requiredFields as $field) {
                    if (!isset($json->$field)) {
                        exit_with_message("Bad Request: $field is missing", 400, $res);
                    }
                }

                try {
                    $result = $this->addApartment(
                        $json->surface_area,
                        $json->capacity,
                        $json->address,
                        $json->availability,
                        $json->night_price
                    );
                    $res->setCode(201);
                    $res->setMessage("Created!");
                    $res->send();
                } catch (HTTPException $e) {
                    exit_with_message($e->getMessage(), $e->getCode(), $res);
                }
                break;

            // Si on a une requête PATCH, on met à jour un apartment
            case 'PATCH':
                if ($uri === null || $uri == "") {
                    exit_with_message("Bad Request", 400, $res);
                }

                $body = $req->getBody();
                $json = json_decode($body);

                if (!isset($json->surface_area) && !isset($json->capacity) && !isset($json->address) && !isset($json->availability) && !isset($json->night_price)) {
                    exit_with_message("Bad Request", 400, $res);
                }

                try {
                    $this->updateApartment($uri, $json);
                    $res->setCode(200);
                    $res->setMessage("Updated");
                    $res->send();
                } catch (HTTPException $e) {
                    exit_with_message($e->getMessage(), $e->getCode(), $res);
                }
                break;

            // Si on a une requête DELETE, on supprime un apartment
            case 'DELETE':
                if ($uri === null || $uri == "") {
                    exit_with_message("Bad Request", 400, $res);
                }

                try {
                    $this->deleteApartment($uri);
                    $res->setCode(200);
                    $res->setMessage("Deleted");
                    $res->send();
                } catch (HTTPException $e) {
                    exit_with_message($e->getMessage(), $e->getCode(), $res);
                }
                break;

            // On gère les requêtes OPTIONS pour permettre le CORS
            default:
                $res->setCode(200);
                $res->send();
                exit();
        }
    }

    private function getApartments(): array { //ou array, à voir
        return $this->apartmentService->getApartments();
    }

    private function getApartment($id): mixed {
        return $this->apartmentService->getApartment($id);
    }

    private function addApartment($surface_area, $capacity, $address, $availability, $night_price): void {
        $this->apartmentService->addApartment($surface_area, $capacity, $address, $availability, $night_price);
    }

    private function updateApartment($id, $apartment_object): void {
        $this->apartmentService->updateApartment($id, $apartment_object);
    }

    private function deleteApartment($id): void {
        $this->apartmentService->deleteApartment($id);
    }
}
?>

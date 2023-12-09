<?php

/*Le contrôleur traite les entrées utilisateur, invoque la logique métier appropriée (service), puis renvoie la réponse à l'interface utilisateur. 
Dans le contexte d'une API web, les contrôleurs peuvent gérer les requêtes HTTP.
*/
include_once 'ApartmentModel.php';
include_once 'ApartmentService.php'

class ApartmentController {
    private $apartmentService;

    function __construct() {
        $this->apartmentService = new appartmentService();
    }

    function dispatch(Request $req, Response $res): void {
        switch($req->getMethod()) {
            case 'GET':
                if ($req->getPathAt(3) !== "" && is_string($req->getPathAt(3))) {
                    $res->setContent($this->getApartment($req->getPathAt(3)));
                } else {
                    $res->setContent($this->getApartments());
                }
                break;

            case 'POST':
                
                $result = $this->createApartment($req->getBody());
                $res->setContent($result);
                break;

            case 'PATCH':
                if ($req->getPathAt(3) === "") {
                    throw new BadRequestException("Please provide an ID for the property to modify.");
                }
                
                $result = $this->updateApartment($req->getPathAt(3), $req->getBody());
                $res->setContent($result, 200); 
                break;

            case 'DELETE':
                if ($req->getPathAt(3) === "") {
                    throw new BadRequestException("Please provide an ID for the property to delete.");
                }
                $this->deleteApartment($req->getPathAt(3));
                $res->setMessage("Successfuly deleted resource.", 200); 
                break;
        }
    } 


    function getApartment(): array {
        $result = $this->service->getApartment();
        return $result;
    }

    function getApartment(int $id): apartmentcModel {
        $result = $this->service->getApartment($id);
        return $result;    
    }

    public function __construct(ApartmentService $apartmentService) {
        $this->apartmentService = $apartmentService;
    }

    public function createApartment(stdClass $content): apartmentModel {
        $result = $this->service->createApartment();
        return $result;
    }

    function deleteApartment(int $id): void {
        $this->service->deleteApartment($id);
    }

    function updateApartment(int $id, stdClass $body): apartmentModel {
        return $this->service->updateApartment($id, $body);
    }
}

?>





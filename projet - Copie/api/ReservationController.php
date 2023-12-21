<?php
include_once 'ApartmentModel.php';
include_once 'ApartmentService.php';
include_once 'ApartmentRepository.php';

include_once 'ReservationModel.php';
include_once 'UserModel.php';
class ReservationController {
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
}

?>
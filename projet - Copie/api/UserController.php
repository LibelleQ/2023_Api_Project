<?php
include_once 'ApartmentModel.php';
include_once 'ApartmentService.php';
include_once 'ApartmentRepository.php';

include_once 'ReservationModel.php';
include_once 'UserModel.php';

class UserController {
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
}
?>
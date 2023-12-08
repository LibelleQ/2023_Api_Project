<?php
include_once 'todo.php';

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
$uri = explode( '/', $uri ); // On obtient un tableau de la forme ['index.php', 'todos', '1']

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


// Composant principal du controlleur: cette fonction agit comme un routeur en redirigeant les requêtes vers le bon controlleur
function controller($uri) {
    switch($uri[2]) {
        case 'todos':
            todo_controller($uri);
            break;
        default:
            // Si la ressource demandée n'existe pas, alors on renvoie une erreur 404
            header("HTTP/1.1 404 Not Found");
            echo "{\"message\": \"Not Found\"}";
            break;
    }
}



// Le controlleur de la ressource Todo a pour responsabilité de gérer les requêtes relatives à la ressource Todo
function todo_controller($uri) {
    $todoModel = new TodoModel();
    
    // redirection en fonction de la méthode HTTP
    switch($_SERVER['REQUEST_METHOD']) {
        // Si on a une requête GET, on renvoie la liste des todos ou un todo en particulier
        case 'GET':
            if (sizeof($uri) == 4) {
                try {
                    $result = $todoModel->getTodo($uri[3]);
                } catch (HTTPException $e) {
                    exit_with_message($e->getMessage(), $e->getCode());
                }
            } else {
                $result = $todoModel->getTodos();
            } 

            exit_with_content($result);
            break;

        // Si on a une requête POST, on ajoute un todo
        case 'POST':
            $body = file_get_contents("php://input");
            $json = json_decode($body);

            if (!isset($json->description)) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $result = $todoModel->addTodo($json->description);
                exit_with_message("Created!", 201);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            break;

        // Si on a une requête PUT, on met à jour un todo
        case 'PATCH':
            if (sizeof($uri) < 4) {
                exit_with_message("Bad Request", 400);
            }

            $body = file_get_contents("php://input");
            $json = json_decode($body);

            if (!isset($json->description) && !isset($json->done)) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $todoModel->updateTodos($uri[3], $json);
                exit_with_message("Updated", 200);
            } catch (HTTPException $e) {
                exit_with_message($e->getMessage(), $e->getCode());
            }
            
            break;

        // Si on a une requête DELETE, on supprime un todo
        case 'DELETE':
            if (sizeof($uri) < 4) {
                exit_with_message("Bad Request", 400);
            }

            try {
                $todoModel->deleteTodos($uri[3]);
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

// On appelle le controlleur principal
controller($uri);

return
?>

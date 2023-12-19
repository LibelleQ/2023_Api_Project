<?php

include_once 'ApartmentController.php';
include_once 'malwhare.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (sizeof($uri) < 3) {
    header("HTTP/1.1 200 OK");
    echo '{"message": "Welcome to the API"}';
    exit();
}

$apartmentController = new ApartmentController();
$apartmentController->handleRequest($uri);

return;
?>

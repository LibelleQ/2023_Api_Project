<?php 
include_once 'commons/request.php';
include_once 'commons/response.php';
include_once 'commons/exceptions/controller_exceptions.php';

function json_middleware(&$req, &$res) {
    // Ignorer les reques qui n'ont pas de données
    if ($req->getMethod() != "POST" && $req->getMethod() != "PATCH") {
        return;
    }

    // On vérifie que le content-type de la requête existe
    if (!isset($req->getHeaders()["Content-Type"])) {
        throw new BadRequestException("Content-Type is not defined!");
    }

    if ($req->getHeaders()["Content-Type"] != "application/json") {
        throw new BadRequestException("Content-Type for this API is supposed to be \"application/json\"", 400);
    }

    $parsed = json_decode($req->getBody());

    if (!is_object($parsed)) {
        throw new BadRequestException("Incorect JSON body." , 400);
    }

    $req->setBody($parsed);
}




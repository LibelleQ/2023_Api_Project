<?php


include_once 'musics_model.php';
include_once 'musics_service.php';
include_once './commons/request.php';
include_once './commons/response.php';

class MusicsController {
    private $service;    

    function __construct() {
        $this->service = new MusicsService();
    }
    
    function dispatch(Request $req, Response $res): void {
        switch($req->getMethod()) {
            case 'GET':
                if ($req->getPathAt(3) !== "" && is_string($req->getPathAt(3))) {
                    $res->setContent($this->getMusic($req->getPathAt(3)));
                } else {
                    $res->setContent($this->getMusics());
                }
                break;

            case 'POST':
                
                $result = $this->postMusic($req->getBody());
                $res->setContent($result);
                break;

            case 'PATCH':
                if ($req->getPathAt(3) === "") {
                    throw new BadRequestException("Please provide an ID for the music to modify.");
                }
                
                $result = $this->patchMusic($req->getPathAt(3), $req->getBody());
                $res->setContent($result, 200); 
                break;

            case 'DELETE':
                if ($req->getPathAt(3) === "") {
                    throw new BadRequestException("Please provide an ID for the music to delete.");
                }
                $this->deleteMusic($req->getPathAt(3));
                $res->setMessage("Successfuly deleted resource.", 200); 
                break;
        }
    } 

    function getMusics(): array {
        $result = $this->service->getMusics();
        return $result;
    }

    function getMusic(int $id): MusicModel {
        $result = $this->service->getMusic($id);
        return $result;    
    }

    function postMusic(stdClass $content): MusicModel {
        $result = $this->service->createMusic($content);
        return $result;
    }

    function deleteMusic(int $id): void {
        $this->service->deleteMusic($id);
    }

    function patchMusic(int $id, stdClass $body): MusicModel {
        return $this->service->updateMusic($id, $body);
    }
}

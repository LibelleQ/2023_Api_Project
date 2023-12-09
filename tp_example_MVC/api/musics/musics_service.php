<?php 

include_once 'musics_model.php';
include_once 'musics_repository.php';
include_once 'commons/exceptions/service_exceptions.php';

class MusicsService {
    private $repository;

    function __construct() {
        $this->repository = new MusicsRepository();

    }

    
    function getMusics(): array {

        return $this->repository->getMusics();
    }

    function getMusic(int $id): MusicModel {
        
        return $this->repository->getMusic($id);
    }

    function createMusic(stdClass $body): MusicModel {
        if (!isset($body->url)) {
            throw new ValidationException("Please provide an URL for your track !");
        }

        $existing = $this->repository->getMusicBy("url", $body->url);

        if ($existing) {
            throw new ValueTakenExcepiton("Music with URL \"".$body->url."\" already exists");
        }

        return $this->repository->createMusic(new MusicModel($body->url));
    }

    function updateMusic(int $id, stdClass $body): MusicModel {
        if (sizeof(get_object_vars($body)) == 0 ) {
            throw new ValidationException("Nothing to modify !");
        }

        if (!isset($body->url)) {
            throw new ValidationException("Please provide an URL for your track !");
        }

        return $this->repository->updateMusic($id, new MusicModel($body->url));
    }

    function deleteMusic(int $id): void {
       $this->repository->deleteMusic($id);
    }


}

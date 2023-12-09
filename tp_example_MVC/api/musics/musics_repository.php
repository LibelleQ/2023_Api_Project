<?php 
include_once 'musics_model.php';
include_once 'commons/exceptions/repository_exceptions.php';

class MusicsRepository {
    private $connection = null;

    function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=music_db user=music password=password");
            if (  $this->connection == null ) {
                throw new BDDException("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new BDDException("Could not connect db: ". $e->getMessage());
        }
    }

    private function query(string $query,string ...$args): PgSql\Result {
        $prepared = pg_prepare($this->connection, "", $query);
 
        if (!$prepared) {
            throw new BDDException(pg_last_error($this->connection));
        }

        $result = pg_execute($this->connection, "", $args);
    
        if (!$result) {
            throw new BDDException(pg_last_error($this->connection));
        }

        return $result;
    }

    /**
    * @return MusicModel[]
    */
    public function getMusics(): array {
        $query =  "SELECT * FROM musics ORDER BY created_at DESC";
        $result = $this->query($query);

        $musics = [];
        while ($row = pg_fetch_assoc($result)) {
           $musics[] = new MusicModel($row['url'], $row['id'], $row['created_at']);
        }

        return $musics;
    }

    public function getMusic(int $id): MusicModel {
        $query = "SELECT * FROM musics WHERE id = $1";
        $result = $this->query($query, $id);
        
        if (!$result) {
            throw new BDDException(pg_last_error());
        }

        $music = pg_fetch_assoc($result);

        if ($music == null) {
            throw new BDDNotFoundException("Music not found.");
        }

        return new MusicModel($music['url'], $music['id'], $music['created_at']);
    }

    public function getMusicBy(string $attribute, string $value): mixed {
        $query = "SELECT * FROM musics WHERE ".$attribute."=$1";
        
        $result = $this->query($query, $value);

        return pg_fetch_assoc($result);
    }

    public function deleteMusic(int $id): void {
        $query = "DELETE FROM musics WHERE id = $1";
        $result = $this->query($query, $id);

        if (!$result ) {
            throw new BDDException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new BDDNotFoundException("Music with ID ".$id." was not found.");
        }
    }

    
    public function createMusic(MusicModel $musicObject): MusicModel {
        $query = "INSERT INTO musics (url) VALUES ($1) RETURNING id, url, created_at";
        
        $result = $this->query($query, $musicObject->url);

        $created = pg_fetch_assoc($result);
        return new MusicModel($created["url"], $created["id"], $created["created_at"]);
    }

    
    public function updateMusic(int $id, MusicModel $music_object): MusicModel {
        $values = [];

        $query = "UPDATE musics SET ";

        if (isset($music_object->url)) {
            $values[] = $music_object->url;
            $query .= "url = $".sizeof($values);
        }

        $query .= " WHERE id = $id RETURNING id, url, created_at;";

        $result = $this->query($query, ...$values);
        if (!$result) {
            throw new BDDException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new BDDNotFoundException("Music not found.");
        }

        $modified = pg_fetch_assoc($result);

        return new MusicModel($modified['url'], $modified['id'], $modified['created_at']); 
    }
}


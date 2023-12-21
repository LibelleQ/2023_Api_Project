<?php 
include_once "exceptions.php";


class UserModel {
    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=apartment_project user=user password=password");
            if ($this->connection == null) {
                throw new Exception("Could not connect to the database.");
            }
        } catch (Exception $e) {
            throw new HTTPException("Database connection failed: " . $e->getMessage());
        }
    }

    public function addUser($username, $password, $role): void {
    // Vérifie si l'utilisateur avec le même "username" existe déjà
    if ($this->isUsernameExists($username)) {
        throw new HTTPException("Username already exists. Choose a different username.", 400);
    }

    // Si l'utilisateur n'existe pas, ajoutez-le à la base de données
    $query = pg_prepare($this->connection, "addUser", "INSERT INTO users (username, password, role) VALUES ($1, $2, $3)");
    $result = pg_execute($this->connection, "addUser", [$username, $password, $role]);

    if (!$result) {
        throw new HTTPException(pg_last_error(), 500);
    }
}
public function signinUser($username, $password): mixed {
    // Requête préparée pour vérifier l'utilisateur lors de la connexion
    $query = "SELECT * FROM users WHERE username = $1 AND password = $2";
    $preparedQuery = pg_prepare($this->connection, "signinUser", $query);

    // Vérification de la préparation de la requête
    if (!$preparedQuery) {
        throw new HttpException("Failed to prepare the database query.");
    }

    // Exécution de la requête
    $result = pg_execute($this->connection, "signinUser", [$username, $password]);

    // Vérification de l'exécution de la requête
    if (!$result) {
        throw new HttpException("Failed to execute the database query.");
    }

    $user = pg_fetch_assoc($result);

    if ($user == null) {
        throw new NotFoundException("Invalid username or password.");
    }

    return $user;
}

private function isUsernameExists($username): bool {
    $query = pg_prepare($this->connection, "checkUsername", "SELECT COUNT(*) FROM users WHERE username = $1");
    $result = pg_execute($this->connection, "checkUsername", [$username]);

    if (!$result) {
        throw new HTTPException(pg_last_error(), 500);
    }

    $count = pg_fetch_result($result, 0, 0);

    return $count > 0;
    }
}

?>
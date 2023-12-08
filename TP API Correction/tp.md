# BDD

Nous avons, lors de la dernière séance, créé un API minimal permettant de faire différentes formes de "hello world".

En l'utilisant comme exemple, nous allons faire un API minimaliste de TO-DO lists, avec de la persistance (De la sauvegarde de données).

Créez un nouveau dossier dans lequel vous copierez votre API minimale de la dernière fois.

Cette fois, dans le docker-compose, vous aurez ce contenu :

```yml
services:
  frontend:
    image: nginx:1.16.0-alpine
    ports:
      - 8080:80

    volumes:
      - ./front:/usr/share/nginx/html/

  api:
    build: .
    ports:
      - 8081:80
    volumes:
      - ./api:/var/www/html/

  database:
    image: postgres
    volumes:
      - db-data:/var/lib/postgresql/data/
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
    ports:
      - 5432:5432
    environment:
      - POSTGRES_PASSWORD=password
      - POSTGRES_DB=todo_db
      - POSTGRES_USER=todo

volumes:
  pgadmin-data:
  db-data:
```

Afin de connecter PHP avec postgres correctement, il est necessaire de faire en sorte que pgsql soit installé sur le docker contenant l'API.
Créez le fichier `Dockerfile` contenant ceci:

```dockerfile
FROM php:8-apache

RUN apt-get update

# PHP extensions
RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

```

D'après le changement sur le champ "build" de "API" plus haut, votre API peut maintenant interagir avec PGSQL car elle a les bons plugins Apache pour PHP et PGSQL.

## BDD Setup

Dans notre application, nous n'aurons qu'une table dans une base de données que nous appellerons toodos.

Créez init.sql et remplissez le avec ce code :

```sql
CREATE TABLE todos (
    id serial PRIMARY KEY,
    date_time timestamp,
    done boolean,
    description varchar(255)
);
```

Puis, pour que le SQL s'applique au prochain lancement, lancer la commande suivante :
`docker compose down --volumes`.

Nous avons donc une BDD avec une table "todos", contenant une clef primaire, une date de création, un booléen et une description.

## Routes

Reprenez le code de l'API de la dernière fois(index.php) pour la structure, et changez le code de façon à avoir 4 routes, qui pour le moment renvoient des messages "notimplemented":

- Créer un TO-DO (POST `/index.php/to-do`) -> {"message": "Not implemented !", "status": "501"}
- Supprimer un TO-DO (DELETE `/index.php/to-do`) -> {"message": "Not implemented !", "status": "501"}
- Lister les TO-DO (GET `/index.php/to-do`) -> {"message": "Not implemented !", "status": "501"}
- Modifier un TO-DO (PATCH `/index.php/to-do`) -> {"message": "Not implemented !", "status": "501"}

Les routes ne sont pas différenciées par leur chemin, mais par leur méthode.
Pour obtenir la méthode de la requête, on utilise `$_SERVER['REQUEST_METHOD']`.

Ajouter ces requêtes à votre client Insomnia, dans une nouvelle collection appelée "to-do"

## Model

Créez un fichier `todo.php` à côté de `index.php`, qui contiendra le modèle qui gère les to-do.

Voici une base de laquelle partir, à vous de la compléter :

```php
<?php

class TodoModel {

    private $connection = null;

    public function __construct() {
        try {
            $this->connection = pg_connect("host=database port=5432 dbname=todo_db user=todo password=password");
            if (  $this->connection == null ) {
                throw new Exception("Could not connect to database.");
            }
        } catch (Exception $e) {
            throw new HTTPException("Database connection failed :".$e->getMessage());
        }
    }

    /**
     * @return array
     * @throws HttpException
     */
    public function getTodos(): array {
            $result = pg_query($this->connection, "SELECT * FROM todos");
            $todos = [];

            if (!$result) {
                throw new HttpException(pg_last_error());
            }

            while ($row = pg_fetch_assoc($result)) {
                $todos[] = $row;
            }

            return $todos;
    }

    /**
     * @param $id
     * @return mixed
     * @throws HttpException
     * @throws NotFoundException
     */
    public function getTodo($id): mixed {
        // Implémentation
    }

    /**
     * @param $id
     * @return void
     * @throws HttpException
     */
    public function deleteTodos($id): void {
       // Implementation
    }

    /**
    * @param $id
    * @param $description
    * @return resource
    * @throws HttpException
    */
    public function addTodo($description): void {
        $date = date('Y-m-d H:i:s');
        $result = pg_query($this->connection, "INSERT INTO todos (done, description, date_time) VALUES (FALSE, '$description', '$date')");

        if (!$result) {
            throw new HttpException(pg_last_error());
        }

        return;
    }

    /**
     * @param $id
     * @param $todo_object
     * @return resource
     * @throws HttpException
     */
    public function updateTodos($id, $todo_object): void {
       // Implementation
    }
}



```

## Assemblage

Dans votre fichier index.php, vous vous retrouvez avec des conditions sur la requête. Reliez ces conditions aux fonctions de votre modèle, afin que :

Le endpoint avec "GET" renvoie les to-do,
Le endpoint avec "POST" Crée un to-do,
Le endpoint avec "PATCH" modifie un to-do,
et le endpoint avec "DELETE" supprime un to-do.

Pour récupérer la méthode, on utilise : `$_SERVER['REQUEST_METHOD']`
Pour récupérer le chemin, on utilise : `$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);`
Pour récupérer les paramètres URL, on utilise : `$_GET['monParam'];`
Pour récupérer le body depuis du json, on utilise :

```php
$body = file_get_contents("php://input");
$json = json_decode($body);
```

Pensez à la gestion d'érreur:

- On renvoie le statut 404 si la page n'existe pas
- On renvoie le statut 500 si une erreur non gérée s'est produite

On doit être explicite dans les messages.

Exemple :
GET http://localhost:8081/index.php/todos
STATUS 200

```json
[
  {
    "id": "6",
    "date_time": "2023-10-13 13:09:35",
    "done": "f",
    "description": "test"
  },
  {
    "id": "7",
    "date_time": "2023-10-13 13:09:45",
    "done": "f",
    "description": "Do the laundry"
  }
]
```

POST http://localhost:8081/index.php/todos
body :

```json
{
  "description": "Do the laundry"
}
```

response :
STATUS 201

```json
{
  "message": "Created"
}
```

## Frontend

Créez deux pages :

- index.html devient la page qui affiche les to-do

  - les checkboxes des to-do permettent de les activer/desactiver dans la BDD

- create.html permet de créer un to-do
  - Elle dispose d'un champ "description", et d'un bouton "créer"

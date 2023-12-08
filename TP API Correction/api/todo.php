<?php

include_once "exceptions.php";

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
            $result = pg_query($this->connection, "SELECT * FROM todos ORDER BY date_time DESC");
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
            $query = pg_prepare($this->connection, "getTodo", "SELECT * FROM todos WHERE id = $1");
            $result = pg_execute($this->connection, "getTodo", [$id]);
            
            if (!$result) {
                throw new HttpException(pg_last_error());
            }

            $todo = pg_fetch_assoc($result);

            if ($todo == null) {
                throw new NotFoundException("Todo not found.");
            }

            return $todo;
    }

    /**
     * @param $id
     * @return void
     * @throws HttpException
     */
    public function deleteTodos($id): void {
        $query = pg_prepare($this->connection, "deleteTodo", "DELETE FROM todos WHERE id = $1");
        $result = pg_execute($this->connection, "deleteTodo", [$id]);

        if (!$result ) {
            throw new HttpException(pg_last_error());
        }

        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Todo not found.");
        }
    }

    /**
    * @param $id
    * @param $description
    * @return resource
    * @throws HttpException
    */
    public function addTodo($description): void {
        $date = date('Y-m-d H:i:s');
        $query = pg_prepare($this->connection, "addTodo", "INSERT INTO todos (done, description, date_time) VALUES (FALSE, $1, $2)");
        $result = pg_execute($this->connection, "addTodo", [$description, $date]);

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
        $query = "UPDATE todos SET ";
        $query .= isset($todo_object->description) ? "description = '$todo_object->description' " : "";

        if (isset($todo_object->description) && isset($todo_object->done)) {
            $query .= ", ";
        }

        if (isset($todo_object->done)) {
            $query .= "done = "; 
            $query .= $todo_object->done ? "TRUE" : "FALSE" ;
        }

        $query .= " WHERE id = $id";

        $result = pg_query($this->connection, $query);
        if (!$result) {
            throw new HttpException(pg_last_error());
        }
        if (pg_affected_rows($result) == 0) {
            throw new NotFoundException("Todo not found.");
        }
    }
}



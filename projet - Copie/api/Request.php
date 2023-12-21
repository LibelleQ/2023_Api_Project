<?php

class Request
{
    private $method;
    private $path;
    private $params;
    private $headers;
    private $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->params = $_REQUEST;
        $this->headers = getallheaders();

        // Récupération du corps de la requête
        $this->body = file_get_contents('php://input');
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getPathAt($index)
    {
        $pathSegments = explode('/', trim($this->path, '/'));
        return isset($pathSegments[$index]) ? $pathSegments[$index] : null;
    }

    public function getBody()
    {
        return $this->body;
    }
}

?>

<?php 
class Request {
    private $method;
    private $url;
    private $body;
    private $headers;

    function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = $_SERVER['REQUEST_URI'];
        $this->body = file_get_contents("php://input");
        $this->headers = getallheaders();
    }

    function getMethod(): string {
        return $this->method;
    }

    function getUrl(): mixed {
        return $this->url;
    }

    function getBody(): mixed {
        return $this->body;
    }

    function getHeaders(): array {
        return $this->headers;
    }

    /**
    * @param int $index
    */
    function getPathAt($index): string | null {
        $uri = parse_url($this->url, PHP_URL_PATH);
        $uri = explode( '/', $uri ); 
        
        if (!isset($uri[$index])) { 
            return null;
        }
        return $uri[$index];
    }

    /**
    * @param mixed $body
    */
    function setBody($body): void {
        $this->body = $body;
    }

}


?>

<?php

class ApartmentModel {
    public $id;
    public $url;
    private $connection = null;

    /**
    * @param $id
    * @param $url
    */


    function __construct($url, $id = null) {
        $this->id = $id;
        $this->url = $url;
    }
}
?>
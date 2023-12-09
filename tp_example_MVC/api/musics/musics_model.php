<?php

# Cet objet sert à transporter les données d'une entité Music de facon standard et indépendante de http ou de la BDD.
class MusicModel {
    public $id;
    public $url;
    public $created_at;

   /**
    * @param $id
    * @param $url
    * @param $created_at
    */
    public
    function __construct($url, $id = null, $created_at = null) {
        

        $this->id = $id;
        $this->url = $url;
        $this->created_at = $created_at;
    }
}

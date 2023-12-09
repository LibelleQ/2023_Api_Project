<?php

# Cet objet sert à transporter les données d'une entité Apartment de façon standard et indépendante de HTTP ou de la BDD.
class ApartmentModel {
    public $id;
    public $superficie;
    public $nombre_personnes;
    public $adresse;
    public $disponibilite;
    public $prix_nuit;
    public $created_at;

   /**
    * @param $superficie
    * @param $nombre_personnes
    * @param $adresse
    * @param $disponibilite
    * @param $prix_nuit
    * @param $id
    * @param $created_at
    */
    public function __construct($superficie, $nombre_personnes, $adresse, $disponibilite, $prix_nuit, $id = null, $created_at = null) {
        $this->id = $id;
        $this->superficie = $superficie;
        $this->nombre_personnes = $nombre_personnes;
        $this->adresse = $adresse;
        $this->disponibilite = $disponibilite;
        $this->prix_nuit = $prix_nuit;
        $this->created_at = $created_at; // instant T de la création, ne doit pas être modifiable
    }
}

?>

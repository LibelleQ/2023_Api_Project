<?php
include_once('config.php');
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php')
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $superficie = $_POST['superficie'];
    $nombre_personnes = $_POST['nombre_personnes'];
    $adresse = $_POST['adresse'];
    $disponibilite = $_POST['disponibilite'];
    $prix_nuit = $_POST['prix_nuit'];
    $query = "INSERT INTO appartements (superficie, nombre_personnes, adresse, disponibilite, prix_nuit) 
              VALUES ('$superficie', '$nombre_personnes', '$adresse', '$disponibilite', '$prix_nuit')";
    if (mysqli_query($conn, $query)) {
        echo "Appartement ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'appartement : " . mysqli_error($conn);
    }
    mysqli_close($conn);
}
?>



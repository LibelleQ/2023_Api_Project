<?php
$servername = "votre_host";
$username = "votre_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "votre_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données: " . $conn->connect_error);
}
$searchTerm = $_GET['searchTerm'];

$queryUsers = "SELECT * FROM utilisateurs WHERE nom LIKE '%$searchTerm%'";
$resultUsers = $conn->query($queryUsers);
$users = array();

while ($row = $resultUsers->fetch_assoc()) {
    $users[] = $row;
}
$queryApartments = "SELECT * FROM appartements WHERE adresse LIKE '%$searchTerm%'";
$resultApartments = $conn->query($queryApartments);
$appartements = array();

while ($row = $resultApartments->fetch_assoc()) {
    $appartements[] = $row;
}

$queryReservations = "SELECT * FROM reservations WHERE id LIKE '%$searchTerm%'";
$resultReservations = $conn->query($queryReservations);
$reservations = array();

while ($row = $resultReservations->fetch_assoc()) {
    $reservations[] = $row;
}

$conn->close();
$response = array(
    'utilisateurs' => $users,
    'appartements' => $appartements,
    'reservations' => $reservations
);

header('Content-Type: application/json');
echo json_encode($response);
?>

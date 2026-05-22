<?php
///Connexion à la base de donnee
$host = "localhost";
$user = "root";
$password = "";
$database = "bdd_gestion";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Erreur connexion : " . mysqli_connect_error());
}




?>
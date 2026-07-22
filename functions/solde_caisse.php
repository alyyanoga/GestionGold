<?php

include("../functions/functions.php");

header('Content-Type: application/json');

$sql = "SELECT Solde
        FROM caisse
        LIMIT 1";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);
    exit;
}

$row = mysqli_fetch_assoc($result);

echo json_encode([
    "success" => true,
    "solde" => number_format($row['Solde'], 0, ',', ' ')
]);
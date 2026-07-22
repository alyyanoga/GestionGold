<?php

include("../functions/functions.php");

header('Content-Type: application/json');

if (!isset($_POST['txt_numero'])) {

    echo json_encode([
        "success" => false,
        "message" => "Client non reçu."
    ]);
    exit;
}

$compteClient = (int)$_POST['txt_numero'];

$sql = "SELECT Id
        FROM clients
        WHERE Numero = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $compteClient);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row) {

    echo json_encode([
        "success" => true,
        "id" => number_format($row['Id'], 0, ',', ' ')
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Client introuvable."
    ]);
}

mysqli_stmt_close($stmt);
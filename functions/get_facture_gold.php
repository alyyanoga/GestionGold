<?php
header('Content-Type: application/json; charset=utf-8');


include(__DIR__ . "/../functions/functions.php");



if ($rgmt == '') {
    echo json_encode([
        "success" => false,
        "message" => "Rgmt manquant"
    ]);
    exit;
}

// récupérer facture
$sql = "SELECT * FROM facture WHERE Rgmt = '$rgmt'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {

    $row = mysqli_fetch_assoc($result);

    // construire HTML facture
    $html = "
        <h3>Facture #{$row['Rgmt']}</h3>
        <p>Client: {$row['Client']}</p>
        <p>Montant: {$row['Montant']}</p>
        <p>Date: {$row['Date']}</p>
    ";

    echo json_encode([
        "success" => true,
        "html" => $html
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Facture introuvable"
    ]);
}

exit;
<?php

include("../functions/functions.php");

$sql = "SELECT * FROM temptransactionor";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);
    exit;
}

$insertCount = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $base = $row['Base'];
    $poids_air = $row['PoidsAir'];
    $poids_eau = $row['PoidsEau'];
    $densite = $row['Densite'];
    $carat = $row['Carat'];
    $prix_unitaire = $row['PrixUnitaire'];
    $montant = $row['Montant'];

    $rgmt = $row['Rgmt'];
    $date = $row['Dates'];
    $mouvement = $row['Mouvement'];
    $compteClient = $row['compteClient'];
    $nomClient = $row['NomClient'];
    $nomRepClient = $row['NomRepClient'];
    $nomUser = $row['NomUser'];
    $idClient = $row['idClient'];
    $telephone = $row['Telephone'];

    $sqlInsert = "INSERT INTO transaction_totale_or
    (
        Base,
        PoidsAir,
        PoidsEau,
        Densite,
        Carat,
        PrixUnitaire,
        Montant,
        Rgmt,
        Dates,
        Mouvement,
        compteClient,
        NomClient,
        NomRepClient,
        NomUser,
        idClient,
        Telephone
    )
    VALUES
    (
        '$base',
        '$poids_air',
        '$poids_eau',
        '$densite',
        '$carat',
        '$prix_unitaire',
        '$montant',
        '$rgmt',
        '$date',
        '$mouvement',
        '$compteClient',
        '$nomClient',
        '$nomRepClient',
        '$nomUser',
        '$idClient',
        '$telephone'
    )";

    if (mysqli_query($conn, $sqlInsert)) {
        $insertCount++;
    }
}

echo json_encode([
    'success' => true,
    'message' => "$insertCount opérations transférées"
]);

exit;
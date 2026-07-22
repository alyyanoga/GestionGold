<?php

include("../functions/functions.php");

$sql_brouillon = "SELECT
    AVG(Base) AS Base,
    SUM(PoidsAir) AS PoidsAir,
    SUM(PoidsEau) AS PoidsEau,
    AVG(Densite) AS Densite,
    AVG(Carat) AS Carat,
    SUM(Montant) AS Montant,

    MIN(Heure) AS Heure,
    MIN(Rgmt) AS Rgmt,
    MIN(Dates) AS Dates,
    MIN(Mouvement) AS Mouvement,
    MIN(compteClient) AS compteClient,
    MIN(NomClient) AS NomClient,
    MIN(NomRepClient) AS NomRepClient,
    MIN(NomUser) AS NomUser,
    MIN(idClient) AS idClient,
    MIN(Telephone) AS Telephone

FROM temptransactionor";

$result_brouillon = mysqli_query($conn, $sql_brouillon);

if (!$result_brouillon) {

    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);

    exit;
}

$row = mysqli_fetch_assoc($result_brouillon);

if (!$row || $row['PoidsAir'] == NULL) {

    echo json_encode([
        'success' => false,
        'message' => 'Aucune donnée dans la table temptransactionor'
    ]);

    exit;
}
$heure = $row['Heure'];
$base = $row['Base'];
$poids_air = $row['PoidsAir'];
$poids_eau = $row['PoidsEau'];
$densite = $row['Densite'];
$carat = $row['Carat'];
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

$sqlInsert = "INSERT INTO brouillon_achat_or
(
    Dates,
    ClientFournisseur,
    RepClientFournisseur,
    Quantites,
    Bases,
    Montants,
    Agents,
    Mouvements,
    CompteClient,
    Rgmt,
    PoidsEau,
    Densite,
    Carat,
    Telephone,
    idClient,
    Heure
)
VALUES
(
    '$date',
    '$nomClient',
    '$nomRepClient',
    '$poids_air',
    '$base',
    '$montant',
    '$nomUser',
    '$mouvement',
    '$compteClient',
    '$rgmt',
    '$poids_eau',
    '$densite',
    '$carat',
    '$telephone',
    '$idClient',
    '$heure'
)";

if (mysqli_query($conn, $sqlInsert)) {

    // vider la table temporaire après transfert
    mysqli_query($conn, "TRUNCATE TABLE temptransactionor");

    echo json_encode([
        'success' => true,
        'message' => 'Transfert effectué avec succès'
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);
}

exit;
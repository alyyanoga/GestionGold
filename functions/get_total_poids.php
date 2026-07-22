<?php

include("../functions/functions.php");

if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => $conn->connect_error
    ]));
}

$sql = "SELECT
            AVG(Base) AS moyenneBase,
            SUM(PoidsAir) AS totalPoidsAir,
            SUM(PoidsEau) AS totalPoidsEau,
            SUM(Montant) AS totalMontant
        FROM TempTransactionOr";

$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {

    echo json_encode([
        "success" => true,
        "moyenneBase" => (float)$row["moyenneBase"],
        "totalPoidsAir" => (float)$row["totalPoidsAir"],
        "totalPoidsEau" => (float)$row["totalPoidsEau"],
        "totalMontant" => (float)$row["totalMontant"]
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Aucune donnée."
    ]);

}

$conn->close();
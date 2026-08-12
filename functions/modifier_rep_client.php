<?php

header('Content-Type: application/json; charset=utf-8');

include(__DIR__ . "/../functions/functions.php");

$repClient = $_POST['txt_rep_client'];

$sql = "
    UPDATE temptransactionor
    SET NomRepClient = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $repClient
);

if($stmt->execute()){

    echo json_encode([
        "success" => true,
        "message" => "Mouvement mis à jour pour toutes les transactions",
        "mouvement" => $repClient
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => $stmt->error
    ]);
}

?>
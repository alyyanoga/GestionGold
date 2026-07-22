<?php

header('Content-Type: application/json; charset=utf-8');

include(__DIR__ . "/../functions/functions.php");

$mouvement = $_POST['txt_type_operation'];

$sql = "
    UPDATE temptransactionor
    SET Mouvement = ?
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $mouvement
);

if($stmt->execute()){

    echo json_encode([
        "success" => true,
        "message" => "Mouvement mis à jour pour toutes les transactions",
        "mouvement" => $mouvement
    ]);

}else{

    echo json_encode([
        "success" => false,
        "message" => $stmt->error
    ]);
}

?>
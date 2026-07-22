<?php
header('Content-Type: application/json; charset=utf-8');


include(__DIR__ . "/../functions/functions.php");

$nomClient    = $_POST['txt_nom_client'];
$nomRepClient = $_POST['txt_rep_client'];
$compteClient = $_POST['txt_numero_compte'];
$idClient     = $_POST['txtIdClient'];
$typeOperation =  $_POST['txt_type_operation'];


// Vérifier s'il existe une transaction temporaire
$sql = "SELECT COUNT(*) AS total FROM temptransactionor";

$result = $conn->query($sql);
$row = $result->fetch_assoc();


if($row['total'] > 0){

    // Modifier les données existantes
   $sqlUpdate = "
    UPDATE temptransactionor SET
        compteClient = ?,
        NomClient = ?,
        NomRepClient = ?,
        idClient = ?,
        Mouvement = ?
";

$stmt = $conn->prepare($sqlUpdate);

$stmt->bind_param(
    "sssis",
    $compteClient,
    $nomClient,
    $nomRepClient,
    $idClient,
    $typeOperation
);

if($stmt->execute()){

    echo json_encode([
        "success"=>true,
        "message"=>"Transaction temporaire mise à jour"
    ]);

}else{

    echo json_encode([
        "success"=>false,
        "message"=>$stmt->error
    ]);
}
}

?>
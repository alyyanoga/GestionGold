<?php
header('Content-Type: application/json; charset=utf-8');


include(__DIR__ . "/../functions/functions.php");

// 🔴 sécurisation POST
$numero = $_POST['txt_numero_compte'] ?? '';
$id_client = $_POST['txtIdClient'] ?? '';
$client = $_POST['txt_nom_client'] ?? '';
$rep_client = $_POST['txt_rep_client'] ?? '';
$type_operation = $_POST['txt_type_operation'] ?? '';
$regiment = $_POST['txt_rgmt'] ?? '';
$date = $_POST['txt_date'] ?? '';

$base = $_POST['txt_base'] ?? '';
$poids_air = $_POST['txt_poids_air'] ?? '';
$poids_eau = $_POST['txt_poids_eau'] ?? '';
$densite = $_POST['txt_densite'] ?? '';
$carat = $_POST['txt_carat'] ?? '';
$prix_unitaire = $_POST['txt_prix_unitaire'] ?? '';
$montant = $_POST['txt_montant'] ?? '';

$utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');

date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');

$mouvement = $type_operation;

// Vérifier si l'enregistrement existe déjà
$sql_verif = "SELECT Id
              FROM temptransactionor
              WHERE Rgmt = '$regiment'
              AND PoidsAir = '$poids_air'
              AND Carat = '$carat'
              LIMIT 1";

$result_verif = mysqli_query($conn, $sql_verif);

if (!$result_verif) {
    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);
    exit;
}

if (mysqli_num_rows($result_verif) > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Cette barre existe déjà pour ce règlement.'
    ]);
    exit;
}

// 🔴 requête
$sql = "INSERT INTO temptransactionor
(Base,PoidsAir,PoidsEau,Densite,Carat,PrixUnitaire,Montant,Rgmt,Dates,Mouvement,compteClient,NomClient,NomRepClient,NomUser,idClient,Heure)
VALUES
('$base','$poids_air','$poids_eau','$densite','$carat','$prix_unitaire','$montant','$regiment','$date','$mouvement','$numero','$client','$rep_client','$utilisateur','$id_client','$heure')";

if (mysqli_query($conn, $sql)) {

    $id = mysqli_insert_id($conn);

    echo json_encode([
        'success' => true,
        'id' => $id,   // 🔴 OBLIGATOIRE
        'base' => $base,
        'poids_air' => $poids_air,
        'poids_eau' => $poids_eau,
        'densite' => $densite,
        'carat' => $carat,
        'prix'=>$prix_unitaire,
        'montant'=>$montant,
        'regiment'=>$regiment,
        'date'=>$date,
        'type_operation'=>$mouvement,
        'client'=>$client,
        'rep_client'=>$rep_client,


    ]);
} else {

    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);
}

exit;
<?php
include("../functions/functions.php");

$regiment = $_POST['txt_rgmt'] ?? '';

if ($regiment == '') {

    echo json_encode([
        'success' => false,
        'message' => 'Régiment vide'
    ]);
    exit;
}

$sql = "INSERT INTO regiments(Numero) VALUES('$regiment')";

if (mysqli_query($conn, $sql)) {

    echo json_encode([
        'success' => true
    ]);

} else {

    echo json_encode([
        'success' => false,
        'message' => mysqli_error($conn)
    ]);
}

exit;
?>
<?php

header('Content-Type: application/json');
include(__DIR__ . "/../functions/functions.php");

$id = $_POST['id'] ?? 0;

if ($id > 0) {

    $sql = "DELETE FROM temptransactionor WHERE Id = $id";

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

} else {

    echo json_encode([
        'success' => false,
        'message' => 'ID invalide'
    ]);
}
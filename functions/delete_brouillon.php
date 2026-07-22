<?php

header('Content-Type: application/json');
include(__DIR__ . "/../functions/functions.php");

if (!isset($_POST['Rgmt'])) {
    echo json_encode([
        "success" => false,
        "message" => "Rgmt non reçu"
    ]);
    exit;
}

$Rgmt = (int) $_POST['Rgmt'];

if ($Rgmt <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "ID invalide"
    ]);
    exit;
}

// Activer les exceptions MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Début de la transaction
    mysqli_begin_transaction($conn);

    // Suppression dans transaction_totale_or
    $sql1 = "DELETE FROM transaction_totale_or WHERE Rgmt = ?";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, "i", $Rgmt);
    mysqli_stmt_execute($stmt1);
    mysqli_stmt_close($stmt1);

    // Suppression dans brouillon_achat_or
    $sql2 = "DELETE FROM brouillon_achat_or WHERE Rgmt = ?";
    $stmt2 = mysqli_prepare($conn, $sql2);
    mysqli_stmt_bind_param($stmt2, "i", $Rgmt);
    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

    // Validation
    mysqli_commit($conn);

    echo json_encode([
        "success" => true,
        "message" => "Suppression effectuée avec succès."
    ]);

} catch (Exception $e) {

    // Annulation en cas d'erreur
    mysqli_rollback($conn);

    echo json_encode([
        "success" => false,
        "message" => "Erreur : " . $e->getMessage()
    ]);
}
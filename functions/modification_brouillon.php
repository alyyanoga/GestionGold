<?php

header('Content-Type: application/json');
include("../functions/functions.php");

// Activer les exceptions MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_POST['Rgmt'])) {
    echo json_encode([
        "success" => false,
        "message" => "Rgmt non reçu"
    ]);
    exit;
}

$Rgmt = (int)$_POST['Rgmt'];

if ($Rgmt <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Règlement invalide."
    ]);
    exit;
}

try {

    // Début de la transaction
    mysqli_begin_transaction($conn);

    // Vider la table temporaire
    mysqli_query($conn, "DELETE FROM temptransactionor");

    // Copier les opérations dans la table temporaire
    $sql = "INSERT INTO temptransactionor
            SELECT *
            FROM transaction_totale_or
            WHERE Rgmt = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $Rgmt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Récupérer les informations du client
    $sqlClient = "SELECT
                    t.NomClient,
                    t.NomRepClient,
                    t.Rgmt,
                    t.Mouvement,
                    t.compteClient,
                    c.Solde
                  FROM transaction_totale_or t
                  INNER JOIN clients c
                    ON t.compteClient = c.Numero
                  WHERE t.Rgmt = ?
                  LIMIT 1";

    $stmt = mysqli_prepare($conn, $sqlClient);
    mysqli_stmt_bind_param($stmt, "i", $Rgmt);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$row) {
        throw new Exception("Aucune opération trouvée.");
    }

    // Supprimer de transaction_totale_or
    $sqlDelete = "DELETE FROM transaction_totale_or
                  WHERE Rgmt = ?";

    $stmt = mysqli_prepare($conn, $sqlDelete);
    mysqli_stmt_bind_param($stmt, "i", $Rgmt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Supprimer de brouillon_achat_or
    $sqlDelete2 = "DELETE FROM brouillon_achat_or
                   WHERE Rgmt = ?";

    $stmt = mysqli_prepare($conn, $sqlDelete2);
    mysqli_stmt_bind_param($stmt, "i", $Rgmt);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Validation
    mysqli_commit($conn);

    echo json_encode([
        "success"        => true,
        "client"         => $row['NomClient'],
        "rep_client"     => $row['NomRepClient'],
        "rgmt"           => $row['Rgmt'],
        "type_operation" => $row['Mouvement'],
        "numero_compte"  => $row['compteClient'],
        "solde_client" => number_format($row['Solde'], 0, ',', ' ')
    ]);

} catch (Exception $e) {

    mysqli_rollback($conn);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}
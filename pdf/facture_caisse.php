<?php

require('fpdf.php');
include(__DIR__ . "/../functions/functions.php");


$heure = date('H:i:s');
$date = date('Y-m-d');


$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);

$pdf->Image('../assets/Icone/Billet.png',5,5,20,20);
$pdf->Image('../assets/Icone/or.jpg',182,5,20,20);
$pdf->SetFont('Arial','B',22);
$pdf->SetX(55);
$pdf->Cell(100,10,'DIALLO SERVICE',1,1,'C');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,"TRANSFERT D'ARGENT - ACHAT ET VENTE D'OR",0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,2," CHEICK ALMAMY GUIDJO - FACE MOSQUEE YACOUBA GUINDO BAMAKO - MALI",0,1,'C');
$pdf->Cell(190,8,"Tel : (00223) 74 87 54 22 // 72 19 14 65 // 93 31 11 41 // 74 05 94 36",0,1,'C');

$pdf->Ln(2);

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 0,'Date: '. $date, 0, 1, 'C');
$pdf->SetX(110);
$pdf->Cell(0, 0, 'Heure '. $heure, 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 25, 'LISTE DES OPERATIONS DE CAISSE', 0, 1, 'C');

$pdf->Ln(5);

// En-têtes
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(25, 8, 'Date', 1);
$pdf->Cell(65, 8, 'Client', 1);
$pdf->Cell(30, 8, 'Debit', 1);
$pdf->Cell(30, 8, 'Credit', 1);
$pdf->Cell(30, 8, 'Solde', 1);

$pdf->Ln();

$sql = "SELECT * FROM mouvement_caisse ORDER BY Id DESC";
$result = mysqli_query($conn, $sql);

$pdf->SetFont('Arial', '', 9);

$totalDebit = 0;
$totalCredit = 0;

$solde_caisse = dernier_solde_caisse($conn);

while ($row = mysqli_fetch_assoc($result)) {

    $totalDebit += $row['Debit'];
    $totalCredit += $row['Credit'];

   
    $pdf->Cell(25, 7, $row['Date'], 1);
    $pdf->Cell(65, 7, utf8_decode($row['Client']), 1);
    $pdf->Cell(30, 7, number_format($row['Debit'], 0, ',', ' '), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($row['Credit'], 0, ',', ' '), 1, 0, 'R');
    $pdf->Cell(30, 7, number_format($row['Solde'], 0, ',', ' '), 1, 0, 'R');

    $pdf->Ln();
}

// Totaux
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(90, 8, 'TOTAL', 1, 0,);
$pdf->Cell(30, 8, number_format($totalDebit, 0, ',', ' '), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($totalCredit, 0, ',', ' '), 1, 0, 'R');
$pdf->Cell(30, 8, number_format($solde_caisse, 0, ',', ' '), 1, 0, 'R');

$pdf->Output();

?>
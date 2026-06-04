<?php

require('fpdf.php');
include(__DIR__ . "/../functions/functions.php");

$id = $_GET['Id'];

$sql = "SELECT * FROM mouvement_totales WHERE Id = '$id'";
$result = mysqli_query($conn, $sql);

$data = mysqli_fetch_assoc($result);

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
$pdf->SetX(140);
$pdf->Cell(20,4,'Regiment : ',0,0);
$pdf->Cell(100,4,$data['Regiment'],0,1);
$pdf->SetX(140);
$pdf->Cell(12,10,'Date : ',0,0);
$pdf->Cell(20,10,date('d/m/Y', strtotime($data['Date'])).' '.$data['Heure'],0,1);
$pdf->SetX(140);
$pdf->Cell(13,5,'Agent : ',0,0);
$pdf->Cell(100,5,$data['Utilisateur'],0,1);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',18);
$pdf->SetX(12);
$pdf->Cell(180,8,"RECU DE TRANSACTION","BT",1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,'Nom Client : ',0,0);
$pdf->Cell(100,8,$data['Client'],0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,6,'Beneficiaire : ',0,0);
$pdf->Cell(100,6,$data['Nom_R_client'],0,1);
$pdf->SetX(12);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,8,"DETAILS OPERATIONS","BT",1,'C');

$pdf->SetFont('Arial','B',11);
$pdf->Cell(35,9,'Type Operation : ',0,0);
$pdf->Cell(0,9,$data['Transactions'],0,1);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,3,'Montant $$$ : ',0,0);
$pdf->SetX(35);
$pdf->Cell(0,3, number_format($data['Quantite'],0,' ',' '),0,1);
$pdf->SetX(80);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,-3,'Taux : ',0,0);
$pdf->SetX(95);
$pdf->Cell(0,-3,$data['Prix'],0,1);
$pdf->SetX(130);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,3,'Montant XOF : ',0,0);
$pdf->SetX(160);
$pdf->Cell(0,3,number_format($data['Montant_retrait'],0,' ',' ') . ' FCFA',0,1);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,10,'En lettre : ',0,0);
$pdf->Cell(100,10,convertir_en_lettres($data['Montant_retrait']).' francs CFA',0,1);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(32,15,'Signature Agent: ',0,0);
$pdf->Cell(20,15,'___________________',0,1);
$pdf->SetX(118);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(32,-15,'Signature Client: ',0,0);
$pdf->Cell(20,-15,'___________________',0,1);

$pdf->Ln(5);

$pdf->Cell(190,20,'Merci pour votre confiance',0,1,'C');
/**--------DEUXIEME FACTURE--------- */
$pdf->Ln(8);
$pdf->SetFont('Arial','B',16);

$pdf->Image('../assets/Icone/Billet.png',5,145,20,20);
$pdf->Image('../assets/Icone/or.jpg',182,145,20,20);
$pdf->SetFont('Arial','B',22);
$pdf->SetX(55);
$pdf->Cell(100,10,'DIALLO SERVICE',1,1,'C');
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,"TRANSFERT D'ARGENT - ACHAT ET VENTE D'OR",0,1,'C');
$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,2," CHEICK ALMAMY GUIDJO - FACE MOSQUEE YACOUBA GUINDO BAMAKO - MALI",0,1,'C');
$pdf->Cell(190,8,"Tel : (00223) 74 87 54 22 // 72 19 14 65 // 93 31 11 41 // 74 05 94 36",0,1,'C');

$pdf->Ln(2);
$pdf->SetX(140);
$pdf->Cell(20,4,'Regiment : ',0,0);
$pdf->Cell(100,4,$data['Regiment'],0,1);
$pdf->SetX(140);
$pdf->Cell(12,10,'Date : ',0,0);
$pdf->Cell(20,10,date('d/m/Y', strtotime($data['Date'])).' '.$data['Heure'],0,1);
$pdf->SetX(140);
$pdf->Cell(13,5,'Agent : ',0,0);
$pdf->Cell(100,5,$data['Utilisateur'],0,1);
$pdf->Ln(4);
$pdf->SetFont('Arial','B',18);
$pdf->SetX(12);
$pdf->Cell(180,8,"RECU DE TRANSACTION","BT",1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,8,'Nom Client : ',0,0);
$pdf->Cell(100,8,$data['Client'],0,1);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,6,'Beneficiaire : ',0,0);
$pdf->Cell(100,6,$data['Nom_R_client'],0,1);
$pdf->SetX(12);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(180,8,"DETAILS OPERATIONS","BT",1,'C');
$pdf->SetFont('Arial','B',11);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(35,6,'Type Operation : ',0,0);
$pdf->Cell(0,6,$data['Transactions'],0,1);

$pdf->Cell(25,8,'Montant $$$ : ',0,0);
$pdf->Cell(0,8, number_format($data['Quantite'],0,' ',' '),0,1);

$pdf->SetX(80);
$pdf->Cell(15,-10,'Taux : ',0,0);
$pdf->Cell(0,-10,$data['Prix'],0,1);

$pdf->SetX(130);
$pdf->Cell(30,10,'Montant XOF : ',0,0);
$pdf->Cell(0,10,number_format($data['Montant_retrait'],0,' ',' ') . ' FCFA',0,1);

$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,4,'En lettre : ',0,0);
$pdf->Cell(100,4,convertir_en_lettres($data['Montant_retrait']).' francs CFA',0,1);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(32,18,'Signature Agent: ',0,0);
$pdf->Cell(20,18,'___________________',0,1);
$pdf->SetX(118);
$pdf->SetFont('Arial','B',11);
$pdf->Cell(32,-15,'Signature Client: ',0,0);
$pdf->Cell(20,-15,'___________________',0,1);

$pdf->Ln(10);

$pdf->Cell(190,10,'Merci pour votre confiance',0,1,'C');

$pdf->Output();
?>
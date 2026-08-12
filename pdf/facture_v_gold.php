
<?php

function txt($texte)
{
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $texte);
}

function formatSansArrondi($valeur, $decimales = 2)
{
    $facteur = pow(10, $decimales);
    $valeur = floor($valeur * $facteur) / $facteur;

    return number_format($valeur, $decimales, '.', ' ');
}


require('fpdf.php');
include(__DIR__ . "/../functions/functions.php");

date_default_timezone_set('Africa/Bamako');
$heure = date('H:i:s');
$date  = date('d-m-Y');

$rgmt = $_GET['rgmt'] ?? '';

if ($rgmt == '') {
    die("Numéro de règlement introuvable.");
}

$sql = "SELECT * FROM transaction_totale_or WHERE Rgmt = '$rgmt'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die(mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("Aucune facture trouvée.");
}

// Première ligne (pour les informations d'entête)
$info = mysqli_fetch_assoc($result);

// Revenir au début du résultat
mysqli_data_seek($result, 0);

$pdf = new FPDF();
$pdf->AddPage();

$pdf->Image('../assets/Icone/Billet.png',5,5,20,20);
$pdf->Image('../assets/Icone/or.png',182,5,20,20);

$pdf->SetFont('Arial','B',22);
$pdf->SetX(55);
$pdf->Cell(100,10,'DIALLO SERVICE',1,1,'C');

$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,"TRANSFERT D'ARGENT - ACHAT ET VENTE D'OR",0,1,'C');

$pdf->SetFont('Arial','B',10);
$pdf->Cell(190,5,"CHEICK ALMAMY GUIDJO - FACE MOSQUEE YACOUBA GUINDO BAMAKO - MALI",0,1,'C');
$pdf->Cell(190,5,"Tel : (00223) 74 87 54 22 / 72 19 14 65 / 93 31 11 41 / 74 05 94 36",0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',11);
$pdf->SetX(110);
$pdf->Cell(12,6,"Date :",0,0);
$pdf->Cell(25,6,$date,0,0);
$pdf->Cell(15,6,"Heure :",0,0);
$pdf->Cell(10,6,$heure,0,0);
$pdf->SetX(10);
$pdf->Cell(40,6,txt("Facture-".$info['Mouvement']." N°"),0,0);
$pdf->Cell(50,6,$info['Rgmt'],0,0);
$pdf->Ln(8);
$pdf->SetX(10);
$pdf->Cell(15,6,txt("Agent :"),0,0);
$pdf->SetX(50);
$pdf->Cell(80,6,$info['NomUser'],0,2);
$pdf->SetX(110);
$pdf->Ln(2);
$pdf->SetX(10);
$pdf->Cell(40,6,"Nom Client :",0,0);
$pdf->Cell(80,6,$info['NomClient'],0,2);
$pdf->Ln(2);
$pdf->SetX(10);
$pdf->Cell(40,6,"Nom Rep. Client :",0,0);
$pdf->Cell(80,6,$info['NomRepClient'],0,1);

$pdf->Ln(4);

/************* TABLEAU *************/

$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,8,txt('N°'),1,0,'L');
$pdf->Cell(22,8,'Base',1,0,'L');
$pdf->Cell(18,8,'Poids Air',1,0,'L');
$pdf->Cell(20,8,'Poids Eau',1,0,'L');
$pdf->Cell(20,8,'Densite',1,0,'L');
$pdf->Cell(20,8,'Carat',1,0,'L');
$pdf->Cell(25,8,'Prix Unitaire',1,0,'L');
$pdf->Cell(40,8,'Montant',1,1,'L');


$pdf->SetFont('Arial','',10);

$total = 0;
$numero = 1;

$totalPoidsAir = 0;
$totalPoidsEau = 0;
$totalDensite = 0;
$totalCarat = 0;
$totalMontant = 0;
$nbLignes = 0;
while($row = mysqli_fetch_assoc($result))
{
    $pdf->Cell(10,7,$numero,1,0,'L');   
    $pdf->Cell(22,7,number_format($row['Base'],0,'.',' '),1,0,'L'); 
    $pdf->Cell(18,7,number_format($row['PoidsAir'],2,'.',' '),1,0,'L');
    $pdf->Cell(20,7,number_format($row['PoidsEau'],2,'.',' '),1,0,'L');
    $pdf->Cell(20,7,number_format($row['Densite'],2,'.',' '),1,0,'L');
    $pdf->Cell(20,7,number_format($row['Carat'],2,'.',' '),1,0,'L');
    $pdf->Cell(25,7,number_format($row['PrixUnitaire'],0,'.',' '),1,0,'L');
    $pdf->Cell(40,7,number_format($row['Montant'],0,'.',' '),1,0,'L');
    $pdf->Ln(7);

    $numero++;

    $totalPoidsAir += (float)$row['PoidsAir'];
    $totalPoidsEau += (float)$row['PoidsEau'];
    $totalDensite += (float)$row['Densite'];
    $totalCarat += (float)$row['Carat'];
    $totalMontant += (float)$row['Montant'];
    $nbLignes++;
}
$pdf->Ln(2);
$pdf->SetFont('Arial','B',11);

$SommePoidsAir = 0;
$SommePoidsEau = 0;
$sommeMontant = 0;
$moyenneDensite = 0;
$moyenneCarat = 0;

if ($nbLignes > 0) {
    $SommePoidsAir  = $totalPoidsAir;
    $SommePoidsEau  = $totalPoidsEau;
    $moyenneDensite = $totalPoidsAir / $totalPoidsEau;
   // $moyenneCarat   = $totalCarat / $nbLignes;
    $sommeMontant   = $totalMontant;

$sql = "
SELECT Carat
FROM bareme
WHERE $moyenneDensite BETWEEN Densite_min AND Densite_max
LIMIT 1
";

$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $moyenneCarat = $row['Carat'];
}

}


/************* LIGNE DES TOTAUX *************/
$pdf->Cell(10,8,'',0,0,'L');
$pdf->Cell(22,8,'-',0,0,'C');
$pdf->Cell(18,8,formatSansArrondi($SommePoidsAir,2),1,0,'L');
$pdf->Cell(20,8,formatSansArrondi($SommePoidsEau,2),1,0,'L');
$pdf->Cell(20,8,formatSansArrondi($moyenneDensite,2),1,0,'L');
$pdf->Cell(20,8,formatSansArrondi($moyenneCarat,2),1,0,'L');
$pdf->Cell(25,8,'-',0,0,'C');
$pdf->Cell(40,8,formatSansArrondi($sommeMontant,2),1,1,'L');

$pdf->Ln(5);

/************* MONTANT EN LETTRES *************/
$pdf->SetFont('Arial','B',12);
$pdf->MultiCell(
    175,
    8,
    txt("Arrêtée la présente facture à la somme de : ".convertir_en_lettres($sommeMontant)." francs CFA"),
    1,
    'L'
);

$pdf->Ln(8);

/************* SIGNATURES *************/
$pdf->SetFont('Arial','BU',12);

$pdf->Cell(54,8,"Signature du Gestionnaire",0,0,'C');
$pdf->SetX(120);
$pdf->Cell(90,8,"Signature du Client",0,1,'C');

$pdf->Output();
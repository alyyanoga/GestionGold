<?php

include "../serveur/bdd.php";

//Vider les caches
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

session_start();
 

function ajout_user($Prenom, $Nom, $Identifiant, $Password,$Role){

global $conn;

    $sql = "INSERT INTO Utilisateurs
            (Prenom, Nom, Identifiant, Password, Role)
            VALUES
            ('$Prenom', '$Nom','$Identifiant', '$Password', '$Role')";

    $result = mysqli_query($conn, $sql);

    return $result;
}
/*----------AJOUT CLIENT----------*/

function ajout_client($Numero, $Prenom, $Nom,$Solde, $Adresse, $Telephone ){

global $conn;

   $sql = "INSERT INTO clients
(
    Numero,
    Prenom,
    Nom,
    Solde,
    Adresse,
    Telephone
)

VALUES
(
    '$Numero',
    '$Prenom',
    '$Nom',
    '$Solde',
    '$Adresse',
    '$Telephone'
)";

    $result = mysqli_query($conn, $sql);

    return $result;
}

function estConnecte()
{
    // Vérifie si la session existe
    if(!isset($_SESSION['Identifiant']))
    {
        // Redirection login
        header("Location: ../pages/login.php");

        exit();
    }
}

function dernier_numero_compte($conn)
{
    $sql = "SELECT MAX(Numero) AS lastNum FROM clients";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    if($row['lastNum'])
    {
        return $row['lastNum'] + 1;
    }

    return 411000;
}

//------------------Dernier numero de regiment depot et retrait argent
function dernier_numero_regiment($conn)
{
    $sql = "SELECT MAX(Numero) AS lastNum FROM regiments";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    if ($row['lastNum']) {
        return $row['lastNum'] + 1;
    }

  return $row['lastNum'] + 1;
}
// DERNIER NUMERO DE REGIMENT PROFORMA DEVISE
function dernier_numero_regiment_proforma($conn)
{
    $sql = "SELECT MAX(Numero) AS lastNum FROM regiment_devise_proforma";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    if ($row['lastNum']) {
        return $row['lastNum'] + 1;
    }

  return $row['lastNum'] + 1;
}

//----------AJOUTER OPERATIONS DEPOT------------

function faire_depot(
    $Date,
    $Compte,
    $Client,
    $R_client,
    $Prix,
    $Quantite,
    $Montant_depot,
    $Montant_retrait,
    $Ancien_solde ,
    $Transactions,
    $Regiment,
    $Id_client,
    $Utilisateur,
    $Nature,
    $Nom_R_client
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO mouvement_totales
(
    Date,
    Compte,
    Client,
    R_client,
    Prix,
    Quantite,
    Montant_depot,
    Montant_retrait,
    Solde,
    Transactions,
    Regiment,
    Id_client,
    Utilisateur,
    Nature,
    Heure,
    Nom_R_client
)

VALUES
(
    '$Date',
    '$Compte',
    '$Client',
    '$R_client',
    '$Prix',
    '$Quantite',
    '$Montant_depot',
    '$Montant_retrait',
    '$Ancien_solde',
    '$Transactions',
    '$Regiment',
    '$Id_client',
    '$Utilisateur',
    '$Nature',
    '$Heure',
    '$Nom_R_client'
)";

$result = mysqli_query($conn, $sql);

return $result;
}

//----------AJOUTER OPERATIONS RETRAIT------------

function faire_retrait(
    $Date,
    $Compte,
    $Client,
    $R_client,
    $Prix,
    $Quantite,
    $Montant_depot,
    $Montant_retrait,
    $Ancien_solde ,
    $Transactions,
    $Regiment,
    $Id_client,
    $Utilisateur,
    $Nature,
    $Nom_R_client
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO mouvement_totales
(
    Date,
    Compte,
    Client,
    R_client,
    Prix,
    Quantite,
    Montant_depot,
    Montant_retrait,
    Solde,
    Transactions,
    Regiment,
    Id_client,
    Utilisateur,
    Nature,
    Heure,
    Nom_R_client
)

VALUES
(
    '$Date',
    '$Compte',
    '$Client',
    '$R_client',
    '$Prix',
    '$Quantite',
    '$Montant_depot',
    '$Montant_retrait',
    '$Ancien_solde',
    '$Transactions',
    '$Regiment',
    '$Id_client',
    '$Utilisateur',
    '$Nature',
    '$Heure',
    '$Nom_R_client'
)";

$result = mysqli_query($conn, $sql);

return $result;
}

//----------AJOUTER OPERATIONS DEVISE FACTURE PROFORMA------------

function ajout_proforma(
    $Date,
    $Compte,
    $Client,
    $R_client,
    $Prix,
    $Quantite,
    $Montant_depot,
    $Montant_retrait,
    $Ancien_solde ,
    $Transactions,
    $Regiment,
    $Id_client,
    $Utilisateur,
    $Nature,
    $Nom_R_client
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO devise_proforma
(
    Date,
    Compte,
    Client,
    R_client,
    Prix,
    Quantite,
    Montant_depot,
    Montant_retrait,
    Solde,
    Transactions,
    Regiment,
    Id_client,
    Utilisateur,
    Nature,
    Heure,
    Nom_R_client
)

VALUES
(
    '$Date',
    '$Compte',
    '$Client',
    '$R_client',
    '$Prix',
    '$Quantite',
    '$Montant_depot',
    '$Montant_retrait',
    '$Ancien_solde',
    '$Transactions',
    '$Regiment',
    '$Id_client',
    '$Utilisateur',
    '$Nature',
    '$Heure',
    '$Nom_R_client'
)";

$result = mysqli_query($conn, $sql);

return $result;
}


//--------INSERTION DANS LA TABLE REGIMENTS

function ajout_regiments(
    $Numero,
){

global $conn;



$sql = "INSERT INTO regiments
(
    Numero
)

VALUES
(
    '$Numero'
    
)";

$result = mysqli_query($conn, $sql);

return $result;
}

//INSERER REGIMENT FACTURE PROFORMA 

function devise_proforma_regiment(
    $Numero,
){
global $conn;

$sql = "INSERT INTO regiment_devise_proforma
(
    Numero
)
VALUES
(
    '$Numero'
    
)";

$result = mysqli_query($conn, $sql);

return $result;
}

function modifier_solde_client($Id_client, $Solde){

    global $conn;

    $sql = "UPDATE clients 
            SET Solde = '$Solde'
            WHERE Id = '$Id_client'";

    $result = mysqli_query($conn, $sql);

    return $result;
}

//--------INSERTION DANS LA TABLE OPERATIONS CAISSE

function ajout_caisse(
    $Date,
    $Client,
    $Debit,
    $Credit,
    $Ancien_solde_caisse,
    $Transaction,
    $Utilisateur
){

global $conn;

$sql = "INSERT INTO mouvement_caisse
(
   
    Date,
    Client,
    Debit,
    Credit,
    Solde,
    Transaction,
    Utilisateur
)

VALUES
(
    '$Date',
    '$Client',
    '$Debit',
    '$Credit',
    '$Ancien_solde_caisse',
    '$Transaction',
    '$Utilisateur'
    
)";
    
$result = mysqli_query($conn, $sql);

return $result;
}

function modifier_solde_caisse($Solde){

    global $conn;

    $sql = "UPDATE caisse 
            SET Solde = '$Solde'";

    $result = mysqli_query($conn, $sql);

    return $result;
}

//------CONVERTIR EN LETTRE ------///

function convertir_en_lettres($nombre)
{
    $f = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
    return ucfirst($f->format($nombre));
}


//---------OPERATION VIREMENT INTER CLIENT--------------//

function faire_retrait_virement(
    $Date,
    $Depot,
    $Retrait,
    $Transaction,
    $Montant,
    $Regiment
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO virement
(
    Date,
    Depot,
    Retrait,
    Transaction,
    Montant,
    Regiment,
    Heure
)

VALUES
(
    '$Date',
    '$Depot',
    '$Retrait',
    '$Transaction',
    '$Montant',
    '$Regiment',
    '$Heure'
)";

$result = mysqli_query($conn, $sql);

return $result;
}


function faire_depot_virement(
    $Date,
    $Depot,
    $Retrait,
    $Transaction,
    $Montant,
    $Regiment
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO virement
(
    Date,
    Depot,
    Retrait,
    Transaction,
    Montant,
    Regiment,
    Heure
)

VALUES
(
    '$Date',
    '$Depot',
    '$Retrait',
    '$Transaction',
    '$Montant',
    '$Regiment',
    '$Heure'
)";

$result = mysqli_query($conn, $sql);

return $result;
}

//RECUPERER LE DERNIER SOLDE DE LA TABLE MOUVEMENT CAISSE
function dernier_solde_caisse($conn)
{
    $sql = "SELECT Solde
            FROM mouvement_caisse
            ORDER BY Id DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['Solde'];
    }

    return 0;
}

/***----------FAIRE DEPOT OR-------------- */

//----------AJOUTER OPERATIONS DEPOT------------

function faire_depot_or(
    $Date,
    $Compte,
    $Client,
    $R_client,
    $Prix,
    $Quantite,
    $Montant_depot,
    $Montant_retrait,
    $Ancien_solde ,
    $Transactions,
    $Regiment,
    $Id_client,
    $Utilisateur,
    $Nature,
    $Nom_R_client
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO mouvement_totales
(
    Date,
    Compte,
    Client,
    R_client,
    Prix,
    Quantite,
    Montant_depot,
    Montant_retrait,
    Solde,
    Transactions,
    Regiment,
    Id_client,
    Utilisateur,
    Nature,
    Heure,
    Nom_R_client
)

VALUES
(
    '$Date',
    '$Compte',
    '$Client',
    '$R_client',
    '$Prix',
    '$Quantite',
    '$Montant_depot',
    '$Montant_retrait',
    '$Ancien_solde',
    '$Transactions',
    '$Regiment',
    '$Id_client',
    '$Utilisateur',
    '$Nature',
    '$Heure',
    '$Nom_R_client'
)";

$result = mysqli_query($conn, $sql);

return $result;
}

//-----------OPERATION VENTE OR----------//
function faire_vente_or(
    $Date,
    $Compte,
    $Client,
    $R_client,
    $Prix,
    $Quantite,
    $Montant_depot,
    $Montant_retrait,
    $Ancien_solde ,
    $Transactions,
    $Regiment,
    $Id_client,
    $Utilisateur,
    $Nature,
    $Nom_R_client
){

global $conn;

$Heure = date("H:i:s");

$sql = "INSERT INTO mouvement_totales
(
    Date,
    Compte,
    Client,
    R_client,
    Prix,
    Quantite,
    Montant_depot,
    Montant_retrait,
    Solde,
    Transactions,
    Regiment,
    Id_client,
    Utilisateur,
    Nature,
    Heure,
    Nom_R_client
)

VALUES
(
    '$Date',
    '$Compte',
    '$Client',
    '$R_client',
    '$Prix',
    '$Quantite',
    '$Montant_depot',
    '$Montant_retrait',
    '$Ancien_solde',
    '$Transactions',
    '$Regiment',
    '$Id_client',
    '$Utilisateur',
    '$Nature',
    '$Heure',
    '$Nom_R_client'
)";

$result = mysqli_query($conn, $sql);

return $result;
}
?>






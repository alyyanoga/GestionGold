<?php
include "../includes/header.php";
include(__DIR__ . "/../functions/functions.php");

  /*---INITIALISATION----*/
    $Regiment= dernier_numero_regiment($conn);
    date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');

    //CHARGER LES CLIENTS
    $sql = "SELECT * FROM clients";
    $result = mysqli_query($conn, $sql);

    /* RECUPERATION DES DONNEES CLIENT */

if (isset($_GET['txtIdClient']) && !empty($_GET['txtIdClient'])) {

    $Id_client = $_GET['txtIdClient'];
    $sqlClient = "SELECT * FROM clients
                  WHERE Id = '$Id_client'";

    $resClient = mysqli_query($conn, $sqlClient);

    $client_data = mysqli_fetch_assoc($resClient);
}

     /* INSERER L'ACHAT DEVISE DANS LE COMPTE CLIENT */
if (isset($_POST['btn_valider_devise'])) {

    $Date = $_POST['txtDate'] ?? '';
    $Compte = $_POST['txtCompte'] ?? '';
    $Client = $_POST['txtNomComplet'] ?? '';
    $R_client = $_POST['txtRepClient'] ?? '';
    $Prix =$_POST['txtTaux'] ?? '';
    $Quantite = str_replace(' ', '', $_POST['txtMontantUSD'] ?? '');
    $Montant_depot = "0";
    $Montant_retrait = str_replace(' ', '', $_POST['txtMontantCFA']);
    $Ancien_solde  = str_replace(' ', '', $_POST['txtSolde']);
    $Regiment = $_POST['txtRegiment'] ?? '';
    $Id_client = $_POST['txtIdClient'] ?? '';
    $Nature = "VENTE DEVISE";
    $Transactions = $Nature;
    $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');
    $Nom_R_client = $_POST['txtRepClient'] ?? '';
    /*------RECUPERER L'ANCIEN SOLDE DE LA CAISSE-------*/
    $Ancien_solde_caisse  = (int) str_replace(' ', '', $_POST['txtSoldeCaisse']);

    /*----INSERTION DANS LA TABLE MOUVEMENT CAISE------*/

    $Debit = (int) str_replace(' ', '', $_POST['txtMontantCFA']);
    $Credit = "0";
    $Transaction = $Nature;   

    if (
        empty($Compte) ||
        empty($Client) ||
        empty($R_client) ||
        $Quantite <= 0
    ) {

          echo "
        <script>
            alert('Veuillez remplir tous les champs !!!');
        </script>
        ";


    } else {
        $nouveau_solde = $Ancien_solde - $Montant_retrait;
        $nouveau_solde_caisse = $Ancien_solde_caisse - $Montant_retrait;

        $save = modifier_solde_client($Id_client, $nouveau_solde);

        $save = faire_retrait(
            $Date,
            $Compte,
            $Client,
            "VENTE $$$ / ". $R_client,
            $Prix,
            $Quantite,
            $Montant_depot,
            $Montant_retrait,
            $nouveau_solde,
            $Transactions,
            $Regiment,
            $Id_client,
            $Utilisateur,
            $Nature,
            $Nom_R_client
        );
          $save = ajout_regiments($Regiment);
          $idVente = $Regiment;
           if ($save) {

         
            echo "
            <script>
            window.location='_v_devise.php';
            window.open('../pdf/facture_v_devise.php?Id=$idVente');
            </script>
            ";
        
            $Regiment = dernier_numero_regiment($conn);

        } else {

            echo mysqli_error($conn);

        }
    }
}
?>

<body>

 <?php
        include "../includes/aside.php";
        $page = "_vente_devise";
        include "../includes/nav_devise.php";
        ?>
       <div class="container_devise">
            <div class="section">
                  <div class="modal-devise" id="modalClient">
                    <div class="modal-content-devise"   >
                        <span class="close" onclick="fermerModal()">
                            &times;
                        </span>

                        <h3>Liste des clients</h3>
                        <div class="barre_recherche">
                            <input type="text" class="rechercheClient" id="rechercheClientDevise" placeholder="Rechercher un client..." onkeyup="filtrerClientDevise()">
                        </div>
                        <br>
                        <div class="table-responsive-devise">
                            <table border="1" class="table-client" id="tableClientDevise">

                                <tr>
                                    <th>Prenom et Nom</th>
                                    <th>Solde</th>
                                </tr>

                                    <?php while($row = mysqli_fetch_assoc($result)) { ?>

                                    <tr onclick='selectionnerClientDevise(
                                    <?php echo json_encode($row["Numero"]); ?>,
                                    <?php echo json_encode($row["Prenom"]." ".$row["Nom"]." ".$row["Telephone"]); ?>,
                                    <?php echo json_encode($row["Id"]); ?>,
                                    <?php echo json_encode($row["Solde"]); ?>
                                         )'>

                                    <td><?php echo $row['Prenom'] . ' ' . $row['Nom']. ' - ' . $row['Telephone']; ?></td>

                                    <td><?php echo number_format($row['Solde'], 0, ',', ' '); ?></td>

                                    </tr>

                                    <?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="field_groupe">
                        <div class="btn-choisir">
                            <button type="button" class="btn_choisir_devise" onclick="ouvrirModal()">Choisir client</button>
                        </div>
                    <form action="#" method="post" id="form-client"  autocomplete="off">
                       
                            <input type="hidden" name="txtIdClient" id="txtIdClient" value="<?php echo $client_data['Id'] ?? ''; ?>">
                            <input type="hidden" name="txtSolde" id="txtSolde" value="<?php echo $client_data['Solde'] ?? ''; ?>">
                            <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                            <div class="input_field_groupe1">
                                <div class="lbl-fiel">
                                    <label class="lbl">Rgmt:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" class="rgmtDevise" name="txtRegiment" id="txtRegiment"  value="<?php echo sprintf("%04d", $Regiment); ?>" readonly>
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Date:&nbsp;&nbsp;&nbsp;</label>
                                    <input type="date" name="txtDate" class="txtDateDevise" id="txtDate" value="<?php echo date('Y-m-d'); ?>" readonly><br>
                                    <input type="hidden" name="txtHeure" class="txtHeure" id="txtHeure" readonly> 
                                </div>
                            </div>
                            <div class="input_field_groupe2">
                                <div class="lbl-fiel">
                                    <label class="lbl">N° Compte:&nbsp;&nbsp;&nbsp; </label>
                                    <input type="text" class="compte" name="txtCompte" id="txtCompte" value="<?php echo $client_data['Numero'] ?? ''; ?>" readonly>
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Nom Client: &nbsp;&nbsp; </label>
                                    <input type="text" class="nomcomplet" name="txtNomComplet" id="txtNomComplet" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>"  readonly>
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Rep - Client:&nbsp;&nbsp;&nbsp; </label>
                                    <input type="text" class="repClient" name="txtRepClient" id="txtRepClient"  value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>">
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Montant USD:</label>
                                    <input type="text" class="montantUSD" name="txtMontantUSD" id="txtMontantUSD" oninput="calculerDHS()" >
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Taux Devise:&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" class="tauxdevise" name="txtTaux" id="txtTaux" oninput="calculerCFA()" >
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Montant XOF:</label>
                                    <input type="text" class="montantCFA" name="txtMontantCFA" id="txtMontantCFA" >
                                </div>
                                <div class="lbl-fiel">
                                    <label class="lbl">Montant DHS:</label>
                                    <input type="text" class="montantDHS" name="txtMontantDHS" id="txtMontantDHS" >
                                </div>
                                
                                <div class="btn-valider-devise">
                                    <button type="submit"  class="btn_valider" name="btn_valider_devise" id="btn_valider_devise" readonly> EFFECTUER VENTE</button>
                                </div>

                            </div>

                    
                    </form>
            </div>
        </div>
    
</body>
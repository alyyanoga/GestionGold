  <?php
    include "../includes/header.php";
    include(__DIR__ . "/../functions/functions.php");
    include "../includes/aside.php";

    /*---INITIALISATION----*/
    $Regiment= dernier_numero_regiment($conn);
    $message  ="";
    date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');


    /* TOUJOURS charger les clients */

    $sql = "SELECT * FROM clients";
    $result = mysqli_query($conn, $sql);

      /* CHARGEMENT DES DONNEES DE LA TABLE MOUVEMENT_TOTALES */

    $client_data = null;
    $result2 = null;
    $SansCaisse = isset($_POST['checkbox']);

if (isset($_GET['txtIdClient']) && !empty($_GET['txtIdClient'])) {

    $Id_client = $_GET['txtIdClient'];

    /* CHARGEMENT DES OPERATIONS */

    $sql2 = "SELECT * FROM mouvement_totales
             WHERE Id_client = '$Id_client'
             ORDER BY Id DESC";

    $result2 = mysqli_query($conn, $sql2);

    /* RECUPERATION DES DONNEES CLIENT */

    $sqlClient = "SELECT * FROM clients
                  WHERE Id = '$Id_client'";

    $resClient = mysqli_query($conn, $sqlClient);

    $client_data = mysqli_fetch_assoc($resClient);

    /* RECUPERATION LE SOLDE DE LA CAISSE */

    $sqlCaisse = "SELECT * FROM caisse";

    $resCaisse = mysqli_query($conn, $sqlCaisse);

    $caisse_data = mysqli_fetch_assoc($resCaisse);
}

    /* FAIRE LE DEPOT ARGENT DANS LE COMPTE CLIENT */
    if (isset($_POST['btn_valider_depot'])) {
    
   if (!isset($_POST['checkbox'])) {

    $Date = $_POST['txtDate'] ?? '';
    $Compte = $_POST['txtCompte'] ?? '';
    $Client = $_POST['txtNomComplet'] ?? '';
    $R_client = $_POST['txtRepClient'] ?? '';
    $Prix = "";
    $Quantite = "";
    $Montant_depot = (int) str_replace(' ', '', $_POST['txtMontant']);
    $Montant_retrait = "0";
    $Ancien_solde  = (int) str_replace(' ', '', $_POST['txtSolde']);
    $Regiment = $_POST['txtRegiment'] ?? '';
    $Id_client = $_POST['txtIdClient'] ?? '';
    $Nature = "DEPOT ARGENT";
    $Transactions = $Nature;
    $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');
    $Nom_R_client = $_POST['txtRepClient'] ?? '';
    /*------RECUPERER L'ANCIEN SOLDE DE LA CAISSE-------*/
    $Ancien_solde_caisse  = (int) str_replace(' ', '', $_POST['txtSoldeCaisse']);

    /*----INSERTION DANS LA TABLE MOUVEMENT CAISE------*/

    $Debit = (int) str_replace(' ', '', $_POST['txtMontant']);
    $Credit = "0";
    $Transaction = $Nature;


    if (
        empty($Compte) ||
        empty($Client) ||
        empty($R_client) ||
        empty($Montant_depot)
    ) {

        $message = "Veuillez remplir tous les champs";

    } else {
        $nouveau_solde = $Ancien_solde + $Montant_depot;
        $nouveau_solde_caisse = $Ancien_solde_caisse + $Montant_depot;

        $save = modifier_solde_client($Id_client, $nouveau_solde);
        $save_caisse = modifier_solde_caisse($nouveau_solde_caisse);

        $save = faire_depot(
            $Date,
            $Compte,
            $Client,
            "DEPOT / ". $R_client,
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

        $idDepot = mysqli_insert_id($conn);
           

        /*-----Insertion dans table Mouvement caisse*/

        $save = ajout_caisse(
            $Date,
            "DEPOT / ".$Client,
            $Debit,
            $Credit,
            $nouveau_solde_caisse,
            $Transaction,
            $Utilisateur
        );
           
         $save = ajout_regiments($Regiment);

         

        if ($save) {

         
            echo "
            <script>
            window.location='_depot.php';
            window.open('../pdf/facture_depot.php?Id=$idDepot');
            </script>
            ";
        
            $Regiment = dernier_numero_regiment($conn);

        } else {

            echo mysqli_error($conn);

        }
    }
    

    } else {

     $Date = $_POST['txtDate'] ?? '';
    $Compte = $_POST['txtCompte'] ?? '';
    $Client = $_POST['txtNomComplet'] ?? '';
    $R_client = $_POST['txtRepClient'] ?? '';
    $Prix = "";
    $Quantite = "";
    $Montant_depot = (int) str_replace(' ', '', $_POST['txtMontant']);
    $Montant_retrait = "0";
    $Ancien_solde  = (int) str_replace(' ', '', $_POST['txtSolde']);
    $Regiment = $_POST['txtRegiment'] ?? '';
    $Id_client = $_POST['txtIdClient'] ?? '';
    $Nature = "DEPOT ARGENT";
    $Transactions = $Nature;
    $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');
    $Nom_R_client = $_POST['txtRepClient'] ?? '';
    /*------RECUPERER L'ANCIEN SOLDE DE LA CAISSE-------*/
    $Ancien_solde_caisse  = (int) str_replace(' ', '', $_POST['txtSoldeCaisse']);

    /*----INSERTION DANS LA TABLE MOUVEMENT CAISE------*/

    if (
        empty($Compte) ||
        empty($Client) ||
        empty($R_client) ||
        empty($Montant_depot)
    ) {

        $message = "Veuillez remplir tous les champs";

    } else {
        $nouveau_solde = $Ancien_solde + $Montant_depot;
        $nouveau_solde_caisse = $Ancien_solde_caisse + $Montant_depot;

        $save = modifier_solde_client($Id_client, $nouveau_solde);
        $save = faire_depot(
            $Date,
            $Compte,
            $Client,
            "DEPOT / ". $R_client,
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
         $idDepot = mysqli_insert_id($conn);
         $save = ajout_regiments($Regiment);

        if ($save) {

            echo "
            <script>
            window.location='_depot.php';
            window.open('../pdf/facture_depot.php?Id=$idDepot');
            </script>
            ";

            $Regiment = dernier_numero_regiment($conn);

        } else {

            echo mysqli_error($conn);

        }
    }
    }

}

?>
    
    <body>
        <?php
        $page = "_depot";
        include "../includes/nav_gestion_clients.php";
        
        ?>
        <div class="container-depot">
          <div class="content-form">
            <div class="btn-choisir">
              <button type="button" class="btn_choisir" onclick="ouvrirModal()">Choisir client</button>
            </div>

            <div class="input-field-depot">
              <form action="#" method="post" id="form-client"  autocomplete="off">
                <div class="groupe-field">
                  <input type="hidden" name="txtIdClient" id="txtIdClient" value="<?php echo $client_data['Id'] ?? ''; ?>">
                  <input type="hidden" name="txtSolde" id="txtSolde" value="<?php echo $client_data['Solde'] ?? ''; ?>">
                  <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                  <label class="lbl">Compte:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="text" class="compte" name="txtCompte" id="txtCompte" value="<?php echo $client_data['Numero'] ?? ''; ?>" readonly>
                  <label class="lbl">Rgmt: &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</label>
                  <input type="text" class="rgmt" name="txtRegiment" id="txtRegiment"  value="<?php echo sprintf("%04d", $Regiment); ?>" readonly>
                  <label class="lbl">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="date" name="txtDate" class="txtDate" id="txtDate" value="<?php echo date('Y-m-d'); ?>" readonly>
                  <input type="hidden" name="txtHeure" class="txtHeure" id="txtHeure" readonly>
                  <span class="groupe_radio">
                      <input type="checkbox" name="checkbox" class="txtcheckbox" id="txtcheckbox" value="1" readonly>
                      <span class="reporting">Reporting</span>
                  </span>
                </div>
                <div class="groupe-field">
                    <div class="lbl-fiel">
                      <label class="lbl">Client: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" class="nomcomplet" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" name="txtNomComplet" id="txtNomComplet" readonly>
                    </div>
                    <div class="lbl-fiel">
                      <label class="lbl">Montant:&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" class="montant" name="txtMontant" id="txtMontant" >
                    </div>
                  <div class="lbl-fiel">
                      <label class="lbl">Rep. Client:</label>
                      <input type="text" class="repclient" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" name="txtRepClient" id="txtRepClient" >
                  </div>
                  <div class="btn-valider">
                    <button type="submit" name="btn_valider_depot" class="btn_valider" id="btn_valider" readonly> DEPOT</button>
                  </div>
                </div>
              </form>
            </div>
            
          </div>
          <div class="content-table">
            <table border="1" class="table-mouvement">

              <tr>
                  <th style="text-align: left;">Voir</th>
                  <th style="text-align: left;">Date</th>
                  <th style="text-align: left;">Représentant Client</th>
                  <th style="text-align: left;">Quantité</th>
                  <th style="text-align: left;">Prix</th>
                  <th style="text-align: left;">Montant dépot</th>
                  <th style="text-align: left;">Montant retrait</th>
                  <th style="text-align: left;">Solde disponible</th>
                  <th style="text-align: left;">Transactions</th>
                  <th style="text-align: left;">Heure</th>
                  <th style="text-align: left;">Utilisateur</th>
                  
              </tr>
              <?php if ($result2 && mysqli_num_rows($result2) > 0) { ?>

                            <?php while($row = mysqli_fetch_assoc($result2)) { ?>
                                    
                                    <td>
                                 <?php
                                        if($row['Transactions'] == 'DEPOT ARGENT')
                                        {
                                    ?>

                                    <button
                                    onclick="window.open('../pdf/facture_depot.php?Id=<?= $row['Id'] ?>')"
                                    class="btn-print">

                                    <i class="bi bi-printer"></i>

                                    </button>

                                    <?php
                                        }
                                        else if($row['Transactions'] == 'RETRAIT ARGENT')
                                        {
                                    ?>

                                    <button
                                    onclick="window.open('../pdf/facture_retrait.php?Id=<?= $row['Id'] ?>')"
                                    class="btn-print">

                                    <i class="bi bi-printer"></i>

                                    </button>

                                    <?php
                                        }
                                        else if($row['Transactions'] == 'ACHAT DEVISE')
                                        {
                                    ?>
                                    <button
                                    onclick="window.open('../pdf/facture_a_devise.php?Id=<?= $row['Id'] ?>')"
                                    class="btn-print">

                                    <i class="bi bi-printer"></i>

                                    </button>
                                    <?php
                                        }
                                        else if($row['Transactions'] == 'VENTE DEVISE')
                                        {
                                    ?>
                                    <button
                                    onclick="window.open('../pdf/facture_v_devise.php?Id=<?= $row['Id'] ?>')"
                                    class="btn-print">

                                    <i class="bi bi-printer"></i>

                                    </button>
                                    

                                    <?php } ?>
                                     </td>

                                    <td style="text-align: left;"><?php echo date('d/m/Y', strtotime($row['Date'])); ?></td>
                                    <td style="text-align: left;"><?php echo $row['R_client']; ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Quantite'], 0, ',', ' '); ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Prix'], 0, ',', ' '); ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Montant_depot'], 0, ',', ' '); ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Montant_retrait'], 0, ',', ' '); ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Solde'], 0, ',', ' '); ?></td>
                                    <td style="text-align: left;"><?php echo $row['Transactions']; ?></td>
                                    <td style="text-align: left;"><?php echo $row['Heure']; ?></td>
                                    <td style="text-align: left;"><?php echo $row['Utilisateur']; ?></td>
                                    
                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4" >Aucune Operation</td>
                            </tr>

                        <?php } ?>

            </table>
          </div>

        </div>

        <!-- MODAL -->

<div class="modal" id="modalClient">

    <div class="modal-content"   >

        <span class="close" onclick="fermerModal()">
            &times;
        </span>

        <h3>Liste des clients</h3>
		<div class="barre_recherche">
            <input type="text" class="rechercheClient" id="rechercheClient" placeholder="Rechercher un client..." onkeyup="filtrerClient()">
        </div>
            <br>
    <div class="table-responsive">
       <table border="1" class="table-client" id="tableClient">

    <tr>
        <th>Numero</th>
        <th>Prenom</th>
        <th>Nom</th>
        <th>Telephone</th>
        <th>Solde</th>
    </tr>

     <?php while($row = mysqli_fetch_assoc($result)) { ?>

       <tr class="select_client" onclick="selectionnerClient(
       '<?php echo $row['Prenom'] . ' ' . $row['Nom']. ' ' . $row['Telephone']; ?>',
       '<?php echo $row['Numero']; ?>',
       '<?php echo $row['Id']; ?>',
       '<?php echo $row['Solde']; ?>'
      
       )">

            <td><?php echo $row['Numero']; ?></td>

            <td><?php echo $row['Prenom']; ?></td>

            <td><?php echo $row['Nom']; ?></td>

            <td><?php echo $row['Telephone']; ?></td>

            <td><?php echo number_format($row['Solde'], 0, ',', ' '); ?></td>

        </tr>

        <?php } ?>

    </table>
</div>
       
    </div>
</div>

    </body>
    </html>
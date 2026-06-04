<?php
    include "../includes/header.php";
    include(__DIR__ . "/../functions/functions.php");
    include "../includes/aside.php";

     /*---INITIALISATION----*/
    $Regiment= dernier_numero_regiment($conn);
    $message  ="";
    date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');
    $sqlClient=null;


       /* TOUJOURS charger les Clients */

    $sql = "SELECT * FROM clients";
    $result = mysqli_query($conn, $sql);
  

     /* RECUPERATION DES DONNEES CLIENT */
    $Id_client = $_POST['txtIdClient'] ?? '';
    $sqlClient = "SELECT * FROM clients
                  WHERE Id = '$Id_client'";

    $resClient = mysqli_query($conn, $sqlClient);

    $client_data = mysqli_fetch_assoc($resClient);

    /* RECUPERATION LE SOLDE DE LA CAISSE */

    $sqlCaisse = "SELECT * FROM caisse";

    $resCaisse = mysqli_query($conn, $sqlCaisse);

    $caisse_data = mysqli_fetch_assoc($resCaisse);

     /* CHARGEMENT DES OPERATIONS */
   
    $sql2 = "SELECT * FROM virement
         WHERE Transaction = 'DEPOT ARGENT'
         OR Transaction = 'RETRAIT ARGENT'
         ORDER BY Id DESC";

    $result2 = mysqli_query($conn, $sql2);


/*=========================
    VIREMENT ENTRE CLIENTS
==========================*/

if (isset($_POST['btn_ajouter_virement'])) {

    mysqli_begin_transaction($conn);

    try {

        /*=========================
            RECUPERATION DONNEES
        ==========================*/

        $Date = $_POST['txtDate'] ?? '';

        $Montant = (int) str_replace(' ', '', $_POST['txtMontant'] ?? 0);

        $Regiment = $_POST['txtRegiment'] ?? '';

        $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');

        /*=========================
            DEBITEUR
        ==========================*/

        $IdDebiteur = $_POST['txtIdClientDebiteur'] ?? '';

        $CompteDebiteur = $_POST['txtCompteDebiteur'] ?? '';

        $Debiteur = $_POST['txtDebiteur'] ?? '';

        $AncienSoldeDebiteur = (int) str_replace(' ', '', $_POST['txtSoldeDebiteur'] ?? 0);

        /*=========================
            CREDITEUR
        ==========================*/

        $IdCrediteur = $_POST['txtIdClientCrediteur'] ?? '';

        $CompteCrediteur = $_POST['txtCompteCrediteur'] ?? '';

        $Crediteur = $_POST['txtCrediteur'] ?? '';

        $AncienSoldeCrediteur = (int) str_replace(' ', '', $_POST['txtSoldeCrediteur'] ?? 0);

        /*=========================
            VALIDATIONS
        ==========================*/

       

        if ($IdDebiteur == $IdCrediteur) {

            throw new Exception("Le débiteur et le créditeur doivent être différents");
        }


        if (trim($Montant) === '' ||
            $Montant <= 0) {

            throw new Exception("Veuillez saisir le montant !!");
        }
      

        /*=========================
            NOUVEAUX SOLDES
        ==========================*/

        $NouveauSoldeDebiteur =
            $AncienSoldeDebiteur - $Montant;

        $NouveauSoldeCrediteur =
            $AncienSoldeCrediteur + $Montant;

        /*=========================
            MISE A JOUR SOLDES
        ==========================*/

        modifier_solde_client(
            $IdDebiteur,
            $NouveauSoldeDebiteur
        );

        modifier_solde_client(
            $IdCrediteur,
            $NouveauSoldeCrediteur
        );

        /*=========================
            HISTORIQUE RETRAIT
        ==========================*/

        faire_retrait(

            $Date,

            $CompteDebiteur,

            $Debiteur,

            "RETRAIT / " . $Crediteur,

            "",

            "",

            0,

            $Montant,

            $NouveauSoldeDebiteur,

            "RETRAIT ARGENT",

            $Regiment,

            $IdDebiteur,

            $Utilisateur,

            "VIREMENT RETRAIT ARGENT"
        );

        /*=========================
            HISTORIQUE DEPOT
        ==========================*/

        faire_depot(

            $Date,

            $CompteCrediteur,

             $Crediteur,

            "DEPOT / " . $Debiteur,

            "",

            "",

            $Montant,

            0,

            $NouveauSoldeCrediteur,

            "DEPOT ARGENT",

            $Regiment,

            $IdCrediteur,

            $Utilisateur,

            "VIREMENT DEPOT ARGENT"
        );

        faire_retrait_virement(

            $Date,
             "",
            "RETRAIT / " . $Debiteur,
            "RETRAIT ARGENT",
            $Montant,
            $Regiment
        );

        faire_depot_virement(

            $Date,
            "DEPOT / " . $Crediteur,
             "",
            "DEPOT ARGENT",
            $Montant,
            $Regiment
        );
     
        /*=========================
            REGIMENT
        ==========================*/

        ajout_regiments($Regiment);

        /*=========================
            VALIDATION SQL
        ==========================*/

        mysqli_commit($conn);

        echo "
        <script>

            alert('Virement effectué avec succès');

            window.location='_virement_clients.php';

        </script>
        ";
    

    } catch (Exception $e) {

        mysqli_rollback($conn);

        echo "
        <script>

            alert('" . $e->getMessage() . "');

        </script>
        ";
    }
    
    
}


   
    
 

   

?>
 <body>
        <?php
        $page = "_virement_clients";
        include "../includes/nav_gestion_clients.php";
        ?>

 <div class="container_virement">
    <div class="main">
            <div class="sign_container">
        <form action="#" method="post"  autocomplete="off">
               <span class="group-h2-rgmt">
                <h5>VIREMENT  CLIENT</h5> 
                <input type="text" class="rgmt" name="txtRegiment" id="txtRegiment"  value="<?php echo sprintf("%04d", $Regiment); ?>" readonly>
            </span> 
                <input type="hidden" name="txtDate" class="txtDate" id="txtDate" value="<?php echo date('Y-m-d'); ?>" readonly>
                <input type="hidden" name="txtHeure" class="txtHeure" id="txtHeure" readonly>   
                
                
                    <div class="field-group">
                        <label class="lbl_retrait"><h3>Retrait</h3>
                            <label class="lbl">Debiteur </label> 
                            <input type="text" class="CompteDebiteur" name="txtCompteDebiteur" id="txtCompteDebiteur" value="<?php echo $client_data['Numero'] ?? ''; ?>"> 
                            <input type="text" class="Debiteur" name="txtDebiteur" id="txtDebiteur" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" readonly >
                            <input type="hidden" name="txtIdClientDebiteur" id="txtIdClientDebiteur" >
                            <input type="hidden" name="txtSoldeDebiteur" id="txtSoldeDebiteur" value="<?php echo $client_data['Solde'] ?? ''; ?>">
                            <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                            <button type="button" class="btn_choisir" name="btn_choisir_debiteur" onclick="ouvrirModal('debiteur')"><i class="bi bi-plus-circle"></i></button>
                        </label>
                    </div>

                    <div class="field-group">
                        <label class="lbl_depot"><h3>Depot</h3>
                        
                            <label class="lbl">Crediteur </label> 
                            <input type="text" class="CompteCrediteur" name="txtCompteCrediteur" id="txtCompteCrediteur" value="<?php echo $client_data['Numero'] ?? ''; ?>"> 
                            <input type="text" class="Crediteur" name="txtCrediteur" id="txtCrediteur" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" readonly >
                            <input type="hidden" name="txtIdClientCrediteur" id="txtIdClientCrediteur" value="<?php echo $client_data['Id'] ?? ''; ?>">
                            <input type="hidden" name="txtSoldeCrediteur" id="txtSoldeCrediteur" value="<?php echo $client_data['Solde'] ?? ''; ?>">
                            <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                            <button type="button" class="btn_choisir" name="btn_choisir_crediteur" onclick="ouvrirModal('crediteur')"><i class="bi bi-plus-circle"></i></button> 
                        </label>
                        <label class="lbl_montant_virement">Montant</label><br>
                        <input type="text" class="Montant" name="txtMontant" id="txtMontant" >

                    </div>
                        
                        <button type="submit" name="btn_ajouter_virement" class="btn_ajouter_virement">VALIDE</button>
                </form> 
            </div>
            <div class="slide-table-virement">
                <h3>Tableau Virements</h3>
                                 
                    <table border="1" class="table-virement">

                        <tr>
                                <th style="text-align: left;">Date</th>
                                <th style="text-align: left;">Depot</th>
                                <th style="text-align: left;">Retrait</th>
                                <th style="text-align: left;">Transactions</th>
                                <th style="text-align: left;">Montant</th>
                        </tr>
                         <?php if ($result2 && mysqli_num_rows($result2) > 0) { ?>

                            <?php while($row = mysqli_fetch_assoc($result2)) { ?>

                                <tr>
                                    <td style="text-align: left;"><?php echo date('d/m/Y', strtotime($row['Date'])); ?></td>
                                    <td style="text-align: left;"><?php echo $row['Depot']; ?></td>
                                    <td style="text-align: left;"><?php echo $row['Retrait']; ?></td>
                                    <td style="text-align: left;"><?php echo $row['Transaction']; ?></td>
                                    <td style="text-align: left;"><?php echo number_format($row['Montant'], 0, ',', ' '); ?></td>
                                    
                                   
                                </tr>

                            <?php } ?>

                        <?php } else { ?>
                       
                            <tr>
                                <td colspan="4">Aucun utilisateur</td>
                            </tr>

                         <?php } ?>

                    </table>
            </div>
            </div>
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

     <?php while($row = mysqli_fetch_assoc($result)) { 
       
           ?> 
        
       <tr class="select_client"
        onclick='selectionnerVirementClient(
        <?= json_encode($row["Prenom"] . " " . $row["Nom"] . " " . $row["Telephone"]) ?>,
        <?= json_encode($row["Id"]) ?>,
        <?= json_encode($row["Numero"]) ?>,
        <?= json_encode($row["Solde"]) ?>
        )'>

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
       
</div>
</body>
</html>
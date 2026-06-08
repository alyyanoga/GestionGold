  <?php
    include "../includes/header.php";
    include(__DIR__ . "/../functions/functions.php");
    include "../includes/aside.php";

    /*---INITIALISATION----*/
    $message  ="";
    date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');
    $date = date('Y-m-d');
    $result2 = null;
  

    /* TOUJOURS charger les Caisses */

    $sql = "SELECT * FROM Caisse";
    $result = mysqli_query($conn, $sql);

      /* CHARGEMENT DES OPERATIONS */
    

    $sql2 = "SELECT * FROM mouvement_caisse ORDER BY Id DESC";
    $result2 = mysqli_query($conn, $sql2);

    /* RECUPERATION LE SOLDE DE LA CAISSE */

    $sqlCaisse = "SELECT * FROM caisse";

    $resCaisse = mysqli_query($conn, $sqlCaisse);

    $caisse_data = mysqli_fetch_assoc($resCaisse);
    

     /* FAIRE LE DEPOT ARGENT DANS LE COMPTE CLIENT */
    if (isset($_POST['btn_valider_caisse'])) {

    $typeOperation =  $_POST['typeOperation'];
       
   
    $Date = $_POST['txtDate'] ?? '';
    $Client = $_POST['txtCaisse'] ?? '';
    $Debit = (int) str_replace(' ', '', $_POST['txtMontant']);
    $Credit = "0";
    $Transaction = $_POST['txtMotif'] ?? '';
    $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');
    /*------RECUPERER L'ANCIEN SOLDE DE LA CAISSE-------*/
    $Ancien_solde_caisse  = (int) str_replace(' ', '', $_POST['txtSoldeCaisse']);

      $nouveau_solde_caisse = $Ancien_solde_caisse + $Debit;
      if ($typeOperation=='Depot') {

         $save_caisse = modifier_solde_caisse($nouveau_solde_caisse);

        /*-----Insertion dans table Mouvement caisse*/

        $save = ajout_caisse(
            $Date,
            $Client,
            $Debit,
            $Credit,
            $nouveau_solde_caisse,
            $Transaction,
            $Utilisateur
        );

        if ($save) {

         
            echo "
            <script>
             alert('Operation effectuée avec succès');
            window.location='_operations_caisse.php';
            </script>
            ";
        
        } else {

            echo mysqli_error($conn);

        }

        
        } else if ($typeOperation=='Retrait') {
          $Debit = "0";
          $Credit = (int) str_replace(' ', '', $_POST['txtMontant']);
          /***SI OPTION CHOISIR EST RETRAIT  */
           $nouveau_solde_caisse = $Ancien_solde_caisse - $Credit;
  
           $save_caisse = modifier_solde_caisse($nouveau_solde_caisse);
  
          /*-----Insertion dans table Mouvement caisse*/
  
          $save = ajout_caisse(
              $Date,
              $Client,
              $Debit,
              $Credit,
              $nouveau_solde_caisse,
              $Transaction,
              $Utilisateur
          );
  
          if ($save) {
  
           
              echo "
              <script>
               alert('Operation effectuée avec succès');
              window.location='_operations_caisse.php';
              </script>
              ";
          
          } else  {
  
              echo mysqli_error($conn);
  
          }
    } else{
       echo "
            <script>
             alert('Veuillez choisir le Type Operation');
              window.location='_operations_caisse.php';
            </script>
            ";
    }
   }

?>
    
    <body>
        <?php
        $page = "_operations_caisse";
        include "../includes/nav_caisse.php";
        
        ?>

        <div class="container-caisse">
          <div class="content-form">

            <div class="input-field-depot">
              <form action="#" method="post" id="form-client"  autocomplete="off">
                <div class="groupe-field">
                  <input type="hidden" name="txtIdCaisse" id="txtIdCaisse" value="<?php echo $caisse_data['Id'] ?? ''; ?>">
                  <input type="hidden" name="txtSolde" id="txtSolde" >
                  <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                  <label class="lbl">Caisse:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="text" class="compte" name="txtCaisse" id="txtCaisse" value="<?php echo $caisse_data['Caisse'] ?? ''; ?>"  readonly >
                  <label class="lbl">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                  <input type="date" name="txtDate" class="_txtDate" id="txtDate" value="<?php echo $date ?>" readonly>
                  <input type="hidden" name="txtHeure" class="txtHeure" id="txtHeure" readonly>
                  <select name="typeOperation" id="typeOperation">
                            <option value="" selected disabled>choisir</option>
                            <option value="Depot">Depot</option>
                            <option value="Retrait">Retrait</option>
                  </select>
                </div>
                <div class="groupe-field">
                    <div class="lbl-fiel">
                      <label class="lbl">Motif:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" class="motif" name="txtMotif" id="txtMotif"  >
                    </div>

                    <div class="lbl-fiel">
                      <label class="lbl">Montant:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                      <input type="text" class="montant" name="txtMontant" id="txtMontant" >
                    </div>
                     
                  <div class="btn-valider">
                    <button type="submit" name="btn_valider_caisse" class="btn_valider_caisse" id="btn_valider_caisse" disabled> VALIDE</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

           <div class="content-table">
            <table border="1" class="table-caisse">

              <tr>
                  <th>Date</th>
                  <th>Client</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Solde disponible</th>
                  <th>Transactions</th>
                  <th>Utilisateur</th>
                  
              </tr>
              <?php if ($result2 && mysqli_num_rows($result2) > 0) { ?>

                            <?php while($row = mysqli_fetch_assoc($result2)) { ?>
                                    
                                    <td><?php echo date('d/m/Y', strtotime($row['Date'])); ?></td>
                                    <td><?php echo $row['Client']; ?></td>
                                    <td><?php echo number_format($row['Debit'], 0, ',', ' '); ?></td>
                                    <td><?php echo number_format($row['Credit'], 0, ',', ' '); ?></td>
                                    <td><?php echo number_format($row['Solde'], 0, ',', ' '); ?></td>
                                    <td><?php echo $row['Transaction']; ?></td>
                                    <td><?php echo $row['Utilisateur']; ?></td>
                                    
                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4" >Aucune Operation</td>
                            </tr>

                        <?php } ?>

            </table>
          </div>


             <!-- MODAL -->

<div class="modal" id="modalClient">

    <div class="modal-content"   >

        <span class="close" onclick="fermerModal()">
            &times;
        </span>

        <h3>Liste des caisses</h3>

       <table border="1" class="table-client">

    <tr>
        <th>Nom Caisse</th>
        <th>Solde</th>
    </tr>

     <?php while($row = mysqli_fetch_assoc($result)) { ?>

       <tr class="select_client" onclick="selectionnerCaisse(
       '<?php echo $row['Id']; ?>',
       '<?php echo $row['Caisse']; ?>'
      
      
       )">

            <td><?php echo $row['Caisse']; ?></td>

            <td><?php echo number_format($row['Solde'], 0, ',', ' '); ?></td>

        </tr>

        <?php } ?>

    </table>
       
    </div>
</div>
       

    </body>
    </html>
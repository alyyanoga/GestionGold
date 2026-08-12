  <?php

    include "../includes/header.php";
    include(__DIR__ . "/../functions/functions.php");
    include "../includes/aside.php";


  function cleanNumber($value) {
     $value = str_replace([' ', "\u{00A0}"], '', $value);
    $value = str_replace(',', '.', $value);
    return number_format((float)$value, 2, '.', '');
}

    $user = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');

    mysqli_query($conn, "
        DELETE FROM temptransactionor
        WHERE NomUser = '$user'
    ");
      /*---INITIALISATION----*/
    $Regiment= dernier_numero_regiment($conn);
    date_default_timezone_set('Africa/Bamako');
    $heure = date('H:i:s');
    $date = date('d/m/Y');
   

    /* TOUJOURS charger les clients pour Modal */

    $sql = "SELECT * FROM clients";
    $result = mysqli_query($conn, $sql);

      /* Charger les donnée Table Brouillons pour Modal */
    $sql_brouillon = "SELECT * FROM brouillon_vente_or ORDER BY Id DESC";
    $result_brouillon = mysqli_query($conn, $sql_brouillon);


    $client_data = null;
    $result2 = null;

   if (isset($_GET['txtIdClient']) && !empty($_GET['txtIdClient'])) {

    $Id_client = $_GET['txtIdClient'];

    /* RECUPERATION DES DONNEES CLIENT */

    $sqlClient = "SELECT * FROM clients
                  WHERE Id = '$Id_client'";

    $resClient = mysqli_query($conn, $sqlClient);

    $client_data = mysqli_fetch_assoc($resClient);
    
    /* RECUPERER LE SOLDE DE LA CAISSE */

    $sqlCaisse = "SELECT * FROM caisse";

    $resCaisse = mysqli_query($conn, $sqlCaisse);

    $caisse_data = mysqli_fetch_assoc($resCaisse);
    }

     /* FAIRE LE DEPOT OR DANS LE COMPTE CLIENT */
    if (isset($_POST['btn_finale_vente'])) {

    $Date = $_POST['txt_Date'] ?? '';
    $Compte = $_POST['txt_numero'] ?? '';
    $Client = $_POST['txt_Client'] ?? '';
    $R_client = $_POST['txt_Val_Rep_client'] ?? '';

    $Prix = cleanNumber($_POST['txt_Base'] ?? 0);
    $Quantite = cleanNumber($_POST['txt_Quantite'] ?? 0);
    $Montant_retrait = cleanNumber($_POST['txt_Montant'] ?? 0);

    $Ancien_solde = cleanNumber($_POST['txt_solde_client'] ?? 0);
    $Ancien_solde_caisse = cleanNumber($_POST['txt_solde_caisse'] ?? 0);

    $Regiment = $_POST['txt_Rgmt'] ?? '';
    $Id_client = $_POST['txt_id_client'] ?? '';
    $Nom_R_client =  $R_client;

    $Nature = "VENTE OR";
    $Transactions = $Nature;
    $Utilisateur = ($_SESSION['Prenom'] ?? '') . " " . ($_SESSION['Nom'] ?? '');


        $Montant_depot = 0;

        $nouveau_solde = $Ancien_solde - $Montant_retrait;

        $save1 = modifier_solde_client($Id_client, $nouveau_solde);

        $save2 = faire_vente_or(
            $Date,
            $Compte,
            $Client,
            "VENTE OR",
            $Prix,
            $Quantite,
            0,
            $Montant_retrait,
            $nouveau_solde,
            $Transactions,
            $Regiment,
            $Id_client,
            $Utilisateur,
            $Nature,
            $Nom_R_client
        );

        $save = ($save1 && $save2);
    }

    if ($save) {

        $id_op = intval($_POST['txt_id_op'] ?? 0);

        if ($id_op > 0) {

            $sqlDel = "DELETE FROM brouillon_vente_or WHERE Id = '$id_op'";
            $resultDel = mysqli_query($conn, $sqlDel);

            if (!$resultDel) {
                die("Erreur DELETE : " . mysqli_error($conn));
            }
        }

        echo "<script>window.location='_v_gold.php';</script>";
        exit;
    }


    
    ?>

<body>
       

     <?php
     $page = "_v_gold";
     include "../includes/nav_gold.php";
     ?>
    <div class="container_gold">
        <form action="" method="post" autocomplete="off">
            <div class="header_content">
                <div class="btn_client_gold">
                    <button type="submit" name="btn_choisir_client" class="btn_choisir_client" id="btn_choisir_client" onclick="ouvrirModal(); return false;"> Choisir Client</button>
                </div>
            <div class="content_input">
                    <input type="hidden" name="txtIdClient" id="txtIdClient" value="<?php echo $client_data['Id'] ?? ''; ?>">
                     <input type="hidden" name="txt_prix_unitaire" id="txt_prix_unitaire" >
                    <input type="hidden" name="txtSoldeClient" id="txtSoldeClient" value="<?php echo $client_data['Solde'] ?? ''; ?>">
                    <input type="hidden" name="txtSoldeCaisse" id="txtSoldeCaisse" value="<?php echo $caisse_data['Solde'] ?? ''; ?>">
                <div class="input_one">
                    <label for="" class="lbl_compte"> N° Compte</label>
                    <input type="text" name="txt_numero_compte" class="numero_compte" id="numero_compte" value="<?php echo $client_data['Numero'] ?? ''; ?>" readonly><br>
                    <label for="" class="lbl_client"> Client</label>
                    <input type="text" name="txt_nom_client" class="nom_client" id="nom_client" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" readonly>
                    <label for="" class="lbl_date"> Date</label>
                    <input type="text" name="txt_date" class="date" id="date" value="<?php echo $date ?>" readonly>
                    
                </div>
                <div class="input_two">
                     <label for="" class="lbl_rep_client"> Representant Client</label>
                     <input type="text" name="txt_rep_client" class="rep_client" id="rep_client" value="<?php echo ($client_data['Prenom'] ?? '') . ' ' . ($client_data['Nom'] ?? ''). ' ' . ($client_data['Telephone'] ?? ''); ?>" ><br>
                    <label for="" class="lbl_regiment"> Rgmt:</label>
                    <input type="text" name="txt_rgmt" class="rgmt" id="rgmt" value="<?php echo sprintf("%04d", $Regiment); ?>" readonly><br>
                     <select name="txt_type_operation" id="type_operation" class="type_operation">
                                <option value="" selected disabled>choisir</option>
                                <option value="Achat">Achat</option>
                                <option value="Depot">Depot</option>
                                <option value="Engagement">Engagement</option>
                                <option value="Ecart">Ecart</option>
                                <option value="Achat B51">Achat B51</option>
                                <option value="Achat">Achat B55</option>
                    </select>
                </div>
            </div>
               
            </div>
            <div class="middle_content">
                <div class="middle_content_table">
            <table border="1" class="table_gold">

                <thead>
                    <tr>
                        <th>Base</th>
                        <th>Poids Air</th>
                        <th>Poids Eau</th>
                        <th>Densité</th>
                        <th>Carat</th>
                        <th>Montant</th>
                    </tr>
                </thead>

                <tbody id="tableauTransaction"></tbody>

            </table>
                </div>
        

                <div class="middle_content_side">
                    <button type="button" name="btn_creer" class="btn_creer" id="btn_creer" disabled onclick="active_input()"; return false;> Créer</button><br>
                    <button type="submit" name="btn_brouillon" class="btn_brouillon" id="btn_brouillon"  onclick="ouvrirModalBrouillon(); return false;"> Brouillons</button>
                    <div class="img_content">
                        <img src="../assets/Icone/lingot-dor.png" alt="" srcset="" width="110px">
                    </div>
                    <input type="text" class="txt_numero_barre" name="numero_barre" id="numero_barre" value="0" readonly >
                    <label for="" class="title_solde">SOLDE CLIENT</label><br>
                    <input type="text" class="txt_solde_client" name="solde_client" id="solde_client"value="<?php echo number_format($client_data['Solde'] ?? 0, 0, ',', ' '); ?>" readonly >
                </div>
            </div>
            <hr class="hr">
            <div class="footer_content">
                <div class="section_one">
                    <div class="input_one">
                        <label for="">Base</label><br>
                        <input type="text" name="txt_base" class="base" id="base" disabled  ><br>
                    </div>
                    <div class="input_two">
                        <label for="">Poids Air</label><br>
                        <input type="text" name="txt_poids_air" class="poids_air" id="poids_air" disabled ><br>
                    </div>
                    <div class="input_three">
                        <label for="">Poids Eau</label><br>
                        <input type="text" name="txt_poids_eau" class="poids_eau" id="poids_eau" disabled ><br>
                    </div>
                    <div class="input_four">
                        <label for="">Densité</label><br>
                        <input type="text" name="txt_densite" class="densite" id="densite"  readonly ><br>
                    </div>
                    <div class="input_five">
                        <label for="">Carat</label><br>
                        <input type="text" name="txt_carat" class="carat" id="carat" disabled ><br>
                    </div>
                    <div class="input_six">
                        <label for="">Montant</label><br>
                        <input type="text" name="txt_montant" class="achat_gold_montant" id="achat_gold_montant" readonly><br>
                    </div>
                    <div class="input_seven">
                        <div class="btn-plus">
                            <button type="button" class="btn_plus" id="btn_plus">
                                    <i class="bi bi-plus-circle-fill"></i>
                            </button>
                        </div>
                        <div class="groupe_btn">

                            <button type="button" name="btn_valide_achat" class="btn_valide_gold" id="btn_valide_vente"> Valider</button>
                            <button type="button" name="btn_affiche_achat" class="btn_affiche_gold" id="btn_affiche_achat"> Afficher</button>
                        </div>
                    </div>
                </div>

                <div class="section_two">
                    <label for="" class="lbl_sous_totaux">SOUS-TOTAUX</label>
                    <div class="input_section_two">
                        <input type="text" name="txt_base_montant" class="base_montant" id="base_montant" readonly >
                        <input type="text" name="txt_poids_air_montant" class="poids_air_montant" id="poids_air_montant" readonly >
                        <input type="text" name="txt_poids_eau_montant" class="poids_eau_montant" id="poids_eau_montant" readonly >
                        <input type="text" name="txt_densite_montant" class="densite_montant" id="densite_montant" readonly >
                        <input type="text" name="txt_carat_montant" class="carat_montant" id="carat_montant" readonly >
                        <input type="text" name="txt_somme_montant" class="somme_montant" id="somme_montant" readonly >
                    </div>
                </div>
            </div>
        </form>

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

                   <tr class="select_client" onclick="selectionnerClientGold(
                    '<?php echo $row['Prenom'] . ' ' . $row['Nom']. ' ' . $row['Telephone']; ?>',
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

        <!-- MODAL BROUILLON -->

        <div class="modalBrouillon" id="modalBrouillon">

            <div class="modal-content-brouillon"   >
        
                <span class="close-brouillon" onclick="fermerModalBrouillon()">
                    &times;
                </span> <br><br>
                 <h2>TABLEAU DE BROUILLON ACHAT OR</h2>
                <div class="table-responsive-brouillon">
                    <table border="1" class="table_brouillon" id="table">

                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Date</th>
                                <th>N° Compte</th>
                                <th>Client</th>
                                <th>Rep. Client</th>
                                <th>Quantité</th>
                                <th>Base</th>
                                <th>Montant</th>
                                <th>Mouvement</th>
                                <th>Regiment</th>
                                <th>Agent</th>
                            </tr>
                        </thead>

                        <tbody id="tbodyBrouillon">

                            <?php while($row = mysqli_fetch_assoc($result_brouillon)) { ?>

                            <tr class="select_client" onclick="selectionnerBrouillon(
                                '<?= $row['Id'] ?? '' ?>',
                                '<?= date('d/m/Y', strtotime($row['Dates'])) ?? '' ?>',
                                '<?= $row['CompteClient'] ?? '' ?>',
                                '<?= $row['ClientFournisseur'] ?? '' ?>',
                                '<?= $row['RepClientFournisseur'] ?? '' ?>',
                                '<?= $row['Quantites'] ?? '' ?>',
                                '<?= $row['Bases'] ?? '' ?>',
                                '<?= $row['Montants'] ?? '' ?>',
                                '<?= $row['Mouvements'] ?? '' ?>',
                                '<?= $row['Rgmt'] ?? '' ?>',
                                '<?= $row['Agents'] ?? '' ?>',
                                '<?= $row['idClient'] ?? '' ?>'
                            )">
                                <td><?= $row['Id'] ?? '' ?></td>
                                <td><?= $row['Date'] ?? '' ?></td>
                                <td><?= $row['CompteClient'] ?? '' ?></td>
                                <td><?= $row['ClientFournisseur'] ?? '' ?></td>
                                <td><?= $row['RepClientFournisseur'] ?? '' ?></td>
                                <td><?= number_format($row['Quantites']  ?? 0, 2, ',', ' ') ?></td>
                                <td><?= number_format((float)($row['Bases'] ?? 0), 0, ',', ' ') ?></td>
                                <td><?= number_format($row['Montants'] ?? 0, 0, ',', ' ') ?></td>
                                <td><?= $row['Mouvements'] ?? '' ?></td>
                                <td><?= $row['Rgmt'] ?? '' ?></td>
                                <td><?= $row['Agents'] ?? '' ?></td>

                            </tr>

                            <?php } ?>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL VALIDATION FINALE -->

        <div class="modalValidation" id="modalValidation">

            <div class="modal-content-validation"   >
        
                <span class="close-validation" onclick="fermerModalValidation()">
                    &times;
                </span> <br><br>
                <form action="" method="post">
                    <div class="content-validation">
                        <div class="input-content-validation">
                             <input type="hidden" class="numero" name="txt_numero" id="txt_numero" readonly>
                              <input type="hidden" class="id_client" name="txt_id_client" id="txt_id_client" readonly>
                             <input type="hidden" class="solde_client" name="txt_solde_client" id="txt_solde_client" readonly>
                            <input type="hidden" class="solde_caisse" name="txt_solde_caisse" id="txt_solde_caisse" readonly>
                            <input type="hidden" class="id_op" name="txt_id_op" id="txt_id_op" readonly >

                            <div class="input-content-validation-one">
                                <label for="">Operation Effectuée par :</label>
                                <input type="text" class="User" name="txt_User" id="txt_User" readonly>
                            </div>
                            <div class="input-content-validation-two">
                                <label for="">Date :</label>
                                <input type="text" class="Date" name="txt_Date" id="txt_Date" readonly> &nbsp;
                                 <label for="" class="lblFacture">N° Facture :</label>
                                <input type="text" class="Rgmt" name="txt_Rgmt" id="txt_Rgmt" readonly>
                            </div>
                            <div class="input-content-validation-three">
                                <label for="">Mouvement :</label>
                                <input type="text" class="Mouvement" name="txt_Mouvement" id="txt_Mouvement" readonly>
                            </div>
                            <div class="input-content-validation-four">
                                <label for="">Nom Client :</label>
                                <input type="text" class="Nom_client" name="txt_Client" id="txt_Client" readonly>
                            </div>
                            <div class="input-content-validation-five">
                                <label for="">Representant Client :</label>
                                <input type="text" class="Rep_client" name="txt_Val_Rep_client" id="txt_Rep_client" readonly>
                            </div>
                            <div class="input-content-validation-six">
                                <label for="">Base :</label>
                                <input type="text" class="Base" name="txt_Base" id="txt_Base" readonly>
                            </div>
                            <div class="input-content-validation-seven">
                                <label for="">Quantité :</label>
                                <input type="text" class="Quantite" name="txt_Quantite" id="txt_Quantite" readonly>
                            </div>
                             <div class="input-content-validation-eight">
                                <label for="">Montant :</label>
                                <input type="text" class="Montant" name="txt_Montant" id="txt_Montant" readonly>
                                <label for="">CFA</label>
                            </div>
                            <div class="input-content-validation-nine">
                                <button type="submit" name="btn_finale_vente" class="btn_finale_achat" id="btn_finale_vente"> Valider</button>
                            </div>
                        </div>
                        <div class="btn-content-modif-supprimer">
                            <div class="btn_supprimer_validation">
                                <button type="button" name="btn_supprimer_vente" class="btn_supprimer_achat" id="btn_supprimer_achat"> Supprimer</button>
                            </div>
                            <div class="btn_modifier_validation">
                                <button type="button" name="btn_modif_vente" class="btn_modif_achat" id="btn_modif_vente"> Modifier</button>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
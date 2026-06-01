<?php

include "../includes/header.php";
include(__DIR__ . "/../functions/functions.php");
$save ="";
$message = "";
$Numero = dernier_numero_compte($conn);
$Prenom ="";
$Nom="";
$Solde = "0";
$Adresse="";
$Telephone="";
$btn = "AJOUTER";


/*  FONCTIONS MODIFICATION  */

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $req = mysqli_query($conn, "SELECT * FROM Clients WHERE Id='$id'");
    $row = mysqli_fetch_assoc($req);
    $Numero = $row['Numero'];
    $Prenom = $row['Prenom'];
    $Nom = $row['Nom'];
    $Adresse = $row['Adresse'];
    $Telephone = $row['Telephone'];

    $btn = "MODIFIER";
}

/* AJOUTER */
if (isset($_POST['btn_ajouter'])) {
    $id = $_POST['txtId'];
    $Numero = $_POST['txtNumero'];
    $Prenom = $_POST['txtPrenom'];
    $Nom = $_POST['txtNom'];
    $Solde = $_POST['txtSolde'] ?? "";
    $Adresse = $_POST['txtAdresse'];
    $Telephone = $_POST['txtTelephone'];

    if (
    empty($Numero) ||
    empty($Prenom) ||
    empty($Nom) ||
    empty($Adresse) ||
    empty($Telephone)
) {

    $message= "Veuillez remplir tous les champs";

}
else {
    $save = ajout_client(
        $Numero,
        $Prenom,
        $Nom,
        $Solde,
        $Adresse,
        $Telephone
    );
}

    if ($save) {
         echo "
        <script>
            alert('Client enregistré avec succès');
            window.location='client.php';
        </script>
        ";
        $Numero = dernier_numero_compte($conn);
    } else {
        
    }
}

# Modification
if (isset($_POST['btn_modifier'])) {

    $id = $_POST['txtId'];
    $Prenom = $_POST['txtPrenom'];
    $Nom = $_POST['txtNom'];
    $Adresse = $_POST['txtAdresse'];
    $Telephone = $_POST['txtTelephone'];

    mysqli_query($conn, "UPDATE Clients
    SET Prenom='$Prenom', Nom='$Nom', Adresse='$Adresse', Telephone='$Telephone'
    WHERE id='$id'");

     echo "
        <script>
            alert('Modification effectuée avec succès');
            window.location='client.php';
        </script>
        ";
}
#SUPPRESSION 
if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    mysqli_query($conn,
    "DELETE FROM Clients WHERE id=$id");

    header("Location: inscription.php");
    exit();
}

/* TOUJOURS charger les utilisateurs */

$sql = "SELECT * FROM Clients";
$result = mysqli_query($conn, $sql);

?>


<body>
    
     <?php if($message != "") { ?>

            <div class="message">
                <?php echo $message; ?>
            </div>

            <?php } ?>
    <div class="container">
        <?php
        include "../includes/aside.php";
        $page = "client";
        include "../includes/nav.php";
        ?>
        
        <div class="main_client">
            
            <div class="container_client">
                <h2>Formulaire</h2>
                    
                    <form action="#" method="post"  autocomplete="off">
                        <label class="lbl">N° Compte</label> 
                        <input type="text" class="Numero" name="txtNumero" value="<?php echo $Numero; ?>" 
                        maxlength="20"
                        inputmode="numeric"
                        pattern="[0-9]*"> 

                        <label class="lbl">Prenom</label> 
                        <input type="text" class="Prenom" name="txtPrenom" value="<?php echo $Prenom ?>"> 

                        <label class="lbl">Nom</label> 
                        <input type="text" class="Nom" name="txtNom" value="<?php echo $Nom ?>"> 

                        <input type="hidden" class="Solde" name="txtSolde" value="<?php echo $Solde ?>"> 

                        <label class="lbl">Adresse</label> 
                        <input type="text" class="Adresse" name="txtAdresse" value="<?php echo $Adresse ?>"> 

                        <label class="lbl">Telephone</label> 
                        <input type="text" class="Telephone" name="txtTelephone" value="<?php echo $Telephone ?>"> 

                        <input type="hidden" name="txtId" value="<?php echo $id ?>"><br>

                         <?php if($btn=="AJOUTER"){ ?>
                        
                        <button type="submit" name="btn_ajouter" class="btn_ajouter_client">AJOUTER</button>
                         <?php } else { ?>
                         <button type="submit" name="btn_modifier" class="btn_modifier_client">MODIFIER</button>
                          <?php } ?>

                    </form> 
            </div>
            <div class="slide-table-client">
                 <h3>Liste des comptes clients</h3>
                
                    <table border="1" class="table-client">

                        <tr>
                            <th>N° Comptes</th>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Solde</th>
                            <th>Adresse</th>
                            <th>Telephone</th>
                            <th><i class="bi bi-pencil-square"></i></th>
                            <th><i class="bi bi-person-x-fill"></i></th>
                        </tr>

                        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                                <tr><td><?php echo $row['Numero']; ?></td>
                                    <td><?php echo $row['Prenom']; ?></td>
                                    <td><?php echo $row['Nom']; ?></td>
                                    <td><?php echo $row['Solde']; ?></td>
                                    <td><?php echo $row['Adresse']; ?></td>
                                    <td><?php echo $row['Telephone']; ?></td>
                                    <td><a href="?edit=<?php echo $row['Id'] ?>" id="btn_modifier"><img src="../assets/icone/img_modifier.png" class="ico" alt="modifier" srcset=""></a></td>
                                     <td><a href="?delete=<?php echo $row['Id'] ?>" id="btn_supprimer" onclick="return confirm('Supprimer cette ligne ?')"><img src="../assets/icone/img_supprimer.png" class="ico" alt="Supprimer" srcset=""></a></td>
                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="4" >Aucun utilisateur</td>
                            </tr>

                        <?php } ?>

                    </table>
            </div>
        </div>
    </div>
</body>
</html>
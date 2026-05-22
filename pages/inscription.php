<?php

include "../includes/header.php";
include(__DIR__ . "/../functions/functions.php");
$save ="";
$message = "";
$Prenom ="";
$Nom="";
$Identifiant="";
$Password="";
$btn = "AJOUTER";

/*  FONCTIONS MODIFICATION  */

if (isset($_GET['edit'])) {

    $id = $_GET['edit'];

    $req = mysqli_query($conn, "SELECT * FROM Utilisateurs WHERE Id='$id'");
    $row = mysqli_fetch_assoc($req);

    $Prenom = $row['Prenom'];
    $Nom = $row['Nom'];
    $Identifiant = $row['Identifiant'];
    $Password = $row['Password'];

    $btn = "MODIFIER";
}

/* AJOUTER */
if (isset($_POST['btn_ajouter'])) {
    $id = $_POST['txtId'];
    $Prenom = $_POST['txtPrenom'];
    $Nom = $_POST['txtNom'];
    $Identifiant = $_POST['txtIdentifiant'];
    $Password = $_POST['txtPassword'];

    if (
    empty($Prenom) ||
    empty($Nom) ||
    empty($Identifiant) ||
    empty($Password)
) {

    $message= "Veuillez remplir tous les champs";

}
else {
    $save = ajout_user(
        $Prenom,
        $Nom,
        $Identifiant,
        $Password
    );
}

    if ($save) {
         echo "
        <script>
            alert('Utilisateur enregistré avec succès');
            window.location='inscription.php';
        </script>
        ";
    } else {
        
    }
}

# Modification
if (isset($_POST['btn_modifier'])) {

    $id = $_POST['txtId'];
    $Prenom = $_POST['txtPrenom'];
    $Nom = $_POST['txtNom'];
    $Identifiant = $_POST['txtIdentifiant'];
    $Password = $_POST['txtPassword'];

    mysqli_query($conn, "UPDATE Utilisateurs
    SET Prenom='$Prenom', Nom='$Nom', Identifiant='$Identifiant', Password='$Password'
    WHERE id='$id'");

     echo "
        <script>
            alert('Modification effectuée avec succès');
            window.location='inscription.php';
        </script>
        ";
}
#SUPPRESSION 
if(isset($_GET['delete'])){

    $id = intval($_GET['delete']);

    mysqli_query($conn,
    "DELETE FROM Utilisateurs WHERE id=$id");

    header("Location: inscription.php");
    exit();
}

/* TOUJOURS charger les utilisateurs */

$sql = "SELECT * FROM Utilisateurs";
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
        include "../includes/nav.php";
        ?>
        <div class="main">
            
            <div class="sign_container">
                <h2>AJOUT UTILISATEUR</h2>
                    
                    <form action="#" method="post"  autocomplete="off">
                        <label class="lbl">Prenom</label> 
                        <input type="text" class="Prenom" name="txtPrenom" value="<?php echo $Prenom ?>"> 

                        <label class="lbl">Nom</label> 
                        <input type="text" class="Nom" name="txtNom" value="<?php echo $Nom ?>"> 

                        <label class="lbl">Identifiant</label> 
                        <input type="text" class="Identifiant" name="txtIdentifiant" value="<?php echo $Identifiant ?>"> 

                        <label class="lbl">Mot de passe</label> 
                        <input type="text" class="Password" name="txtPassword" value="<?php echo $Password ?>"> 

                        <input type="hidden" name="txtId" value="<?php echo $id ?>"><br>

                         <?php if($btn=="AJOUTER"){ ?>
                        
                        <button type="submit" name="btn_ajouter" class="btn_ajouter">AJOUTER</button>
                         <?php } else { ?>
                         <button type="submit" name="btn_modifier" class="btn_modifier">MODIFIER</button>
                          <?php } ?>

                    </form> 
            </div>
            <div class="slide-table">
                 <h3>Liste des utilisateurs</h3>
                
                    <table border="1">

                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>Identifiant</th>
                            <th>Mot de passe</th>
                            <th>Modifier</th>
                            <th>Supprimer</th>
                        </tr>

                        <?php if ($result && mysqli_num_rows($result) > 0) { ?>

                            <?php while($row = mysqli_fetch_assoc($result)) { ?>

                                <tr>
                                    <td><?php echo $row['Prenom']; ?></td>
                                    <td><?php echo $row['Nom']; ?></td>
                                    <td><?php echo $row['Identifiant']; ?></td>
                                    <td><?php echo $row['Password']; ?></td>
                                    <td><a href="?edit=<?php echo $row['Id'] ?>" id="btn_modifier"><img src="../assets/icone/img_modifier.png" class="ico" alt="modifier" srcset=""></a></td>
                                     <td><a href="?delete=<?php echo $row['Id'] ?>" id="btn_supprimer" onclick="return confirm('Supprimer cette ligne ?')"><img src="../assets/icone/img_supprimer.png" class="ico" alt="Supprimer" srcset=""></a></td>
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
</body>
</html>
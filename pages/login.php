<!DOCTYPE html>
<!--Nav bar-->
    <?php 
    session_start();
    include "../includes/header.php";
    include "../serveur/bdd.php";
    $message="";
    // Connexion
if(isset($_POST['btn_connexion']))
{
    $identifiant = $_POST['txtIdentifiant'];
    $password    = $_POST['txtPassword'];

    // Sécurisation
    $identifiant = mysqli_real_escape_string($conn, $identifiant);
    $password    = mysqli_real_escape_string($conn, $password);

    // Requête
    $sql = "SELECT * FROM Utilisateurs
            WHERE Identifiant = '$identifiant'
            AND Password = '$password'";

    $resultat = mysqli_query($conn, $sql);

    // Vérification
    if(mysqli_num_rows($resultat) > 0)
    {
        $user = mysqli_fetch_assoc($resultat);
        //STOCKER DANS SESSION
        $_SESSION['Prenom'] = $user['Prenom'];
        $_SESSION['Nom'] = $user['Nom'];
        $_SESSION['Identifiant'] = $user['Identifiant'];
        $_SESSION['Role'] = $user['Role'];
        header("Location: main.php");
        exit();
    }
    else
    {
        $message = "Identifiant ou mot de passe incorrect";
    }
}

?>


<body>
    <div class="login-container">
        <form action="" method="POST" class="form-login" autocomplete="off">
             <!-- MESSAGE ERREUR -->
                
                <div class="message-erreur">
                    <?php echo $message; ?>
                </div>
            <h1 class="title">IDENTIFICATION</h1>
            <div class="input-grup">
                <i class="bi bi-person-fill"></i>
                <input type="text" class="input-field" name="txtIdentifiant" placeholder="Identifiant" id="">
            </div>
            <div class="input-grup">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="input-field"  name="txtPassword" placeholder="Mot de passe" id="">
            </div>
           <button type="submit" class="submit" name="btn_connexion">
            <i class="bi bi-box-arrow-in-right"></i>
                    Se connecter
            </button>

         </form>
           <div></div>
  
        <div class="banner">
                <i class="bi"> <img src="../assets/icone/gold.ico" alt="" srcset=""></i>
                <p class="text">DIALLO SERVICE</p>
        </div>
    </div>
</body>
</html>
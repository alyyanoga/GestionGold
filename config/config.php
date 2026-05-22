<?php
function est_connecte(): bool{
    if (session_status()=== PHP_SESSION_NONE){
        session_start();
    }
    return !empty($_SESSION['connecte']);
}


function connexion(): void {
    if(!est_connecte()){
        header('Location: connexion.php');
        exit();    
        }else{
    $nom_serveur = "localhost";
    $utilisateur= "root";
    $mot_de_passe="";
    $nom_base_donnee="dbf";
    $conn= mysqli_connect($nom_serveur,$utilisateur,$mot_de_passe,$nom_base_donnee);
    if(!$conn){
        die("conneection failed: ".mysqli_connect_error());
    }
    
    if(isset($_POST['champUser']) && isset($_POST['champPassword'])){
    
        ///nous allons mettre email et mot de passe dans des variables
        $email = $_POST['champUser'];
        $mdp = $_POST['champPassword'];
    
        $req=mysqli_query($conn,"SELECT * FROM users WHERE identifiant ='$email' AND password= '$mdp'");
        $num_ligne = mysqli_num_rows($req); ////compter le nombre de ligne ayant rapport à la requette SQL
        if($num_ligne > 0){
            session_start();
            $_SESSION['connecte'] = 1;
            echo '<h3> Bienvenu '.$email.'</h3>';
          header("Location:accueil.php"); ///si le nombre de ligne est >0, on sera rediriger vers login.php
          
        }else{ ///sinon
            echo "Adresse Mail ou mot de passe incorrecte !";
        }
    }
    }
}
///}
?>
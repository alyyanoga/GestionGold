
<?php
session_start();

// Supprimer toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Redirection vers login
header("Location: login.php");
exit();
?>
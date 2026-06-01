<?php
include "../includes/header.php";
include(__DIR__ . "/../functions/functions.php");
?>

<body>

 <?php
        include "../includes/aside.php";
        $page = "_achat_devise";
        include "../includes/nav_devise.php";
        ?>

        <div class="container_devise">
        <div class="header">
            <div class="btn-choisir">
              <button type="button" class="btn_choisir" onclick="ouvrirModal()">Choisir client</button>
            </div>
        </div>

        </div>
    
</body>
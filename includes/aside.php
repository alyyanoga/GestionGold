  <!-- SIDEBAR -->
  <?php 
  
  ?>
  <div class="sidebar" id="sidebar">
        <div class="user">
            <i class="bi bi-person-circle"></i>
            <?php
  
             echo $_SESSION['Prenom'] . " " . $_SESSION['Nom'];
            ?>
        </div>

        <ul class="menu">
             <li>
                <a href="index.php">

                    <i class="bi bi-house-door-fill"></i>

                    Accueil

                </a>
            </li>

            <li>
                <a href="client.php">

                    <i class="bi bi-person-lines-fill"></i>

                    Espace Clients

                </a>
            </li>
            <li>
                <a href="_depot.php">

                   <i class="bi bi-arrow-down-up"></i>

                    Depot / Retrait Argent

                </a>
            </li>

            <li>
                <a href="#">

                    <i class="bi bi-gem"></i>

                    Gestion Or

                </a>
            </li>

            <li>
                <a href="#">

                    <i class="bi bi-currency-exchange"></i>

                    Gestion Devise

                </a>
            </li>

            <li>
                <a href="#">

                    <i class="bi bi-arrows-angle-contract"></i>

                    Virement Inter-Clients

                </a>
            </li>

            <li>
                <a href="inscription.php">

                    <i class="bi bi-gear-fill"></i>

                    Paramètres

                </a>
            </li>

            <li>
                <a href="logout.php">

                    <i class="bi bi-box-arrow-right"></i>

                    Déconnexion

                </a>
            </li>

        </ul>

    </div>


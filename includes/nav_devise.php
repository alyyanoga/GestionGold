  <!-- NAVBAR -->
  <!-- BOUTON MOBILE -->
<button class="btn-toggle" onclick="toggleSidebar()">
    ☰
</button>
    <nav class="top-navbar">

        <!-- MENU -->
        <ul class="menu-top-navbar">

            <li>
                <a href="../pages/_a_devise.php"  class="<?php if($page == '_achat_devise') echo 'active'; ?>">

                    <i class="bi bi-people-fill"></i>

                    Achat Devise

                </a>
            </li>

            <li>
                <a href="../pages/_v_devise.php"  class="<?php if($page == '_vente_devise') echo 'active'; ?>">

                    <i class="bi bi-wallet2"></i>

                    Vente Devise

                </a>
            </li>

            <li>
               <a href="../pages/_proforma_devise.php" class="<?php if($page == '_proforma_devise') echo 'active'; ?>">

                   <i class="bi bi-bar-chart-line-fill"></i>

                   Proforma

               </a>
           </li>

        </ul>

    </nav>
  <!-- NAVBAR -->
  <!-- BOUTON MOBILE -->
<button class="btn-toggle" onclick="toggleSidebar()">
    ☰
</button>
    <nav class="top-navbar">

        <!-- MENU -->
        <ul class="menu-top-navbar">

            <li>
                <a href="../pages/_operations_caisse.php"  class="<?php if($page == '_operations_caisse') echo 'active'; ?>">

                    <i class="bi bi-cash"></i>

                    Caisses

                </a>
            </li>

            <li>
                <a href="#"  class="<?php if($page == '_operations_banque') echo 'active'; ?>">

                    <i class="bi bi-bank2"></i>

                    Banques

                </a>
            </li>

            <li>
               <a href="#" class="<?php if($page == '_rapport') echo 'active'; ?>">

                   <i class="bi bi-bar-chart-line-fill"></i>

                   Rapport

               </a>
           </li>
        </ul>

    </nav>
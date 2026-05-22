  <!-- NAVBAR -->
  <!-- BOUTON MOBILE -->
<button class="btn-toggle" onclick="toggleSidebar()">
    ☰
</button>
    <nav class="top-navbar">

        <!-- MENU -->
        <ul class="menu-top-navbar">

            <li>
                <a href="../pages/_depot.php"  class="<?php if($page == '_depot') echo 'active'; ?>">

                    <i class="bi bi-people-fill"></i>

                    Depot

                </a>
            </li>

            <li>
                <a href="../pages/_retrait.php"  class="<?php if($page == '_retrait') echo 'active'; ?>">

                    <i class="bi bi-wallet2"></i>

                    Retrait

                </a>
            </li>

            <li>
               <a href="_virement_clients.php" class="<?php if($page == '_virement_clients') echo 'active'; ?>">

                   <i class="bi bi-bar-chart-line-fill"></i>

                   Virement Entre Clients

               </a>
           </li>
            <li>
                <a href="_operations_clients.php" class="<?php if($page == '_operations_clients') echo 'active'; ?>">

                    <i class="bi bi-bar-chart-line-fill"></i>

                    Operations Clients

                </a>
            </li>

        </ul>

    </nav>
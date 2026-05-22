  <!-- NAVBAR -->
  <!-- BOUTON MOBILE -->
<button class="btn-toggle" onclick="toggleSidebar()">
    ☰
</button>
    <nav class="top-navbar">

        <!-- MENU -->
        <ul class="menu-top-navbar">

            <li>
                <a href="../pages/client.php"  class="<?php if($page == 'client') echo 'active'; ?>">

                    <i class="bi bi-people-fill"></i>

                    Gestion Clients

                </a>
            </li>

            <li>
                <a href="../pages/operations_clients.php"  class="<?php if($page == 'operations_clients') echo 'active'; ?>">

                    <i class="bi bi-wallet2"></i>

                    Operations Clients

                </a>
            </li>

            <li>
                <a href="_etat_comptes.php" class="<?php if($page == '_etat_comptes') echo 'active'; ?>">

                    <i class="bi bi-bar-chart-line-fill"></i>

                    Etat des Comptes

                </a>
            </li>

        </ul>

    </nav>
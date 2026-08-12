  <!-- NAVBAR -->
  <!-- BOUTON MOBILE -->
<button class="btn-toggle" onclick="toggleSidebar()">
    ☰
</button>
    <nav class="top-navbar">

        <!-- MENU -->
        <ul class="menu-top-navbar">

            <li>
                <a href="../pages/_a_gold.php"  class="<?php if($page == '_a_gold') echo 'active'; ?>">

                   <i class="bi bi-clipboard-plus-fill"></i>

                    ACHAT OR

                </a>
            </li>

            <li>
                <a href="../pages/_v_gold.php"  class="<?php if($page == '_v_gold') echo 'active'; ?>">

                   <i class="bi bi-clipboard-minus-fill"></i>

                    VENTE OR

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
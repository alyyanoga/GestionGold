function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("active");
}

function ouvrirModal(){

    document.getElementById("modalClient").style.display = "block";

}

function fermerModal(){

    document.getElementById("modalClient").style.display = "none";
}

function selectionnerClient(Nom, Numero, IdClient, Solde){

    document.getElementById("txtNomComplet").value = Nom;
    document.getElementById("txtRepClient").value = Nom;
    document.getElementById("txtCompte").value = Numero;
    document.getElementById("txtIdClient").value = IdClient;
    document.getElementById("txtSolde").value = Solde;
    
    // Déverrouiller les champs

    document.getElementById("txtMontant").disabled = false;
    document.getElementById("txtDate").disabled = false;
    document.getElementById("txtRepClient").disabled = false;
    document.getElementById("txtcheckbox").disabled = false;
    document.getElementById("btn_valider").disabled = false;
    document.getElementById("modalClient").style.display = "none";

      // envoyer vers PHP
    // afficher les opérations du client
     window.location.href = window.location.pathname + "?txtIdClient=" + IdClient;
}

//----------SEPARATEUR DE MILLIER A LA SAISIE DE MONTANT 
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('txtMontant');

    input.addEventListener('input', function () {

        // garder uniquement les chiffres
        let valeur = this.value.replace(/[^\d]/g, '');

        if (valeur === '') {
            this.value = '';
            return;
        }

        // éviter Number() direct (cause perte sur gros nombres parfois)
        this.value = valeur.replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    });

});


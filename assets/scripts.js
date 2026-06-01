function toggleSidebar(){
    document.getElementById("sidebar").classList.toggle("active");
}

function ouvrirModal(){

    document.getElementById("modalClient").style.display = "block";

}

function fermerModal(){

    document.getElementById("modalClient").style.display = "none";
}

let typeSelection = "";

function ouvrirModal(type){

    typeSelection = type;

    document.getElementById("modalClient").style.display = "block";

}

function selectionnerClient(Nom, Numero, IdClient, Solde){
    // Client.php
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

    // Virement_client.php

    

      // envoyer vers PHP
    // afficher les opérations du client
     window.location.href = window.location.pathname + "?txtIdClient=" + IdClient;
}


function selectionnerVirementClient(Nom, IdClient, Numero, Solde){

      if(typeSelection === "debiteur"){
        let crediteur = document.getElementById("txtCrediteur").value;

        if(crediteur === Nom){

        alert("Le débiteur et le créditeur doivent être différents");

        return;
    }

        document.getElementById("txtDebiteur").value = Nom;
        document.getElementById("txtIdClientDebiteur").value = IdClient;
        document.getElementById("txtCompteDebiteur").value = Numero;
        document.getElementById("txtSoldeDebiteur").value = Solde;


    }
    else if(typeSelection === "crediteur"){

        
        let debiteur = document.getElementById("txtDebiteur").value;

        if(debiteur === Nom){

        alert("Le débiteur et le créditeur doivent être différents");

        return;
    }
        document.getElementById("txtCrediteur").value = Nom;
        document.getElementById("txtIdClientCrediteur").value = IdClient;
        document.getElementById("txtCompteCrediteur").value = Numero;
        document.getElementById("txtSoldeCrediteur").value = Solde;

    }
    document.getElementById("modalClient").style.display = "none";
    

    //window.location.href = window.location.pathname + "?txtIdClient=" + IdClient;
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


/*------SELECTION CAISSE */

function selectionnerCaisse(Caisse){
    document.getElementById("txtCaisse").value = Caisse;

    
    document.getElementById("modalClient").style.display = "none";

      // envoyer vers PHP
    // afficher les opérations du client
   //  window.location.href = window.location.pathname + "?txtIdCaisse=" + Id;
}

document.addEventListener("DOMContentLoaded", function () {

    let selectOperation = document.getElementById("typeOperation");
    let btnValider = document.getElementById("btn_valider_caisse");

    selectOperation.addEventListener("change", function () {

        if (this.value === "Depot" || this.value === "Retrait") {

            btnValider.disabled = false;

        } else {

            btnValider.disabled = true;
        }

    });

});

function filtrerClient() {

    let input = document.getElementById("rechercheClient");
    let filtre = input.value.toLowerCase();

    let table = document.getElementById("tableClient");
    let tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {

        let tdPrenom = tr[i].getElementsByTagName("td")[1];
        let tdNom = tr[i].getElementsByTagName("td")[2];

        if (tdPrenom || tdNom) {

            let prenom = tdPrenom.textContent.toLowerCase();
            let nom = tdNom.textContent.toLowerCase();

            if (prenom.includes(filtre) || nom.includes(filtre)) {

                tr[i].style.display = "";

            } else {

                tr[i].style.display = "none";
            }
        }
    }
}
          

 
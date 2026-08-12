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

//SELECTIONNER CLIENT GOLD
function selectionnerClientGold(Nom, rep_client, Numero, IdClient, SoldeClient){

    let typeOperation = document.getElementById("type_operation").value;

    console.log("Type opération :", typeOperation);

    document.getElementById("nom_client").value = Nom;
    document.getElementById("rep_client").value = rep_client;
    document.getElementById("numero_compte").value = Numero;
    document.getElementById("txtIdClient").value = IdClient;
    document.getElementById("solde_client").value = SoldeClient;

    document.getElementById("modalClient").style.display = "none";

    console.log(document.getElementById("type_operation").value);
    fetch("../functions/modifier_temp_transaction.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body:
            "txt_nom_client=" + encodeURIComponent(Nom) +
            "&txt_rep_client=" + encodeURIComponent(rep_client) +
            "&txt_numero_compte=" + encodeURIComponent(Numero) +
            "&txtIdClient=" + encodeURIComponent(IdClient) +
            "&solde_client=" + encodeURIComponent(SoldeClient) +
            "&txt_type_operation=" + encodeURIComponent(typeOperation)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    });

    // Modifier l'URL sans recharger la page
    let nouvelleUrl = window.location.pathname + "?txtIdClient=" + IdClient;
    history.pushState(null, "", nouvelleUrl);

    console.log("ID Client :", IdClient);

    // Ici tu peux appeler une fonction AJAX si tu veux charger les opérations
    // chargerOperationsClient(IdClient);
}

//----------SEPARATEUR DE MILLIER A LA SAISIE DE MONTANT 
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('txtMontant');

    input.addEventListener('input', function () {

        let valeur = this.value.replace(/\s/g, '');

        // Vérifier si le nombre est négatif
        const negatif = valeur.startsWith('-');

        // Autoriser chiffres, point, virgule et -
        valeur = valeur.replace(/[^0-9.,-]/g, '');

        // Supprimer tous les - puis le remettre au début si nécessaire
        valeur = valeur.replace(/-/g, '');

        // Transformer la virgule en point
        valeur = valeur.replace(',', '.');

        // Un seul point décimal
        const parties = valeur.split('.');
        if (parties.length > 2) {
            valeur = parties[0] + '.' + parties.slice(1).join('');
        }

        const hasDecimal = valeur.includes('.');

        let [entier, decimal] = valeur.split('.');

        // Séparateur de milliers
        entier = entier.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

        let resultat = hasDecimal
            ? entier + '.' + (decimal || '')
            : entier;

        this.value = negatif ? '-' + resultat : resultat;

    });

});

//----------SEPARATEUR DE MILLIER A LA SAISIE DE MONTANT USD
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('txtMontantUSD');

    // 🔴 sécurité obligatoire
    if (!input) {
        console.log("❌ txtMontantUSD introuvable");
        return;
    }

    input.addEventListener('input', function () {

        let valeur = this.value;

        // vérifier signe négatif
        let negatif = valeur.startsWith('-');

        // garder uniquement les chiffres
        valeur = valeur.replace(/[^\d]/g, '');

        // si vide
        if (valeur === '') {
            this.value = negatif ? '-' : '';
            return;
        }

        // format milliers
        valeur = valeur.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

        // réappliquer signe
        this.value = negatif ? '-' + valeur : valeur;
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

    const selectOperation = document.getElementById("typeOperation");
    const btnValider = document.getElementById("btn_valider_caisse");

    // 🔴 sécurité : éviter crash si élément introuvable
    if (!selectOperation) {
        console.log("❌ typeOperation introuvable");
        return;
    }

    if (!btnValider) {
        console.log("❌ btn_valider_caisse introuvable");
        return;
    }

    // état initial du bouton
    btnValider.disabled = true;

    selectOperation.addEventListener("change", function () {

        const value = this.value;

        if (value === "Depot" || value === "Retrait") {
            btnValider.disabled = false;
        } else {
            btnValider.disabled = true;
        }

    });

});

//SELECTION TYPE OPRATION GOLD

document.addEventListener("DOMContentLoaded", function () {

    const selectOperations = document.getElementById("type_operation");
    const btnCreer = document.getElementById("btn_creer");

    if (!selectOperations) {
        console.log("❌ select_operation introuvable");
        return;
    }

    if (!btnCreer) {
        console.log("❌ btn_creer introuvable");
        return;
    }

    btnCreer.disabled = true;

    selectOperations.addEventListener("change", function () {

        if (this.value !== "choisir") {
            btnCreer.disabled = false;
        } else {
            btnCreer.disabled = true;
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


// SELECTION CLIENT DEVISE
function selectionnerClientDevise(Compte,Nom, IdClient, Solde){

  document.getElementById("txtCompte").value = Compte;
  document.getElementById("txtNomComplet").value = Nom;
  document.getElementById("txtRepClient").value = Nom;
  document.getElementById("txtIdClient").value = IdClient;

  


    window.location.href = window.location.pathname +
    "?txtIdClient=" + IdClient + "&focus=montant";

   document.getElementById("modalClient").style.display = "none";

}
/// FOCUS SUR INPUT MONTANT DOLLARS
document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);

    if (params.get("focus") === "montant") {
        const txt = document.getElementById("txtMontantUSD");
        if (txt) {
            txt.focus();
        }
    }
});

//CALCULER LE MONTANT CFA
function calculerDHS() {

    let montantUSD = document.getElementById("txtMontantUSD").value;

    // Supprimer les espaces des milliers
    montantUSD = montantUSD.replace(/\s/g, '');

    montantUSD = parseFloat(montantUSD) || 0;

    let tauxDHS = 3.67;

    let montantDHS = (montantUSD * tauxDHS).toFixed(2);

    document.getElementById("txtMontantDHS").value =
        montantDHS.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}

//CALCULER LE MONTANT CFA
function calculerCFA() {

    let montantUSD = document.getElementById("txtMontantUSD").value.replace(/\s/g, '');
    let tauxCFA = document.getElementById("txtTaux").value.replace(/\s/g, '');

    montantUSD = parseFloat(montantUSD) || 0;
    tauxCFA = parseFloat(tauxCFA) || 0;

    let montant = (montantUSD * tauxCFA).toFixed(2);

    document.getElementById("txtMontantCFA").value =
        montant.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
}


// POUR AFFICHER LA LISTE CLIENT DEVISE EN RESPONSIVE
function AfficheModal(){
    document.getElementById("modalClient").style.display = "block";
}

function filtrerClientDevise() {

    let filtre = document
        .getElementById("rechercheClientDevise")
        .value
        .toLowerCase();

    let lignes = document.querySelectorAll("#tableClientDevise tr");

    lignes.forEach((ligne, index) => {

        if (index === 0) return; // ignorer l'en-tête

        let texte = ligne.textContent.toLowerCase();

        ligne.style.display =
            texte.includes(filtre) ? "" : "none";
    });
}


//----------PARCOURIR LES CHAMPS INPUTS AVEC BOUTON ENTRER (FORMULAIRE DEVISE)
document.addEventListener('DOMContentLoaded', function () {

    const champs = [
        document.getElementById('txtMontantUSD'),
        document.getElementById('txtTaux')
    ];

    const btnValider = document.getElementById('btn_valider_devise');

    champs.forEach((champ, index) => {

        champ.addEventListener('keydown', function (e) {

            if (e.key === 'Enter') {

                e.preventDefault();

                if (index < champs.length - 1) {
                    champs[index + 1].focus();
                } else {
                    btnValider.click();
                }

            }

        });

    });

});




// ACTIVATION DES INPUTS (Base, poids, densite, carat, Montant)
function active_input(){
    document.getElementById("base").disabled = false;
    document.getElementById("poids_air").disabled = false;
    document.getElementById("poids_eau").disabled = false;
    document.getElementById("carat").disabled = false;

     // optionnel : remettre focus
    document.getElementById("base").focus();
}
///SEPARATEUR DE MILLIER DANS LE TABLEAU
function formatNombre(valeur, decimals = 0) {

    if (valeur === null || valeur === undefined || valeur === '') return '';

    valeur = Number(valeur);

    let facteur = Math.pow(10, decimals);

    // suppression des décimales supplémentaires sans arrondi
    valeur = Math.trunc(valeur * facteur) / facteur;

    return valeur.toLocaleString('fr-FR', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

//AJOUT DANS TABLEAU TEMPTRANSACTIOROR

document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btn_plus");

    if (!btn) return;

    btn.addEventListener("click", function (e) {

        e.preventDefault();

        // 🔴 récupérer au clic
        let base = parseFloat(document.getElementById('base').value.replace(/\s/g, "")) || 0;
        let poids_air = parseFloat(document.getElementById('poids_air').value.replace(/\s/g, "")) || 0;
        let poids_eau = parseFloat(document.getElementById('poids_eau').value.replace(/\s/g, "")) || 0;
        let prix = nombre(document.getElementById('txt_prix_unitaire'));
        let densite = nombre(document.getElementById('densite'));
        let carat = nombre(document.getElementById('carat'));
        let montant = parseFloat(document.getElementById('achat_gold_montant').value.replace(/\s/g, "")) || 0;


        let id_client = document.getElementById('txtIdClient').value.trim();
        let numero = document.getElementById('numero_compte').value.trim();
        let client = document.getElementById('nom_client').value.trim();
        let rep_client = document.getElementById('rep_client').value.trim();
        let regiment = document.getElementById('rgmt').value.trim();
        let type_operation = document.getElementById('type_operation').value.trim();
        let date = document.getElementById('date').value.trim();




        // 🔴 validation
        if (
    !client ||
    base === "" || base == null ||
    poids_air === "" || poids_air == null ||
    poids_eau === "" || poids_eau == null ||
    carat === "" || carat == null
) {
    alert("Champs incomplets");
    return;
}
        fetch('../functions/ajout_barre.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            
            body:
                '&txt_base=' + encodeURIComponent(base) +
                '&txt_poids_air=' + encodeURIComponent(poids_air) +
                '&txt_poids_eau=' + encodeURIComponent(poids_eau) +
                '&txt_densite=' + encodeURIComponent(densite) +
                '&txt_prix_unitaire=' + encodeURIComponent(prix) +
                '&txt_carat=' + encodeURIComponent(carat)+
                '&txt_montant=' + encodeURIComponent(montant)+
                '&txtIdClient='+ encodeURIComponent(id_client)+
                '&txt_nom_client='+ encodeURIComponent(client)+
                '&txt_rep_client='+ encodeURIComponent(rep_client)+
                '&txt_rgmt=' + encodeURIComponent(regiment)+
                '&txt_numero_compte=' + encodeURIComponent(numero)+
                '&txt_type_operation=' + encodeURIComponent(type_operation)+
                '&txt_date=' + encodeURIComponent(date)


        })

        .then(res => res.json())
        .then(data => {

            console.log(data);

              if (!data.success) {
        alert(data.message);
        return;
    }

           if (data.success && data.id) {

          console.log("Données reçues :", data);
    document.getElementById('tableauTransaction')
    .insertAdjacentHTML('beforeend', `
            <tr data-id="${data.id}">
            <td>${formatNombre(data.base)}</td>
            <td>${formatNombre(data.poids_air,2)}</td>
            <td>${formatNombre(data.poids_eau,2)}</td>
            <td>${formatNombre(data.densite,2)}</td>
            <td>${formatNombre(data.carat,2)}</td>
            <td>${formatNombre(data.montant)}</td>
        </tr>
    `);

} else {
    console.log("❌ ID manquant :", data);
}
            // 🔴 VIDER LES INPUTS
    
    document.getElementById("poids_air").value = "";
    document.getElementById("poids_eau").value = "";
    document.getElementById("densite").value="";
    document.getElementById("carat").value = "";
    document.getElementById("achat_gold_montant").value = "";

    // optionnel : remettre focus
    document.getElementById("poids_air").focus();

      // 🔥 ICI TU APPELLES LE CALCUL
   // calculerTotaux();
      getTotalPoids();
     // calculerDensiteGlobal();
    //Nombre de ligne dans le tableau
    document.getElementById('numero_barre').value=compterLignes();

    

        });

    });

});

//Modifier ou supprimer une ligne du tableau tempTransaction
document.addEventListener("click", function (e) {

    let row = e.target.closest("#tableauTransaction tr");

    if (!row) return;

    let id = row.getAttribute("data-id");

    let cells = row.querySelectorAll("td");

    // 🔵 remplir inputs
    document.getElementById("base").value = cells[0].innerText;
    document.getElementById("poids_air").value = Number(cells[1].innerText.replace(",", ".")).toFixed(2);
    document.getElementById("densite").value = Number(cells[3].innerText.replace(",", ".")).toFixed(2);
    document.getElementById("carat").value = Number(cells[4].innerText.replace(",", ".")).toFixed(2);
    document.getElementById("poids_eau").value = Number(cells[2].innerText.replace(",", ".")).toFixed(2);
    document.getElementById("achat_gold_montant").value = cells[5].innerText;

    document.getElementById("base").focus();

    // 🔴 SUPPRESSION EN BASE
    fetch('../functions/delete_barre.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {

            // 🔴 supprimer ligne tableau
            row.remove();
            //Modifier le total dans la table
           // calculerTotaux();
            document.getElementById('numero_barre').value=compterLignes();
            console.log("✔ supprimé en base + interface");

        } else {
            alert("Erreur suppression en base");
        }

    });

});

function toNumber(text) {

    if (!text) return 0;

    return parseFloat(
        text
            .replace(/\s/g, '')   // enlève espaces
            .replace(',', '.')    // virgule → point
    ) || 0;
}

// CALCULER POIDS TOTAL

async function getTotalPoids() {

    try {

        const response = await fetch("../functions/get_total_poids.php");
        const data = await response.json();

        let DensiteGlobal = document.getElementById("densite_montant");
        let AirTotal = document.getElementById("poids_air_montant");
        let EauTotal = document.getElementById("poids_eau_montant");
        let CaratGlobal = document.getElementById("carat_montant");
        let moyenneBase = document.getElementById("base_montant");
        let MontantTotal = document.getElementById("somme_montant");
        console.log(data);

        if (data.success) {

            document.getElementById("base_montant").value =
                Number(data.moyenneBase).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            
            document.getElementById("somme_montant").value =
                Number(data.totalMontant).toLocaleString('fr-FR', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });

            AirTotal.value = Number(data.totalPoidsAir).toFixed(2);
            EauTotal.value = Number(data.totalPoidsEau).toFixed(2);
          //  MontantTotal =   Number(data.TotalMontant).toFixed(2);

            if (Number(EauTotal.value) > 0) {

                let densiteReelleTotal =
                    Number(AirTotal.value) / Number(EauTotal.value);


                // Troncature sans arrondi
                let densite =
                    Math.trunc(densiteReelleTotal * 100) / 100;


                DensiteGlobal.value = densite.toFixed(2);


                // Calcul carat
                const responseCarat = await fetch(
                    '../functions/get_carat_global.php',
                    {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                       body: 'txt_densite_montant=' + encodeURIComponent(densite)
                    }
                );


                const carat = await responseCarat.text();

                CaratGlobal.value = carat;

                
            }

        } else {

            alert(data.message);

        }

    } catch(error) {

        console.error("Erreur :", error);

    }
}
//Function nombre de ligne dans la table
function compterLignes() {

    let rows = document.querySelectorAll("#tableauTransaction tr");

    let nombre = rows.length;

    console.log("Nombre de lignes :", nombre);

    return nombre;
}

//Calcule 
document.addEventListener("DOMContentLoaded", function () {

    const poidsAir = document.getElementById("poids_air");
    const poidsEau = document.getElementById("poids_eau");

   

    const densite = document.getElementById("densite");
    const carat = document.getElementById("carat");

    

    const baseInput = document.getElementById("base");

    const prixInput = document.getElementById("txt_prix_unitaire");
    const montantInput = document.getElementById("achat_gold_montant");

    /*-------------Ca-------------------- */

    // 🔵 Prix unitaire
    function calculerPrixUnitaire() {

       let base = nombre(document.getElementById("base"));
    let c = nombre(carat);

    let prix = (base / 24) * c;

    // Affichage dans le champ
    prixInput.value = Math.floor(prix).toLocaleString('fr-FR');

    return prix;
    }

    // 🔴 Montant
    function calculerMontant() {

        let prix = calculerPrixUnitaire();
        let poids = parseFloat(poidsAir.value.replace(/\s/g, "").replace(",", ".")) || 0;

        let montant = Math.floor((prix * poids) + 0.5);

        montantInput.value = montant.toLocaleString('fr-FR');
    }
///--------------CALCULE DENSITE----------------////
    function calculerDensite() {
        

        let air = parseFloat(
        poidsAir.value.replace(/\s/g, "").replace(",", ".")
        ) || 0;

        let eau = parseFloat(
        poidsEau.value.replace(/\s/g, "").replace(",", ".")
        ) || 0;

        if (eau > 0) {
   
        let densiteReelle = air / eau;

// valeur exacte pour AJAX
densite.dataset.valeur = densiteReelle;

// affichage seulement
densite.value = (Math.trunc(densiteReelle * 100) / 100).toFixed(2);

            // 🔥 AJAX carat
            fetch('../functions/get_carat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'densite=' + encodeURIComponent(densite.value)
            })
            .then(res => res.text())
            .then(data => {

                carat.value = data;

                // 🔥 prix + montant immédiat
                prixInput.value = calculerPrixUnitaire().toFixed(2);
                calculerMontant();

            });

        } else {

            densite.value = "";
            carat.value = "";
            prixInput.value = "";
            montantInput.value = "";
        }
    }

    poidsAir.addEventListener("input", calculerDensite);
    poidsEau.addEventListener("input", calculerDensite);
    carat.addEventListener("input", function () {
    calculerPrixUnitaire();
    calculerMontant();
});
    carat.addEventListener("input", calculerMontant);

});

//----------PARCOURIR LES CHAMPS INPUTS AVEC BOUTON ENTRER
document.addEventListener('DOMContentLoaded', function () {

    const champs = [
        document.getElementById('base'),
        document.getElementById('poids_air'),
        document.getElementById('poids_eau'),
        document.getElementById('carat')
    ];

    const btnValider = document.getElementById('btn_plus');

    champs.forEach((champ, index) => {

        champ.addEventListener('keydown', function (e) {

            if (e.key === 'Enter') {

                e.preventDefault();

                if (index < champs.length - 1) {
                    champs[index + 1].focus();
                } else {
                    btnValider.click();
                }

            }

        });

    });

});


//--------------CALCULE DENSITE GLOBALE--------///
const poidsAirTotal = document.getElementById("poids_air_montant");
const poidsEauTotal = document.getElementById("poids_eau_montant");
const DenstiteGlobal = document.getElementById("densite_montant");
const CaratGlobal = document.getElementById("carat_montant");


function calculerDensiteGlobal() {

        let airTotal = parseFloat(poidsAirTotal.value) || 0;
        let eauTotal = parseFloat(poidsEauTotal.value) || 0;

        if (eauTotal > 0) {

           let densiteReelleTotal = airTotal / eauTotal;

// valeur exacte pour AJAX
DenstiteGlobal.dataset.valeur = densiteReelleTotal;

// affichage seulement
DenstiteGlobal.value = (Math.trunc(densiteReelleTotal * 100) / 100).toFixed(2);

            // 🔥 AJAX carat
            fetch('../functions/get_carat_global.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'densite_montant=' + encodeURIComponent(DenstiteGlobal.value)
            })
            .then(res => res.text())
            .then(data => {

                CaratGlobal.value = data;

                // 🔥 prix + montant immédiat
              //  prixInput.value = calculerPrixUnitaire().toFixed(2);
              //  calculerMontant();

            });

        } else {

          //  densite.value = "";
            //carat.value = "";
            //prixInput.value = "";
            //montantInput.value = "";
        }
    }

   // poidsAirTotal.addEventListener("input", calculerDensiteGlobal);
   // poidsEauTotal.addEventListener("input", calculerDensiteGlobal);
    ///CaratGlobal.addEventListener("input", calculerMontant);


////SAISIE DE SEPARATEUR DE MILLIER, LE NOMBRE DECIMAL ET LE SIGNE MOINS



function nombre(input) {
   
    let valeur = (input.value || '')
        .replace(/\s/g, '')
        .replace(',', '.')
        .replace(/[^0-9.-]/g, '');

    return parseFloat(valeur) || 0;
}

///SEPARATEUR DANS INPUT BASE
document.addEventListener('DOMContentLoaded', function () {

    const base = document.getElementById('base');
    const poidsAir = document.getElementById('poids_air');
    const poidsEau = document.getElementById('poids_eau');

    formatNombreInput(base);
    formatNombreInput(poidsAir);
    formatNombreInput(poidsEau);

});

//Fonction FORMATAGE NOMBRE
function formatNombreInput(input) {

    input.addEventListener('input', function () {

        let valeur = this.value;

        let negatif = valeur.startsWith('-');

        valeur = valeur.replace(/[^0-9.,]/g, '');
        valeur = valeur.replace(',', '.');

        let parts = valeur.split('.');
        if (parts.length > 2) {
            valeur = parts[0] + '.' + parts.slice(1).join('');
        }

        let [entier, decimal] = valeur.split('.');

        entier = (entier || '').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');

        let resultat = decimal !== undefined
            ? entier + '.' + decimal
            : entier;

        input.value = negatif ? '-' + resultat : resultat;
    });
}

//---------ESPACE BROUILLON------------//

function ouvrirModalBrouillon(){

    document.getElementById("modalBrouillon").style.display = "block";

}

function fermerModalBrouillon(){

    document.getElementById("modalBrouillon").style.display = "none";
}


/**-------TRANSFERT DANS TABLE BROUILLON ACHAT + REGIMENT----------- */
document.addEventListener("DOMContentLoaded", function () {

    let btn = document.getElementById("btn_valide_achat");

    if (!btn) {
        return;
    }
      btn.addEventListener("click", function (e) {

    e.preventDefault();

    /** -------------------------
     * 1. transaction_or
     * ------------------------- */
    let xhr1 = new XMLHttpRequest();

    xhr1.open("POST", "../functions/transfert_table_transaction_totale_or.php", true);

    xhr1.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    xhr1.onload = function () {

        console.log("STEP 1:", this.responseText);

        let rep1 = JSON.parse(this.responseText);

        if (rep1.success) {

            /** -------------------------
             * 2. brouillon
             * ------------------------- */
            let xhr2 = new XMLHttpRequest();

            xhr2.open("POST", "../functions/transfert_brouillon_ahat.php", true);

            xhr2.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );

            xhr2.onload = function () {

                console.log("STEP 2:", this.responseText);

                let rep2 = JSON.parse(this.responseText);

                if (rep2.success) {

                    alert("Transfert effectué");

                    /** ------------------------
                     *  REGIMENT
                     * ------------------------ */
                    let rgmt = document.getElementById("rgmt").value;

                    let xhr3 = new XMLHttpRequest();

                    xhr3.open("POST", "../functions/save_regiment.php", true);

                    xhr3.setRequestHeader(
                        "Content-Type",
                        "application/x-www-form-urlencoded"
                    );

                    xhr3.send("txt_rgmt=" + encodeURIComponent(rgmt));

                      // Ouvrir la facture
                    window.open(
                        "../pdf/facture_a_gold.php?rgmt=" + encodeURIComponent(rgmt),
                        "_blank"
                    );

                    /** ------------------------
                     * RESET UI
                     * ------------------------ */

                    document.getElementById("tableauTransaction").innerHTML = "";

                    document.getElementById("txtIdClient").value = "";
                    document.getElementById("txt_prix_unitaire").value = "";
                    document.getElementById("nom_client").value = "";
                    document.getElementById("rep_client").value = "";

                    let rgmtInput = document.getElementById("rgmt");
                    let valeur = parseInt(rgmtInput.value || 0) + 1;
                    rgmtInput.value = String(valeur).padStart(4, "0");

                    document.getElementById("numero_compte").value = "";
                    document.getElementById("type_operation").value = "Choisir";
                    document.getElementById("numero_barre").value = "0";
                    document.getElementById("solde_client").value = "0";

                    document.getElementById("base").value = "";
                    document.getElementById("poids_air").value = "";
                    document.getElementById("poids_eau").value = "";
                    document.getElementById("densite").value = "";
                    document.getElementById("carat").value = "";
                    document.getElementById("achat_gold_montant").value = "";

                    document.getElementById("base_montant").value = "";
                    document.getElementById("poids_air_montant").value = "";
                    document.getElementById("poids_eau_montant").value = "";
                    document.getElementById("densite_montant").value = "";
                    document.getElementById("carat_montant").value = "";
                    document.getElementById("somme_montant").value = "";
                    
                    rechargerBrouillon();
                    ouvrirModalBrouillon();

                } else {
                    alert(rep2.message);
                }
            };

            xhr2.send();

        } else {
            alert(rep1.message);
        }
    };

    xhr1.send();

});

});


/**-------TRANSFERT DANS TABLE BROUILLON VENTE + REGIMENT----------- */
document.addEventListener("DOMContentLoaded", function () {

    let btn = document.getElementById("btn_valide_vente");

    if (!btn) {
        return;
    }
      btn.addEventListener("click", function (e) {

    e.preventDefault();

    /** -------------------------
     * 1. transaction_or
     * ------------------------- */
    let xhr1 = new XMLHttpRequest();

    xhr1.open("POST", "../functions/transfert_table_transaction_totale_or.php", true);

    xhr1.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );

    xhr1.onload = function () {

        console.log("STEP 1:", this.responseText);

        let rep1 = JSON.parse(this.responseText);

        if (rep1.success) {

            /** -------------------------
             * 2. brouillon
             * ------------------------- */
            let xhr2 = new XMLHttpRequest();

            xhr2.open("POST", "../functions/transfert_brouillon_vente.php", true);

            xhr2.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );

            xhr2.onload = function () {

                console.log("STEP 2:", this.responseText);

                let rep2 = JSON.parse(this.responseText);

                if (rep2.success) {

                    alert("Transfert effectué");

                    /** ------------------------
                     *  REGIMENT
                     * ------------------------ */
                    let rgmt = document.getElementById("rgmt").value;

                    let xhr3 = new XMLHttpRequest();

                    xhr3.open("POST", "../functions/save_regiment.php", true);

                    xhr3.setRequestHeader(
                        "Content-Type",
                        "application/x-www-form-urlencoded"
                    );

                    xhr3.send("txt_rgmt=" + encodeURIComponent(rgmt));

                      // Ouvrir la facture
                    window.open(
                        "../pdf/facture_v_gold.php?rgmt=" + encodeURIComponent(rgmt),
                        "_blank"
                    );

                    /** ------------------------
                     * RESET UI
                     * ------------------------ */

                    document.getElementById("tableauTransaction").innerHTML = "";

                    document.getElementById("txtIdClient").value = "";
                    document.getElementById("txt_prix_unitaire").value = "";
                    document.getElementById("nom_client").value = "";
                    document.getElementById("rep_client").value = "";

                    let rgmtInput = document.getElementById("rgmt");
                    let valeur = parseInt(rgmtInput.value || 0) + 1;
                    rgmtInput.value = String(valeur).padStart(4, "0");

                    document.getElementById("numero_compte").value = "";
                    document.getElementById("type_operation").value = "Choisir";
                    document.getElementById("numero_barre").value = "0";
                    document.getElementById("solde_client").value = "0";

                    document.getElementById("base").value = "";
                    document.getElementById("poids_air").value = "";
                    document.getElementById("poids_eau").value = "";
                    document.getElementById("densite").value = "";
                    document.getElementById("carat").value = "";
                    document.getElementById("achat_gold_montant").value = "";

                    document.getElementById("base_montant").value = "";
                    document.getElementById("poids_air_montant").value = "";
                    document.getElementById("poids_eau_montant").value = "";
                    document.getElementById("densite_montant").value = "";
                    document.getElementById("carat_montant").value = "";
                    document.getElementById("somme_montant").value = "";
                    
                    rechargerBrouillonVente();
                    ouvrirModalBrouillon();

                } else {
                    alert(rep2.message);
                }
            };

            xhr2.send();

        } else {
            alert(rep1.message);
        }
    };

    xhr1.send();

});

});


// VISUALISATION DE LA FACTURE AVANT VALIDER 
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btn_affiche_achat");

    if (!btn) {
        console.log("Bouton btn_affiche_achat introuvable.");
        return;
    }

    btn.addEventListener("click", function () {
        let rgmt = document.getElementById("rgmt").value.trim();
        let rep_client = document.getElementById("rep_client").value.trim();
        let nom_client = document.getElementById("nom_client").value.trim();
        let numero_compte = document.getElementById("numero_compte").value.trim();
        let type_operation = document.getElementById("type_operation").value.trim();

        window.open("../pdf/facture_gold_view.php?" +
            "rgmt=" + encodeURIComponent(rgmt) +
            "&rep_client=" + encodeURIComponent(rep_client) +
            "&nom_client=" + encodeURIComponent(nom_client) +
            "&numero_compte=" + encodeURIComponent(numero_compte) +
            "&type_operation=" + encodeURIComponent(type_operation),
            "_blank"
        );

    });

});

function rechargerBrouillon() {

    let xhr = new XMLHttpRequest();

    xhr.open("GET", "../functions/charger_brouillon.php", true);

    xhr.onload = function () {

        if (this.status == 200) {

            document.getElementById("tbodyBrouillon").innerHTML =
                this.responseText;
        }
    };

    xhr.send();
}

function rechargerBrouillonVente() {

    let xhr = new XMLHttpRequest();

    xhr.open("GET", "../functions/charger_brouillon_vente.php", true);

    xhr.onload = function () {

        if (this.status == 200) {

            document.getElementById("tbodyBrouillon").innerHTML =
                this.responseText;
        }
    };

    xhr.send();
}

/*----------FORMULAIRE VALIDATION FINALE----------- */
function fermerModalValidation(){

    document.getElementById("modalValidation").style.display = "none";
} 

function OuvrirModalValidation(){

    document.getElementById("modalValidation").style.display = "block";
} 

// SELECTION OPERATION BROUILLON POUR VALIDATION

document.addEventListener("click", function (e) {

    let row = e.target.closest("#tbodyBrouillon tr");

    if (!row) return;

    let id = row.getAttribute("data-id");

    let cells = row.querySelectorAll("td");

    // 🔵 remplir inputs
    document.getElementById("txt_id_op").value = cells[0].innerText;
    document.getElementById("txt_Date").value = cells[1].innerText;
    document.getElementById("txt_numero").value = cells[2].innerText;
    document.getElementById("txt_Client").value = cells[3].innerText;
    document.getElementById("txt_Rep_client").value = cells[4].innerText;
    
    let quantite = parseFloat(
    cells[5].innerText
        .replace(/\s/g, '')
        .replace(',', '.')
) || 0;

    document.getElementById("txt_Quantite").value = quantite;
    document.getElementById("txt_Base").value = cells[6].innerText;
    document.getElementById("txt_Montant").value =cells[7].innerText;
    document.getElementById("txt_Mouvement").value = cells[8].innerText;
    document.getElementById("txt_Rgmt").value = cells[9].innerText;
    document.getElementById("txt_User").value = cells[10].innerText;
   
    let Rgmt = cells[9].innerText;
    document.getElementById("txt_Rgmt").value = Rgmt;

    let numero = cells[2].innerText.trim();

    document.getElementById("txt_numero").value = numero;

    OuvrirModalValidation();
    recupererSoldeClient(numero);
    recupererSoldeCaisse();
    recuperer_id_client(numero)
    
});


/**--------MODIFICATION OPERATION BROUILLON----------- */
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btn_modif_achat");

    btn.addEventListener("click", function () {

        let Rgmt = document.getElementById("txt_Rgmt").value.trim();

        if (Rgmt === "") {
            alert("Regiment introuvable.");
            return;
        }

        fetch("../functions/modification_brouillon.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Rgmt=" + encodeURIComponent(Rgmt)
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {
            document.getElementById("numero_compte").value = data.numero_compte;
            document.getElementById("nom_client").value = data.client;
            document.getElementById("rep_client").value = data.rep_client;
            document.getElementById("rgmt").value = data.rgmt;
            document.getElementById("type_operation").value = data.type_operation;
            document.getElementById("solde_client").value = data.solde_client;

                fermerModalValidation();
                fermerModalBrouillon();
               
                rechargerBrouillon();
                chargerTableTemp();
                active_input();
               

            } else {

                alert(data.message);

            }

        })
        .catch(error => {

            console.error(error);
            alert("Erreur de communication avec le serveur.");

        });

    });

});


/**--------MODIFICATION OPERATION BROUILLON VENTE----------- */
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btn_modif_vente");

    btn.addEventListener("click", function () {

        let Rgmt = document.getElementById("txt_Rgmt").value.trim();

        if (Rgmt === "") {
            alert("Regiment introuvable.");
            return;
        }

        fetch("../functions/modification_brouillon_vente.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Rgmt=" + encodeURIComponent(Rgmt)
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {
            document.getElementById("numero_compte").value = data.numero_compte;
            document.getElementById("nom_client").value = data.client;
            document.getElementById("rep_client").value = data.rep_client;
            document.getElementById("rgmt").value = data.rgmt;
            document.getElementById("type_operation").value = data.type_operation;
            document.getElementById("solde_client").value = data.solde_client;

                fermerModalValidation();
                fermerModalBrouillon();
               
                rechargerBrouillonVente();
                chargerTableTemp();
                active_input();
               

            } else {

                alert(data.message);

            }

        })
        .catch(error => {

            console.error(error);
            alert("Erreur de communication avec le serveur.");

        });

    });

});



/**--------SUPPRESSION OPERATION BROUILLON----------- */
document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("btn_supprimer_achat");

    btn.addEventListener("click", function () {

        let Rgmt = document.getElementById("txt_Rgmt").value.trim();

        if (Rgmt === "") {
            alert("Regiment introuvable.");
            return;
        }

        fetch("../functions/delete_brouillon.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "Rgmt=" + encodeURIComponent(Rgmt)
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                fermerModalValidation(); 
                rechargerBrouillon();               

            } else {

                alert(data.message);

            }

        })
        .catch(error => {

            console.error(error);
            alert("Erreur de communication avec le serveur.");

        });

    });

});

function chargerTableTemp() {

    fetch("../functions/afficher_table_temp.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("tableauTransaction").innerHTML = html;
              //Nombre de ligne dans le tableau
        document.getElementById('numero_barre').value=compterLignes();
      
        })
        .catch(error => console.error(error));
}

/**----RECUPERER LE SOLDE CLIENT */
function recupererSoldeClient($compteClient) {

    fetch("../functions/recuperer_solde_client.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "txt_numero=" + encodeURIComponent($compteClient)
    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            document.getElementById("txt_solde_client").value = data.solde;

        } else {

            alert(data.message);

        }

    })
    .catch(error => console.log(error));
}

/** Récupérer le solde de la caisse */
function recupererSoldeCaisse() {

    fetch("../functions/solde_caisse.php")
        .then(response => response.json())
        .then(data => {

            if (data.success) {

                document.getElementById("txt_solde_caisse").value = data.solde;

            } else {

                alert(data.message);

            }

        })
        .catch(error => console.error(error));
}

/**----RECUPERER LE ID CLIENT */
function recuperer_id_client($compteClient) {

    fetch("../functions/recuperer_id_client.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "txt_numero=" + encodeURIComponent($compteClient)
    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {

            document.getElementById("txt_id_client").value = data.id;

        } else {

            alert(data.message);

        }

    })
    .catch(error => console.log(error));
}

//----Modification type_operation dans la base temptransactionor---///

document.addEventListener("DOMContentLoaded", function () {

    // Récupérer le select
    const typeOperation = document.getElementById("type_operation");

    // Vérifier qu'il existe
    if (!typeOperation) {
        console.error("❌ Le champ #type_operation est introuvable.");
        return;
    }

    // Détecter le changement
    typeOperation.addEventListener("change", function () {

        let mouvement = this.value;

        console.log("Nouveau mouvement :", mouvement);

        fetch("../functions/modifier_type_operation.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "txt_type_operation=" + encodeURIComponent(mouvement)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP : " + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Réponse PHP :", data);

            if (!data.success) {
                console.error(data.message);
            }
        })
        .catch(error => {
            console.error("Erreur AJAX :", error);
        });

    });

});

//----Modification Representant client dans la base temptransactionor---///

document.addEventListener("DOMContentLoaded", function () {

    // Récupérer le select
    const typeOperation = document.getElementById("rep_client");

    // Vérifier qu'il existe
    if (!typeOperation) {
        console.error("❌ Le champ #Rep Client est introuvable.");
        return;
    }

    // Détecter le changement
    typeOperation.addEventListener("change", function () {

        let RepClient = this.value;

        console.log("Nouveau Rep Client :", RepClient);

        fetch("../functions/modifier_rep_client.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "txt_rep_client=" + encodeURIComponent(RepClient)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Erreur HTTP : " + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log("Réponse PHP :", data);

            if (!data.success) {
                console.error(data.message);
            }
        })
        .catch(error => {
            console.error("Erreur AJAX :", error);
        });

    });

});
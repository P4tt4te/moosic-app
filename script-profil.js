"use strict";


function debutProfil(evt){
  var script = document.querySelector('script.dernier');
  valeurProfil();
}
// met à jour le logo/nom/niveau/barre d'exp du profil //
function valeurProfil(){
  // pseudo //
  let zonepseudo = document.querySelector('h1.profil-name');
  zonepseudo.textContent = pseudo;
  // barre d'experience //
  let pourcentage = (exp / (niveau * 100))* 100 ;
  if (pourcentage < 0){
    pourcentage = 0;
  }

  let barre = document.querySelector('.pourcentage-progression');
  barre.style.width = pourcentage+'%';
  // niveau //
  let niveausup = parseInt(niveau) + 1;
  let zoneniveau = document.querySelector('.nbr-niveau');
  zoneniveau.textContent = niveau;
  let zoneniveausup = document.querySelector('.nbr-niveau-sup');
  zoneniveausup.textContent = niveausup;
  // logo banniere //
  let zonelogopseudo = document.querySelector('.logo-utilisateur');
  zonelogopseudo.setAttribute('xlink:href', sourceProfil);

  let zonelogobanniere = document.querySelector('.profil-utilisateur');
  zonelogobanniere.style.backgroundImage = 'url('+sourceBanniere+')';
}
// change la banniere profil graphiquement au clic //
function changerBanniereProfil() {
  let zonelogobanniere = document.querySelector('.profil-utilisateur');
  zonelogobanniere.style.backgroundImage = 'url('+sourceBanniere+')';
  changerinventaire();
}
// change le logo profil graphiquement au clic //
function changerLogoProfil(couleur) {

  let zonelogopseudo = document.querySelector('.logo-utilisateur');
  zonelogopseudo.setAttribute('xlink:href', sourceProfil);
  changerinventaire();
}


// gere la requete pour le changement d'inventaire //
function changerinventaire() {
  let param = new FormData();
  param.append("type", 10);
  param.append("srclogo",retournerLogoProfil());
  param.append("srcbanniere",retournerLogoBanniere());


  fetch('info.php', {
    method: 'POST',
    body: param,
  })
}

"use strict";
var validationProfil = 'false';

function debutaccueil(evt){
  appareilaccueil();
  infosAccueil();
  if (pseudo == 'GUEST') {
    accueildefault();
  }
}

function infosAccueil(evt){
  var pointsAccueil = document.querySelector(".points-chiffres");
  var pseudoAccueil = document.querySelector(".pseudo");
  var niveauAccueil = document.querySelector(".niveau-chiffres");

  pointsAccueil.textContent = moodPoints;
  pseudoAccueil.textContent = pseudo;
  niveauAccueil.textContent = niveau;

}

// detecte si l'appareil de l'utilisateur est un pc ou mobile //
function appareilaccueil() {
  if (window.matchMedia("(min-width: 1025px)").matches) {
    scrollreveal();
  }
}

// verouille les éléments quand on veut découvrir le site //
function accueildefault(){
  // bloque le mood //
  var presource = 'https://la-projets.univ-lemans.fr/~mmi1pj03/images/';
  var divshop = document.querySelector('.shop');
  divshop.addEventListener('click',bloquecliqueshop,true);

  function bloquecliqueshop(evt) {
    evt.stopPropagation();
  }
  var listefavoris = [favoris1,favoris2,favoris3];
  var listeimg = document.querySelectorAll('.best-radio .image-best-webradio');
  for (let i =0;i<listeimg.length;i++){
    listeimg[i].src = presource.concat(listefavoris[i]);
  }

}


//met en place le reveal des éléments de l'accueil au scroll//
function scrollreveal(){
  var blocs = document.querySelectorAll('.grid-container > div');
  for (let i = 0;i < blocs.length; i++){
    let alea =Math.floor(Math.random() * (300 - 100 +1)) +100;
    ScrollReveal().reveal(blocs[i], {
      delay: alea,
      duration: 400,
      scale: 0.3,
      reset: false
    });
  }

  ScrollReveal().reveal('.grid-container > section', {
    delay: 500,
    duration: 400,
    scale: 0.3,
    reset: false
  });

  ScrollReveal().reveal('.pub', {
    delay: 200,
    duration: 300,
    scale: 0.3,
    reset: false
  });

}





// verifie si l'item à déjà été acheter dans le Moodshop//

function verifachatfond(objet){
  let achete = objet.firstElementChild.dataset.achete;
  if (achete == 'true'){
    objet.firstElementChild.src = "https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2FGroup_109.svg?v=1623756357037";
    objet.lastElementChild.classList.add("bloc-selectione");
    objet.removeEventListener("click", verifprixDivFond);
  }
}

function verifachaticone(objet){
  let achete = objet.firstElementChild.dataset.achete;
  if (achete == 'true'){
    objet.lastElementChild.classList.add("bloc-vert");
    objet.lastElementChild.firstElementChild.textContent = "Acheté";
    objet.removeEventListener("click", verifprixDivIcone);
  }
}




//requete pour la page accueil (mood shop) pour verfier l'achat de cette item//

function envoiMoodShop(objet,img) {
  validationProfil = 'false';
  let param = new FormData();
  param.append("type", 11);
  param.append("idObjet", img.dataset.id);


  fetch('achete.php', {
    method: 'POST',
    body: param,
  }).then(function(response) {
    return response.json();
  }).then(function(object) {
      validationProfil = object.resultat;
    return (object.resultat);
  }).catch(function(error) {
    console.error('bugEnvoi');
    console.error(error);
  })


}

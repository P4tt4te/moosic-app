"use strict";
window.addEventListener('load', loading);
document.addEventListener("DOMContentLoaded", debut);
var testplayer;
// variables du profil utilisateur //
var pseudo = "LaZen53";
var mood = "Good Mood";
var logoProfil = "2.svg";
var sourceProfil = "https://la-projets.univ-lemans.fr/~mmi1pj03/imagesObjets/2.svg";
var logoBanniere = "6.webp";
var sourceBanniere = "https://la-projets.univ-lemans.fr/~mmi1pj03/imagesObjets/6.webp";
var moodPoints = "1000";
var niveau = "1";
var exp = "0";
// variables par default quand l'utilisateur n'est pas connecter //
var favoris1;
var favoris2;
var favoris3;
// variables overlay edition profil //
var menuOption;
var overlay;
var editionHeader;
var boutonEdition;
var page;
// statut player //
var soundon = false;

function loading(evt) {
  let loader = document.querySelector('.loader');
  loader.style.display = 'none';
}



function debut(evt) {
  // affectation variables overlay edition profil //
  menuOption = document.querySelector(".menu-options");
  overlay = document.querySelector(".overlay-invisible");

  // detecte si l'utilisateur est sur mobile ou pc //
  appareil();
  testplayer = new player();
  // recupere les infos en local du player (evite une requete) //
  recupInfo();
  // recupere les infos du profil utilisateur //
  recupProfil();
  testplayer.off();
  playerdrag();
  window.addEventListener('beforeunload', infoplayer, {
    capture: true
  });
  // event overlay edition profil //

  page = document.location.pathname;
  if (page == "/~mmi1pj03/profil.php") {
    boutonEdition = document.querySelector(".edition-profil");
    boutonEdition.addEventListener("click", ouvrirOverlay);
  }
  if (page == "/~mmi1pj03/accueil.php"){
    var zonealeatoire = document.querySelector('.des-aleatoire');
    zonealeatoire.addEventListener('click',requetealea);
  }
  var boutonprofilradio;
  if (page == "/~mmi1pj03/profil-radio.php") {
    boutonprofilradio = document.querySelector('.bouton-ecoute-radio');
    boutonprofilradio.addEventListener('click', radioprofil);
  }
}

//indique à l'utilisateur d'ouvrir le player//




// detecte si l'appareil de l'utilisateur est un pc ou mobile //
function appareil() {
  if (window.matchMedia("(max-width: 1024px)").matches) {
    menutel();
    clickDroit();
    playertel();
    option(0);
  } else {
    playerpc();
    logoplayer();
    option(1);
  }
}

//desactive le click droit//

function clickDroit() {
  document.addEventListener('contextmenu', non);

  function non(evt) {
    evt.preventDefault();
  }
}

// crée le menu pour mobile //
function menutel() {
  let profil = document.querySelector(".profil");
  profil.style.display = "none";
  let menu = document.querySelector("nav");
  menu.classList.add("mobile-off");
  //ajoute la section profil dans le menu//
  let section = document.createElement('li');
  let lien = document.createElement('a');
  lien.href = 'https://la-projets.univ-lemans.fr/~mmi1pj03/profil.php';
  lien.textContent = 'Profil';
  section.append(lien);
  let liste = document.querySelector('nav ul');
  liste.append(section);
  let bouton = document.querySelector('#menu-burger');
  let menuSvg = document.querySelector('#menu1');
  let visible = false;

  let option = document.createElement('img');
  option.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foption.svg?v=1623224459362';
  option.classList.add('engrenage');
  menu.append(option);

  bouton.addEventListener('click', menuanim);

  function menuanim(evt) {
    menuSvg.classList.toggle('actif');
    menu.classList.toggle('mobile-off');
    if (visible == false) {
      window.addEventListener('scroll', pasScroll);
      visible = true;
    } else {
      window.removeEventListener('scroll', pasScroll);
      visible = false;
    }
  }
}

//permet d'enlever le scroll quand le menu mobile est actif//

function pasScroll(evt) {
  window.scrollTo(0, 0);
}


// affichage de l'overlay option //

function option(type) {
  let visible = false;

  if (type == 1) {
    var engrenage = document.querySelector('.profil-options');
  } else {
    var engrenage = document.querySelector('.engrenage');
  }

  editionHeader = document.querySelector(".edit-profil");
  var menu = document.querySelector('.menu-options');
  var nav = document.querySelector('nav');
  engrenage.addEventListener('click', sombre);

  function sombre(evt) {
    menu.classList.remove('none');
    menu.classList.add('visible');
    nav.classList.add('grayscale');
    window.setTimeout(antibug, 50);
    engrenage.removeEventListener('click', sombre);
    var inscriptionHeader = document.querySelector(".creer-compte");

    if (pseudo == 'GUEST') {
      editionHeader.addEventListener("click", redirectLanding);
      inscriptionHeader.addEventListener("click", redirectLanding);
    } else {
      editionHeader.addEventListener("click", redirectProfil);
      inscriptionHeader.addEventListener("click", redirectDeco);
    }
    editionHeader.addEventListener("click", testfin);

    function antibug() {
      window.addEventListener('click', testfin);
    }

    function testfin(evt) {
      if (menu.contains(evt.target)) {
        if (this == editionHeader) {
          finsombre();
          window.removeEventListener('click', testfin);
          editionHeader.removeEventListener("click", ouvrirOverlay);
          editionHeader.removeEventListener("click", testfin);
        }
      } else {
        finsombre();
        window.removeEventListener('click', testfin);
        editionHeader.removeEventListener("click", ouvrirOverlay);
        editionHeader.removeEventListener("click", testfin);
      }
    }

    function finsombre(evt) {
      menu.classList.remove('visible');
      menu.classList.add('none');
      nav.classList.remove('grayscale');
      engrenage.addEventListener('click', sombre);
    }
  }




}

function redirectProfil(evt) {
  window.location.replace("https://la-projets.univ-lemans.fr/~mmi1pj03/profil.php");
}

function redirectLanding(evt) {
  window.location.replace("https://la-projets.univ-lemans.fr/~mmi1pj03/index.php");
}

function redirectDeco(evt) {
  window.location.replace("https://la-projets.univ-lemans.fr/~mmi1pj03/deconnexion.php");
}



function ouvrirOverlay(evt) {
  window.setTimeout(antibug, 50);


  let valider = document.querySelector(".valider");
  valider.addEventListener("click", fermerOverlay);
  boutonEdition.removeEventListener("click", ouvrirOverlay);
  overlay.style.display = 'block';

  function antibug() {
    window.addEventListener("click", testclose);
  }

  function testclose(evt) {
    if (divOverlay.contains(evt.target)) {
      console.log("dedans");
    } else {
      fermerOverlay();
    }
  }

  function fermerOverlay() {
    overlay.style.display = 'none';
    window.removeEventListener("click", testclose);
    boutonEdition.addEventListener("click", ouvrirOverlay);
    valider.removeEventListener('click', fermerOverlay);
  }
}









// recup données utilisateur //

function recupProfil() {
  let param = new FormData();
  param.append("type", 9);
  param.append("idRadio", testplayer.retournerid());
  param.append("compteur", testplayer.retournerCompteur());

  fetch('info.php', {
    method: 'POST',
    body: param,
  }).then(function(response) {
    return response.json();
  }).then(function(object) {

    pseudo = object.pseudo;
    mood = object.humeur;
    if (object.photoProfil != null) {
      logoProfil = object.photoProfil;
    }
    if (object.banniere != null) {
      logoBanniere = object.banniere;
    }
    moodPoints = object.moodPoints;
    niveau = object.niveauUti;
    exp = object.experience;

    if (pseudo == 'GUEST') {
      favoris1 = object.favoris1;
      favoris2 = object.favoris2;
      favoris3 = object.favoris3;
    }

    afficheProfil();


    return (object.nomRadio);
  }).catch(function(error) {
    console.error('bugEnvoi');
    console.error(error);

  })
}

// initialise l'affichage de la photo de profil et du pseudo à l'initialisation de la page //
function afficheProfil() {
  let pseudopage = document.querySelector('span.profil-name');
  pseudopage.textContent = pseudo;
  let logoProfilpage = document.querySelector('.logo-profil');
  let presource = 'https://la-projets.univ-lemans.fr/~mmi1pj03/imagesObjets/';
  sourceProfil = presource.concat(logoProfil);
  logoProfilpage.setAttribute('xlink:href', sourceProfil);
  let presourcebanniere = 'https://la-projets.univ-lemans.fr/~mmi1pj03/imagesObjets/';
  sourceBanniere = presourcebanniere.concat(logoBanniere);
  let zoneMoodfill = document.querySelectorAll('.cercle-mood-fill');
  let zoneMoodstroke = document.querySelectorAll('.cercle-mood-stroke');
  var couleur;
  if (mood == "Good Mood") {
    for (let i = 0; i < zoneMoodfill.length; i++) {
      zoneMoodfill[i].setAttribute('fill', '#03f29e');
    }
    for (let i = 0; i < zoneMoodstroke.length; i++) {
      zoneMoodstroke[i].setAttribute('stroke', '#03f29e');
    }
    couleur = '#03f29e';

  } else if (mood == "Anxieux") {
    for (let i = 0; i < zoneMoodfill.length; i++) {
      zoneMoodfill[i].setAttribute('fill', '#faff00');
    }
    for (let i = 0; i < zoneMoodstroke.length; i++) {
      zoneMoodstroke[i].setAttribute('stroke', '#faff00');
    }
    couleur = '#faff00';
  } else if (mood == "Énervé") {
    for (let i = 0; i < zoneMoodfill.length; i++) {
      zoneMoodfill[i].setAttribute('fill', '#ff0000');
    }
    for (let i = 0; i < zoneMoodstroke.length; i++) {
      zoneMoodstroke[i].setAttribute('stroke', '#ff0000');
    }
    couleur = '#ff0000';
  }

  if (page == "/~mmi1pj03/profil.php") {
    let nom = document.querySelector('h1.profil-name');
    nom.style.color = couleur;
    debutProfil();
    debutswiper();
  } else if (page == "/~mmi1pj03/accueil.php"){
    debutaccueil();
    debutswiper();

  } else if (page == "/~mmi1pj03/profil-radio.php") {
    debutProfilRadio();
  } else if (page == "/~mmi1pj03/rechercher.php") {
    debutswiper();
  } else if (page == "/~mmi1pj03/bibliotheque.php") {
    debutswiper();
  }

  if (pseudo != 'GUEST'){
    let txtoption = document.querySelector('.container-liste-options>.creer-compte>p');
    txtoption.textContent = 'Déconnexion';
  }

}
// met à jour le logo du profil //
function changeLogo() {
  let logoProfilpage = document.querySelector('.logo-profil');
  logoProfilpage.setAttribute('xlink:href', sourceProfil);

  if (page == "/~mmi1pj03/profil.php") {
    changerLogoProfil();
  }
}
// met à jour la banniere du profil //
function changeBanniere() {
  if (page == "/~mmi1pj03/profil.php") {
    changerBanniereProfil();
  }
}

function retournerLogoBanniere() {
  let tab = sourceBanniere.split("/");
  let nbr = tab.length;
  logoBanniere = tab[nbr - 1];
  return logoBanniere;
}

function retournerLogoProfil() {
  let tab = sourceProfil.split("/");
  let nbr = tab.length;
  logoProfil = tab[nbr - 1];
  return logoProfil;
}

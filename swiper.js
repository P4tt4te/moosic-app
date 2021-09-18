"use strict";
var lesCases;
var lesIcones;
var swiperFondBib;
var swiperIcones;
var swiperLike;
var swiperRec;
var swiperBest;

function debutswiper(evt) {
  var nbr = swipenumb();
  var nbrFond = swipenumbFond();
  var nbrIcone = swipenumbIcone();
  var switchLoop = true;
  var lesCases;
  if (window.matchMedia("(min-width: 1024px)").matches) {
    switchLoop = false;
  }

  // initialisation des swipers

  swiperLike = new Swiper(".liste-webradios-likees", {
    slidesPerView: nbr,
    spaceBetween: 30,

    direction: "horizontal",
    loop: false,

    navigation: {
      nextEl: ".swiper-button-like-next",
      prevEl: ".swiper-button-like-prev"
    }
  });
  swiperRec = new Swiper(".liste-recemment-ecoute", {
    slidesPerView: nbr,
    spaceBetween: 30,

    direction: "horizontal",
    loop: false,

    navigation: {
      nextEl: ".swiper-button-rec-next",
      prevEl: ".swiper-button-rec-prev"
    }
  });

  if (window.matchMedia("(max-width: 1024px)").matches) {
    swiperBest = new Swiper(".best-radio", {
      slidesPerView: 1,
      spaceBetween: 30,

      direction: "horizontal",
      loop: false,

      navigation: {
        nextEl: ".swiper-button-best-next",
        prevEl: ".swiper-button-best-prev"
      }
    });
  }

  swiperFondBib = new Swiper(".imgs-fond", {
    slidesPerView: nbrFond,
    spaceBetween: 30,

    direction: "horizontal",
    loop: false,

    navigation: {
      nextEl: ".swiper-button-fondbib-next",
      prevEl: ".swiper-button-fondbib-prev"
    }
  });

  swiperIcones = new Swiper(".liste-icones", {
    slidesPerView: nbrIcone,
    spaceBetween: 30,
    // Optional parameters
    direction: "horizontal",
    loop: false,

    // Navigation arrows
    navigation: {
      nextEl: ".swiper-button-icones-next",
      prevEl: ".swiper-button-icones-prev"
    }
  });

  // mise en place des écouteurs de clic pour la sélection d'un contenu (profil et accueil)

  var divsFond = document.querySelectorAll(".div-fond");
  for (let divFond of divsFond) {
    divFond.addEventListener("click", cliquerDivFond);
    if (divFond.firstElementChild.src == sourceBanniere){
      divFond.click();
    }
  }

  var divsIcone = document.querySelectorAll(".div-icone");
  for (let divIcone of divsIcone) {
    divIcone.addEventListener("click", cliquerDivIcone);
    if (divIcone.firstElementChild.src == sourceProfil){
      divIcone.click();
    }
  }

  var divsFondAcc = document.querySelectorAll(".div-fond-acc");
  for (let divFondAcc of divsFondAcc) {
    divFondAcc.addEventListener("click", verifprixDivFond);
    verifachatfond(divFondAcc);
  }

  var divsIconeAcc = document.querySelectorAll(".div-icone-acc");
  for (let divIconeAcc of divsIconeAcc) {
    divIconeAcc.addEventListener("click", verifprixDivIcone);
    verifachaticone(divIconeAcc);
  }

  // test affichage des flèches au lancement, et mise en place des écouteurs de swipe tel, swipe ordi (plutôt hover), et clic pour l'affichage des flèches

  var containers = document.querySelectorAll(".swiper-container");
  for (let container of containers) {
    if (
      container.className == "swiper-container best-radio" &&
      window.matchMedia("(min-width: 1024px)").matches
    ) {
      container.classList.remove("swiper-container");
    } else {
      if (container.swiper.isBeginning) {
        container.previousElementSibling.classList.add("disparition");
      } else {
        container.previousElementSibling.classList.remove("disparition");
      }
      if (container.swiper.isEnd) {
        container.nextElementSibling.classList.add("disparition");
      } else {
        container.nextElementSibling.classList.remove("disparition");
      }
      container.parentElement.addEventListener("mousemove", defiler);
      container.parentElement.addEventListener("touchmove", defiler);
      container.parentElement.addEventListener("click", defiler);
    }
  }
}


// connaitre le nombre d'éléments à afficher sur les différents swipers en fonction des media queries

function swipenumb() {
  var spv;

  if (window.matchMedia("(min-width: 1600px)").matches) {
    spv = 4;
  } else if (window.matchMedia("(min-width: 1024px)").matches) {
    spv = 3;
  } else if (window.matchMedia("(min-width: 481px)").matches) {
    spv = 2;
  } else {
    spv = 1;
  }
  return spv;
}

function swipenumbFond() {
  var spv;

  if (window.matchMedia("(min-width: 769px)").matches) {
    spv = 2;
  } else if (window.matchMedia("(min-width: 481px)").matches) {
    spv = 1;
  } else {
    spv = 1;
  }
  return spv;
}

function swipenumbIcone() {
  var spv;

  if (window.matchMedia("(min-width: 1024px)").matches) {
    spv = 3;
  } else if (window.matchMedia("(min-width: 769px)").matches) {
    spv = 3;
  } else if (window.matchMedia("(min-width: 481px)").matches) {
    spv = 2;
  } else {
    spv = 1;
  }
  return spv;
}

// affichage du mode sélectionné (profil + accueil)
//fonctions pour profil//
function cliquerDivFond(evt) {
  var imagesFond = document.querySelectorAll(".img-fond");
  for (let imageFond of imagesFond) {
    imageFond.classList.remove("img-selectionnee");
  }
  this.firstElementChild.classList.add("img-selectionnee");
  sourceBanniere = this.firstElementChild.src;
  changeBanniere();

  var blocsFond = document.querySelectorAll(".bloc-achat-fond");
  for (let blocFond of blocsFond) {
    blocFond.classList.remove("bloc-selectione");
  }
  this.lastElementChild.classList.add("bloc-selectione");
}

function cliquerDivIcone(evt) {
  sourceProfil = this.firstElementChild.src;
  changeLogo();
  var blocsIcone = document.querySelectorAll(".bloc-achat-icone");
  for (let blocIcone of blocsIcone) {
    blocIcone.classList.remove("bloc-selectione");
  }
  this.lastElementChild.classList.add("bloc-selectione");
}

//gere si l'item cliqué peut-être acheter par un utilisateur ou non//

function verifprixDivFond(evt) {
  var src = this.firstElementChild;
  var last = this.lastElementChild;
  var obj = this;

  let zoneprix = this.querySelector('.cout-chiffres');
  let verif = false;
  var prix = zoneprix.textContent;
  var prixint = parseInt(prix, 10);


    envoiMoodShop(obj, src);
    window.setTimeout(verifprixfin, 300);

    function verifprixfin() {
      if (validationProfil == 'true') {
        moodPoints = moodPoints - prixint;
        var zonepoints = document.querySelector(".points-chiffres");
        zonepoints.textContent = moodPoints;
        verif = true;
      } else {
        verif = false;
      }
      if (verif == true) {
        src.src = "https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2FGroup_109.svg?v=1623756357037";
        last.classList.add("bloc-selectione");
        obj.removeEventListener("click", verifprixDivFond);
      }
    }
  }

  function verifprixDivIcone(evt) {
    var src = this.firstElementChild;
    var obj = this;

    let zoneprix = this.querySelector('.cout-chiffres');
    let verif = false;
    var prix = zoneprix.textContent;
    var prixint = parseInt(prix, 10);


      envoiMoodShop(obj, src);
      window.setTimeout(verifprixfin, 300);

      function verifprixfin() {
        if (validationProfil == 'true') {
          moodPoints = moodPoints - prixint;
          var zonepoints = document.querySelector(".points-chiffres");
          zonepoints.textContent = moodPoints;
          verif = true;
        } else {
          verif = false;
        }
        if (verif == true) {
          obj.lastElementChild.classList.add("bloc-vert");
          obj.lastElementChild.firstElementChild.textContent = "Acheté";
          obj.removeEventListener("click", verifprixDivIcone);
        }
      }
    }




// affichage des flèches

function defiler(evt) {
  var fleche1 = this.children[1].previousElementSibling;
  if (this.children[1].swiper.isBeginning) {
    fleche1.classList.add("disparition");
  } else {
    fleche1.classList.remove("disparition");
  }

  var fleche2 = this.children[1].nextElementSibling;
  if (this.children[1].swiper.isEnd) {
    fleche2.classList.add("disparition");
  } else {
    fleche2.classList.remove("disparition");
  }
}

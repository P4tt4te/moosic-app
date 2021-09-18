"use strict";

var commentprofilradio = document.querySelector(".overlayClosed");
var boutoncommprofilradio = document.querySelector(".div-addcomm");
var boutonshare = document.querySelector('.boutonshare');
var boutonlikeprofilradio = document.querySelector('.boutonlike');

function debutProfilRadio() {
  initialiserprofilradio();
  toggleLikerprofilradio();
}



function initialiserprofilradio(evt) {
  boutoncommprofilradio.addEventListener("click", openOverlayprofilradio);
  boutonshare.addEventListener('click', clipboardlien);
}


//copie le lien dans le presse papier //

function clipboardlien(evt) {
    var copyUrl = window.location.href;
  navigator.clipboard.writeText(copyUrl);

  this.style.color = 'var(--green)';
  let text = document.createElement('p');
  text.textContent = 'Le lien est copié dans le presse papier';
  text.classList.add('textclipboard');
  let main = document.querySelector('main');
  main.append(text);
}


function toggleLikerprofilradio() {
  if (boutonlikeprofilradio.dataset.like == "true") {
    boutonlikeprofilradio.classList.add('likes');
  }
}

function openOverlayprofilradio(evt) {
  window.setTimeout(antibug, 50);

  commentprofilradio.classList.add("publier-commentaire");
  boutoncommprofilradio.removeEventListener("click", ouvrirOverlay);

  function antibug() {
    window.addEventListener("click", testclose);
  }

  function testclose(evt) {
    if (commentprofilradio.contains(evt.target)) {
      console.log("dedans");
    } else {
      fermerOverlay();
      window.removeEventListener("click", testclose);
    }
  }
}

function fermerOverlay() {
  commentprofilradio.classList.remove("publier-commentaire");
  boutoncommprofilradio.addEventListener("click", ouvrirOverlay);
}

"use strict";
var pon = false;


// gère le drag du player sur pc //

function playerdrag() {
  const topbox = document.querySelector('.place-top');
  const bottombox = document.querySelector('.place-bottom');
  const pagecontainer = document.querySelector('.container-player');
  const drag = {
    name: 'drag',
    axis: 'xy'
  };
  var container = interact.modifiers.restrictRect({
    restriction: pagecontainer,
    endOnly: true
  });
  const playerbox = interact('.player');
  // créer barre de son //
  let soundcommand = document.createElement('div');
  const position = {
    x: 0,
    y: 0
  };
  let player = document.querySelector('.player');
  player.classList.add('draggable');

  //interact.js//

  playerbox.draggable({
    //inertie de l'élément//
    inertia: {
      resistance: 20,
      minSpeed: 80,
      endSpeed: 30
    },
    //zone du drag //
    modifiers: [container],
    listeners: {
      start(event) {

      },
      move(event) {

        position.x += event.dx
        position.y += event.dy
        event.target.style.transform =
          `translate(${position.x}px, ${position.y}px)`
      },
      end(event) {
        playerbox.reflow(drag);
      }
    }
  });





  // gestion des dropzones //
  // TEST POUR LA ZONE DE DROP ET 9A MARCHE POUR CHANGER LA ZONE DE RESTRICTION //


  interact('.dropzone-top').dropzone({
    accept: '.player',
    overlap: 0.6,
    ondragenter: function(event) {
      container = interact.modifiers.restrictRect({
        restriction: topbox,
        endOnly: true
      });
    },
    ondragleave: function(event) {
      container = interact.modifiers.restrictRect({
        restriction: pagecontainer,
        endOnly: true
      });
    }


  });

  interact('.dropzone-bottom').dropzone({
    accept: '.player',
    overlap: 0.6,
    ondragenter: function(event) {
      container = interact.modifiers.restrictRect({
        restriction: bottombox,
        endOnly: true
      });
    },
    ondragleave: function(event) {
      container = interact.modifiers.restrictRect({
        restriction: pagecontainer,
        endOnly: true
      });
    }

  });

}

//gere la transformation du player sur mobile//
function playertel() {
  interact('#logo-player')
    .on('tap', function(event) {
      if (pon == false) {
        testplayer.small();
        pon = true;
      } else {
        testplayer.off();
        pon = false;
      }
    });
}

//gere la transformation du player sur pc//
function playerpc() {
  interact('#logo-player')
    .on('tap', function(event) {
      if (pon == false) {
        testplayer.medium();
        pon = true;
      } else {
        testplayer.off();
        pon = false;
      }
    });
}


class player {

  constructor() {
    this.i = 0;
    this.zone = document.querySelector('.player');
    this.imgradio = document.querySelector('#logo-player');
    //valeur image radio off et radio active//
    this.imgoff = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogoplayer.svg?v=1622559092939';
    this.imgactive = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo%20smooth_chill%206.png?v=1622619831570';
    // stockage idradio et compteur et favoris//
    this.id = 1;
    this.compteur = 0;
    this.favoris = 'false';
    // stockage playeractif //
    this.soundActif = false;

    this.nomradio = document.createElement('span');
    this.nomradio.classList.add('nom-radio');
    this.nomradio.innerHTML = 'Smooth Chill';
    //image scale quand l'image radio est hover//
    this.imghover = document.querySelector('img');
    this.imghover.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210';
    // config svg play-pause //
    this.play = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    this.play.setAttribute('width', '80');
    this.play.setAttribute('height', '70');
    this.play.setAttribute('viewBox', '0 0 65 100');
    this.play.setAttribute('fill', 'none');
    this.play.classList.add('grp-play-pause');
    this.playgun = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgun.setAttribute('id', 'play-pause');
    this.playgdeux = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgdeux.setAttribute('id', 'pause');
    this.playgtrois = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgtrois.setAttribute('id', 'pause-droit');
    this.playgquatre = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgquatre.setAttribute('id', 'Group');
    this.playpathun = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.playpathun.setAttribute('id', 'Vector');
    this.playpathun.setAttribute('d', 'M51.1202 22H39.6619C37.7667 22 36.2244 23.5423 36.2244 25.4375V73.5625C36.2244 75.4577 37.7667 77 39.6619 77H51.1203C53.0155 77 54.5578 75.4577 54.5578 73.5625V25.4375C54.5577 23.5423 53.0154 22 51.1202 22Z');
    this.playpathun.setAttribute('fill', 'white');
    this.playgquatre.append(this.playpathun);
    this.playgtrois.append(this.playgquatre);
    this.playgdeux.append(this.playgtrois);
    this.playgcinq = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgcinq.setAttribute('id', 'pause-gauche');
    this.playgsix = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.playgsix.setAttribute('id', 'Group_2');
    this.playpathdeux = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.playpathdeux.setAttribute('id', 'Vector_2');
    this.playpathdeux.setAttribute('d', 'M23.4791 22H12.0209C10.1256 22 8.58337 23.5423 8.58337 25.4375V73.5625C8.58337 75.4577 10.1256 77 12.0209 77H23.4792C25.3745 77 26.9167 75.4577 26.9167 73.5625V25.4375C26.9166 23.5423 25.3744 22 23.4791 22Z');
    this.playpathdeux.setAttribute('fill', 'white');
    this.playgsix.append(this.playpathdeux);
    this.playgcinq.append(this.playgsix);

    this.pause = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.pause.setAttribute('id', 'play');
    this.pause.setAttribute('d', 'M54.6117 47.1021L11.0561 22.3521C10.2122 21.8736 9.18322 21.8846 8.35022 22.3741C7.51178 22.8691 7 23.7711 7 24.7501V74.25C7 75.229 7.51178 76.131 8.35022 76.626C8.77489 76.8735 9.24856 77 9.72222 77C10.1796 77 10.6423 76.8845 11.0561 76.648L54.6117 51.898C55.4664 51.4086 56 50.4956 56 49.5001C56 48.5046 55.4664 47.5916 54.6117 47.1021Z');
    this.pause.setAttribute('fill', 'white');

    this.playgdeux.append(this.playgcinq);
    this.playgun.append(this.playgdeux);
    this.playgun.append(this.pause);
    this.play.append(this.playgun);

    // config svg suivant //
    this.next = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    this.next.setAttribute('width', '100');
    this.next.setAttribute('height', '60');
    this.next.setAttribute('viewBox', '0 0 120 100');
    this.next.setAttribute('fill', 'white');
    this.triangles = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.triangles.classList.add('triangles');
    this.lightGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.lightGroup.classList.add('lightGroup');
    this.pathlight = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.pathlight.setAttribute('opacity', '0.6');
    this.pathlight.setAttribute('d', 'M53.4872 46.3509C55.7436 47.6536 55.7436 50.9105 53.4872 52.2132L13.718 75.174C11.4615 76.4767 8.64104 74.8483 8.64104 72.2428L8.64104 26.3213C8.64104 23.7158 11.4616 22.0874 13.718 23.3901L53.4872 46.3509Z');
    this.lightGroup.append(this.pathlight);
    this.darkGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    this.darkGroup.classList.add('darkGroup');
    this.pathdarkb = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.pathdarkb.classList.add('dark1');
    this.pathdarkb.setAttribute('opacity', '0.8');
    this.pathdarkb.setAttribute('d', 'M74.9231 46.915C77.1795 48.2177 77.1795 51.4746 74.9231 52.7773L34.3077 76.2266C32.0513 77.5294 29.2308 75.9009 29.2308 73.2955L29.2308 26.3968C29.2308 23.7914 32.0513 22.1629 34.3077 23.4657L74.9231 46.915Z');
    this.darkGroup.append(this.pathdarkb);
    this.pathdark = document.createElementNS('http://www.w3.org/2000/svg', 'path');
    this.pathdark.classList.add('dark2');
    this.pathdark.setAttribute('opacity', '0.8');
    this.pathdark.setAttribute('d', 'M54.6154 46.915C56.8718 48.2177 56.8718 51.4746 54.6154 52.7773L14 76.2266C11.7436 77.5294 8.92307 75.9009 8.92307 73.2955L8.92308 26.3968C8.92308 23.7914 11.7436 22.1629 14 23.4657L54.6154 46.915Z');
    //fin config svg suivant//
    this.darkGroup.append(this.pathdark);
    this.triangles.append(this.lightGroup);
    this.triangles.append(this.darkGroup);
    this.next.append(this.triangles);
    //config svg retour//
    this.back = this.next.cloneNode(true);
    this.back.classList.add('rotate');
    this.back.classList.add('player-back');
    this.next.classList.add('player-next');
    //creation commande de son//
    this.songcommand = document.createElement('div');
    this.songcommand.classList.add('songcommand');
    this.songcommand.append(this.back);
    this.songcommand.append(this.play);
    this.songcommand.append(this.next);
    //zone commenter + liker//
    this.interactgroup = document.createElement('div');
    this.interactgroup.classList.add('interactgroup');
    this.commenter = document.createElement('img');
    this.commenter.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fcomment.svg?v=1622623869640';
    this.commenter.classList.add('bouton-commenter');
    this.like = document.createElement('img');
    this.like.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fcoeur-vide.svg?v=1623318410561';
    this.like.classList.add('bouton-like');
    this.fav = false;
    this.interactgroup.append(this.commenter);
    this.interactgroup.append(this.like);
    //barre de son (creation objet avec interactjs)//
    this.volume = document.createElement('div');
    this.volume.classList.add('zone-volume');
    this.volumeup = document.createElement('img');
    this.volumeup.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-up.svg?v=1622728143745';
    this.volumeup.classList.add('volume-up');
    this.volumedown = document.createElement('img');
    this.volumedown.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-down.svg?v=1622727996426';
    this.volumedown.classList.add('volume-down');
    this.volume.append(this.volumeup);
    this.volume.append(this.volumedown);
    this.nbrvol = 0.5;
    //objet flux audio//
    this.sourceAudio = new Audio();
    this.sourceAudio.src = 'https://media-ice.musicradio.com/Chill';

  }


  medium() {
    this.i++;
    if (this.zone.classList.contains('player-big')) {
      this.zone.classList.remove('player-big');
    } else if (this.zone.classList.contains('player-small')) {
      this.zone.classList.remove('player-small');
    } else if (this.zone.classList.contains('player-off')) {
      this.zone.classList.remove('player-off');
    }
    this.zone.classList.add('player-medium');
    this.zone.append(this.nomradio);
    this.imgradio.src = this.imgactive;
    this.imgradio.classList.add('logo-player-medium');
    this.zone.append(this.songcommand);
    this.zone.append(this.interactgroup);
    this.zone.append(this.volume);
    this.zone.append(this.sourceAudio);
    if (this.i == 1) {
      gestionPlayer();
      animPlayer();
    }
  }

  small() {
    this.i++;
    if (this.zone.classList.contains('player-big')) {
      this.zone.classList.remove('player-big');
    } else if (this.zone.classList.contains('player-medium')) {
      this.zone.classList.remove('player-medium');
    } else if (this.zone.classList.contains('player-off')) {
      this.zone.classList.remove('player-off');
    }
    this.zone.classList.add('player-small');
    this.zone.append(this.nomradio);
    this.imgradio.src = this.imgactive;
    this.imgradio.classList.add('logo-player-small');
    this.next.setAttribute('height', '50');
    this.next.setAttribute('width', '90');
    this.back.setAttribute('height', '50');
    this.back.setAttribute('width', '90');

    this.zone.append(this.songcommand);
    this.zone.append(this.interactgroup);
    this.zone.append(this.volume);
    this.zone.append(this.sourceAudio);

    if (this.i == 1) {
      gestionPlayer();
      animPlayer();
    }

  }

  off() {

    if (this.zone.classList.contains('player-big') == true) {
      this.zone.classList.remove('player-big');
    } else if (this.zone.classList.contains('player-medium') == true) {
      this.zone.classList.remove('player-medium');
    } else if (this.zone.classList.contains('player-small') == true) {
      this.zone.classList.remove('player-small');
    }
    this.nomradio.remove();
    this.songcommand.remove();
    this.interactgroup.remove();
    this.volume.remove();
    this.zone.classList.add('player-off');
    this.imgradio.src = this.imgoff;
  }

  start() {
    this.sourceAudio.play();
    this.soundActif = true;
  }

  stop() {
    this.sourceAudio.pause();
    this.soundActif = false;
  }

  up() {
    this.nbrvol = parseFloat(this.nbrvol, 10) + 0.1;
    if (this.nbrvol >= 1.0) {
      this.nbrvol = 1.0;
    }
    this.sourceAudio.volume = Number.parseFloat(this.nbrvol, 1).toFixed(1);
  }

  down() {
    this.nbrvol = this.nbrvol - 0.1;
    if (this.nbrvol <= 0.0) {
      this.nbrvol = 0.0;
    }
    this.sourceAudio.volume = Number.parseFloat(this.nbrvol, 1).toFixed(1);
  }

  changerNom(nouveaunom) {
    this.nomradio.innerHTML = nouveaunom;
  }

  changerSource(nouvellesource) {
    this.sourceAudio.src = nouvellesource;
  }

  changerImg(nouvelleimage) {
    this.imgactive = nouvelleimage;
    this.imgradio.src = this.imgactive;
  }

  changerImgActive(nouvelleimage) {
    this.imgactive = nouvelleimage;
  }

  changerId(nouvelleid) {
    this.id = nouvelleid;
  }

  changerCompteur(nouveauCompteur) {
    this.compteur = nouveauCompteur;
  }

  changerVolume(nouveauvolume) {
    if (isNaN(nouveauvolume)) {
      nouveauvolume = 0.5;
    }
    //adapte la valeur volume pour avoir un seul chiffre après la virgule//
    this.nbrvol = Number.parseFloat(nouveauvolume).toFixed(1);
    this.sourceAudio.volume = this.nbrvol;
  }

  toggleFav(favori) {
    if (favori == true) {
      this.like.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fcoeur-plein.svg?v=1623318413590';
      this.favoris = 'true';
    } else {
      this.like.src = 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fcoeur-vide.svg?v=1623318410561';
      this.favoris = 'false';
    }
  }

  retournerid() {
    return (this.id);
  }

  retournerNom() {
    return (this.nomradio.innerHTML);
  }

  retournerImg() {
    return (this.imgactive);
  }


  retournerSrc() {
    return (this.sourceAudio.src);
  }

  retournerVolume() {
    return (parseFloat(this.nbrvol).toFixed(1));
  }

  retournerActif() {
    return (this.soundActif);
  }

  retournerCompteur() {
    return (this.compteur);
  }

  retorunerFavoris() {
    return (this.favoris);
  }


}

// gere la récupération de données pour le player (arrivé sur une page)//

function recupInfo() {
  let idRadio;
  let nomRadio;
  let imgRadio;
  let srcRadio;
  let volume;
  let compteur;
  let favoris;

  if (localStorage.length == 7) {
    idRadio = localStorage.getItem('idRadio');
    nomRadio = localStorage.getItem('nomRadio');
    imgRadio = localStorage.getItem('imgRadio');
    srcRadio = localStorage.getItem('srcRadio');
    volume = localStorage.getItem('volume');
    favoris = localStorage.getItem('favoris');
    compteur = localStorage.getItem('compteur');

    testplayer.changerId(idRadio);
    testplayer.changerNom(nomRadio);
    testplayer.changerImgActive(imgRadio);
    testplayer.changerSource(srcRadio);
    testplayer.changerVolume(volume);
    testplayer.retournerVolume();
    testplayer.changerCompteur(compteur);
    if (favoris == 'true') {
      testplayer.toggleFav(true);
    } else {
      testplayer.toggleFav(false);
    }

  } else {
    changermusique(1);
  }

}




// gestion du player quand il est actif //
function gestionPlayer() {
  let pause = document.querySelector('.grp-play-pause');
  let ecoute = false;
  pause.addEventListener('click', play);
  let boutonup = document.querySelector('.volume-up');
  boutonup.addEventListener('click', up);
  let boutondown = document.querySelector('.volume-down');
  boutondown.addEventListener('click', down);
  let boutonnext = document.querySelector('.player-next');
  boutonnext.addEventListener('click', next);
  let boutonprev = document.querySelector('.player-back');
  boutonprev.addEventListener('click', prev);
  let boutonlike = document.querySelector('.bouton-like');
  boutonlike.addEventListener('click', fav);
  let boutoncom = document.querySelector('.bouton-commenter');
  boutoncom.addEventListener('click', commenter);
  var indicvolume = document.querySelector('.sound-number>span');
  indicvolume.textContent = testplayer.retournerVolume() * 100;
  var divindicvolume = document.querySelector('.sound-number');
  divindicvolume.style.display = 'none';

  var activanim = 0;

  // si utilisateur non-connecter //
  var groupebtnprofil = document.querySelector('.interactgroup');
  if (pseudo == 'GUEST') {
    groupebtnprofil.addEventListener('click',enleveroptions,true);
  }

  //desactive le bouton commentaire et like //

function enleveroptions(evt) {
  evt.stopPropagation();
}




  //changer le coeur en favoris sur le player//
  function fav(evt) {

    let param = new FormData();
    param.append("type", 6);
    param.append("nomRadio", testplayer.retournerNom());
    param.append("idRadio", testplayer.retournerid());
    param.append("compteur", testplayer.retournerCompteur());
    param.append('favoris', testplayer.retorunerFavoris());


    fetch('info.php', {
      method: 'POST',
      body: param,
    }).then(function(response) {
      return response.json();
    }).then(function(object) {
      if (object.favoris == 'true') {
        testplayer.toggleFav(true);
      } else {
        testplayer.toggleFav(false);
      }
      return (object.nomRadio);
    }).catch(function(error) {
      console.error('bugEnvoi');
      console.error(error);
    })
  }

  //redirige vers le profil radio en écoute //

  function commenter(evt) {
    let adresse = 'https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=' + testplayer.retournerid();
    window.location.replace(adresse);
  }



  function up(evt) {
    testplayer.up();
    indicvolume.textContent = testplayer.retournerVolume() * 100;
    apparitionvol();
  }

  function down(evt) {
    testplayer.down();
    indicvolume.textContent = testplayer.retournerVolume() * 100;
    apparitionvol();
  }

  function next(evt) {
    if (testplayer.retournerActif() == true) {
      play(evt);
    }
    changermusique(2);

  }

  function prev(evt) {
    if (testplayer.retournerActif() == true) {
      play(evt);
    }
    changermusique(3);

  }

  // pause ou play //

  function play(evt) {
    let play = document.querySelector('#play');
    let pause = document.querySelector('#pause');
    if (ecoute == false) {
      testplayer.start();
      ecoute = true;
      envoiActif(7);
      play.classList.add('actif');
      pause.classList.add('actif');
    } else {
      testplayer.stop();
      ecoute = false;
      envoiActif(8);
      play.classList.remove('actif');
      pause.classList.remove('actif');
    }
    evt.stopPropagation();
  }



  //animation apparition indicateur volume//

  function apparitionvol() {
    divindicvolume.style.display = 'block';
    activanim++;
    if (activanim == 1) {
      window.setTimeout(non, 1900);
    }

    function non() {
      divindicvolume.style.display = 'none';
      activanim = 0;
    }
  }

}

// gere le lancer aléatoire des dès dans la page profil //

function requetealea(evt) {
  let image = document.querySelector('#logo-player');

  if(image.src == 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogoplayer.svg?v=1622559092939') {
    window.alert('Le player doit être en position ouvert');
  } else {
    if (testplayer.retournerActif() == true) {
      // déclanche l'event pour arreter la radio //
      window.alert('Le player doit être en pause');
    } else {
      changermusique(4);
    }

  }

}

// pour écouter radio précise //
function radioprofil(evt) {
  let image = document.querySelector('#logo-player');
  if(image.src == 'https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogoplayer.svg?v=1622559092939') {
    window.alert('Le player doit être en position ouvert');
  } else {
    if (testplayer.retournerActif() == true) {
      window.alert('Le player doit être en pause');
    } else {
      let id = this.dataset.idradio;
      sessionStorage.setItem('newIdRadio',id);
      changermusique(5);
    }
  }

}


// gere la requete fetch pour toutes les requetes avec un changement de musique du player//

function changermusique(type) {
  let param = new FormData();

  if (type == 4) {
    param.append("type", 2);
    let aleatoire = Math.floor(Math.random() * (23 - 1 +1)) + 1;
    param.append("nomRadio", testplayer.retournerNom());
    param.append("idRadio", testplayer.retournerid());
    param.append("photoRadio", testplayer.retournerImg());
    param.append("url", testplayer.retournerSrc());
    param.append("compteur", aleatoire);

  } else if (type == 5) {
    param.append("type", 5);
    let newcompteur = sessionStorage.getItem('newIdRadio') - 1;
    param.append("idRadio", sessionStorage.getItem('newIdRadio'));
    param.append("compteur", newcompteur);
    sessionStorage.removeItem('newIdRadio');
  } else if (type > 1) {
    param.append("type", type);
    param.append("nomRadio", testplayer.retournerNom());
    param.append("idRadio", testplayer.retournerid());
    param.append("photoRadio", testplayer.retournerImg());
    param.append("url", testplayer.retournerSrc());
    param.append("compteur", testplayer.retournerCompteur());
  } else {
    param.append("type", type);
    param.append("idRadio", 1);
    param.append('compteur', 0);
  }



  fetch('info.php', {
    method: 'POST',
    body: param,
  }).then(function(response) {
    return response.json();
  }).then(function(object) {
    testplayer.changerNom(object.nomRadio);
    testplayer.changerSource(object.url);
    testplayer.changerId(object.idRadio);
    testplayer.changerCompteur(object.compteur);
    //permet d'ajouter à la source le dossier//
    let presource = './images/';
    let test = presource.concat(object.photoRadio);
    if (object.type == 1) {
      testplayer.changerImgActive(test);
    } else {
      testplayer.changerImg(test);
    }
    if (object.favoris == 'true') {
      testplayer.toggleFav(true);
    } else {
      testplayer.toggleFav(false);
    }
    return (object.nomRadio);
  }).catch(function(error) {
    console.error('bugEnvoi');
    console.error(error);
  })
}



// gere l'envoie de requete quand un utilisateur commence à écouter une webradio et quand il arrete //
function envoiActif(type) {
  let param = new FormData();
  param.append("type", type);
  param.append("nomRadio", testplayer.retournerNom());
  param.append("idRadio", testplayer.retournerid());
  param.append("photoRadio", testplayer.retournerImg());
  param.append("url", testplayer.retournerSrc());
  param.append("compteur", testplayer.retournerCompteur());

  fetch('info.php', {
    method: 'POST',
    body: param,
  })
}

// gere l'animation des fleches next et prev du player//
function animPlayer() {
  //animation hover logo//
  //animation next et prev//
  const svg = document.querySelectorAll('.triangles');
  for (var i = 0; i < svg.length; i++) {
    svg[i].addEventListener('click', animNext);
  }

  function animNext(evt) {
    const colors = ['#FFC15E', '#03F29E', 'white']
    const rando = () => colors[Math.floor(Math.random() * colors.length)];
    document.documentElement.style.cssText = `
    --dark-color: ${rando()};
    --light-color: ${rando()};
    `
    this.classList.add('on');
    var marche = document.querySelector('.triangles.on');
    var dark1 = document.querySelector('.triangles.on .dark1');
    var dark2 = document.querySelector('.triangles.on .dark2');
    window.setTimeout(transition, 600);

    function transition() {
      dark1.classList.add('transb');
      dark2.classList.add('transb');
      marche.classList.remove('on');
      window.setTimeout(transitionb, 50);
    }

    function transitionb() {
      dark1.classList.remove('transb');
      dark2.classList.remove('transb');
    }

  }
}


// gere l'apparition de l'image d'aide pour indiquer l'endroit pour changer la taille du player //
function logoplayer() {
  var logo = document.querySelector('#logo-player');
  var nouveaulogo = document.querySelector('#logo-hover');
  logo.addEventListener('mouseover', animHover);
  logo.addEventListener('mouseout', animpart);

  function animHover(evt) {
    nouveaulogo.style.display = 'block';
  }

  function animpart(evt) {
    nouveaulogo.style.display = 'none';
  }

}

//stockage des informations du player avant le déchargment de la page//
function infoplayer(evt) {
  localStorage.clear();
  envoiActif(7);

  localStorage.setItem('idRadio', testplayer.retournerid());
  localStorage.setItem('nomRadio', testplayer.retournerNom());
  localStorage.setItem('imgRadio', testplayer.retournerImg());
  localStorage.setItem('srcRadio', testplayer.retournerSrc());
  localStorage.setItem('volume', testplayer.retournerVolume());
  localStorage.setItem('compteur', testplayer.retournerCompteur());
  localStorage.setItem('favoris', testplayer.retorunerFavoris());
}

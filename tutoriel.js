"use strict";
document.addEventListener('DOMContentLoaded',lancevideo);

function lancevideo(evt){
  var bouton = document.querySelector('.quitter');
  var boutonb = document.querySelector('.quitterb');
  bouton.addEventListener('click',fintuto);
  boutonb.addEventListener('click',fintuto);

  function fintuto(evt){
    let zone = document.querySelector('.tutoriel');
    let video = zone.querySelector('#tuto');
    zone.remove();
  }

}

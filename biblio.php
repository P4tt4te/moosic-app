<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$req = $bdd->prepare("SELECT * FROM Radio WHERE idRadio = 46");
$req->execute(array());   
$ligne = $req->fetch(PDO::FETCH_ASSOC);




?>






































<!DOCTYPE html>
<html>
  <head>
    <title>Moosic</title>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
    />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="style-bibliotheque.css" type="text/css" media="screen" />
    <link
      rel="icon"
      href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon.ico?v=1622468860944"
    />
  </head>

  <body>
    <header>
      <div class="player player-off case">
        <img src="" id="logo-player" />
      </div>
      <nav>
        <svg
          id="menu-burger"
          width="45"
          height="45"
          viewBox="0 0 384 384"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <g id="menu1">
            <path
              id="BARRE-BAS"
              d="M368 289H16C7.16797 289 0 277.352 0 263C0 248.648 7.16797 237 16 237H368C376.832 237 384 248.648 384 263C384 277.352 376.832 289 368 289Z"
              fill="#FFFFFF"
            />
            <path
              id="BARRE-MILLIEU"
              d="M368 197H16C7.16797 197 0 185.352 0 171C0 156.648 7.16797 145 16 145H368C376.832 145 384 156.648 384 171C384 185.352 376.832 197 368 197Z"
              fill="#FFFFFF"
            />
            <path
              id="BARRE-HAUT"
              d="M368 105H16C7.16797 105 0 93.3521 0 79C0 64.6479 7.16797 53 16 53H368C376.832 53 384 64.6479 384 79C384 93.3521 376.832 105 368 105Z"
              fill="#FFFFFF"
            />
          </g>
        </svg>
        <ul>
          <li>
            <a href="https://moosic-app.glitch.me/accueil.html">Accueil</a>
          </li>
          <li>
            <a href="https://moosic-app.glitch.me/rechercher.html"
              >Rechercher</a
            >
          </li>
          <li>
            <a href="https://moosic-app.glitch.me/decouvrir.html">Découvrir</a>
          </li>
          <li>
            <a href="https://moosic-app.glitch.me/mon-mood.html">Mon mood</a>
          </li>
          <li>
            <a href="https://moosic-app.glitch.me/bibliotheque.html"
              >Bibliothèque</a
            >
          </li>
        </ul>
        <div class="profil">
          <div class="profil-name">
            <svg
              width="67"
              height="78"
              viewbox="0 0 67 78"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <circle
                cx="33.5"
                cy="44.5"
                r="32"
                stroke="#00FFA3"
                stroke-width="3"
              />
              <path
                d="M33.4996 35.2498C36.0538 35.2498 38.1245 33.1792 38.1245 30.6249C38.1245 28.0706 36.0538 26 33.4996 26C30.9453 26 28.8746 28.0706 28.8746 30.6249C28.8746 33.1792 30.9453 35.2498 33.4996 35.2498Z"
                fill="white"
              />
              <path
                d="M42.4887 59.6542L36.7931 61.7902L39.6255 62.8524C40.8444 63.305 42.1597 62.6779 42.6029 61.4986C42.8389 60.8694 42.7691 60.2104 42.4887 59.6542Z"
                fill="white"
              />
              <path
                d="M22.7497 51.5837C21.5562 51.1411 20.2216 51.7418 19.7722 52.9375C19.324 54.1332 19.9303 55.4656 21.126 55.9149L23.6208 56.8504L30.2073 54.3802L22.7497 51.5837Z"
                fill="white"
              />
              <path
                d="M47.2276 52.9376C46.7782 51.7418 45.4436 51.1411 44.2502 51.5838L25.7506 58.5212C24.5548 58.9706 23.9485 60.3029 24.3967 61.4987C24.8399 62.6775 26.1549 63.3052 27.3742 62.8524L45.8738 55.9151C47.0697 55.4657 47.676 54.1334 47.2276 52.9376Z"
                fill="white"
              />
              <path
                d="M49.6871 44.4997H44.1792L40.1934 36.528C39.7792 35.7004 38.9366 35.2289 38.0682 35.2499H33.4999H28.9314C28.0631 35.2289 27.2216 35.7005 26.8065 36.528L22.8206 44.4997H17.3128C16.0358 44.4997 15.0004 45.5351 15.0004 46.8121C15.0004 48.0891 16.0358 49.1245 17.3128 49.1245H24.2501C25.1264 49.1245 25.9269 48.6299 26.3187 47.8463L28.875 42.7337V51.4114L33.4998 53.1455L38.1248 51.4108V42.7337L40.6811 47.8463C41.073 48.63 41.8735 49.1245 42.7497 49.1245H49.687C50.964 49.1245 51.9994 48.0891 51.9994 46.8121C51.9994 45.5351 50.9641 44.4997 49.6871 44.4997Z"
                fill="white"
              />
              <circle cx="34" cy="7" r="7" fill="#00FFA3" />
            </svg>
            <span class="profil-name">LaZen53</span>
          </div>
          <img
            src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733"
            class="profil-options"
          />
        </div>
      </nav>
    </header>
    <main>
      <div class="listes-webradios">
        <div class="webradios-likees">
          <div class="zone-titre">
            <h2>Webradios likées</h2>
            <svg
              class="trait"
              width="218"
              height="3"
              viewBox="0 0 218 3"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <line
                y1="1.5"
                x2="218"
                y2="1.5"
                stroke="white"
                stroke-width="3"
              />
            </svg>
          </div>
          <div class="liste-webradios-likees">
            <svg
              class="fleche likes gauche"
              width="37"
              height="61"
              viewBox="0 0 37 61"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M1.55451 32.8802L28.7003 60.0255C29.3281 60.6538 30.1663 61 31.0599 61C31.9536 61 32.7917 60.6538 33.4196 60.0255L35.4187 58.0269C36.7195 56.7246 36.7195 54.6079 35.4187 53.3076L12.6237 30.5126L35.444 7.69238C36.0718 7.06404 36.4185 6.22641 36.4185 5.33324C36.4185 4.43907 36.0718 3.60145 35.444 2.97261L33.4449 0.974501C32.8165 0.346156 31.9789 -1.34179e-06 31.0852 -1.41992e-06C30.1916 -1.49805e-06 29.3534 0.346156 28.7256 0.974501L1.55451 28.1446C0.925174 28.7749 0.579512 29.6165 0.581496 30.5112C0.579512 31.4093 0.925173 32.2504 1.55451 32.8802Z"
                fill="white"
              />
            </svg>
            <div class="zone-cases likes">
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
            </div>
            <svg
              class="fleche likes droite"
              width="37"
              height="61"
              viewBox="0 0 37 61"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M35.4455 28.1198L8.29972 0.974504C7.67187 0.34616 6.83375 0 5.94008 0C5.04641 0 4.20829 0.34616 3.58044 0.974504L1.58134 2.97311C0.280513 4.27542 0.280513 6.39205 1.58134 7.69238L24.3763 30.4874L1.55605 53.3076C0.928199 53.936 0.581543 54.7736 0.581543 55.6668C0.581543 56.5609 0.928199 57.3986 1.55605 58.0274L3.55515 60.0255C4.18349 60.6538 5.02112 61 5.91479 61C6.80845 61 7.64658 60.6538 8.27442 60.0255L35.4455 32.8554C36.0748 32.2251 36.4205 31.3835 36.4185 30.4888C36.4205 29.5907 36.0748 28.7496 35.4455 28.1198Z"
                fill="white"
              />
            </svg>
          </div>
        </div>
        <div class="webradios-recemment-ecoutees">
          <div class="zone-titre">
            <h2>Récemment écouté</h2>
            <svg
              class="trait"
              width="218"
              height="3"
              viewBox="0 0 218 3"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <line
                y1="1.5"
                x2="218"
                y2="1.5"
                stroke="white"
                stroke-width="3"
              />
            </svg>
          </div>
          <div class="liste-recemment-ecoute">
            <svg
              class="fleche ecoute gauche"
              width="37"
              height="61"
              viewBox="0 0 37 61"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M1.55451 32.8802L28.7003 60.0255C29.3281 60.6538 30.1663 61 31.0599 61C31.9536 61 32.7917 60.6538 33.4196 60.0255L35.4187 58.0269C36.7195 56.7246 36.7195 54.6079 35.4187 53.3076L12.6237 30.5126L35.444 7.69238C36.0718 7.06404 36.4185 6.22641 36.4185 5.33324C36.4185 4.43907 36.0718 3.60145 35.444 2.97261L33.4449 0.974501C32.8165 0.346156 31.9789 -1.34179e-06 31.0852 -1.41992e-06C30.1916 -1.49805e-06 29.3534 0.346156 28.7256 0.974501L1.55451 28.1446C0.925174 28.7749 0.579512 29.6165 0.581496 30.5112C0.579512 31.4093 0.925173 32.2504 1.55451 32.8802Z"
                fill="white"
              />
            </svg>
            <div class="zone-cases ecoute">
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
              <div class="case-radio">
                <!--        php div radio1-->
                <img
                  class="logo-radio"
                  src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill.svg?v=1622466702646"
                />
                <!--            php logo webradio-->
                <p class="nom-radio">
                  Smooth chill
                </p>
                <!--            php nom webradio-->
                <p class="categorie">
                  catégorie
                </p>
                <!--            php catégorie webradio-->
              </div>
              <!--        fin php div radio1-->
            </div>
            <svg
              class="fleche ecoute droite"
              width="37"
              height="61"
              viewBox="0 0 37 61"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M35.4455 28.1198L8.29972 0.974504C7.67187 0.34616 6.83375 0 5.94008 0C5.04641 0 4.20829 0.34616 3.58044 0.974504L1.58134 2.97311C0.280513 4.27542 0.280513 6.39205 1.58134 7.69238L24.3763 30.4874L1.55605 53.3076C0.928199 53.936 0.581543 54.7736 0.581543 55.6668C0.581543 56.5609 0.928199 57.3986 1.55605 58.0274L3.55515 60.0255C4.18349 60.6538 5.02112 61 5.91479 61C6.80845 61 7.64658 60.6538 8.27442 60.0255L35.4455 32.8554C36.0748 32.2251 36.4205 31.3835 36.4185 30.4888C36.4205 29.5907 36.0748 28.7496 35.4455 28.1198Z"
                fill="white"
              />
            </svg>
          </div>
        </div>
      </div>

      <div class="MODULE-PUB">
        <div class="case">
          <img
            src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Frechercher.svg?v=1622465544509"
            class="pub"
          />
        </div>
      </div>
      <img
        src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036"
        class="fond-montagne"
      />
    </main>
    <footer>
      <div class="footer-links">
        <a href="">Qui-sommes-nous ?</a>
        <a href="">FAQ</a>
        <a href="">Conditions d'utilisations</a>
      </div>
      <p class="footer-name">Moosic -> 2021</p>
    </footer>
  </body>
  <script src="script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
</html>

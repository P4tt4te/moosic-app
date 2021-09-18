<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8") ;
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");

$idUti = $_SESSION['idUtilisateur'];

$select = $bdd -> prepare('SELECT * FROM possede WHERE idUtilisateur = ?');
$select -> execute(array($idUti));
$ligne = $select->fetch(PDO::FETCH_ASSOC);

if($ligne == false){

    if(isset($_POST['moodGood'])){
        $insertMood = $bdd -> prepare('INSERT INTO possede(idMood,idUtilisateur) VALUES (1,?)');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=1"); // Quand l'utilisateur sélectionne goodMood je mets dans l'url de mood-selectionne 1 (normalement lors de l'inscription un mood est déjà mit, c'est juste au cas où) 
        exit;

    }
    if(isset($_POST['moodAnxieux'])){
        $insertMood = $bdd -> prepare('INSERT INTO possede(idMood,idUtilisateur) VALUES (2,?)');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=2"); // Pareil avec 2
        exit;

    }
    if(isset($_POST['moodEnerve'])){
        $insertMood = $bdd -> prepare('INSERT INTO possede(idMood,idUtilisateur) VALUES (3,?)');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=3");// Pareil avec 3
        exit;

    }
}else{
    if(isset($_POST['moodGood'])){
        $insertMood = $bdd -> prepare('UPDATE possede SET idMood = 1 WHERE idUtilisateur = ?');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=1"); // Sinon j'update la base de données
        exit;

    }
    if(isset($_POST['moodAnxieux'])){
        $insertMood = $bdd -> prepare('UPDATE possede SET idMood = 2 WHERE idUtilisateur = ?');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=2");// Pareil
        exit;

    }
    if(isset($_POST['moodEnerve'])){
        $insertMood = $bdd -> prepare('UPDATE possede SET idMood = 3 WHERE idUtilisateur = ?');
        $insertMood -> execute(array($idUti));
        header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/mood-selectionne.php?id=3"); // Pareil
        exit;

    }
}


?>
<?php if(isset($_SESSION['idUtilisateur'])){ ?>




  <!DOCTYPE html>
  <html>

  <head>
      <title>Moosic</title>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
      <link rel="preconnect" href="https://fonts.gstatic.com" />
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
      <link rel="icon" href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon.ico?v=1622468860944">
      <link rel="stylesheet" href="style-mon-mood.css" type="text/css" media="screen" />
  </head>

  <body>
      <header>
          <div class="player player-off case">
              <img alt="image webradio" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210"
                  id="logo-hover">
              <img alt="agrandir" src="" id="logo-player">
          </div>
          <nav>
              <svg id="menu-burger" width="45" height="45" viewBox="0 0 384 384" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <g id="menu1">
                      <path id="BARRE-BAS"
                          d="M368 289H16C7.16797 289 0 277.352 0 263C0 248.648 7.16797 237 16 237H368C376.832 237 384 248.648 384 263C384 277.352 376.832 289 368 289Z"
                          fill="#34314F" />
                      <path id="BARRE-MILLIEU"
                          d="M368 197H16C7.16797 197 0 185.352 0 171C0 156.648 7.16797 145 16 145H368C376.832 145 384 156.648 384 171C384 185.352 376.832 197 368 197Z"
                          fill="#34314F" />
                      <path id="BARRE-HAUT"
                          d="M368 105H16C7.16797 105 0 93.3521 0 79C0 64.6479 7.16797 53 16 53H368C376.832 53 384 64.6479 384 79C384 93.3521 376.832 105 368 105Z"
                          fill="#34314F" />
                  </g>
              </svg>
              <ul>
                  <li>
                      <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/accueil.php?video=0">Accueil</a>
                  </li>
                  <li>
                      <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/rechercher.php">Rechercher</a>
                  </li>
                  <li>
                      <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/decouvrir.php">Découvrir</a>
                  </li>
                  <li>
                      <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/mon-mood.php">Mon mood</a>
                  </li>
                  <li>
                      <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/bibliotheque.php">Bibliothèque</a>
                  </li>

              </ul>
              <div class="profil">
                  <div class="profil-name">
  									<a href="https://la-projets.univ-lemans.fr/~mmi1pj03/profil.php">
                      <svg width="67" height="78" viewbox="0 0 67 78" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <circle class="cercle-mood-stroke" cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
                          <image class='logo-profil' width="40" height="40" x="13.5" y="23"
                              xlink:href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmeditation.svg?v=1623682812929"
                              alt="image logo profil" />
                          <circle class="cercle-mood-fill" cx="34" cy="7" r="7" fill="#00FFA3" />
                      </svg>
  									</a>
                      <span class="profil-name">LaZen53</span>
                  </div>
                  <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733"
                      class="profil-options" alt="engrenage"/>
              </div>
          </nav>
          <div class="menu-options case none">
              <div class="title-options">
                  <img class='engrenage-option'
                      src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foption.svg?v=1623851085548"
                      alt="engrenage option">
                  <p class="word-title-options">Options</p>
              </div>
              <div class="container-liste-options">
                  <div class="edit-profil edit-item">
                      <img class="profil-edit-img"
                          src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fedition-profil.svg?v=1622469121712"
                          alt="logo edition">
                      <p>Editer le profil</p>
                  </div>
                  <div class="creer-compte edit-item">
                      <img class="creer-compte-img"
                          src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fuser.svg?v=1623851085549"
                          alt="logo agrandir ou rétrécir">
                      <p>Inscription / Connexion</p>
                  </div>
              </div>
          </div>
      </header>
    <main>
        <div class="explication-choix">
            <div class="selection-mood">
                <form method="post">
                    <h1 class="titre case">Quelle est votre humeur du jour ?</h1>

                        <button class="brique case" name="moodGood">
                            <p class="forme"> Good mood </p>
                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle id="Ellipse 5" cx="21.5" cy="21.5" r="21.5" fill="#03F29E" />
                            </svg>
                        </button>

                        <button class="brique case" name="moodAnxieux">
                            <p class="forme"> Anxieux </p>
                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle id="Ellipse 5" cx="21.5" cy="21.5" r="21.5" fill="#FAFF00" />
                            </svg>
                        </button>

                        <button class="brique case" name="moodEnerve">
                            <p class="forme"> Énervé </p>
                            <svg width="43" height="43" viewBox="0 0 43 43" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle id="Ellipse 5" cx="21.5" cy="21.5" r="21.5" fill="#FF0000" />
                            </svg>
                        </button>

                </form>
            </div>

            <section class="description-mood case">
                <h1>Explication</h1>
                <p>
                    Sur Moosic, il vous est possible de sélectionner parmi trois humeurs
                    différentes: Good Mood (de bonne humeur), anxieux et énervé. Selon
                    votre choix, Moosic sélectionne pour vous les webradios qui vous
                    correspondent le plus et qui seront le plus susceptible de vous
                    reposer, de vous apaiser, ou de vous divertir. Vous pouvez changer
                    votre Mood autant de fois que vous le souhaitez et à tout moment.
                </p>
                <h1>Exemple</h1>
                <p>
                    Vous vous sentez stressé ? Pas d'inquiétude, Moosic est là pour
                    vous. Il vous suffit de cliquer sur le bouton "Anxieux", et le tour
                    est joué. Dirigez vous maintenant sur votre profil ou sur l'accueil
                    et détendez-vous en écoutant les radios que nous vous proposons.
                    Mince! Vous arrivez au travail et renversez votre café sur votre
                    nouvelle chemise blanche… Vous cliquez alors sur le bouton “énervé”
                    et vous sentez votre esprit s'apaiser au rythme de longues nappes
                    musicales et votre frustration s’envoler dans de merveilleux
                    paysages sonores... Bonne écoute !
                </p>
            </section>
        </div>
        <div class="sound-number" style="display:none">
            <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479"
                alt="logo-volume">
            <span>0</span>
        </div>
        </div>
        <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036"
            class="fond-montagne" />
        <div class="container-player">
            <div class="dropzone-top">
            </div>
            <div class="dropzone-bottom">
            </div>
        </div>
        <div class="loader">
            <img class='logo-loader'
                src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-moosic-chargement.svg?v=1623660668507"
                alt="logo-moosic-chargement">
        </div>
    </main>
    <footer>
        <div class="footer-links">
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/quisommesnous.html">Qui-sommes-nous ?</a>
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/faq.html">FAQ</a>
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/cgu.html">Conditions d'utilisations</a>
        </div>
        <p class="footer-name">Moosic -> 2021</p>
    </footer>
</body>
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="player.js"></script>

</html>
<?php }else{
    header("Location: index.php");
}

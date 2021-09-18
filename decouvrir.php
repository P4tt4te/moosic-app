<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
require (__DIR__ . "/param.inc.php");

 // Etape 1 : connexion au serveur de base de données
 $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
 $bdd->query("SET NAMES utf8");
 $bdd->query("SET CHARACTER SET 'utf8'");
 $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 $radio1 = $bdd->query('SELECT categorie FROM Radio WHERE categorie = "study" ORDER BY idRadio DESC LIMIT 0,3'); // récup de la catégorie
 $categorieJazz = $radio1->fetch(PDO::FETCH_ASSOC);
 $radio1 = $bdd->query('SELECT * FROM Radio WHERE categorie = "study" ORDER BY idRadio DESC LIMIT 0,3'); // study



 $radio2 = $bdd->query('SELECT categorie FROM Radio WHERE categorie = "relax" ORDER BY idRadio ASC LIMIT 0,3'); // récup de la catégorie
 $categorieChill = $radio2->fetch(PDO::FETCH_ASSOC);
 $radio2 = $bdd->query('SELECT * FROM Radio WHERE categorie = "relax" ORDER BY idRadio ASC LIMIT 0,3'); // relax

 $radio3 = $bdd->query('SELECT *, SUM(Ecoute.ecouteGlobal) as ecouteGlobal FROM Ecoute INNER JOIN Radio ON Ecoute.idRadio = Radio.idRadio GROUP BY Ecoute.idRadio ORDER BY ecouteGlobal DESC LIMIT 0,2'); // On stock les radios les plus écoutées par tout les utilisateurs




?>


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
    <link rel="stylesheet" href="style-decouvrir.css" type="text/css" media="screen" />
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
        <section class="container-top-section">
            <div class="container-categories">
                <div class="case categorie1">
                    <p><?php echo(strtoupper($categorieJazz['categorie']));?></p>
                    <?php while($ligne = $radio1->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
                    <a
                        href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($ligne['idRadio']) ?>'>
                        <img class="img-radios" src="./images/<?php echo($ligne['photoRadio']); ?>">
                    </a>
                    <?php } ?>
                </div>
                <div class="case categorie2">
                    <p><?php echo(strtoupper($categorieChill['categorie']));?></p>
                    <?php while($ligne = $radio2->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
                    <a
                        href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($ligne['idRadio']) ?>'>
                        <img class="img-radios" src="./images/<?php echo($ligne['photoRadio']); ?>">
                    </a>
                    <?php } ?>

                </div>
            </div>
            <div class="case top-webradios">
                <p>Top Webradios</p>
                <div class="top-container">
                    <?php while($ligne = $radio3->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
                    <div class="webradio-top-case">
                        <p class="nom-webradio"><?php echo($ligne['nomRadio']); ?></p>
                        <a
                            href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($ligne['idRadio']) ?>'>
                            <img class="img-radios" src="./images/<?php echo($ligne['photoRadio']); ?>">
                        </a>
                    </div>
                    <?php } ?>
                </div>
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

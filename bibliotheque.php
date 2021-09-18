<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
session_start();
require (__DIR__ . "/param.inc.php");

$idUti = $_SESSION['idUtilisateur'];
 // Etape 1 : connexion au serveur de base de données
 $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
 $bdd->query("SET NAMES utf8");
 $bdd->query("SET CHARACTER SET 'utf8'");
 $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$favoris = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url,Radio.categorie,
CASE
    WHEN favoris.idUtilisateur IS NULL THEN "false"
    ELSE "true"
END AS favoris
FROM Radio

LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti
HAVING favoris = "true"
ORDER BY Radio.idRadio DESC');
$favoris->bindValue('idUti',$idUti,PDO::PARAM_INT);
$favoris -> execute(); // On stock les favoris de l'utilisateur

$derniereEcoute = $bdd -> prepare('SELECT * FROM Radio INNER JOIN Ecoute ON Radio.idRadio = Ecoute.idRadio WHERE idUtilisateur = ?  ORDER BY Ecoute.derniereEcoute DESC LIMIT 0,7');
$derniereEcoute -> execute(array($idUti)); // On stock quelles sont les radios dernièrement écoutées par l'utilisateur

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
      <link rel="stylesheet" href="style-bibliotheque.css" type="text/css" media="screen" />
      <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  </head>

  <body>
      <header>
          <div class="player player-off case">
              <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210"
                  id="logo-hover" alt="agrandir">
              <img src="" id="logo-player" alt="image webradio">
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
                      class="profil-options" alt="engrenage" />
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
        <div class="listes-webradios">
            <div class="webradios-likees">
                <div class="zone-titre">
                    <h2>Webradios likées</h2>
                    <svg class="trait" width="218" height="3" viewBox="0 0 218 3" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <line y1="1.5" x2="218" y2="1.5" stroke="white" stroke-width="3" />
                    </svg>
                </div>
                <div class="swiper-like">
                    <svg class="fleche likes gauche swiper-button-like-prev" width="37" height="61" viewBox="0 0 37 61"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.55451 32.8802L28.7003 60.0255C29.3281 60.6538 30.1663 61 31.0599 61C31.9536 61 32.7917 60.6538 33.4196 60.0255L35.4187 58.0269C36.7195 56.7246 36.7195 54.6079 35.4187 53.3076L12.6237 30.5126L35.444 7.69238C36.0718 7.06404 36.4185 6.22641 36.4185 5.33324C36.4185 4.43907 36.0718 3.60145 35.444 2.97261L33.4449 0.974501C32.8165 0.346156 31.9789 -1.34179e-06 31.0852 -1.41992e-06C30.1916 -1.49805e-06 29.3534 0.346156 28.7256 0.974501L1.55451 28.1446C0.925174 28.7749 0.579512 29.6165 0.581496 30.5112C0.579512 31.4093 0.925173 32.2504 1.55451 32.8802Z"
                            fill="white" />
                    </svg>
                    <div class="liste-webradios-likees swiper-container">
                        <div class="zone-cases likes swiper-wrapper">
                            <?php while($ligne = $favoris->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
                            <div class="case-radio swiper-slide">
                                <a
                                    href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($ligne['idRadio']) ?>'>
                                    <img class="logo-radio" src="./images/<?php echo($ligne['photoRadio']); ?>" alt="photoradio">
                                </a>
                                <p class="nom-radio">
                                    <?php echo($ligne['nomRadio']); ?>
                                </p>
                                <p class="categorie">
                                    <?php echo($ligne['categorie']); ?>
                                </p>
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <svg class="fleche likes droite swiper-button-like-next" width="37" height="61" viewBox="0 0 37 61"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M35.4455 28.1198L8.29972 0.974504C7.67187 0.34616 6.83375 0 5.94008 0C5.04641 0 4.20829 0.34616 3.58044 0.974504L1.58134 2.97311C0.280513 4.27542 0.280513 6.39205 1.58134 7.69238L24.3763 30.4874L1.55605 53.3076C0.928199 53.936 0.581543 54.7736 0.581543 55.6668C0.581543 56.5609 0.928199 57.3986 1.55605 58.0274L3.55515 60.0255C4.18349 60.6538 5.02112 61 5.91479 61C6.80845 61 7.64658 60.6538 8.27442 60.0255L35.4455 32.8554C36.0748 32.2251 36.4205 31.3835 36.4185 30.4888C36.4205 29.5907 36.0748 28.7496 35.4455 28.1198Z"
                            fill="white" />
                    </svg>
                </div>
            </div>
            <div class="webradios-recemment-ecoutees">
                <div class="zone-titre">
                    <h2>Récemment écouté</h2>
                    <svg class="trait" width="218" height="3" viewBox="0 0 218 3" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <line y1="1.5" x2="218" y2="1.5" stroke="white" stroke-width="3" />
                    </svg>
                </div>
                <div class="swiper-rec">
                    <svg class="fleche ecoute gauche swiper-button-rec-prev" width="37" height="61" viewBox="0 0 37 61"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.55451 32.8802L28.7003 60.0255C29.3281 60.6538 30.1663 61 31.0599 61C31.9536 61 32.7917 60.6538 33.4196 60.0255L35.4187 58.0269C36.7195 56.7246 36.7195 54.6079 35.4187 53.3076L12.6237 30.5126L35.444 7.69238C36.0718 7.06404 36.4185 6.22641 36.4185 5.33324C36.4185 4.43907 36.0718 3.60145 35.444 2.97261L33.4449 0.974501C32.8165 0.346156 31.9789 -1.34179e-06 31.0852 -1.41992e-06C30.1916 -1.49805e-06 29.3534 0.346156 28.7256 0.974501L1.55451 28.1446C0.925174 28.7749 0.579512 29.6165 0.581496 30.5112C0.579512 31.4093 0.925173 32.2504 1.55451 32.8802Z"
                            fill="white" />
                    </svg>
                    <div class="liste-recemment-ecoute swiper-container">
                        <div class="zone-cases ecoute swiper-wrapper">
                            <?php while($ligne = $derniereEcoute->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
                            <div class="case-radio swiper-slide">
                                <a
                                    href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($ligne['idRadio']) ?>'>
                                    <img class="logo-radio" src="./images/<?php echo($ligne['photoRadio']); ?>">
                                </a>
                                <p class="nom-radio">
                                    <?php echo($ligne['nomRadio']); ?>
                                </p>
                                <p class="categorie">
                                    <?php echo($ligne['categorie']); ?>
                                </p>
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                    <svg class="fleche ecoute droite swiper-button-rec-next" width="37" height="61"
                            viewBox="0 0 37 61" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M35.4455 28.1198L8.29972 0.974504C7.67187 0.34616 6.83375 0 5.94008 0C5.04641 0 4.20829 0.34616 3.58044 0.974504L1.58134 2.97311C0.280513 4.27542 0.280513 6.39205 1.58134 7.69238L24.3763 30.4874L1.55605 53.3076C0.928199 53.936 0.581543 54.7736 0.581543 55.6668C0.581543 56.5609 0.928199 57.3986 1.55605 58.0274L3.55515 60.0255C4.18349 60.6538 5.02112 61 5.91479 61C6.80845 61 7.64658 60.6538 8.27442 60.0255L35.4455 32.8554C36.0748 32.2251 36.4205 31.3835 36.4185 30.4888C36.4205 29.5907 36.0748 28.7496 35.4455 28.1198Z"
                                fill="white" />
                        </svg>
                </div>
            </div>

            <div class="sound-number" style="display:none">
                <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479"
                    alt="logo-volume">
                <span>0</span>
            </div>
            </div>
            <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036"
                class="fond-montagne" alt="fond ecran"/>
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
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="script.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="swiper.js"></script>
<script src="player.js"></script>

</html>
<?php }else{
    header('Location: https://la-projets.univ-lemans.fr/~mmi1pj03/index.php');
    }?>

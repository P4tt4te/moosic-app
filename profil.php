<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");

$idUti = $_SESSION['idUtilisateur'];

$mood = $bdd->prepare('SELECT * FROM possede INNER JOIN Mood ON possede.idMood = Mood.idMood INNER JOIN Radio ON Mood.idRadio = Radio.idRadio WHERE possede.idUtilisateur = ? ');
$mood->execute(array($idUti));
$moodSelec = $mood->fetch(PDO::FETCH_ASSOC); // On sélectionne le mood choisi par l'utilsateur ainsi que ses caractéristiques

$acheteObjet = $bdd -> prepare('SELECT Objet.idObjet,Objet.nomObjet,Objet.prixObjet,Objet.photoObjet,Objet.idType,
CASE
	WHEN Achete.idObjet IS NULL THEN "false"
    ELSE "true"
    END AS achete
FROM Objet LEFT JOIN Achete ON Objet.idObjet = Achete.idObjet
WHERE Achete.idUtilisateur = ?
ORDER BY idType DESC');

$acheteObjet -> execute(array($idUti));


$achetePhoto = $bdd -> prepare('SELECT Objet.idObjet,Objet.nomObjet,Objet.prixObjet,Objet.photoObjet,Objet.idType,
CASE
	WHEN Achete.idObjet IS NULL THEN "false"
    ELSE "true"
    END AS achete
FROM Objet LEFT JOIN Achete ON Objet.idObjet = Achete.idObjet
WHERE Achete.idUtilisateur = ?
ORDER BY idType DESC');

$achetePhoto -> execute(array($idUti));

$requser = $bdd->prepare("SELECT * FROM Utilisateur WHERE idUtilisateur = ?");
$requser->execute(array($_SESSION['idUtilisateur']));
$user = $requser->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['forminscription'])) {
  $name = $_POST['name'];
  $pseudolength = mb_strlen($name); //On stock la longueur du pseudo
  if($pseudolength <= 10 AND $name != "GUEST") {
    if(isset($_POST['name']) AND !empty($_POST['name']) AND $_POST['name'] != $user['pseudo']) {
        $newpseudo = htmlspecialchars($_POST['name']);
        $insertpseudo = $bdd->prepare("UPDATE Utilisateur SET pseudo = ? WHERE idUtilisateur = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['idUtilisateur']));
        $_SESSION['pseudo'] = $newpseudo ;
    }
  } else{$erreur = "Votre pseudo ne doit pas dépasser 10 caractères !";}
    if(isset($_POST['mdp']) AND !empty($_POST['mdp']) AND isset($_POST['mdp2']) AND !empty($_POST['mdp2'])) {
        $mdp1 = sha1($_POST['mdp']);
        $mdp2 = sha1($_POST['mdp2']);
        if($mdp1 == $mdp2) {
          $mdplength= mb_strlen($_POST['mdp']);
          if($mdplength>=4){
            $insertmdp = $bdd->prepare("UPDATE Utilisateur SET mdpUti = ? WHERE idUtilisateur = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['idUtilisateur']));

        }else{$erreur = "Votre mot de passe ne doit pas dépasser 10 caractères !";}

      }else {
        $erreur = "Vos deux mots de passes ne correspondent pas !";
    }
  }
}
?>

<?php if(isset($idUti)){?>
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
	    <link rel="stylesheet" href="style-profil.css" type="text/css" media="screen" />
	    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	</head>

	<body>
  <?php if(isset($erreur)) {
?>
          <div class="error" style="color:red"><?php echo($erreur) ; ?></div>
<?php	} ?>
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
    <div class="profil-utilisateur">
      <div class="icone-utilisateur">
        <svg width="300" height="300" viewbox="0 0 67 78" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle class="cercle-mood-stroke" cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
          <image class='logo-utilisateur' width="40" height="40" x="13.5" y="23"
            xlink:href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmeditation.svg?v=1623682812929"
            alt="image logo profil" />
          <circle class="cercle-mood-fill" cx="34" cy="7" r="7" fill="#00FFA3" />
        </svg>
      </div>
      <div class="interface-utilisateur">
        <div class="description-utilisateur">
          <div class="bonjour">
            <h2>Bonjour</h2>
            <h1 class="profil-name">LaZen53</h1>
            <div class="edition-profil-container">
              <img
                src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fedition-profil.svg?v=1622469121712"
                alt="edition du profil" class="edition-profil" />
            </div>
          </div>
          <!--             php pseudo -->

          <div class="niveau-utilisateur">
            <p class="nbr-niveau">
              1
            </p>
            <div class="progression">
              <div class="pourcentage-progression"></div>
            </div>
            <p class="nbr-niveau-sup">
              2
            </p>
            <!--             php niveau -->
            <!--             php barre de progression -->
            <!--             php niveau suivant-->
          </div>
        </div>
      </div>
    </div>
    <div class="enveloppe">
      <section class="inventaire case">
        <h2>inventaire</h2>
        <div class="fond-lecteur">
          <div class="texte-chrono">
            <p class="texte-lecteur">Fonds Profil</p>
            <div class="chronometre">
              <!-- <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fchronometre-accueil.png?v=1622465682960"
                                  alt="chronometre" />
                                  <p>13h45min</p> -->
            </div>
          </div>

          <div class="fond">
            <svg class="fleche-fond fleche swiper-button-fondbib-prev" width="46" height="76" viewBox="0 0 46 76"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M1.88717 40.9655L35.7081 74.7859C36.4904 75.5687 37.5346 76 38.648 76C39.7614 76 40.8057 75.5687 41.5879 74.7859L44.0786 72.2958C45.6993 70.6732 45.6993 68.0361 44.0786 66.416L15.6783 38.0158L44.1101 9.58395C44.8923 8.8011 45.3242 7.75749 45.3242 6.64469C45.3242 5.53065 44.8923 4.48705 44.1101 3.70358L41.6194 1.21413C40.8365 0.431275 39.7929 4.83559e-07 38.6795 5.80898e-07C37.5661 6.78236e-07 36.5219 0.431275 35.7396 1.21414L1.88717 35.0654C1.10307 35.8507 0.672413 36.8992 0.674885 38.0139C0.672413 39.1329 1.10307 40.1808 1.88717 40.9655Z"
                fill="white" />
            </svg>
            <div class="imgs-fond swiper-container">
              <div class="swiper-wrapper">

                <?php while($ligne = $acheteObjet->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les bannieres
               if($ligne['idType'] == 2){ ?>
                <div class="div-fond swiper-slide">
                  <img class="image-fond img-fond" src="./imagesObjets/<?php echo($ligne['photoObjet']); ?> "
                    alt="icone" class="image-fond img-fond" data-id="<?php echo($ligne['idObjet']); ?>"
                    data-achete="<?php echo($ligne['achete']); ?>">
                  <div class="bloc-achat-fond">
                    <p class="cout">
                      Obtenu
                    </p>
                  </div>
                </div>
                <?php }
                }?>



                <!-- <div class="div-fond swiper-slide">
                  <img class="image-fond2 img-fond" src="https://la-projets.univ-lemans.fr/~mmi1pj03/test_edou/imagesObjets/7.webp" alt="Fond d'écran" />
                  <div class="bloc-achat-fond">
                    <p class="cout">
                      Obtenu le <span class="cout-chiffres"></span>
                    </p>
                  </div>
                </div> -->

              </div>
            </div>
            <svg class="fleche-fond fleche swiper-button-fondbib-next" width="46" height="76" viewBox="0 0 46 76"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M44.1128 35.0345L10.2919 1.21414C9.50963 0.431281 8.46541 0 7.35199 0C6.23857 0 5.19435 0.431281 4.41211 1.21414L1.92143 3.7042C0.300728 5.32675 0.300728 7.96387 1.92143 9.58395L30.3217 37.9842L1.88992 66.416C1.10768 67.1989 0.675781 68.2425 0.675781 69.3553C0.675781 70.4694 1.10768 71.513 1.88992 72.2964L4.3806 74.7859C5.16345 75.5687 6.20705 76 7.32048 76C8.4339 76 9.47812 75.5687 10.2604 74.7859L44.1128 40.9346C44.8969 40.1493 45.3276 39.1008 45.3251 37.9861C45.3276 36.8671 44.8969 35.8192 44.1128 35.0345Z"
                fill="white" />
            </svg>
          </div>
        </div>

        <div class="icone-profil">
          <p class="texte-profil bold">Icônes Profil</p>

          <div class="icone">
            <svg class="fleche-icone fleche swiper-button-icones-prev" width="46" height="76" viewBox="0 0 46 76"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M1.88717 40.9655L35.7081 74.7859C36.4904 75.5687 37.5346 76 38.648 76C39.7614 76 40.8057 75.5687 41.5879 74.7859L44.0786 72.2958C45.6993 70.6732 45.6993 68.0361 44.0786 66.416L15.6783 38.0158L44.1101 9.58395C44.8923 8.8011 45.3242 7.75749 45.3242 6.64469C45.3242 5.53065 44.8923 4.48705 44.1101 3.70358L41.6194 1.21413C40.8365 0.431275 39.7929 4.83559e-07 38.6795 5.80898e-07C37.5661 6.78236e-07 36.5219 0.431275 35.7396 1.21414L1.88717 35.0654C1.10307 35.8507 0.672413 36.8992 0.674885 38.0139C0.672413 39.1329 1.10307 40.1808 1.88717 40.9655Z"
                fill="white" />
            </svg>
            <div class="liste-icones swiper-container">
              <div class="swiper-wrapper">
                <?php while($ligne2 = $achetePhoto->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les photos
                if($ligne2['idType'] == 1){?>
                <div class="div-icone div-icone1 swiper-slide">
                  <img src="./imagesObjets/<?php echo($ligne2['photoObjet']); ?> " alt="icone" class="image-icone"
                    data-id="<?php echo($ligne2['idObjet']); ?>" data-achete="<?php echo($ligne2['achete']); ?>">
                  <div class="bloc-achat-icone">
                    <p class="cout">
                      Obtenu
                    </p>
                  </div>
                </div>

                <?php }
              }?>
              </div>
            </div>
            <svg class="fleche-icone fleche swiper-button-icones-next" width="46" height="76" viewBox="0 0 46 76"
              fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M44.1128 35.0345L10.2919 1.21414C9.50963 0.431281 8.46541 0 7.35199 0C6.23857 0 5.19435 0.431281 4.41211 1.21414L1.92143 3.7042C0.300728 5.32675 0.300728 7.96387 1.92143 9.58395L30.3217 37.9842L1.88992 66.416C1.10768 67.1989 0.675781 68.2425 0.675781 69.3553C0.675781 70.4694 1.10768 71.513 1.88992 72.2964L4.3806 74.7859C5.16345 75.5687 6.20705 76 7.32048 76C8.4339 76 9.47812 75.5687 10.2604 74.7859L44.1128 40.9346C44.8969 40.1493 45.3276 39.1008 45.3251 37.9861C45.3276 36.8671 44.8969 35.8192 44.1128 35.0345Z"
                fill="white" />
            </svg>
          </div>
        </div>
      </section>

      <div class="petite-enveloppe">
        <div class="forme-humeur case">
          <div class="humeur-du-jour">
            <h3>
              Humeur du jour
            </h3>
          </div>
          <div class="container-forme" style="background-color:<?php echo($moodSelec['couleur']);?>">
            <span><?php echo($moodSelec['humeur']);?></span>
          </div>
          <!--             php forme colorée -->

          <!--             php txt1 -->
        </div>
        <div class="webradio-du-jour case">
          <h3>
            Votre webradio du jour
          </h3>
          <div class="case-webradio-du-jour">
            <a
              href="https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php echo($moodSelec['idRadio']);?>">
              <img src="./images/<?php echo($moodSelec['photoRadio']); ?>" />
            </a>
            <!--             php forme colorée -->
            <span><?php echo($moodSelec['nomRadio']); ?></span>
            <span><?php echo($moodSelec['categorie']); ?></span>
            <!--             php nom webradio -->
            <!--             php catégorie -->
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
		<div id="divOverlay" class="overlay-invisible overlay-visible" style="display:none">
			<div class="form-container">
	      <form method="post" action="" class="form-inscription">
	        <div class="form-pseudo">
	          <p for="pseudo">Entrez votre nouveau pseudo :</p>
	          <label></label>
	          <input type="text" name="name" id="name" value="<?php echo $user['pseudo']; ?>" />
	        </div>
	        <div class="form-mdp">
	          <p>Entrez votre nouveau mot de passe :</p>
	          <label></label>
	          <input type="password" name="mdp" id="mdp" />
	        </div>
	        <div class="cell">
	          <p>Confirmez votre nouveau mot de passe :</p>
	          <label></label>
	          <input type="password" name="mdp2" class="mdp2" />
	        </div>

	        <div class="form-submit">
	          <input type="submit" name="forminscription" class="valider" value="Valider" />
	        </div>
	      </form>
	    </div>
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
<script src="player.js"></script>
<script src="script.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="swiper.js"></script>
<script class="dernier" src="script-profil.js"></script>

</html>

<?php }else{
  header('Location: https://la-projets.univ-lemans.fr/~mmi1pj03/index.php');
} ?>

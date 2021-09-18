<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
setlocale (LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$getid = htmlspecialchars($_GET['id']);
$radio = $bdd->prepare('SELECT * FROM Radio WHERE idRadio = ?');
$radio->execute(array($getid));
$radio = $radio->fetch(PDO::FETCH_ASSOC); // On sélectionne la radio par rapport à l'id dans l'url
$select = $bdd->prepare('SELECT COUNT(idRadio) as nbFav FROM favoris WHERE idRadio = ?');
$select->execute(array($getid));
$nbFav = $select->fetch(PDO::FETCH_ASSOC); // On selectionne le nombre de favoris de la radio en question
$dateActuelle = date("y/m/d");// On stock la date actuelle


if(isset($_GET['id']) AND isset($_SESSION['idUtilisateur'])) {
    $idUti = $_SESSION['idUtilisateur'];
    if(isset($_POST['like'])){
        $like = $_POST['like'];
        $select = $bdd->prepare('SELECT *  FROM favoris WHERE idRadio = ? AND idUtilisateur = ?');
        $select->execute(array($getid,$idUti)); // On sélectionne les radios like par l'utilisateur
        $dejaLike = $select->fetch(PDO::FETCH_ASSOC);
        if($dejaLike != true){
            $ins = $bdd->prepare('INSERT INTO favoris (idUtilisateur, idRadio) VALUES (?,?)');
            $ins->execute(array($idUti,$getid)); // Si il n'y à pas de correspondance on insert dans la bdd
        }else{
            $delete = $bdd->prepare('DELETE FROM favoris WHERE idRadio = ? AND idUtilisateur = ? ');
            $delete->execute(array($getid,$idUti));// Si il y a correspondance on supprime de la bdd
        }
         header("Refresh: 0;url=https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=".$_GET['id']);

    }
    if(isset($_POST['poster_commentaire'])) {
        if(isset($_POST['commentaire']) AND !empty($_POST['commentaire'])) {
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $idUti = $_SESSION['idUtilisateur'];
            $commentaire = htmlspecialchars($_POST['commentaire']);
            $ins = $bdd->prepare('INSERT INTO Commentaire (contenu, idRadio, Commentaire.date, idUtilisateur) VALUES (?,?,?,?)');
            $ins->execute(array($commentaire, $getid,$dateActuelle, $idUti)); // On insert dans la bdd les valeurs du commentaire
            $erreur = "Votre commentaire a bien été posté";
            header("Refresh: 0;url=https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=".$_GET['id']);
        }else{
            $erreur = 'Veuillez vous connectez pour poster un commentaire';
            header('Location : https://la-projets.univ-lemans.fr/~mmi1pj03/landing-connexion.php');
        }
    }else{
        $erreur = "Veuillez rentrer un commentaire pour pouvoir le poster";
    }
    if(isset($_POST['signalement'])) {
        $idCom = $_POST['idCom'];
        $select = $bdd->prepare('SELECT * FROM Commentaire WHERE idCommentaire = ?');
        $select -> execute(array($idCom));// On selectionne tout les commentaire qui correspond à l'id du commentaire en question
        $nbSignalement = $select->fetch(PDO::FETCH_ASSOC);
        if($nbSignalement!=false){
            $update = $bdd -> prepare('UPDATE Commentaire SET nbSignalement = nbSignalement + 1 WHERE idCommentaire = ?');
            $update -> execute(array($idCom )); // On incrémente le compteur de signalement de + 1
        }
        if($nbSignalement['nbSignalement'] == '5'){ // Si le nombre de signalement est égale à 5 on envoie un mail à la modération (ici pour le test je l'envoie sur ma propre adresse mail)
            $mail = 'nathan.harnay29@gmail.com';
            $header="MIME-Version: 1.0\r\n"; // On créer le header du mail
            $header.='From:"[MOOSIC]"<support@moosic.com>'."\n";
            $header.='Content-Type:text/html; charset="utf-8"'."\n";
            $header.='Content-Transfer-Encoding: 8bit';
            $message = '
                        <html>
                        <head>
                            <title> Récupération de mot de passe - MOOSIC </title>
                             <meta charset="utf-8" />
                        </head>
                        <body>
                        <div>
                            Bonjour,
                            Le commentaire d\'id '.$idCom.' a été signalé plus de 5 fois
                            Voici le lien vers la page d\'administration des commentaire: https://la-projets.univ-lemans.fr/~mmi1pj03/admin-commentaire.php
                            A bientôt sur MOOSIC !
                        </div>
                        </html>';
            mail($mail, "Signalement de commentaire - MOOSIC", $message, $header); // On envoie le mail avec les infos donné
        }
    }
    $commentaires = $bdd->prepare('SELECT Utilisateur.photoProfil,Commentaire.idCommentaire, Commentaire.contenu, Utilisateur.pseudo, Commentaire.date FROM Commentaire INNER JOIN Utilisateur ON Commentaire.idUtilisateur = Utilisateur.idUtilisateur WHERE idRadio = ? ORDER BY Commentaire.idCommentaire DESC');
    $commentaires->execute(array($getid)); // On sélectionne tout les commentaire pour une radio précises, on les mets du plus récent au plus vieux
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
    <link rel="stylesheet" href="style-profil-webradio.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
    <header>
        <div class="player player-off case">
            <img alt="image webradio"
                src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210"
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
                            <circle class="cercle-mood-stroke" cx="33.5" cy="44.5" r="32" stroke="#00FFA3"
                                stroke-width="3" />
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
        <div class="profil-radio">
            <div class="presentation-radio">

                <div class="infos-radio">
                    <div class="image-webradio">
                        <div class="img-container">


                            <img class="logo-profil-radio" src="./images/<?php echo($radio['photoRadio']); ?>" />

                        </div>
                    </div>
                    <div class="nom-webradio">
                        <h1><?php echo($radio['nomRadio']);?></h1>
                        <!--                php nom webradio-->

                        <h2><?php echo($radio['slogan']);?></h2>
                        <div class="likes">
                            <span> <?php echo($nbFav['nbFav']);?> Likes</span>

                            <!-- <p><span>358 </span><i class="fa fa-heart"></i></p> -->
                            <!--                php likes-->
                        </div>
                    </div>
                </div>
                <div class="interagir-webradio">
                    <input type="button" value="Ecouter la radio" class="bouton-ecoute-radio"
                        data-idradio="<?php echo($_GET['id']); ?>" />
                    <div class="icones">
                        <button class="boutonshare"><i class="fa fa-share-alt"></i></button>
                        <form method="post" name="form">
                            <button name="like" class="boutonlike" data-like="<?php
                          $select = $bdd->prepare('SELECT *  FROM favoris WHERE idRadio = ? AND idUtilisateur = ?'); // Si l'utilisateur a like
                          $select->execute(array($getid,$idUti));
                          $like = $select -> fetch(PDO::FETCH_ASSOC);

                          if($like == true){
                            $like = "true";//On envoie true
                            echo($like);
                          }else{
                            $like = "false";// On envoie false
                            echo($like);
                          } ?>"><i class="fa fa-heart"></i></button>
                            <input type="hidden" name="like" />
                        </form>
                    </div>
                </div>
            </div>

            <div class="desc-webradio case">
                <h2>Description</h2>
                <p>
                    <!--                php description-->
                    <?php echo($radio['description']);?>
                </p>
            </div>
            <div class="overlayClosed">


                <form method="POST">
                    <i class="fa fa-comment fa-2x"></i>
                    <p>Ajoutez votre commentaire</p>
                    <textarea name="commentaire"></textarea><br />
                    <input type="submit" value="Poster mon commentaire" name="poster_commentaire" />
                </form>
                <?php
                if(isset($erreur)) {
                ?>
                <div class="error"><?php echo($erreur) ; ?></div>
                <?php
                    }
                    ?>
            </div>
            <div class="div-milieu">
                <div class="commentaires">
                    <h3>Commentaires :</h3>
                    <div class="div-addcomm">
                        <i class="fa fa-comment fa-lg"></i>
                        <p>Ajouter un commentaire public...</p>
                    </div>

                    <?php while($ligne = $commentaires->fetch(PDO::FETCH_ASSOC)) { ?>
                    <form method="POST" class="formulaireCommentaire">
                        <div class="unCommentaire case">
                            <!--                    php div com-->
                            <!--                        php photo profil-->
                            <!--                        php pseudo-->
                            <!--                        php texte-->
                            <!--                        php date et heure-->
                            <!--                    fin php div com-->

                            <!--                    php div com2-->
                            <!--                    ...-->
                            <!--                    ...-->



                            <div class="utilisateur">
                                <div class="profil-name">
                                    <svg width="67" height="78" viewbox="0 0 67 78" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
                                        <image class='logo-profil' width="40" height="40" x="13.5" y="23"
                                            xlink:href="./imagesObjets/<?php echo($ligne['photoProfil']); ?>" />
                                        <circle cx="34" cy="7" r="7" fill="#00FFA3" />
                                    </svg>

                                    <span class="profil-name"><?php echo($ligne['pseudo']); ?></span>
                                </div>
                            </div>
                            <div class="texte-commentaire">
                                <p> <?php echo($ligne['contenu']); ?></p>
                            </div>
                            <div class="div-date-texte">
                                <div class="date-commentaire">
                                    <div class="report">
                                        <span class="tooltiptext">Cliquez pour signaler ce commentaire</span>
                                        <button name="signalement" class="btn-report"><i
                                                class="fa fa-exclamation-triangle"></i></button>
                                        <input type="hidden"
                                            value="<?php if(isset($ligne['idCommentaire'])) { echo $ligne['idCommentaire']; } ?>"
                                            name="idCom"> <!--  On mets l'id dans un champ caché pour pouvoir le retrouver ensuite et le signaler  -->
                                    </div>
                                    <p><?php echo($ligne['date']); ?></p>
                                </div>

                            </div>
                    </form>


                </div>

                <?php
                            }
                        ?>
            </div>
            <div class="webradios-preferees case">
                <h3>Vos meilleures webradios</h3>
                <div class="top-webradios">
                    <!--                    php radio top 1-->
                    <img
                        src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-smooth-chill%201.svg?v=1622469135817" />
                    <!--                    php radio top 2-->
                    <img
                        src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-radio-nature%201.svg?v=1622469135909" />
                    <!--                    php radio top 3-->
                    <img
                        src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-koffee%201.svg?v=1622469135816" />
                </div>
            </div>
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
    <script type="text/javascript" src="script.js">
    </script>
    <script type="text/javascript" src="player.js">
    </script>
    <script type="text/javascript" src="script-profil-radio.js"> </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js">

    </script>


</body>

</html>


<?php }else{
    header("Location: index.php");
}?>

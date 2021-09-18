<?php


ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'"); ?>


<?php
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$select = $bdd -> prepare('SELECT est_un.idRole,est_un.idUtilisateur FROM est_un INNER JOIN Role ON est_un.idRole = Role.idRole INNER JOIN Utilisateur ON est_un.idUtilisateur = Utilisateur.idUtilisateur WHERE est_un.idUtilisateur = ? AND ( Role.nomRole = "Admin" OR Role.nomRole = "Moderateur") ');
$select -> execute(array($_SESSION['idUtilisateur']));
$role = $select -> fetch(PDO::FETCH_ASSOC); // On recherche tout les id d'utilisateur qui ont comme rôles admin ou moderateur

    if(isset($_SESSION['idUtilisateur']) AND $role!=false){

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
    <link rel="stylesheet" href="style-admin-commentaire.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>

<body>
    <header>
        <div class="player player-off case">
            <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210"
                id="logo-hover" alt="agrandir">
            <img src="" id="logo-player" alt="photo webradio">
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
                        <image alt="icone profil" class='logo-profil' width="40" height="40" x="13.5" y="23"
                            xlink:href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmeditation.svg?v=1623682812929"
                             />
                        <circle class="cercle-mood-fill" cx="34" cy="7" r="7" fill="#00FFA3" />
                    </svg>
									</a>
                    <span class="profil-name">LaZen53</span>
                </div>
                <img alt="reglage" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733"
                    class="profil-options" />
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
    <div class="admincomm-container case">


<form action="" method="get" enctype="multipart/form-data">
        <div>
            <p>Id du commentaire</p>
            <label for="idCom"></label>
            <input type="number" name="idCom" required />
        </div>
        <div>
            <input class="inputAddCom" type="submit" name="formadmin" value="Valider" />
        </div>
</form>
<?php


if(isset($_GET['formadmin'])){ // On affiche le deuxième formulaire quand le premier est envoyé
    $idCom = $_GET['idCom'];
    if(!empty($_GET['idCom'])){
        $contenu = $bdd->prepare('SELECT * FROM Commentaire WHERE idCommentaire = ?');
        $contenu -> execute (array($idCom));
        $ligne = $contenu ->fetch(PDO::FETCH_ASSOC);

        ?>
            <p>Commentaire en question : </p>
            <?php echo($ligne['contenu']);?>
            <form action ="" method = "post">
                <div>
                    <p>Voulez vous le supprimer ?</p>
                    <label for="supprimerCom"></label>
                    <select name="supprimerCom">
                        <option value="1"> OUI </option>
                        <option value="2"> NON </option>
                    </select>
                </div>
                <div>
                    <p>Voulez vous réinitialiser le nombre de signalement ?</p>
                    <label for="signalerCom"></label>
                    <select name="signalerCom">
                        <option value="1"> OUI </option>
                        <option value="2"> NON </option>
                    </select>
                </div>
                <div class="form-submit">
                    <input type="submit" name="formcom" value="Valider" />
                    <input type="hidden" name="idCom"/>
                </div>
            </form>
    <?php
    }else{
        $erreur = ('Champ imcomplet');
    }?>
<?php
}?>
<?php
if(isset($_POST['formcom'])){ // On traite le 2è formulaire
    $idCom = $_GET['idCom'];
    if(isset($_GET['idCom'])){
        $suprCom = $_POST['supprimerCom'];
        $resetSignalement = $_POST['signalerCom'];
        if($suprCom == '1'){
            $suppr = $bdd->prepare('DELETE FROM Commentaire WHERE idCommentaire = ? ');
            $suppr -> execute (array($idCom));
            $ligne = $contenu ->fetch(PDO::FETCH_ASSOC);
            $erreur = ('Commentaire Supprimer');

        }
        if($suprCom == '2'){
            $erreur = ('Commentaire non supprimer');
        }
        if($resetSignalement == '1'){
            $update = $bdd->prepare('UPDATE Commentaire SET nbSignalement = 0 WHERE idCommentaire = ? ');
            $update -> execute (array($idCom));
            $ligne = $contenu ->fetch(PDO::FETCH_ASSOC);
            $erreur = ('Signalement remis à zéro');
        }
        if($resetSignalement == '2'){
            $erreur = ('Signalement non remis à zéro');
        }
    }else{
        $erreur = ("erreur");
    }


}
if(isset($erreur)) {
    ?>
<div class="error"><?php echo($erreur) ; ?></div>
<?php }else {?> Pas d'erreur <?php }?>
</div>
<div class="sound-number" style="display:none">
  <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479" alt="logo-volume">
  <span>0</span>
</div>
<img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036" class="fond-montagne" alt="fond ecran">
<div class="container-player">
  <div class="dropzone-top">
  </div>
  <div class="dropzone-bottom">
  </div>
</div>
<div class="loader">
  <img class='logo-loader' src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-moosic-chargement.svg?v=1623660668507" alt="logo-moosic-chargement">
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


</html>

<?php
}else{
    header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/accueil.php");
}
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
require (__DIR__ . "/param.inc.php");
session_start();
if(isset($_POST['formadmin'])) {

    // Etape 1 : connexion au serveur de base de données
    $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
    $bdd->query("SET NAMES utf8");
    $bdd->query("SET CHARACTER SET 'utf8'");
    $nomObjet = strtolower(htmlspecialchars($_POST['nomObjet'])); // On comble les espaces, à voir après si on peux faire l'inverse pour que cela s'affiche sur la page html
    $prixObjet = strtolower(htmlspecialchars($_POST['prixObjet']));
    $disponibilite = htmlspecialchars($_POST['disponibilite']);
    $niveauObjet = $_POST['nvObj'];
    $typeObjet = $_POST['typeObjet'];
    $photoObjet = $_FILES['photoObjet']['name']; // on stock le nom de la objet
    $extensionsValides = array('webp', 'jpg','png', 'webp','svg'); // on stock les extensions valides
    $extension= strrchr($_FILES['photoObjet']['name'], '.'); // on regarde quelle est l'extension du fichier ajouté
    $extensionUpload = strtolower(substr(strrchr($_FILES['photoObjet']['name'], '.'), 1)); // on prends l'extention du fichier

    if(in_array($extensionUpload,$extensionsValides)){ // On vérifie que l'extention de l'image uploader soit conforme aux extensions valides

    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $req = $bdd->prepare("SELECT * FROM Objet WHERE nomObjet = ?");
    $req->execute(array($nomObjet)); // On sélectionne la ligne qui correspond au nom de la objet qui a été envoyé par l'admin
    $ligne = $req->fetch(PDO::FETCH_ASSOC) ; // On stock dans un tableau

        if($ligne == false) {	// Si il trouve pas de correspondance dans la bdd

            if(!empty($_POST['nomObjet']) AND !empty($_POST['prixObjet']) AND !empty($_POST['disponibilite']) AND !empty($_POST['nvObj']) AND !empty($_POST['typeObjet'])) {

                // Etape 2 : envoi de la requête SQL au serveur
                $nomFichier = $_FILES['photoObjet']['name']; // On stock le nom du fichier envoyer
                $insert = $bdd->prepare("INSERT INTO Objet(Objet.nomObjet, Objet.prixObjet, Objet.disponibilite, Objet.niveauObj, Objet.photoObjet, Objet.idType) VALUES (?, ?, ?, ?, ?, ?)");
                $insert->execute (array($nomObjet, $prixObjet, $disponibilite, $niveauObjet, $photoObjet, $typeObjet )); // On rentre les infos dans la bdd
                $req = $bdd->prepare("SELECT idObjet FROM Objet WHERE nomObjet = ?"); // selection de l'id
                $req->execute(array($nomObjet)); // on stock l'id dans un tableau
                $idObjet = $req->fetch(PDO::FETCH_ASSOC) ; // on parcours le tableau
                $dossier = "imagesObjets/".$idObjet['idObjet'].".".$extensionUpload; // on indique le chemin du dossier

                if(move_uploaded_file($_FILES['photoObjet']['tmp_name'], $dossier)){// on déplace le ficher dans le dossier image

                    $updateImg = $bdd->prepare('UPDATE Objet SET photoObjet = :photoObjet WHERE idObjet = :idObjet');
                    $updateImg->execute(array('photoObjet' => $idObjet["idObjet"].".".$extensionUpload, 'idObjet' =>$idObjet['idObjet']));// change le nom de l'img par rapport à l'id
                }else{
                        $erreur = "Fichiers non téléversé";
                }
            }else{
                    $erreur = "Tout les champs ne sont pas remplie";
            }
        }else{
            $erreur = "L'objet existe déjà !";
        }
    }else{
        $erreur = "Les extensions ne sont pas bonne !";
    }
}
?>
<?php
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$select = $bdd -> prepare('SELECT est_un.idRole,est_un.idUtilisateur FROM est_un INNER JOIN Role ON est_un.idRole = Role.idRole INNER JOIN Utilisateur ON est_un.idUtilisateur = Utilisateur.idUtilisateur WHERE est_un.idUtilisateur = ? AND ( Role.nomRole = "Admin" OR Role.nomRole = "Moderateur") ');
$select -> execute(array($_SESSION['idUtilisateur']));
$role = $select -> fetch(PDO::FETCH_ASSOC);

    if(isset($_SESSION['idUtilisateur']) AND $role!=false){

    ?>

<!DOCTYPE html>
<html>

<head>
  <title>AJOUT OBJET</title>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
  <link rel="icon" href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon.ico?v=1622468860944">
  <link rel="stylesheet" href="style-ajout-objet">
</head>

<body>


  <header>
    <div class="player player-off case">
      <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210" id="logo-hover">
      <img src="" id="logo-player" alt="agrandir">
    </div>
    <nav>
      <svg id="menu-burger" width="45" height="45" viewBox="0 0 384 384" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="menu1">
          <path id="BARRE-BAS" d="M368 289H16C7.16797 289 0 277.352 0 263C0 248.648 7.16797 237 16 237H368C376.832 237 384 248.648 384 263C384 277.352 376.832 289 368 289Z" fill="#34314F" />
          <path id="BARRE-MILLIEU" d="M368 197H16C7.16797 197 0 185.352 0 171C0 156.648 7.16797 145 16 145H368C376.832 145 384 156.648 384 171C384 185.352 376.832 197 368 197Z" fill="#34314F" />
          <path id="BARRE-HAUT" d="M368 105H16C7.16797 105 0 93.3521 0 79C0 64.6479 7.16797 53 16 53H368C376.832 53 384 64.6479 384 79C384 93.3521 376.832 105 368 105Z" fill="#34314F" />
        </g>
      </svg>
      <ul>
        <li>
          <a href="https://moosic-app.glitch.me/accueil.html">Accueil</a>
        </li>
        <li>
          <a href="https://moosic-app.glitch.me/rechercher.html">Rechercher</a>
        </li>
        <li>
          <a href="https://moosic-app.glitch.me/decouvrir.html">Découvrir</a>
        </li>
        <li>
          <a href="https://moosic-app.glitch.me/mon-mood.html">Mon mood</a>
        </li>
        <li>
          <a href="https://moosic-app.glitch.me/bibliotheque.html">Bibliothèque</a>
        </li>

      </ul>
      <div class="profil">
        <div class="profil-name">
          <svg width="67" height="78" viewbox="0 0 67 78" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
            <image class='logo-profil' width="40" height="40" x="13.5" y="23"  xlink:href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmeditation.svg?v=1623682812929" alt="image logo profil" />
            <circle cx="34" cy="7" r="7" fill="#00FFA3" />
          </svg>
          <span class="profil-name">LaZen53</span>
        </div>
        <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733" class="profil-options" alt="engrenage" />
      </div>
    </nav>
    <div class="menu-options case none">
      <div class="title-options">
        <img class='engrenage-option' src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733" alt="engrenage option">
        <p class="word-title-options">Options</p>
      </div>
      <div class="container-liste-options">
        <div class="edit-profil edit-item">
          <img class="profil-edit-img" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fedition-profil.svg?v=1622469121712" alt="logo edition">
          <p>Editer le profil</p>
        </div>
        <div class="creer-compte edit-item">
          <img class="creer-compte-img" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fuser.webp?v=1623845443620" alt="logo agrandir ou rétrécir">
          <p>Inscription / Connexion</p>
        </div>
      </div>
    </div>
  </header>
  <main>
    <div class="ajoutobjet-container case">
        <h2>Ajouter un Objet</h2>
        <br /><br />
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <p>Nom de l'objet</p>
                <label for="nomObjet"></label>
                <input type="text" name="nomObjet" id="nomObjet"
                    value="<?php if(isset($nomObjet)) { echo $nomObjet; } ?>" required />
            </div>
            <div>
                <p>Prix objet</p>
                <label for="prixObjet"></label>
                <input type="text" name="prixObjet" id="prixObjet"
                    value="<?php if(isset($prixObjet)) { echo $prixObjet; } ?>" required />
            </div>
            <div>
                <label for="disponibilite">Disponibilité de l'objet</label>
                <select name="disponibilite">
                    <option value="1"> OUI </option>
                    <option value="2"> NON </option>
                </select>
            </div>
            <div>
                <p>Photo de l'objet</p>
                <label for="photoObjet"></label>
                <input type="file" name="photoObjet" id="photoObjet" value="selectionnez un fichier" required />
            </div>
            <div>
                <p>Niveau de l'objet</p>
                <label for="nvObj"></label>
                <input type="text" name="nvObj" id="nvObj" value="<?php if(isset($nvObj)) { echo $nvObj; } ?>"
                    required />
            </div>
            <label for="pet-select"> Choisir un type d'objet </label>

                <select name="typeObjet">
                    <option value="1"> photo profil </option>
                    <option value="2"> banniere </option>
                </select>
            <div>

            </div>
            <div class="form-submit">
                <input type="submit" name="formadmin" value="Valider" />
            </div>
        </form>
        <?php
if(isset($erreur)) {
            ?>
        <div class="error"><?php echo($erreur) ; ?></div>
        <?php
                }
                else {
            ?>
        Votre compte a bien été créé !
        <?php
}

    ?>
    </div>
    <div class="sound-number" style="display:none">
      <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479" alt="logo-volume">
      <span>0</span>
    </div>
    <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036" class="fond-montagne" alt="fond">
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
      <a href="">Qui-sommes-nous ?</a>
      <a href="">FAQ</a>
      <a href="">Conditions d'utilisations</a>
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
    header("Location: https://la-projets.univ-lemans.fr/~mmi1pj03/index.php");
}
?>

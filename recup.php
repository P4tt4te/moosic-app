 <?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    session_start();
    header("Content-Type: text/html; charset=utf-8");
    require (__DIR__ . "/param.inc.php");
    if(isset($_POST['mdpoublie'])){
        if(!empty($_POST['mail'])){
            $recupmail = htmlspecialchars($_POST['mail']);
            if(filter_var($recupmail, FILTER_VALIDATE_EMAIL)){
                // Etape 1 : connexion au serveur de base de données
                $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
                $bdd->query("SET NAMES utf8");
                $bdd->query("SET CHARACTER SET 'utf8'");
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Etape 2 : envoi de la requête SQL au serveur
                $mailexiste = $bdd->prepare('SELECT * FROM Utilisateur WHERE mail = ?');
                $mailexiste -> execute(array($recupmail));
                $ligne = $mailexiste->fetch(PDO::FETCH_ASSOC);
                if($ligne != false){
                    $pseudo = $ligne['pseudo'];  // on cherche le pseudo pour pouvoir l'afficher dans le mail
                    $_SESSION['mail'] = $recupmail; // on stock le mail dans une variable de session pour update la bdd dans la page d'après
                    $header="MIME-Version: 1.0\r\n";
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
                                    Bonjour '.$pseudo.',
                                    Voici le lien vers la page de récupération de votre mot de passe: https://la-projets.univ-lemans.fr/~mmi1pj03/mdp-oublie.php
                                    A bientôt sur MOOSIC !
                                </div>
                                </html>';
                    mail($recupmail, "Récupération de mot de passe - MOOSIC", $message, $header);
                    echo("Mail envoyé !");
                }else{
                    $erreur="Vous n'avez pas de compte sur notre site";
                }



             }else{
                $erreur =  "email non valide";
             }


        }else{
            $erreur = "Veuillez remplir le champ";
        }

    }







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
  <link rel="stylesheet" href="style-recup.css">
  <link rel="icon" href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon.ico?v=1622468860944">
</head>

<body>
  <header>
    <div class="player player-off case">
      <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210" id="logo-hover">
      <img src="" id="logo-player">
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
        <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733" class="profil-options" />
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
        <form action="" method="post" class="form-container case">
            <div class="form-mail">
                <p>Entrez votre adresse Mail :</p>
                <label for="mail"></label>
                <input type="email" name="mail" id="mail" required class="input-mail" />
            </div>
            <div class="form-submit">
                <input type="submit" value="Valider" name="mdpoublie" />
            </div>
            <?php
	if(isset($erreur)) {
?>
        <div class="error"><?php echo($erreur) ; ?></div>
        <?php
	}
    ?>
        </form>
        <div class="sound-number" style="display:none">
              <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479" alt="logo-volume">
              <span>0</span>
            </div>
            <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036" class="fond-montagne">
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

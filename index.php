<?php
// inscription.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: text/html; charset=utf-8") ;
session_start();
require (__DIR__ . "/param.inc.php");
if(isset($_POST['moosicSansCompte'])){
  header("Location: https:la-projets.univ-lemans.fr/~mmi1pj03/accueil.php?video=1");
}
if(isset($_POST['forminscription'])) { // Si le formulaire à été complété on éxécute le if()
  $pseudo = htmlspecialchars($_POST['name']); // On convertit les caractères spéciaux en entités HTML car certains caractères ont des significations spéciales en HTML
  $mail = htmlspecialchars($_POST['mail']);
  $mail2 = htmlspecialchars($_POST['mail2']);
  $mdp = sha1($_POST['mdp']); // On crypte le mdp
  $mdp2 = sha1($_POST['mdp2']);
  $sexe = htmlspecialchars($_POST['gender']);

  if(!empty($_POST['name']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']) AND !empty($_POST['gender'])) {
    $pseudolength = mb_strlen($pseudo); //On stock la longueur du pseudo
    if($pseudolength <= 10) {
      $mdplength= mb_strlen($_POST['mdp']);//On stock la longueur du mot de passe
      if($mdplength>=4){
        if($mail == $mail2) {
          if(filter_var($mail, FILTER_VALIDATE_EMAIL)) { // On vérifie si le mot de passe est conforme : exemple@exemple.com

            // Etape 1 : connexion au serveur de base de données
            $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
            $bdd->query("SET NAMES utf8");
            $bdd->query("SET CHARACTER SET 'utf8'");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Etape 2 : envoi de la requête SQL au serveur
            $reqmail = $bdd->prepare("SELECT * FROM Utilisateur WHERE mail = ?");
            $reqmail->execute(array($mail)); //On sélectionne tout les champs qui sont égale au mail écrit par l'utilisateur
            $ligne = $reqmail->fetch(PDO::FETCH_ASSOC) ; // On stock les valeurs dans un tableau
            if($ligne == false) {// Si il ne trouve pas ce mail dans la bdd on éxécute le if()
              if($mdp == $mdp2) {// Si le mot de passe est égale à la confirmation du mot de passe
                // Etape 2 : envoi de la requête SQL au serveur
                $insertuti = $bdd->prepare("INSERT INTO Utilisateur(pseudo, mail, mdpUti, sexe,photoProfil,banniere) VALUES(?, ?, ?, ?,'1.svg','2.webp')");
                  $insertuti->execute(array($pseudo, $mail, $mdp, $sexe)); // On stock le nouvelle utilisateur dans la bdd



                $requser = $bdd->prepare("SELECT * FROM Utilisateur WHERE mail = ?");
                $requser -> execute(array($mail));
                $ligne2 = $requser ->fetch(PDO::FETCH_ASSOC); // On sélectionne cet utilisateur créer et on stock dans un tableau
                $update = $bdd->prepare("INSERT INTO est_un(idUtilisateur, idRole) VALUES (?,'3')");
                $update->execute (array($ligne2['idUtilisateur']));
                $insertInv = $bdd->prepare("INSERT INTO Achete(idUtilisateur,idObjet) VALUES(?, 1),(?, 2)");
                $insertInv->execute(array($ligne2['idUtilisateur'],$ligne2['idUtilisateur'])); // On stock le nouvelle utilisateur dans la bdd
                $insertMood = $bdd->prepare("INSERT INTO possede(idUtilisateur,idMood) VALUES(?, 1)");
                $insertMood->execute(array($ligne2['idUtilisateur'])); // On stock le nouvelle utilisateur dans la bdd

                if($ligne2 !=false){// Si il trouve on éxécute le if()
                  $_SESSION['idUtilisateur'] = $ligne2['idUtilisateur']; // Ici on stock les valeurs dans des variables de session
                  $_SESSION['pseudo'] = $ligne2['pseudo'];
                  $_SESSION['mail'] = $ligne2['mail'];
                  $_SESSION['sexe'] = $ligne2['sexe'];
                  $_SESSION['mdpUti'] = $ligne2['mdpUti'];
                  header("Location: accueil.php?video=1");
                }else{
                  $erreur = "Erreur lors de la création du compte";
                }

              }else{
                  $erreur = "Vos mot de passe ne correspondent pas !";
              }

            }else{
                $erreur = "Adresse mail déjà utilisé !";
            }
            // Etape 4 : ferme la connexion au serveur de base de données
            $pdo = null;

          }else{
              $erreur = "Votre adresse mail n'est pas valide !";
          }
        }else{
            $erreur = "Vos adresse mail de correspondent pas !";
        }
      }else{
          $erreur = "Votre mot de passe doit contenir plus de 4 caractères !";
      }
    }else{
        $erreur = "Votre pseudo ne doit pas dépasser 10 caractères !";
    }
  }else{
      $erreur = "Tout les champs doivent être complétés !";
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Moosic</title>
    <meta charset="UTF-8" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="style-landing.css"
      type="text/css"
      media="screen"
    />
  </head>

  <body>
    <main>
      <section class="partie-gauche">
        <svg
          width="700"
          height="300"
          viewBox="0 0 700 300"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
          class="logoanime"
        >
          <g id="logo1 copie 2">
            <g id="moosic">
              <path
                id="m"
                d="M178.734 157.629V270.524H156.157V197.107L125.996 270.524H108.9L78.5769 197.107V270.524H56V157.629H81.6445L117.448 241.581L153.252 157.629H178.734Z"
                fill="white"
              />
              <path
                id="s"
                d="M421.297 268.015C415.492 265.373 410.896 261.788 407.508 257.259C404.193 252.917 402.254 247.678 401.942 242.218H424.705C425.131 245.667 426.824 248.525 429.783 250.791C432.742 253.057 436.425 254.189 440.833 254.186C445.132 254.186 448.489 253.324 450.904 251.599C453.318 249.874 454.528 247.663 454.533 244.964C454.533 242.054 453.054 239.871 450.098 238.415C447.141 236.96 442.438 235.37 435.988 233.645C429.318 232.024 423.861 230.352 419.616 228.63C415.378 226.911 411.616 224.191 408.651 220.702C405.583 217.144 404.051 212.345 404.053 206.306C404.019 201.438 405.509 196.683 408.314 192.71C411.154 188.616 415.24 185.379 420.572 182.999C425.905 180.619 432.168 179.433 439.361 179.441C450.005 179.441 458.499 182.109 464.843 187.447C471.187 192.784 474.682 199.982 475.328 209.041H453.715C453.393 205.482 451.914 202.652 449.281 200.549C446.647 198.446 443.125 197.395 438.715 197.395C434.627 197.395 431.483 198.15 429.283 199.66C428.234 200.334 427.378 201.27 426.798 202.375C426.218 203.481 425.935 204.719 425.976 205.968C425.976 208.99 427.481 211.282 430.492 212.844C433.502 214.405 438.18 215.995 444.524 217.614C450.98 219.232 456.301 220.904 460.49 222.629C464.714 224.38 468.451 227.136 471.377 230.658C474.442 234.283 476.028 239.055 476.134 244.972C476.2 249.943 474.71 254.81 471.873 258.886C469.033 262.986 464.946 266.193 459.614 268.508C454.282 270.823 448.074 271.989 440.988 272.004C433.663 271.986 427.099 270.656 421.297 268.015Z"
                fill="white"
              />
              <path
                id="i"
                d="M496.213 166.436C494.961 165.2 493.968 163.727 493.29 162.102C492.611 160.478 492.262 158.734 492.262 156.973C492.262 155.212 492.611 153.468 493.29 151.844C493.968 150.219 494.961 148.746 496.213 147.51C498.846 144.978 502.153 143.71 506.132 143.707C510.111 143.705 513.417 144.973 516.051 147.51C517.302 148.746 518.296 150.219 518.974 151.844C519.652 153.468 520.002 155.212 520.002 156.973C520.002 158.734 519.652 160.478 518.974 162.102C518.296 163.727 517.302 165.2 516.051 166.436C513.417 168.968 510.111 170.235 506.132 170.235C502.153 170.235 498.846 168.968 496.213 166.436ZM517.275 180.913V270.524H494.698V180.913H517.275Z"
                fill="white"
              />
              <path
                id="c"
                d="M539.352 201.373C542.939 194.609 548.365 189.005 555 185.209C561.664 181.382 569.299 179.468 577.902 179.468C588.974 179.468 598.141 182.244 605.402 187.796C612.663 193.349 617.529 201.141 620 211.173H595.645C594.354 207.289 592.176 204.242 589.111 202.033C586.046 199.824 582.257 198.718 577.743 198.716C571.288 198.716 566.181 201.062 562.421 205.754C558.661 210.447 556.782 217.101 556.782 225.717C556.782 234.237 558.664 240.84 562.429 245.528C566.194 250.215 571.301 252.561 577.751 252.566C586.889 252.566 592.857 248.468 595.653 240.272H620.008C617.532 249.975 612.638 257.686 605.328 263.404C598.018 269.122 588.879 271.978 577.91 271.973C569.306 271.973 561.672 270.06 555.008 266.232C548.37 262.443 542.941 256.842 539.352 250.08C535.587 243.127 533.706 235.012 533.709 225.736C533.712 216.46 535.593 208.339 539.352 201.373Z"
                fill="white"
              />
              <g id="casque">
                <path
                  id="oreille-droite"
                  d="M340.827 199.193L341.017 187.676C341.035 186.564 341.293 185.469 341.772 184.466C342.251 183.463 342.94 182.575 343.792 181.864C344.645 181.152 345.64 180.634 346.711 180.344C347.781 180.053 348.902 179.999 349.995 180.183C355.088 181.031 360.003 182.73 364.535 185.209C371.454 188.969 377.182 194.602 381.066 201.466C385.099 208.476 387.116 216.564 387.116 225.728C387.116 234.893 385.05 242.981 380.919 249.991C376.928 256.857 371.123 262.483 364.144 266.247C359.571 268.728 354.616 270.425 349.484 271.266C348.381 271.45 347.251 271.391 346.172 271.092C345.094 270.794 344.094 270.264 343.241 269.539C342.387 268.813 341.702 267.91 341.232 266.893C340.761 265.875 340.518 264.767 340.518 263.645V252.244L340.827 199.193ZM352.048 249.253C355.65 247.259 358.527 244.266 360.678 240.276C362.829 236.285 363.904 231.432 363.904 225.717C363.904 217.202 361.673 210.651 357.211 206.065C352.749 201.479 347.288 199.188 340.827 199.193C334.372 199.193 328.969 201.485 324.618 206.069C320.267 210.653 318.09 217.203 318.088 225.721C318.088 234.241 320.212 240.792 324.459 245.376C328.707 249.96 334.056 252.25 340.506 252.248C344.542 252.261 348.514 251.229 352.037 249.253H352.048Z"
                  fill="white"
                />
                <path
                  id="oreille-gauche"
                  d="M244.102 252.248V263.637C244.102 264.759 243.859 265.867 243.389 266.884C242.918 267.902 242.233 268.805 241.379 269.53C240.526 270.255 239.525 270.785 238.447 271.082C237.369 271.38 236.239 271.439 235.136 271.255C230.004 270.414 225.047 268.719 220.472 266.24C213.495 262.474 207.691 256.848 203.701 249.983C199.57 242.975 197.504 234.888 197.504 225.721C197.504 216.553 199.518 208.466 203.546 201.458C207.429 194.597 213.153 188.967 220.069 185.209C224.602 182.731 229.517 181.034 234.609 180.187C235.703 180.002 236.824 180.056 237.895 180.346C238.966 180.636 239.962 181.154 240.815 181.866C241.668 182.577 242.357 183.465 242.837 184.468C243.316 185.472 243.573 186.567 243.591 187.68L243.777 199.201L244.102 252.248ZM244.102 252.255C250.558 252.255 255.907 249.964 260.149 245.38C264.392 240.796 266.515 234.246 266.521 225.728C266.521 217.211 264.342 210.659 259.986 206.073C255.63 201.486 250.227 199.193 243.777 199.193C237.322 199.193 231.866 201.484 227.409 206.065C222.952 210.646 220.721 217.198 220.716 225.721C220.716 231.438 221.791 236.29 223.942 240.276C226.093 244.261 228.969 247.255 232.568 249.257C236.093 251.231 240.065 252.261 244.102 252.248V252.255Z"
                  fill="white"
                />
                <path
                  id="tete-du-casque"
                  d="M236 196.048V174.684C236 99.1224 344 99.1224 344 174.684V196.048"
                  stroke="white"
                  stroke-width="9"
                  stroke-miterlimit="10"
                />
              </g>
            </g>
          </g>
        </svg>

        <h1>Catalogue de webradios et musiques pour se détendre.</h1>
      </section>
      <section class="partie-droite">
        <div class="boutons-acces">
          <form
            action=" accueil.php?video=1"
            class="bouton1" method='post'
          >
            <input type="submit" name="moosicSansCompte" value="Découvrir Moosic sans compte" />
          </form>
          <form
            action="https://la-projets.univ-lemans.fr/~mmi1pj03/landing-connexion.php"
            class="bouton2"
          >
            <input type="submit" value="se connecter" />
          </form>
        </div>
        <div class="form-container">
          <h2>Créer votre compte</h2>
          <form action="" method="post" class="form-inscription">
            <div class="form-pseudo">
              <p>Entrez votre pseudo :</p>
              <label for="name"></label>
              <input type="text" name="name" id="name" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>" required />
            </div>
            <div class="form-mail">
              <p>Entrez votre adresse Mail :</p>
              <label for="mail"></label>
              <input type="email" name="mail" id="mail"  value="<?php if(isset($mail)) { echo $mail; } ?>" required />
            </div>
            <div class="form-mail">
              <p>Confirmez votre adresse Mail :</p>
              <label for="mail2"></label>
              <input type="email" name="mail2" id="mail2"  required />
            </div>
            <div class="form-mdp">
              <p>Entrez votre mot de passe:</p>
              <label for="mdp"></label>
              <input type="password" name="mdp" id="mdp" required />
            </div>
            <div class="form-mdp">
              <p>Confirmez votre mot de passe:</p>
              <label for="mdp2"></label>
              <input type="password" name="mdp2" id="mdp2" required />
            </div>
            <p>Renseignez votre sexe:</p>
            <div class="form-sexe">
              <div class="boutons-sexe">
                <input
                  type="radio"
                  id="homme"
                  name="gender"
                  value="homme"
                  checked
                />
                <label for="homme">Homme</label><br />
                <input type="radio" id="femme" name="gender" value="femme" />
                <label for="femme">Femme</label><br />
                <input type="radio" id="autre" name="gender" value="autre" />
                <label for="autre">Autre</label>
              </div>
            </div>
            <div class="form-submit">
              <input type="submit" value="Valider" name="forminscription" />
            </div>
            <?php
	if(isset($erreur)) {
?>
          <div class="error"><?php echo($erreur) ; ?></div>
          <?php
	}{
    $erreur = "C'est tout bon !";
  }
    ?>
          </form>

          <p class="cgu-warning">
            En validant votre compte, vous accepter nos CGU et conditions
            d’utilisations
          </p>
        </div>
        <div class="icones">
          <div class="div-icone">
            <img
              src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon1.svg?v=1622464119716"
            />
            <p>Esprit communautaire et de partage.</p>
          </div>
          <div class="div-icone">
            <img
              src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon2.svg?v=1622464119716"
            />
            <p>Tri de contenu en fonction de vos envies.</p>
          </div>
          <div class="div-icone">
            <img
              src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon3.svg?v=1622465251440"
            />
            <p>Espace bien-être.</p>
          </div>
        </div>
      </section>
    </main>
  </body>
</html>

<?php
   // connexion.php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  session_start();
  header("Content-Type: text/html; charset=utf-8") ;
  require (__DIR__ . "/param.inc.php");

  if(isset($_POST['formconnexion'])) {
		$mailconnect = htmlspecialchars($_POST['mail']);
		$mdpconnect = sha1($_POST['mdp']);
		if(!empty($mailconnect) AND !empty($mdpconnect)) {
			// Etape 1 : connexion au serveur de base de données
			$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
			$bdd->query("SET NAMES utf8");
			$bdd->query("SET CHARACTER SET 'utf8'");
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// Etape 2 : envoi de la requête SQL au serveur
			$requser = $bdd->prepare("SELECT idUtilisateur, pseudo, mail, mdpUti FROM Utilisateur WHERE mail = ? AND mdpUti = ? ");
			$requser->execute(array($mailconnect, $mdpconnect)); // On sélectionne l'id, le pseudo, le mail et le mdp qui correspondent au mail et mdp entré par l'utilisateur
			$ligne = $requser->fetch(PDO::FETCH_ASSOC) ;
			if($ligne != false) {
				$_SESSION['idUtilisateur'] = $ligne['idUtilisateur'];
				$_SESSION['pseudo'] = $ligne['pseudo'];
				$_SESSION['mail'] = $ligne['mail'];
				$_SESSION['mdpUti'] = $ligne['mdpUti'];
				header("Location: accueil.html"); // Si les infos correspondent au variables de SESSION stocké dans l'inscription alors on le redirige vers l'acceuil
			} else {
				$erreur = "Mauvais mail ou mot de passe !";
			}
			
			// Etape 4 : ferme la connexion au serveur de base de données
			$pdo = null ;
		} else {
			$erreur = "Tous les champs doivent être complétés !";
		}
	}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Moosic</title>
  <meta charset="UTF-8" />
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>

<body>
  <header>
    <div class="player-off case">
      <svg width="68" height="57" viewBox="0 0 68 57" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M51.3994 30.8158L51.4675 26.7171C51.474 26.3213 51.5663 25.9316 51.738 25.5746C51.9098 25.2177 52.1569 24.9018 52.4626 24.6486C52.7683 24.3954 53.1253 24.2109 53.5093 24.1077C53.8932 24.0045 54.295 23.9849 54.6872 24.0504C56.5137 24.3523 58.2763 24.957 59.9017 25.8393C62.383 27.1774 64.437 29.1818 65.8301 31.6245C67.2765 34.1193 67.9997 36.9974 67.9997 40.2589C67.9997 43.5204 67.2589 46.3985 65.7773 48.8932C64.346 51.3366 62.2641 53.3387 59.7614 54.6785C58.1212 55.5613 56.3442 56.165 54.5039 56.4645C54.1082 56.53 53.7029 56.5089 53.3163 56.4027C52.9296 56.2965 52.5708 56.1078 52.2648 55.8497C51.9588 55.5916 51.7129 55.2703 51.5443 54.9081C51.3757 54.5459 51.2883 54.1515 51.2883 53.7523V49.695L51.3994 30.8158ZM55.4234 48.6306C56.7152 47.921 57.7468 46.8561 58.5182 45.4359C59.2896 44.0157 59.6753 42.2887 59.6753 40.2547C59.6753 37.2246 58.8752 34.8934 57.275 33.2613C55.6748 31.6291 53.7163 30.814 51.3994 30.8158C49.0843 30.8158 47.1466 31.6314 45.5863 33.2626C44.026 34.8939 43.2453 37.225 43.2444 40.2561C43.2444 43.2881 44.006 45.6198 45.5293 47.251C47.0527 48.8822 48.9709 49.6973 51.2841 49.6964C52.7317 49.701 54.1559 49.3339 55.4193 48.6306H55.4234Z"
          fill="white" />
        <path
          d="M16.7115 49.6965V53.7497C16.7115 54.1488 16.6241 54.5432 16.4555 54.9053C16.2868 55.2675 16.0409 55.5887 15.7349 55.8467C15.4289 56.1047 15.07 56.2933 14.6834 56.3993C14.2967 56.5053 13.8915 56.5262 13.4959 56.4605C11.6553 56.1612 9.87784 55.558 8.23697 54.6758C5.73478 53.3358 3.65334 51.3337 2.22245 48.8906C0.740818 46.3968 0 43.5186 0 40.2562C0 36.9938 0.722294 34.1157 2.16689 31.6219C3.55924 29.1803 5.61233 27.1768 8.09251 25.8394C9.718 24.9576 11.4806 24.3534 13.3069 24.0519C13.6993 23.9862 14.1012 24.0055 14.4853 24.1087C14.8694 24.2118 15.2266 24.3963 15.5325 24.6495C15.8384 24.9027 16.0857 25.2186 16.2575 25.5757C16.4293 25.9328 16.5216 26.3227 16.5281 26.7185L16.5948 30.8187L16.7115 49.6965ZM16.7115 49.6993C19.0265 49.6993 20.9448 48.8837 22.4662 47.2525C23.9877 45.6212 24.7493 43.2901 24.7512 40.259C24.7512 37.2279 23.9701 34.8963 22.4079 33.2641C20.8457 31.632 18.908 30.8159 16.5948 30.8159C14.2797 30.8159 12.323 31.6311 10.7247 33.2614C9.12641 34.8917 8.32633 37.2233 8.32448 40.2562C8.32448 42.2911 8.71016 44.0177 9.48154 45.436C10.2529 46.8543 11.284 47.9197 12.5749 48.6321C13.839 49.3347 15.2636 49.7013 16.7115 49.6965V49.6993Z"
          fill="white" />
        <path d="M14.6099 29.7707V22.1676C14.6099 -4.72253 53.3417 -4.72253 53.3417 22.1676V29.7707" stroke="white" stroke-width="3.8004" stroke-miterlimit="10" />
      </svg>
    </div>
    <nav>
      <ul>
        <li>Accueil</li>
        <li>Rechercher</li>
        <li>Découvrir</li>
        <li>Mon mood</li>
        <li>Bibliothèque</li>
      </ul>
      <div class="profil">
        <div class="profil-name">
          <svg width="67" height="78" viewbox="0 0 67 78" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
            <path d="M33.4996 35.2498C36.0538 35.2498 38.1245 33.1792 38.1245 30.6249C38.1245 28.0706 36.0538 26 33.4996 26C30.9453 26 28.8746 28.0706 28.8746 30.6249C28.8746 33.1792 30.9453 35.2498 33.4996 35.2498Z" fill="white" />
            <path d="M42.4887 59.6542L36.7931 61.7902L39.6255 62.8524C40.8444 63.305 42.1597 62.6779 42.6029 61.4986C42.8389 60.8694 42.7691 60.2104 42.4887 59.6542Z" fill="white" />
            <path d="M22.7497 51.5837C21.5562 51.1411 20.2216 51.7418 19.7722 52.9375C19.324 54.1332 19.9303 55.4656 21.126 55.9149L23.6208 56.8504L30.2073 54.3802L22.7497 51.5837Z" fill="white" />
            <path
              d="M47.2276 52.9376C46.7782 51.7418 45.4436 51.1411 44.2502 51.5838L25.7506 58.5212C24.5548 58.9706 23.9485 60.3029 24.3967 61.4987C24.8399 62.6775 26.1549 63.3052 27.3742 62.8524L45.8738 55.9151C47.0697 55.4657 47.676 54.1334 47.2276 52.9376Z"
              fill="white" />
            <path
              d="M49.6871 44.4997H44.1792L40.1934 36.528C39.7792 35.7004 38.9366 35.2289 38.0682 35.2499H33.4999H28.9314C28.0631 35.2289 27.2216 35.7005 26.8065 36.528L22.8206 44.4997H17.3128C16.0358 44.4997 15.0004 45.5351 15.0004 46.8121C15.0004 48.0891 16.0358 49.1245 17.3128 49.1245H24.2501C25.1264 49.1245 25.9269 48.6299 26.3187 47.8463L28.875 42.7337V51.4114L33.4998 53.1455L38.1248 51.4108V42.7337L40.6811 47.8463C41.073 48.63 41.8735 49.1245 42.7497 49.1245H49.687C50.964 49.1245 51.9994 48.0891 51.9994 46.8121C51.9994 45.5351 50.9641 44.4997 49.6871 44.4997Z"
              fill="white" />
            <circle cx="34" cy="7" r="7" fill="#00FFA3" />
          </svg>
          <span class="profil-name">LaZen53</span>
        </div>
        <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733" class="profil-options" />
      </div>
    </nav>
  </header>
  <main>
    <section class="partie-gauche">

    </div>
    <div class="zone-background">
      <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-moosic.svg?v=1622464119881" alt="Logo de Moosic" class="logo-entier" />

      <div class="top-part">


        <h1>Catalogue de webradios et musiques pour se détendre.</h1>
        <svg class="logo-petit" width="190" height="158" viewBox="0 0 190 158" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M143.324 85.6702L143.513 74.2251C143.531 73.12 143.789 72.0319 144.268 71.0351C144.747 70.0383 145.436 69.1562 146.288 68.4493C147.141 67.7423 148.136 67.2271 149.207 66.9388C150.277 66.6506 151.398 66.5961 152.491 66.779C157.584 67.6219 162.499 69.3103 167.031 71.7739C173.95 75.5105 179.678 81.1076 183.562 87.9283C187.596 94.8944 189.612 102.931 189.612 112.038C189.612 121.145 187.546 129.182 183.415 136.148C179.424 142.971 173.619 148.561 166.64 152.303C162.067 154.768 157.112 156.453 151.98 157.29C150.877 157.472 149.747 157.413 148.669 157.117C147.59 156.821 146.59 156.294 145.737 155.573C144.883 154.852 144.198 153.955 143.728 152.944C143.257 151.932 143.014 150.831 143.014 149.716V138.387L143.324 85.6702ZM154.544 135.415C158.146 133.433 161.023 130.46 163.174 126.494C165.325 122.529 166.4 117.706 166.4 112.027C166.4 103.565 164.169 97.0561 159.707 92.4986C155.245 87.9412 149.784 85.665 143.324 85.6702C136.868 85.6702 131.465 87.9476 127.114 92.5025C122.763 97.0573 120.587 103.567 120.584 112.031C120.584 120.497 122.708 127.008 126.955 131.562C131.203 136.117 136.552 138.393 143.002 138.391C147.038 138.404 151.01 137.379 154.533 135.415H154.544Z"
            fill="white" />
          <path
            d="M46.5985 138.391V149.709C46.5985 150.823 46.355 151.924 45.8847 152.936C45.4145 153.947 44.7288 154.844 43.8755 155.564C43.0221 156.285 42.0215 156.811 40.9433 157.107C39.8651 157.403 38.7352 157.461 37.632 157.278C32.4997 156.442 27.5435 154.758 22.9681 152.295C15.9909 148.553 10.187 142.962 6.19712 136.14C2.06571 129.177 0 121.14 0 112.03C0 102.921 2.01406 94.8841 6.04219 87.9205C9.92463 81.1029 15.6495 75.5085 22.5653 71.7739C27.0978 69.3117 32.0127 67.6246 37.1053 66.7829C38.1992 66.5993 39.32 66.6533 40.3911 66.9413C41.4621 67.2293 42.4582 67.7444 43.311 68.4514C44.1639 69.1584 44.8535 70.0407 45.3326 71.0378C45.8118 72.035 46.0692 73.1235 46.0872 74.2289L46.2731 85.6778L46.5985 138.391ZM46.5985 138.399C53.0538 138.399 58.4027 136.121 62.6452 131.566C66.8876 127.011 69.0114 120.502 69.0166 112.038C69.0166 103.574 66.8385 97.0637 62.4825 92.5063C58.1264 87.9489 52.7233 85.6701 46.2731 85.6701C39.8178 85.6701 34.3617 87.9463 29.905 92.4986C25.4482 97.0509 23.2173 103.561 23.2121 112.03C23.2121 117.712 24.2875 122.534 26.4385 126.494C28.5894 130.455 31.4646 133.429 35.0641 135.419C38.5887 137.381 42.5613 138.404 46.5985 138.391V138.399Z"
            fill="white" />
          <path d="M38.4961 82.5448V61.3144C38.4961 -13.7715 146.496 -13.7715 146.496 61.3144V82.5448" stroke="white" stroke-width="9" stroke-miterlimit="10" />
        </svg>
      </div>
      <div class="icones">
        <div class="icones-partage">
          <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon1.svg?v=1622464119716" />
          <p>Esprit communautaire et de partage.</p>
        </div>
        <div class="icones-tri">
          <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon2.svg?v=1622464119716" />
          <p>Tri de contenu en fonction de vos envies.</p>
        </div>
        <div class="icones-bienetre">
          <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon3.svg?v=1622465251440" />
          <p>Espace bien-être.</p>
        </div>
      </div>
    </section>
    <section class="partie-droite">
      <div class="form-container">
        <h2>Connexion</h2>
        <form action="" method="post" class="form-inscription">

          <div class="form-mail">
            <p>Entrez votre adresse Mail :</p>
            <label for="mail"></label>
            <input type="email" name="mail" id="mail" required />
          </div>

          <div class="form-mail">
            <p>Entrez votre mot de passe:</p>
            <label for="mdp"></label>
            <input type="password" name="mdp" id="mdp" required />
          </div>

          <div class="form-submit">
            <input type="submit" value="Connexion" class="connexion" name ="formconnexion" />
            <a href="recup.php" name="mdpoublie"> Mots de passe oublié </a>
          </div>
        </form>
      </div>
    </section>
  </main>


</body>

</html>

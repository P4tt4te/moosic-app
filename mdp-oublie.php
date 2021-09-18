<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8") ;
require (__DIR__ . "/param.inc.php");
if(isset($_POST['mdpoublie'])){
    if(!empty($_POST['mdprecup'])){
        $recupmdp = htmlspecialchars($_POST['mdprecup']); // On stock le nouveau mdp dans une varible
        $mdplength= mb_strlen($recupmdp);
        if($mdplength>=4){
            // Etape 1 : connexion au serveur de base de données
            $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
            $bdd->query("SET NAMES utf8");
            $bdd->query("SET CHARACTER SET 'utf8'");
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Etape 2 : envoi de la requête SQL au serveur
            $mdpexiste = $bdd->prepare('SELECT mdpUti FROM Utilisateur WHERE mail = ?');
            $mdpexiste -> execute(array($_SESSION['mail'])); // On sélectionne l'ancien mdp qui correspond au mail stocker dans la page de récup
            $ligne = $mdpexiste->fetch(PDO::FETCH_ASSOC);
            if($mdpexiste != $recupmdp){
                $newmdp = $bdd->prepare('UPDATE Utilisateur SET mdpUti = ? WHERE mail = ? ');
                $newmdp -> execute(array(sha1($recupmdp),$_SESSION["mail"])); // On stock le nouveau mdp et on le crypte

            }else{
                $erreur = "Ton mot de passe correspond à ton mot de passe actuel !";
            }
        }else{
            $erreur = "Ton mot de passe doit contenir plus de 4 caractères";
        }
    }else{
        $erreur = "Veuillez remplir tout les champs";
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
    <!-- <link rel="stylesheet" href="style-landing.css" type="text/css" media="screen" /> -->
</head>


<body>
    <main>
        <form action="" method="post">
            <div class="form-mdp">
                <p>Entrez votre nouveau mot de passe</p>
                <label for="mdprecup"></label>
                <input type="password" name="mdprecup" id="mdprecup" required />
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

    </main>
</body>

</html>
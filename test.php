<?php
//Dans une autre page :
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
require (__DIR__ . "/param.inc.php");
/*
Partons sur le fait que l'ID de l'utilisateur qui upload l'image possède son ID dans une variable de SESSION récupérée lors de la connexion
*/
 // Etape 1 : connexion au serveur de base de données
 $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
 $bdd->query("SET NAMES utf8");
 $bdd->query("SET CHARACTER SET 'utf8'");
 
//Tu récupère donc le nom de l'image
$req = $bdd->prepare('SELECT photoRadio FROM Radio WHERE Radio.nomRadio = ?');
$req->execute(array($_POST['nomRadio']));
$donnees = $req->fetch();
//Ensuite tu peux afficher l'image comme ceci
?>
<img src="images/<?php echo $donnees['photoRadio']; ?>"/>
 

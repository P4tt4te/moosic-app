<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/css; charset: UTF-8");
session_start();
require (__DIR__ . "/param.inc.php");
// Etape 1 : connexion au serveur de base de données
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");

$idUti = $_SESSION['idUtilisateur'];
$getid = htmlspecialchars($_GET['id']);

$mood = $bdd->prepare('SELECT * FROM Mood INNER JOIN possede ON Mood.idMood = possede.idMood INNER JOIN Utilisateur ON possede.idUtilisateur = Utilisateur.idUtilisateur WHERE Utilisateur.idUtilisateur= ?');
$mood->execute(array($idUti));
$ligne = $mood->fetch(PDO::FETCH_ASSOC);
$couleur = $ligne['couleur'];

?>

.mood::before{
    background-color:<?php echo($couleur);?> ;
}


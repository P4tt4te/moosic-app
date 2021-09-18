<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: application/json; charset=utf-8");
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$idObjet = $_POST['idObjet'];
$idUti =  $_SESSION['idUtilisateur'];
$type = $_POST['type'];
$acheteObjet = $bdd -> prepare('SELECT *,
CASE
	WHEN Achete.idObjet IS NULL THEN "false"
    ELSE "true"
    END AS achete
FROM Objet LEFT JOIN Achete ON Objet.idObjet = Achete.idObjet 
AND Achete.idUtilisateur = ?
WHERE Objet.idObjet = ? 
ORDER BY idType DESC'); // On stock quels objets sont achetés par l'utilisateur 

$acheteObjet -> execute(array($idUti,$idObjet));
$dejaAchete = $acheteObjet->fetch(PDO::FETCH_ASSOC);

$select = $bdd -> prepare('SELECT * FROM Utilisateur WHERE idUtilisateur = ?');
$select -> execute(array($idUti)); 
$moodPoints =  $select->fetch(PDO::FETCH_ASSOC); // On veut savoir le nombre de moodPoints de l'utilisateur



$idUti =  $_SESSION['idUtilisateur'];

if(isset($type) AND $type == 11 ){

    if($dejaAchete['achete'] == "false" AND $moodPoints['moodPoints'] >= $dejaAchete['prixObjet']){

        $insert = $bdd -> prepare('INSERT INTO Achete(idUtilisateur,idObjet) VALUES (?,?)');
        $insert -> execute(array($idUti,$idObjet));
        $update = $bdd -> prepare('UPDATE Utilisateur SET moodPoints = moodPoints - ? WHERE idUtilisateur = ?');
        $update -> execute(array($dejaAchete['prixObjet'],$idUti));
        $dejaAchete = array_merge($dejaAchete, array("resultat" => "true"));
        echo(json_encode($dejaAchete));
    }
    else{
        $dejaAchete = array_merge($dejaAchete, array("resultat" => "false"));
        echo(json_encode($dejaAchete));
    }
}



?>
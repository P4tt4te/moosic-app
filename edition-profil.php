<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS);
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$requser = $bdd->prepare("SELECT * FROM Utilisateur WHERE idUtilisateur = ?");
$requser->execute(array($_SESSION['idUtilisateur']));
$user = $requser->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['formEdition'])) {
    if(isset($_POST['name']) AND !empty($_POST['name']) AND $_POST['name'] != $user['pseudo']) {
        $newpseudo = htmlspecialchars($_POST['name']);
        $insertpseudo = $bdd->prepare("UPDATE Utilisateur SET pseudo = ? WHERE idUtilisateur = ?");
        $insertpseudo->execute(array($newpseudo, $_SESSION['idUtilisateur']));
        $_SESSION['pseudo'] = $newpseudo;	
    }
    if(isset($_POST['mdp']) AND !empty($_POST['mdp']) AND isset($_POST['mdp2']) AND !empty($_POST['mdp2'])) {
        $mdp1 = sha1($_POST['mdp']);
        $mdp2 = sha1($_POST['mdp2']);
        if($mdp1 == $mdp2) {
            $insertmdp = $bdd->prepare("UPDATE Utilisateur SET mdpUti = ? WHERE idUtilisateur = ?");
            $insertmdp->execute(array($mdp1, $_SESSION['idUtilisateur']));
        } 
        else {
            $erreur = "Vos deux mdp ne correspondent pas !";
        }
    }
}
?>
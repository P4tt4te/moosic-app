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
$ligne["type"] = $_POST['type'];
$idRadio = $_POST['idRadio'];

if(isset($ligne['type']) AND $ligne['type'] == 6 ){
    if(isset($_GET['id']) AND isset($_SESSION['idUtilisateur'])) {
        if(isset($_POST['like'])){
            $like = $_POST['like'];
            $idUti = $_SESSION['idUtilisateur'];
            $select = $bdd->prepare('SELECT *  FROM favoris WHERE idRadio = ? AND idUtilisateur = ?');
            $select->execute(array($getid,$idUti));
            $dejaLike = $select->fetch(PDO::FETCH_ASSOC);
            if($dejaLike != true){
                $ins = $bdd->prepare('INSERT INTO favoris (idUtilisateur, idRadio) VALUES (?,?)');
                $ins->execute(array($idUti,$getid));
            }else{
                $delete = $bdd->prepare('DELETE FROM favoris WHERE idRadio = ? AND idUtilisateur = ? ');
                $delete->execute(array($getid,$idUti));
            }
            header("Refresh: 0;url=https://la-projets.univ-lemans.fr/~mmi1pj03/espace-commentaire.php?id=1"); // ID A CHANGER
    
        }
    }
}















?>
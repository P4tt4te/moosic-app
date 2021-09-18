<?php
// inscription.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-Type: text/html; charset=utf-8") ;
session_start();
require (__DIR__ . "/param.inc.php");

// Etape 1 : connexion au serveur de base de données
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$dateActuelle = date("d/m/y H:i");
$dateTest = date("H:i");
$idUti = $_SESSION['idUtilisateur'];
$idRadio = $_GET['id'];

if(isset($_POST['formheure'])) {

$heureexiste = $bdd->prepare('SELECT * FROM Ecoute WHERE idUtilisateur = ?');
$heureexiste -> execute(array($idUti));
$ligne = $heureexiste->fetch(PDO::FETCH_ASSOC);
    if($ligne == false) {
        $insertheure = $bdd -> prepare("INSERT INTO Ecoute(idUtilisateur,idRadio,derniereEcoute, dureeEcoute) VALUES(?,?,NOW(),NOW())");
        $insertheure -> execute(array($idUti,$idRadio));
    }else{
        echo('Pas bon');
    }
    if($ligne != false){
        
    $updateheure = $bdd -> prepare('UPDATE Ecoute SET diffDuree =  TIMESTAMPDIFF(SECOND,dureeEcoute,NOW()), dureeEcoute = NOW() WHERE idUtilisateur = ? AND idRadio = ? ');
    $updateheure -> execute(array($idUti,$idRadio));
    $updatePoints = $bdd -> prepare('UPDATE Utilisateur 
    SET Utilisateur.moodPoints = Utilisateur.moodPoints + ((SELECT Ecoute.diffDuree FROM Ecoute WHERE Utilisateur.idUtilisateur=Ecoute.idUtilisateur AND idRadio = ? )) WHERE idUtilisateur = ?');
    $updatePoints -> execute(array($idRadio,$idUti));

    $updateExp = $bdd -> prepare('UPDATE Utilisateur SET Utilisateur.experience = Utilisateur.experience + ((SELECT Ecoute.diffDuree FROM Ecoute WHERE Utilisateur.idUtilisateur=Ecoute.idUtilisateur AND idRadio = ? )) WHERE idUtilisateur = ?');
    $updateExp -> execute(array($idRadio,$idUti));

    $select = $bdd -> prepare('SELECT * FROM Utilisateur WHERE idUtilisateur = ?');
    $select -> execute(array($idUti));
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $niveau = $ligne['niveauUti'];
    if($ligne['experience'] == ($niveau * 100)){
        
        $niveau = $niveau + 1;
        $updateNiveau = $bdd -> prepare('UPDATE Utilisateur SET niveau = ? WHERE idUtilisateur = ?');
        $updateNiveau -> execute(array($niveau, $idUti));



    } 
    }
}




?>

<form action="" method="post" >
<input type="submit" value="Heure" name="formheure" />
</form>
<!DOCTYPE html>
<html>

<head>
    <title>AJOUT RADIO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="styles-admin.css" type="text/css" />
</head>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
setlocale (LC_TIME, 'fr_FR.utf8','fra');
date_default_timezone_set('Europe/Paris');
require (__DIR__ . "/param.inc.php");
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$getid = htmlspecialchars($_GET['id']);
$radio = $bdd->prepare('SELECT * FROM Radio WHERE idRadio = ?');
$radio->execute(array($getid));
$radio = $radio->fetch(PDO::FETCH_ASSOC);
$select = $bdd->prepare('SELECT COUNT(idRadio) as nbFav FROM favoris WHERE idRadio = ?');
$select->execute(array($getid));
$nbFav = $select->fetch(PDO::FETCH_ASSOC);
$dateActuelle = date("d/m/y H:i");

?> 
<form action = "" method = "post">
<p><?php echo($radio['description']); echo($radio['nomRadio']);?></p>
<p><img src="./images/<?php echo($radio['photoRadio']); ?> " width = '100' > </p>  <!--AFFICHAGE RADIO IMAGE/NOM-->
<p>  <input type="submit" value="liker" name="like"/> </p>
<p> Nb like : <?php echo($nbFav['nbFav']);?> </p>
</form>
<?php
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    if(isset($_POST['poster_commentaire'])) {
        if(isset($_POST['commentaire']) AND !empty($_POST['commentaire'])) {
            $pseudo = htmlspecialchars($_SESSION['pseudo']);
            $idUti = $_SESSION['idUtilisateur'];
            $commentaire = htmlspecialchars($_POST['commentaire']);
            $ins = $bdd->prepare('INSERT INTO Commentaire (contenu, idRadio, Commentaire.date, idUtilisateur) VALUES (?,?,?,?)');
            $ins->execute(array($commentaire, $getid,$dateActuelle, $idUti));
            $erreur = "Votre commentaire a bien été posté";
            header("Refresh: 0;url=https://la-projets.univ-lemans.fr/~mmi1pj03/espace-commentaire.php?id=1"); // ID A CHANGER
        }else{
            $erreur = 'Veuillez vous connectez pour poster un commentaire';
            header('Location : https://la-projets.univ-lemans.fr/~mmi1pj03/landing-connexion.php');
        }
    }else{
        $erreur = "Veuillez rentrer un commentaire pour pouvoir le poster";
    }
    if(isset($_POST['signalement'])) {
        $idCom = $_POST['idCom'];
        $select = $bdd->prepare('SELECT * FROM Commentaire WHERE idCommentaire = ?');
        $select -> execute(array($idCom));
        $nbSignalement = $select->fetch(PDO::FETCH_ASSOC);
        if($nbSignalement!=false){
            $update = $bdd -> prepare('UPDATE Commentaire SET nbSignalement = nbSignalement + 1 WHERE idCommentaire = ?');
            $update -> execute(array($idCom ));
        }
        if($nbSignalement['nbSignalement'] >= '5'){
            $mail = 'nathan.harnay29@gmail.com';
            $header="MIME-Version: 1.0\r\n"; // On créer le header du mail
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
                            Bonjour,
                            Le commentaire d\'id '.$idCom.' a été signalé plus de 5 fois
                            Voici le lien vers la page d\'administration des commentaire: https://la-projets.univ-lemans.fr/~mmi1pj03/admin-commentaire.php
                            A bientôt sur MOOSIC !
                        </div>
                        </html>';
            mail($mail, "Signalement de commentaire - MOOSIC", $message, $header); // On envoie le mail avec les infos donné

        }
    }
    
    $commentaires = $bdd->prepare('SELECT Commentaire.idCommentaire, Commentaire.contenu, Utilisateur.pseudo, Commentaire.date FROM Commentaire INNER JOIN Utilisateur ON Commentaire.idUtilisateur = Utilisateur.idUtilisateur WHERE idRadio = ? ORDER BY Commentaire.idCommentaire DESC');
    $commentaires->execute(array($getid));

?>
<form method="POST">
   <textarea name="commentaire" placeholder="Votre commentaire..."></textarea><br />
   <input type="submit" value="Poster mon commentaire" name="poster_commentaire" />
</form>
<?php
if(isset($erreur)) {
?>
        <div class="error"><?php echo($erreur) ; ?></div>
        <?php
	} 
    ?>

<div>
<?php while($ligne = $commentaires->fetch(PDO::FETCH_ASSOC)) { ?>
<form method="POST">
<?php   echo($ligne['pseudo']); ?> <?php echo($dateActuelle); ?>:  <?php echo($ligne['contenu']); ?> </br>
        <input type="submit" value="Signaler commentaire" name="signalement" />
        <input type="hidden" value = "<?php if(isset($ligne['idCommentaire'])) { echo $ligne['idCommentaire']; } ?> " name = "idCom">
</form>
</br>
<?php 
}?>

</div>
<?php
}else{?>
    <?php $erreur = "Veuillez vous connectez pour continuer";?>
    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/landing-connexion.php" target="_blank"> <input type="button" value="Connexion"> </a>

<?php
}
?>
</html>
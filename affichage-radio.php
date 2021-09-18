<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
session_start();
require (__DIR__ . "/param.inc.php");

 // Etape 1 : connexion au serveur de base de données
 $bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
 $bdd->query("SET NAMES utf8");
 $bdd->query("SET CHARACTER SET 'utf8'");
 $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 $idUti = $_SESSION['idUtilisateur'];

 $rechercheRadio = $bdd->query('SELECT * FROM Radio WHERE categorie = "jazz" ORDER BY idRadio DESC');
 

 $favoris = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url,Radio.categorie,
                        CASE 
                            WHEN favoris.idUtilisateur IS NULL THEN "false"
                            ELSE "true"
                        END AS favoris
                        FROM Radio 
                        
                        LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti 
                        HAVING favoris = "true"
                        ORDER BY Radio.idRadio DESC
                        LIMIT 0,3');
    $favoris->bindValue('idUti',$idUti,PDO::PARAM_INT);
  
    



?>


<?php while($ligne = $rechercheRadio->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
<div>
    <a href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php $ilgne['idRadio'] ?>'>
        <img class="img-radios" src="./images/<?php echo($ligne['photoRadio']); ?>" width='100'>
        <?php echo($ligne['nomRadio']); echo($ligne['categorie']);?></div>
</a>
<?php } ?>


<?php while($ligne = $select->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios?>
<div>
    <a href='https://la-projets.univ-lemans.fr/~mmi1pj03/profil-radio.php?id=<?php $ilgne['idRadio'] ?>'>
        <img class="img-radios" src="./images/<?php echo($ligne['photoRadio']); ?>" width='100'>
        <?php echo($ligne['nomRadio']); echo($ligne['categorie']);?></div>
</a>
<?php } ?>


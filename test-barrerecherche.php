<?php
//Dans une autre page :
ini_set('display_errors', 1);
header("Content-type: text/html; charset=UTF-8");
error_reporting(E_ALL);
require (__DIR__ . "/param.inc.php");
 // Etape 1 : connexion au serveur de base de données
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$rechercheRadio = $bdd->query('SELECT * FROM Radio ORDER BY idRadio DESC');


if(isset($_GET['rechercher']) AND !empty($_GET['rechercher'])){
   
   $recherche = htmlspecialchars($_GET['rechercher']);
   $rechercheRadio = $bdd->prepare('SELECT * FROM Radio WHERE (nomRadio LIKE ? OR categorie LIKE  ?)');
   $rechercheRadio->execute(array('%'.$recherche.'%','%'.$recherche.'%'));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>AJOUT RADIO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="styles-admin.css" type="text/css" />
</head>
<body>
<form method="GET">
   <input type="search" name="rechercher" placeholder="Recherche..." />
   <input type="submit" value="Valider" />
</form>
   <ul>
    <?php while($ligne = $rechercheRadio->fetch(PDO::FETCH_ASSOC)) { ?>    
      <li><img src="./images/<?php echo($ligne['photoRadio']); ?> " width = '100' > <?php echo($ligne['nomRadio']);?></li>
    <?php } ?>
   </ul>
</body>
</html>
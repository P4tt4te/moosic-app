<?php
//Dans une autre page :
ini_set('display_errors', 1);
error_reporting(E_ALL);

require (__DIR__ . "/param.inc.php");
/*
Partons sur le fait que l'ID de l'utilisateur qui upload l'image possède son ID dans une variable de SESSION récupérée lors de la connexion
*/
 // Etape 1 : connexion au serveur de base de données
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
$req = $bdd->prepare("SELECT * FROM Radio WHERE idRadio = 46");
$req->execute(array());   
$ligne = $req->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <title>AJOUT RADIO</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="styles-admin.css" type="text/css" />
</head>

<body>

<img src="./images/<?php echo $ligne['photoRadio']); ?> ">
<audio controls>
     <source src="<?php echo($ligne["url"]); ?>" preload="auto">
</audio> 


</body>

</html>
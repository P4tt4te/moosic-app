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
$select = $bdd->prepare("SELECT COUNT(idRadio) as max FROM Radio");
$select->execute();
$max = $select->fetch(PDO::FETCH_ASSOC); //nomnbre de radios

if(isset($ligne["type"]) AND $ligne["type"] == 1 ){ // INITIALE LECTEUR
    $compteur = $_POST['compteur'];
    $idUti = $_SESSION['idUtilisateur'];
    $idRadio = $_POST['idRadio'];
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url, 
    CASE 
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio 
    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti LIMIT 0,1'); // On sélectionne les radios ainsi que lesquelles sont misent en favoris ou non
    $select->bindValue('idUti',$idUti,PDO::PARAM_INT); //bindvalue car pas le choix avec limit
    $select->execute();
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $ligne = array_merge($ligne, array("type" => "1"));
    $ligne = array_merge($ligne, array("compteur" => 0)); // on initialise à 0 le compteur
    echo(json_encode($ligne));// on convertie en objet json pour le récupérer en js
}

if(isset($ligne["type"]) AND $ligne["type"] == 2 ){ // NEXT
    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];
    if($_POST['compteur'] != ($max['max']-1)){ // si le compteur est différent de la valeur max de radio (-1 car le compteur commence à 0 et max à 1)
        $compteur +=1;
    }
        else{
            $compteur = 0; // Si égal au max, on retourne à la première radios
        }
    if(isset($_SESSION['idUtilisateur'])){
    $idUti = $_SESSION['idUtilisateur'];
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url, 
    CASE 
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio 
    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti LIMIT :compteur,1');
    $select->bindValue('compteur',$compteur,PDO::PARAM_INT);
    $select->bindValue('idUti',$idUti,PDO::PARAM_INT);
    $select->execute();
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $ligne = array_merge($ligne, array("type" => "2"));
    $ligne = array_merge($ligne, array("compteur" => $compteur));
    echo(json_encode($ligne));
    }else{
        $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url
        FROM Radio  LIMIT :compteur,1');
        $select->bindValue('compteur',$compteur,PDO::PARAM_INT);
        $select->execute();
        $ligne = $select->fetch(PDO::FETCH_ASSOC);
        $ligne = array_merge($ligne, array("type" => "2"));
        $ligne = array_merge($ligne, array("compteur" => $compteur));
        echo(json_encode($ligne));

    }
}





if(isset($ligne["type"]) AND $ligne["type"]== 3){ //PREV
    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];
    
    
    if($_POST['compteur'] == 0){ // si notre compteur est égal à zéro on va à la dernière radio

        $compteur = ($max['max']);
       
    }
    if(isset($_SESSION['idUtilisateur'])){
    $idUti = $_SESSION['idUtilisateur'];
    $compteur -=1;
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url,
    CASE 
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio 
    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti LIMIT :compteur,1');
    $select->bindValue('compteur',$compteur,PDO::PARAM_INT);
    $select->bindValue('idUti',$idUti,PDO::PARAM_INT);
    $select->execute();
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $ligne = array_merge($ligne, array("type" => "3"));
    $ligne = array_merge($ligne, array("compteur" => $compteur));
    echo(json_encode($ligne));
    }else{
        $compteur -=1;
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url
    FROM Radio  LIMIT :compteur,1');
    $select->bindValue('compteur',$compteur,PDO::PARAM_INT);
    $select->execute();
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $ligne = array_merge($ligne, array("type" => "3"));
    $ligne = array_merge($ligne, array("compteur" => $compteur));
    echo(json_encode($ligne));

    }
}
    
if(isset($ligne["type"]) AND $ligne["type"] == 5 ){ //Ecoute Radio

    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];
    $idUti = $_SESSION['idUtilisateur'];
    if($_POST['compteur'] != ($max['max']-1)){ // si le compteur est différent de la valeur max de radio (-1 car le compteur commence à 0 et max à 1)
        $compteur +=1;
    }
        else{
            $compteur = 0; // Si égal au max, on retourne à la première radios
        }
    
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url, 
    CASE 
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio 
    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = ? WHERE Radio.idRadio = ?'); // On stock les radios ainsi que celles aimé par l'utilisateur
    $select->execute(array($idUti,$idRadio));
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    $ligne = array_merge($ligne, array("type" => "5"));
    $ligne = array_merge($ligne, array("compteur" => $compteur));
    echo(json_encode($ligne));

}




if(isset($ligne["type"]) AND $ligne["type"] == 6 ){ //LIKE
    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];
    $idUti = $_SESSION['idUtilisateur'];
    $select = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url,
    CASE 
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio 
    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti LIMIT :compteur,1');
    $select->bindValue('compteur',$compteur,PDO::PARAM_INT);
    $select->bindValue('idUti',$idUti,PDO::PARAM_INT);
    $select->execute();
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    if($ligne['favoris'] == "true"): // Si l'utilisateur a en favoris la radios en cours
        $delete = $bdd->prepare('DELETE FROM favoris WHERE idRadio = ? AND idUtilisateur = ? '); // On supprime de la table favoris
        $delete->execute(array($idRadio,$idUti));
        $ligne['favoris'] = 'false';
    elseif($ligne['favoris'] == "false"):// Si l'utilisateur n'a pas en favoris la radios en cours
        $ins = $bdd->prepare('INSERT INTO favoris (idUtilisateur, idRadio) VALUES (?,?)');// On l'ajoute à la table favoris
        $ins->execute(array($idUti,$idRadio));
        $ligne['favoris'] = 'true';
    endif;
    $ligne = array_merge($ligne, array("type" => "6"));
    $ligne = array_merge($ligne, array("compteur" => intval($compteur))); // intval car la valeur envoyer est en string (je ne sais pas pourquoi)
    echo(json_encode($ligne));
}



if(isset($ligne["type"]) AND $ligne["type"] == 7 ){ //PLAY
    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];    
    $idUti = $_SESSION['idUtilisateur'];         
    $heureexiste = $bdd->prepare('SELECT * FROM Ecoute WHERE idUtilisateur = ? AND idRadio = ?');
    $heureexiste -> execute(array($idUti,$idRadio));
    $ligne = $heureexiste->fetch(PDO::FETCH_ASSOC);
        if($ligne == false) {
            $insertheure = $bdd -> prepare("INSERT INTO Ecoute(idUtilisateur,idRadio,derniereEcoute, dureeEcoute) VALUES(?,?,NOW(),NOW())");
            $insertheure -> execute(array($idUti,$idRadio));
        }
        if($ligne != false){
            $updateheure = $bdd -> prepare('UPDATE Ecoute SET dureeEcoute = NOW(),derniereEcoute = NOW() WHERE idUtilisateur = ? AND idRadio = ? ');
            $updateheure -> execute(array($idUti,$idRadio));
        }
        if($ligne['premiereEcoute']==NULL){
            $updateheure = $bdd -> prepare('UPDATE Ecoute SET premiereEcoute = NOW() WHERE idUtilisateur = ? AND idRadio = ? ');
            $updateheure -> execute(array($idUti,$idRadio));
        }
}

if(isset($ligne["type"]) AND $ligne["type"] == 8 ){ //PAUSE
    $idRadio = $_POST['idRadio'];
    $compteur = $_POST['compteur'];
    $idUti = $_SESSION['idUtilisateur'];
    $updateheure = $bdd -> prepare('UPDATE Ecoute SET diffDuree =  TIMESTAMPDIFF(SECOND,dureeEcoute,NOW()) WHERE idUtilisateur = ? AND idRadio = ? ');
    $updateheure -> execute(array($idUti,$idRadio));
    $updateEcoute = $bdd ->prepare('UPDATE Ecoute SET ecouteGlobal = ecouteGlobal + TIMESTAMPDIFF(MINUTE,dureeEcoute,NOW()) WHERE idUtilisateur = ? AND idRadio = ?');
    $updateEcoute -> execute(array($idUti,$idRadio));
    $updatePoints = $bdd -> prepare('UPDATE Utilisateur 
    SET Utilisateur.moodPoints = Utilisateur.moodPoints + ((SELECT Ecoute.diffDuree FROM Ecoute WHERE Utilisateur.idUtilisateur=Ecoute.idUtilisateur AND idRadio = ? )) WHERE idUtilisateur = ?');
    $updatePoints -> execute(array($idRadio,$idUti));

    $updateExp = $bdd -> prepare('UPDATE Utilisateur SET Utilisateur.experience = Utilisateur.experience + ((SELECT Ecoute.diffDuree FROM Ecoute WHERE Utilisateur.idUtilisateur=Ecoute.idUtilisateur AND idRadio = ?)) WHERE idUtilisateur = ?');
    $updateExp -> execute(array($idRadio,$idUti));

    $select = $bdd -> prepare('SELECT * FROM Utilisateur WHERE idUtilisateur = ?');
    $select -> execute(array($idUti));
    $ligne = $select->fetch(PDO::FETCH_ASSOC);

    if($ligne['experience'] >= ($ligne['niveauUti'] * 100)){
        $experience = (($ligne['experience']) - ($ligne['niveauUti'] * 100));
        $niveau = $ligne['niveauUti'] + 1;
        $updateNiveau = $bdd -> prepare('UPDATE Utilisateur SET niveauUti = ?, experience = ? WHERE idUtilisateur = ?');
        $updateNiveau -> execute(array($niveau,$experience, $idUti));
    } 



}
if(isset($ligne["type"]) AND $ligne["type"] == 9 ){

if(isset($_SESSION['idUtilisateur'])){
   $idUti =  $_SESSION['idUtilisateur'];
    $select = $bdd -> prepare('SELECT Utilisateur.pseudo, Mood.humeur,Utilisateur.photoProfil, Utilisateur.moodPoints,Utilisateur.experience,Utilisateur.niveauUti,Utilisateur.banniere
    FROM Utilisateur INNER JOIN possede ON Utilisateur.idUtilisateur = possede.idUtilisateur INNER JOIN Mood ON possede.idMood = Mood.idMood WHERE Utilisateur.idUtilisateur= ?');
    $select -> execute(array($idUti));
    $ligne = $select->fetch(PDO::FETCH_ASSOC);
    echo(json_encode($ligne));
}else{

    
    $ligne = array_merge($ligne, array("pseudo" => "GUEST"));
    $ligne = array_merge($ligne, array("moodPoints" => "0"));
    $ligne = array_merge($ligne, array("niveauUti" => "1"));
    $ligne = array_merge($ligne, array("experience" => "0"));
    $ligne = array_merge($ligne, array("photoProfil" => "1.svg"));
    $ligne = array_merge($ligne, array("banniere" => "2.webp"));
    $ligne = array_merge($ligne, array("humeur" => "Good Mood"));
    $ligne = array_merge($ligne, array("favoris1" => "1.webp"));
    $ligne = array_merge($ligne, array("favoris2" => "9.webp"));
    $ligne = array_merge($ligne, array("favoris3" => "20.webp"));
    echo(json_encode($ligne));
}

}

if(isset($ligne["type"]) AND $ligne["type"] == 10 ){
    $idUti =  $_SESSION['idUtilisateur'];
    $banniere = $_POST['srcbanniere'];
    $photoProfil = $_POST['srclogo'];

    $insert = $bdd -> prepare('UPDATE Utilisateur SET banniere = ?, photoProfil = ? WHERE idUtilisateur = ?');
    $insert -> execute(array($banniere,$photoProfil,$idUti));

}



// if(isset($ligne["type"]) AND $ligne["type"] == 11 ){
//     $srcImg = $_POST['srcItem'];
//     $prix = $_POST['prixItem'];
//     $idUti =  $_SESSION['idUtilisateur'];

//     $select = $bdd -> prepare('SELECT * FROM Objet WHERE photoObjet = ?');
//     $select -> execute(array($srcImg));
//     $idObjet = $select->fetch(PDO::FETCH_ASSOC);
//     $select = $bdd -> prepare('SELECT * FROM Objet WHERE idObjet = ? HAVING photoObjet =  ? AND prixObjet = ?');
//     $select -> execute(array($idObjet['idObjet'],$srcImg,$prix));
//     $true = $select->fetch(PDO::FETCH_ASSOC);

//     $select = $bdd -> prepare('SELECT * FROM Achete WHERE idObjet = ? AND idUtilisateur = ? ');
//     $select -> execute(array($idObjet['idObjet'],$idUti));
//     $ligne = $select->fetch(PDO::FETCH_ASSOC);

//     if($true != false AND $ligne == false){
        
//         $insert = $bdd -> prepare('INSERT INTO Achete(idUtilisateur,idObjet) VALUES (?,?)');
//         $insert -> execute(array($idUti,$idObjet['idObjet']));
//         $update = $bdd -> prepare('UPDATE Utilisateur SET moodPoints = moodPoints - ? WHERE idUtilisateur = ?');
//         $update -> execute(array($prix,$idUti));
//         $true = array_merge($true, array("resultat" => "true"));
//         $true = array_merge($true, array("test" => "bonne boucle"));
//         echo(json_encode($true));
//     }
//     if($true != false AND $ligne != false){
        
//         $true = array_merge($true, array("resultat" => "true"));
//         $true = array_merge($true, array("test" => "pas la bonne bu
//         oucle"));
//         echo(json_encode($true));
//     }
//     if($true == false){
//         $true = array_merge($true, array("resultat" => "false"));
//         echo(json_encode($true));
//     }
    
// }

?>


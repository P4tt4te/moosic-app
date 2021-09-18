<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header("Content-type: text/html; charset=UTF-8");
require (__DIR__ . "/param.inc.php");
session_start();
// Etape 1 : connexion au serveur de base de données
$bdd = new PDO("mysql:host=".MYHOST.";dbname=".MYDB, MYUSER, MYPASS) ;
$bdd->query("SET NAMES utf8");
$bdd->query("SET CHARACTER SET 'utf8'");
if(isset($_SESSION['idUtilisateur'])){
    $idUti = $_SESSION['idUtilisateur'];

    $acheteObjet = $bdd -> prepare('SELECT Objet.idObjet,Objet.nomObjet,Objet.prixObjet,Objet.photoObjet,Objet.idType,
    CASE
        WHEN Achete.idObjet IS NULL THEN "false"
        ELSE "true"
        END AS achete
    FROM Objet LEFT JOIN Achete ON Objet.idObjet = Achete.idObjet
    AND Achete.idUtilisateur = ?
    ORDER BY Objet.prixObjet '); // Affiche toutes les objets et une colonne "achete" qui sert à savoir si oui ou non l'utilsateur là acheté

    $acheteObjet -> execute(array($idUti));

    $achetePhoto = $bdd -> prepare('SELECT Objet.idObjet,Objet.nomObjet,Objet.prixObjet,Objet.photoObjet,Objet.idType,
    CASE
        WHEN Achete.idObjet IS NULL THEN "false"
        ELSE "true"
        END AS achete
    FROM Objet LEFT JOIN Achete ON Objet.idObjet = Achete.idObjet
    AND Achete.idUtilisateur = ?
    ORDER BY Objet.prixObjet ');// Pareil mais pour les photos de profil

    $achetePhoto -> execute(array($idUti));

    $favoris = $bdd->prepare('SELECT Radio.idRadio, nomRadio, photoRadio, Radio.url,Radio.categorie,
    CASE
        WHEN favoris.idUtilisateur IS NULL THEN "false"
        ELSE "true"
    END AS favoris
    FROM Radio

    LEFT JOIN favoris ON Radio.idRadio = favoris.idRadio AND favoris.idUtilisateur = :idUti
    HAVING favoris = "true"
    ORDER BY Radio.idRadio DESC
    LIMIT 0,3'); // Affiche les 3 dernières radios aimé par l'utilisateur
    $favoris->bindValue('idUti',$idUti,PDO::PARAM_INT);

    $select = $bdd -> prepare('SELECT SUM(Ecoute.ecouteGlobal) as ecouteGlobal FROM Ecoute WHERE Ecoute.idUtilisateur = ?'); // Sert à afficher l'écoute global d'un utilisateur 
    $select -> execute(array($idUti));
    $ecouteGlobal = $select -> fetch(PDO::FETCH_ASSOC);

    $radioPref = $bdd->prepare('SELECT *,SUM(Ecoute.ecouteGlobal) as ecouteMax FROM Ecoute INNER JOIN Radio ON Ecoute.idRadio = Radio.idRadio WHERE Ecoute.idUtilisateur = ?  GROUP BY Ecoute.idRadio ORDER BY ecouteMax DESC LIMIT 0,3'); // affiche les radios les plus écoutées par l'utilisateur
    $radioPref -> execute(array($idUti));

}else{
    $acheteObjet = $bdd -> prepare('SELECT * FROM Objet');
    $acheteObjet -> execute(); // Pour l'utilisateur non connecté
    $achetePhoto = $bdd -> prepare('SELECT * FROM Objet');
    $achetePhoto -> execute();// Pour l'utilisateur non connecté
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Moosic</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <link rel="icon" href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Ficon.ico?v=1622468860944">
    <link rel="stylesheet" href="style-accueil.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>

<body>
    <header>
        <div class="player player-off case">
            <img alt="image webradio" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fscale.svg?v=1622734351210" id="logo-hover">
            <img alt="agrandir" src="" id="logo-player">
        </div>
        <nav>
            <svg id="menu-burger" width="45" height="45" viewBox="0 0 384 384" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g id="menu1">
                    <path id="BARRE-BAS" d="M368 289H16C7.16797 289 0 277.352 0 263C0 248.648 7.16797 237 16 237H368C376.832 237 384 248.648 384 263C384 277.352 376.832 289 368 289Z" fill="#34314F" />
                    <path id="BARRE-MILLIEU" d="M368 197H16C7.16797 197 0 185.352 0 171C0 156.648 7.16797 145 16 145H368C376.832 145 384 156.648 384 171C384 185.352 376.832 197 368 197Z" fill="#34314F" />
                    <path id="BARRE-HAUT" d="M368 105H16C7.16797 105 0 93.3521 0 79C0 64.6479 7.16797 53 16 53H368C376.832 53 384 64.6479 384 79C384 93.3521 376.832 105 368 105Z" fill="#34314F" />
                </g>
            </svg>
            <ul>
                <li>
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/accueil.php?video=0">Accueil</a>
                </li>
                <li>
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/rechercher.php">Rechercher</a>
                </li>
                <li>
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/decouvrir.php">Découvrir</a>
                </li>
                <li>
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/mon-mood.php">Mon mood</a>
                </li>
                <li>
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/bibliotheque.php">Bibliothèque</a>
                </li>

            </ul>
            <div class="profil">
                <div class="profil-name">
                    <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/profil.php">
                        <svg width="67" height="78" viewbox="0 0 67 78" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle class="cercle-mood-stroke" cx="33.5" cy="44.5" r="32" stroke="#00FFA3" stroke-width="3" />
                            <image class='logo-profil' width="40" height="40" x="13.5" y="23" xlink:href="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmeditation.svg?v=1623682812929" alt="image logo profil" />
                            <circle class="cercle-mood-fill" cx="34" cy="7" r="7" fill="#00FFA3" />
                        </svg>
                    </a>
                    <span class="profil-name">LaZen53</span>
                </div>
                <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foptions.svg?v=1622464132733" class="profil-options" alt="engrenage" />
            </div>
        </nav>
        <div class="menu-options case none">
            <div class="title-options">
                <img class='engrenage-option' src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Foption.svg?v=1623851085548" alt="engrenage option">
                <p class="word-title-options">Options</p>
            </div>
            <div class="container-liste-options">
                <div class="edit-profil edit-item">
                    <img class="profil-edit-img" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fedition-profil.svg?v=1622469121712" alt="logo edition">
                    <p>Editer le profil</p>
                </div>
                <div class="creer-compte edit-item">
                    <img class="creer-compte-img" src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fuser.svg?v=1623851085549" alt="logo agrandir ou rétrécir">
                    <p>Inscription / Connexion</p>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="grid-container">
            <div class="carre-niveau case">
                <p class="bonjour bold">Bonjour</p>
                <span class="pseudo bold">Pseudo</span>
                <div class="div-niveau">
                    <p class="level bold">Niveau</p>
                    <span class="niveau-chiffres bold">34</span>
                    <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2FEllipse%207.png?v=1623051700572" class="cercle-profil" alt="cercle"/>
                </div>
            </div>
            <div class="mood-points case">
                <p class="points debutpoints bold">Vous avez</p>
                <span class="points-chiffres bold">45</span>
                <p class="points bold">Mood points</p>
            </div>

            <div class="heures-ecoutes case">
                <p class="temps-ecoute bold">
                    <span class="temps-ecoute-chiffres"><span class="temps-ecoute-heures"><?php if(isset($_SESSION['idUtilisateur'])){ echo($ecouteGlobal['ecouteGlobal']);  }else{$tempsEcoute = "0";echo($tempsEcoute);}?>min
                        </span>
                    </span>d'écoute
                </p>
            </div>
            <section class="shop case">
                <h2>Moodshop</h2>
                <div class="fond-lecteur">
                    <div class="texte-chrono">
                        <p class="texte-lecteur bold">Fonds Profil</p>

                    </div>

                    <div class="fond">
                        <svg class="fleche-fond fleche swiper-button-fondbib-prev" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1.88717 40.9655L35.7081 74.7859C36.4904 75.5687 37.5346 76 38.648 76C39.7614 76 40.8057 75.5687 41.5879 74.7859L44.0786 72.2958C45.6993 70.6732 45.6993 68.0361 44.0786 66.416L15.6783 38.0158L44.1101 9.58395C44.8923 8.8011 45.3242 7.75749 45.3242 6.64469C45.3242 5.53065 44.8923 4.48705 44.1101 3.70358L41.6194 1.21413C40.8365 0.431275 39.7929 4.83559e-07 38.6795 5.80898e-07C37.5661 6.78236e-07 36.5219 0.431275 35.7396 1.21414L1.88717 35.0654C1.10307 35.8507 0.672413 36.8992 0.674885 38.0139C0.672413 39.1329 1.10307 40.1808 1.88717 40.9655Z" fill="white" />
                        </svg>
                        <div class="imgs-fond swiper-container">
                            <div class="swiper-wrapper">
                                <?php while($ligne = $acheteObjet->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les bannieres
                                    if($ligne['idType'] == 2){

                                    ?>
                                <div class="div-fond1 swiper-slide div-fond-acc">
                                    <img class="image-fond img-fond" src="./imagesObjets/<?php echo($ligne['photoObjet']); ?> " alt="banniere" class="image-fond img-fond" data-id="<?php echo($ligne['idObjet']); ?>" data-achete="<?php echo($ligne['achete']); ?>">
                                    <div class="bloc-achat-fond">
                                        <p class="cout">
                                            <span class="cout-chiffres"><?php echo($ligne['prixObjet']); ?></span> mood
                                            points
                                        </p>
                                    </div>
                                </div>

                                <?php }
                            } ?>

                            </div>
                        </div>
                        <svg class="fleche-fond fleche swiper-button-fondbib-next" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M44.1128 35.0345L10.2919 1.21414C9.50963 0.431281 8.46541 0 7.35199 0C6.23857 0 5.19435 0.431281 4.41211 1.21414L1.92143 3.7042C0.300728 5.32675 0.300728 7.96387 1.92143 9.58395L30.3217 37.9842L1.88992 66.416C1.10768 67.1989 0.675781 68.2425 0.675781 69.3553C0.675781 70.4694 1.10768 71.513 1.88992 72.2964L4.3806 74.7859C5.16345 75.5687 6.20705 76 7.32048 76C8.4339 76 9.47812 75.5687 10.2604 74.7859L44.1128 40.9346C44.8969 40.1493 45.3276 39.1008 45.3251 37.9861C45.3276 36.8671 44.8969 35.8192 44.1128 35.0345Z" fill="white" />
                        </svg>
                    </div>

                    <div class="icone-profil">
                        <p class="texte-profil bold">Icônes Profil</p>

                        <div class="icone">
                            <svg class="fleche-icone fleche swiper-button-icones-prev" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1.88717 40.9655L35.7081 74.7859C36.4904 75.5687 37.5346 76 38.648 76C39.7614 76 40.8057 75.5687 41.5879 74.7859L44.0786 72.2958C45.6993 70.6732 45.6993 68.0361 44.0786 66.416L15.6783 38.0158L44.1101 9.58395C44.8923 8.8011 45.3242 7.75749 45.3242 6.64469C45.3242 5.53065 44.8923 4.48705 44.1101 3.70358L41.6194 1.21413C40.8365 0.431275 39.7929 4.83559e-07 38.6795 5.80898e-07C37.5661 6.78236e-07 36.5219 0.431275 35.7396 1.21414L1.88717 35.0654C1.10307 35.8507 0.672413 36.8992 0.674885 38.0139C0.672413 39.1329 1.10307 40.1808 1.88717 40.9655Z" fill="white" />
                            </svg>
                            <div class="liste-icones swiper-container">
                                <div class="swiper-wrapper">
                                    <?php while($ligne2 = $achetePhoto->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les photos de profil
                                        if($ligne2['idType'] == 1){
                                        ?>
                                    <div class="div-icone1 swiper-slide div-icone-acc">
                                        <img src="./imagesObjets/<?php echo($ligne2['photoObjet']); ?>" alt="icone" class="image-fond img-fond" data-id="<?php echo($ligne2['idObjet']); ?>" data-achete="<?php echo($ligne2['achete']); ?>">

                                        <div class="bloc-achat-icone">
                                            <p class="cout">
                                                <span class="cout-chiffres"><?php echo($ligne2['prixObjet']); ?></span>
                                                mood points
                                            </p>
                                        </div>
                                    </div>
                                    <?php }
                                    }
                                ?>
                                </div>
                            </div>
                            <svg class="fleche-icone fleche swiper-button-icones-next" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M44.1128 35.0345L10.2919 1.21414C9.50963 0.431281 8.46541 0 7.35199 0C6.23857 0 5.19435 0.431281 4.41211 1.21414L1.92143 3.7042C0.300728 5.32675 0.300728 7.96387 1.92143 9.58395L30.3217 37.9842L1.88992 66.416C1.10768 67.1989 0.675781 68.2425 0.675781 69.3553C0.675781 70.4694 1.10768 71.513 1.88992 72.2964L4.3806 74.7859C5.16345 75.5687 6.20705 76 7.32048 76C8.4339 76 9.47812 75.5687 10.2604 74.7859L44.1128 40.9346C44.8969 40.1493 45.3276 39.1008 45.3251 37.9861C45.3276 36.8671 44.8969 35.8192 44.1128 35.0345Z" fill="white" />
                            </svg>
                        </div>
                    </div>
                </div>
            </section>

            <div class="des-aleatoire case">
                <p class="hasard-txt bold">
                    Laissez les dés choisir une musique pour vous
                </p>
                <div class="img-de">
                    <svg class="svg-des" width="214" height="213" viewBox="0 0 214 213" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="lesdes">
                            <g id="desdroit">
                                <g id="droitde">
                                    <path id="depleindroit" d="M154.557 55.5215L112.127 66.8906C107.668 68.0851 105.023 72.6676 106.218 77.1254L117.587 119.556C118.781 124.014 123.363 126.659 127.821 125.465L170.252 114.096C174.71 112.901 177.355 108.319 176.161 103.861L164.792 61.4302C163.598 56.973 159.015 54.327 154.557 55.5215Z" fill="#34314F" />
                                    <path id="borddroit" d="M112.126 66.8908C109.139 67.6914 106.968 70.0137 106.219 72.7997C108.261 74.8376 111.302 75.7634 114.29 74.9628L147.975 65.9366C152.805 64.6426 157.769 67.5086 159.063 72.3382L168.09 106.024C168.89 109.011 171.212 111.183 173.998 111.932C176.036 109.89 176.962 106.848 176.162 103.861L164.792 61.4307C163.598 56.9726 159.016 54.3272 154.558 55.5218L112.126 66.8908Z" fill="#2A273F" />
                                </g>
                                <g id="trois">
                                    <path id="troisdroit" d="M162.807 99.3624C162.807 95.901 160.001 93.0949 156.539 93.0949C153.078 93.0949 150.272 95.901 150.272 99.3624C150.272 102.824 153.078 105.63 156.539 105.63C160.001 105.63 162.807 102.824 162.807 99.3624Z" fill="white" />
                                    <path id="deuxdroit" d="M147.458 90.4936C147.458 87.0321 144.652 84.2261 141.19 84.2261C137.729 84.2261 134.923 87.0321 134.923 90.4936C134.923 93.955 137.729 96.761 141.19 96.761C144.652 96.761 147.458 93.955 147.458 90.4936Z" fill="white" />
                                    <path id="undroit" d="M132.104 81.6345C132.104 78.1731 129.298 75.367 125.836 75.367C122.375 75.367 119.569 78.1731 119.569 81.6345C119.569 85.0959 122.375 87.9019 125.836 87.9019C129.298 87.9019 132.104 85.0959 132.104 81.6345Z" fill="white" />
                                </g>
                            </g>
                            <g id="desgauche">
                                <g id="gauchede">
                                    <path id="depleingauche" d="M49.6682 94.4647L38.2992 136.895C37.1046 141.353 39.7503 145.935 44.2081 147.13L86.6386 158.499C91.0967 159.694 95.6788 157.048 96.8734 152.59L108.242 110.16C109.437 105.702 106.791 101.12 102.333 99.9251L59.903 88.5558C55.4452 87.3609 50.863 90.0066 49.6682 94.4647Z" fill="#34314F" />
                                    <path id="bordgauche" d="M51.832 90.7188C52.5813 93.5048 54.753 95.8272 57.7401 96.6272L91.4263 105.653C96.2558 106.947 99.1219 111.912 97.8278 116.741L88.8017 150.427C88.0005 153.416 88.9266 156.456 90.9644 158.498C93.7505 157.749 96.0733 155.578 96.8734 152.59L108.242 110.16C109.437 105.702 106.791 101.119 102.333 99.9248L59.9029 88.5555C56.9158 87.7549 53.874 88.681 51.832 90.7188Z" fill="#2A273F" />
                                </g>
                                <g id="quatre">
                                    <path id="quatregauche" d="M64.1882 132.393C64.1882 128.932 61.3822 126.126 57.9208 126.126C54.4594 126.126 51.6533 128.932 51.6533 132.393C51.6533 135.854 54.4594 138.661 57.9208 138.661C61.3822 138.661 64.1882 135.854 64.1882 132.393Z" fill="white" />
                                    <path id="troisgauche" d="M70.6743 108.175C70.6743 104.714 67.8683 101.907 64.4069 101.907C60.9454 101.907 58.1394 104.714 58.1394 108.175C58.1394 111.636 60.9454 114.442 64.4069 114.442C67.8683 114.442 70.6743 111.636 70.6743 108.175Z" fill="white" />
                                    <path id="deuxgauche" d="M88.402 138.88C88.402 135.419 85.5959 132.613 82.1345 132.613C78.6731 132.613 75.8671 135.419 75.8671 138.88C75.8671 142.342 78.6731 145.148 82.1345 145.148C85.5959 145.148 88.402 142.342 88.402 138.88Z" fill="white" />
                                    <path id="ungauche" d="M94.8931 114.666C94.8931 111.205 92.087 108.399 88.6256 108.399C85.1642 108.399 82.3582 111.205 82.3582 114.666C82.3582 118.128 85.1642 120.934 88.6256 120.934C92.087 120.934 94.8931 118.128 94.8931 114.666Z" fill="white" />
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>

            <div class="meilleures-webradios case">
                <p class="meilleures-txt">Vos meilleures webradios</p>
                <div class="imgs-meilleures-webradios">
                    <svg class="fleche fleche-best-radio swiper-button-best-prev" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.88717 40.9655L35.7081 74.7859C36.4904 75.5687 37.5346 76 38.648 76C39.7614 76 40.8057 75.5687 41.5879 74.7859L44.0786 72.2958C45.6993 70.6732 45.6993 68.0361 44.0786 66.416L15.6783 38.0158L44.1101 9.58395C44.8923 8.8011 45.3242 7.75749 45.3242 6.64469C45.3242 5.53065 44.8923 4.48705 44.1101 3.70358L41.6194 1.21413C40.8365 0.431275 39.7929 4.83559e-07 38.6795 5.80898e-07C37.5661 6.78236e-07 36.5219 0.431275 35.7396 1.21414L1.88717 35.0654C1.10307 35.8507 0.672413 36.8992 0.674885 38.0139C0.672413 39.1329 1.10307 40.1808 1.88717 40.9655Z" fill="white" />
                    </svg>
                    <div class="swiper-container best-radio">
                        <div class="swiper-wrapper div-swipe">
                            <?php 
                            if(isset($_SESSION['idUtilisateur'])){ 
                                while($ligne = $radioPref->fetch(PDO::FETCH_ASSOC)) { // boucle qui parcours le tableau pour afficher les radios préféré de l'utilisateur
                            ?>
                            <div class="swiper-slide">
                                <a href="profil-radio.php?id=<?php echo($ligne['idRadio']);?>">
                                    <img class="image-best-webradio" src="./images/<?php echo($ligne['photoRadio']); ?>" alt="meilleure webradio" />
                                </a>
                                <div class="radio-txt">
                                    <p><?php echo($ligne['nomRadio']); ?></p>
                                    <span><?php echo($ligne['categorie']); ?></span>
                                </div>
                            </div>
                            <?php }
                            }else{?>
                            <div class="swiper-slide">
                                <img class="image-best-webradio" src=" " alt="meilleure webradio" />
                            </div>
                            <div class="swiper-slide">
                                <img class="image-best-webradio" src=" " alt="meilleure webradio" />
                            </div>
                            <div class="swiper-slide">
                                <img class="image-best-webradio" src=" " alt="meilleure webradio" />
                            </div>


                            <?php }?>
                        </div>
                    </div>
                    <svg class="fleche fleche-best-radio swiper-button-best-next" width="46" height="76" viewBox="0 0 46 76" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M44.1128 35.0345L10.2919 1.21414C9.50963 0.431281 8.46541 0 7.35199 0C6.23857 0 5.19435 0.431281 4.41211 1.21414L1.92143 3.7042C0.300728 5.32675 0.300728 7.96387 1.92143 9.58395L30.3217 37.9842L1.88992 66.416C1.10768 67.1989 0.675781 68.2425 0.675781 69.3553C0.675781 70.4694 1.10768 71.513 1.88992 72.2964L4.3806 74.7859C5.16345 75.5687 6.20705 76 7.32048 76C8.4339 76 9.47812 75.5687 10.2604 74.7859L44.1128 40.9346C44.8969 40.1493 45.3276 39.1008 45.3251 37.9861C45.3276 36.8671 44.8969 35.8192 44.1128 35.0345Z" fill="white" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="sound-number" style="display:none">
            <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fvolume-on.svg?v=1622727665479" alt="logo-volume">
            <span>0</span>
        </div>
        <img src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Fmontagne.webp?v=1622467589036" class="fond-montagne" alt="fond ecran">
        <div class="container-player">
            <div class="dropzone-top">
            </div>
            <div class="dropzone-bottom">
            </div>
        </div>
        <?php if($_GET['video']==1){?>
        <!--  On regarde si dans l'url "video" vaut 1 ou 0, si il vaut 1, on affiche la vidéo   -->
        <div class="tutoriel">
            <div class="quitter case">
                Quitter
            </div>
            <div class="video-tuto">
                <iframe id="tuto" class="video-tuto" src="https://www.youtube.com/embed/y5CrcO0Jbuc?controls=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <div class="quitterb case">
                Quitter
            </div>
        </div>
        <?php }?>
        <div class="loader">
            <img class='logo-loader' src="https://cdn.glitch.com/635f7807-931c-4359-955f-e5a30b567eb1%2Flogo-moosic-chargement.svg?v=1623660668507" alt="logo-moosic-chargement">
        </div>
    </main>
    <footer>
        <div class="footer-links">
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/quisommesnous.html">Qui-sommes-nous ?</a>
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/faq.html">FAQ</a>
            <a href="https://la-projets.univ-lemans.fr/~mmi1pj03/cgu.html">Conditions d'utilisations</a>
        </div>
        <p class="footer-name">Moosic -> 2021</p>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/interactjs/dist/interact.min.js"></script>
<script src="script.js"></script>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="script-accueil.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="swiper.js"></script>
<script src="player.js"></script>
<?php if($_GET['video']==1){?>
<script src="tutoriel.js"></script>
<?php }?>

</html>
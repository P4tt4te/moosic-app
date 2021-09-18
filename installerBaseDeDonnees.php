<?php
    // installerBaseDeDonnees.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    require (__DIR__ . "/param.inc.php");
    
    // Le fichier contient la séquence de requêtes SQL.
    // Chaque requête se termine par le caractère ';'.
    $fichierDatabaseScript = "database.sql";
    
    try {
        // Etape 1 : connexion au serveur de base de données
        $pdo = new PDO("mysql:host=" . MYHOST, MYUSER, MYPASS);
        $pdo->query("CREATE DATABASE IF NOT EXISTS " . MYDB . " DEFAULT CHARACTER SET utf8 COLLATE utf8_bin");
        $pdo = null;
        $pdo = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
        $pdo->query("SET NAMES utf8");
        $pdo->query("SET CHARACTER SET 'utf8'");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Etape 2 : envoi les requêtes au serveur MySQL
        $lecteur = new SplFileObject($fichierDatabaseScript, 'r');
        $requete = "";
        while ($lecteur->eof() == false) {
            $ligne = $lecteur->fgets();
            if (substr($ligne, 0, 1) != "#") {
                $requete = $requete . $ligne;
                // Il est necessaire d'appeler la méthode query pour chaque requête.
                if (strpos($ligne, ";")) {
                    $pdo->query($requete);
                    $requete = "";
                }
            }
        }
        $lecture = null;
        // Etape 3 : traitement du résultat d’une requête (aucun résultat)
        // Etape 4 : ferme la connexion au serveur de base de données
        $pdo = null;
    } catch (exception $e) {
        echo ("Exception :" . $e->getMessage());
    }
    header("Content-type: text/html; charset=UTF-8");
?><!DOCTYPE html>
<html>
<head>
<title>installerBaseDeDonnees.php</title>
</head>
<body>
	<code>
		Tout se passe du côté serveur de base de données.<br>
Les tables de la base de données "<?php echo(MYDB) ; ?>" ont été créées.<br>
		Sur la-perso.univ-lemans.fr, l'application <a
			href="https://la-projets.univ-lemans.fr/pj-pma" target="_blank">phpmyadmin</a>
		vous permet de vérifier.
	</code>
</body>
</html>
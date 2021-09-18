<?php
	// question7.php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	header("Content-type: text/html; charset=UTF-8");
?><!DOCTYPE html>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   </head>
	<body>
        <?php
   if(isset($_GET["urlPisteMp3Select"])) { ?>       
   <audio controls>
     <source src="<?php echo($_GET["urlPisteMp3Select"]); ?>" preload="auto">
    </audio> 
    <?php }        
?>
		<form action="" method="get">
			<select name="urlPisteMp3Select">
<?php
	$lecteur=new SplFileObject("compteurs.txt",'r') ;
	while($lecteur->eof()==false) {
		$ligne = $lecteur->fgets() ;
		if ($ligne != "") {
			$tab=explode( ";",$ligne) ;
			$titrePisteMp3=$tab[0] ;
			$urlPisteMp3=$tab[1] ;
?>
				<option value="<?php echo($urlPisteMp3); ?>"><?php echo($titrePisteMp3); ?></option>
                
<?php
		}
        
	}
	$lecteur=null ;
?>
			</select>
			<input type="submit" value="Envoi !"/>
		</form>
	</body>
</html>
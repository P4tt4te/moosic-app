<?php
   // deconnexion.php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
	session_start();

	 session_unset();//$_SESSION =  null ;
	 session_destroy();
	header("Location: index.php");
?>

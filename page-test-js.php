<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
header("Content-Type: text/html; charset=utf-8");
require (__DIR__ . "/param.inc.php");
?>



<script type="text/javascript">
<?php
echo "alert('Hello World!');";
?>
</script>


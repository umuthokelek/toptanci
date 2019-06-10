<?php

session_start();
unset($_SESSION["toptanciad"]);
unset($_SESSION["toptanciid"]);
//session_destroy();
header("Location: index.php");
exit;

?>
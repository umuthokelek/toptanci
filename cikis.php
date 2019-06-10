<?php

session_start();
//session_destroy();
unset($_SESSION["subead"]);
unset($_SESSION["subeid"]);
header("Location: index.php");
exit;

?>
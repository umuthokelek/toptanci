<?php
session_start();

if (isset($_SESSION["toptanciid"])) {
  require("panel.php");
}
else {
  require("giris.php");
}

?>
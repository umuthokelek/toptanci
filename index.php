<?php
session_start();

if (isset($_SESSION["subeid"])) {
	require("siparistakip.php");
}
else {
	require("giris.php");
}

?>
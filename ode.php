<?php
session_start();
require 'config.php';

$id = $_GET["id"];

$ode = $db->prepare("UPDATE siparisler SET odeme='Ödendi' WHERE id='$id'");
$ode->execute();

?>
<?php
require '../config.php';
require '../replace_tr.php';

$ad = strtoupper(replace_tr(trim($_POST["ad"])));
$sifre = trim($_POST["sifre"]);

$sube = $db->prepare("INSERT INTO subeler(ad,sifre) VALUES('$ad','$sifre')");
$sube->execute();
header('Location: index.php');

?>
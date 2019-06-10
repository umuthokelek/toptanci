<?php
require '../config.php';
require '../replace_tr.php';

$ad = strtoupper(replace_tr(trim($_POST["ad"])));
$sifre = trim($_POST["sifre"]);

$toptanci = $db->prepare("INSERT INTO toptancilar(ad,sifre) VALUES('$ad','$sifre')");
$toptanci->execute();
header('Location: index.php');

?>
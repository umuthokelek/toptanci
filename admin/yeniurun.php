<?php
require '../config.php';

$ad = $_POST["ad"];
$stok = $_POST["stok"];
$fiyat = $_POST["fiyat"];

$urun = $db->prepare("INSERT INTO urunler(ad,stok,fiyat) VALUES('$ad','$stok','$fiyat')");
$urun->execute();
header('Location: index.php');

?>
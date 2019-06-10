<?php
session_start();
require '../config.php';

$urun = $_POST["urun"];
$adet = $_POST["adet"];
$sube = $_POST["sube"];
$odeme = $_POST["odeme"];


$sorgu = $db->prepare("SELECT fiyat,stok FROM urunler WHERE id = '$urun'");
$sorgu->execute();
$row = $sorgu->fetch(PDO::FETCH_ASSOC);
$stok = $row['stok'];
//$fiyat = substr($row['fiyat'],1);
$fiyat = $row['fiyat'];
$ucret = $adet*$fiyat;

date_default_timezone_set("Europe/Istanbul");
$tarih = date("Y-m-d");


if(($stok > 0) && ($adet < $stok)) {
	if (isset($odeme)) {
		$ode = "Ödendi";
	}
	else {
		$ode = "Ödenmedi";
	}

	$siparis = $db->prepare("INSERT INTO siparisler(sube,urun,adet,ucret,tarih,odeme) VALUES('$sube','$urun','$adet','$ucret','$tarih','$ode')");
    $siparis->execute();
    if($siparis) {
		$stokdus = $db->prepare("UPDATE urunler SET stok = stok - $adet WHERE id = '$urun'");
    	$stokdus->execute();
	}
    header("Location: index.php");
}

else {
	echo 'Stok yetersiz!';
}



?>
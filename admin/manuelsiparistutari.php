<?php
require '../config.php';

$urun = intval($_GET['urun']);
$adet = intval($_GET['adet']);

$sorgu = $db->prepare("SELECT fiyat FROM urunler WHERE id = '$urun'");
$sorgu->execute();
$row = $sorgu->fetch(PDO::FETCH_ASSOC);
$fiyat = $row['fiyat'];

echo "<strong>Toplam tutar: </strong>";
echo $fiyat*$adet;
echo "₺";
?>

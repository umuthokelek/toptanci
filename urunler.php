<?php


function urunleri_getir() {

$urunler = $db->prepare("SELECT id,ad FROM urunler;");
$urunler->execute();

while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {

	echo "<option value='" . $row['ad'] . "'>" . $row['ad'];
}

}



?>
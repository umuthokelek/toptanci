<html>

<?php
require '../config.php';
require '../usd_to_try.php';

$sube = $_GET['id'];

$sorgu = $db->prepare("SELECT * FROM subeler WHERE id = '$sube'");
$sorgu->execute();

while($row = $sorgu->fetch(PDO::FETCH_ASSOC)) {
    $subead = $row['ad'];
}

echo "<h1>UVM TOPTANCILIK LTD. ŞTİ.</h1>";

echo "<h1>" . $subead . " ŞUBESİ FATURASI</h1>";

$faturaTarihi = date('d/m/Y');
echo $faturaTarihi;

echo '<table border="1" width="100%">
<thead>
<tr>
<th>ÜRÜN ADI</th>
<th>ÜRÜN FİYATI</th>
</tr>
</thead>
<tbody>';

$toplam = 0;

$siparisler = $db->prepare("
  SELECT siparisler.id,siparisler.tarih,urunler.ad uad,siparisler.adet,siparisler.ucret,siparisler.odeme 
  FROM siparisler 
  INNER JOIN subeler ON siparisler.sube = subeler.id 
  INNER JOIN urunler ON siparisler.urun = urunler.id
  WHERE subeler.id = '$sube'
  ORDER BY id ASC");
$siparisler->execute();

while($row = $siparisler->fetch(PDO::FETCH_ASSOC)) {
    $tarih = $row['tarih'];
    $tarih1 = strtotime($tarih);
    $bugun = strtotime(date('Y-m-d'));
    $fark = $bugun - $tarih1;
    $teslimgunu = 3 - ($fark / 86400);
    $odeme = $row['odeme'];

    if(!($fark >= 259200) && $odeme == "Ödenmedi") {
        echo "<tr>";

        $adet = $row['adet'];

        $urun = $row['uad'];
        echo "<td>" . $urun . " x " . $adet . "</td>";
    
        $ucret = $row['ucret'];
        $toplam += $ucret;
        echo "<td>" . usd_to_try($ucret) . "</td>";

        echo "</tr>";
    }
}

$kdv = ($toplam / 100)*8;

echo "<tr>";
echo "<td>ARA TOPLAM</td>";
echo "<td>" . usd_to_try(round($toplam-$kdv,2)) . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td>KDV TUTARI</td>";
echo "<td>" . usd_to_try(round($kdv,2)) . "</td>";
echo "</tr>";

echo "<tr>";
echo "<td>GENEL TOPLAM</td>";
echo "<td>" . usd_to_try($toplam) . "</td>";
echo "</tr>";
echo '</tbody>
</table>';
?>

<button onclick="window.print()">Yazdır</a>
<button onclick="window.close()">Pencereyi Kapat</a>

</html>
<?php
session_start();
require '../config.php';
require '../usd_to_try.php';

if(isset($_SESSION["toptanciid"])) {

$urunid = $_POST["urun"];

$sorgu = $db->prepare("SELECT * FROM urunler WHERE id = '$urunid'");
$sorgu->execute();
$row = $sorgu->fetch(PDO::FETCH_ASSOC);
$urunad = $row['ad'];

echo '
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/style.css"/>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico"/>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <title>Ürün Raporu | UVM Toptancılık</title>

    <script>
    function cikisonay() {
    if (window.confirm("Çıkış yapmak istediğinizden emin misiniz?")) { 
      window.location.href = "cikis.php";
    }
    else {
      //
    }
    }
    </script>
  </head>
  <body>
  <div class="container">
  <a href="index.php"><img class="img-responsive center-block" src="../images/logo.png" style="width:75%" title="UVM Toptancılık"/></a>

  <div class="pull-right">Hoş geldin, ' . $_SESSION["toptanciad"] . '.</div><br>
        <a class="btn btn-danger pull-right" onClick="cikisonay()"><span class="glyphicon glyphicon-log-out"></span> Çıkış Yap</a>        
        <a class="btn btn-success pull-right" href="profiliduzenle.php" style="margin-right: 5px"><span class="glyphicon glyphicon-edit"></span> Profili Düzenle</a>
        <a class="btn btn-primary pull-right" href="index.php" style="margin-right: 5px"><span class="glyphicon glyphicon-home"></span> Ana Sayfa</a>


<h1> ' . $urunad . ' Ürünü </h1>';

$urun = $db->prepare("SELECT * FROM urunler WHERE id='$urunid'");
$urun->execute();


while ($row = $urun->fetch(PDO::FETCH_ASSOC)) {
    
    $id = $row['id'];
  
    $ad = $row['ad'];

    $fiyat = $row['fiyat'];
    $tlfiyat = usd_to_try($fiyat);

  
    $stok = $row['stok'];

    $satis = $db->prepare("SELECT sum(adet) FROM siparisler WHERE urun='$urunid'");
    $satis->execute();
    $satissayisi = $satis->fetch(PDO::FETCH_ASSOC);
    $toplamsatis = $satissayisi['sum(adet)'];
    if(!isset($toplamsatis)) {
      $toplamsatis = 0;
    }
  
}



$ilkstok = $stok + $toplamsatis;
$satisorani = ($toplamsatis/$ilkstok)*100;
$gelir = $toplamsatis*$tlfiyat;


echo '<table class="table table-striped">
<tr>
<th>ÜRÜN ID</th>';
echo '<td>' . $id . '</td> </tr>';

echo '<tr>
<th>ÜRÜN ADI</th>
<td>' . $ad . '</td> </tr>';

echo '<tr>
<th>ÜRÜN FİYATI</th>
<td>' . $tlfiyat . '</td> </tr>';

echo '<tr>
<th>ÜRÜN STOĞU</th>
<td>' . $stok . '</td> </tr>';

echo '<tr>
<th>SATIŞ SAYISI</th>
<td>' . $toplamsatis . '</td> </tr>';

echo '<tr>
<th>SATIŞ ORANI</th>
<td>%' . round($satisorani,2) . '</td> </tr>';

echo '<tr>
<th>BRÜT GELİR</th>
<td>' . $gelir . '₺</td> </tr>';

echo '
<tbody>';


echo '</tbody>
</table>

</div>
</body>
</html>';

}
else {
  header("Location: index.php");
}
?>
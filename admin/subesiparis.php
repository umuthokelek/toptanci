<?php
session_start();
require '../config.php';
require '../usd_to_try.php';

if(isset($_SESSION["toptanciid"])) {

$subeid = $_POST["sube"];

$sorgu = $db->prepare("SELECT * FROM subeler WHERE id = '$subeid'");
$sorgu->execute();
$row = $sorgu->fetch(PDO::FETCH_ASSOC);
$subead = $row['ad'];

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
    <title>Şube Sipariş Takibi | UVM Toptancılık</title>

    <script>
    function cikisonay() {
    if (window.confirm("Çıkış yapmak istediğinizden emin misiniz?")) { 
      window.location.href = "cikis.php";
    }
    }
    function fatura(sube) {
      pencere = open("subefatura.php?id="+sube,"fatura","width=800,height=600");    
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


<h1> ' . $subead . ' Şubesi Siparişleri </h1>
<table class="table table-striped">

<thead class="thead-dark">
<tr>
<th>SİPARİŞ ID</th>
<th>SİPARİŞ TARİHİ</th>
<th>ÜRÜN ADI</th>
<th>ADET</th>
<th>ÜCRET</th>
<th>ÖDEME DURUMU</th>
<th>TESLİM DURUMU</th>
</tr>
</thead>
<tbody>';

$siparisler = $db->prepare("
  SELECT siparisler.id,siparisler.tarih,urunler.ad uad,siparisler.adet,siparisler.ucret,siparisler.odeme 
  FROM siparisler 
  INNER JOIN subeler ON siparisler.sube = subeler.id 
  INNER JOIN urunler ON siparisler.urun = urunler.id
  WHERE subeler.id = '$subeid'
  ORDER BY id ASC");
$siparisler->execute();

$yolsiparis = 0;
$odenmemis = 0;

while ($row = $siparisler->fetch(PDO::FETCH_ASSOC)) {

  echo "<tr>";
  $id = $row['id'];
  echo "<td>" . $id . "</td>";

  $tarih = $row['tarih'];
  $ftarih =   date('d.m.Y', strtotime($tarih));
  echo "<td>" . $ftarih . "</td>";

  $urun = $row['uad'];
  echo "<td>" . $urun . "</td>";

  $adet = $row['adet'];
  echo "<td>" . $adet . "</td>";

  $ucret = $row['ucret'];
  echo "<td>" . usd_to_try($ucret) . "</td>";

  $odeme = $row['odeme'];
  echo "<td>" . $odeme . "</td>";
  if($odeme == "Ödenmedi") {
    $odenmemis++;
  }

  $tarih1 = strtotime($tarih);
  $bugun = strtotime(date('Y-m-d'));
  $fark = $bugun - $tarih1;
  $teslimgunu = 3 - ($fark / 86400);

  if($fark >= 259200)
  {
    if($teslimgunu == 0) 
    {
        echo "<td title='Bugün'>Teslim edildi</td>";
    }
    else {
        echo "<td title='" . substr($teslimgunu,1) . " gün önce'>Teslim edildi</td>";
    }
  }
  else {
    echo "<td title='". $teslimgunu . " gün kaldı'>Yolda</td>";
    $yolsiparis++;
  }
  echo "</tr>";
}

$sorgu = $db->prepare("SELECT sum(ucret) FROM siparisler WHERE sube = '$subeid' AND odeme = 'Ödenmedi'");
$sorgu->execute();

$toplam = $sorgu->fetch(PDO::FETCH_ASSOC);
$sonuc = $toplam['sum(ucret)']; //sorgudan gelen alanin adi sum

if(isset($sonuc)) {
  echo "<td colspan='7' style='padding-left:75%'>Toplam Alacak: <strong>" . usd_to_try($sonuc) . "</strong>
  <br> Şubeye teslim edilmemiş<strong> " . $yolsiparis . " </strong> sipariş var.
  <br> Tahsil edilmemiş<strong> " . $odenmemis . "</strong> ödeme var.</td>";
}
else {
  echo "<td colspan='7' style='padding-left:75%'>Toplam Alacak: <strong>0₺</strong>
  <br> Şubeye teslim edilmemiş<strong> " . $yolsiparis . " </strong> sipariş var.
  <br> Tahsil edilmemiş<strong> " . $odenmemis . "</strong> ödeme var.</td>";
}


echo '</tbody>
</table>

<a class="btn btn-primary" onclick="fatura(' . $subeid . ')"><span class="glyphicon glyphicon-file"></span> Şube Faturası</a>

</div>
</body>
</html>';

}
else {
  header("Location: index.php");
}
?>
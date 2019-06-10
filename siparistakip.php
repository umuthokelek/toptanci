<?php
session_start();
require 'config.php';
require 'usd_to_try.php';

if(isset($_SESSION["subeid"])) {

$subeid = $_SESSION["subeid"];

echo '
<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico"/>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>Şube Sipariş Takibi | UVM Toptancılık</title>

    <script>
    function cikisonay() {
		if (window.confirm("Çıkış yapmak istediğinizden emin misiniz?")) { 
			window.location.href = "cikis.php";
		}
	}
    function formDogrula() {
      var urun = document.forms["yenisiparisform"]["urun"].value;
      var adet = document.forms["yenisiparisform"]["adet"].value;
      if (urun == "" || adet == "") {
        alert("Lütfen boş geçmeyiniz");
        return false;
      }
    } 
    function siparisTutari(urun,adet) {
      if (urun == "" || adet == "") {
        document.getElementById("tutar").innerHTML="";
        return;
      }
      if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
      } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          document.getElementById("tutar").innerHTML=this.responseText;
        }
      }
      xmlhttp.open("GET","siparistutari.php?urun="+urun+"&adet="+adet,true);
      xmlhttp.send();
    }
    function odemeOnay(siparis,ucret) {
      if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
      } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      var onay = confirm(ucret + "₺\'lik ödeme gerçekleşecek, \nişlemi onaylıyor musunuz?");
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          location.reload();
        }
      }
      if(onay) {
        xmlhttp.open("GET","ode.php?id="+siparis,true);
        xmlhttp.send();
      }
    }
    function fatura() {
      pencere = open("faturam.php","fatura","width=800,height=600");    
    }
    function sayiKontrol(evt)
    {
      var e = evt || window.event;
      var charCode = e.which || e.keyCode;                        
      if (charCode > 31 && (charCode < 47 || charCode > 57))
      return false;
      if (e.shiftKey) return false;
      return true;
    }
    </script>

  </head>
  <body>
  <div class="container">
  <a href="index.php"><img class="img-responsive center-block" src="images/logo.png" style="width:75%" title="UVM Toptancılık"/></a>

  <div class="pull-right">Hoş geldin, ' . $_SESSION["subead"] . '.</div><br>
        <a class="btn btn-danger pull-right" onClick="cikisonay()"><span class="glyphicon glyphicon-log-out"></span> Çıkış Yap</a>
        <a class="btn btn-success pull-right" href="profiliduzenle.php" style="margin-right: 5px"><span class="glyphicon glyphicon-edit"></span> Profili Düzenle</a>
        <a class="btn btn-primary pull-right" href="index.php" style="margin-right: 5px"><span class="glyphicon glyphicon-home"></span> Ana Sayfa</a>


<h1> ' . $_SESSION["subead"] . ' Şubesi Siparişleri </h1>
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
  if($odeme == "Ödendi") {
      echo "<td>" . $odeme . "</td>";
  }
  else {
    //echo "<td> <a class='btn btn-warning' href='ode.php?id=". $id . "'><span class='glyphicon glyphicon-check'></span> Öde</a> </td>";
    echo "<td> <a class='btn btn-warning' onclick='odemeOnay(" . $id . "," . $ucret . ")'><span class='glyphicon glyphicon-check'></span> Öde</a> </td>";    
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
$sonuc = $toplam['sum(ucret)']; //sorgudan gelen alanin adi mysql'de sum(ucret)
if(isset($sonuc)) {
  echo "<td colspan='7' style='padding-left:75%'>Toplam Borcunuz: <strong>" . usd_to_try($sonuc) . "</strong> 
  <br> Teslim edilmemiş<strong> " . $yolsiparis . " </strong>siparişiniz var.
  <br> Ödeme bekleyen<strong> " . $odenmemis . "</strong> siparişiniz var.</td>";
}
else {
  echo "<td colspan='7' style='padding-left:75%'>Toplam Borcunuz: <strong>0₺</strong> 
  <br> Teslim edilmemiş<strong> " . $yolsiparis . " </strong>siparişiniz var.
  <br> Ödeme bekleyen<strong> " . $odenmemis . "</strong> siparişiniz var.</td>";
}


echo '</tbody>
</table>

<a class="btn btn-success" data-toggle="modal" data-target="#YeniSiparisModal"><span class="glyphicon glyphicon-plus"></span> Yeni Sipariş</a> </td>
<a class="btn btn-primary" onclick="fatura()"><span class="glyphicon glyphicon-file"></span> Faturam</a> </td>

<div id="YeniSiparisModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-ku">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yeni Sipariş</h4>
      </div>
      <div class="modal-body">


  <form name="yenisiparisform" method="post" action="yenisiparis.php" onsubmit="return formDogrula()">

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Ürün Seçiniz</label>
      <select name="urun" onchange="siparisTutari(this.value,form.adet.value)">';

      $urunler = $db->prepare("SELECT id,ad,fiyat FROM urunler ORDER BY id ASC");
      $urunler->execute();

  while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {
    echo "<option class='form-control' value='" . $row['id'] . "'>" . $row['ad'] . " (" . usd_to_try($row['fiyat']) . ")";
  }

      echo '</select>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Adet</label>
      <input type="text" class="form-control" name="adet" id="ex1" autocomplete="off" onkeyup="siparisTutari(form.urun.value,this.value)" onkeypress="return sayiKontrol(event)">
      </div>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="odeme"> Şimdi Öde</label>
    </div>

    <p id="tutar"></p>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Siparişi Tamamla">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>


</div>
</body>
</html>';

}
else {
  header("Location: index.php");
}
?>
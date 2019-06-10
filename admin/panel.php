<?php
session_start();
require '../config.php';
require '../usd_to_try.php';

if(isset($_SESSION["toptanciid"])) {

$toptanciid = $_SESSION["toptanciid"];

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
    <title>Toptancı Admin Paneli | UVM Toptancılık</title>

    <script>
    function cikisonay() {
    if (window.confirm("Çıkış yapmak istediğinizden emin misiniz?")) { 
      window.location.href = "cikis.php";
    }
  }
  function manuelSiparisFormDogrula() {
    var urun = document.forms["manuelsiparisform"]["urun"].value;
    var sube = document.forms["manuelsiparisform"]["sube"].value;
    var adet = document.forms["manuelsiparisform"]["adet"].value;
    if (urun == "" || sube == "" || adet == "") {
      alert("Lütfen boş geçmeyiniz");
      return false;
    }
  } 
  function yeniSubeFormDogrula() {
    var ad = document.forms["yenisubeform"]["ad"].value;
    var sifre = document.forms["yenisubeform"]["sifre"].value;
    if (ad == "" || sifre == "") {
      alert("Lütfen boş geçmeyiniz");
      return false;
    }
  } 
  function yeniUrunFormDogrula() {
    var ad = document.forms["yeniurunform"]["ad"].value;
    var stok = document.forms["yeniurunform"]["stok"].value;
    var fiyat = document.forms["yeniurunform"]["fiyat"].value;
    if (ad == "" || stok == "" || fiyat == "") {
      alert("Lütfen boş geçmeyiniz");
      return false;
    }
  } 
  function yeniToptanciFormDogrula() {
    var ad = document.forms["yenitoptanciform"]["ad"].value;
    var sifre = document.forms["yenitoptanciform"]["sifre"].value;
    if (ad == "" || sifre == "") {
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
    xmlhttp.open("GET","manuelsiparistutari.php?urun="+urun+"&adet="+adet,true);
    xmlhttp.send();
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
  <a href="index.php"><img class="img-responsive center-block" src="../images/logo.png" style="width:75%" title="UVM Toptancılık"/></a>
    <div class="pull-right">Hoş geldin, ' . $_SESSION["toptanciad"] . '.</div><br>
        <a class="btn btn-danger pull-right" onClick="cikisonay()"><span class="glyphicon glyphicon-log-out"></span> Çıkış Yap</a>
        <a class="btn btn-success pull-right" href="profiliduzenle.php" style="margin-right: 5px"><span class="glyphicon glyphicon-edit"></span> Profili Düzenle</a>
        <a class="btn btn-primary pull-right" href="index.php" style="margin-right: 5px"><span class="glyphicon glyphicon-home"></span> Ana Sayfa</a>


<h1> Tüm Siparişler </h1>
<table class="table table-striped">

<thead class="thead-dark">
<tr>
<th>SİPARİŞ ID</th>
<th>SİPARİŞ TARİHİ</th>
<th>ŞUBE ADI</th>
<th>ÜRÜN ADI</th>
<th>ADET</th>
<th>ÜCRET</th>
<th>ÖDEME DURUMU</th>
<th>TESLİM DURUMU</th>
</tr>
</thead>
<tbody>';


$siparisler = $db->prepare("
	SELECT siparisler.id,siparisler.tarih,subeler.ad sad,urunler.ad uad,siparisler.adet,siparisler.ucret,siparisler.odeme 
	FROM siparisler 
	INNER JOIN subeler ON siparisler.sube = subeler.id 
	INNER JOIN urunler ON siparisler.urun = urunler.id
	ORDER BY sube ASC, id ASC");
$siparisler->execute();

$yolsiparis = 0;
$odenmemis = 0;

while ($row = $siparisler->fetch(PDO::FETCH_ASSOC)) {

	echo "<tr>";
	$id = $row['id'];
	echo "<td>" . $id . "</td>";

	$tarih = $row['tarih'];
	$ftarih = 	date('d.m.Y', strtotime($tarih));
	echo "<td>" . $ftarih . "</td>";

	$sube = $row['sad'];
	echo "<td>" . $sube . "</td>";

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

$sorgu = $db->prepare("SELECT sum(ucret) FROM siparisler WHERE odeme = 'Ödenmedi'");
$sorgu->execute();

$toplam = $sorgu->fetch(PDO::FETCH_ASSOC);
$sonuc = $toplam['sum(ucret)']; //sorgudan gelen alanin adi sum

if(isset($sonuc)) {
  echo "<td colspan='8' style='padding-left:80%'>Toplam Alacak: <strong>" . usd_to_try($sonuc) . "</strong>
  <br> Teslim edilmemiş<strong> " . $yolsiparis . " </strong> sipariş var.
  <br> Tahsil edilmemiş<strong> " . $odenmemis . "</strong> ödeme var.</td>";
}
else {
  echo "<td colspan='8' style='padding-left:80%'>Toplam Alacak: <strong>0₺</strong>
  <br> Teslim edilmemiş<strong> " . $yolsiparis . " </strong> sipariş var.
  <br> Tahsil edilmemiş<strong> " . $odenmemis . "</strong> ödeme var.</td>";
}

echo '</tbody>
</table>

<a class="btn btn-success" data-toggle="modal" data-target="#ManuelSiparisModal"><span class="glyphicon glyphicon-plus"></span> Manuel Sipariş</a>
<a class="btn btn-primary" data-toggle="modal" data-target="#SubeSiparisModal"><span class="glyphicon glyphicon-th-list"></span> Şube Sipariş Raporları</a>



<h1> Şubeler </h1>
<table class="table table-striped">

<thead class="thead-dark">
<tr>
<th>ŞUBE ID</th>
<th>ŞUBE ADI</th>
<th>ŞUBE ŞİFRESİ</th>
<th></th>
</tr>
</thead>
<tbody>';

$subeler = $db->prepare("SELECT * FROM subeler ORDER BY id ASC");
$subeler->execute();

while ($row = $subeler->fetch(PDO::FETCH_ASSOC)) {

  echo "<tr>";
  $id = $row['id'];
  echo "<td>" . $id . "</td>";

  $ad = $row['ad'];
  echo "<td>" . $ad . "</td>";

  $sifre = $row['sifre'];
  echo "<td>" . $sifre . "</td>";

  echo "<td> <a class='btn btn-primary' href='subeduzenle.php?id=". $id . "'><span class='glyphicon glyphicon-edit'></span> Düzenle</a>
  <a class='btn btn-danger' href='subesil.php?id=". $id . "'><span class='glyphicon glyphicon-erase'></span> Sil</a></td>";

  echo "</tr>";
}

echo '</tbody>
</table>

<a class="btn btn-success" data-toggle="modal" data-target="#YeniSubeModal"><span class="glyphicon glyphicon-plus"></span> Yeni Şube</a> </td>


<h1> Ürünler </h1>
<table class="table table-striped">

<thead class="thead-dark">
<tr>
<th>ÜRÜN ID</th>
<th>ÜRÜN ADI</th>
<th>ÜRÜN STOĞU</th>
<th>ÜRÜN FİYATI</th>
<th></th>
</tr>
</thead>
<tbody>';


$urunler = $db->prepare("SELECT * FROM urunler ORDER BY id ASC");
$urunler->execute();

while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {

  echo "<tr>";
  $id = $row['id'];
  echo "<td>" . $id . "</td>";

  $ad = $row['ad'];
  echo "<td>" . $ad . "</td>";

  $stok = $row['stok'];
  echo "<td>" . $stok . "</td>";

  $fiyat = $row['fiyat'];
  echo "<td>" . usd_to_try($fiyat) . "</td>";

  echo "<td> <a class='btn btn-primary' href='urunduzenle.php?id=". $id . "'><span class='glyphicon glyphicon-edit'></span> Düzenle</a>
  <a class='btn btn-danger' href='urunsil.php?id=". $id . "'><span class='glyphicon glyphicon-erase'></span> Sil</a></td>";


  echo "</tr>";
}

echo '</tbody>
</table>

<a class="btn btn-success" data-toggle="modal" data-target="#YeniUrunModal"><span class="glyphicon glyphicon-plus"></span> Yeni Ürün</a> </td>
<a class="btn btn-primary" data-toggle="modal" data-target="#UrunRaporModal"><span class="glyphicon glyphicon-th-list"></span> Ürün Raporları</a>';


echo '<h1> Toptancılar </h1>
<table class="table table-striped">

<thead class="thead-dark">
<tr>
<th>TOPTANCI ID</th>
<th>TOPTANCI ADI</th>
<th>TOPTANCI ŞİFRESİ</th>
<th></th>
</tr>
</thead>
<tbody>';

$toptancilar = $db->prepare("SELECT * FROM toptancilar ORDER BY id ASC");
$toptancilar->execute();

while ($row = $toptancilar->fetch(PDO::FETCH_ASSOC)) {

  echo "<tr>";
  $id = $row['id'];
  echo "<td>" . $id . "</td>";

  $ad = $row['ad'];
  echo "<td>" . $ad . "</td>";

  $sifre = $row['sifre'];
  echo "<td>" . $sifre . "</td>";

  echo "<td> <a class='btn btn-primary' href='toptanciduzenle.php?id=". $id . "'><span class='glyphicon glyphicon-edit'></span> Düzenle</a>
  <a class='btn btn-danger' href='toptancisil.php?id=". $id . "'><span class='glyphicon glyphicon-erase'></span> Sil</a></td>";

  echo "</tr>";

}

echo '</tbody>
</table>

<a class="btn btn-success" data-toggle="modal" data-target="#YeniToptanciModal"><span class="glyphicon glyphicon-plus"></span> Yeni Toptancı</a> </td>';




echo '<div id="ManuelSiparisModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-ku">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Manuel Sipariş</h4>
      </div>
      <div class="modal-body">


  <form name="manuelsiparisform" method="post" action="manuelsiparis.php" onsubmit="return manuelSiparisFormDogrula()">

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Ürün Seçiniz</label>
      <select name="urun" onchange="siparisTutari(this.value,form.adet.value)">';

      $urunler = $db->prepare("SELECT id,ad,fiyat FROM urunler ORDER BY id ASC;");
      $urunler->execute();

	while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {
		echo "<option class='form-control' value='" . $row['id'] . "' required>" . $row['ad'] . " (" . usd_to_try($row['fiyat']) . ")";
	}

      echo '</select>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Şube Seçiniz</label>
      <select name="sube">';

      $urunler = $db->prepare("SELECT id,ad FROM subeler ORDER BY id ASC;");
      $urunler->execute();

  while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {
    echo "<option class='form-control' value='" . $row['id'] . "'>" . $row['ad'];
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
      <label><input type="checkbox" name="odeme"> Ödeme Tahsil Edildi</label>
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





<div id="SubeSiparisModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Şube Sipariş Raporları</h4>
      </div>
      <div class="modal-body">


  <form name="form1" method="post" action="subesiparis.php">

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Şube Seçiniz</label>
      <select name="sube">';

      $subeler = $db->prepare("SELECT id,ad FROM subeler ORDER BY id ASC;");
      $subeler->execute();

  while ($row = $subeler->fetch(PDO::FETCH_ASSOC)) {
    echo "<option class='form-control' value='" . $row['id'] . "' required>" . $row['ad'];
  }

echo '</select>
      </div>
    </div>


      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Göster">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>



<div id="UrunRaporModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Ürün Raporları</h4>
      </div>
      <div class="modal-body">


  <form name="form1" method="post" action="urunrapor.php">

    <div class="form-group row">
      <div class="col-sm-12">
      <label for="name">Ürün Seçiniz</label>
      <select name="urun">';

      $urunler = $db->prepare("SELECT id,ad FROM urunler ORDER BY id ASC;");
      $urunler->execute();

  while ($row = $urunler->fetch(PDO::FETCH_ASSOC)) {
    echo "<option class='form-control' value='" . $row['id'] . "' required>" . $row['ad'];
  }

echo '</select>
      </div>
    </div>


      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Göster">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>









<div id="YeniSubeModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yeni Şube</h4>
      </div>
      <div class="modal-body">

  <form name="yenisubeform" method="post" action="yenisube.php" onsubmit="return yeniSubeFormDogrula()">
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="usr">Ad</label>
      <input type="text" class="form-control" name="ad" id="ex1" autocomplete="off">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="pwd">Şifre</label>
      <input type="password" class="form-control" name="sifre" id="ex1" autocomplete="off">
      </div>
    </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Şube Ekle">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>





<div id="YeniUrunModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yeni Ürün</h4>
      </div>
      <div class="modal-body">

  <form name="yeniurunform" method="post" action="yeniurun.php" onsubmit="return yeniUrunFormDogrula()">
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="usr">Ad</label>
      <input type="text" class="form-control" name="ad" id="ex1" autocomplete="off">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="pwd">Stok</label>
      <input type="text" class="form-control" name="stok" id="ex1" autocomplete="off">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="pwd">Fiyat</label>
      <input type="text" class="form-control" name="fiyat" id="ex1" autocomplete="off">
      </div>
    </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Ürün Ekle">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>




<div id="YeniToptanciModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Yeni Toptancı</h4>
      </div>
      <div class="modal-body">

  <form name="yenitoptanciform" method="post" action="yenitoptanci.php" onsubmit="return yeniToptanciFormDogrula()">
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="usr">Ad</label>
      <input type="text" class="form-control" name="ad" id="ex1" autocomplete="off">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-12">
      <label for="pwd">Şifre</label>
      <input type="password" class="form-control" name="sifre" id="ex1" autocomplete="off">
      </div>
    </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-success" value="Toptancı Ekle">
        <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
      </div>
  </form>
    </div>
  </div>
</div>







</div>';





echo"
</body>
</html>";


  
}
else {
  header("Location: index.php");
}
?>
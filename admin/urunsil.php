<?php
session_start();
require '../config.php';
require '../replace_tr.php';


$id = $_GET["id"];
$urun = $db->prepare("SELECT * FROM urunler WHERE id='$id'");
$urun->execute();

while ($row = $urun->fetch(PDO::FETCH_ASSOC)) {
    $ad = $row["ad"];
    $stok = $row["stok"];
    $fiyat = $row["fiyat"];
	}

if (isset($_POST["submit"])) {

$urun = $db->prepare("DELETE FROM urunler WHERE id='$id'");
$urun->execute();
header("Location: index.php");
}


?>

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
    <title>Ürün Sil | UVM Toptancılık</title>
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

    <div class="pull-right">Hoş geldin, <?php echo $_SESSION["toptanciad"] ?>.</div><br>
        <a class="btn btn-danger pull-right" onClick="cikisonay()"><span class="glyphicon glyphicon-log-out"></span> Çıkış Yap</a>            
        <a class="btn btn-success pull-right" href="profiliduzenle.php" style="margin-right: 5px"><span class="glyphicon glyphicon-edit"></span> Profili Düzenle</a>
        <a class="btn btn-primary pull-right" href="index.php" style="margin-right: 5px"><span class="glyphicon glyphicon-home"></span> Ana Sayfa</a>

  <h1>Ürün Sil</h1>

<form method="post" action="">
  <div class="form-group">
    <label for="exampleInputPassword1"><?php echo $ad ?> ürününü silmek istediğinize emin misiniz?</label>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Sil</button>
  <a class="btn btn-default" href="index.php">Vazgeç</a>
</form>
</div>
</body>
</html>
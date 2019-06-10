<?php
session_start();
require '../config.php';


$id = $_GET["id"];
$urun = $db->prepare("SELECT * FROM urunler WHERE id='$id'");
$urun->execute();

while ($row = $urun->fetch(PDO::FETCH_ASSOC)) {
		$ad = $row["ad"];
		$stok = $row["stok"];
    $fiyat = substr($row["fiyat"],1);
	}

if (isset($_POST["submit"])) {
$ad = trim($_POST["ad"]);
$stok = trim($_POST["stok"]);
$fiyat = trim($_POST["fiyat"]);


$urun = $db->prepare("UPDATE urunler SET ad='$ad',stok='$stok',fiyat='$fiyat' WHERE id='$id'");
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
    <title>Ürün Düzenle | UVM Toptancılık</title>

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

  <h1>Ürün Düzenle</h1>

<form method="post" action="">
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="name">Ürün ID</label>
    <input type="text" name="id" class="form-control" value="<?php echo $id ?>" readonly>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="nm">Ürün Adı</label>
    <input type="text" name="ad" class="form-control" value="<?php echo $ad ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="nm">Ürün Stoğu</label>
    <input type="text" name="stok" class="form-control" value="<?php echo $stok ?>" required>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="nm">Ürün Fiyatı</label>
    <input type="text" name="fiyat" class="form-control" value="<?php echo $fiyat ?>" required>
    </div>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Güncelle</button>
  <a class="btn btn-default" href="index.php">Vazgeç</a>
</form>

</div>
</body>
</html>
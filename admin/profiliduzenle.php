<?php
session_start();
require '../config.php';
require '../replace_tr.php';


$id = $_SESSION["toptanciid"];
$toptanci = $db->prepare("SELECT * FROM toptancilar WHERE id='$id'");
$toptanci->execute();

while ($row = $toptanci->fetch(PDO::FETCH_ASSOC)) {
		$ad = $row["ad"];
		$sifre = $row["sifre"];
	}

if (isset($_POST["submit"])) {
$ad = strtoupper(replace_tr(trim($_POST["ad"])));
$sifre = trim($_POST["sifre"]);
$sifreonay = trim($_POST["sifreonay"]);

if($sifre == $sifreonay) {
  $toptanci = $db->prepare("UPDATE toptancilar SET ad='$ad',sifre='$sifre' WHERE id='$id'");
  $toptanci->execute();
  header("Location: index.php");
}
else {
  echo '<div class="alert alert-danger" role="alert">
  İki şifre aynı değil
</div>';
}

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
    <title>Toptancı Profili Düzenle | UVM Toptancılık</title>

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

  <h1>Profili Düzenle</h1>

<form method="post" action="">
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="name">Toptancı ID</label>
    <input type="text" name="id" class="form-control" value="<?php echo $id ?>" readonly>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="nm">Toptancı Adı</label>
    <input type="text" name="ad" class="form-control" value="<?php echo $ad ?>">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="exampleInputPassword1">Yeni Şifre</label>
    <input type="password" name="sifre" class="form-control" placeholder="Yeni şifreyi giriniz">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3">
    <label for="exampleInputPassword1">Yeni Şifre Onay</label>
    <input type="password" name="sifreonay" class="form-control" placeholder="Yeni şifreyi tekrar giriniz">
    </div>
  </div>
  <button type="submit" class="btn btn-primary" name="submit">Güncelle</button>
  <a class="btn btn-default" href="index.php">Vazgeç</a>
</form>

</div>
</body>
</html>
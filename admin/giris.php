<?php
session_start();
require '../config.php';
require '../replace_tr.php';


if (isset($_POST["submit"])) {

$ad = strtoupper(replace_tr(trim($_POST["ad"])));
$sifre = trim($_POST["sifre"]);

$giris = $db->prepare("SELECT * FROM toptancilar WHERE ad='$ad' AND sifre='$sifre'");
$giris->execute();
$sonuc = $giris->fetch(PDO::FETCH_ASSOC);

if ($sonuc) {
  $_SESSION["toptanciad"] = $ad;
  $_SESSION["toptanciid"] = $sonuc["id"];
  header("Location: index.php");
}
else {
  echo '<div class="alert alert-danger" role="alert">
  Hatalı giriş
</div>';
}

}

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
    <title>Toptancı Girişi | UVM Toptancılık</title>

    <script>
    function formDogrula() {
      var ad = document.forms["girisform"]["ad"].value;
      var sifre = document.forms["girisform"]["sifre"].value;
      if (ad == "" || sifre == "") {
        alert("Lütfen boş geçmeyiniz");
        return false;
      }
    } 
    </script>
  </head>
  <body>
  <div class="container">
  <a href="index.php"><img class="img-responsive center-block" src="../images/logo.png" style="width:75%" title="UVM Toptancılık"/></a>
  <h1 class="text-center">Toptancı Girişi</h1>

 <form name="girisform" action="" method="post" onsubmit="return formDogrula()">
  <div class="form-group row">
    <div class="col-xs-3 ortala">
    <label for="name">Toptancı Adı</label>
    <input type="text" class="form-control" name="ad">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-xs-3 ortala">
    <label for="pwd">Şifre</label>
    <input type="password" class="form-control" name="sifre">
    </div>
  </div>
  <button type="submit" class="btn btn-primary center-block" name="submit">Giriş Yap</button>
</form> 


  </div>
</body>
</html>';
?>
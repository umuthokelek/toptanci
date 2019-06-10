<?php
session_start();
require 'config.php';
require 'usd_to_try.php';

$sube = $_SESSION['subeid'];

$sorgu = $db->prepare("SELECT * FROM subeler WHERE id = '$sube'");
$sorgu->execute();

while($row = $sorgu->fetch(PDO::FETCH_ASSOC)) {
    $subead = $row['ad'];
}

echo '<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . $subead . ' Şubesi Faturası</title>
    
    <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="images/logo.png" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>';

                                $faturaTarihi = date('d.m.Y');
                                echo "Fatura Tarihi: " . $faturaTarihi;

                                echo '<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                UVM Toptancılık Ltd. Şti.<br>
                                Çiftlikköy Mahallesi, 33110<br>
                                Yenişehir / MERSİN
                            </td>
                            
                            <td>';

                                echo $subead . " Şubesi Faturası";
                            echo '    
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            
            <tr class="heading">
                <td>
                    Ürün x Adet
                </td>
                
                <td>
                    Fiyat
                </td>
            </tr>';
            
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
            
                if($fark < 259200 && $odeme == "Ödenmedi") {
                    echo "<tr class='item'>";
            
                    $adet = $row['adet'];
            
                    $urun = $row['uad'];
                    echo "<td>" . $urun . " x " . $adet . "</td>";
                
                    $ucret = $row['ucret'];
                    $toplam += $ucret;
                    echo "<td>" . usd_to_try($ucret) . "</td>";
            
                    echo "</tr>";
                }
            }
            



            echo ' 
            <tr class="heading">
            <td>
                KDV (%8)
            </td>
            
            <td>';
            $kdv = ($toplam / 100)*8;
            echo usd_to_try(round($kdv,2));
            echo '</td>
        </tr>

            <tr class="heading">
            <td>
                Ara Toplam
            </td>
            <td>';
                echo usd_to_try(round($toplam-$kdv,2));
            echo '</td>
            </tr>

            <tr class="total">
                <td></td>
                
                <td>
                   Genel Toplam: ';
                   echo usd_to_try($toplam);
               
                echo '</td>
            </tr>
        </table>
        <div align="right">
        <button onclick="window.print()">Yazdır</a>
        <button onclick="window.close()">Pencereyi Kapat</a>
        </div>
    </div>
</body>
</html>';

?>

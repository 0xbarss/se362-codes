<?php
session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
$bag = new mysqli("localhost", "root", "", "kitapci_db");
$bag->set_charset("utf8");
$uid = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<body style="font-family:sans-serif; margin:20px;">
    <h2>📦 Sipariş Geçmişim</h2>
    <a href="index.php">⬅️ Markete Dön</a><br><br>
    <?php
    $sorgu = "SELECT s.*, k.baslik FROM siparisler s 
              JOIN kitaplar k ON s.kitap_id = k.id 
              WHERE s.kullanici_id = $uid ORDER BY s.id DESC";
    $siparisler = $bag->query($sorgu);
    
    if($siparisler->num_rows > 0){
        while($s = $siparisler->fetch_assoc()){
            echo "<div style='border:1px solid #ccc; padding:15px; margin-bottom:10px; background:#f9f9f9;'>
                <strong>Kitap:</strong> {$s['baslik']} <br>
                <strong>Tutar:</strong> {$s['toplam_fiyat']} ₺ <br>
                <strong>Durum:</strong> <span style='color:blue; font-weight:bold;'>{$s['durum']}</span> <br>
                <small>Tarih: {$s['tarih']}</small>
            </div>";
        }
    } else { echo "Henüz siparişiniz yok."; }
    ?>
</body>
</html>
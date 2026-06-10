<?php
require_once 'baglanti.php';

// Oturum kontrolü
if(!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Siparişlerim</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .siparis-kutu { background: #fff; border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .siparis-ust { border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 10px; display: flex; justify-content: space-between; }
        .durum { font-weight: bold; color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 8px; border-bottom: 1px solid #eee; }
        th { color: #666; font-size: 13px; }
        .toplam-fiyat { text-align: right; font-size: 18px; font-weight: bold; margin-top: 10px; color: #333; }
        .nav-link { color: #007bff; text-decoration: none; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>

    <a href="index.php" class="nav-link">← Ana Sayfaya Dön</a>
    <h1>Siparişlerim</h1>

    <?php
    // Kullanıcının siparişlerini getir
    $siparis_sorgu = "SELECT * FROM siparisler WHERE kullanici_id = $kullanici_id ORDER BY tarih DESC";
    $siparis_sonuc = mysqli_query($conn, $siparis_sorgu);

    if(mysqli_num_rows($siparis_sonuc) > 0) {
        while($siparis = mysqli_fetch_assoc($siparis_sonuc)) {
            $siparis_id = $siparis['id'];
            ?>
            <div class="siparis-kutu">
                <div class="siparis-ust">
                    <span><strong>Sipariş No:</strong> #<?php echo $siparis['id']; ?></span>
                    <span><strong>Tarih:</strong> <?php echo date('d.m.Y H:i', strtotime($siparis['tarih'])); ?></span>
                    <span class="durum">Durum: <?php echo $siparis['durum']; ?></span>
                </div>
                
                <p><strong>Teslimat Adresi:</strong> <?php echo htmlspecialchars($siparis['teslimat_adresi']); ?></p>

                <table>
                    <tr>
                        <th>Kitap Adı</th>
                        <th>Adet</th>
                        <th>Birim Fiyat (O Gün)</th>
                        <th>Toplam</th>
                    </tr>
                    <?php
                    // Siparişin detaylarını (kitapları) getir
                    $detay_sorgu = "SELECT sd.*, k.kitap_adi 
                                    FROM siparis_detaylari sd 
                                    JOIN kitaplar k ON sd.kitap_id = k.id 
                                    WHERE sd.siparis_id = $siparis_id";
                    $detay_sonuc = mysqli_query($conn, $detay_sorgu);
                    
                    while($detay = mysqli_fetch_assoc($detay_sonuc)) {
                        $ara_toplam = $detay['adet'] * $detay['alinan_fiyat'];
                        echo "<tr>
                                <td>{$detay['kitap_adi']}</td>
                                <td>{$detay['adet']}</td>
                                <td>{$detay['alinan_fiyat']} TL</td>
                                <td>$ara_toplam TL</td>
                              </tr>";
                    }
                    ?>
                </table>
                <div class="toplam-fiyat">Ödenen Toplam: <?php echo $siparis['toplam_tutar']; ?> TL</div>
            </div>
            <?php
        }
    } else {
        echo "<p>Henüz bir siparişiniz bulunmamaktadır.</p>";
    }
    ?>

</body>
</html>
<?php
require_once 'baglanti.php';

// Güvenlik: Sadece adminler girebilir
if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'admin') {
    die("Erişim engellendi!");
}

// --- İSTATİSTİKLERİ HESAPLAMA SORGULARI ---

// 1. Toplam Müşteri Sayısı (Adminler hariç)
$q_musteri = mysqli_query($conn, "SELECT COUNT(id) as toplam FROM kullanicilar WHERE rol = 'kullanici'");
$toplam_musteri = mysqli_fetch_assoc($q_musteri)['toplam'];

// 2. Toplam Kitap Sayısı
$q_kitap = mysqli_query($conn, "SELECT COUNT(id) as toplam FROM kitaplar");
$toplam_kitap = mysqli_fetch_assoc($q_kitap)['toplam'];

// 3. Bekleyen Sipariş Sayısı (Hazırlanıyor durumunda olanlar)
$q_bekleyen = mysqli_query($conn, "SELECT COUNT(id) as toplam FROM siparisler WHERE durum = 'Hazırlanıyor'");
$bekleyen_siparis = mysqli_fetch_assoc($q_bekleyen)['toplam'];

// 4. Toplam Kazanç (İptal edilenler hariç)
$q_kazanc = mysqli_query($conn, "SELECT SUM(toplam_tutar) as ciro FROM siparisler WHERE durum != 'İptal Edildi'");
$toplam_kazanc = mysqli_fetch_assoc($q_kazanc)['ciro'];
if(!$toplam_kazanc) $toplam_kazanc = 0; // Eğer hiç sipariş yoksa null dönmesin
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Özet Paneli</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; margin: 0; }
        
        /* Admin Menüsü CSS */
        .admin-nav { background: #343a40; padding: 15px; margin-bottom: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-nav a { color: #fff; margin-right: 20px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .admin-nav a:hover { color: #ffc107; }
        .cikis-link { float: right; color: #dc3545 !important; }

        /* Kartlar (Dashboard Widget) CSS */
        .kart-kapsayici { display: flex; justify-content: space-between; gap: 20px; margin-bottom: 30px; }
        .kart { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #ddd; }
        .kart h3 { margin: 0; color: #6c757d; font-size: 16px; text-transform: uppercase; }
        .kart .deger { font-size: 32px; font-weight: bold; color: #333; margin-top: 10px; }
        
        /* Kart Renkleri */
        .kart-mavi { border-bottom-color: #007bff; }
        .kart-yesil { border-bottom-color: #28a745; }
        .kart-sari { border-bottom-color: #ffc107; }
        .kart-mor { border-bottom-color: #6f42c1; }

        /* Tablolar ve Düzen */
        .alt-kisim { display: flex; gap: 20px; }
        .panel-bolum { flex: 1; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .panel-bolum h2 { margin-top: 0; color: #343a40; font-size: 18px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border-bottom: 1px solid #eee; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #f8f9fa; color: #495057; }
        .kirmizi-yazi { color: #dc3545; font-weight: bold; }
        .durum-etiket { background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="admin-nav">
        <a href="admin.php">📊 Özet Paneli</a>
        <a href="admin_kitaplar.php">📚 Kitap Yönetimi</a>
        <a href="admin_siparisler.php">📦 Sipariş Yönetimi</a>
        <a href="admin_kullanicilar.php">👥 Kullanıcı Yönetimi</a>
        <a href="index.php" style="color: #17a2b8;">🏠 Siteye Dön</a>
        <a href="cikis.php" class="cikis-link">🚪 Çıkış Yap</a>
    </div>

    <h1>Sistem Özeti</h1>

    <div class="kart-kapsayici">
        <div class="kart kart-mavi">
            <h3>Kayıtlı Müşteri</h3>
            <div class="deger"><?php echo $toplam_musteri; ?></div>
        </div>
        <div class="kart kart-mor">
            <h3>Sistemdeki Kitap</h3>
            <div class="deger"><?php echo $toplam_kitap; ?></div>
        </div>
        <div class="kart kart-sari">
            <h3>Bekleyen Sipariş</h3>
            <div class="deger"><?php echo $bekleyen_siparis; ?></div>
        </div>
        <div class="kart kart-yesil">
            <h3>Toplam Kazanç</h3>
            <div class="deger"><?php echo number_format($toplam_kazanc, 2); ?> ₺</div>
        </div>
    </div>

    <div class="alt-kisim">
        
        <div class="panel-bolum" style="flex: 2;">
            <h2>Son Gelen 5 Sipariş</h2>
            <table>
                <tr>
                    <th>Sipariş No</th>
                    <th>Müşteri</th>
                    <th>Tutar</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                </tr>
                <?php
                $son_siparisler = mysqli_query($conn, "SELECT s.id, k.ad_soyad, s.toplam_tutar, s.tarih, s.durum 
                                                       FROM siparisler s 
                                                       JOIN kullanicilar k ON s.kullanici_id = k.id 
                                                       ORDER BY s.tarih DESC LIMIT 5");
                
                if(mysqli_num_rows($son_siparisler) > 0) {
                    while($ss = mysqli_fetch_assoc($son_siparisler)) {
                        echo "<tr>
                                <td>#{$ss['id']}</td>
                                <td>{$ss['ad_soyad']}</td>
                                <td>{$ss['toplam_tutar']} ₺</td>
                                <td>".date('d.m.Y H:i', strtotime($ss['tarih']))."</td>
                                <td><span class='durum-etiket'>{$ss['durum']}</span></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Henüz hiç sipariş yok.</td></tr>";
                }
                ?>
            </table>
            <br>
            <a href="admin_siparisler.php" style="font-size: 14px; color: #007bff; text-decoration: none;">Tüm Siparişleri Gör →</a>
        </div>

        <div class="panel-bolum" style="flex: 1;">
            <h2>Kritik Stok Uyarısı (Azalanlar)</h2>
            <table>
                <tr>
                    <th>Kitap Adı</th>
                    <th>Kalan Stok</th>
                </tr>
                <?php
                // Stoğu 5 ve altında olan kitapları getir
                $stok_uyari = mysqli_query($conn, "SELECT kitap_adi, stok FROM kitaplar WHERE stok <= 5 ORDER BY stok ASC LIMIT 8");
                
                if(mysqli_num_rows($stok_uyari) > 0) {
                    while($su = mysqli_fetch_assoc($stok_uyari)) {
                        $stok_class = ($su['stok'] == 0) ? "kirmizi-yazi" : "";
                        echo "<tr>
                                <td>{$su['kitap_adi']}</td>
                                <td class='$stok_class'>{$su['stok']} Adet</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>Stoğu azalan kitap yok. Durum iyi!</td></tr>";
                }
                ?>
            </table>
            <br>
            <a href="admin_kitaplar.php" style="font-size: 14px; color: #007bff; text-decoration: none;">Kitapları Yönet →</a>
        </div>

    </div>

</body>
</html>
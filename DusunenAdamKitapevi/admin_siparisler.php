<?php
require_once 'baglanti.php';

// Güvenlik kontrolü
if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'admin') { 
    die("Erişim engellendi!"); 
}

// Durum Güncelleme ve İptal Mantığı
if(isset($_POST['durum_guncelle'])) {
    $s_id = (int)$_POST['siparis_id'];
    $yeni_durum = mysqli_real_escape_string($conn, $_POST['yeni_durum']);
    
    // Mevcut durumu öğren
    $mevcut_sorgu = mysqli_query($conn, "SELECT durum FROM siparisler WHERE id = $s_id");
    $mevcut_veri = mysqli_fetch_assoc($mevcut_sorgu);
    $mevcut = $mevcut_veri['durum'];

    // Eğer yeni durum "İptal Edildi" ise ve önceden iptal edilmemişse (Stoğu geri ver, Satılanı düş)
    if($yeni_durum == 'İptal Edildi' && $mevcut != 'İptal Edildi') {
        $detaylar = mysqli_query($conn, "SELECT kitap_id, adet FROM siparis_detaylari WHERE siparis_id = $s_id");
        while($d = mysqli_fetch_assoc($detaylar)) {
            $k_id = $d['kitap_id'];
            $adet = $d['adet'];
            mysqli_query($conn, "UPDATE kitaplar SET stok = stok + $adet, satilan_adet = satilan_adet - $adet WHERE id = $k_id");
        }
    } 
    // Eğer iptalden vazgeçilip tekrar aktif edilirse (Stoğu tekrar düş, Satılanı artır)
    elseif($mevcut == 'İptal Edildi' && $yeni_durum != 'İptal Edildi') {
        $detaylar = mysqli_query($conn, "SELECT kitap_id, adet FROM siparis_detaylari WHERE siparis_id = $s_id");
        while($d = mysqli_fetch_assoc($detaylar)) {
            $k_id = $d['kitap_id'];
            $adet = $d['adet'];
            mysqli_query($conn, "UPDATE kitaplar SET stok = stok - $adet, satilan_adet = satilan_adet + $adet WHERE id = $k_id");
        }
    }
    
    // Siparişin durumunu veritabanında güncelle
    mysqli_query($conn, "UPDATE siparisler SET durum = '$yeni_durum' WHERE id = $s_id");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Sipariş Yönetimi</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; margin: 0; }
        
        /* Admin Menüsü CSS */
        .admin-nav { background: #343a40; padding: 15px; margin-bottom: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-nav a { color: #fff; margin-right: 20px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .admin-nav a:hover { color: #ffc107; }
        .cikis-link { float: right; color: #dc3545 !important; }

        /* Tablo CSS */
        .panel-bolum { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; color: #343a40; font-size: 22px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: top; }
        th { background: #f8f9fa; color: #495057; }
        .iptal { color: #dc3545; font-weight: bold; }
        .btn-guncelle { background: #007bff; color: white; border: none; padding: 6px 10px; cursor: pointer; border-radius: 3px; margin-top: 5px; width: 100%; font-weight:bold;}
        .btn-guncelle:hover { background: #0056b3; }
        
        /* Liste CSS */
        .kitap-listesi { margin: 0; padding-left: 20px; font-size: 13px; color: #333; }
        .kitap-listesi li { margin-bottom: 5px; }
    </style>
</head>
<body>

    <div class="admin-nav">
        <a href="admin.php">📊 Özet Paneli</a>
        <a href="admin_kitaplar.php">📚 Kitap Yönetimi</a>
        <a href="admin_siparisler.php" style="color: #ffc107;">📦 Sipariş Yönetimi</a>
        <a href="admin_kullanicilar.php">👥 Kullanıcı Yönetimi</a>
        <a href="index.php" style="color: #17a2b8;">🏠 Siteye Dön</a>
        <a href="cikis.php" class="cikis-link">🚪 Çıkış Yap</a>
    </div>

    <div class="panel-bolum">
        <h1>📦 Sipariş Yönetimi</h1>
        <table>
            <tr>
                <th>Sipariş No & Tarih</th>
                <th>Müşteri Bilgisi</th>
                <th>Alınan Kitaplar (Detay)</th>
                <th>Toplam Tutar</th>
                <th>Teslimat Adresi</th>
                <th>Durum</th>
                <th>İşlem</th>
            </tr>
            <?php
            $siparisler = mysqli_query($conn, "SELECT s.*, k.ad_soyad FROM siparisler s JOIN kullanicilar k ON s.kullanici_id = k.id ORDER BY s.tarih DESC");
            
            while($s = mysqli_fetch_assoc($siparisler)) {
                $class = ($s['durum'] == 'İptal Edildi') ? "class='iptal'" : "";
                
                // Siparişe ait kitapları bulmak için iç sorgu (JOIN kullanarak kitap adını da alıyoruz)
                $siparis_id = $s['id'];
                $detaylar = mysqli_query($conn, "SELECT sd.adet, sd.alinan_fiyat, k.kitap_adi 
                                                 FROM siparis_detaylari sd 
                                                 JOIN kitaplar k ON sd.kitap_id = k.id 
                                                 WHERE sd.siparis_id = $siparis_id");
                
                // Kitapları liste formatında hazırlayalım
                $kitap_html = "<ul class='kitap-listesi'>";
                while($d = mysqli_fetch_assoc($detaylar)) {
                    $kitap_html .= "<li><b>{$d['kitap_adi']}</b><br>({$d['adet']} Adet x {$d['alinan_fiyat']} TL)</li>";
                }
                $kitap_html .= "</ul>";

                echo "<tr>
                    <td>#{$s['id']} <br><small style='color:#777; font-weight:bold;'>".date('d.m.Y H:i', strtotime($s['tarih']))."</small></td>
                    <td><strong>{$s['ad_soyad']}</strong></td>
                    <td>{$kitap_html}</td>
                    <td><strong style='font-size:16px; color:#28a745;'>{$s['toplam_tutar']} TL</strong></td>
                    <td>{$s['teslimat_adresi']}</td>
                    <td $class>{$s['durum']}</td>
                    <td>
                        <form method='POST'>
                            <input type='hidden' name='siparis_id' value='{$s['id']}'>
                            <select name='yeni_durum' style='padding:5px; width: 100%; border-radius:3px;'>
                                <option value='Hazırlanıyor' ".($s['durum']=='Hazırlanıyor'?'selected':'').">Hazırlanıyor</option>
                                <option value='Kargoya Verildi' ".($s['durum']=='Kargoya Verildi'?'selected':'').">Kargoya Verildi</option>
                                <option value='Teslim Edildi' ".($s['durum']=='Teslim Edildi'?'selected':'').">Teslim Edildi</option>
                                <option value='İptal Edildi' ".($s['durum']=='İptal Edildi'?'selected':'').">İptal Edildi</option>
                            </select><br>
                            <button type='submit' name='durum_guncelle' class='btn-guncelle'>Güncelle</button>
                        </form>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
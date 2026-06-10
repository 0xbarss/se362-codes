<?php
require_once 'baglanti.php';

// --- SAYFALAMA VE FİLTRELEME AYARLARI ---
$kitap_basina_sayfa = 12; // 4 sütun x 3 satır = 12 kitap
$mevcut_sayfa = isset($_GET['sayfa']) ? (int)$_GET['sayfa'] : 1;
if($mevcut_sayfa < 1) $mevcut_sayfa = 1;
$offset = ($mevcut_sayfa - 1) * $kitap_basina_sayfa;

// URL'den kategori ve arama parametrelerini al
$kat_id = isset($_GET['kat']) ? (int)$_GET['kat'] : 0;
$kelime = isset($_GET['arama']) ? mysqli_real_escape_string($conn, $_GET['arama']) : '';

// STOK BİLDİRİM TALEBİNİ KAYDETME İŞLEMİ
if(isset($_POST['bildirim_istek']) && isset($_SESSION['kullanici_id'])) {
    $b_kitap_id = (int)$_POST['bildirim_kitap_id'];
    $b_kul_id = $_SESSION['kullanici_id'];
    
    $kontrol = mysqli_query($conn, "SELECT id FROM stok_bildirim WHERE kullanici_id = $b_kul_id AND kitap_id = $b_kitap_id");
    if(mysqli_num_rows($kontrol) == 0) {
        mysqli_query($conn, "INSERT INTO stok_bildirim (kullanici_id, kitap_id) VALUES ($b_kul_id, $b_kitap_id)");
    }
    // Mevcut filtreleri koruyarak yönlendir
    header("Location: index.php?sayfa=".$mevcut_sayfa.($kat_id ? "&kat=".$kat_id : "").(!empty($kelime) ? "&arama=".$kelime : ""));
    exit();
}

// SQL Dinamik Filtreleme Şartları
$sartlar = "WHERE 1=1";
if($kat_id > 0) $sartlar .= " AND kategori_id = $kat_id";
if(!empty($kelime)) $sartlar .= " AND (kitap_adi LIKE '%$kelime%' OR yazar LIKE '%$kelime%')";

// Toplam Kitap Sayısı (Sayfalama için filtreli sayım)
$toplam_kitap_sorgu = mysqli_query($conn, "SELECT COUNT(*) as toplam FROM kitaplar $sartlar");
$toplam_veri = mysqli_fetch_assoc($toplam_kitap_sorgu);
$toplam_sayfa = ceil($toplam_veri['toplam'] / $kitap_basina_sayfa);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Düşünen Adam Kitapevi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f4f4; margin:0; padding:20px; color: #333;}
        .container { max-width: 1200px; margin: 0 auto; }
        
        /* Ana Navigasyon */
        nav { margin-bottom: 10px; background: #333; padding: 15px; border-radius: 5px; display: flex; justify-content: space-between;}
        nav a { color: white; text-decoration: none; margin-right: 15px; font-weight: bold;}
        nav a:hover { color: #f1c407; }

        /* Kategori Navigasyonu */
        .kategori-nav { background: #fff; padding: 10px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center; }
        .kategori-nav a { display: inline-block; padding: 8px 15px; margin: 5px; text-decoration: none; color: #555; border-radius: 20px; background: #f8f9fa; font-size: 14px; transition: 0.3s; }
        .kategori-nav a:hover, .kategori-nav a.aktif { background: #3498db; color: white; }

        .kitap-konteynir { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; max-width: 1200px; margin: 20px auto; }
        .kitap-kutu { position: relative; border: 1px solid #ddd; padding: 15px; background: #fff; text-align: center; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .populer-rozet { position: absolute; top: 10px; right: 10px; background: #ff3b30; color: white; padding: 5px 10px; border-radius: 20px; font-weight: bold; font-size: 11px; z-index: 10;}
        .kitap-resim { width: 100%; height: 250px; object-fit: cover; border-bottom: 1px solid #eee; margin-bottom: 10px; border-radius: 4px; }
        .baslik { color: #333; font-size: 16px; margin: 10px 0; height: 40px; overflow: hidden;}
        
        .buton { background: #28a745; color: white; border: none; padding: 10px; cursor: pointer; border-radius: 3px; font-weight: bold; width: 100%; text-decoration:none; display:inline-block;}
        .arama-kutu { padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
        .sayfalama { text-align: center; margin: 30px 0; }
        .sayfalama a { display: inline-block; padding: 8px 16px; margin: 0 5px; background: #fff; border: 1px solid #ddd; color: #333; text-decoration: none; border-radius: 4px; }
        .sayfalama a.aktif { background: #3498db; color: white; border-color: #3498db; }
        .uyari-link { color: #007bff; font-weight: bold; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

<div class="container">
    <nav>
        <div>
            <a href="index.php">🏠 Ana Sayfa</a>
            <?php if(isset($_SESSION['kullanici_id'])): ?>
                <a href="sepet.php">🛒 Sepetim</a>
                <a href="siparislerim.php">📦 Siparişlerim</a>
                <a href="profil.php">👤 Profilim</a>
            <?php endif; ?>
        </div>
        <div>
            <?php if(isset($_SESSION['kullanici_id'])): ?>
                <?php if($_SESSION['rol'] == 'admin') echo '<a href="admin.php" style="color:#ffc107;">⚙️ Admin Paneli</a>'; ?>
                <a href="cikis.php" style="color: #ff4d4d; margin-left: 15px;">🚪 Çıkış</a>
            <?php else: ?>
                <a href="giris.php">🔑 Giriş / Kayıt</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="kategori-nav">
        <strong>Filtrele: </strong>
        <a href="index.php" class="<?= $kat_id == 0 ? 'aktif' : '' ?>">Tüm Kitaplar</a>
        <?php
        $kategori_listesi = mysqli_query($conn, "SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
        while($kat = mysqli_fetch_assoc($kategori_listesi)) {
            $aktif_sinif = ($kat['id'] == $kat_id) ? 'aktif' : '';
            echo "<a href='index.php?kat={$kat['id']}' class='{$aktif_sinif}'>".htmlspecialchars($kat['kategori_adi'])."</a>";
        }
        ?>
    </div>

    <center>
        <h1>Düşünen Adam Kitabevevi</h1>
        <form method="GET" action="index.php">
            <input type="hidden" name="kat" value="<?= $kat_id ?>">
            <input type="text" name="arama" class="arama-kutu" placeholder="Kitap veya yazar ara..." value="<?= htmlspecialchars($kelime) ?>">
            <button type="submit" class="buton" style="background: #007bff; width: auto; padding: 10px 25px; border-radius: 20px; margin-left: 10px;">🔍 Ara</button>
        </form>
    </center>

    <div class="kitap-konteynir">
    <?php
    $sorgu = "SELECT * FROM kitaplar $sartlar ORDER BY satilan_adet DESC, eklenme_tarihi DESC LIMIT $kitap_basina_sayfa OFFSET $offset";
    $sonuc = mysqli_query($conn, $sorgu);

    if(mysqli_num_rows($sonuc) > 0) {
        while($kitap = mysqli_fetch_assoc($sonuc)) {
            $resim_yolu = "resimler/" . ($kitap['resim'] ? $kitap['resim'] : "varsayilan.jpg");
            echo "<div class='kitap-kutu'>";
            
            if($kitap['satilan_adet'] > 50) echo "<div class='populer-rozet'>🔥 {$kitap['satilan_adet']} Satış</div>";

            echo "<img src='$resim_yolu' class='kitap-resim'>";
            echo "<h3 class='baslik'>" . htmlspecialchars($kitap['kitap_adi']) . "</h3>";
            echo "<p style='color: #666; font-size: 13px;'>".htmlspecialchars($kitap['yazar'])."</p>";
            echo "<p style='font-size: 18px; font-weight: bold; color: #000;'>".$kitap['fiyat']." TL</p>";
            
            if($kitap['stok'] > 0) {
                echo "<p style='color:green; font-weight:bold;'>Stokta ✅</p>";
                if(isset($_SESSION['kullanici_id'])) {
                    echo "<form action='sepet.php' method='POST'>
                            <input type='hidden' name='kitap_id' value='".$kitap['id']."'>
                            <input type='number' name='adet' value='1' min='1' max='".$kitap['stok']."' style='width:45px; margin-bottom:5px;'><br>
                            <button type='submit' name='sepete_ekle' class='buton'>Sepete Ekle</button>
                          </form>";
                } else {
                    echo "<p><a href='giris.php' class='uyari-link'>🛒 Satın almak için giriş yapın</a></p>";
                }
            } else {
                echo "<p style='color:red; font-weight:bold;'>Tükendi ❌</p>";
                if(isset($_SESSION['kullanici_id'])) {
                    $bildirim_kontrol = mysqli_query($conn, "SELECT id FROM stok_bildirim WHERE kullanici_id = ".$_SESSION['kullanici_id']." AND kitap_id = ".$kitap['id']);
                    if(mysqli_num_rows($bildirim_kontrol) > 0) {
                        echo "<p style='color: #28a745; font-size: 12px; font-weight:bold;'>✅ Stoka gelince haber verilecek.</p>";
                    } else {
                        echo "<form method='POST'>
                                <input type='hidden' name='bildirim_kitap_id' value='".$kitap['id']."'>
                                <button type='submit' name='bildirim_istek' class='buton' style='background:#ffc107; color:#333; font-size:12px;'>🔔 Gelince Bildir</button>
                              </form>";
                    }
                } else {
                    echo "<p><a href='giris.php' class='uyari-link' style='color:#ff9800;'>🔔 Bildirim için giriş yapın</a></p>";
                }
            }
            echo "</div>";
        }
    } else {
        echo "<p style='grid-column: span 4; text-align:center;'>Kitap bulunamadı.</p>";
    }
    ?>
    </div>

    <div class="sayfalama">
        <?php if($toplam_sayfa > 1): ?>
            <?php for($i = 1; $i <= $toplam_sayfa; $i++): ?>
                <?php 
                    // Sayfa geçişlerinde filtreleri (kategori ve arama) korumak için URL parametreleri
                    $url_parametreleri = "sayfa=$i";
                    if($kat_id > 0) $url_parametreleri .= "&kat=$kat_id";
                    if(!empty($kelime)) $url_parametreleri .= "&arama=".urlencode($kelime);
                ?>
                <a href="index.php?<?= $url_parametreleri ?>" class="<?= ($i == $mevcut_sayfa) ? 'aktif' : ''; ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
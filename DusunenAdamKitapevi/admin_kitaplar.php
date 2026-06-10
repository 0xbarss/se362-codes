<?php
require_once 'baglanti.php';

// Güvenlik: Sadece adminler girebilir
if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'admin') {
    die("Erişim engellendi!");
}

$mesaj = "";

// --- SAYFALAMA AYARLARI ---
$sayfa_basina_kitap = 10;
$mevcut_sayfa = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if($mevcut_sayfa < 1) $mevcut_sayfa = 1;
$offset = ($mevcut_sayfa - 1) * $sayfa_basina_kitap;

$toplam_sorgu = mysqli_query($conn, "SELECT COUNT(*) as toplam FROM kitaplar");
$toplam_kitap_sayisi = mysqli_fetch_assoc($toplam_sorgu)['toplam'];
$toplam_sayfa = ceil($toplam_kitap_sayisi / $sayfa_basina_kitap);
// --------------------------

// 1. YENİ KİTAP EKLEME İŞLEMİ
if(isset($_POST['kitap_ekle'])) {
    $ad = mysqli_real_escape_string($conn, $_POST['kitap_adi']);
    $yazar = mysqli_real_escape_string($conn, $_POST['yazar']);
    $kat_id = (int)$_POST['kategori_id'];
    $fiyat = (float)$_POST['fiyat'];
    $stok = (int)$_POST['stok'];
    $resim_adi = 'varsayilan.jpg';

    if(isset($_FILES['kitap_resmi']) && $_FILES['kitap_resmi']['error'] == 0) {
        $uzanti = pathinfo($_FILES['kitap_resmi']['name'], PATHINFO_EXTENSION);
        $yeni_isim = time() . '_' . rand(100, 999) . '.' . $uzanti;
        if(move_uploaded_file($_FILES['kitap_resmi']['tmp_name'], "resimler/" . $yeni_isim)) {
            $resim_adi = $yeni_isim;
        }
    }

    $sorgu = "INSERT INTO kitaplar (kategori_id, kitap_adi, yazar, fiyat, stok, resim) VALUES ($kat_id, '$ad', '$yazar', $fiyat, $stok, '$resim_adi')";
    if(mysqli_query($conn, $sorgu)) {
        $mesaj = "<div class='basarili'>✅ Yeni kitap başarıyla eklendi!</div>";
    }
}

// 2. KİTAP GÜNCELLEME İŞLEMİ
if(isset($_POST['guncelle'])) {
    $id = (int)$_POST['kitap_id'];
    $f = (float)$_POST['yeni_fiyat'];
    $s = (int)$_POST['yeni_stok'];
    $k = (int)$_POST['yeni_kategori'];
    $sat = (int)$_POST['yeni_satilan'];
    
    $resim_ek = "";
    if(isset($_FILES['yeni_resim']) && $_FILES['yeni_resim']['error'] == 0) {
        $isim = time() . '_up_' . rand(100, 999) . '.' . pathinfo($_FILES['yeni_resim']['name'], PATHINFO_EXTENSION);
        if(move_uploaded_file($_FILES['yeni_resim']['tmp_name'], "resimler/" . $isim)) {
            $resim_ek = ", resim = '$isim'";
        }
    }

    $sql = "UPDATE kitaplar SET fiyat=$f, stok=$s, kategori_id=$k, satilan_adet=$sat $resim_ek WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        $mesaj = "<div class='basarili'>✅ Kitap bilgileri güncellendi!</div>";
        // Eğer stok 0'dan büyük yapıldıysa, bekleyen bildirimleri otomatik temizle
        if($s > 0) mysqli_query($conn, "DELETE FROM stok_bildirim WHERE kitap_id = $id");
    }
}

// Kategorileri dropdown için çekelim
$kategoriler = mysqli_query($conn, "SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
$kat_listesi = mysqli_fetch_all($kategoriler, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Düşünen Adam Kitapevi - Kitap Yönetimi</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; margin: 0; }
        .admin-nav { background: #343a40; padding: 15px; margin-bottom: 25px; border-radius: 5px; }
        .admin-nav a { color: #fff; margin-right: 15px; text-decoration: none; font-weight: bold; }
        .panel-bolum { background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .panel-bolum h2 { margin-top: 0; border-bottom: 2px solid #eee; padding-bottom: 10px; color: #333; }
        
        /* Bildirim Paneli Özel Stili */
        .bildirim-panel { background: #fff8e1; border: 1px solid #ffe082; }
        .bildirim-panel h2 { color: #d84315; border-bottom-color: #ffe082; }

        /* Form Tasarımı */
        .form-grup { margin-bottom: 15px; display: inline-block; margin-right: 15px; vertical-align: top; }
        .form-grup label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        .form-grup input, .form-grup select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        
        .btn-ekle { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-guncelle { background: #007bff; color: white; border: none; padding: 6px 12px; cursor: pointer; border-radius: 3px; font-weight: bold; }
        
        /* Dosya Seç Buton Stili */
        input[type="file"]::file-selector-button { background: #007bff; color: white; border: none; padding: 6px 10px; border-radius: 3px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f8f9fa; }

        .sayfalama { margin-top: 20px; text-align: center; }
        .sayfalama a { display: inline-block; padding: 8px 16px; margin: 0 4px; background: #fff; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px; }
        .sayfalama a.aktif { background: #007bff; color: white; border-color: #007bff; }
        
        .basarili { background: #d4edda; padding: 10px; margin-bottom: 15px; color: #155724; border-radius: 4px; font-weight: bold; }
        .bildirim-rozet { background: #ffc107; padding: 4px 8px; border-radius: 10px; font-weight: bold; font-size: 12px; color: #333; }
    </style>
</head>
<body>

    <div class="admin-nav">
        <a href="admin.php">📊 Özet Paneli</a>
        <a href="admin_kitaplar.php" style="color:#ffc107;">📚 Kitap Yönetimi</a>
        <a href="admin_siparisler.php">📦 Sipariş Yönetimi</a>
        <a href="admin_kullanicilar.php">👥 Kullanıcı Yönetimi</a>
        <a href="index.php" style="color: #17a2b8;">🏠 Siteye Dön</a>
        <a href="cikis.php" style="float:right; color:#ff4d4d;">🚪 Çıkış</a>
    </div>

    <?php echo $mesaj; ?>

    <?php
    $bildirim_sorgu = mysqli_query($conn, "SELECT k.kitap_adi, k.id as kid, COUNT(sb.id) as talep_sayisi 
                                           FROM stok_bildirim sb 
                                           JOIN kitaplar k ON sb.kitap_id = k.id 
                                           GROUP BY sb.kitap_id 
                                           ORDER BY talep_sayisi DESC");
    if(mysqli_num_rows($bildirim_sorgu) > 0):
    ?>
    <div class="panel-bolum bildirim-panel">
        <h2>🔔 Stoka Girmesini Bekleyen Kitaplar</h2>
        <table>
            <tr>
                <th>Kitap Adı (ID)</th>
                <th>Bekleyen Kişi Sayısı</th>
                <th>Bilgi</th>
            </tr>
            <?php while($b = mysqli_fetch_assoc($bildirim_sorgu)): ?>
            <tr>
                <td><strong><?= htmlspecialchars($b['kitap_adi']) ?></strong> (<?= $b['kid'] ?>)</td>
                <td><span class="bildirim-rozet">🔥 <?= $b['talep_sayisi'] ?> Kişi Bekliyor</span></td>
                <td style="font-size: 12px; color: #666;">Aşağıdaki listeden stok miktarını 0'dan büyük bir değere güncellediğinizde bu bildirimler otomatik temizlenir.</td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <?php endif; ?>

    <div class="panel-bolum">
        <h2>➕ Yeni Kitap Ekle</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grup">
                <label>Kategori</label>
                <select name="kategori_id" required>
                    <option value="">Seçiniz...</option>
                    <?php foreach($kat_listesi as $ct) echo "<option value='{$ct['id']}'>{$ct['kategori_adi']}</option>"; ?>
                </select>
            </div>
            <div class="form-grup">
                <label>Kitap Adı</label>
                <input type="text" name="kitap_adi" required>
            </div>
            <div class="form-grup">
                <label>Yazar</label>
                <input type="text" name="yazar">
            </div>
            <div class="form-grup">
                <label>Fiyat (TL)</label>
                <input type="number" step="0.01" name="fiyat" required style="width:80px;">
            </div>
            <div class="form-grup">
                <label>Stok</label>
                <input type="number" name="stok" required style="width:70px;">
            </div>
            <div class="form-grup">
                <label>Kitap Resmi</label>
                <input type="file" name="kitap_resmi" accept="image/*">
            </div>
            <div style="margin-top: 10px;">
                <button type="submit" name="kitap_ekle" class="btn-ekle">Kitabı Kaydet</button>
            </div>
        </form>
    </div>

    <div class="panel-bolum">
        <h2>📚 Kitap Listesi (Sayfa <?php echo $mevcut_sayfa; ?> / <?php echo $toplam_sayfa; ?>)</h2>
        <table>
            <tr>
                <th>Resim</th>
                <th>Kitap Bilgisi</th>
                <th>Kategori</th>
                <th>Fiyat</th>
                <th>Stok</th>
                <th>Satılan</th>
                <th>İşlem</th>
            </tr>
            <?php
            $sql_list = "SELECT * FROM kitaplar ORDER BY id DESC LIMIT $sayfa_basina_kitap OFFSET $offset";
            $sonuc = mysqli_query($conn, $sql_list);
            while($k = mysqli_fetch_assoc($sonuc)) {
                $img = $k['resim'] ? $k['resim'] : "varsayilan.jpg";
                echo "<tr><form method='POST' enctype='multipart/form-data'>
                    <td style='text-align:center;'>
                        <img src='resimler/$img' width='45' style='border-radius:4px;'><br>
                        <input type='file' name='yeni_resim' style='width:120px; font-size:10px; margin-top:5px;'>
                    </td>
                    <td><small>#{$k['id']}</small><br><strong>{$k['kitap_adi']}</strong><br><small>{$k['yazar']}</small></td>
                    <td><select name='yeni_kategori'>";
                    foreach($kat_listesi as $ct) {
                        $s = ($ct['id'] == $k['kategori_id']) ? "selected" : "";
                        echo "<option value='{$ct['id']}' $s>{$ct['kategori_adi']}</option>";
                    }
                echo "</select></td>
                    <td><input type='number' step='0.01' name='yeni_fiyat' value='{$k['fiyat']}' style='width:70px;'></td>
                    <td><input type='number' name='yeni_stok' value='{$k['stok']}' style='width:55px;'></td>
                    <td><input type='number' name='yeni_satilan' value='{$k['satilan_adet']}' style='width:55px; color:green; font-weight:bold;'></td>
                    <td>
                        <input type='hidden' name='kitap_id' value='{$k['id']}'>
                        <button type='submit' name='guncelle' class='btn-guncelle'>Güncelle</button>
                    </td>
                </form></tr>";
            }
            ?>
        </table>

        <div class="sayfalama">
            <?php
            for($i = 1; $i <= $toplam_sayfa; $i++) {
                $aktif = ($i == $mevcut_sayfa) ? "aktif" : "";
                echo "<a href='admin_kitaplar.php?p=$i' class='$aktif'>$i</a>";
            }
            ?>
        </div>
    </div>

</body>
</html>
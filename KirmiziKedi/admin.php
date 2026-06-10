<?php
session_start();

// --- GÜVENLİK VE YÖNLENDİRME ---
if(!isset($_SESSION['rol'])) {
    header("Location: login.php");
    exit();
}
if($_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}

$bag = new mysqli("localhost", "root", "", "kitapci_db");
$bag->set_charset("utf8mb4");

// 1. KİTAP EKLEME
if(isset($_POST['ekle'])){
    $b = $_POST['b']; $k = $_POST['k']; $f = $_POST['f']; $s = $_POST['s'];
    $bag->query("INSERT INTO kitaplar (kategori_id, baslik, fiyat, stok) VALUES ($k, '$b', '$f', $s)");
}

// 2. KİTAP GÜNCELLEME
if(isset($_POST['guncelle'])){
    $id = $_POST['id']; $b = $_POST['b']; $f = $_POST['f']; $s = $_POST['s'];
    $bag->query("UPDATE kitaplar SET baslik='$b', fiyat='$f', stok='$s' WHERE id=$id");
}

// 3. SİPARİŞ DURUM GÜNCELLEME
if(isset($_POST['d_set'])){
    $sid = $_POST['sid']; $d = $_POST['durum'];
    $bag->query("UPDATE siparisler SET durum='$d' WHERE id=$sid");
}
?>
<!DOCTYPE html>
<html>
<head><title>Admin Paneli</title></head>
<body style="font-family:sans-serif; background:#f4f4f4; padding:20px;">
    
    <div style="background:#2c3e50; color:white; padding:15px; border-radius:5px;">
        <h2 style="margin:0; display:inline-block;">🛠️ ADMİN YÖNETİM MERKEZİ</h2>
        <a href="login.php" style="color:red; float:right; text-decoration:none; font-weight:bold;">ÇIKIŞ YAP</a>
    </div>

    <div style="background:white; padding:20px; margin-top:20px; border:1px solid #ddd;">
        <h3>➕ Yeni Kitap Ekle</h3>
        <form method="POST">
            <input type="text" name="b" placeholder="Kitap Adı" required>
            <select name="k">
                <?php 
                $kat = $bag->query("SELECT * FROM kategoriler"); 
                while($kt = $kat->fetch_assoc()) echo "<option value='{$kt['id']}'>{$kt['kategori_adi']}</option>"; 
                ?>
            </select>
            <input type="text" name="f" placeholder="Fiyat (150.00)" size="10">
            <input type="number" name="s" placeholder="Stok" size="5">
            <button name="ekle" style="background:green; color:white;">EKLE</button>
        </form>
    </div>

    <div style="background:white; padding:20px; margin-top:20px; border:1px solid #ddd;">
        <h3>📚 Mevcut Kitapları Düzenle</h3>
        <table border="1" width="100%" cellpadding="8" style="border-collapse:collapse;">
            <tr style="background:#eee;"><th>Kitap Adı</th><th>Fiyat</th><th>Stok</th><th>İşlem</th></tr>
            <?php
            $kitaplar = $bag->query("SELECT * FROM kitaplar ORDER BY id DESC");
            while($k = $kitaplar->fetch_assoc()){
                echo "<tr>
                <form method='POST'>
                    <td><input type='text' name='b' value='{$k['baslik']}'></td>
                    <td><input type='text' name='f' value='{$k['fiyat']}' size='5'> TL</td>
                    <td><input type='number' name='s' value='{$k['stok']}' style='width:50px;'></td>
                    <td><input type='hidden' name='id' value='{$k['id']}'><button name='guncelle' style='background:blue; color:white;'>KAYDET</button></td>
                </form>
                </tr>";
            }
            ?>
        </table>
    </div>

    <div style="background:white; padding:20px; margin-top:20px; border:1px solid #ddd;">
        <h3>📦 Tüm Siparişler</h3>
        <table border="1" width="100%" cellpadding="8" style="border-collapse:collapse;">
            <tr style="background:#eee;"><th>Müşteri</th><th>Kitap</th><th>Tutar</th><th>Durum</th><th>İşlem</th></tr>
            <?php
            $sip = $bag->query("SELECT s.*, k.baslik, u.kullanici_adi FROM siparisler s JOIN kitaplar k ON s.kitap_id=k.id JOIN kullanicilar u ON s.kullanici_id=u.id ORDER BY s.id DESC");
            while($s = $sip->fetch_assoc()){
                echo "<tr>
                <td>{$s['kullanici_adi']}</td>
                <td>{$s['baslik']}</td>
                <td>{$s['toplam_fiyat']} TL</td>
                <td><b>{$s['durum']}</b></td>
                <td>
                    <form method='POST'>
                        <input type='hidden' name='sid' value='{$s['id']}'>
                        <select name='durum'>
                            <option value='Hazırlanıyor' ".($s['durum']=='Hazırlanıyor'?'selected':'').">Hazırlanıyor</option>
                            <option value='Kargoya Verildi' ".($s['durum']=='Kargoya Verildi'?'selected':'').">Kargoya Verildi</option>
                            <option value='Teslim Edildi' ".($s['durum']=='Teslim Edildi'?'selected':'').">Teslim Edildi</option>
                        </select>
                        <button name='d_set'>GÜNCELLE</button>
                    </form>
                </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
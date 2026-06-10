<?php
session_start();
// Giriş kontrolü
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- ADMIN KONTROLÜ: Admin buraya giremez, admin.php'ye gitsin ---
if($_SESSION['rol'] == 'admin') {
    header("Location: admin.php");
    exit();
}

$bag = new mysqli("localhost", "root", "", "kitapci_db");
$bag->set_charset("utf8mb4");

// Satın Alma İşlemi
if(isset($_GET['al'])){
    $kid = intval($_GET['al']); $uid = $_SESSION['user_id'];
    $kitap = $bag->query("SELECT * FROM kitaplar WHERE id=$kid")->fetch_assoc();
    if($kitap['stok'] > 0){
        $bag->query("UPDATE kitaplar SET stok = stok - 1 WHERE id=$kid");
        $fiyat = $kitap['fiyat'];
        $bag->query("INSERT INTO siparisler (kullanici_id, kitap_id, toplam_fiyat) VALUES ($uid, $kid, $fiyat)");
        header("Location: index.php?islem=tamam");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<body style="font-family:sans-serif; padding:20px;">
    <nav style="background:#333; padding:15px; color:white; border-radius:5px;">
        <b>📚 Kırmızı Kedi</b> | Hoşgeldin <?= $_SESSION['kadi'] ?> | 
        <a href="index.php" style="color:white;">Market</a> | 
        <a href="siparislerim.php" style="color:white;">Siparişlerim</a> | 
        <a href="login.php" style="color:red;">Çıkış</a>
    </nav>
    
    <h2>Kitap Listesi</h2>
    <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse;">
        <tr style="background:#f9f9f9;"><th>Kitap Adı</th><th>Fiyat</th><th>Stok</th><th>İşlem</th></tr>
        <?php
        $res = $bag->query("SELECT * FROM kitaplar");
        while($k = $res->fetch_assoc()){
            echo "<tr>
                <td>{$k['baslik']}</td>
                <td>{$k['fiyat']} TL</td>
                <td>{$k['stok']}</td>
                <td><a href='index.php?al={$k['id']}' style='color:green; font-weight:bold;'>🛒 SATIN AL</a></td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
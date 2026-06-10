<?php
session_start();
$bag = new mysqli("localhost", "root", "", "kitapci_db");
$bag->set_charset("utf8mb4");

if(isset($_POST['giris'])){
    $kadi = $_POST['kadi']; $sifre = $_POST['sifre'];
    $res = $bag->query("SELECT * FROM kullanicilar WHERE kullanici_adi='$kadi' AND sifre='$sifre'");
    
    if($user = $res->fetch_assoc()){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['rol'] = $user['rol'];
        $_SESSION['kadi'] = $user['kullanici_adi'];
        
        // --- ROL KONTROLÜ VE YÖNLENDİRME ---
        if($user['rol'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else { $hata = "Kullanıcı adı veya şifre hatalı!"; }
}
?>
<!DOCTYPE html>
<html>
<body style="font-family:sans-serif; text-align:center; padding-top:100px; background:#f4f4f4;">
    <form method="POST" style="display:inline-block; border:1px solid #ccc; padding:30px; background:white; border-radius:10px;">
        <h2>📚 Kırmızı Kedi</h2>
        <?php if(isset($hata)) echo "<p style='color:red'>$hata</p>"; ?>
        <input type="text" name="kadi" placeholder="Kullanıcı Adı" required style="padding:10px;"><br><br>
        <input type="password" name="sifre" placeholder="Şifre" required style="padding:10px;"><br><br>
        <button type="submit" name="giris" style="padding:10px 20px; background:#2c3e50; color:white; border:none; cursor:pointer;">Giriş Yap</button>
    </form>
</body>
</html>
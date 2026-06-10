<?php
require_once 'baglanti.php';

$hata = "";
$mesaj = "";

// KAYIT OLMA İŞLEMİ
if (isset($_POST['kayit_ol'])) {
    $ad = mysqli_real_escape_string($conn, $_POST['ad_soyad']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sifre = $_POST['sifre'];
    $adres = mysqli_real_escape_string($conn, $_POST['adres']);

    // Email kontrolü
    $kontrol = mysqli_query($conn, "SELECT id FROM kullanicilar WHERE email = '$email'");
    if (mysqli_num_rows($kontrol) > 0) {
        $hata = "Bu email adresi zaten kayıtlı!";
    } else {
        $sorgu = "INSERT INTO kullanicilar (ad_soyad, email, sifre, adres) VALUES ('$ad', '$email', '$sifre', '$adres')";
        if (mysqli_query($conn, $sorgu)) {
            $mesaj = "Başarıyla kayıt oldunuz. Giriş yapabilirsiniz.";
        } else {
            $hata = "Kayıt sırasında bir hata oluştu.";
        }
    }
}

// GİRİŞ YAPMA İŞLEMİ
if (isset($_POST['giris_yap'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sifre = $_POST['sifre'];

    $sorgu = "SELECT * FROM kullanicilar WHERE email = '$email' AND sifre = '$sifre'";
    $sonuc = mysqli_query($conn, $sorgu);

    if (mysqli_num_rows($sonuc) == 1) {
        $kullanici = mysqli_fetch_assoc($sonuc);
        $_SESSION['kullanici_id'] = $kullanici['id'];
        $_SESSION['ad_soyad'] = $kullanici['ad_soyad'];
        $_SESSION['rol'] = $kullanici['rol'];

        if ($kullanici['rol'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $hata = "Hatalı email veya şifre!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Giriş / Kayıt</title>
    <style>
        .form-kutu { width: 300px; float: left; margin: 20px; padding: 20px; border: 1px solid #ccc; background: #f4f4f4; }
        .hata { color: red; }
        .mesaj { color: green; }
    </style>
</head>
<body>
    <a href="index.php">← Ana Sayfaya Dön</a>
    <hr>
    <?php if($hata) echo "<p class='hata'>$hata</p>"; ?>
    <?php if($mesaj) echo "<p class='mesaj'>$mesaj</p>"; ?>

    <div class="form-kutu">
        <h2>Giriş Yap</h2>
        <form method="POST">
            Email:<br> <input type="email" name="email" required><br><br>
            Şifre:<br> <input type="password" name="sifre" required><br><br>
            <button type="submit" name="giris_yap">Giriş Yap</button>
        </form>
    </div>

    <div class="form-kutu">
        <h2>Kayıt Ol</h2>
        <form method="POST">
            Ad Soyad:<br> <input type="text" name="ad_soyad" required><br><br>
            Email:<br> <input type="email" name="email" required><br><br>
            Şifre:<br> <input type="password" name="sifre" required><br><br>
            Adres:<br> <textarea name="adres" required></textarea><br><br>
            <button type="submit" name="kayit_ol">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>
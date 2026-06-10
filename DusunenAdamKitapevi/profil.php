<?php
require_once 'baglanti.php';

// Oturum kontrolü
if(!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$mesaj = "";
$hata = "";

// BİLGİ GÜNCELLEME İŞLEMİ
if(isset($_POST['guncelle'])) {
    $ad_soyad = mysqli_real_escape_string($conn, $_POST['ad_soyad']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $adres = mysqli_real_escape_string($conn, $_POST['adres']);
    
    // Şifre değişkenlerini al
    $eski_sifre = $_POST['eski_sifre'];
    $yeni_sifre = $_POST['yeni_sifre'];
    $yeni_sifre_tekrar = $_POST['yeni_sifre_tekrar'];

    // Yeni girilen email başka bir kullanıcıya ait mi kontrol et
    $email_kontrol = mysqli_query($conn, "SELECT id FROM kullanicilar WHERE email = '$email' AND id != $kullanici_id");
    
    if(mysqli_num_rows($email_kontrol) > 0) {
        $hata = "Bu e-posta adresi başka bir hesap tarafından kullanılıyor.";
    } else {
        
        // Kullanıcı şifre değiştirmek istiyor mu? (Alanlardan herhangi biri dolu mu)
        if(!empty($eski_sifre) || !empty($yeni_sifre) || !empty($yeni_sifre_tekrar)) {
            
            // Tüm şifre alanları eksiksiz doldurulmuş mu?
            if(empty($eski_sifre) || empty($yeni_sifre) || empty($yeni_sifre_tekrar)) {
                $hata = "Şifrenizi değiştirmek için tüm şifre alanlarını doldurmalısınız.";
            } 
            // Yeni şifreler birbiriyle uyuşuyor mu?
            elseif($yeni_sifre !== $yeni_sifre_tekrar) {
                $hata = "Girdiğiniz yeni şifreler birbiriyle eşleşmiyor.";
            } 
            else {
                // Eski şifre veritabanındaki ile aynı mı kontrol et
                $mevcut_sifre_sorgu = mysqli_query($conn, "SELECT sifre FROM kullanicilar WHERE id = $kullanici_id");
                $mevcut_sifre_veri = mysqli_fetch_assoc($mevcut_sifre_sorgu);
                
                if($eski_sifre !== $mevcut_sifre_veri['sifre']) {
                    $hata = "Mevcut şifrenizi hatalı girdiniz.";
                } else {
                    // Her şey doğru, şifre dahil güncelle
                    $sorgu = "UPDATE kullanicilar SET ad_soyad = '$ad_soyad', email = '$email', adres = '$adres', sifre = '$yeni_sifre' WHERE id = $kullanici_id";
                    if(mysqli_query($conn, $sorgu)) {
                        $mesaj = "Bilgileriniz ve şifreniz başarıyla güncellendi.";
                        $_SESSION['ad_soyad'] = $ad_soyad; // Oturum ismini de güncelle
                    } else {
                        $hata = "Güncelleme sırasında bir hata oluştu.";
                    }
                }
            }
            
        } else {
            // Şifre alanları boş, sadece diğer bilgileri (ad, email, adres) güncelle
            $sorgu = "UPDATE kullanicilar SET ad_soyad = '$ad_soyad', email = '$email', adres = '$adres' WHERE id = $kullanici_id";
            if(mysqli_query($conn, $sorgu)) {
                $mesaj = "Bilgileriniz başarıyla güncellendi.";
                $_SESSION['ad_soyad'] = $ad_soyad;
            } else {
                $hata = "Güncelleme sırasında bir hata oluştu.";
            }
        }
    }
}

// KULLANICININ MEVCUT BİLGİLERİNİ FORMA YAZDIRMAK İÇİN ÇEK
$kullanici_sorgu = mysqli_query($conn, "SELECT * FROM kullanicilar WHERE id = $kullanici_id");
$kullanici = mysqli_fetch_assoc($kullanici_sorgu);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profilim</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .profil-kutu { background: #fff; border: 1px solid #ddd; padding: 20px; border-radius: 8px; width: 100%; max-width: 500px; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-grup { margin-bottom: 15px; }
        .form-grup label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        .form-grup input[type="text"], .form-grup input[type="email"], .form-grup input[type="password"], .form-grup textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .buton { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-size: 16px; width: 100%; }
        .buton:hover { background: #0056b3; }
        .mesaj { color: green; font-weight: bold; text-align: center; margin-bottom: 15px; padding: 10px; background: #e6ffe6; border: 1px solid #b3ffb3; border-radius: 4px;}
        .hata { color: red; font-weight: bold; text-align: center; margin-bottom: 15px; padding: 10px; background: #ffe6e6; border: 1px solid #ffb3b3; border-radius: 4px;}
        .nav-link { color: #007bff; text-decoration: none; display: inline-block; margin-bottom: 20px; }
        .bilgi-notu { font-size: 12px; color: #666; margin-top: 5px; }
        .sifre-alani { background-color: #f9f9f9; padding: 15px; border: 1px dashed #ccc; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <a href="index.php" class="nav-link">← Ana Sayfaya Dön</a>
    
    <div class="profil-kutu">
        <h2 style="text-align:center; margin-top:0;">Profil Bilgilerim</h2>

        <?php if($mesaj) echo "<div class='mesaj'>$mesaj</div>"; ?>
        <?php if($hata) echo "<div class='hata'>$hata</div>"; ?>

        <form method="POST">
            <div class="form-grup">
                <label>Ad Soyad:</label>
                <input type="text" name="ad_soyad" value="<?php echo htmlspecialchars($kullanici['ad_soyad']); ?>" required>
            </div>

            <div class="form-grup">
                <label>E-posta Adresi:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($kullanici['email']); ?>" required>
            </div>

            <div class="form-grup">
                <label>Adres (Teslimatlar İçin):</label>
                <textarea name="adres" rows="4" required><?php echo htmlspecialchars($kullanici['adres']); ?></textarea>
            </div>

            <div class="sifre-alani">
                <h4 style="margin-top:0; color:#555;">Şifre Değiştir</h4>
                
                <div class="form-grup">
                    <label>Eski Şifreniz:</label>
                    <input type="password" name="eski_sifre" placeholder="Mevcut şifrenizi girin">
                </div>

                <div class="form-grup">
                    <label>Yeni Şifre:</label>
                    <input type="password" name="yeni_sifre" placeholder="Yeni şifrenizi girin">
                </div>

                <div class="form-grup">
                    <label>Yeni Şifre (Tekrar):</label>
                    <input type="password" name="yeni_sifre_tekrar" placeholder="Yeni şifrenizi tekrar girin">
                </div>
                
                <div class="bilgi-notu">* Şifrenizi değiştirmek istemiyorsanız yukarıdaki 3 şifre alanını da boş bırakabilirsiniz.</div>
            </div>

            <button type="submit" name="guncelle" class="buton">Bilgilerimi Güncelle</button>
        </form>
    </div>

</body>
</html>
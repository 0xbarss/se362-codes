<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];
    $sifre_tekrar = $_POST['sifre_tekrar'];
    $ad_soyad = trim($_POST['ad_soyad']);
    
    if (empty($kullanici_adi) || empty($email) || empty($sifre) || empty($ad_soyad)) {
        $error = 'Tüm alanlar gereklidir.';
    } elseif ($sifre !== $sifre_tekrar) {
        $error = 'Şifreler eşleşmiyor.';
    } else {
        // Check if username or email already exists
        $stmt = $db->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ? OR email = ?");
        $stmt->execute([$kullanici_adi, $email]);
        
        if ($stmt->fetch()) {
            $error = 'Bu kullanıcı adı veya email zaten kullanımda.';
        } else {
            $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);
            
            $stmt = $db->prepare("INSERT INTO kullanicilar (kullanici_adi, email, sifre, ad_soyad, kayit_tarihi) VALUES (?, ?, ?, ?, NOW())");
            if ($stmt->execute([$kullanici_adi, $email, $hashed_password, $ad_soyad])) {
                $_SESSION['success_message'] = 'Kayıt başarılı. Giriş yapabilirsiniz.';
                redirect('giris.php');
            } else {
                $error = 'Kayıt sırasında bir hata oluştu.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Kayıt Ol</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form action="kayit.php" method="post">
    <div class="form-group">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required>
    </div>
    
    <div class="form-group">
        <label for="sifre_tekrar">Şifre Tekrar:</label>
        <input type="password" id="sifre_tekrar" name="sifre_tekrar" required>
    </div>
    
    <div class="form-group">
        <label for="ad_soyad">Ad Soyad:</label>
        <input type="text" id="ad_soyad" name="ad_soyad" required>
    </div>
    
    <button type="submit" class="btn">Kayıt Ol</button>
</form>

<p>Zaten hesabınız var mı? <a href="giris.php">Giriş yapın</a></p>

<?php
require_once 'includes/footer.php';
?>
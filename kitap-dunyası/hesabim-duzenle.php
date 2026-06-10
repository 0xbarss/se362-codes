<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('giris.php');
}

// Get current user information
$stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad_soyad = trim($_POST['ad_soyad']);
    $email = trim($_POST['email']);
    $telefon = trim($_POST['telefon']);
    $adres = trim($_POST['adres']);
    $mevcut_sifre = $_POST['mevcut_sifre'];
    $yeni_sifre = $_POST['yeni_sifre'];
    $yeni_sifre_tekrar = $_POST['yeni_sifre_tekrar'];

    // Basic validation
    if (empty($ad_soyad) || empty($email)) {
        $error = 'Ad Soyad ve Email alanları zorunludur.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Geçersiz email formatı.';
    } elseif ($db->query("SELECT id FROM kullanicilar WHERE email = '$email' AND id != ".$user['id'])->rowCount() > 0) {
        $error = 'Bu email adresi başka bir kullanıcı tarafından kullanılıyor.';
    } else {
        // Password change logic
        $password_changed = false;
        if (!empty($yeni_sifre)) {
            if (empty($mevcut_sifre)) {
                $error = 'Mevcut şifrenizi girmelisiniz.';
            } elseif (!password_verify($mevcut_sifre, $user['sifre'])) {
                $error = 'Mevcut şifreniz yanlış.';
            } elseif ($yeni_sifre !== $yeni_sifre_tekrar) {
                $error = 'Yeni şifreler eşleşmiyor.';
            } elseif (strlen($yeni_sifre) < 6) {
                $error = 'Yeni şifre en az 6 karakter olmalıdır.';
            } else {
                $password_changed = true;
            }
        }

        if (empty($error)) {
            try {
                if ($password_changed) {
                    $hashed_password = password_hash($yeni_sifre, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, email = ?, telefon = ?, adres = ?, sifre = ? WHERE id = ?");
                    $stmt->execute([$ad_soyad, $email, $telefon, $adres, $hashed_password, $_SESSION['user_id']]);
                } else {
                    $stmt = $db->prepare("UPDATE kullanicilar SET ad_soyad = ?, email = ?, telefon = ?, adres = ? WHERE id = ?");
                    $stmt->execute([$ad_soyad, $email, $telefon, $adres, $_SESSION['user_id']]);
                }

                $success = 'Profil bilgileriniz başarıyla güncellendi.';
                
                // Update session variables if needed
                $_SESSION['email'] = $email;
                
                // Refresh user data
                $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $error = 'Bir hata oluştu: ' . $e->getMessage();
            }
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Profil Bilgilerimi Düzenle</h2>

<?php if (!empty($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<form action="hesabim-duzenle.php" method="post">
    <div class="form-group">
        <label for="kullanici_adi">Kullanıcı Adı:</label>
        <input type="text" id="kullanici_adi" value="<?php echo htmlspecialchars($user['kullanici_adi']); ?>" disabled>
        <small>Kullanıcı adı değiştirilemez.</small>
    </div>
    
    <div class="form-group">
        <label for="ad_soyad">Ad Soyad:*</label>
        <input type="text" id="ad_soyad" name="ad_soyad" value="<?php echo htmlspecialchars($user['ad_soyad']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:*</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>
    
    <div class="form-group">
        <label for="telefon">Telefon:</label>
        <input type="text" id="telefon" name="telefon" value="<?php echo htmlspecialchars($user['telefon']); ?>">
    </div>
    
    <div class="form-group">
        <label for="adres">Adres:</label>
        <textarea id="adres" name="adres" rows="4"><?php echo htmlspecialchars($user['adres']); ?></textarea>
    </div>
    
    <h3>Şifre Değiştir (İsteğe Bağlı)</h3>
    
    <div class="form-group">
        <label for="mevcut_sifre">Mevcut Şifre:</label>
        <input type="password" id="mevcut_sifre" name="mevcut_sifre">
    </div>
    
    <div class="form-group">
        <label for="yeni_sifre">Yeni Şifre:</label>
        <input type="password" id="yeni_sifre" name="yeni_sifre">
        <small>En az 6 karakter olmalıdır.</small>
    </div>
    
    <div class="form-group">
        <label for="yeni_sifre_tekrar">Yeni Şifre (Tekrar):</label>
        <input type="password" id="yeni_sifre_tekrar" name="yeni_sifre_tekrar">
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn">Bilgilerimi Güncelle</button>
        <a href="hesabim.php" class="btn">İptal</a>
    </div>
</form>

<?php
require_once 'includes/footer.php';
?>
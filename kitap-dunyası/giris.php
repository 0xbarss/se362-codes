<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $sifre = $_POST['sifre'];
    
    if (empty($kullanici_adi) || empty($sifre)) {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } else {
        $stmt = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ? OR email = ?");
        $stmt->execute([$kullanici_adi, $kullanici_adi]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($sifre, $user['sifre'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
			$_SESSION['admin'] = $user['admin'];
			
            redirect('index.php');
        } else {
            $error = 'Kullanıcı adı/email veya şifre hatalı.';
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Giriş Yap</h2>

<?php if ($error): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form action="giris.php" method="post">
    <div class="form-group">
        <label for="kullanici_adi">Kullanıcı Adı veya Email:</label>
        <input type="text" id="kullanici_adi" name="kullanici_adi" required>
    </div>
    
    <div class="form-group">
        <label for="sifre">Şifre:</label>
        <input type="password" id="sifre" name="sifre" required>
    </div>
    
    <button type="submit" class="btn">Giriş Yap</button>
</form>

<p>Hesabınız yok mu? <a href="kayit.php">Kayıt olun</a></p>

<?php
require_once 'includes/footer.php';
?>
<?php include 'header.php'; require_once 'db.php';
if($_POST){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['un']]);
    $u = $stmt->fetch();
    if($u && password_verify($_POST['pw'], $u['password'])){
        $_SESSION['user_id'] = $u['id']; $_SESSION['role_id'] = $u['role_id']; $_SESSION['full_name'] = $u['full_name'];
        header("Location: dashboard.php");
    } else { echo "<p style='color:red'>Hatalı Giriş!</p>"; }
}
?>
<div class="container" style="max-width: 400px; margin-top: 50px;">
    <h2>Klinik Girişi</h2>
    <form method="POST">
        Kullanıcı Adı: <input type="text" name="un" required>
        Şifre: <input type="password" name="pw" required>
        <button type="submit" class="btn btn-blue" style="width:100%">Giriş Yap</button>
    </form>
</div>
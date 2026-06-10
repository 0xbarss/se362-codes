<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../giris.php');
}

// Add new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kullanici_ekle'])) {
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $sifre = $_POST['sifre'];
    $ad_soyad = trim($_POST['ad_soyad']);
    $admin = isset($_POST['admin']) ? 1 : 0;
    
    $hashed_password = password_hash($sifre, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("INSERT INTO kullanicilar (kullanici_adi, email, sifre, ad_soyad, admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$kullanici_adi, $email, $hashed_password, $ad_soyad, $admin]);
    
    $_SESSION['success_message'] = 'Kullanıcı başarıyla eklendi.';
    redirect('kullanicilar.php');
}

// Update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kullanici_guncelle'])) {
    $id = (int)$_POST['id'];
    $kullanici_adi = trim($_POST['kullanici_adi']);
    $email = trim($_POST['email']);
    $ad_soyad = trim($_POST['ad_soyad']);
    $admin = isset($_POST['admin']) ? 1 : 0;
    
    $stmt = $db->prepare("UPDATE kullanicilar SET kullanici_adi = ?, email = ?, ad_soyad = ?, admin = ? WHERE id = ?");
    $stmt->execute([$kullanici_adi, $email, $ad_soyad, $admin, $id]);
    
    // Update password if provided
    if (!empty($_POST['sifre'])) {
        $hashed_password = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE kullanicilar SET sifre = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $id]);
    }
    
    $_SESSION['success_message'] = 'Kullanıcı başarıyla güncellendi.';
    redirect('kullanicilar.php');
}

// Delete user
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    // Don't allow deleting yourself
    if ($_GET['sil'] != $_SESSION['user_id']) {
        $stmt = $db->prepare("DELETE FROM kullanicilar WHERE id = ?");
        $stmt->execute([$_GET['sil']]);
        
        $_SESSION['success_message'] = 'Kullanıcı başarıyla silindi.';
    } else {
        $_SESSION['error_message'] = 'Kendi kullanıcı hesabınızı silemezsiniz.';
    }
    
    redirect('kullanicilar.php');
}

// Get all users
$kullanicilar = $db->query("SELECT id, kullanici_adi, email, ad_soyad, admin FROM kullanicilar ORDER BY kullanici_adi")->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<h2>Kullanıcı Yönetimi</h2>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<h3>Kullanıcı Ekle</h3>
<form action="kullanicilar.php" method="post">
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
        <label for="ad_soyad">Ad Soyad:</label>
        <input type="text" id="ad_soyad" name="ad_soyad" required>
    </div>
    
    <div class="form-group">
        <label>
            <input type="checkbox" name="admin" value="1"> Yönetici
        </label>
    </div>
    
    <button type="submit" name="kullanici_ekle" class="btn">Kullanıcı Ekle</button>
</form>

<h3>Kullanıcı Listesi</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>Email</th>
            <th>Ad Soyad</th>
            <th>Yönetici</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kullanicilar as $kullanici): ?>
            <tr>
                <td><?php echo $kullanici['id']; ?></td>
                <td><?php echo htmlspecialchars($kullanici['kullanici_adi']); ?></td>
                <td><?php echo htmlspecialchars($kullanici['email']); ?></td>
                <td><?php echo htmlspecialchars($kullanici['ad_soyad']); ?></td>
                <td><?php echo $kullanici['admin'] ? 'Evet' : 'Hayır'; ?></td>
                <td>
                    <a href="#" onclick="editUser(<?php echo $kullanici['id']; ?>)" class="btn">Düzenle</a>
                    <?php if ($kullanici['id'] != $_SESSION['user_id']): ?>
                        <a href="kullanicilar.php?sil=<?php echo $kullanici['id']; ?>" class="btn" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Edit User Modal -->
<div id="editModal" style="display:none;">
    <h3>Kullanıcı Düzenle</h3>
    <form id="editForm" method="post">
        <input type="hidden" name="id" id="edit_id">
        
        <div class="form-group">
            <label for="edit_kullanici_adi">Kullanıcı Adı:</label>
            <input type="text" id="edit_kullanici_adi" name="kullanici_adi" required>
        </div>
        
        <div class="form-group">
            <label for="edit_email">Email:</label>
            <input type="email" id="edit_email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="edit_sifre">Şifre (Değiştirmek istemiyorsanız boş bırakın):</label>
            <input type="password" id="edit_sifre" name="sifre">
        </div>
        
        <div class="form-group">
            <label for="edit_ad_soyad">Ad Soyad:</label>
            <input type="text" id="edit_ad_soyad" name="ad_soyad" required>
        </div>
        
        <div class="form-group">
            <label>
                <input type="checkbox" id="edit_admin" name="admin" value="1"> Yönetici
            </label>
        </div>
        
        <button type="submit" name="kullanici_guncelle" class="btn">Güncelle</button>
        <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn">İptal</button>
    </form>
</div>

<script>
function editUser(id) {
    fetch(`get_kullanici.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_kullanici_adi').value = data.kullanici_adi;
            document.getElementById('edit_email').value = data.email;
            document.getElementById('edit_ad_soyad').value = data.ad_soyad;
            document.getElementById('edit_admin').checked = data.admin == 1;
            
            document.getElementById('editModal').style.display = 'block';
        });
}
</script>

<?php
require_once 'footer.php';
?>
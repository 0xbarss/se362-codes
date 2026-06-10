<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../giris.php');
}

// Add new book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kitap_ekle'])) {
    $baslik = trim($_POST['baslik']);
    $yazar = trim($_POST['yazar']);
    $aciklama = trim($_POST['aciklama']);
    $fiyat = (float)$_POST['fiyat'];
    $stok = (int)$_POST['stok'];
    $kategori = trim($_POST['kategori']);
    
    // Handle image upload
    $kapak_resmi = 'default.jpg';
    if (isset($_FILES['kapak_resmi']) && $_FILES['kapak_resmi']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/';
        $file_ext = pathinfo($_FILES['kapak_resmi']['name'], PATHINFO_EXTENSION);
        $kapak_resmi = uniqid() . '.' . $file_ext;
        
        if (!move_uploaded_file($_FILES['kapak_resmi']['tmp_name'], $upload_dir . $kapak_resmi)) {
            $kapak_resmi = 'default.jpg';
        }
    }
    
    $stmt = $db->prepare("INSERT INTO kitaplar (baslik, yazar, aciklama, fiyat, stok, kategori, kapak_resmi) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$baslik, $yazar, $aciklama, $fiyat, $stok, $kategori, $kapak_resmi]);
    
    $_SESSION['success_message'] = 'Kitap başarıyla eklendi.';
    redirect('kitaplar.php');
}

// Update book
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kitap_guncelle'])) {
    $id = (int)$_POST['id'];
    $baslik = trim($_POST['baslik']);
    $yazar = trim($_POST['yazar']);
    $aciklama = trim($_POST['aciklama']);
    $fiyat = (float)$_POST['fiyat'];
    $stok = (int)$_POST['stok'];
    $kategori = trim($_POST['kategori']);
    
    // Handle image upload
    $kapak_resmi = $_POST['mevcut_resim'];
    if (isset($_FILES['kapak_resmi']) && $_FILES['kapak_resmi']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../images/';
        $file_ext = pathinfo($_FILES['kapak_resmi']['name'], PATHINFO_EXTENSION);
        $kapak_resmi = uniqid() . '.' . $file_ext;
        
        if (!move_uploaded_file($_FILES['kapak_resmi']['tmp_name'], $upload_dir . $kapak_resmi)) {
            $kapak_resmi = $_POST['mevcut_resim'];
        }
    }
    
    $stmt = $db->prepare("UPDATE kitaplar SET baslik = ?, yazar = ?, aciklama = ?, fiyat = ?, stok = ?, kategori = ?, kapak_resmi = ? WHERE id = ?");
    $stmt->execute([$baslik, $yazar, $aciklama, $fiyat, $stok, $kategori, $kapak_resmi, $id]);
    
    $_SESSION['success_message'] = 'Kitap başarıyla güncellendi.';
    redirect('kitaplar.php');
}

// Delete book
if (isset($_GET['sil']) && is_numeric($_GET['sil'])) {
    $stmt = $db->prepare("DELETE FROM kitaplar WHERE id = ?");
    $stmt->execute([$_GET['sil']]);
    
    $_SESSION['success_message'] = 'Kitap başarıyla silindi.';
    redirect('kitaplar.php');
}

// Get all books
$kitaplar = $db->query("SELECT * FROM kitaplar ORDER BY baslik")->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<h2>Kitap Yönetimi</h2>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<h3>Kitap Ekle</h3>
<form action="kitaplar.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="baslik">Kitap Adı:</label>
        <input type="text" id="baslik" name="baslik" required>
    </div>
    
    <div class="form-group">
        <label for="yazar">Yazar:</label>
        <input type="text" id="yazar" name="yazar" required>
    </div>
    
    <div class="form-group">
        <label for="aciklama">Açıklama:</label>
        <textarea id="aciklama" name="aciklama" rows="3" required></textarea>
    </div>
    
    <div class="form-group">
        <label for="fiyat">Fiyat (TL):</label>
        <input type="number" id="fiyat" name="fiyat" step="0.01" min="0" required>
    </div>
    
    <div class="form-group">
        <label for="stok">Stok:</label>
        <input type="number" id="stok" name="stok" min="0" required>
    </div>
    
    <div class="form-group">
        <label for="kategori">Kategori:</label>
        <input type="text" id="kategori" name="kategori">
    </div>
    
    <div class="form-group">
        <label for="kapak_resmi">Kapak Resmi:</label>
        <input type="file" id="kapak_resmi" name="kapak_resmi" accept="image/*">
    </div>
    
    <button type="submit" name="kitap_ekle" class="btn">Kitap Ekle</button>
</form>

<h3>Kitap Listesi</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Kapak</th>
            <th>Kitap Adı</th>
            <th>Yazar</th>
            <th>Fiyat</th>
            <th>Stok</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kitaplar as $kitap): ?>
            <tr>
                <td><?php echo $kitap['id']; ?></td>
                <td><img src="../images/<?php echo htmlspecialchars($kitap['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($kitap['baslik']); ?>" width="50"></td>
                <td><?php echo htmlspecialchars($kitap['baslik']); ?></td>
                <td><?php echo htmlspecialchars($kitap['yazar']); ?></td>
                <td><?php echo number_format($kitap['fiyat'], 2); ?> TL</td>
                <td><?php echo $kitap['stok']; ?></td>
                <td>
                    <a href="#" onclick="editBook(<?php echo $kitap['id']; ?>)" class="btn">Düzenle</a>
                    <a href="kitaplar.php?sil=<?php echo $kitap['id']; ?>" class="btn" onclick="return confirm('Bu kitabı silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Edit Book Modal -->
<div id="editModal" style="display:none;">
    <h3>Kitap Düzenle</h3>
    <form id="editForm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" id="edit_id">
        <input type="hidden" name="mevcut_resim" id="mevcut_resim">
        
        <div class="form-group">
            <label for="edit_baslik">Kitap Adı:</label>
            <input type="text" id="edit_baslik" name="baslik" required>
        </div>
        
        <div class="form-group">
            <label for="edit_yazar">Yazar:</label>
            <input type="text" id="edit_yazar" name="yazar" required>
        </div>
        
        <div class="form-group">
            <label for="edit_aciklama">Açıklama:</label>
            <textarea id="edit_aciklama" name="aciklama" rows="4" required></textarea>
        </div>
        
        <div class="form-group">
            <label for="edit_fiyat">Fiyat (TL):</label>
            <input type="number" id="edit_fiyat" name="fiyat" step="0.01" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="edit_stok">Stok:</label>
            <input type="number" id="edit_stok" name="stok" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="edit_kategori">Kategori:</label>
            <input type="text" id="edit_kategori" name="kategori">
        </div>
        
        <div class="form-group">
            <label for="edit_kapak_resmi">Kapak Resmi:</label>
            <input type="file" id="edit_kapak_resmi" name="kapak_resmi" accept="image/*">
            <img id="current_image" src="" width="100" style="display:block; margin-top:10px;">
        </div>
        
        <button type="submit" name="kitap_guncelle" class="btn">Güncelle</button>
        <button type="button" onclick="document.getElementById('editModal').style.display='none'" class="btn">İptal</button>
    </form>
</div>

<script>
function editBook(id) {
    fetch(`get_kitap.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_baslik').value = data.baslik;
            document.getElementById('edit_yazar').value = data.yazar;
            document.getElementById('edit_aciklama').value = data.aciklama;
            document.getElementById('edit_fiyat').value = data.fiyat;
            document.getElementById('edit_stok').value = data.stok;
            document.getElementById('edit_kategori').value = data.kategori;
            document.getElementById('mevcut_resim').value = data.kapak_resmi;
            document.getElementById('current_image').src = `../images/${data.kapak_resmi}`;
            document.getElementById('current_image').style.display = 'block';
            
            document.getElementById('editModal').style.display = 'block';
        });
}
</script>

<?php
require_once 'footer.php';
?>
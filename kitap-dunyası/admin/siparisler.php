<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../giris.php');
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['siparis_id'])) {
    $siparis_id = (int)$_POST['siparis_id'];
    $durum = $_POST['durum'];
    
    $stmt = $db->prepare("UPDATE siparisler SET durum = ? WHERE id = ?");
    $stmt->execute([$durum, $siparis_id]);
    
    $_SESSION['success_message'] = 'Sipariş durumu güncellendi.';
    redirect('siparisler.php');
}

// Get all orders
$siparisler = $db->query("SELECT s.*, k.kullanici_adi 
                         FROM siparisler s 
                         JOIN kullanicilar k ON s.kullanici_id = k.id 
                         ORDER BY s.siparis_tarihi DESC")->fetchAll(PDO::FETCH_ASSOC);

require_once 'header.php';
?>

<h2>Sipariş Yönetimi</h2>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>Sipariş No</th>
            <th>Tarih</th>
            <th>Kullanıcı</th>
            <th>Toplam</th>
            <th>Durum</th>
            <th>İşlemler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($siparisler as $siparis): ?>
            <tr>
                <td><?php echo $siparis['id']; ?></td>
                <td><?php echo date('d.m.Y H:i', strtotime($siparis['siparis_tarihi'])); ?></td>
                <td><?php echo htmlspecialchars($siparis['kullanici_adi']); ?></td>
                <td><?php echo number_format($siparis['toplam_fiyat'], 2); ?> TL</td>
                <td>
                    <form action="siparisler.php" method="post" style="display:inline;">
                        <input type="hidden" name="siparis_id" value="<?php echo $siparis['id']; ?>">
                        <select name="durum" onchange="this.form.submit()">
                            <option value="beklemede" <?php echo $siparis['durum'] === 'beklemede' ? 'selected' : ''; ?>>Beklemede</option>
                            <option value="hazırlanıyor" <?php echo $siparis['durum'] === 'hazırlanıyor' ? 'selected' : ''; ?>>Hazırlanıyor</option>
                            <option value="kargoda" <?php echo $siparis['durum'] === 'kargoda' ? 'selected' : ''; ?>>Kargoda</option>
                            <option value="teslim edildi" <?php echo $siparis['durum'] === 'teslim edildi' ? 'selected' : ''; ?>>Teslim Edildi</option>
                        </select>
                        <noscript><button type="submit" name="durum_guncelle">Güncelle</button></noscript>
                    </form>
                </td>
                <td>
                    <a href="siparis-detay.php?id=<?php echo $siparis['id']; ?>" class="btn">Detay</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
require_once 'footer.php';
?>
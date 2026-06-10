<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn()) {
    $_SESSION['error_message'] = 'Sipariş detaylarını görüntülemek için giriş yapmalısınız.';
    redirect('giris.php');
}

// Check if order ID is provided and valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Geçersiz sipariş numarası.';
    redirect('hesabim.php');
}

$siparis_id = (int)$_GET['id'];

// Get order information with proper access control
if (isAdmin()) {
    // Admin can see any order
    $stmt = $db->prepare("SELECT s.*, k.kullanici_adi, k.email 
                         FROM siparisler s 
                         JOIN kullanicilar k ON s.kullanici_id = k.id 
                         WHERE s.id = ?");
    $stmt->execute([$siparis_id]);
} else {
    // Regular user can only see their own orders
    $stmt = $db->prepare("SELECT s.*, k.kullanici_adi, k.email 
                         FROM siparisler s 
                         JOIN kullanicilar k ON s.kullanici_id = k.id 
                         WHERE s.id = ? AND s.kullanici_id = ?");
    $stmt->execute([$siparis_id, $_SESSION['user_id']]);
}

$siparis = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$siparis) {
    $_SESSION['error_message'] = 'Sipariş bulunamadı veya bu siparişi görüntüleme yetkiniz yok.';
    redirect('hesabim.php');
}

// Get order items
$siparis_urunleri = getSiparisDetay($siparis_id);

require_once 'header.php';
?>

<h2>Sipariş Detayları - #<?php echo $siparis['id']; ?></h2>

<div class="siparis-bilgileri">
    <h3>Sipariş Bilgileri</h3>
    <p><strong>Sipariş Tarihi:</strong> <?php echo date('d.m.Y H:i', strtotime($siparis['siparis_tarihi'])); ?></p>
    <p><strong>Sipariş Durumu:</strong> <?php echo ucfirst($siparis['durum']); ?></p>
    <p><strong>Toplam Tutar:</strong> <?php echo number_format($siparis['toplam_fiyat'], 2); ?> TL</p>
    
    <?php if (isAdmin()): ?>
        <p><strong>Kullanıcı:</strong> <?php echo htmlspecialchars($siparis['kullanici_adi']); ?> (<?php echo htmlspecialchars($siparis['email']); ?>)</p>
    <?php endif; ?>
    
    <h4>Teslimat Adresi:</h4>
    <p><?php echo nl2br(htmlspecialchars($siparis['adres'])); ?></p>
</div>

<div class="siparis-urunleri">
    <h3>Sipariş Edilen Ürünler</h3>
    
    <table>
        <thead>
            <tr>
                <th>Ürün</th>
                <th>Birim Fiyat</th>
                <th>Adet</th>
                <th>Toplam</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($siparis_urunleri as $urun): ?>
                <tr>
                    <td>
                        <?php if (!empty($urun['kapak_resmi'])): ?>
                            <img src="../images/<?php echo htmlspecialchars($urun['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($urun['baslik']); ?>" width="50">
                        <?php endif; ?>
                        <?php echo htmlspecialchars($urun['baslik']); ?>
                    </td>
                    <td><?php echo number_format($urun['birim_fiyat'], 2); ?> TL</td>
                    <td><?php echo $urun['miktar']; ?></td>
                    <td><?php echo number_format($urun['birim_fiyat'] * $urun['miktar'], 2); ?> TL</td>
                </tr>
            <?php endforeach; ?>
            <tr class="toplam-row">
                <td colspan="3" style="text-align: right;"><strong>Genel Toplam:</strong></td>
                <td><strong><?php echo number_format($siparis['toplam_fiyat'], 2); ?> TL</strong></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="action-buttons">
    <?php if (isAdmin()): ?>
        <a href="siparisler.php" class="btn">Yönetim Paneline Dön</a>
	<?php else: ?>	
		<a href="hesabim.php" class="btn">Siparişlerime Dön</a>
    <?php endif; ?>
</div>

<?php
require_once 'footer.php';
?>
<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
	redirect('giris.php');
}

// Get user info
$stmt = $db->prepare("SELECT * FROM kullanicilar WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get user orders
$siparisler = getKullaniciSiparisler($_SESSION['user_id']);

require_once 'includes/header.php';
?>

<h2>Hesabım</h2>

<div class="kullanici-bilgileri">
    <h3>Kişisel Bilgiler</h3>
    <p><strong>Kullanıcı Adı:</strong> <?php echo htmlspecialchars($user['kullanici_adi']); ?></p>
    <p><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($user['ad_soyad']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Telefon:</strong> <?php echo htmlspecialchars($user['telefon'] ?: 'Belirtilmemiş'); ?></p>
    <p><strong>Adres:</strong> <?php echo nl2br(htmlspecialchars($user['adres'] ?: 'Belirtilmemiş')); ?></p>
    
    <a href="hesabim-duzenle.php" class="btn">Bilgileri Düzenle</a>
</div>

<div class="siparislerim">
    <h3>Sipariş Geçmişim</h3>
    
    <?php if (empty($siparisler)): ?>
        <p>Henüz siparişiniz bulunmamaktadır.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Sipariş No</th>
                    <th>Tarih</th>
                    <th>Toplam</th>
                    <th>Durum</th>
                    <th>Detay</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($siparisler as $siparis): ?>
                    <tr>
                        <td><?php echo $siparis['id']; ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($siparis['siparis_tarihi'])); ?></td>
                        <td><?php echo number_format($siparis['toplam_fiyat'], 2); ?> TL</td>
                        <td><?php echo ucfirst($siparis['durum']); ?></td>
                        <td><a href="siparis-detay.php?id=<?php echo $siparis['id']; ?>">Görüntüle</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>
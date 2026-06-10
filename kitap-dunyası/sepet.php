<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('giris.php');
}

// Add to cart
if (isset($_GET['ekle']) && is_numeric($_GET['ekle'])) {
    sepeteEkle($_GET['ekle']);
    redirect('sepet.php');
}

// Remove from cart
if (isset($_GET['cikar']) && is_numeric($_GET['cikar'])) {
    sepettenCikar($_GET['cikar']);
    redirect('sepet.php');
}

// Update quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guncelle'])) {
    foreach ($_POST['miktar'] as $kitap_id => $miktar) {
        if ($miktar > 0) {
            $_SESSION['sepet'][$kitap_id] = $miktar;
        } else {
            unset($_SESSION['sepet'][$kitap_id]);
        }
    }
    redirect('sepet.php');
}

// Clear cart
if (isset($_GET['temizle'])) {
    sepetiTemizle();
    redirect('sepet.php');
}

// Place order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['siparis_ver'])) {
    // Validate address
    $adres = trim($_POST['adres']);
    
    if (empty($adres)) {
        $error = 'Lütfen teslimat adresinizi girin.';
    } else {
        $sepet = sepetiGetir();
        
        if (empty($sepet)) {
            $error = 'Sepetiniz boş.';
        } else {
            try {
                $db->beginTransaction();
                
                // Create order
                $stmt = $db->prepare("INSERT INTO siparisler (kullanici_id, toplam_fiyat, adres) VALUES (?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], sepetToplam(), $adres]);
                $siparis_id = $db->lastInsertId();
                
                // Add order items
                $stmt = $db->prepare("INSERT INTO siparis_urunleri (siparis_id, kitap_id, miktar, birim_fiyat) VALUES (?, ?, ?, ?)");
                
                foreach ($sepet as $item) {
                    $stmt->execute([$siparis_id, $item['id'], $item['miktar'], $item['fiyat']]);
                    
                    // Update stock
                    $db->prepare("UPDATE kitaplar SET stok = stok - ? WHERE id = ?")
                       ->execute([$item['miktar'], $item['id']]);
                }
                
                $db->commit();
                sepetiTemizle();
                $_SESSION['success_message'] = 'Siparişiniz başarıyla oluşturuldu.';
                redirect('hesabim.php');
            } catch (Exception $e) {
                $db->rollBack();
                $error = 'Sipariş oluşturulurken bir hata oluştu: ' . $e->getMessage();
            }
        }
    }
}

$sepet = sepetiGetir();

require_once 'includes/header.php';
?>

<h2>Alışveriş Sepeti</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (empty($sepet)): ?>
    <p>Sepetiniz boş.</p>
    <a href="kitaplar.php" class="btn">Alışverişe Devam Et</a>
<?php else: ?>
    <form action="sepet.php" method="post">
        <table>
            <thead>
                <tr>
                    <th>Kitap</th>
                    <th>Fiyat</th>
                    <th>Miktar</th>
                    <th>Toplam</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sepet as $item): ?>
                    <tr>
                        <td>
                            <img src="images/<?php echo htmlspecialchars($item['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($item['baslik']); ?>" width="50">
                            <?php echo htmlspecialchars($item['baslik']); ?>
                        </td>
                        <td><?php echo number_format($item['fiyat'], 2); ?> TL</td>
                        <td>
                            <input type="number" name="miktar[<?php echo $item['id']; ?>]" value="<?php echo $item['miktar']; ?>" min="1" max="<?php echo $item['stok']; ?>">
                        </td>
                        <td><?php echo number_format($item['fiyat'] * $item['miktar'], 2); ?> TL</td>
                        <td>
                            <a href="sepet.php?cikar=<?php echo $item['id']; ?>" class="btn">Kaldır</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="sepet-toplam">
            Toplam: <?php echo number_format(sepetToplam(), 2); ?> TL
        </div>
        
        <div>
            <button type="submit" name="guncelle" class="btn">Sepeti Güncelle</button>
            <a href="sepet.php?temizle" class="btn">Sepeti Temizle</a>
        </div>
    </form>
    
    <h3>Siparişi Tamamla</h3>
    <form action="sepet.php" method="post">
        <div class="form-group">
            <label for="adres">Teslimat Adresi:</label>
            <textarea id="adres" name="adres" rows="4" required><?php 
                $stmt = $db->prepare("SELECT adres FROM kullanicilar WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                echo htmlspecialchars($user['adres'] ?: '');
            ?></textarea>
        </div>
        
        <button type="submit" name="siparis_ver" class="btn">Siparişi Tamamla</button>
    </form>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>
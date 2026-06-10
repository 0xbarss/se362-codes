<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('kitaplar.php');
}

$kitap = getKitapById($_GET['id']);

if (!$kitap) {
    redirect('kitaplar.php');
}

require_once 'includes/header.php';
?>

<div class="kitap-detay">
    <div class="kitap-resim">
        <img src="images/<?php echo htmlspecialchars($kitap['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($kitap['baslik']); ?>">
    </div>
    <div class="kitap-bilgileri">
        <h2><?php echo htmlspecialchars($kitap['baslik']); ?></h2>
        <p><strong>Yazar:</strong> <?php echo htmlspecialchars($kitap['yazar']); ?></p>
        <p><strong>Kategori:</strong> <?php echo htmlspecialchars($kitap['kategori']); ?></p>
        <p><strong>Fiyat:</strong> <span class="fiyat"><?php echo number_format($kitap['fiyat'], 2); ?> TL</span></p>
        <p><strong>Stok:</strong> <?php echo $kitap['stok']; ?> adet</p>
        
        <h3>Kitap Hakkında</h3>
        <p><?php echo nl2br(htmlspecialchars($kitap['aciklama'])); ?></p>
        
        <?php if (isLoggedIn()): ?>
            <form action="sepet.php" method="post">
                <input type="hidden" name="kitap_id" value="<?php echo $kitap['id']; ?>">
                <div class="form-group">
                    <label for="miktar">Miktar:</label>
                    <input type="number" id="miktar" name="miktar" value="1" min="1" max="<?php echo $kitap['stok']; ?>">
                </div>
                <a href="sepet.php?ekle=<?php echo $kitap['id']; ?>" class="btn">Sepete Ekle</a>
            </form>
        <?php else: ?>
            <p>Sepete eklemek için <a href="giris.php">giriş yapmalısınız</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
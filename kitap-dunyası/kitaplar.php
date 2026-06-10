<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : null;
$kategoriler = $db->query("SELECT DISTINCT kategori FROM kitaplar WHERE kategori IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$kitaplar = getKitaplar($search, $kategori);

require_once 'includes/header.php';
?>
<h2>Kategoriler</h2>
<ul>
    <?php foreach ($kategoriler as $kategori): ?>
        <li><a href="kitaplar.php?kategori=<?php echo urlencode($kategori); ?>"><?php echo htmlspecialchars($kategori); ?></a></li>
    <?php endforeach; ?>
</ul>
<h2>Kitap Listesi</h2>
<?php if ($search): ?>
    <p>Arama sonuçları: "<?php echo htmlspecialchars($search); ?>"</p>
<?php elseif ($kategori): ?>
    <p>Kategori: <?php echo htmlspecialchars($kategori); ?></p>
<?php endif; ?>

<div class="kitap-listesi">
    <?php if (empty($kitaplar)): ?>
        <p>Kitap bulunamadı.</p>
    <?php else: ?>
        <?php foreach ($kitaplar as $kitap): ?>
            <div class="kitap">
                <img src="images/<?php echo htmlspecialchars($kitap['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($kitap['baslik']); ?>">
                <h3><?php echo htmlspecialchars($kitap['baslik']); ?></h3>
                <p>Yazar: <?php echo htmlspecialchars($kitap['yazar']); ?></p>
                <p class="fiyat"><?php echo number_format($kitap['fiyat'], 2); ?> TL</p>
                <a href="kitap-detay.php?id=<?php echo $kitap['id']; ?>" class="btn">Detaylar</a>
                <?php if (isLoggedIn()): ?>
                    <a href="sepet.php?ekle=<?php echo $kitap['id']; ?>" class="btn">Sepete Ekle</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>
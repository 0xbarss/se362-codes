<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$kitaplar = getKitaplar(null, null);
$kategoriler = $db->query("SELECT DISTINCT kategori FROM kitaplar WHERE kategori IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

require_once 'includes/header.php';
$counter = 0;
?>
<h2>Kategoriler</h2>
<ul>
    <?php foreach ($kategoriler as $kategori): ?>
        <li><a href="kitaplar.php?kategori=<?php echo urlencode($kategori); ?>"><?php echo htmlspecialchars($kategori); ?></a></li>
    <?php endforeach; ?>
</ul>
<h2>Yeni Eklenen Kitaplar</h2>
<div class="kitap-listesi">
    <?php foreach ($kitaplar as $kitap): ?>
		<?php if ($counter == 4) {break; };
		$counter++; ?>
        <div class="kitap">
            <img src="images/<?php echo htmlspecialchars($kitap['kapak_resmi']); ?>" alt="<?php echo htmlspecialchars($kitap['baslik']); ?>">
            <h3><?php echo htmlspecialchars($kitap['baslik']); ?></h3>
            <p>Yazar: <?php echo htmlspecialchars($kitap['yazar']); ?></p>
            <p class="fiyat"><?php echo number_format($kitap['fiyat'], 2); ?> TL</p>
            <a href="kitap-detay.php?id=<?php echo $kitap['id']; ?>" class="btn">Detaylar</a>
        </div>
    <?php endforeach; ?>
</div>
<?php
require_once 'includes/footer.php';
?>
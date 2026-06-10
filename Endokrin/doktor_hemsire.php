<?php include 'header.php'; require_once 'db.php';
if(!in_array($_SESSION['role_id'], [2,3])) die("Yetkisiz!");

$hastalar = [];
$arama_yapildi = false;

// Eğer URL'de 'q' parametresi varsa (Kullanıcı "Ara" butonuna basmışsa)
if (isset($_GET['q'])) {
    $arama_yapildi = true;
    $search_query = $_GET['q'];

    if (empty($search_query)) {
        // Arama kutusu boş bırakılıp "Ara"ya basıldıysa TÜM hastaları getir
        $hastalar = $pdo->query("SELECT * FROM users WHERE role_id = 6")->fetchAll();
    } else {
        // Bir kelime yazıldıysa FİLTRELEME yaparak getir
        $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = 6 AND full_name LIKE ?");
        $stmt->execute(["%$search_query%"]);
        $hastalar = $stmt->fetchAll();
    }
}
?>

<div class="container">
    <h2>🩺 Klinik Hasta Takip Sistemi</h2>

    <div style="background: #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ccc;">
        <p style="margin-top:0;"><strong>Hasta Bul:</strong> (Tüm listeyi görmek için kutuyu boş bırakıp Ara'ya basın)</p>
        <form method="GET" action="doktor_hemsire.php" style="display: flex; gap: 10px;">
            <input type="text" name="q" placeholder="Hasta adını yazınız..." 
                   value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" 
                   style="flex: 1; margin: 0; height: 40px;">
            <button type="submit" class="btn btn-blue" style="width: 120px;">Ara</button>
        </form>
    </div>

    <?php if ($arama_yapildi): ?>
        <h3>Arama Sonuçları (<?php echo count($hastalar); ?> Hasta Bulundu)</h3>
        
        <?php if (count($hastalar) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Hasta Adı Soyadı</th>
                        <th>Telefon</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hastalar as $h): ?>
                    <tr>
                        <td><strong><?php echo $h['full_name']; ?></strong></td>
                        <td><?php echo $h['phone'] ? $h['phone'] : '-'; ?></td>
                        <td>
                            <a href="hasta_detay.php?id=<?php echo $h['id']; ?>" class="btn btn-green">Detayları Gör / Kayıt Ekle</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding: 20px; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; border-radius: 5px;">
                Eşleşen hasta bulunamadı. Lütfen farklı bir isim deneyin.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div style="text-align: center; padding: 40px; border: 2px dashed #ccc; color: #666;">
            <p style="font-size: 18px;">Lütfen işlem yapmak istediğiniz hastayı yukarıdaki kutucuktan aratın.</p>
            <small>Tüm hastaların listesini görmek isterseniz arama kutusunu boş bırakıp "Ara" butonuna tıklayabilirsiniz.</small>
        </div>
    <?php endif; ?>

    <br>
    <a href="dashboard.php" class="btn btn-blue">Geri Dön</a>
</div>
<?php include 'footer.php'; ?>
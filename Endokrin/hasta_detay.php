<?php include 'header.php'; require_once 'db.php';
if(!isset($_GET['id'])) die("Hasta ID belirtilmedi!");
$p_id = $_GET['id'];

// YENİ KAYIT EKLEME MANTIĞI (POST işlemi)
if (isset($_POST['save_visit'])) {
    // Sadece Doktor (2) ve Hemşire (3) ekleme yapabilsin
    if (in_array($_SESSION['role_id'], [2, 3])) {
        $diag = $_POST['diagnosis'];
        $med = $_POST['medication'];
        $dr_id = $_SESSION['user_id']; // Giriş yapan görevlinin ID'si

        $stmt = $pdo->prepare("INSERT INTO visits (patient_id, doctor_id, diagnosis, medication) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$p_id, $dr_id, $diag, $med])) {
            echo "<p style='color: green; font-weight: bold;'>✔️ Yeni muayene kaydı başarıyla eklendi!</p>";
        }
    } else {
        echo "<p style='color: red;'>Hata: Bu işlemi yapmaya yetkiniz yok.</p>";
    }
}

// Hasta Bilgileri
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role_id = 6");
$stmt->execute([$p_id]);
$patient = $stmt->fetch();
if(!$patient) die("Hasta bulunamadı!");

// Geçmiş Muayeneler
$visits = $pdo->prepare("SELECT v.*, d.full_name as dr_name FROM visits v JOIN users d ON v.doctor_id = d.id WHERE v.patient_id = ? ORDER BY v.visit_date DESC");
$visits->execute([$p_id]);

// Kan Değerleri
$labs = $pdo->prepare("SELECT * FROM lab_results WHERE patient_id = ? ORDER BY test_date DESC");
$labs->execute([$p_id]);
?>
<div class="container">
    <h2>📋 Hasta Profil Kartı: <?php echo htmlspecialchars($patient['full_name']); ?></h2>
    <hr>
    <?php if (in_array($_SESSION['role_id'], [2, 3])): ?>
    <div style="background: #fdfdfd; border: 2px solid #007bff; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
        <h3 style="margin-top: 0; color: #007bff;">✍️ Yeni Muayene / Teşhis Ekle</h3>
        <form method="POST">
            <label><strong>Konulan Teşhis:</strong></label>
            <textarea name="diagnosis" rows="3" placeholder="Hastanın şikayeti ve konulan teşhis..." required></textarea>
            
            <label><strong>Yazılan İlaç(lar):</strong></label>
            <input type="text" name="medication" placeholder="Örn: Metformin 500mg, Günde 2 kez" required>
            
            <button type="submit" name="save_visit" class="btn btn-green" style="width: 100%; font-size: 16px;">Kaydı Sisteme İşle</button>
        </form>
    </div>
    <?php endif; ?>
<div>
<div class="container">
    <h2>Hasta Detay Dosyası: <?php echo $patient['full_name']; ?></h2>
    
    <div class="grid">
        <div>
            <h3>🧪 Son Kan Değerleri</h3>
            <table>
                <tr><th>Tarih</th><th>Glikoz</th><th>TSH</th><th>İnsülin</th></tr>
                <?php while($l = $labs->fetch()): ?>
                <tr>
                    <td><?php echo $l['test_date']; ?></td>
                    <td><?php echo $l['glucose']; ?> mg/dL</td>
                    <td><?php echo $l['tsh']; ?> mIU/L</td>
                    <td><?php echo $l['insulin']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
        
        <div>
            <h3>📜 Muayene Geçmişi</h3>
            <table>
                <tr><th>Tarih</th><th>Doktor</th><th>Teşhis</th><th>İlaç</th></tr>
                <?php while($v = $visits->fetch()): ?>
                <tr>
                    <td><?php echo $v['visit_date']; ?></td>
                    <td><?php echo $v['dr_name']; ?></td>
                    <td><?php echo $v['diagnosis']; ?></td>
                    <td><?php echo $v['medication']; ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <br>
</div>
<br><br>
<div class="container">
    <div style="background: #f1f1f1; border: 1px solid #ccc; padding: 20px; border-radius: 10px; margin-top: 20px;">
        <h3 style="margin-top: 0; border-bottom: 2px solid #333; padding-bottom: 5px;">📞 İletişim ve Adres Bilgileri</h3>
        <table style="border: none; margin-top: 0;">
            <tr style="border: none;">
                <td style="border: none; width: 150px;"><strong>Cep Telefonu:</strong></td>
                <td style="border: none;">
                    <?php 
                        if($patient['phone']) {
                            // Telefon numarasını formatlı gösterelim (Örn: 531 234 56 78)
                            echo substr($patient['phone'], 0, 3) . " " . substr($patient['phone'], 3, 3) . " " . substr($patient['phone'], 6, 2) . " " . substr($patient['phone'], 8, 2);
                        } else {
                            echo "<em>Girilmemiş</em>";
                        }
                    ?>
                </td>
            </tr>
            <tr style="border: none;">
                <td style="border: none;"><strong>İkamet Adresi:</strong></td>
                <td style="border: none;">
                    <?php 
                        if($patient['city'] || $patient['district']) {
                            echo htmlspecialchars($patient['district']) . " / " . htmlspecialchars($patient['city']);
                        } else {
                            echo "<em>Adres bilgisi bulunmuyor</em>";
                        }
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <br>
    <div style="display: flex; gap: 10px;">
        <a href="doktor_hemsire.php" class="btn btn-blue">⬅️ Hasta Listesine Dön</a>
        <a href="dashboard.php" class="btn btn-blue" style="background: #6c757d;">Ana Sayfa</a>
    </div>
</div>

<?php include 'footer.php'; ?>
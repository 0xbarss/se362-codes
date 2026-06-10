<?php 
include 'header.php'; 
require_once 'db.php';

// Güvenlik: Sadece Lab Teknisyeni (Role ID: 5) girebilir
if($_SESSION['role_id'] != 5) {
    die("<div class='container'><p style='color:red'>Bu sayfaya sadece Laboratuvar personeli erişebilir.</p></div>");
}

$msg = "";

// --- 1. VERİ KAYDETME İŞLEMİ ---
if(isset($_POST['save_lab'])){
    $stmt = $pdo->prepare("INSERT INTO lab_results (patient_id, glucose, tsh, insulin) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['p_id'], 
        $_POST['glucose'], 
        $_POST['tsh'], 
        $_POST['insulin']
    ]);
    $msg = "✔️ " . $_POST['p_name'] . " için sonuçlar başarıyla kaydedildi.";
}

// --- 2. HASTA ARAMA MANTIĞI ---
$search = isset($_GET['q']) ? $_GET['q'] : '';
$hastalar = [];
if(!empty($search)){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = 6 AND full_name LIKE ?");
    $stmt->execute(["%$search%"]);
    $hastalar = $stmt->fetchAll();
}

// --- 3. SON GİRİLEN KAYITLAR ---
$son_kayitlar = $pdo->query("
    SELECT l.*, u.full_name 
    FROM lab_results l 
    JOIN users u ON l.patient_id = u.id 
    ORDER BY l.test_date DESC LIMIT 10
")->fetchAll();
?>

<div class="container">
    <h2>🧪 Laboratuvar Bilgi Sistemi</h2>
    
    <?php if($msg) echo "<p style='background:#d4edda; color:#155724; padding:10px; border-radius:5px;'>$msg</p>"; ?>

    <div class="grid">
        <div>
            <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ccc; border-radius: 8px;">
                <h3>🔍 Hasta Bul</h3>
                <form method="GET" style="display:flex; gap:5px;">
                    <input type="text" name="q" placeholder="Sonuç girilecek hasta adı..." value="<?php echo htmlspecialchars($search); ?>" style="margin:0;">
                    <button type="submit" class="btn btn-blue" style="width:auto;">Ara</button>
                </form>

                <?php if(!empty($search)): ?>
                    <h4 style="margin-top:20px;">Arama Sonuçları</h4>
                    <?php if(count($hastalar) > 0): ?>
                        <?php foreach($hastalar as $h): ?>
                            <div style="background:#fff; border:1px solid #ddd; padding:10px; margin-bottom:10px; border-radius:5px;">
                                <strong><?php echo $h['full_name']; ?></strong>
                                <form method="POST" style="margin-top:10px; border-top:1px dashed #ccc; padding-top:10px;">
                                    <input type="hidden" name="p_id" value="<?php echo $h['id']; ?>">
                                    <input type="hidden" name="p_name" value="<?php echo $h['full_name']; ?>">
                                    
                                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:5px;">
                                        <div>
                                            <small>Glikoz (mg/dL)</small>
                                            <input type="number" step="0.01" name="glucose" required>
                                        </div>
                                        <div>
                                            <small>TSH (mIU/L)</small>
                                            <input type="number" step="0.01" name="tsh" required>
                                        </div>
                                        <div>
                                            <small>İnsülin</small>
                                            <input type="number" step="0.01" name="insulin" required>
                                        </div>
                                    </div>
                                    <button type="submit" name="save_lab" class="btn btn-green" style="width:100%; margin-top:5px;">Sonuçları Kaydet</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:red;">Hasta bulunamadı.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p style="font-size:13px; color:#666; margin-top:15px;">Lütfen işlem yapmak için yukarıdan hasta araması yapın.</p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3>📋 Son Girilen Sonuçlar</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Hasta</th>
                            <th>Değerler</th>
                            <th>Tarih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($son_kayitlar as $s): ?>
                        <tr>
                            <td><strong><?php echo $s['full_name']; ?></strong></td>
                            <td style="font-size:12px;">
                                Şeker: <?php echo $s['glucose']; ?><br>
                                TSH: <?php echo $s['tsh']; ?><br>
                                İns: <?php echo $s['insulin']; ?>
                            </td>
                            <td><small><?php echo date("d.m.Y H:i", strtotime($s['test_date'])); ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
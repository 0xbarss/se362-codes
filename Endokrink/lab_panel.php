<?php 
include 'header.php'; 
require_once 'db.php';

// 1. GÜVENLİK KONTROLÜ
if($_SESSION['role_id'] != 5) {
    die("<div class='container'><p style='color:red; font-weight:bold;'>Yetkisiz Erişim! Sadece Laboratuvar personeli giriş yapabilir.</p></div>");
}

$msg = "";
$tech_id = $_SESSION['user_id']; // Giriş yapan teknisyenin benzersiz ID'si

// 2. VERİ KAYDETME VE GÜNCELLEME (UPDATE / INSERT) MANTIĞI
if(isset($_POST['save_lab'])){
    if(!empty($_POST['edit_result_id'])){
        // Düzenleme modu aktifse mevcut kaydı güncelle
        $stmt = $pdo->prepare("UPDATE lab_results SET glucose = ?, tsh = ?, insulin = ?, technician_id = ? WHERE id = ?");
        $stmt->execute([
            $_POST['glucose'], 
            $_POST['tsh'], 
            $_POST['insulin'],
            $tech_id,
            $_POST['edit_result_id']
        ]);
        $msg = "✔️ Tahlil sonuçları başarıyla güncellendi (Düzeltildi).";
    } else {
        // Düzenleme modu yoksa yeni tahlil kaydı ekle
        $stmt = $pdo->prepare("INSERT INTO lab_results (patient_id, technician_id, glucose, tsh, insulin) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['p_id'], 
            $tech_id,
            $_POST['glucose'], 
            $_POST['tsh'], 
            $_POST['insulin']
        ]);
        $msg = "✔️ " . htmlspecialchars($_POST['p_name']) . " isimli hastanın tahlil sonuçları sisteme kaydedildi.";
    }
}

// 3. DÜZENLENECEK KAYDIN VERİLERİNİ FORMA DOLDURMA KONTROLÜ
$edit_record = null;
if(isset($_GET['edit_res_id'])){
    $stmt = $pdo->prepare("SELECT * FROM lab_results WHERE id = ?");
    $stmt->execute([$_GET['edit_res_id']]);
    $edit_record = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 4. SADECE HASTA ROLÜNDEKİLERİ (`role_id = 6`) KAPSAYAN ARAMA MOTORU
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$hastalar = [];

if(!empty($search)){
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = 6 AND full_name LIKE ? ORDER BY full_name ASC");
    $stmt->execute(["%$search%"]);
    $hastalar = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 5. SİSTEMDEKİ SON 10 İŞLEMİN LİSTESİ (Hasta ID `p.id` alanını da çekiyoruz)
$son_kayitlar = $pdo->query("
    SELECT l.*, p.id as patient_id, p.full_name as patient_name, t.full_name as tech_name 
    FROM lab_results l 
    JOIN users p ON l.patient_id = p.id 
    LEFT JOIN users t ON l.technician_id = t.id 
    ORDER BY l.test_date DESC LIMIT 10
")->fetchAll();
?>

<div class="container">
    <h2>🧪 Laboratuvar Bilgi Giriş, Sorgulama ve Düzenleme Paneli</h2>
    
    <!-- BİLGİLENDİRME MESAJLARI -->
    <?php if($msg) echo "<p style='background:#d4edda; color:#155724; padding:10px; border-radius:5px; font-weight:bold;'>$msg</p>"; ?>

    <!-- DÜZENLEME MODU UYARISI -->
    <?php if($edit_record): ?>
        <div style="background: #fff3cd; color: #856404; padding: 12px; border: 1px solid #ffeeba; border-radius: 5px; margin-bottom: 20px;">
            ⚠️ <strong>Düzenleme Modu Aktif:</strong> Şu anda geçmiş bir tahlil sonucunu düzeltiyorsunuz. Değişiklikleri yaptıktan sonra "Değişiklikleri Onayla ve Güncelle" butonuna basın. 
            <a href="lab_panel.php?q=<?php echo urlencode($search); ?>" style="color:red; float:right; font-weight:bold; text-decoration:none;">Düzeltmeyi İptal Et</a>
        </div>
    <?php endif; ?>

    <div class="grid">
        <!-- SOL PANEL: HASTA BULMA, FORM GİRİŞİ VE HASTANIN KENDİ GEÇMİŞİ -->
        <div>
            <div style="background: #f9f9f9; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
                <h3>🔍 Hastayı Bul ve Dosyasını İncele</h3>
                <form method="GET" style="display:flex; gap:5px;">
                    <input type="text" name="q" placeholder="Hasta adı yazın (Örn: Mehmet)..." value="<?php echo htmlspecialchars($search); ?>" style="margin:0;" required>
                    <button type="submit" class="btn btn-blue" style="width:auto;">Sistemde Ara</button>
                </form>

                <?php if(!empty($search)): ?>
                    <h4 style="margin-top:20px; color:#007bff;">Arama Sonuçları (<?php echo count($hastalar); ?> Hasta)</h4>
                    
                    <?php if(count($hastalar) > 0): ?>
                        <?php foreach($hastalar as $h): ?>
                            <div style="background:#fff; border:2px solid #007bff; padding:15px; margin-bottom:20px; border-radius:5px;">
                                <span style="background: #007bff; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">HASTA</span>
                                <!-- 1. KISIM: ARAMA KARTINA HASTA ID EKLEME -->
                                <span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">ID: <?php echo $h['id']; ?></span>
                                
                                <strong style="font-size: 16px; margin-left: 5px;"><?php echo htmlspecialchars($h['full_name']); ?></strong>
                                <small style="color:#777; display:block; margin-top:2px;">📍 Adres: <?php echo htmlspecialchars($h['district'] . " / " . $h['city']); ?> | 📞 Tel: <?php echo htmlspecialchars($h['phone']); ?></small>
                                
                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap:10px; margin-top:15px; border-top:1px dashed #ccc; padding-top:10px;">
                                    
                                    <!-- A) YENİ VERİ GİRİŞ VEYA DÜZENLEME FORMU -->
                                    <div style="background:#fcfcfc; padding:10px; border:1px solid #eee; border-radius:4px;">
                                        <span style="color:#28a745; font-weight:bold; font-size:13px;">
                                            <?php echo ($edit_record && $edit_record['patient_id'] == $h['id']) ? "✏️ Değeri Düzelt" : "➕ Yeni Değer Gir"; ?>
                                        </span>
                                        
                                        <form method="POST" style="margin-top:5px;">
                                            <input type="hidden" name="p_id" value="<?php echo $h['id']; ?>">
                                            <input type="hidden" name="p_name" value="<?php echo $h['full_name']; ?>">
                                            
                                            <?php if($edit_record && $edit_record['patient_id'] == $h['id']): ?>
                                                <input type="hidden" name="edit_result_id" value="<?php echo $edit_record['id']; ?>">
                                            <?php endif; ?>
                                            
                                            <?php 
                                                $is_this_patient_edit = ($edit_record && $edit_record['patient_id'] == $h['id']);
                                                $glc_val = $is_this_patient_edit ? $edit_record['glucose'] : '';
                                                $tsh_val = $is_this_patient_edit ? $edit_record['tsh'] : '';
                                                $ins_val = $is_this_patient_edit ? $edit_record['insulin'] : '';
                                            ?>

                                            <small>Glikoz (mg/dL):</small>
                                            <input type="number" step="0.01" name="glucose" value="<?php echo $glc_val; ?>" required style="padding:4px; margin-bottom:5px;">
                                            
                                            <small>TSH (mIU/L):</small>
                                            <input type="number" step="0.01" name="tsh" value="<?php echo $tsh_val; ?>" required style="padding:4px; margin-bottom:5px;">
                                            
                                            <small>İnsülin:</small>
                                            <input type="number" step="0.01" name="insulin" value="<?php echo $ins_val; ?>" required style="padding:4px; margin-bottom:8px;">
                                            
                                            <?php if($is_this_patient_edit): ?>
                                                <button type="submit" name="save_lab" class="btn btn-blue" style="width:100%; padding:5px; font-size:12px;">Değişiklikleri Onayla ve Güncelle</button>
                                            <?php else: ?>
                                                <button type="submit" name="save_lab" class="btn btn-green" style="width:100%; padding:5px; font-size:12px;">Kaydet</button>
                                            <?php endif; ?>
                                        </form>
                                    </div>

                                    <!-- B) O HASTAYA AİT GEÇMİŞ LABORATUVAR GEÇMİŞİ -->
                                    <div style="background:#f4f7f6; padding:10px; border:1px solid #eee; border-radius:4px; max-height:220px; overflow-y:auto;">
                                        <span style="color:#17a2b8; font-weight:bold; font-size:13px;">📜 Geçmiş Tahlilleri</span>
                                        
                                        <?php 
                                        $p_history = $pdo->prepare("SELECT l.*, u.full_name as tech_name FROM lab_results l LEFT JOIN users u ON l.technician_id = u.id WHERE l.patient_id = ? ORDER BY l.test_date DESC");
                                        $p_history->execute([$h['id']]);
                                        $history_records = $p_history->fetchAll();
                                        
                                        if(count($history_records) > 0): 
                                        ?>
                                            <table style="font-size:11px; margin-top:5px; background:white;">
                                                <thead>
                                                    <tr><th>Tarih</th><th>Değerler</th><th>İşlem</th></tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($history_records as $hr): ?>
                                                    <tr>
                                                        <td><small><?php echo date("d.m.y", strtotime($hr['test_date'])); ?></small></td>
                                                        <td>
                                                            G: <?php echo $hr['glucose']; ?><br>
                                                            T: <?php echo $hr['tsh']; ?><br>
                                                            İ: <?php echo $hr['insulin']; ?>
                                                        </td>
                                                        <td>
                                                            <a href="lab_panel.php?q=<?php echo urlencode($search); ?>&edit_res_id=<?php echo $hr['id']; ?>" class="btn btn-blue" style="font-size:9px; padding:2px 4px; display:inline-block;">Düzenle</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <p style="font-size:11px; color:#888; margin-top:10px;">Geçmiş tahlil kaydı yok.</p>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color:red; font-weight:bold;">Kayıt bulunamadı veya aradığınız kişi 'Hasta' rolünde değil.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- SAĞ PANEL: SİSTEM GENELİNDE GİRİLEN SON 10 TAHLİL -->
        <div>
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3>📋 Sistemde Son İşlem Görenler</h3>
                <table>
                    <thead><tr><th>Hasta Bilgisi</th><th>Kan Analizi</th><th>Teknisyen</th></tr></thead>
                    <tbody>
                        <?php foreach($son_kayitlar as $s): ?>
                        <tr>
                            <td>
                                <!-- 2. KISIM: SON İŞLEMLER TABLOSUNA HASTA ID EKLEME -->
                                <small style="background:#6c757d; color:#fff; padding:1px 4px; border-radius:3px; font-size:10px;">ID: <?php echo $s['patient_id']; ?></small><br>
                                <strong><?php echo $s['patient_name']; ?></strong><br>
                                <small style="color:gray;"><?php echo date("d.m.Y H:i", strtotime($s['test_date'])); ?></small>
                            </td>
                            <td style="font-size:12px;">G: <b><?php echo $s['glucose']; ?></b> | T: <b><?php echo $s['tsh']; ?></b> | İ: <b><?php echo $s['insulin']; ?></b></td>
                            <td><small style="color:#28a745;">💻 <?php echo $s['tech_name'] ? $s['tech_name'] : 'Sistem'; ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
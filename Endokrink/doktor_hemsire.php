<?php 
include 'header.php'; 
require_once 'db.php';

// Güvenlik: Sadece Doktor (2) veya Hemşire (3) girebilir
if($_SESSION['role_id'] != 2 && $_SESSION['role_id'] != 3) {
    die("<div class='container'><p style='color:red; font-weight:bold;'>Yetkisiz Erişim! Bu sayfa sağlık personeline özeldir.</p></div>");
}

$msg = "";
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// --- 1. DOKTOR İÇİN: MUAYENE TAMAMLAMA ---
if(isset($_POST['complete_visit']) && $role_id == 2) {
    $appointment_id = $_POST['app_id'];
    $diagnosis = trim($_POST['diagnosis']);
    $medication = trim($_POST['medication']);

    $stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
    $stmt->execute([$appointment_id]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if($app) {
        $insert_visit = $pdo->prepare("INSERT INTO visits (patient_id, doctor_id, diagnosis, medication, visit_date) VALUES (?, ?, ?, ?, ?)");
        $insert_visit->execute([
            $app['patient_id'],
            $app['doctor_id'],
            $diagnosis,
            $medication,
            $app['appointment_date']
        ]);

        $update_app = $pdo->prepare("UPDATE appointments SET status = 'Tamamlandı' WHERE id = ?");
        $update_app->execute([$appointment_id]);

        $msg = "✔️ Muayene başarıyla tamamlandı. Kayıt orijinal randevu saatine (" . date("H:i", strtotime($app['appointment_date'])) . ") işlendi.";
    }
}

// --- 2. ORTAK ALAN: SEÇİLİ HASTANIN DETAYLARINI GETİRME ---
$selected_patient_id = null;
$selected_app = null;
$patient_info = null;
$patient_history_visits = [];
$patient_history_labs = [];
$patient_history_apps = [];

// A) Doktor bir hasta seçtiyse
if($role_id == 2 && isset($_GET['action']) && $_GET['action'] == 'muayene' && isset($_GET['app_id'])) {
    $stmt = $pdo->prepare("
        SELECT a.*, p.full_name as patient_name, p.phone as patient_phone, p.city as patient_city, p.district as patient_district 
        FROM appointments a 
        JOIN users p ON a.patient_id = p.id 
        WHERE a.id = ? AND a.doctor_id = ? AND a.status = 'Bekliyor'
    ");
    $stmt->execute([$_GET['app_id'], $user_id]);
    $selected_app = $stmt->fetch(PDO::FETCH_ASSOC);
    if($selected_app) {
        $selected_patient_id = $selected_app['patient_id'];
        $patient_info = [
            'id' => $selected_app['patient_id'],
            'full_name' => $selected_app['patient_name'],
            'phone' => $selected_app['patient_phone'],
            'city' => $selected_app['patient_city'],
            'district' => $selected_app['patient_district']
        ];
    }
}

// B) Hemşire bir hasta seçtiyse
if($role_id == 3 && isset($_GET['action']) && $_GET['action'] == 'incele' && isset($_GET['p_id'])) {
    $selected_patient_id = $_GET['p_id'];
    $stmt = $pdo->prepare("SELECT id, full_name, phone, city, district FROM users WHERE id = ? AND role_id = 6");
    $stmt->execute([$selected_patient_id]);
    $patient_info = $stmt->fetch(PDO::FETCH_ASSOC);
}

// C) Geçmiş Tıbbi Bilgileri Çekme
if($selected_patient_id) {
    $v_stmt = $pdo->prepare("SELECT v.*, d.full_name as doc_name FROM visits v JOIN users d ON v.doctor_id = d.id WHERE v.patient_id = ? ORDER BY v.visit_date DESC");
    $v_stmt->execute([$selected_patient_id]);
    $patient_history_visits = $v_stmt->fetchAll(PDO::FETCH_ASSOC);

    $l_stmt = $pdo->prepare("SELECT l.*, t.full_name as tech_name FROM lab_results l LEFT JOIN users t ON l.technician_id = t.id WHERE l.patient_id = ? ORDER BY l.test_date DESC");
    $l_stmt->execute([$selected_patient_id]);
    $patient_history_labs = $l_stmt->fetchAll(PDO::FETCH_ASSOC);

    $a_stmt = $pdo->prepare("SELECT a.*, d.full_name as doc_name FROM appointments a JOIN users d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.appointment_date DESC");
    $a_stmt->execute([$selected_patient_id]);
    $patient_history_apps = $a_stmt->fetchAll(PDO::FETCH_ASSOC);
}

// --- 3. VERİ LİSTELEME VE HEMŞİRE İÇİN ARAMA MANTIĞI ---
$aktif_randevular = [];
$tum_hastalar = [];
$h_search = isset($_GET['h_q']) ? trim($_GET['h_q']) : '';

if($role_id == 2) {
    $stmt = $pdo->prepare("SELECT a.*, p.full_name as patient_name FROM appointments a JOIN users p ON a.patient_id = p.id WHERE a.doctor_id = ? AND a.status = 'Bekliyor' ORDER BY a.appointment_date ASC");
    $stmt->execute([$user_id]);
    $aktif_randevular = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Hemşire araması doluysa filtrele, boşsa alfabetik tüm listeyi getir
    if(!empty($h_search)) {
        $stmt = $pdo->prepare("SELECT id, full_name, phone FROM users WHERE role_id = 6 AND full_name LIKE ? ORDER BY full_name ASC");
        $stmt->execute(["%$h_search%"]);
        $tum_hastalar = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $tum_hastalar = $pdo->query("SELECT id, full_name, phone FROM users WHERE role_id = 6 ORDER BY full_name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<div class="container">
    <h2>🩺 Sağlık Personeli Çalışma Alanı (<?php echo $_SESSION['full_name']; ?> - <?php echo $role_id == 2 ? 'Uzm. Dr.' : 'Hemşire'; ?>)</h2>
    
    <?php if($msg) echo "<p style='background:#d4edda; color:#155724; padding:10px; border-radius:5px; font-weight:bold;'>$msg</p>"; ?>

    <div class="grid">
        
        <!-- SOL PANEL: VERİ GİRİŞİ VEYA HEMŞİRE ARAMALI HASTA LİSTESİ -->
        <div>
            <?php if($role_id == 2): ?>
                <!-- ================== DOKTOR PANELİ ================== -->
                <?php if($selected_app): ?>
                    <div style="background: #fff; padding: 20px; border: 2px solid #007bff; border-radius: 8px;">
                        <h3 style="margin-top:0; color:#007bff;">✏️ Aktif Muayene Ekranı</h3>
                        <div style="background:#f8f9fa; padding:10px; border-radius:5px; margin-bottom:15px; font-size:14px;">
                            <strong>Hasta:</strong> <?php echo htmlspecialchars($patient_info['full_name']); ?> (ID: <?php echo $patient_info['id']; ?>)<br>
                            <strong>Randevu Slotu:</strong> ⏰ <?php echo date("d.m.Y - H:i", strtotime($selected_app['appointment_date'])); ?>
                        </div>
                        <form method="POST" action="doktor_hemsire.php">
                            <input type="hidden" name="app_id" value="<?php echo $selected_app['id']; ?>">
                            <label><strong>Konulan Teşhis:</strong></label>
                            <textarea name="diagnosis" rows="4" required placeholder="Teşhis girin..." style="margin-bottom:10px;"></textarea>
                            <label><strong>Reçete Edilen İlaç:</strong></label>
                            <input type="text" name="medication" required placeholder="İlaç adı..." style="margin-bottom:15px;">
                            <button type="submit" name="complete_visit" class="btn btn-green" style="width:100%; font-weight:bold;">Muayeneyi Tamayla ve Reçeteyi Onayla</button>
                            <a href="doktor_hemsire.php" style="display:block; text-align:center; margin-top:12px; color:red; text-decoration:none;">❌ Muayeneden Çık</a>
                        </form>
                    </div>
                <?php else: ?>
                    <div style="background: #f9f9f9; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
                        <h3 style="margin-top:0; color:#007bff;">⏳ Bugün Bekleyen Randevularınız</h3>
                        <?php if(count($aktif_randevular) > 0): ?>
                            <table>
                                <thead><tr><th>Slot</th><th>Hasta Adı</th><th>Aksiyon</th></tr></thead>
                                <tbody>
                                    <?php foreach($aktif_randevular as $ar): ?>
                                    <tr>
                                        <td><strong>⏰ <?php echo date("H:i", strtotime($ar['appointment_date'])); ?></strong></td>
                                        <td><b><?php echo htmlspecialchars($ar['patient_name']); ?></b></td>
                                        <td><a href="doktor_hemsire.php?action=muayene&app_id=<?php echo $ar['id']; ?>" class="btn btn-blue" style="font-size:11px; padding:4px 8px;">Muayene Et</a></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p style="color:green; font-weight:bold;">✔️ Bekleyen aktif randevunuz bulunmuyor.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <!-- ================== HEMŞİRE PANELİ (ARAMA ÖZELLİKLİ) ================== -->
                <div style="background: #f9f9f9; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
                    <h3 style="margin-top:0; color:#17a2b8;">👥 Klinik Hasta İzlem Listesi</h3>
                    
                    <!-- HEMŞİRE İÇİN YENİ ARAMA FORMU -->
                    <form method="GET" action="doktor_hemsire.php" style="display:flex; gap:5px; margin-bottom:15px;">
                        <input type="hidden" name="action" value="<?php echo isset($_GET['action']) ? htmlspecialchars($_GET['action']) : ''; ?>">
                        <input type="hidden" name="p_id" value="<?php echo isset($_GET['p_id']) ? htmlspecialchars($_GET['p_id']) : ''; ?>">
                        <input type="text" name="h_q" placeholder="Hasta adı arayın..." value="<?php echo htmlspecialchars($h_search); ?>" style="margin:0; padding:6px;">
                        <button type="submit" class="btn btn-blue" style="width:auto; background:#17a2b8; border-color:#17a2b8; padding:0 15px;">Ara</button>
                    </form>

                    <div style="max-height: 350px; overflow-y:auto;">
                        <table>
                            <thead><tr><th>ID</th><th>Hasta Adı</th><th>İşlem</th></tr></thead>
                            <tbody>
                                <?php if(count($tum_hastalar) > 0): ?>
                                    <?php foreach($tum_hastalar as $th): ?>
                                    <tr style="<?php echo ($selected_patient_id == $th['id']) ? 'background:#e8f4f8; font-weight:bold;' : ''; ?>">
                                        <td><small style="background:#eee; padding:2px 5px; border-radius:3px;">ID: <?php echo $th['id']; ?></small></td>
                                        <td><?php echo htmlspecialchars($th['full_name']); ?></td>
                                        <td>
                                            <!-- Arama yapıldığında kaybolmaması için h_q parametresini de linke ekliyoruz -->
                                            <a href="doktor_hemsire.php?action=incele&p_id=<?php echo $th['id']; ?>&h_q=<?php echo urlencode($h_search); ?>" class="btn btn-blue" style="font-size:11px; padding:4px 8px; background:#17a2b8; border-color:#17a2b8; text-decoration:none;">Dosyayı Aç</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" style="color:red; text-align:center;">Aranan kritere uygun hasta bulunamadı.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($selected_patient_id): ?>
                        <br><a href="doktor_hemsire.php" class="btn btn-red" style="background:#6c757d; border-color:#6c757d; display:block; text-align:center; text-decoration:none; font-size:13px;">Seçimi Temizle / Boş Ekrana Dön</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- SAĞ PANEL: SEÇİLİ HASTA YOKSA TAMAMEN BOŞ KALAN KISIM -->
        <div>
            <?php if($selected_patient_id && $patient_info): ?>
                <!-- 🎯 HASTA SEÇİLDİĞİNDE AÇILAN MEDİKAL KLASÖR -->
                <div style="background: #fff; padding: 20px; border: 2px solid #17a2b8; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                    <h3 style="margin-top:0; color:#17a2b8; border-bottom:2px solid #17a2b8; padding-bottom:5px;">📁 Sağlık Klasörü: <?php echo htmlspecialchars($patient_info['full_name']); ?> (ID: <?php echo $patient_info['id']; ?>)</h3>
                    <p style="font-size:12px; color:#555; margin-top:-5px;">📞 <b>Tel:</b> <?php echo htmlspecialchars($patient_info['phone']); ?> | 📍 <b>Konum:</b> <?php echo htmlspecialchars($patient_info['district'] . " / " . $patient_info['city']); ?></p>
                    
                    <!-- 1. RANDEVU GEÇMİŞİ -->
                    <h4 style="color:#007bff; margin-bottom:5px; margin-top:15px; border-bottom:1px solid #ddd; padding-bottom:3px;">📅 Tüm Randevu Planları (Slot Zamanları)</h4>
                    <div style="max-height: 120px; overflow-y: auto; background:#fdfefe; padding:8px; border-radius:5px; border:1px solid #ddd; margin-bottom:15px;">
                        <?php if(count($patient_history_apps) > 0): ?>
                            <table style="font-size:11px; margin:0;">
                                <?php foreach($patient_history_apps as $pha): 
                                    $lbl = $pha['status'] == 'Bekliyor' ? 'background:#fff3cd; color:#856404;' : ($pha['status'] == 'Tamamlandı' ? 'background:#d4edda; color:#155724;' : 'background:#f8d7da; color:#721c24;');
                                ?>
                                    <tr>
                                        <td><b>⏰ <?php echo date("d.m.Y H:i", strtotime($pha['appointment_date'])); ?></b></td>
                                        <td>Dr. <?php echo htmlspecialchars($pha['doc_name']); ?></td>
                                        <td><span style="<?php echo $lbl; ?> padding:1px 4px; border-radius:3px; font-weight:bold; font-size:10px;"><?php echo $pha['status']; ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        <?php else: ?>
                            <p style="font-size:12px; color:#888; margin:0;">Kayıtlı randevu planı yok.</p>
                        <?php endif; ?>
                    </div>

                    <!-- 2. GEÇMİŞ MUAYENELER -->
                    <h4 style="color:#b35900; margin-bottom:5px; border-bottom:1px solid #ddd; padding-bottom:3px;">📜 Geçmiş Muayene & Reçete Kayıtları</h4>
                    <div style="max-height: 150px; overflow-y: auto; background:#fcfcfc; padding:10px; border-radius:5px; border:1px solid #ddd; margin-bottom:15px;">
                        <?php if(count($patient_history_visits) > 0): ?>
                            <?php foreach($patient_history_visits as $phv): ?>
                                <div style="font-size:12px; border-bottom:1px dashed #eee; padding-bottom:5px; margin-bottom:5px;">
                                    <small style="color:green; font-weight:bold;">⏱️ <?php echo date("d.m.Y H:i", strtotime($phv['visit_date'])); ?></small> | 
                                    <small style="color:#555;">Hekim: Uzm. Dr. <?php echo htmlspecialchars($phv['doc_name']); ?></small><br>
                                    <b>Teşhis:</b> <?php echo htmlspecialchars($phv['diagnosis']); ?><br>
                                    <span style="color:#d9534f; font-weight:bold;">💊 İlaç: <?php echo htmlspecialchars($phv['medication']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="font-size:12px; color:#888; margin:0;">Geçmiş vizit kaydı bulunamadı.</p>
                        <?php endif; ?>
                    </div>

                    <!-- 3. LABORATUVAR -->
                    <h4 style="color:#28a745; margin-bottom:5px; border-bottom:1px solid #ddd; padding-bottom:3px;">🧪 Biyokimya Lab Sonuç Karnesi</h4>
                    <div style="max-height: 150px; overflow-y: auto; background:#fff; padding:8px; border-radius:5px; border:1px solid #ddd;">
                        <?php if(count($patient_history_labs) > 0): ?>
                            <table style="font-size:11px; background:white; margin:0;">
                                <thead><tr><th>Tarih</th><th>Kan Değerleri</th><th>Onaylayan</th></tr></thead>
                                <tbody>
                                    <?php foreach($patient_history_labs as $phl): ?>
                                    <tr>
                                        <td><small><?php echo date("d.m.Y", strtotime($phl['test_date'])); ?></small></td>
                                        <td>
                                            Glikoz: <b><?php echo $phl['glucose']; ?></b> mg/dL<br>
                                            TSH: <b><?php echo $phl['tsh']; ?></b> mIU/L | İnsülin: <b><?php echo $phl['insulin']; ?></b>
                                        </td>
                                        <td><small style="color:gray;"><?php echo $phl['tech_name'] ? htmlspecialchars($phl['tech_name']) : 'Sistem'; ?></small></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p style="font-size:12px; color:#888; margin:0;">Laboratuvar tahlil sonucu bulunamadı.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- 🔍 HASTA SEÇİLMEDİYSE SAĞ TARAF TAMAMEN SADE VE BOŞ BİR REHBER EKRANIDIR -->
                <div style="border: 2px dashed #ccc; padding: 40px; text-align: center; border-radius: 8px; color: #777; margin-top:0;">
                    <span style="font-size: 40px;">🔍</span>
                    <h4 style="margin: 10px 0 5px 0;">Tıbbi Dosya İzleme Alanı</h4>
                    <p style="font-size: 13px; margin:0;">Detaylı tıbbi geçmişi, laboratuvar tahlillerini ve randevu slot durumlarını sağ tarafta incelemek için sol panelden bir hasta seçerek <b>"Dosyayı Aç"</b> butonuna basın.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
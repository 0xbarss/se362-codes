<?php 
include 'header.php'; 
require_once 'db.php';

// 1. GÜVENLİK KONTROLÜ
if($_SESSION['role_id'] != 4 && $_SESSION['role_id'] != 1) {
    die("<div class='container'><p style='color:red; font-weight:bold;'>Yetkisiz Erişim! Bu panele sadece resepsiyon kadrosu erişebilir.</p></div>");
}

$msg = ""; 
$error_msg = "";
$selected_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');
$day_of_week = date('N', strtotime($selected_date));

// HAFTA SONU KISITI: Cumartesi veya Pazar seçildiyse en yakın Pazartesiye yuvarla
if ($day_of_week > 5) {
    $selected_date = date('Y-m-d', strtotime('next Monday', strtotime($selected_date)));
}

// --- 2. HASTA KAYIT VEYA BİLGİ DÜZELTME (UPDATE / INSERT) İŞLEMİ ---
if (isset($_POST['save_patient'])) {
    $p_name = trim($_POST['full_name']);
    $p_phone = trim($_POST['phone']);
    $p_city = trim($_POST['city']);
    $p_dist = trim($_POST['district']);
    $edit_p_id = $_POST['edit_patient_id'];

    if (!empty($edit_p_id)) {
        // Düzenleme Modu
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, phone = ?, city = ?, district = ? WHERE id = ? AND role_id = 6");
        $stmt->execute([$p_name, $p_phone, $p_city, $p_dist, $edit_p_id]);
        $msg = "✔️ Hasta bilgileri başarıyla düzeltildi.";
    } else {
        // Yeni Kayıt Modu (Şifre varsayılan 123456, kullanıcı adı sistem üretimi)
        $username = "hasta_new_" . rand(100, 999) . rand(10, 99);
        $password = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (role_id, username, password, full_name, phone, city, district) VALUES (6, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$username, $password, $p_name, $p_phone, $p_city, $p_dist]);
        $msg = "✔️ Yeni hasta kaydı oluşturuldu. Sistem Kullanıcı Adı: $username";
    }
}

// Düzenlenecek hasta verisini seçip forma çekme
$edit_patient = null;
if (isset($_GET['edit_p_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role_id = 6");
    $stmt->execute([$_GET['edit_p_id']]);
    $edit_patient = $stmt->fetch(PDO::FETCH_ASSOC);
}

// --- 3. YENİ RANDEVU EKLEME MANTIĞI (GÜVENLİK KİLİTLİ) ---
if (isset($_POST['add_appointment'])) {
    $p_id = trim($_POST['patient_id']); // Gizli inputtan gelen hasta ID
    $d_id = trim($_POST['doctor_id']);
    $app_date = $_POST['app_date'];
    $app_time = $_POST['app_time'];

    // CRITICAL CONSTRAINT PROTECTION: Eğer hasta seçilmeden butona basıldıysa hatayı engelle
    if (empty($p_id)) {
        $error_msg = "❌ Hata: Randevu kaydedilemedi! Lütfen önce arama sonuçlarından bir hasta bulup yanındaki yeşil 'Seç' butonuna tıklayın.";
    } else {
        $full_datetime = $app_date . " " . $app_time . ":00";

        // Hafta içi kontrolü
        if (date('N', strtotime($app_date)) > 5) {
            $error_msg = "❌ Cumartesi ve Pazar günleri klinik kapalıdır! Sadece hafta içine randevu verilebilir.";
        } else {
            // Doktor Slot Doluluk Kontrolü
            $check = $pdo->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status = 'Bekliyor'");
            $check->execute([$d_id, $full_datetime]);

            if ($check->rowCount() > 0) {
                $error_msg = "❌ Hata: Seçilen doktorun o zaman slotu doludur! Lütfen matristeki yeşil alanlardan başka bir saat seçin.";
            } else {
                // Her şey yolundaysa kaydet
                $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, 'Bekliyor')");
                $stmt->execute([$p_id, $d_id, $full_datetime]);
                $msg = "✔️ Randevu başarıyla oluşturuldu: " . date("d.m.Y H:i", strtotime($full_datetime));
            }
        }
    }
}

// --- 4. BEKLEYEN RANDEVUYU İPTAL ETME ---
if (isset($_GET['cancel_id'])) {
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'İptal' WHERE id = ?");
    $stmt->execute([$_GET['cancel_id']]);
    $msg = "❌ Randevu başarıyla iptal edildi (Durumu İptal olarak işaretlendi).";
}

// --- 5. HASTA SÖZLÜK ARAMASI (İLK AÇILIŞTA BOŞ BAŞLAMA KURALI) ---
$search_q = isset($_GET['p_search']) ? trim($_GET['p_search']) : '';
$hastalar = [];
if (!empty($search_q)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role_id = 6 AND full_name LIKE ? ORDER BY full_name ASC");
    $stmt->execute(["%$search_q%"]);
    $hastalar = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ortak listeler ve Ajanda verileri
$doktorlar = $pdo->query("SELECT id, full_name FROM users WHERE role_id = 2 ORDER BY full_name ASC")->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT a.*, p.full_name as patient_name, d.full_name as doctor_name FROM appointments a JOIN users p ON a.patient_id = p.id JOIN users d ON a.doctor_id = d.id WHERE DATE(a.appointment_date) = ? ORDER BY a.appointment_date ASC");
$stmt->execute([$selected_date]);
$günün_randevulari = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dolu_slotlar = [];
$slot_stmt = $pdo->prepare("SELECT doctor_id, DATE_FORMAT(appointment_date, '%H:%i') as slot_time FROM appointments WHERE DATE(appointment_date) = ? AND status = 'Bekliyor'");
$slot_stmt->execute([$selected_date]);
while($row = $slot_stmt->fetch(PDO::FETCH_ASSOC)) {
    $dolu_slotlar[$row['doctor_id']][] = $row['slot_time'];
}

// Sabit Poliklinik Slotları (09:00 - 17:00 Akşam 5 Dahil)
$sabit_slotlar = [
    "09:00", "09:20", "09:40", "10:00", "10:20", "10:40", "11:00", "11:20", "11:40",
    "13:00", "13:20", "13:40", "14:00", "14:20", "14:40", "15:00", "15:20", "15:40", 
    "16:00", "16:20", "16:40", "17:00"
];
?>

<div class="container">
    <h2>🏢 Resepsiyonist Ana Kontrol Masası</h2>

    <?php if($msg) echo "<p style='background:#d4edda; color:#155724; padding:10px; border-radius:5px; font-weight:bold;'>$msg</p>"; ?>
    <?php if($error_msg) echo "<p style='background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; font-weight:bold; border-left:5px solid #dc3545;'>$error_msg</p>"; ?>

    <div class="grid">
        
        <div>
            <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 20px;">
                <h3 style="margin-top:0; color:#007bff;"><?php echo $edit_patient ? '✏️ Hasta Bilgilerini Düzelt' : '➕ Yeni Hasta Kaydı Oluştur'; ?></h3>
                <form method="POST" action="resepsiyon.php?filter_date=<?php echo $selected_date; ?>">
                    <input type="hidden" name="edit_patient_id" value="<?php echo $edit_patient ? $edit_patient['id'] : ''; ?>">
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap:10px;">
                        <div><small>Hasta Adı Soyadı:</small><input type="text" name="full_name" value="<?php echo $edit_patient ? htmlspecialchars($edit_patient['full_name']) : ''; ?>" required style="padding:5px;"></div>
                        <div><small>Telefon:</small><input type="text" name="phone" value="<?php echo $edit_patient ? htmlspecialchars($edit_patient['phone']) : ''; ?>" required style="padding:5px;"></div>
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap:10px; margin-top:5px;">
                        <div><small>Şehir:</small><input type="text" name="city" value="<?php echo $edit_patient ? htmlspecialchars($edit_patient['city']) : ''; ?>" required style="padding:5px;"></div>
                        <div><small>İlçe:</small><input type="text" name="district" value="<?php echo $edit_patient ? htmlspecialchars($edit_patient['district']) : ''; ?>" required style="padding:5px;"></div>
                    </div>
                    <button type="submit" name="save_patient" class="btn btn-green" style="width:100%; margin-top:10px; padding:6px; font-weight:bold;"><?php echo $edit_patient ? 'Değişiklikleri Veritabanına İşle' : 'Yeni Hastayı Sisteme Kaydet'; ?></button>
                    <?php if($edit_patient): ?>
                        <a href="resepsiyon.php?filter_date=<?php echo $selected_date; ?>" style="display:block; text-align:center; color:red; margin-top:8px; font-size:12px; text-decoration:none;">❌ Düzeltme İşlemini İptal Et</a>
                    <?php endif; ?>
                </form>
            </div>

            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="margin-top:0; color:#17a2b8;">🔍 Hasta Bul & Randevu Planla</h3>
                <form method="GET" style="display:flex; gap:5px; margin-bottom:10px;">
                    <input type="hidden" name="filter_date" value="<?php echo $selected_date; ?>">
                    <input type="text" name="p_search" placeholder="Öneri listesi için hasta adı yazın..." value="<?php echo htmlspecialchars($search_q); ?>" required style="margin:0; padding:6px;">
                    <button type="submit" class="btn btn-blue" style="width:auto; background:#17a2b8; border-color:#17a2b8;">Sistemde Ara</button>
                </form>

                <?php if(!empty($search_q)): ?>
                    <table style="font-size:12px; background:#fafafa;">
                        <thead><tr><th>Hasta Detay Bilgisi</th><th>Aksiyonlar</th></tr></thead>
                        <tbody>
                            <?php if(count($hastalar) > 0): ?>
                                <?php foreach($hastalar as $h): ?>
                                <tr>
                                    <td>
                                        <b><?php echo htmlspecialchars($h['full_name']); ?></b> <small style="color:gray;">(ID: <?php echo $h['id']; ?>)</small><br>
                                        <small style="color:#777;">📞 <?php echo htmlspecialchars($h['phone']); ?> | 📍 <?php echo htmlspecialchars($h['district']); ?></small>
                                    </td>
                                    <td>
                                        <a href="resepsiyon.php?filter_date=<?php echo $selected_date; ?>&edit_p_id=<?php echo $h['id']; ?>&p_search=<?php echo urlencode($search_q); ?>" class="btn btn-blue" style="font-size:10px; padding:2px 5px; background:#ffc107; border-color:#ffc107; color:#000; text-decoration:none; display:inline-block;">Düzelt</a>
                                        <button onclick="document.getElementById('p_target_id').value='<?php echo $h['id']; ?>'; document.getElementById('p_target_name').innerText='👤 <?php echo htmlspecialchars($h['full_name']); ?> (ID: <?php echo $h['id']; ?>)';" class="btn btn-green" style="font-size:10px; padding:2px 5px; margin-left:3px;">Seç</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" style="color:red; text-align:center; font-weight:bold; padding:10px;">Kritik: Aradığınız kriterde bir hasta kaydı bulunamadı.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="font-size:12px; color:#888; border:1px dashed #ccc; padding:12px; text-align:center; border-radius:4px; margin-bottom:15px;">🔍 Öneri listesini görmek, hasta bilgilerini düzeltmek veya randevu tanımlamak için yukarıdaki kutudan arama yapın (İlk açılışta liste gizlidir).</p>
                <?php endif; ?>

                <div id="randevu_form_alani" style="margin-top:15px; background:#eef7f9; padding:12px; border-radius:5px; border:1px solid #bee5eb;">
                    <div style="font-size:13px; margin-bottom:8px;">🎯 Rezerve Edilecek Hasta: <br><strong id="p_target_name" style="color:#007bff; display:inline-block; margin-top:3px;">Lütfen yukarıdaki ajanstan bir hasta "Seç" edin.</strong></div>
                    
                    <form method="POST" action="resepsiyon.php?filter_date=<?php echo $selected_date; ?>">
                        <input type="hidden" name="patient_id" id="p_target_id" required>
                        
                        <div class="grid" style="grid-template-columns: 1fr 1fr; gap:5px;">
                            <select name="doctor_id" required style="padding:4px; font-size:12px;">
                                <option value="">Uzman Hekim Seçin...</option>
                                <?php foreach($doktorlar as $d) echo "<option value='{$d['id']}'>Uzm. Dr. {$d['full_name']}</option>"; ?>
                            </select>
                            <input type="date" name="app_date" value="<?php echo $selected_date; ?>" required style="padding:4px; font-size:12px;" min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        
                        <div class="grid" style="grid-template-columns: 2fr 1fr; gap:5px; margin-top:8px; align-items: center;">
                            <select name="app_time" required style="padding:5px; font-size:12px;">
                                <option value="">Zaman Slotu Seçin (09:00 - 17:00)...</option>
                                <?php foreach($sabit_slotlar as $sl) echo "<option value='$sl'>$sl</option>"; ?>
                            </select>
                            <button type="submit" name="add_appointment" class="btn btn-blue" style="font-size: 11px; padding: 5px 10px; width: auto; white-space: nowrap; font-weight:bold;">Randevu Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div>
            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px;">
                <h3 style="margin-top:0; color:#28a745;">📅 Klinik Takvim Filtresi (Hafta İçi)</h3>
                <p style="font-size:11px; color:#666; margin-top:-5px;">Tarih seçimi yaptığınızda sistem otomatik olarak sadece hafta içi (5 iş günü) mesailerini filtreler.</p>
                <form method="GET" action="resepsiyon.php" style="display:flex; gap:5px;">
                    <input type="date" name="filter_date" value="<?php echo $selected_date; ?>" onchange="this.form.submit()" style="padding:5px;">
                </form>
                <div style="margin-top:10px; font-size:13px; font-weight:bold; color:#0056b3; background:#eef4fa; padding:5px; border-left:3px solid #0056b3;">
                    🔍 İncelenen Gün: <?php echo date("d.m.Y", strtotime($selected_date)); ?> (<?php $gunler = ["","Pazartesi","Salı","Çarşamba","Perşembe","Cuma"]; echo $gunler[date('N', strtotime($selected_date))]; ?>)
                </div>
            </div>

            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom:20px;">
                <h3 style="margin-top:0; color:#555;">📋 Günlük Planlanan Randevular</h3>
                <table style="font-size:12px;">
                    <thead><tr><th>Slot</th><th>Hasta / Hekim</th><th>Aksiyon</th></tr></thead>
                    <tbody>
                        <?php if(count($günün_randevulari) > 0): ?>
                            <?php foreach($günün_randevulari as $gr): $bg = $gr['status'] == 'İptal' ? 'background:#fdf2f2;' : ''; ?>
                            <tr style="<?php echo $bg; ?>">
                                <td><b>⏰ <?php echo date("H:i", strtotime($gr['appointment_date'])); ?></b></td>
                                <td>
                                    👤 <b><?php echo htmlspecialchars($gr['patient_name']); ?></b><br>
                                    <small style="color:gray;">👨‍⚕️ Uzm. Dr. <?php echo htmlspecialchars($gr['doctor_name']); ?></small>
                                </td>
                                <td>
                                    <?php if($gr['status'] == 'Bekliyor'): ?>
                                        <a href="resepsiyon.php?filter_date=<?php echo $selected_date; ?>&cancel_id=<?php echo $gr['id']; ?>" class="btn btn-red" style="padding:2px 5px; font-size:10px; background:#dc3545; text-decoration:none;" onclick="return confirm('Bu randevuyu iptal etmek istediğinize emin misiniz?')">İptal Et</a>
                                    <?php else: ?>
                                        <small style="font-weight:bold; color:<?php echo $gr['status']=='İptal'?'red':'green'; ?>;"><?php echo $gr['status']; ?></small>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align:center; color:gray; padding:15px;">Seçilen tarihe ait planlanmış bir randevu kaydı bulunmuyor.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div style="background: #fdfefe; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="margin-top:0; color:#6f42c1;">📊 Hekim Doluluk / Slot Matrisi</h3>
                <div style="max-height: 250px; overflow-y:auto; font-size:11px;">
                    <?php foreach($doktorlar as $doc): ?>
                        <div style="background:#fff; border:1px solid #eee; border-left:4px solid #6f42c1; padding:8px; margin-bottom:10px; border-radius:4px;">
                            <strong>👨‍⚕️ Uzm. Dr. <?php echo htmlspecialchars($doc['full_name']); ?></strong>
                            <div style="display:flex; flex-wrap:wrap; gap:4px; margin-top:5px;">
                                <?php 
                                foreach($sabit_slotlar as $slot): 
                                    $is_dolu = (isset($dolu_slotlar[$doc['id']]) && in_array($slot, $dolu_slotlar[$doc['id']]));
                                    $style = $is_dolu ? 'background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;' : 'background:#d4edda; color:#155724; border:1px solid #c3e6cb;';
                                ?>
                                    <span style="<?php echo $style; ?> padding:2px 4px; border-radius:3px; font-size:9px; font-weight:bold;">
                                        <?php echo $slot; ?><?php echo $is_dolu ? ' (Dolu)' : ''; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
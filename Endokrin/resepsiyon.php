<?php 
include 'header.php'; 
require_once 'db.php';

if($_SESSION['role_id'] != 4) die("Yetkisiz Erişim!");

$msg = "";
$msg_type = "green";

// --- 1. YENİ HASTA KAYDI ---
if(isset($_POST['add_patient'])){
    $pw = password_hash('123456', PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (role_id, username, password, full_name, phone, city, district) VALUES (6, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['p_un'], $pw, $_POST['p_fn'], $_POST['p_ph'], $_POST['p_ci'], $_POST['p_di']]);
        $msg = "✔️ Yeni hasta kaydedildi: " . $_POST['p_fn'];
    } catch(Exception $e) { $msg = "❌ Hata: Kullanıcı adı zaten alınmış olabilir."; $msg_type = "red"; }
}

// --- 2. RANDEVU KAYIT VE ÇAKIŞMA KONTROLÜ ---
if(isset($_POST['add_app'])){
    $full_dt = $_POST['app_date'] . " " . $_POST['app_time'] . ":00";
    
    // Çakışma Kontrolü
    $check = $pdo->prepare("SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ?");
    $check->execute([$_POST['d_id'], $full_dt]);
    
    if($check->rowCount() > 0){
        $msg = "❌ HATA: Seçilen doktorun bu saatte (".$_POST['app_time'].") zaten randevusu var!";
        $msg_type = "red";
    } else {
        $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['p_id'], $_POST['d_id'], $full_dt]);
        $msg = "✔️ Randevu başarıyla oluşturuldu.";
    }
}

// --- 3. RANDEVU İPTAL ---
if(isset($_GET['del_id'])){
    $pdo->prepare("DELETE FROM appointments WHERE id = ?")->execute([$_GET['del_id']]);
    header("Location: resepsiyon.php?msg=Randevu İptal Edildi"); exit;
}

// --- VERİLERİ ÇEKME ---
$search = isset($_GET['q']) ? $_GET['q'] : '';
$hastalar = $pdo->query("SELECT * FROM users WHERE role_id = 6 ORDER BY full_name ASC")->fetchAll();
$doktorlar = $pdo->query("SELECT * FROM users WHERE role_id = 2 ORDER BY full_name ASC")->fetchAll();

$sql = "SELECT a.*, p.full_name as p_name, d.full_name as d_name FROM appointments a 
        JOIN users p ON a.patient_id = p.id JOIN users d ON a.doctor_id = d.id";
if($search) {
    $stmt = $pdo->prepare($sql . " WHERE p.full_name LIKE ? ORDER BY a.appointment_date ASC");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query($sql . " ORDER BY a.appointment_date ASC");
}
$randevular = $stmt->fetchAll();
?>

<div class="container">
    <h2>📌 Resepsiyonist İşlem Merkezi</h2>
    
    <?php if($msg || isset($_GET['msg'])): ?>
        <p style="background:<?php echo $msg_type == 'red' ? '#f8d7da' : '#d4edda'; ?>; color:<?php echo $msg_type == 'red' ? '#721c24' : '#155724'; ?>; padding:15px; border-radius:5px;">
            <?php echo $msg ? $msg : $_GET['msg']; ?>
        </p>
    <?php endif; ?>

    <div class="grid">
        <div>
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom:20px;">
                <h3 style="margin-top:0; color:#007bff;">👤 Yeni Hasta Kaydı</h3>
                <form method="POST">
                    <input type="text" name="p_fn" placeholder="Ad Soyad" required>
                    <input type="text" name="p_un" placeholder="Kullanıcı Adı" required>
                    <input type="text" name="p_ph" placeholder="Telefon (53XXXXXXXX)" maxlength="10">
                    <div style="display:flex; gap:5px;">
                        <input type="text" name="p_ci" placeholder="İl">
                        <input type="text" name="p_di" placeholder="İlçe">
                    </div>
                    <button type="submit" name="add_patient" class="btn btn-blue" style="width:100%">Hastayı Kaydet</button>
                </form>
            </div>

            <div style="background: #f9f9f9; padding: 15px; border: 1px solid #ccc; border-radius: 8px;">
                <h3 style="margin-top:0; color:#28a745;">📅 Randevu Planla</h3>
                <form method="POST">
                    <label>Hasta Seç:</label>
                    <select name="p_id" required>
                        <option value="">Seçiniz...</option>
                        <?php foreach($hastalar as $h) echo "<option value='{$h['id']}'>{$h['full_name']}</option>"; ?>
                    </select>

                    <label>Doktor Seç:</label>
                    <select name="d_id" required>
                        <?php foreach($doktorlar as $d) echo "<option value='{$d['id']}'>{$d['full_name']}</option>"; ?>
                    </select>

                    <label>Tarih:</label>
                    <input type="date" name="app_date" min="<?php echo date('Y-m-d'); ?>" required>

                    <label>Saat (Slot):</label>
                    <select name="app_time" required>
                        <?php 
                        for($h=9; $h<=17; $h++){
                            foreach(['00','20','40'] as $m){
                                $val = str_pad($h,2,"0",STR_PAD_LEFT).":".$m;
                                echo "<option value='$val'>$val</option>";
                            }
                        }
                        ?>
                    </select>
                    <button type="submit" name="add_app" class="btn btn-green" style="width:100%; margin-top:10px;">Randevuyu Onayla</button>
                </form>
            </div>
        </div>

        <div>
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="margin-top:0;">🔍 Randevu Ara</h3>
                <form method="GET" style="display:flex; gap:5px;">
                    <input type="text" name="q" placeholder="Hasta ismiyle ara..." value="<?php echo htmlspecialchars($search); ?>" style="margin:0;">
                    <button type="submit" class="btn btn-blue" style="width:auto;">Ara</button>
                </form>

                <table style="margin-top:20px; font-size:13px;">
                    <thead><tr><th>Tarih/Saat</th><th>Hasta/Doktor</th><th>İşlem</th></tr></thead>
                    <tbody>
                        <?php foreach($randevular as $r): ?>
                        <tr>
                            <td><strong><?php echo date("H:i", strtotime($r['appointment_date'])); ?></strong><br><?php echo date("d.m.Y", strtotime($r['appointment_date'])); ?></td>
                            <td>H: <?php echo $r['p_name']; ?><br>D: <?php echo $r['d_name']; ?></td>
                            <td><a href="?del_id=<?php echo $r['id']; ?>" class="btn btn-red" style="padding:2px 5px; font-size:11px;" onclick="return confirm('İptal edilsin mi?')">Sil</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php 
include 'header.php'; 
require_once 'db.php';

if($_SESSION['role_id'] != 6) die("Yetkisiz Erişim!");

$p_id = $_SESSION['user_id'];

// 1. Randevuları Çek
$randevular = $pdo->prepare("SELECT a.*, d.full_name as dr FROM appointments a JOIN users d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.appointment_date ASC");
$randevular->execute([$p_id]);

// 2. Muayene Geçmişi (Teşhis ve İlaçlar)
$gecmis = $pdo->prepare("SELECT v.*, d.full_name as dr FROM visits v JOIN users d ON v.doctor_id = d.id WHERE v.patient_id = ? ORDER BY v.visit_date DESC");
$gecmis->execute([$p_id]);

// 3. Kan Değerleri
$lab = $pdo->prepare("SELECT * FROM lab_results WHERE patient_id = ? ORDER BY test_date DESC");
$lab->execute([$p_id]);
?>

<div class="container">
    <h2>👋 Merhaba, <?php echo $_SESSION['full_name']; ?></h2>
    <p>Klinik kayıtlarınız aşağıda listelenmiştir.</p>

    <div class="grid">
        <div>
            <div style="background:#e7f3ff; padding:15px; border-radius:8px; margin-bottom:20px; border-left:5px solid #007bff;">
                <h3>📅 Randevularım</h3>
                <table>
                    <?php while($r = $randevular->fetch()) echo "<tr><td>".date("d.m.Y H:i", strtotime($r['appointment_date']))."</td><td>{$r['dr']}</td></tr>"; ?>
                </table>
            </div>

            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px;">
                <h3>🧪 Son Kan Değerlerim</h3>
                <table>
                    <tr><th>Tarih</th><th>Şeker</th><th>TSH</th></tr>
                    <?php while($l = $lab->fetch()) echo "<tr><td>".date("d.m.Y", strtotime($l['test_date']))."</td><td>{$l['glucose']}</td><td>{$l['tsh']}</td></tr>"; ?>
                </table>
            </div>
        </div>

        <div>
            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px;">
                <h3>📜 Geçmiş Muayene ve İlaçlarım</h3>
                <?php while($v = $gecmis->fetch()): ?>
                    <div style="border-bottom:1px solid #eee; padding:10px 0;">
                        <small><?php echo date("d.m.Y", strtotime($v['visit_date'])); ?></small><br>
                        <strong>Dr. <?php echo $v['dr']; ?></strong><br>
                        <span>Teşhis: <?php echo $v['diagnosis']; ?></span><br>
                        <strong style="color:red;">💊 İlaç: <?php echo $v['medication']; ?></strong>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
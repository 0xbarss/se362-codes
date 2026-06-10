<?php 
include 'header.php'; 
require_once 'db.php';

if($_SESSION['role_id'] != 6) die("Yetkisiz Erişim!");

$p_id = $_SESSION['user_id'];

// 1. YAKLAŞAN RANDEVULAR (Gelecekteki ve durumu 'Bekliyor' olanlar)
$yaklasan_query = $pdo->prepare("
    SELECT a.*, d.full_name as dr 
    FROM appointments a 
    JOIN users d ON a.doctor_id = d.id 
    WHERE a.patient_id = ? AND a.appointment_date >= NOW() AND a.status = 'Bekliyor'
    ORDER BY a.appointment_date ASC
");
$yaklasan_query->execute([$p_id]);

// 2. RANDEVU GEÇMİŞİ (Sadece Resepsiyonist tarafından İPTAL EDİLENLER)
$iptal_query = $pdo->prepare("
    SELECT a.*, d.full_name as dr 
    FROM appointments a 
    JOIN users d ON a.doctor_id = d.id 
    WHERE a.patient_id = ? AND a.status = 'İptal'
    ORDER BY a.appointment_date DESC
");
$iptal_query->execute([$p_id]);

// 3. MUAYENE GEÇMİŞİ (Doktorun muayene edip TAMAMLADIĞI vizitler - 20dk slotlu)
$vizitler = $pdo->prepare("
    SELECT v.*, d.full_name as dr 
    FROM visits v 
    JOIN users d ON v.doctor_id = d.id 
    WHERE v.patient_id = ? 
    ORDER BY v.visit_date DESC
");
$vizitler->execute([$p_id]);

// 4. LABORATUVAR SONUÇLARI
$lab = $pdo->prepare("SELECT * FROM lab_results WHERE patient_id = ? ORDER BY test_date DESC");
$lab->execute([$p_id]);
?>

<div class="container">
    <h2>👋 Merhaba, <?php echo $_SESSION['full_name']; ?></h2>
    <p>Klinik kayıtlarınız, tıbbi geçmişiniz ve randevu durumlarınız aşağıdadır.</p>

    <div class="grid">
        <!-- SOL SÜTUN: AKTİF VE İPTAL RANDEVULAR -->
        <div>
            <!-- YAKLAŞAN RANDEVULAR -->
            <div style="background:#e7f3ff; padding:15px; border-radius:8px; margin-bottom:20px; border-left:5px solid #007bff;">
                <h3 style="margin-top:0; color:#0056b3;">📅 Güncel Randevularım (Bekleyen)</h3>
                <?php if($yaklasan_query->rowCount() > 0): ?>
                    <table style="font-size:14px;">
                        <tr><th>Tarih / Saat (Slot)</th><th>Hekim</th></tr>
                        <?php while($r = $yaklasan_query->fetch()): ?>
                            <tr>
                                <td><strong>⏰ <?php echo date("d.m.Y H:i", strtotime($r['appointment_date'])); ?></strong></td>
                                <td>Uzm. Dr. <?php echo $r['dr']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p style="font-size:13px; color:#666;">Planlanmış aktif bir randevunuz bulunmuyor.</p>
                <?php endif; ?>
            </div>

            <!-- İPTAL EDİLEN RANDEVULAR -->
            <div style="background:#fceade; padding:15px; border:1px solid #f5c6cb; border-radius:8px; margin-bottom:20px; border-left:5px solid #dc3545;">
                <h3 style="margin-top:0; color:#c7254e;">❌ İptal Edilen Randevularım</h3>
                <?php if($iptal_query->rowCount() > 0): ?>
                    <table style="font-size:13px; color:#444;">
                        <tr><th>İptal Edilen Zaman</th><th>Hekim</th></tr>
                        <?php while($ir = $iptal_query->fetch()): ?>
                            <tr style="background:#fff;">
                                <td style="color:#dc3545;"><s><?php echo date("d.m.Y H:i", strtotime($ir['appointment_date'])); ?></s></td>
                                Kak<td>Uzm. Dr. <?php echo $ir['dr']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p style="font-size:13px; color:#888;">İptal edilmiş bir randevunuz bulunmuyor.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- SAĞ SÜTUN: GERÇEKLEŞEN MUAYENELER VE LAB -->
        <div>
            <!-- GERÇEKLEŞEN MUAYENE GEÇMİŞİ -->
            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px; border-left:5px solid #28a745;">
                <h3 style="margin-top:0; color:#28a745;">📜 Gerçekleşen Muayene ve Reçete Geçmişim</h3>
                <?php if($vizitler->rowCount() > 0): ?>
                    <?php while($v = $vizitler->fetch()): ?>
                        <div style="border-bottom: 1px solid #eee; padding:10px 0;">
                            <!-- Doktorun muayeneyi tamamladığı 20'şer dakikalık kesin slot zamanı -->
                            <span style="background:#e2f0d9; color:#385723; padding:3px 8px; border-radius:4px; font-weight: bold; font-size: 13px;">
                                ⏱️ <?php echo date("d.m.Y - H:i", strtotime($v['visit_date'])); ?>
                            </span><br>
                            
                            <strong style="display:inline-block; margin-top:8px;">👨‍⚕️ Uzm. Dr. <?php echo $v['dr']; ?></strong><br>
                            <span style="display:block; margin:5px 0;"><strong>Konulan Teşhis:</strong> <?php echo $v['diagnosis']; ?></span>
                            <span style="color:#d9534f; font-weight:bold;">💊 Reçete: <?php echo $v['medication']; ?></span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color:#666;">Henüz tamamlanmış bir muayene kaydınız bulunmuyor.</p>
                <?php endif; ?>
            </div>

            <!-- LABORATUVAR -->
            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px;">
                <h3>🧪 Biyokimya Kan Değerlerim</h3>
                <table style="font-size:13px;">
                    <tr><th>Analiz Tarihi</th><th>Glikoz</th><th>TSH</th></tr>
                    <?php while($l = $lab->fetch()) echo "<tr><td>".date("d.m.Y", strtotime($l['test_date']))."</td><td>{$l['glucose']} mg/dL</td><td>{$l['tsh']} mIU/L</td></tr>"; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php include 'header.php'; require_once 'db.php';
if($_SESSION['role_id'] != 1) die("Yetkisiz Giriş!");

// Rapor 1: Hangi Doktor Hangi Hastalara Bakmış?
$doktor_hasta = $pdo->query("SELECT d.full_name as dr_name, p.full_name as pt_name, v.visit_date 
    FROM visits v 
    JOIN users d ON v.doctor_id = d.id 
    JOIN users p ON v.patient_id = p.id 
    ORDER BY d.full_name ASC")->fetchAll();

// Rapor 2: Hangi Doktor Hangi İlacı Kaç Kez Yazmış?
$doktor_ilac = $pdo->query("SELECT d.full_name as dr_name, v.medication, COUNT(*) as adet 
    FROM visits v 
    JOIN users d ON v.doctor_id = d.id 
    GROUP BY d.id, v.medication 
    ORDER BY adet DESC")->fetchAll();
?>
<div class="container">
    <h2>Yönetici Detaylı Raporlar</h2>
    
    <h3>👨‍⚕️ Doktor - Hasta Muayene Listesi</h3>
    <table>
        <tr><th>Doktor Adı</th><th>Hasta Adı</th><th>Tarih</th></tr>
        <?php foreach($doktor_hasta as $row): ?>
        <tr>
            <td><?php echo $row['dr_name']; ?></td>
            <td><?php echo $row['pt_name']; ?></td>
            <td><?php echo $row['visit_date']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>

    <h3>💊 Doktorların İlaç Yazma İstatistikleri</h3>
    <table>
        <tr><th>Doktor Adı</th><th>Yazılan İlaç</th><th>Toplam Adet</th></tr>
        <?php foreach($doktor_ilac as $row): ?>
        <tr>
            <td><?php echo $row['dr_name']; ?></td>
            <td><?php echo $row['medication']; ?></td>
            <td><strong><?php echo $row['adet']; ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <br>
    <a href="dashboard.php" class="btn btn-blue">Anasayfaya Dön</a>
</div>
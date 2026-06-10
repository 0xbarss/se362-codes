<?php 
include 'header.php'; 
require_once 'db.php';

// 1. GÜVENLİK KONTROLÜ
if($_SESSION['role_id'] != 1) {
    die("<div class='container'><p style='color:red; font-weight:bold;'>Yetkisiz Erişim! Bu sayfa sadece Yöneticiye özeldir.</p></div>");
}

$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
?>

<div class="container">
    <h2>📊 Hastane Yönetim ve Gelişmiş Raporlama Merkezi</h2>
    <p>Klinik operasyonel verilerini, doktor performanslarını ve ilaç analizlerini buradan takip edebilirsiniz.</p>
    
    <div style="margin-bottom: 20px; background: #eee; padding: 10px; border-radius: 5px;">
        <a href="admin.php" class="btn btn-blue" style="background: #6c757d; font-size: 13px;">🏠 Ana Raporlama Paneli</a>
        <a href="admin.php?view=en_cok_gelenler" class="btn btn-blue" style="background: #28a745; font-size: 13px;">👥 En Çok Gelen Hastalar</a>
    </div>

    <?php 
    // ==========================================
    // SAYFA 1: EN ÇOK GELEN HASTALAR SAYFASI
    // ==========================================
    if ($view == 'en_cok_gelenler'): 
        $patient_ranks = $pdo->query("
            SELECT u.id, u.full_name, u.phone, u.city, COUNT(v.id) as gelis_sayisi 
            FROM users u
            JOIN visits v ON u.id = v.patient_id
            WHERE u.role_id = 6
            GROUP BY u.id
            ORDER BY gelis_sayisi DESC
        ")->fetchAll();
    ?>
        <div style="background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
            <h3 style="color: #28a745; margin-top:0;">👥 Hastaneyi En Sık Ziyaret Eden Hasta Listesi</h3>
            <p>Klinik muayene kayıtlarına göre en çok geliş yapan hastaların sıralaması. <i>Detaylı profil ve muayene geçmişini görmek için hasta adına tıklayabilirsiniz.</i></p>
            <table>
                <thead>
                    <tr>
                        <th>Sıra</th>
                        <th>Hasta ID</th>
                        <th>Hasta Adı Soyadı</th>
                        <th>İletişim / Şehir</th>
                        <th>Toplam Muayene</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sira = 1;
                    foreach($patient_ranks as $pr): 
                    ?>
                    <tr>
                        <td><b><?php echo $sira++; ?></b></td>
                        <td><small style="background:#eee; padding:2px 5px; border-radius:3px;">ID: <?php echo $pr['id']; ?></small></td>
                        <td>
                            <a href="admin.php?view=hasta_vizit_detay&patient_id=<?php echo $pr['id']; ?>" style="color: #007bff; font-weight: bold; text-decoration: none;">
                                👤 <?php echo htmlspecialchars($pr['full_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($pr['phone'] . " - " . $pr['city']); ?></td>
                        <td style="background: #eafaf1; text-align: center; font-weight: bold; color: #28a745;"><?php echo $pr['gelis_sayisi']; ?> Kez</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php 
    // ==========================================
    // SAYFA 2: HASTA DETAYLI TIBBİ DOSYA SAYFASI (DOKTOR FORMATI DÜZELTİLDİ)
    // ==========================================
    elseif ($view == 'hasta_vizit_detay' && isset($_GET['patient_id'])):
        $patient_id = $_GET['patient_id'];

        $p_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role_id = 6");
        $p_stmt->execute([$patient_id]);
        $patient_data = $p_stmt->fetch(PDO::FETCH_ASSOC);

        // Muayene Geçmişi
        $v_stmt = $pdo->prepare("
            SELECT v.*, d.full_name as doc_name 
            FROM visits v
            JOIN users d ON v.doctor_id = d.id
            WHERE v.patient_id = ?
            ORDER BY v.visit_date DESC
        ");
        $v_stmt->execute([$patient_id]);
        $vizitler = $v_stmt->fetchAll();

        // Laboratuvar Geçmişi
        $l_stmt = $pdo->prepare("
            SELECT l.*, t.full_name as tech_name 
            FROM lab_results l
            LEFT JOIN users t ON l.technician_id = t.id
            WHERE l.patient_id = ?
            ORDER BY l.test_date DESC
        ");
        $l_stmt->execute([$patient_id]);
        $lab_sonuclari = $l_stmt->fetchAll();
    ?>
        <div style="background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 8px; margin-bottom: 20px;">
            <h3 style="color: #17a2b8; margin-top:0;">👤 Hasta Dijital Sağlık Klasörü (ID: <?php echo $patient_data['id']; ?>)</h3>
            <p><strong>Hasta Adı:</strong> <?php echo htmlspecialchars($patient_data['full_name']); ?> | <strong>Tel:</strong> <?php echo htmlspecialchars($patient_data['phone']); ?> | <strong>Konum:</strong> <?php echo htmlspecialchars($patient_data['district'] . " / " . $patient_data['city']); ?></p>
        </div>

        <div class="grid">
            <!-- Sol: Muayene Kayıtları -->
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#007bff; margin-top:0;">📜 Geçmiş Muayene ve Reçeteleri</h3>
                <?php if(count($vizitler) > 0): ?>
                    <table>
                        <thead>
                            <tr><th>Tarih</th><th>Sorumlu Hekim</th><th>Teşhis / İlaç</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($vizitler as $v): ?>
                            <tr>
                                <td><small><?php echo date("d.m.Y H:i", strtotime($v['visit_date'])); ?></small></td>
                                <!-- DOKTOR FORMATI GÜNCELLENEN KISIM -->
                                <td><span style="font-weight: 600; color: #2c3e50;">👨‍⚕️ Uzm. Dr. <?php echo htmlspecialchars($v['doc_name']); ?></span></td>
                                <td>
                                    <span style="background:#fff3cd; padding:1px 4px; border-radius:3px; font-size:12px; display:inline-block; margin-bottom:3px;"><?php echo htmlspecialchars($v['diagnosis']); ?></span><br>
                                    <strong style="color:#d9534f; font-size:12px;">💊 <?php echo htmlspecialchars($v['medication'] ? $v['medication'] : 'Yazılmadı'); ?></strong>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#999; font-size:13px;">Kayıtlı muayene geçmişi bulunmuyor.</p>
                <?php endif; ?>
            </div>

            <!-- Sağ: Laboratuvar Tahlil Kayıtları -->
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#28a745; margin-top:0;">🧪 Geçmiş Laboratuvar Analizleri</h3>
                <?php if(count($lab_sonuclari) > 0): ?>
                    <table>
                        <thead>
                            <tr><th>Analiz Tarihi</th><th>Değerler</th><th>Onaylayan</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach($lab_sonuclari as $l): ?>
                            <tr>
                                <td><small><?php echo date("d.m.Y H:i", strtotime($l['test_date'])); ?></small></td>
                                <td style="font-size:12px;">
                                    Glikoz: <b><?php echo $l['glucose']; ?></b><br>
                                    TSH: <b><?php echo $l['tsh']; ?></b><br>
                                    İnsülin: <b><?php echo $l['insulin']; ?></b>
                                </td>
                                <td><small style="color:green;"><?php echo $l['tech_name'] ? htmlspecialchars($l['tech_name']) : 'Sistem'; ?></small></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="color:#999; font-size:13px;">Kayıtlı laboratuvar sonucu bulunmuyor.</p>
                <?php endif; ?>
            </div>
        </div>
        <br>
        <a href="admin.php" class="btn btn-blue" style="background:#6c757d; display:inline-block;">🔙 Ana Panele Geri Dön</a>

    <?php 
    // ==========================================
    // SAYFA 3: DOKTOR ÖZEL ANALİZ SAYFASI
    // ==========================================
    elseif ($view == 'doktor_detay' && isset($_GET['doc_id'])): 
        $doc_id = $_GET['doc_id'];
        
        $doc_stmt = $pdo->prepare("SELECT full_name FROM users WHERE id = ? AND role_id = 2");
        $doc_stmt->execute([$doc_id]);
        $doc_name = $doc_stmt->fetchColumn();

        $doc_patients = $pdo->prepare("
            SELECT u.id, u.full_name, COUNT(v.id) as muayene_sayisi
            FROM users u
            JOIN visits v ON u.id = v.patient_id
            WHERE v.doctor_id = ?
            GROUP BY u.id
            ORDER BY muayene_sayisi DESC
        ");
        $doc_patients->execute([$doc_id]);
        $patients_list = $doc_patients->fetchAll();

        $doc_meds = $pdo->prepare("
            SELECT medication, COUNT(*) as yazilma_adedi 
            FROM visits 
            WHERE doctor_id = ? AND medication != '' AND medication IS NOT NULL
            GROUP BY medication
            ORDER BY yazilma_adedi DESC
        ");
        $doc_meds->execute([$doc_id]);
        $meds_list = $doc_meds->fetchAll();
    ?>
        <div class="grid">
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#007bff; margin-top:0;">👥 Dr. <?php echo htmlspecialchars($doc_name); ?> - Hasta Listesi</h3>
                <p>Doktorun takip ettiği hastalar ve muayene edilme frekansları (Çoktan Aza):</p>
                <table>
                    <thead>
                        <tr><th>Hasta ID / Adı</th><th>Muayene Sayısı</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach($patients_list as $pl): ?>
                        <tr>
                            <td>
                                <small style="background:#eee; padding:1px 4px;">ID: <?php echo $pl['id']; ?></small> 
                                <b><?php echo htmlspecialchars($pl['full_name']); ?></b>
                            </td>
                            <td style="text-align:center; font-weight:bold; color:#007bff;"><?php echo $pl['muayene_sayisi']; ?> Kez</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="background: #fdfefe; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#d9534f; margin-top:0;">💊 En Sık Yazdığı İlaçlar</h3>
                <p>Doktorun hastalarına en çok reçete ettiği ilaçların adet bazlı sıralaması:</p>
                <table>
                    <thead>
                        <tr><th>Sıra</th><th>İlaç Adı</th><th>Reçete Edilme Adedi</th></tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i_sira = 1;
                        foreach($meds_list as $ml): 
                        ?>
                        <tr>
                            <td><?php echo $i_sira++; ?></td>
                            <td><strong style="color:#d9534f;">💊 <?php echo htmlspecialchars($ml['medication']); ?></strong></td>
                            <td style="text-align:center; font-weight:bold;"><?php echo $ml['yazilma_adedi']; ?> Reçete</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php 
    // ==========================================
    // SAYFA 4: İLAÇ ÖZEL ANALİZ SAYFASI
    // ==========================================
    elseif ($view == 'ilac_detay' && isset($_GET['med_name'])):
        $med_name = trim($_GET['med_name']);

        $med_docs = $pdo->prepare("
            SELECT d.full_name as doc_name, COUNT(v.id) as verilme_sayisi
            FROM visits v
            JOIN users d ON v.doctor_id = d.id
            WHERE v.medication = ?
            GROUP BY v.doctor_id
            ORDER BY verilme_sayisi DESC
        ");
        $med_docs->execute([$med_name]);
        $med_docs_list = $med_docs->fetchAll();

        $med_patients = $pdo->prepare("
            SELECT p.id, p.full_name as pat_name, COUNT(v.id) as alinma_sayisi
            FROM visits v
            JOIN users p ON v.patient_id = p.id
            WHERE v.medication = ?
            GROUP BY v.patient_id
            ORDER BY alinma_sayisi DESC
        ");
        $med_patients->execute([$med_name]);
        $med_pats_list = $med_patients->fetchAll();
    ?>
        <div class="grid">
            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#6f42c1; margin-top:0;">👨‍⚕️ İlacı En Çok Yazan Doktorlar</h3>
                <p><strong><?php echo htmlspecialchars($med_name); ?></strong> ilacını en sık reçete eden hekimler:</p>
                <table>
                    <thead><tr><th>Doktor Adı</th><th>Yazma Sayısı</th></tr></thead>
                    <tbody>
                        <?php foreach($med_docs_list as $md) echo "<tr><td><b>Dr. {$md['doc_name']}</b></td><td style='text-align:center; font-weight:bold;'>{$md['verilme_sayisi']} Kez</td></tr>"; ?>
                    </tbody>
                </table>
            </div>

            <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <h3 style="color:#fd7e14; margin-top:0;">👥 İlacı En Çok Alan Hastalar</h3>
                <p><strong><?php echo htmlspecialchars($med_name); ?></strong> ilacını en yoğun kullanan hastalar (Çoktan Aza):</p>
                <table>
                    <thead><tr><th>Hasta ID / Adı</th><th>Kullanım Adedi</th></tr></thead>
                    <tbody>
                        <?php foreach($med_pats_list as $mp): ?>
                        <tr>
                            <td><small style="background:#eee; padding:1px 3px;">ID: <?php echo $mp['id']; ?></small> <?php echo htmlspecialchars($mp['pat_name']); ?></td>
                            <td style="text-align:center; font-weight:bold; color:#fd7e14;"><?php echo $mp['alinma_sayisi']; ?> Defa</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php 
    // ==========================================
    // SAYFA 0: ANA PANEL (DASHBOARD)
    // ==========================================
    else: 
        $doktorlar = $pdo->query("SELECT id, full_name FROM users WHERE role_id = 2 ORDER BY full_name ASC")->fetchAll();
        $mevcut_ilaclar = $pdo->query("SELECT DISTINCT medication FROM visits WHERE medication != '' AND medication IS NOT NULL ORDER BY medication ASC")->fetchAll();
        $tum_hastalar = $pdo->query("SELECT id, full_name FROM users WHERE role_id = 6 ORDER BY full_name ASC")->fetchAll();
    ?>
        <div class="grid">
            <div>
                <!-- 1. HASTA DETAY SORGULAMA PANELİ -->
                <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #17a2b8;">
                    <h3 style="margin-top:0; color:#17a2b8;">👤 Hasta Tıbbi Dosya Sorgulama</h3>
                    <p style="font-size:13px; color:#666;">Sistemdeki herhangi bir hastayı seçerek muayenelerini, teşhislerini ve laboratuvar kan değerlerini tek sayfada bir arada inceleyin.</p>
                    <form method="GET" action="admin.php">
                        <input type="hidden" name="view" value="hasta_vizit_detay">
                        <label>Hasta Seçin:</label>
                        <select name="patient_id" required>
                            <option value="">Hasta Listesinden Seçin...</option>
                            <?php foreach($tum_hastalar as $has) echo "<option value='{$has['id']}'>👤 {$has['full_name']} (ID: {$has['id']})</option>"; ?>
                        </select>
                        <button type="submit" class="btn btn-blue" style="width:100%; margin-top:10px; background:#17a2b8;">Hastanın Tüm Dosyasını Aç</button>
                    </form>
                </div>

                <!-- 2. DOKTOR PERFORMANS SORGULAMA -->
                <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px;">
                    <h3 style="margin-top:0; color:#007bff;">👨‍⚕️ Doktor Performans Analizi</h3>
                    <p style="font-size:13px; color:#666;">Bir doktor seçerek baktığı tüm hastaların yoğunluk sırasını ve yazdığı ilaç adetlerini listeyin.</p>
                    <form method="GET" action="admin.php">
                        <input type="hidden" name="view" value="doktor_detay">
                        <label>Doktor Seçin:</label>
                        <select name="doc_id" required>
                            <option value="">Seçiniz...</option>
                            <?php foreach($doktorlar as $d) echo "<option value='{$d['id']}'>{$d['full_name']}</option>"; ?>
                        </select>
                        <button type="submit" class="btn btn-blue" style="width:100%; margin-top:10px;">Doktor Raporunu Aç</button>
                    </form>
                </div>

                <!-- 3. İLAÇ REÇETE TAKİP MOTORU -->
                <div style="background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 8px;">
                    <h3 style="margin-top:0; color:#d9534f;">💊 İlaç Reçete Takip Motoru</h3>
                    <p style="font-size:13px; color:#666;">Sistemdeki ilaçlardan birini seçerek hangi hastaların bu ilacı aldığını ve hangi doktorların en çok yazdığını sorgulayın.</p>
                    <form method="GET" action="admin.php">
                        <input type="hidden" name="view" value="ilac_detay">
                        <label>İlaç Seçin:</label>
                        <select name="med_name" required>
                            <option value="">İlaç Listesinden Seçin...</option>
                            <?php foreach($mevcut_ilaclar as $ilac): ?>
                                <option value="<?php echo htmlspecialchars($ilac['medication']); ?>">
                                    💊 <?php echo htmlspecialchars($ilac['medication']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-red" style="width:100%; margin-top:10px; background:#d9534f;">İlaç Dağılımını Sorgula</button>
                    </form>
                </div>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border: 1px solid #ccc; border-radius: 8px;">
                <h3 style="margin-top:0;">💡 Sistem Raporlama Güncellemesi</h3>
                <p>Yönetim paneline **Merkezi Hasta Profil Entegrasyonu** başarıyla uygulandı:</p>
                <ul>
                    <li><b>Tam Entegre Tıbbi Dosya:</b> Sol taraftaki yeni mavi paneli kullanarak herhangi bir hastayı seçtiğinizde, sistem o hastanın sadece vizitlerini değil, laboratuvardaki **tüm kan şekeri, TSH ve İnsülin tahlil geçmişini** de yan yana iki tablo halinde listeler.</li>
                    <li><b>Kapsamlı Analiz Düzeni:</b> Yönetici artık hastane içerisindeki tüm tıbbi akışları (Hekim İstatistikleri, İlaç Dağılımları, Hasta Muayeneleri ve Laboratuvar Sonuçları) tek bir kontrol noktasından yönetebilir.</li>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
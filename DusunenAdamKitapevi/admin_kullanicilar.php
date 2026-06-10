<?php
require_once 'baglanti.php';
if(!isset($_SESSION['kullanici_id']) || $_SESSION['rol'] != 'admin') { die("Erişim engellendi!"); }

// Rol Değiştirme
if(isset($_GET['rol_yap']) && isset($_GET['id'])) {
    $yeni_rol = mysqli_real_escape_string($conn, $_GET['rol_yap']);
    $u_id = (int)$_GET['id'];
    mysqli_query($conn, "UPDATE kullanicilar SET rol = '$yeni_rol' WHERE id = $u_id");
    header("Location: admin_kullanicilar.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Kullanıcı Yönetimi</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px; margin: 0; }
        
        /* Admin Menüsü CSS */
        .admin-nav { background: #343a40; padding: 15px; margin-bottom: 25px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .admin-nav a { color: #fff; margin-right: 20px; text-decoration: none; font-weight: bold; font-size: 15px; }
        .admin-nav a:hover { color: #ffc107; }
        .cikis-link { float: right; color: #dc3545 !important; }

        /* Tablo CSS */
        .panel-bolum { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; color: #343a40; font-size: 22px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #f8f9fa; color: #495057; }
        .admin-label { background: #ffc107; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; color: #333;}
        .islem-link { text-decoration: none; padding: 5px 10px; border-radius: 3px; font-size: 13px; font-weight: bold;}
        .admin-yap { background: #28a745; color: white; }
        .yetki-al { background: #dc3545; color: white; }
    </style>
</head>
<body>

    <div class="admin-nav">
        <a href="admin.php">📊 Özet Paneli</a>
        <a href="admin_kitaplar.php">📚 Kitap Yönetimi</a>
        <a href="admin_siparisler.php">📦 Sipariş Yönetimi</a>
        <a href="admin_kullanicilar.php" style="color: #ffc107;">👥 Kullanıcı Yönetimi</a>
        <a href="index.php" style="color: #17a2b8;">🏠 Siteye Dön</a>
        <a href="cikis.php" class="cikis-link">🚪 Çıkış Yap</a>
    </div>

    <div class="panel-bolum">
        <h1>👥 Kullanıcı Yönetimi</h1>
        <table>
            <tr><th>Kullanıcı ID</th><th>Ad Soyad</th><th>Email</th><th>Sistem Rolü</th><th>Kayıtlı Adres</th><th>Yetki İşlemi</th></tr>
            <?php
            $kullanicilar = mysqli_query($conn, "SELECT * FROM kullanicilar ORDER BY id DESC");
            while($u = mysqli_fetch_assoc($kullanicilar)) {
                $rol_etiket = ($u['rol'] == 'admin') ? "<span class='admin-label'>YÖNETİCİ</span>" : "Kullanıcı";
                echo "<tr>
                    <td>#{$u['id']}</td>
                    <td><strong>{$u['ad_soyad']}</strong></td>
                    <td>{$u['email']}</td>
                    <td>$rol_etiket</td>
                    <td>".htmlspecialchars($u['adres'])."</td>
                    <td>";
                    if($u['rol'] == 'kullanici') {
                        echo "<a href='admin_kullanicilar.php?rol_yap=admin&id={$u['id']}' class='islem-link admin-yap'>Admin Yap</a>";
                    } else {
                        // Kendi yetkisini alamaması için ufak bir kontrol ekleyelim
                        if($u['id'] != $_SESSION['kullanici_id']) {
                            echo "<a href='admin_kullanicilar.php?rol_yap=kullanici&id={$u['id']}' class='islem-link yetki-al'>Yetki Al</a>";
                        } else {
                            echo "<span style='color:#ccc; font-size:12px;'>Kendi yetkiniz</span>";
                        }
                    }
                echo "</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
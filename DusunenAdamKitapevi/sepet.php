<?php
require_once 'baglanti.php';

// Oturum kontrolü
if(!isset($_SESSION['kullanici_id'])) {
    header("Location: giris.php");
    exit();
}

// Sepeti Başlat
if(!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = array();
}

// 1. ANA SAYFADAN SEPETE YENİ ÜRÜN EKLEME İŞLEMİ
if(isset($_POST['sepete_ekle'])) {
    $kitap_id = (int)$_POST['kitap_id'];
    $adet = (int)$_POST['adet'];
    
    // Eğer kitap zaten sepette varsa adedini artır, yoksa yeni ekle
    if(isset($_SESSION['sepet'][$kitap_id])) {
        $_SESSION['sepet'][$kitap_id] += $adet;
    } else {
        $_SESSION['sepet'][$kitap_id] = $adet;
    }
    header("Location: sepet.php");
    exit();
}

// 2. SEPET İÇİNDEKİ İŞLEMLER (Artır, Azalt, Sil)
if(isset($_GET['islem']) && isset($_GET['id'])) {
    $islem = $_GET['islem'];
    $islem_id = (int)$_GET['id'];

    if(isset($_SESSION['sepet'][$islem_id])) {
        if($islem == 'sil') {
            // Kitabı sepetten tamamen çıkar
            unset($_SESSION['sepet'][$islem_id]);
            
        } elseif($islem == 'artir') {
            // Stok sınırını aşmamak için önce stoğu kontrol edelim
            $stok_kontrol = mysqli_query($conn, "SELECT stok FROM kitaplar WHERE id = $islem_id");
            $stok_veri = mysqli_fetch_assoc($stok_kontrol);
            
            if($_SESSION['sepet'][$islem_id] < $stok_veri['stok']) {
                $_SESSION['sepet'][$islem_id]++;
            }
            
        } elseif($islem == 'azalt') {
            // Adet 1'den büyükse azalt, 1 ise komple sil
            if($_SESSION['sepet'][$islem_id] > 1) {
                $_SESSION['sepet'][$islem_id]--;
            } else {
                unset($_SESSION['sepet'][$islem_id]);
            }
        }
    }
    header("Location: sepet.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sepetim</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; padding:20px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #333; color: white; }
        .btn-islem { text-decoration: none; padding: 5px 10px; background-color: #e0e0e0; color: #333; border-radius: 3px; font-weight: bold; }
        .btn-islem:hover { background-color: #ccc; }
        .btn-sil { text-decoration: none; padding: 5px 10px; background-color: #dc3545; color: white; border-radius: 3px; }
        .btn-sil:hover { background-color: #c82333; }
        .onay-btn { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 3px; font-size: 16px; }
        .onay-btn:hover { background: #218838; }
        .nav-link { color: #007bff; text-decoration: none; font-size: 16px; }
    </style>
</head>
<body>
    <h2>Sepetiniz</h2>
    
    <?php if(!empty($_SESSION['sepet'])): ?>
        <table>
            <tr>
                <th>Kitap Adı</th>
                <th>Adet Yönetimi</th>
                <th>Birim Fiyat</th>
                <th>Toplam</th>
                <th>İşlem</th>
            </tr>
            <?php
            $genel_toplam = 0;
            
            // Sepetteki ürünleri döngüye al
            foreach($_SESSION['sepet'] as $id => $adet) {
                $sorgu = "SELECT kitap_adi, fiyat, stok FROM kitaplar WHERE id = $id";
                $sonuc = mysqli_query($conn, $sorgu);
                $kitap = mysqli_fetch_assoc($sonuc);
                
                // Eğer sepet adedi veritabanındaki stoktan fazlaysa (arkaplanda stok değişmişse) adedi stoğa eşitle
                if($adet > $kitap['stok']) {
                    $adet = $kitap['stok'];
                    $_SESSION['sepet'][$id] = $adet;
                }

                $toplam = $adet * $kitap['fiyat'];
                $genel_toplam += $toplam;
                
                echo "<tr>
                        <td>{$kitap['kitap_adi']}</td>
                        <td>
                            <a href='sepet.php?islem=azalt&id=$id' class='btn-islem'>-</a> 
                            <span style='margin: 0 10px; font-weight: bold;'>$adet</span> 
                            <a href='sepet.php?islem=artir&id=$id' class='btn-islem'>+</a>
                        </td>
                        <td>{$kitap['fiyat']} TL</td>
                        <td>$toplam TL</td>
                        <td>
                            <a href='sepet.php?islem=sil&id=$id' class='btn-sil'>Sepetten Sil</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
        
        <h3 style="text-align: right;">Genel Toplam: <?php echo number_format($genel_toplam, 2); ?> TL</h3>
        
        <form action="odeme.php" method="POST" style="background: #fff; padding: 20px; border: 1px solid #ddd;">
            <p><strong>Teslimat Adresi:</strong></p>
            <textarea name="adres" rows="4" style="width: 100%; max-width: 400px;" required placeholder="Lütfen açık adresinizi giriniz..."></textarea><br><br>
            <button type="submit" name="siparis_ver" class="onay-btn">Siparişi Onayla</button>
        </form>

    <?php else: ?>
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; text-align: center;">
            <p>Sepetinizde henüz ürün bulunmamaktadır.</p>
        </div>
    <?php endif; ?>
    
    <br>
    <a href="index.php" class="nav-link">← Alışverişe Dön</a>
</body>
</html>
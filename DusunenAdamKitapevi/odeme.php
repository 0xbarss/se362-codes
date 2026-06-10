<?php
require_once 'baglanti.php';

if(!isset($_SESSION['kullanici_id']) || !isset($_POST['siparis_ver']) || empty($_SESSION['sepet'])) {
    header("Location: index.php");
    exit();
}

$kullanici_id = $_SESSION['kullanici_id'];
$adres = mysqli_real_escape_string($conn, $_POST['adres']);
$genel_toplam = 0;

// Toplam tutarı hesapla
foreach($_SESSION['sepet'] as $id => $adet) {
    $sonuc = mysqli_query($conn, "SELECT fiyat FROM kitaplar WHERE id = $id");
    $kitap = mysqli_fetch_assoc($sonuc);
    $genel_toplam += ($kitap['fiyat'] * $adet);
}

// 1. Siparişler tablosuna ana kaydı at
$siparis_sorgu = "INSERT INTO siparisler (kullanici_id, toplam_tutar, teslimat_adresi) VALUES ('$kullanici_id', '$genel_toplam', '$adres')";
if(mysqli_query($conn, $siparis_sorgu)) {
    $siparis_id = mysqli_insert_id($conn); // Oluşan Sipariş IDsini al

    // 2. Sepetteki her ürünü Sipariş Detaylarına (O GÜNKÜ FİYATLA) ekle ve stoğu düş
    foreach($_SESSION['sepet'] as $id => $adet) {
        $sonuc = mysqli_query($conn, "SELECT fiyat FROM kitaplar WHERE id = $id");
        $kitap = mysqli_fetch_assoc($sonuc);
        $o_gunku_fiyat = $kitap['fiyat'];

        // Detay tablosuna ekle
        $detay_sorgu = "INSERT INTO siparis_detaylari (siparis_id, kitap_id, adet, alinan_fiyat) VALUES ('$siparis_id', '$id', '$adet', '$o_gunku_fiyat')";
        mysqli_query($conn, $detay_sorgu);


		// Stoğu düşür ve SATILAN ADEDİ ARTIR
mysqli_query($conn, "UPDATE kitaplar SET stok = stok - $adet, satilan_adet = satilan_adet + $adet WHERE id = $id");
    }

    // İşlem bittiyse sepeti boşalt
    unset($_SESSION['sepet']);
    echo "<h1>Siparişiniz Başarıyla Alındı!</h1>";
    echo "<a href='index.php'>Ana Sayfaya Dön</a>";
} else {
    echo "Sipariş oluşturulurken bir hata oluştu.";
}
?>
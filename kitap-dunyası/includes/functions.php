<?php
require_once 'config.php';

function getKitaplar($search = null, $kategori = null) {
    global $db;
    
    $sql = "SELECT * FROM kitaplar WHERE stok > 0";
    $params = [];
    
    if ($search) {
        $sql .= " AND (baslik LIKE ? OR yazar LIKE ? OR aciklama LIKE ?)";
        $searchTerm = "%$search%";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    if ($kategori) {
        $sql .= " AND kategori = ?";
        $params[] = $kategori;
    }
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getKitapById($id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM kitaplar WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getKullaniciSiparisler($kullanici_id) {
    global $db;
    $stmt = $db->prepare("SELECT * FROM siparisler WHERE kullanici_id = ? ORDER BY siparis_tarihi DESC");
    $stmt->execute([$kullanici_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSiparisDetay($siparis_id) {
    global $db;
    $stmt = $db->prepare("SELECT su.*, k.baslik, k.kapak_resmi 
                         FROM siparis_urunleri su 
                         JOIN kitaplar k ON su.kitap_id = k.id 
                         WHERE su.siparis_id = ?");
    $stmt->execute([$siparis_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function sepeteEkle($kitap_id, $miktar = 1) {
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = array();
    }
    
    if (isset($_SESSION['sepet'][$kitap_id])) {
        $_SESSION['sepet'][$kitap_id] += $miktar;
    } else {
        $_SESSION['sepet'][$kitap_id] = $miktar;
    }
}

function sepetiGetir() {
    if (!isset($_SESSION['sepet'])) {
        return array();
    }
    
    $sepet = array();
    foreach ($_SESSION['sepet'] as $kitap_id => $miktar) {
        $kitap = getKitapById($kitap_id);
        if ($kitap) {
            $kitap['miktar'] = $miktar;
            $sepet[] = $kitap;
        }
    }
    
    return $sepet;
}

function sepetToplam() {
    $sepet = sepetiGetir();
    $toplam = 0;
    
    foreach ($sepet as $item) {
        $toplam += $item['fiyat'] * $item['miktar'];
    }
    
    return $toplam;
}

function sepettenCikar($kitap_id) {
    if (isset($_SESSION['sepet'][$kitap_id])) {
        unset($_SESSION['sepet'][$kitap_id]);
    }
}

function sepetiTemizle() {
    unset($_SESSION['sepet']);
}
?>
<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('HTTP/1.0 400 Bad Request');
    exit;
}

$stmt = $db->prepare("SELECT id, kullanici_adi, email, ad_soyad, admin FROM kullanicilar WHERE id = ?");
$stmt->execute([$_GET['id']]);
$kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kullanici) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

header('Content-Type: application/json');
echo json_encode($kullanici);
?>
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

$kitap = getKitapById($_GET['id']);

if (!$kitap) {
    header('HTTP/1.0 404 Not Found');
    exit;
}

header('Content-Type: application/json');
echo json_encode($kitap);
?>
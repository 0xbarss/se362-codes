<?php
$host = "localhost"; $user = "root"; $pass = ""; $db = "endokrin_klinik";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { die("Bağlantı Hatası: " . $e->getMessage()); }
?>
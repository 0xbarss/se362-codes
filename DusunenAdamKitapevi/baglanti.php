<?php
session_start();
$host = "localhost";
$kullanici = "root";
$sifre = "";
$db_adi = "books";

$conn = mysqli_connect($host, $kullanici, $sifre, $db_adi);

// Türkçe karakter sorunu olmaması için
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Veritabanı bağlantı hatası: " . mysqli_connect_error());
}
?>
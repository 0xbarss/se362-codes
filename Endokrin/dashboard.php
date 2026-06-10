<?php 
include 'db.php';
session_start();

// Giriş yapılmamışsa login sayfasına gönder
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role_id'];

// ROL BAZLI OTOMATİK YÖNLENDİRME
switch ($role) {
    case 1: // Yönetici
        header("Location: admin.php");
        break;
    case 2: // Doktor
    case 3: // Hemşire
        header("Location: doktor_hemsire.php");
        break;
    case 4: // Resepsiyonist
        header("Location: resepsiyon.php");
        break;
    case 5: // Lab Teknisyeni
        header("Location: lab_panel.php"); // Eğer bu sayfayı yapmadıysan oluşturmalısın
        break;
    case 6: // Hasta
        header("Location: hasta_paneli.php");
        break;
    default:
        echo "Tanımlanmamış bir rol ile giriş yapıldı.";
        break;
}
exit;
?>
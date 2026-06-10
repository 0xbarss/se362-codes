<?php
session_start();

// Kullanıcı zaten giriş yapmışsa, rolüne göre paneline gönder
if (isset($_SESSION['role_id'])) { 
    header("Location: dashboard.php");
    exit;
}

// Giriş yapmamışsa direkt login.php'ye fırlat
header("Location: login.php");
exit;
?>
<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../giris.php');
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Kitap Dünyası</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="logo">
                <h1><a href="index.php">Yönetim Paneli</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Anasayfa</a></li>
                    <li><a href="kitaplar.php">Kitaplar</a></li>
                    <li><a href="siparisler.php">Siparişler</a></li>
                    <li><a href="kullanicilar.php">Kullanıcılar</a></li>
                    <li><a href="../index.php">Siteye Dön</a></li>
                    <li><a href="../cikis.php">Çıkış</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
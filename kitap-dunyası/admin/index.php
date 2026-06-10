<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    redirect('../giris.php');
}

require_once 'header.php';
?>

<h2>Yönetim Paneli</h2>

<div class="admin-menu">
    <ul>
        <li><a href="kitaplar.php">Kitap Yönetimi</a></li>
        <li><a href="siparisler.php">Sipariş Yönetimi</a></li>
        <li><a href="kullanicilar.php">Kullanıcı Yönetimi</a></li>
    </ul>
</div>

<?php
require_once 'footer.php';
?>
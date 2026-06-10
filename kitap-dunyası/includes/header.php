<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitap Dünyası</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="logo">
                <h1><a href="index.php">Kitap Dünyası</a></h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Anasayfa</a></li>
                    <li><a href="kitaplar.php">Kitaplar</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="hesabim.php">Hesabım</a></li>
                        <li><a href="sepet.php">Sepet</a></li>
                        <?php if (isAdmin()): ?>
                            <li><a href="admin/">Yönetim</a></li>
                        <?php endif; ?>
                        <li><a href="cikis.php">Çıkış</a></li>
                    <?php else: ?>
                        <li><a href="giris.php">Giriş</a></li>
                        <li><a href="kayit.php">Kayıt</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div id="search-box">
            <form action="kitaplar.php" method="get">
                <input type="text" name="search" placeholder="Kitap adı, yazar veya konu ara...">
                <button type="submit">Ara</button>
            </form>
        </div>
    </div>

    <main class="container">
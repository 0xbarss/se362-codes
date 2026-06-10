<?php session_start(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 1000px; margin: auto; }
        nav { background: #333; padding: 10px; color: white; margin-bottom: 20px; border-radius: 5px; }
        nav a { color: white; text-decoration: none; margin-right: 15px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; }
        .btn-blue { background: #007bff; color: white; }
        .btn-red { background: #dc3545; color: white; }
        .btn-green { background: #28a745; color: white; }
        input, select, textarea { width: 100%; padding: 8px; margin: 5px 0 15px 0; border: 1px solid #ccc; box-sizing: border-box; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    </style>
</head>
<body>
<?php if(isset($_SESSION['user_id'])): ?>
    <nav>
        <a href="dashboard.php">Ana Sayfa</a>
        <span>| Giriş Yapan: <?php echo $_SESSION['full_name']; ?></span>
        <a href="logout.php" style="float:right">Güvenli Çıkış</a>
    </nav>
<?php endif; ?>
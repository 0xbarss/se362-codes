-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 20 Nis 2026, 10:45:32
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `kitapci_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`) VALUES
(1, 'Roman'),
(2, 'Tarih'),
(3, 'Bilim'),
(4, 'Felsefe');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kitaplar`
--

CREATE TABLE `kitaplar` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `baslik` varchar(255) NOT NULL,
  `fiyat` decimal(10,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kitaplar`
--

INSERT INTO `kitaplar` (`id`, `kategori_id`, `baslik`, `fiyat`, `stok`) VALUES
(1, 1, 'Sefiller', 150.00, 10),
(2, 1, 'Suç ve Ceza', 120.00, 7),
(3, 2, 'Nutuk', 200.00, 18),
(4, 3, 'Kozmos', 250.00, 6),
(5, 4, 'Devlet', 90.00, 11),
(6, 1, 'Beyaz Kale ', 200.00, 20);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `rol` enum('admin','musteri') DEFAULT 'musteri'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `sifre`, `rol`) VALUES
(1, 'admin', '1234', 'admin'),
(2, 'ahmet', '1234', 'musteri'),
(3, 'mehmet_yilmaz', '1234', 'musteri'),
(4, 'zeynep_kaya', '1234', 'musteri'),
(5, 'can_demir', '1234', 'musteri'),
(6, 'elif_sahin', '1234', 'musteri'),
(7, 'admin_murat', '1234', 'admin'),
(8, 'ayse_bulut', '1234', 'musteri'),
(9, 'mert_ozkan', '1234', 'musteri'),
(10, 'sibel_aksoy', '1234', 'musteri'),
(11, 'kerem_aslan', '1234', 'musteri'),
(12, 'admin_ebru', '1234', 'admin'),
(13, 'user1', '1234', 'musteri'),
(14, 'user2', '1234', 'musteri'),
(15, 'user3', '1234', 'musteri'),
(16, 'user4', '1234', 'musteri'),
(17, 'user5', '1234', 'musteri'),
(18, 'user6', '1234', 'musteri'),
(19, 'user7', '1234', 'musteri'),
(20, 'user8', '1234', 'musteri'),
(21, 'user9', '1234', 'musteri'),
(22, 'user10', '1234', 'musteri'),
(23, 'user11', '1234', 'musteri'),
(24, 'user12', '1234', 'musteri'),
(25, 'user13', '1234', 'musteri'),
(26, 'user14', '1234', 'musteri'),
(27, 'user15', '1234', 'musteri'),
(28, 'user16', '1234', 'musteri'),
(29, 'user17', '1234', 'musteri'),
(30, 'user18', '1234', 'musteri'),
(31, 'user19', '1234', 'musteri'),
(32, 'user20', '1234', 'musteri'),
(33, 'user21', '1234', 'musteri'),
(34, 'user22', '1234', 'musteri'),
(35, 'user23', '1234', 'musteri'),
(36, 'user24', '1234', 'musteri'),
(37, 'user25', '1234', 'musteri'),
(38, 'user26', '1234', 'musteri'),
(39, 'user27', '1234', 'musteri'),
(40, 'user28', '1234', 'musteri'),
(41, 'user29', '1234', 'musteri'),
(42, 'user30', '1234', 'musteri'),
(43, 'user31', '1234', 'musteri'),
(44, 'user32', '1234', 'musteri'),
(45, 'user33', '1234', 'musteri'),
(46, 'user34', '1234', 'musteri'),
(47, 'user35', '1234', 'musteri'),
(48, 'user36', '1234', 'musteri'),
(49, 'user37', '1234', 'musteri'),
(50, 'user38', '1234', 'musteri'),
(51, 'user39', '1234', 'musteri'),
(52, 'user40', '1234', 'musteri');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `kitap_id` int(11) DEFAULT NULL,
  `adet` int(11) DEFAULT NULL,
  `toplam_fiyat` decimal(10,2) DEFAULT NULL,
  `durum` enum('Hazırlanıyor','Kargoya Verildi','Teslim Edildi') DEFAULT 'Hazırlanıyor',
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`id`, `kullanici_id`, `kitap_id`, `adet`, `toplam_fiyat`, `durum`, `tarih`) VALUES
(1, 2, 2, 1, 120.00, 'Kargoya Verildi', '2026-04-20 08:22:52'),
(2, 2, 3, 1, 200.00, 'Teslim Edildi', '2026-04-20 08:22:53'),
(3, 2, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-20 08:22:54'),
(4, 2, 5, 1, 90.00, 'Hazırlanıyor', '2026-04-20 08:22:55'),
(5, 1, 3, NULL, 200.00, 'Hazırlanıyor', '2026-04-20 08:34:06'),
(6, 2, 1, 1, 150.00, 'Teslim Edildi', '2026-04-01 07:00:00'),
(7, 3, 2, 1, 120.00, 'Teslim Edildi', '2026-04-02 08:30:00'),
(8, 4, 3, 1, 200.00, 'Kargoya Verildi', '2026-04-05 06:15:00'),
(9, 6, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-10 11:20:00'),
(10, 7, 1, 1, 150.00, 'Hazırlanıyor', '2026-04-12 13:45:00'),
(11, 8, 2, 1, 120.00, 'Kargoya Verildi', '2026-04-13 07:10:00'),
(12, 9, 5, 1, 90.00, 'Teslim Edildi', '2026-04-14 09:00:00'),
(13, 11, 3, 1, 200.00, 'Hazırlanıyor', '2026-04-15 05:30:00'),
(14, 12, 4, 1, 250.00, 'Kargoya Verildi', '2026-04-16 14:00:00'),
(15, 13, 2, 1, 120.00, 'Hazırlanıyor', '2026-04-17 08:11:00'),
(16, 14, 1, 1, 150.00, 'Teslim Edildi', '2026-04-18 06:00:00'),
(17, 15, 5, 1, 90.00, 'Kargoya Verildi', '2026-04-19 10:25:00'),
(18, 16, 3, 1, 200.00, 'Hazırlanıyor', '2026-04-20 07:05:00'),
(19, 17, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-20 08:30:00'),
(20, 18, 1, 1, 150.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(21, 19, 2, 1, 120.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(22, 20, 3, 1, 200.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(23, 21, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(24, 22, 5, 1, 90.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(25, 23, 1, 1, 150.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(26, 24, 2, 1, 120.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(27, 25, 3, 1, 200.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(28, 26, 4, 1, 250.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(29, 27, 5, 1, 90.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(30, 28, 1, 1, 150.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(31, 29, 2, 1, 120.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(32, 30, 3, 1, 200.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(33, 31, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(34, 32, 5, 1, 90.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(35, 33, 1, 1, 150.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(36, 34, 2, 1, 120.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(37, 35, 3, 1, 200.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(38, 36, 4, 1, 250.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(39, 37, 5, 1, 90.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(40, 38, 1, 1, 150.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(41, 39, 2, 1, 120.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(42, 40, 3, 1, 200.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(43, 41, 4, 1, 250.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(44, 42, 5, 1, 90.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(45, 43, 1, 1, 150.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(46, 44, 2, 1, 120.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(47, 45, 3, 1, 200.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(48, 46, 4, 1, 250.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(49, 47, 5, 1, 90.00, 'Kargoya Verildi', '2026-04-20 08:43:21'),
(50, 48, 1, 1, 150.00, 'Teslim Edildi', '2026-04-20 08:43:21'),
(51, 49, 2, 1, 120.00, 'Hazırlanıyor', '2026-04-20 08:43:21'),
(52, 50, 3, 1, 200.00, 'Kargoya Verildi', '2026-04-20 08:43:21');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `kitaplar`
--
ALTER TABLE `kitaplar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Tablo için AUTO_INCREMENT değeri `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD CONSTRAINT `kitaplar_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`);

--
-- Tablo kısıtlamaları `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`),
  ADD CONSTRAINT `siparisler_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

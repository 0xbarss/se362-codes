-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 04:13 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `books`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategoriler`
--

CREATE TABLE `kategoriler` (
  `id` int(11) NOT NULL,
  `kategori_adi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategoriler`
--

INSERT INTO `kategoriler` (`id`, `kategori_adi`) VALUES
(1, 'Roman'),
(2, 'Bilim Kurgu'),
(3, 'Tarih'),
(4, 'Teknoloji ve Yazılım'),
(5, 'Mimari'),
(6, 'Spor');

-- --------------------------------------------------------

--
-- Table structure for table `kitaplar`
--

CREATE TABLE `kitaplar` (
  `id` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `kitap_adi` varchar(255) NOT NULL,
  `yazar` varchar(150) DEFAULT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `stok` int(11) DEFAULT 0,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `resim` varchar(255) DEFAULT 'varsayilan.jpg',
  `satilan_adet` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kitaplar`
--

INSERT INTO `kitaplar` (`id`, `kategori_id`, `kitap_adi`, `yazar`, `fiyat`, `stok`, `eklenme_tarihi`, `resim`, `satilan_adet`) VALUES
(1, 1, 'Suç ve Ceza', 'Dostoyevski', 150.00, 0, '2026-04-26 11:17:49', 'varsayilan.jpg', 19),
(2, 2, 'Dune', 'Frank Herbert', 200.00, 0, '2026-04-26 11:17:49', 'varsayilan.jpg', 22),
(3, 3, 'Nutuk', 'Mustafa Kemal Atatürk', 120.00, 0, '2026-04-26 11:17:49', 'varsayilan.jpg', 55),
(4, 1, 'Yüzüklerin Efendisi', 'J.R.R. Tolkien', 350.00, 25, '2026-04-26 11:21:14', 'varsayilan.jpg', 54),
(5, 1, '1984', 'George Orwell', 125.50, 38, '2026-04-26 11:21:14', 'varsayilan.jpg', 33),
(6, 1, 'Simyacı', 'Paulo Coelho', 110.00, 15, '2026-04-26 11:21:14', 'varsayilan.jpg', 1),
(7, 1, 'Sefiller', 'Victor Hugo', 220.00, 6, '2026-04-26 11:21:14', 'varsayilan.jpg', 56),
(8, 1, 'Körlük', 'José Saramago', 145.00, 12, '2026-04-26 11:21:14', 'varsayilan.jpg', 53),
(9, 1, 'İki Şehrin Hikayesi', 'Charles Dickens', 130.00, 20, '2026-04-26 11:21:14', 'varsayilan.jpg', 19),
(10, 1, 'Bülbülü Öldürmek', 'Harper Lee', 140.00, 18, '2026-04-26 11:21:14', 'varsayilan.jpg', 10),
(11, 1, 'Savaş ve Barış', 'Lev Tolstoy', 280.00, 2, '2026-04-26 11:21:14', 'varsayilan.jpg', 68),
(12, 2, 'Vakıf', 'Isaac Asimov', 190.00, 27, '2026-04-26 11:21:14', 'varsayilan.jpg', 6),
(13, 2, 'Cesur Yeni Dünya', 'Aldous Huxley', 135.00, 18, '2026-04-26 11:21:14', 'varsayilan.jpg', 53),
(14, 2, 'Fahrenheit 451', 'Ray Bradbury', 120.00, 14, '2026-04-26 11:21:14', 'varsayilan.jpg', 22),
(15, 3, 'Türklerin Tarihi', 'İlber Ortaylı', 175.00, 50, '2026-04-26 11:21:14', 'varsayilan.jpg', 26),
(16, 3, 'Osmanlı İmparatorluğu Klasik Çağ', 'Halil İnalcık', 210.00, 10, '2026-04-26 11:21:14', 'varsayilan.jpg', 61),
(17, 4, 'PHP 8 ve Modern Web Geliştirme', 'Ahmet Vatansever', 245.00, 12, '2026-04-26 11:21:14', 'varsayilan.jpg', 3),
(18, 4, 'Yazılım Proje Yönetimi ve Çevik Yöntemler', 'Mehmet Yılmaz', 195.00, 8, '2026-04-26 11:21:14', 'varsayilan.jpg', 57),
(19, 4, 'Geleceğin Otomobilleri: Elektrikli Araçlar ve Bataryalar', 'Dr. Caner Tekin', 310.00, 9, '2026-04-26 11:21:14', 'varsayilan.jpg', 51),
(20, 5, 'Geleneksel Osmanlı Evleri ve Türk İmar Sanatı', 'Sedat Hakkı Eldem', 450.00, 4, '2026-04-26 11:21:14', 'varsayilan.jpg', 8),
(21, 5, 'Modern Mimarinin Temelleri', 'Le Corbusier', 275.00, 6, '2026-04-26 11:21:14', 'varsayilan.jpg', 38),
(22, 6, 'Süper Lig Tarihi ve Unutulmaz Maçlar', 'Tuğrul Akşar', 160.00, 19, '2026-04-26 11:21:14', 'varsayilan.jpg', 9),
(23, 6, 'Futbol Taktikleri Tarihi', 'Jonathan Wilson', 185.00, 11, '2026-04-26 11:21:14', 'varsayilan.jpg', 8),
(24, 5, 'Istanbul Evleri', 'Berfin Yıldız', 200.00, 24, '2026-04-26 12:39:19', 'varsayilan.jpg', 1),
(25, 6, 'Pele: Hayatım', 'Pele', 100.00, 10, '2026-04-26 13:10:28', 'varsayilan.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `adres` text DEFAULT NULL,
  `rol` enum('kullanici','admin') DEFAULT 'kullanici'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `email`, `sifre`, `adres`, `rol`) VALUES
(1, 'Admin Bey', 'admin@site.com', '123456', NULL, 'admin'),
(2, 'tengiz han', 'than@site.com.tr', 'than123', 'Karşıyaka İzmir Türkiye', 'kullanici'),
(3, 'user tam', 'user@site.com', '456user', 'Bornova İzmir', 'kullanici'),
(4, 'ton ton', 'tonton@site.com.tr', 'site123', 'asdfgh', 'kullanici');

-- --------------------------------------------------------

--
-- Table structure for table `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `toplam_tutar` decimal(10,2) NOT NULL,
  `teslimat_adresi` text NOT NULL,
  `durum` enum('Hazırlanıyor','Kargoya Verildi','Teslim Edildi','İptal Edildi') DEFAULT 'Hazırlanıyor',
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siparisler`
--

INSERT INTO `siparisler` (`id`, `kullanici_id`, `toplam_tutar`, `teslimat_adresi`, `durum`, `tarih`) VALUES
(1, 3, 140.00, 'efergferfef', 'İptal Edildi', '2026-04-26 11:32:00'),
(2, 3, 821.00, 'rggrgthtjh', 'Teslim Edildi', '2026-04-26 11:37:32'),
(3, 3, 1435.00, 'Bostanlı Kusu', 'Kargoya Verildi', '2026-04-26 11:40:21'),
(4, 4, 1930.00, 'aslanabs', 'Hazırlanıyor', '2026-04-26 12:02:16'),
(5, 2, 280.00, 'gktıejdj', 'Kargoya Verildi', '2026-04-26 12:30:49'),
(6, 4, 275.00, 'xsdscdd', 'Hazırlanıyor', '2026-04-26 13:06:04'),
(7, 4, 120.00, 'zzzzz', 'Teslim Edildi', '2026-04-26 13:11:12'),
(8, 4, 120.00, 'dfggggg', 'Hazırlanıyor', '2026-04-26 13:13:07');

-- --------------------------------------------------------

--
-- Table structure for table `siparis_detaylari`
--

CREATE TABLE `siparis_detaylari` (
  `id` int(11) NOT NULL,
  `siparis_id` int(11) DEFAULT NULL,
  `kitap_id` int(11) DEFAULT NULL,
  `adet` int(11) NOT NULL,
  `alinan_fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siparis_detaylari`
--

INSERT INTO `siparis_detaylari` (`id`, `siparis_id`, `kitap_id`, `adet`, `alinan_fiyat`) VALUES
(1, 1, 10, 1, 140.00),
(2, 2, 5, 2, 125.50),
(3, 2, 12, 3, 190.00),
(4, 3, 22, 1, 160.00),
(5, 3, 13, 4, 135.00),
(6, 3, 17, 3, 245.00),
(7, 4, 7, 2, 220.00),
(8, 4, 19, 3, 310.00),
(9, 4, 11, 2, 280.00),
(10, 5, 11, 1, 280.00),
(11, 6, 21, 1, 275.00),
(12, 7, 3, 1, 120.00),
(13, 8, 3, 1, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `stok_bildirim`
--

CREATE TABLE `stok_bildirim` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `tarih` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategoriler`
--
ALTER TABLE `kategoriler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Indexes for table `siparis_detaylari`
--
ALTER TABLE `siparis_detaylari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siparis_id` (`siparis_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- Indexes for table `stok_bildirim`
--
ALTER TABLE `stok_bildirim`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_id` (`kullanici_id`,`kitap_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategoriler`
--
ALTER TABLE `kategoriler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kitaplar`
--
ALTER TABLE `kitaplar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `siparis_detaylari`
--
ALTER TABLE `siparis_detaylari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stok_bildirim`
--
ALTER TABLE `stok_bildirim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD CONSTRAINT `kitaplar_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategoriler` (`id`);

--
-- Constraints for table `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`);

--
-- Constraints for table `siparis_detaylari`
--
ALTER TABLE `siparis_detaylari`
  ADD CONSTRAINT `siparis_detaylari_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`),
  ADD CONSTRAINT `siparis_detaylari_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`);

--
-- Constraints for table `stok_bildirim`
--
ALTER TABLE `stok_bildirim`
  ADD CONSTRAINT `stok_bildirim_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`),
  ADD CONSTRAINT `stok_bildirim_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

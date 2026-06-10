-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 11:18 AM
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
-- Database: `kitap_dunyasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `kitaplar`
--

CREATE TABLE `kitaplar` (
  `id` int(11) NOT NULL,
  `baslik` varchar(255) NOT NULL,
  `yazar` varchar(255) NOT NULL,
  `aciklama` text DEFAULT NULL,
  `fiyat` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `kapak_resmi` varchar(255) DEFAULT NULL,
  `eklenme_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kitaplar`
--

INSERT INTO `kitaplar` (`id`, `baslik`, `yazar`, `aciklama`, `fiyat`, `stok`, `kategori`, `kapak_resmi`, `eklenme_tarihi`) VALUES
(1, 'Kürk Mantolu Madonna', 'Sabahattin Ali', 'Türk edebiyatının en önemli eserlerinden biri olan Kürk Mantolu Madonna, aşkın ve yalnızlığın derinliklerine inen bir başyapıt.', 29.90, 50, 'Klasik Türk Edebiyatı', 'kurk_mantolu_madonna.jpg', '2025-05-12 02:33:43'),
(2, 'İnce Memed', 'Yaşar Kemal', 'Yaşar Kemal\'in Çukurova\'nın eşkıya destanını anlattığı dörtlemenin ilk kitabı.', 35.50, 29, 'Roman', 'ince_memed.jpg', '2025-05-12 02:33:43'),
(3, 'Simyacı', 'Paulo Coelho', 'Kişisel keşif ve kader üzerine yazılmış modern bir klasik.', 42.00, 74, 'Kişisel Gelişim', 'simyaci.jpg', '2025-05-12 02:33:43'),
(4, 'Şeker Portakalı', 'José Mauro de Vasconcelos', 'Brezilya edebiyatının unutulmaz eseri, Zezé\'nin dokunaklı hikayesi.', 27.90, 40, 'Dünya Klasikleri', 'seker_portakali.jpg', '2025-05-12 02:33:43'),
(5, 'Kuyucaklı Yusuf', 'Sabahattin Ali', 'Anadolu\'nun küçük bir kasabasında geçen trajik bir aşk hikayesi.', 24.90, 35, 'Klasik Türk Edebiyatı', 'kuyucakli_yusuf.jpg', '2025-05-12 02:33:43'),
(6, 'Fahrenheit 451', 'Ray Bradbury', 'Distopik bir gelecekte kitapların yasak olduğu bir dünyayı anlatan bilim kurgu klasiği.', 38.50, 60, 'Bilim Kurgu', 'fahrenheit_451.jpg', '2025-05-12 02:33:43'),
(7, 'Hayvan Çiftliği', 'George Orwell', 'Totalitarizmi eleştiren politik bir fabl.', 22.90, 55, 'Dünya Klasikleri', 'hayvan_ciftligi.jpg', '2025-05-12 02:33:43'),
(8, 'Uçurtma Avcısı', 'Khaled Hosseini', 'Afganistan\'da geçen dostluk, ihanet ve kefaret hikayesi.', 45.00, 25, 'Roman', 'ucurtma_avcisi.jpg', '2025-05-12 02:33:43'),
(9, 'Küçük Prens', 'Antoine de Saint-Exupéry', 'Tüm zamanların en çok okunan kitaplarından biri.', 19.90, 100, 'Çocuk', 'kucuk_prens.jpg', '2025-05-12 02:33:43'),
(10, 'Suç ve Ceza', 'Fyodor Dostoyevski', 'Rus edebiyatının başyapıtlarından biri.', 39.90, 45, 'Dünya Klasikleri', 'suc_ve_ceza.jpg', '2025-05-12 02:33:43'),
(11, 'Beyaz Diş', 'Jack London', 'Kuzeyin vahşi doğasında geçen bir hayatta kalma hikayesi.', 31.50, 30, 'Macera', 'beyaz_dis.jpg', '2025-05-12 02:33:43'),
(12, 'Martı', 'Richard Bach', 'Kişisel özgürlük ve kendini aşma üzerine bir hikaye.', 26.90, 50, 'Kişisel Gelişim', 'marti.jpg', '2025-05-12 02:33:43'),
(13, 'Satranç', 'Stefan Zweig', 'Bir satranç ustasının psikolojik portresi.', 21.90, 65, 'Novella', 'satranc.jpg', '2025-05-12 02:33:43'),
(14, 'Dönüşüm', 'Franz Kafka', 'Gregor Samsa\'nın bir sabah kendini dev bir böceğe dönüşmüş bulmasıyla başlayan hikaye.', 23.50, 70, 'Dünya Klasikleri', 'donusum.jpg', '2025-05-12 02:33:43'),
(15, 'Sefiller', 'Victor Hugo', 'Fransız edebiyatının en önemli eserlerinden biri.', 49.90, 20, 'Dünya Klasikleri', 'sefiller.jpg', '2025-05-12 02:33:43'),
(16, 'Bülbülü Öldürmek', 'Harper Lee', 'Irkçılık ve adaletsizlik üzerine bir Amerikan klasiği.', 37.90, 35, 'Roman', 'bulbulu_oldurmek.jpg', '2025-05-12 02:33:43'),
(17, '1984', 'George Orwell', 'Distopik bir gelecekte totaliter bir rejim altında yaşam.', 34.90, 60, 'Bilim Kurgu', '1984.jpg', '2025-05-12 02:33:43'),
(18, 'Yabancı', 'Albert Camus', 'Varoluşçuluk akımının önemli eserlerinden biri.', 28.90, 45, 'Dünya Klasikleri', 'yabancı.jpg', '2025-05-12 02:33:43'),
(19, 'Dokuzuncu Hariciye Koğuşu', 'Peyami Safa', 'Bir gencin hastalıkla mücadelesini anlatan roman.', 26.50, 40, 'Klasik Türk Edebiyatı', 'dokuzuncu_hariciye.jpg', '2025-05-12 02:33:43'),
(20, 'Serenad', 'Zülfü Livaneli', 'İkinci Dünya Savaşı döneminde geçen bir aşk hikayesi.', 32.90, 30, 'Roman', 'serenad.jpg', '2025-05-12 02:33:43'),
(21, 'Yaban', 'yakup Kadri Karaosmanoğlu', 'Anadolunun durumu', 200.00, 50, 'Roman', '682140ee41373.jpg', '2025-05-12 03:28:36');

-- --------------------------------------------------------

--
-- Table structure for table `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `kullanici_adi` varchar(50) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ad_soyad` varchar(100) NOT NULL,
  `adres` text DEFAULT NULL,
  `telefon` varchar(20) DEFAULT NULL,
  `kayit_tarihi` datetime DEFAULT current_timestamp(),
  `admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `kullanici_adi`, `sifre`, `email`, `ad_soyad`, `adres`, `telefon`, `kayit_tarihi`, `admin`) VALUES
(1, 'ahmet_yilmaz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmet@example.com', 'Ahmet Yılmaz', 'Atatürk Bulvarı No:45, Çankaya, Ankara', '05551234567', '2025-05-12 02:32:42', 0),
(2, 'ayse_kaya', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ayse@example.com', 'Ayşe Kaya', 'Bağdat Caddesi No:123, Kadıköy, İstanbul', '05552345678', '2025-05-12 02:32:42', 0),
(3, 'mehmet_demir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mehmet@example.com', 'Mehmet Demir', 'Kızılay Sokak No:5, Konak, İzmir', '05553456789', '2025-05-12 02:32:42', 0),
(4, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@kitapdunyasi.com', 'Site Yöneticisi', 'İş Merkezi Kat:5, Levent, İstanbul', '05550000001', '2025-05-12 02:32:42', 1),
(5, 'mbora', '$2y$10$UM4VCO/9PH1QrXTpmYQ6te/YXJlPaJOG9xT15kPfFablEpLEMWgru', 'mbora@hot.com', 'mehmet bora', 'Bornova Izmir', '234555', '2025-05-12 03:42:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `siparisler`
--

CREATE TABLE `siparisler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) NOT NULL,
  `siparis_tarihi` datetime DEFAULT current_timestamp(),
  `toplam_fiyat` decimal(10,2) NOT NULL,
  `durum` enum('beklemede','hazırlanıyor','kargoda','teslim edildi') DEFAULT 'beklemede',
  `adres` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siparisler`
--

INSERT INTO `siparisler` (`id`, `kullanici_id`, `siparis_tarihi`, `toplam_fiyat`, `durum`, `adres`) VALUES
(1, 1, '2023-05-15 14:30:22', 97.30, 'teslim edildi', 'Atatürk Bulvarı No:45, Çankaya, Ankara'),
(2, 2, '2023-06-02 10:15:45', 62.80, 'kargoda', 'Bağdat Caddesi No:123, Kadıköy, İstanbul'),
(3, 1, '2023-06-10 18:45:12', 45.00, 'hazırlanıyor', 'Atatürk Bulvarı No:45, Çankaya, Ankara'),
(4, 3, '2023-06-15 09:30:00', 129.70, 'teslim edildi', 'Kızılay Sokak No:5, Konak, İzmir'),
(5, 2, '2023-06-20 16:20:33', 84.40, 'beklemede', 'Bağdat Caddesi No:123, Kadıköy, İstanbul'),
(6, 5, '2025-05-12 03:45:00', 77.50, 'beklemede', 'Bornova Izmir');

-- --------------------------------------------------------

--
-- Table structure for table `siparis_urunleri`
--

CREATE TABLE `siparis_urunleri` (
  `id` int(11) NOT NULL,
  `siparis_id` int(11) NOT NULL,
  `kitap_id` int(11) NOT NULL,
  `miktar` int(11) NOT NULL,
  `birim_fiyat` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siparis_urunleri`
--

INSERT INTO `siparis_urunleri` (`id`, `siparis_id`, `kitap_id`, `miktar`, `birim_fiyat`) VALUES
(1, 1, 3, 1, 42.00),
(2, 1, 7, 2, 22.90),
(3, 1, 12, 1, 26.90),
(4, 2, 1, 1, 29.90),
(5, 2, 5, 1, 24.90),
(6, 2, 19, 1, 8.00),
(7, 3, 8, 1, 45.00),
(8, 4, 10, 1, 39.90),
(9, 4, 16, 1, 37.90),
(10, 4, 17, 1, 34.90),
(11, 4, 9, 2, 19.90),
(12, 5, 2, 1, 35.50),
(13, 5, 4, 1, 27.90),
(14, 5, 6, 1, 38.50),
(15, 5, 14, 1, 23.50),
(16, 6, 2, 1, 35.50),
(17, 6, 3, 1, 42.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kitaplar`
--
ALTER TABLE `kitaplar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `siparisler`
--
ALTER TABLE `siparisler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Indexes for table `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siparis_id` (`siparis_id`),
  ADD KEY `kitap_id` (`kitap_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kitaplar`
--
ALTER TABLE `kitaplar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `siparisler`
--
ALTER TABLE `siparisler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `siparisler`
--
ALTER TABLE `siparisler`
  ADD CONSTRAINT `siparisler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`);

--
-- Constraints for table `siparis_urunleri`
--
ALTER TABLE `siparis_urunleri`
  ADD CONSTRAINT `siparis_urunleri_ibfk_1` FOREIGN KEY (`siparis_id`) REFERENCES `siparisler` (`id`),
  ADD CONSTRAINT `siparis_urunleri_ibfk_2` FOREIGN KEY (`kitap_id`) REFERENCES `kitaplar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

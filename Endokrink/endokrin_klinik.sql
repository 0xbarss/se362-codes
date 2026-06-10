-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 28 May 2026, 03:40:36
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
-- Veritabanı: `endokrin_klinik`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('Bekliyor','Tamamlandı','İptal') DEFAULT 'Bekliyor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `status`) VALUES
(1, 51, 17, '2026-06-01 09:00:00', 'Bekliyor'),
(2, 51, 17, '2026-05-27 09:00:00', 'İptal'),
(3, 51, 17, '2026-05-26 09:00:00', 'Tamamlandı'),
(4, 52, 11, '2026-06-01 09:20:00', 'Bekliyor'),
(5, 52, 11, '2026-05-27 09:20:00', 'İptal'),
(6, 52, 11, '2026-05-26 09:20:00', 'Tamamlandı'),
(7, 53, 19, '2026-06-01 09:40:00', 'Bekliyor'),
(8, 53, 19, '2026-05-27 09:40:00', 'İptal'),
(9, 53, 19, '2026-05-26 09:40:00', 'Tamamlandı'),
(10, 54, 15, '2026-06-01 10:00:00', 'Bekliyor'),
(11, 54, 15, '2026-05-27 10:00:00', 'İptal'),
(12, 54, 15, '2026-05-26 10:00:00', 'Tamamlandı'),
(13, 55, 16, '2026-06-01 10:20:00', 'Bekliyor'),
(14, 55, 16, '2026-05-27 10:20:00', 'İptal'),
(15, 55, 16, '2026-05-26 10:20:00', 'Tamamlandı'),
(16, 56, 15, '2026-06-01 10:40:00', 'Bekliyor'),
(17, 56, 15, '2026-05-27 10:40:00', 'İptal'),
(18, 56, 15, '2026-05-26 10:40:00', 'Tamamlandı'),
(19, 57, 20, '2026-06-01 11:00:00', 'Bekliyor'),
(20, 57, 20, '2026-05-27 11:00:00', 'İptal'),
(21, 57, 20, '2026-05-26 11:00:00', 'Tamamlandı'),
(22, 58, 12, '2026-06-01 11:20:00', 'Bekliyor'),
(23, 58, 12, '2026-05-27 11:20:00', 'İptal'),
(24, 58, 12, '2026-05-26 11:20:00', 'Tamamlandı'),
(25, 59, 12, '2026-06-01 11:40:00', 'Bekliyor'),
(26, 59, 12, '2026-05-27 11:40:00', 'İptal'),
(27, 59, 12, '2026-05-26 11:40:00', 'Tamamlandı'),
(28, 60, 20, '2026-06-01 13:00:00', 'Bekliyor'),
(29, 60, 20, '2026-05-27 13:00:00', 'İptal'),
(30, 60, 20, '2026-05-26 13:00:00', 'Tamamlandı'),
(32, 57, 20, '2026-05-28 10:00:00', 'Bekliyor');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `technician_id` int(11) DEFAULT NULL,
  `glucose` decimal(5,2) DEFAULT NULL,
  `tsh` decimal(5,2) DEFAULT NULL,
  `insulin` decimal(5,2) DEFAULT NULL,
  `test_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `lab_results`
--

INSERT INTO `lab_results` (`id`, `patient_id`, `technician_id`, `glucose`, `tsh`, `insulin`, `test_date`) VALUES
(1, 51, 45, 194.00, 8.00, 7.00, '2026-05-28 00:04:48'),
(2, 52, 49, 161.00, 6.00, 11.00, '2026-05-28 00:04:48'),
(3, 53, 41, 101.00, 7.00, 17.00, '2026-05-28 00:04:48'),
(4, 54, 47, 180.00, 3.00, 13.00, '2026-05-28 00:04:48'),
(5, 55, 48, 136.00, 7.00, 4.00, '2026-05-28 00:04:48'),
(6, 56, 41, 217.00, 0.00, 7.00, '2026-05-28 00:04:48'),
(7, 57, 46, 135.00, 2.00, 5.00, '2026-05-28 00:04:48'),
(8, 58, 49, 168.00, 6.00, 15.00, '2026-05-28 00:04:48'),
(9, 59, 44, 216.00, 4.00, 7.00, '2026-05-28 00:04:49'),
(10, 60, 43, 193.00, 4.00, 9.00, '2026-05-28 00:04:49');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Yönetici'),
(2, 'Doktor'),
(3, 'Hemşire'),
(4, 'Resepsiyonist'),
(5, 'Lab Teknisyeni'),
(6, 'Hasta');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `full_name`, `phone`, `city`, `district`) VALUES
(1, 1, 'yönetici1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ahmet Yılmaz', '5394144944', 'Antalya', 'Beşiktaş'),
(2, 1, 'yönetici2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Canan Kaya', '5326245532', 'İstanbul', 'Nilüfer'),
(3, 1, 'yönetici3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Murat Demir', '5334999680', 'Antalya', 'Nilüfer'),
(4, 1, 'yönetici4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Sibel Çelik', '5372394237', 'Ankara', 'Nilüfer'),
(5, 1, 'yönetici5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Bülent Şahin', '5340775896', 'Ankara', 'Nilüfer'),
(6, 1, 'yönetici6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ayla Öztürk', '5322682386', 'Bursa', 'Çankaya'),
(7, 1, 'yönetici7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Hakan Arslan', '5319865756', 'Ankara', 'Bornova'),
(8, 1, 'yönetici8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Gülten Bulut', '5349286729', 'Bursa', 'Beşiktaş'),
(9, 1, 'yönetici9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Niyazi Kılıç', '5349816775', 'İzmir', 'Bornova'),
(10, 1, 'yönetici10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Zekiye Yıldız', '5359758851', 'Bursa', 'Muratpaşa'),
(11, 2, 'doktor1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Mustafa Aydın', '5367487850', 'Bursa', 'Bornova'),
(12, 2, 'doktor2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Eser Koç', '5336490112', 'İzmir', 'Çankaya'),
(13, 2, 'doktor3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ferit Özkan', '5393481293', 'Antalya', 'Çankaya'),
(14, 2, 'doktor4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Banu Yıldırım', '5381467929', 'Antalya', 'Çankaya'),
(15, 2, 'doktor5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Kemal Polat', '5386812905', 'Ankara', 'Nilüfer'),
(16, 2, 'doktor6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Dilek Çetin', '5360540042', 'Antalya', 'Çankaya'),
(17, 2, 'doktor7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Cem Aksoy', '5322004967', 'Antalya', 'Muratpaşa'),
(18, 2, 'doktor8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Aslı Yalçın', '5391513224', 'Antalya', 'Bornova'),
(19, 2, 'doktor9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Gökhan Özer', '5329561162', 'İzmir', 'Nilüfer'),
(20, 2, 'doktor10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Arzu Yurt', '5346919341', 'Ankara', 'Beşiktaş'),
(21, 3, 'hemşire1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Merve Güneş', '5353155134', 'Antalya', 'Bornova'),
(22, 3, 'hemşire2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Selin Deniz', '5328098931', 'Ankara', 'Muratpaşa'),
(23, 3, 'hemşire3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Fatma Avcı', '5335271546', 'İzmir', 'Nilüfer'),
(24, 3, 'hemşire4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Zeynep Korkmaz', '5346941386', 'İstanbul', 'Muratpaşa'),
(25, 3, 'hemşire5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ebru Yiğit', '5312181038', 'Ankara', 'Nilüfer'),
(26, 3, 'hemşire6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Özlem Şen', '5375758163', 'İstanbul', 'Muratpaşa'),
(27, 3, 'hemşire7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Derya Köse', '5321722867', 'İstanbul', 'Nilüfer'),
(28, 3, 'hemşire8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Seda Sari', '5389112842', 'Bursa', 'Bornova'),
(29, 3, 'hemşire9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Pınar Yaman', '5323521598', 'Bursa', 'Bornova'),
(30, 3, 'hemşire10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Hülya Eren', '5377120145', 'İzmir', 'Çankaya'),
(31, 4, 'resepsiyon1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Mert Can', '5387639618', 'Ankara', 'Bornova'),
(32, 4, 'resepsiyon2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Burak Toprak', '5339776839', 'İstanbul', 'Beşiktaş'),
(33, 4, 'resepsiyon3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Emre Bulut', '5386320125', 'İzmir', 'Beşiktaş'),
(34, 4, 'resepsiyon4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Oğuzhan Çakır', '5355530163', 'Antalya', 'Bornova'),
(35, 4, 'resepsiyon5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Tolga Ateş', '5365229104', 'İzmir', 'Muratpaşa'),
(36, 4, 'resepsiyon6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Serkan Doğan', '5350406581', 'İstanbul', 'Çankaya'),
(37, 4, 'resepsiyon7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Uğur Güler', '5318746378', 'Ankara', 'Çankaya'),
(38, 4, 'resepsiyon8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Kaan Aslan', '5374110195', 'İstanbul', 'Nilüfer'),
(39, 4, 'resepsiyon9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Volkan Şimşek', '5321157564', 'Bursa', 'Beşiktaş'),
(40, 4, 'resepsiyon10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Tunay Taş', '5313009213', 'Ankara', 'Muratpaşa'),
(41, 5, 'lab1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Deniz Bilgin', '5365840751', 'Bursa', 'Çankaya'),
(42, 5, 'lab2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Turgut Soydan', '5323442268', 'Antalya', 'Beşiktaş'),
(43, 5, 'lab3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Metin Erdem', '5374655769', 'Antalya', 'Beşiktaş'),
(44, 5, 'lab4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ufuk Uzun', '5375643748', 'İzmir', 'Çankaya'),
(45, 5, 'lab5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Kenan Kara', '5318213795', 'Ankara', 'Nilüfer'),
(46, 5, 'lab6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'İlker ak', '5313334082', 'İzmir', 'Muratpaşa'),
(47, 5, 'lab7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Gürkan Kaplan', '5397169176', 'Ankara', 'Nilüfer'),
(48, 5, 'lab8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Mesut duman', '5353354449', 'Ankara', 'Bornova'),
(49, 5, 'lab9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Sinan kartal', '5376813579', 'İzmir', 'Beşiktaş'),
(50, 5, 'lab10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Yener Tekin', '5337995928', 'Bursa', 'Beşiktaş'),
(51, 6, 'hasta1', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Mehmet Yıldız', '5343202179', 'Antalya', 'Nilüfer'),
(52, 6, 'hasta2', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Mehmet Yıldız', '5378828037', 'Antalya', 'Çankaya'),
(53, 6, 'hasta3', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ece Öztürk', '5317970534', 'Ankara', 'Beşiktaş'),
(54, 6, 'hasta4', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Elif Polat', '5324064264', 'Antalya', 'Nilüfer'),
(55, 6, 'hasta5', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Gamze Özdemir', '5375792586', 'Ankara', 'Beşiktaş'),
(56, 6, 'hasta6', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ali Rıza Keskiner', '5320772294', 'İzmir', 'Nilüfer'),
(57, 6, 'hasta7', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Ayşe Solmaz', '5321858850', 'Ankara', 'Çankaya'),
(58, 6, 'hasta8', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Selim Aktaş', '5369573856', 'İstanbul', 'Bornova'),
(59, 6, 'hasta9', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Hüseyin Çevik', '5319426322', 'Ankara', 'Nilüfer'),
(60, 6, 'hasta10', '$2y$10$p88VJPzhQyywMISFX7arL.e0Q094FpBkc.2AMZqpNF1tcWgxmdWG6', 'Nisa Karaca', '5381766274', 'Antalya', 'Bornova');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `medication` varchar(255) DEFAULT NULL,
  `visit_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `visits`
--

INSERT INTO `visits` (`id`, `patient_id`, `doctor_id`, `diagnosis`, `medication`, `visit_date`) VALUES
(1, 51, 17, 'Tip 2 Diyabet', 'Januvia', '2026-05-26 09:00:00'),
(2, 52, 11, 'İnsülin Direnci', 'Metformin', '2026-05-26 09:20:00'),
(3, 53, 19, 'Hashimoto', 'Euthyrox', '2026-05-26 09:40:00'),
(4, 54, 15, 'İnsülin Direnci', 'Euthyrox', '2026-05-26 10:00:00'),
(5, 55, 16, 'İnsülin Direnci', 'Metformin', '2026-05-26 10:20:00'),
(6, 56, 15, 'Hashimoto', 'Levotiron', '2026-05-26 10:40:00'),
(7, 57, 20, 'İnsülin Direnci', 'Euthyrox', '2026-05-26 11:00:00'),
(8, 58, 12, 'Hashimoto', 'Euthyrox', '2026-05-26 11:20:00'),
(9, 59, 12, 'Hipotiroidi', 'Levotiron', '2026-05-26 11:40:00'),
(10, 60, 20, 'Hashimoto', 'Januvia', '2026-05-26 13:00:00');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Tablo için indeksler `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `technician_id` (`technician_id`);

--
-- Tablo için indeksler `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Tablo için indeksler `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Tablo için AUTO_INCREMENT değeri `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- Tablo için AUTO_INCREMENT değeri `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `lab_results_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `lab_results_ibfk_2` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Tablo kısıtlamaları `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

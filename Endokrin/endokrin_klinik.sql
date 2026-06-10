-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2026 at 07:52 PM
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
-- Database: `endokrin_klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('Bekliyor','Tamamlandı','İptal') DEFAULT 'Bekliyor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date`, `status`) VALUES
(1, 60, 17, '2026-04-27 10:00:00', 'Bekliyor'),
(2, 55, 18, '2026-04-27 09:00:00', 'Bekliyor'),
(3, 52, 20, '2026-04-27 12:00:00', 'Bekliyor');

-- --------------------------------------------------------

--
-- Table structure for table `lab_results`
--

CREATE TABLE `lab_results` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `glucose` decimal(5,2) DEFAULT NULL,
  `tsh` decimal(5,2) DEFAULT NULL,
  `insulin` decimal(5,2) DEFAULT NULL,
  `test_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `lab_results`
--

INSERT INTO `lab_results` (`id`, `patient_id`, `glucose`, `tsh`, `insulin`, `test_date`) VALUES
(1, 51, 192.00, 3.00, 13.00, '2026-04-26 15:19:00'),
(2, 52, 91.00, 0.00, 9.00, '2026-04-26 15:19:00'),
(3, 53, 117.00, 1.00, 13.00, '2026-04-26 15:19:00'),
(4, 54, 92.00, 6.00, 15.00, '2026-04-26 15:19:00'),
(5, 55, 172.00, 2.00, 3.00, '2026-04-26 15:19:00'),
(6, 56, 248.00, 10.00, 18.00, '2026-04-26 15:19:00'),
(7, 57, 222.00, 2.00, 15.00, '2026-04-26 15:19:00'),
(8, 58, 92.00, 3.00, 20.00, '2026-04-26 15:19:00'),
(9, 59, 245.00, 5.00, 16.00, '2026-04-26 15:19:00'),
(10, 60, 109.00, 2.00, 4.00, '2026-04-26 15:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `roles`
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
-- Table structure for table `users`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `password`, `full_name`, `phone`, `city`, `district`) VALUES
(1, 1, 'admin1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ahmet Arslan', '5388266097', 'İstanbul', 'Beşiktaş'),
(2, 1, 'admin2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Şahin', '5326154315', 'Ankara', 'Çankaya'),
(3, 1, 'admin3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Şahin', '5335973288', 'Bursa', 'Nilüfer'),
(4, 1, 'admin4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Doğan', '5391403497', 'Antalya', 'Muratpaşa'),
(5, 1, 'admin5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Bulut', '5369097623', 'İzmir', 'Bornova'),
(6, 1, 'admin6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Kılıç', '5361277629', 'İstanbul', 'Beşiktaş'),
(7, 1, 'admin7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Yılmaz', '5389095278', 'Ankara', 'Çankaya'),
(8, 1, 'admin8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Kaya', '5371643506', 'Bursa', 'Nilüfer'),
(9, 1, 'admin9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ahmet Demir', '5380931702', 'Antalya', 'Muratpaşa'),
(10, 1, 'admin10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Yılmaz', '5389727993', 'İzmir', 'Bornova'),
(11, 2, 'doktor1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Şahin', '5315844865', 'İstanbul', 'Beşiktaş'),
(12, 2, 'doktor2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Arslan', '5370040348', 'Ankara', 'Çankaya'),
(13, 2, 'doktor3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ahmet Kılıç', '5322667146', 'Bursa', 'Nilüfer'),
(14, 2, 'doktor4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Demir', '5373214691', 'Antalya', 'Muratpaşa'),
(15, 2, 'doktor5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Kılıç', '5318072022', 'İzmir', 'Bornova'),
(16, 2, 'doktor6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Demir', '5340716038', 'İstanbul', 'Beşiktaş'),
(17, 2, 'doktor7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Öztürk', '5349364126', 'Ankara', 'Çankaya'),
(18, 2, 'doktor8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Kaya', '5324672523', 'Bursa', 'Nilüfer'),
(19, 2, 'doktor9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Bulut', '5355270237', 'Antalya', 'Muratpaşa'),
(20, 2, 'doktor10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Selin Doğan', '5312333618', 'İzmir', 'Bornova'),
(21, 3, 'hemşire1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Kılıç', '5365857386', 'İstanbul', 'Beşiktaş'),
(22, 3, 'hemşire2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Şahin', '5312286076', 'Ankara', 'Çankaya'),
(23, 3, 'hemşire3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Çelik', '5333858228', 'Bursa', 'Nilüfer'),
(24, 3, 'hemşire4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Çelik', '5332432916', 'Antalya', 'Muratpaşa'),
(25, 3, 'hemşire5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Kılıç', '5350589897', 'İzmir', 'Bornova'),
(26, 3, 'hemşire6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ahmet Yılmaz', '5355650740', 'İstanbul', 'Beşiktaş'),
(27, 3, 'hemşire7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Doğan', '5326484014', 'Ankara', 'Çankaya'),
(28, 3, 'hemşire8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Arslan', '5345467851', 'Bursa', 'Nilüfer'),
(29, 3, 'hemşire9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Öztürk', '5347887218', 'Antalya', 'Muratpaşa'),
(30, 3, 'hemşire10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Yılmaz', '5393032538', 'İzmir', 'Bornova'),
(31, 4, 'resepsiyon1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Bulut', '5341501041', 'İstanbul', 'Beşiktaş'),
(32, 4, 'resepsiyon2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Çelik', '5398407593', 'Ankara', 'Çankaya'),
(33, 4, 'resepsiyon3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Kılıç', '5387534844', 'Bursa', 'Nilüfer'),
(34, 4, 'resepsiyon4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Demir', '5342451447', 'Antalya', 'Muratpaşa'),
(35, 4, 'resepsiyon5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Yılmaz', '5329652705', 'İzmir', 'Bornova'),
(36, 4, 'resepsiyon6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Kılıç', '5310909187', 'İstanbul', 'Beşiktaş'),
(37, 4, 'resepsiyon7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Bulut', '5345587825', 'Ankara', 'Çankaya'),
(38, 4, 'resepsiyon8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Doğan', '5395211567', 'Bursa', 'Nilüfer'),
(39, 4, 'resepsiyon9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Selin Demir', '5359294365', 'Antalya', 'Muratpaşa'),
(40, 4, 'resepsiyon10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Şahin', '5390837124', 'İzmir', 'Bornova'),
(41, 5, 'lab1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Yılmaz', '5386302528', 'İstanbul', 'Beşiktaş'),
(42, 5, 'lab2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mustafa Arslan', '5359001273', 'Ankara', 'Çankaya'),
(43, 5, 'lab3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Öztürk', '5326098786', 'Bursa', 'Nilüfer'),
(44, 5, 'lab4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Bulut', '5333490111', 'Antalya', 'Muratpaşa'),
(45, 5, 'lab5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Emel Arslan', '5379154203', 'İzmir', 'Bornova'),
(46, 5, 'lab6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Arslan', '5315300682', 'İstanbul', 'Beşiktaş'),
(47, 5, 'lab7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ali Öztürk', '5399040806', 'Ankara', 'Çankaya'),
(48, 5, 'lab8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mehmet Arslan', '5379301989', 'Bursa', 'Nilüfer'),
(49, 5, 'lab9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Selin Doğan', '5389387527', 'Antalya', 'Muratpaşa'),
(50, 5, 'lab10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Bulut', '5319031674', 'İzmir', 'Bornova'),
(51, 6, 'hasta1', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Kaya', '5386995792', 'İstanbul', 'Beşiktaş'),
(52, 6, 'hasta2', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Mert Arslan', '5397883943', 'Ankara', 'Çankaya'),
(53, 6, 'hasta3', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Bulut', '5338432340', 'Bursa', 'Nilüfer'),
(54, 6, 'hasta4', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Selin Kaya', '5368509874', 'Antalya', 'Muratpaşa'),
(55, 6, 'hasta5', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ahmet Çelik', '5337252353', 'İzmir', 'Bornova'),
(56, 6, 'hasta6', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Şahin', '5360732147', 'İstanbul', 'Beşiktaş'),
(57, 6, 'hasta7', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Can Yılmaz', '5391903678', 'Ankara', 'Çankaya'),
(58, 6, 'hasta8', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Ayşe Bulut', '5387321952', 'Bursa', 'Nilüfer'),
(59, 6, 'hasta9', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Çelik', '5360898730', 'Antalya', 'Muratpaşa'),
(60, 6, 'hasta10', '$2y$10$N1Ng7ofDUUwxBwPnNfme2eyl9mueo9dwtENLM0/5GUc9vRMknYs9K', 'Fatma Bulut', '5332527796', 'İzmir', 'Bornova');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `medication` varchar(255) DEFAULT NULL,
  `visit_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`id`, `patient_id`, `doctor_id`, `diagnosis`, `medication`, `visit_date`) VALUES
(1, 51, 16, 'Tip 2 Diyabet', 'Metformin', '2026-04-26 15:19:00'),
(2, 51, 13, 'Hipotiroidi', 'Metformin', '2026-04-26 15:19:00'),
(3, 51, 15, 'İnsülin Direnci', 'Januvia', '2026-04-26 15:19:00'),
(4, 51, 13, 'Hipertiroidi', 'Metformin', '2026-04-26 15:19:00'),
(5, 51, 13, 'Hashimoto', 'Euthyrox', '2026-04-26 15:19:00'),
(6, 52, 14, 'Hashimoto', 'Euthyrox', '2026-04-26 15:19:00'),
(7, 52, 13, 'Hipertiroidi', 'Euthyrox', '2026-04-26 15:19:00'),
(8, 52, 18, 'Hashimoto', 'Levotiron', '2026-04-26 15:19:00'),
(9, 52, 18, 'İnsülin Direnci', 'Lantus', '2026-04-26 15:19:00'),
(10, 52, 12, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(11, 53, 18, 'Tip 2 Diyabet', 'Januvia', '2026-04-26 15:19:00'),
(12, 53, 11, 'İnsülin Direnci', 'Lantus', '2026-04-26 15:19:00'),
(13, 53, 16, 'Hipertiroidi', 'Lantus', '2026-04-26 15:19:00'),
(14, 53, 17, 'İnsülin Direnci', 'Levotiron', '2026-04-26 15:19:00'),
(15, 53, 15, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(16, 54, 17, 'Hashimoto', 'Januvia', '2026-04-26 15:19:00'),
(17, 54, 18, 'Hipotiroidi', 'Januvia', '2026-04-26 15:19:00'),
(18, 54, 14, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(19, 54, 12, 'Hipertiroidi', 'Lantus', '2026-04-26 15:19:00'),
(20, 54, 16, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(21, 55, 13, 'Hashimoto', 'Levotiron', '2026-04-26 15:19:00'),
(22, 55, 20, 'Hashimoto', 'Lantus', '2026-04-26 15:19:00'),
(23, 55, 11, 'Hipertiroidi', 'Januvia', '2026-04-26 15:19:00'),
(24, 55, 15, 'Hipertiroidi', 'Euthyrox', '2026-04-26 15:19:00'),
(25, 55, 19, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(26, 56, 11, 'Tip 2 Diyabet', 'Euthyrox', '2026-04-26 15:19:00'),
(27, 56, 14, 'Tip 2 Diyabet', 'Metformin', '2026-04-26 15:19:00'),
(28, 56, 14, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(29, 56, 13, 'Hashimoto', 'Euthyrox', '2026-04-26 15:19:00'),
(30, 56, 14, 'İnsülin Direnci', 'Januvia', '2026-04-26 15:19:00'),
(31, 57, 14, 'Tip 2 Diyabet', 'Januvia', '2026-04-26 15:19:00'),
(32, 57, 12, 'Hashimoto', 'Metformin', '2026-04-26 15:19:00'),
(33, 57, 20, 'Hipertiroidi', 'Metformin', '2026-04-26 15:19:00'),
(34, 57, 17, 'Tip 2 Diyabet', 'Januvia', '2026-04-26 15:19:00'),
(35, 57, 20, 'Hipertiroidi', 'Euthyrox', '2026-04-26 15:19:00'),
(36, 58, 12, 'İnsülin Direnci', 'Lantus', '2026-04-26 15:19:00'),
(37, 58, 12, 'Hashimoto', 'Lantus', '2026-04-26 15:19:00'),
(38, 58, 14, 'İnsülin Direnci', 'Levotiron', '2026-04-26 15:19:00'),
(39, 58, 16, 'İnsülin Direnci', 'Januvia', '2026-04-26 15:19:00'),
(40, 58, 13, 'Tip 2 Diyabet', 'Januvia', '2026-04-26 15:19:00'),
(41, 59, 13, 'Hashimoto', 'Januvia', '2026-04-26 15:19:00'),
(42, 59, 14, 'Tip 2 Diyabet', 'Januvia', '2026-04-26 15:19:00'),
(43, 59, 20, 'Hipertiroidi', 'Euthyrox', '2026-04-26 15:19:00'),
(44, 59, 18, 'İnsülin Direnci', 'Euthyrox', '2026-04-26 15:19:00'),
(45, 59, 12, 'Hipertiroidi', 'Metformin', '2026-04-26 15:19:00'),
(46, 60, 20, 'Hipotiroidi', 'Metformin', '2026-04-26 15:19:00'),
(47, 60, 13, 'Tip 2 Diyabet', 'Levotiron', '2026-04-26 15:19:00'),
(48, 60, 11, 'Hipertiroidi', 'Levotiron', '2026-04-26 15:19:00'),
(49, 60, 14, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(50, 60, 12, 'Hipotiroidi', 'Lantus', '2026-04-26 15:19:00'),
(51, 51, 14, 'Karın ağrısı', 'Talcid', '2026-04-26 16:24:32'),
(52, 51, 14, 'Baş ağrısı uykusuzluk stres', 'Atranax', '2026-04-26 16:47:28'),
(53, 59, 14, 'Bulantı kusma', 'Lefanto', '2026-04-26 16:50:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `lab_results`
--
ALTER TABLE `lab_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `lab_results`
--
ALTER TABLE `lab_results`
  ADD CONSTRAINT `lab_results_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `visits_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `visits_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

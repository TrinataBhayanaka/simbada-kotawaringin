-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 18, 2016 at 02:30 AM
-- Server version: 10.0.22-MariaDB-log
-- PHP Version: 5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simbada_kotawaringin_v2_08juli2016`
--

--
-- Dumping data for table `penyusutan_tahun_berjalan`
--

INSERT INTO `penyusutan_tahun_berjalan` (`id`, `kodeSatker`, `KelompokAset`, `Tahun`, `StatusRunning`, `UserNm`) VALUES
(122, 'REVISI', 'Peralatan dan Mesin (B)', 2015, 2, '0'),
(123, 'REVISI', 'Gedung dan Bangunan (C)', 2015, 2, '0'),
(124, 'REVISI', 'Jalan, Irigrasi, dan Jaringan (D)', 2015, 2, '0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

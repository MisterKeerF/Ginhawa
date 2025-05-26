-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 05:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `userName` varchar(50) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `userName`, `fullName`, `password`) VALUES
(2, 'admin', 'Admin Justine', '123456'),
(4, 'Justines', 'Justine Tabor', '$2y$10$O53kmhelmxw/7oCwJ70SU.rkduRkDG3DWBsdk.sFvAV6rwbfz9BK6');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `doctor` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(20) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status` enum('Pending','Done','Canceled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `name`, `doctor`, `date`, `time`, `patient_id`, `status`) VALUES
(1, 'Just', 'Dra. Precious Damian', '2025-05-30', '08:00 AM', 0, ''),
(2, 'Just', 'Justine', '2025-05-29', '08:00 AM', 0, ''),
(3, 'Oscar', 'Justine', '2025-05-31', '09:00 AM', 0, ''),
(4, 'Oscar', 'Justine', '2025-05-30', '01:00 PM', 0, ''),
(5, 'Oscar', 'Justine', '2025-05-30', '01:00 PM', 0, ''),
(6, 'Ronin', 'Justine', '2025-05-28', '10:00 AM', 0, ''),
(7, 'Ronin', 'Justine', '2025-05-28', '08:00 AM', 0, ''),
(8, 'Ronin', 'Justine', '2025-05-28', '03:00 PM', 0, ''),
(9, 'Nature', 'Ronin Wallet', '2025-05-27', '03:00 PM', 0, ''),
(10, 'open', 'Justine', '2025-05-26', '03:00 PM', 0, 'Pending'),
(11, 'matthew', 'Keer The Great', '2025-05-26', '03:00 PM', 0, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(10) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `specialization` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `user_name`, `fullName`, `specialization`, `password`) VALUES
(4, 'Keer', 'Keer The Great', 'Cardiologist', '$2y$10$jCDTZvuQCLRMhug9XiZ..eELmqJJNPfQhSa.XPdqCEc.SjxyPcM5q'),
(5, 'Justine', 'Justine', 'General', '$2y$10$byMEpKH5oou5JAF11XJ3f.vKZK2OsZVBL2xcGX1GZyhjXTDYO1cC.'),
(6, 'Ariane', 'Ariane Jade Bote', 'General', '$2y$10$B8Kv4.w1.1akRse1078WYOePAlg3S.OGl5jC2oxsYoTaNZ4SV3mty'),
(7, 'Prince', 'Prince Lord', 'Overall', '$2y$10$fwLocmSggRsELwmg.64kyufZIlcWBUY2Qt96433U5Tc.3JOndqgCK'),
(8, 'Precious', 'Precious damian', 'Overall', '$2y$10$rrVlsnnDfTQAQfc79XmyXudIkGANlgvxc3ZX0gqpTVqeQZYQ5gge2'),
(9, 'Hannah', 'Hanna De Leon', 'Mag-ingay', '$2y$10$sboZCNinfaQed2YfE/IEOeF4rBEoEd90lPArYKQvbvhRGzIkNQ6dK'),
(11, 'Oscar', 'Oscar Oganiza', 'Engineer ng Mundo', '$2y$10$sJyWC1jjTe.m6Frs/k0Jpu77p6YxotmlM1scjCE.m/d8AVM5L6okS'),
(12, 'Ronin', 'Ronin Wallet', 'General', '$2y$10$XAOXruoQUmbZNVObrUXF1.RWDXjuO4g2zGUR/66cpzuHHqLYIQT.G');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `patient_id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `age` int(11) NOT NULL,
  `sex` char(10) NOT NULL,
  `phoneNumber` bigint(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`patient_id`, `firstName`, `lastName`, `age`, `sex`, `phoneNumber`, `password`) VALUES
(9, 'Justine ', 'Bieber', 51, 'male/lalak', 12345678901, '$2y$10$1C8a/vh/NFu3d'),
(11, 'killua', 'jojo', 56, 'male/lalak', 9876543210, '$2y$10$rFECrFakQm1sn'),
(12, 'justine', 'graet', 21, 'male/lalak', 99999999991, '$2y$10$CoOrkOaUVZ3Yh'),
(13, 'juju', 'onthebeat', 23, 'male/lalak', 2323232323, '$2y$10$IBsJW8pdSI5Th'),
(14, 'kraaa', 'brrbrr', 22, 'male/lalak', 123456789, '$2y$10$P1Zsdt38LADwS'),
(15, 'hgfjhv', 'hjtfuytfu', 25, 'male/lalak', 21, '$2y$10$nz.JjvhyZcWt/'),
(16, 'Errenthe', 'Great', 21, 'male/lalak', 0, '$2y$10$cnzKi4kY0Ioq8'),
(17, 'Keerpogi', 'Pogi', 21, 'male/lalak', 102030405, '$2y$10$nRPneSxM9/.cT79WNOcwZOPcx4kV2UZv4zSNZZak.EVDPWfFxV2US'),
(18, 'Oscarthe', 'Great', 19, 'male/lalak', 88888888888, '$2y$10$qhJ.OVNMswCvk5nvVyV9cOGSB4vaMIqIV4yD4esDqF27Jp/cGql8S'),
(19, 'yv', 'ff', 10, 'male/lalak', 9543784035, '$2y$10$QIlB7krW7.8ggPSi1Mlo6.yDReTJj2WWnvIIBk3vMWLQBWfon5uFK'),
(20, 'Test', 'Long', 99, 'male/lalak', 22222222222, '$2y$10$ynCriheuwePfz/0gtOECRudebBigjkneflSIcxm.w3p7d9qYENas6'),
(21, 'Just', 'Me', 21, 'male/lalak', 33333333333, '$2y$10$LweR/JiuG43HH5aoIYoe8.VPw2avEU35hdbzyYt6A.47xrnvcvuRi'),
(22, 'Just', 'Me', 21, 'male/lalak', 33333333333, '$2y$10$h8seWIKQr7WKLm9Mr.cFEuAGKIbMFa/1H1D07ojjJHdMZIYhd/ZJe'),
(23, 'Oscar', 'Oganiza', 21, 'male/lalak', 44444444444, '$2y$10$mYWnJgWVQnuxcYRG1qqxVenu9Fg4F0E9wvifEbIDYmxhGULZWDiN.'),
(24, 'Oscar', 'Desolation', 17, 'female/bab', 55555555555, '$2y$10$hJck5rvLU5APPYJAdmVzseM81QNXwi/NU1ttw72fI0IRJjO3HLCmu'),
(25, 'Ronin', 'Wallet', 69, 'male/lalak', 696969, '$2y$10$CGp2ELvvkkjk1KgUTja58.vgCmjM9GQyeXVswh7c1Fm5fA9nFeGNG'),
(26, 'Nature', 'Sprig', 23, 'male/lalak', 111, '$2y$10$xFD4lQVfyjYnBMF7sc5Ab.TJmf5i9V0DrnRLzKrHigZAWMD.z2fTm'),
(27, 'Harvard', 'Lux', 54, 'male/lalak', 123456789, '$2y$10$wLprYV5nPTBuQdzowU/B3.5GxcqCX6QD2C3FWIZG7U//fgYSf7VD.'),
(28, 'Pre', 'Preno', 44, 'male/lalak', 0, '$2y$10$89s4xeYMEMVZ/CY71wpUJOO69Rugi1Jgk5h6OMDmFTDP4GpvrFeYK'),
(29, 'open', 'hammer', 89, 'male/lalak', 77777777777, '$2y$10$u0xXbb9I003ov7d3Z0NJx.VItH5n52qOasaDL2WluimgSa1xSohtS'),
(30, 'matthew', 'mangalili', 18, 'male/lalak', 10203, '$2y$10$2BzJv/tPbiLGrqEipkPn3eA2sVSV8APjDZ9wUnQ.iSsbhwNMiy6x.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`patient_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

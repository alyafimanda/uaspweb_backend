-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2022 at 05:08 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uaspweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `idbarang` varchar(15) NOT NULL,
  `namabarang` varchar(30) NOT NULL,
  `merk` varchar(30) NOT NULL,
  `jumlah` int(10) NOT NULL,
  `hargabarang` int(20) NOT NULL,
  `expiredate` date NOT NULL,
  `idkaryawan` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`idbarang`, `namabarang`, `merk`, `jumlah`, `hargabarang`, `expiredate`, `idkaryawan`) VALUES
('B001', 'Makanan Kucing', 'Whiskas', 50, 35000, '2023-10-07', 101),
('B002', 'Makanan kucing', 'Royal Canin', 25, 45000, '2023-12-01', 102),
('B003', 'Makanan Kucing', 'Me-o', 20, 50000, '2022-12-29', 103);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `idkaryawan` int(10) NOT NULL,
  `password` varchar(25) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `jeniskelamin` varchar(10) NOT NULL,
  `email` varchar(35) NOT NULL,
  `nohp` int(15) NOT NULL,
  `idshift` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`idkaryawan`, `password`, `nama`, `jeniskelamin`, `email`, `nohp`, `idshift`) VALUES
(101, 'a123', 'Alya Fimanda', 'Perempuan', 'alyafimanda@gmail', 1234567, 'S01'),
(102, '1234', 'Alif Alfarabi', 'Laki-Laki', 'alifalfarabi@gmail.com', 876543, 'S01'),
(103, 'b1212', 'Egi Larasati', 'Perempuan', 'egiklaa@gmail.com', 67543, 'S02');

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `idshift` varchar(10) NOT NULL,
  `keterangan` varchar(25) NOT NULL,
  `jam` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`idshift`, `keterangan`, `jam`) VALUES
('S01', 'Pagi', '09:00-16:00'),
('S02', 'Malam', '16:00-21:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`idbarang`),
  ADD KEY `idkaryawan` (`idkaryawan`);

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`idkaryawan`),
  ADD KEY `idshift` (`idshift`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`idshift`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`idkaryawan`) REFERENCES `karyawan` (`idkaryawan`);

--
-- Constraints for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD CONSTRAINT `karyawan_ibfk_1` FOREIGN KEY (`idshift`) REFERENCES `shift` (`idshift`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

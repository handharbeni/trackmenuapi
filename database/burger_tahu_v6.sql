-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 25, 2017 at 08:08 PM
-- Server version: 5.5.57-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `burger_tahu`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_admin`
--

CREATE TABLE IF NOT EXISTS `m_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_outlet` int(11) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `key` varchar(250) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `m_admin`
--

INSERT INTO `m_admin` (`id`, `id_outlet`, `username`, `password`, `key`, `tanggal`) VALUES
(1, 0, 'superuser', '202cb962ac59075b964b07152d234b70', 'superuserkey', '2017-07-17'),
(2, 1, 'outletsuhat', '202cb962ac59075b964b07152d234b70', 'superkeyoutletsuhat', '2017-07-17');

-- --------------------------------------------------------

--
-- Table structure for table `m_kurir`
--

CREATE TABLE IF NOT EXISTS `m_kurir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `foto_profil` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `no_plat` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `m_kurir`
--

INSERT INTO `m_kurir` (`id`, `nama`, `username`, `password`, `foto_profil`, `no_hp`, `no_plat`, `key`, `tanggal`) VALUES
(1, 'Kurir Satu', 'kurirsatu', '202cb962ac59075b964b07152d234b70', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '08977997161', 'N 4605 BX', 'keykurirsatu', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `m_menu`
--

CREATE TABLE IF NOT EXISTS `m_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
  `harga` varchar(50) DEFAULT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `m_menu`
--

INSERT INTO `m_menu` (`id`, `nama`, `gambar`, `harga`, `kategori`, `sha`) VALUES
(1, 'TEST', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan', NULL),
(2, 'Onde Onde', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '35000', 'Makanan', NULL),
(3, 'Es Teh', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '5000', 'Minuman', NULL),
(4, 'Es Jeruk', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '7000', 'Minuman', NULL),
(5, 'Es Dawet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '10000', 'Minuman', NULL),
(6, 'Es Cendol', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '15000', 'Minuman', NULL),
(7, 'Nasi Goreng', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan', NULL),
(8, 'Nasi Liwet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '17000', 'Makanan', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_order`
--

CREATE TABLE IF NOT EXISTS `m_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_kurir` int(11) DEFAULT NULL,
  `id_outlet` int(11) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `tanggal_waktu` datetime DEFAULT '0000-00-00 00:00:00',
  `status` int(11) DEFAULT '1' COMMENT '1:new order, 2:accept by kurir, 3:current pengiriman, 4:pengiriman selese, 5:cancel by admin or user',
  `keterangan` text,
  `delivery_fee` varchar(50) DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `m_order`
--

INSERT INTO `m_order` (`id`, `id_user`, `id_kurir`, `id_outlet`, `alamat`, `latitude`, `longitude`, `tanggal_waktu`, `status`, `keterangan`, `delivery_fee`, `sha`) VALUES
(1, 1, 0, 1, NULL, NULL, NULL, '2017-06-05 23:58:09', 1, NULL, NULL, NULL),
(2, 1, 0, NULL, NULL, NULL, NULL, '2017-06-05 23:58:09', 1, NULL, NULL, NULL),
(29, 2, 0, 1, 'Jalan Raya Gadang No.35', '-8.011161258465417', '112.62892238795759', '2017-07-12 20:52:09', 1, 'nothing', '90000', NULL),
(30, 2, 0, NULL, 'Jalan Puncak Borobudur No.6510', '-7.9359448', '112.6245207', '2017-07-17 14:57:10', 1, 'nothing', '10000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_outlet`
--

CREATE TABLE IF NOT EXISTS `m_outlet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) DEFAULT NULL,
  `outlet` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `long` varchar(50) DEFAULT NULL,
  `tanggal_waktu` datetime DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_outlet_m_resto` (`id_resto`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `m_outlet`
--

INSERT INTO `m_outlet` (`id`, `id_resto`, `outlet`, `alamat`, `lat`, `long`, `tanggal_waktu`, `sha`) VALUES
(1, 1, 'Burger Tahu Sukarno Hatta', 'Suhat', 'lat', 'lang', '2017-07-21 13:56:56', 'sha'),
(2, 1, 'Burger Tahu Sukun', 'Sukun', 'lat', 'lang', '2017-07-21 13:56:58', 'sha1'),
(3, 1, 'Burger Tahu Blimbing', 'Blimbing', 'lat', 'lang', '2017-07-21 13:56:59', 'sha2');

-- --------------------------------------------------------

--
-- Table structure for table `m_resto`
--

CREATE TABLE IF NOT EXISTS `m_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `m_resto`
--

INSERT INTO `m_resto` (`id`, `resto`) VALUES
(1, 'Burger Tahu');

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE IF NOT EXISTS `m_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `key` varchar(150) DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`id`, `nama`, `email`, `password`, `no_hp`, `alamat`, `location`, `key`, `tanggal_buat`) VALUES
(1, 'Muhammad Handharbeni', 'mhandharbeni@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, 'f5769193ed-bx2-0186766941-s6cvw', '2017-06-05 22:33:30'),
(2, 'Muhammad Handharbenis', 'mhandharbeni@gmail.coms', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, '6897f11721-Ub2-1466047941-l2hlq', '2017-06-14 09:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `tools_value`
--

CREATE TABLE IF NOT EXISTS `tools_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tools_value`
--

INSERT INTO `tools_value` (`id`, `key`, `value`) VALUES
(1, 'km', '10000');

-- --------------------------------------------------------

--
-- Table structure for table `t_banner`
--

CREATE TABLE IF NOT EXISTS `t_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(5) DEFAULT '0',
  `keterangan` text NOT NULL,
  `gambar` text NOT NULL,
  `tanggal_waktu` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `t_order`
--

CREATE TABLE IF NOT EXISTS `t_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `t_order`
--

INSERT INTO `t_order` (`id`, `id_order`, `id_menu`, `jumlah`, `harga`, `total_harga`, `keterangan`) VALUES
(2, 1, 1, 5, 20000, 100000, NULL),
(3, 1, 2, 3, 35000, 105000, 'nothing'),
(4, 2, 1, 3, 20000, 60000, 'nothing'),
(5, 16, 7, 3, 20000, 60000, 'nothing'),
(6, 16, 8, 3, 17000, 51000, 'nothing'),
(7, 17, 7, 2, 20000, 40000, 'nothing'),
(8, 17, 8, 2, 17000, 34000, 'nothing'),
(9, 17, 2, 2, 35000, 70000, 'nothing'),
(10, 18, 3, 4, 5000, 20000, 'nothing'),
(11, 18, 4, 5, 7000, 35000, 'nothing'),
(12, 18, 5, 4, 10000, 40000, 'nothing'),
(13, 18, 6, 5, 15000, 75000, 'nothing'),
(14, 19, 2, 5, 35000, 175000, 'nothing'),
(15, 19, 8, 5, 17000, 85000, 'nothing'),
(16, 20, 2, 5, 35000, 175000, 'nothing'),
(17, 20, 1, 4, 20000, 80000, 'nothing'),
(18, 21, 1, 5, 20000, 100000, 'nothing'),
(19, 21, 8, 4, 17000, 68000, 'nothing'),
(20, 22, 2, 5, 35000, 175000, 'nothing'),
(21, 22, 8, 6, 17000, 102000, 'nothing'),
(22, 23, 1, 5, 20000, 100000, 'nothing'),
(23, 23, 8, 2, 17000, 34000, 'nothing'),
(24, 24, 1, 38, 20000, 760000, 'nothing'),
(25, 24, 7, 12, 20000, 240000, 'nothing'),
(26, 24, 2, 6, 35000, 210000, 'nothing'),
(27, 25, 7, 5, 20000, 100000, 'nothing'),
(28, 26, 3, 6, 5000, 30000, 'nothing'),
(29, 27, 3, 6, 5000, 30000, 'nothing'),
(30, 28, 1, 2, 20000, 40000, 'nothing'),
(31, 28, 2, 2, 35000, 70000, 'nothing'),
(32, 28, 7, 2, 20000, 40000, 'nothing'),
(33, 29, 1, 9, 20000, 180000, 'nothing'),
(34, 30, 1, 1, 20000, 20000, 'nothing'),
(35, 30, 2, 1, 35000, 35000, 'nothing'),
(36, 30, 8, 1, 17000, 17000, 'nothing'),
(37, 30, 7, 1, 20000, 20000, 'nothing');

-- --------------------------------------------------------

--
-- Table structure for table `t_tracking`
--

CREATE TABLE IF NOT EXISTS `t_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurir` int(11) DEFAULT NULL,
  `latitude` varchar(150) DEFAULT 'nothing',
  `longitude` varchar(150) DEFAULT 'nothing',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `t_tracking`
--

INSERT INTO `t_tracking` (`id`, `id_kurir`, `latitude`, `longitude`) VALUES
(1, 1, 'nothing', 'nothing');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_outlet`
--
ALTER TABLE `m_outlet`
  ADD CONSTRAINT `FK_m_outlet_m_resto` FOREIGN KEY (`id_resto`) REFERENCES `m_resto` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

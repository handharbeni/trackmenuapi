-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.55-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for burger_tahu
CREATE DATABASE IF NOT EXISTS `burger_tahu` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `burger_tahu`;

-- Dumping structure for table burger_tahu.m_admin
CREATE TABLE IF NOT EXISTS `m_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `key` varchar(250) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_admin: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_admin` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_admin` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_kurir
CREATE TABLE IF NOT EXISTS `m_kurir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_kurir: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_kurir` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_kurir` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_menu
CREATE TABLE IF NOT EXISTS `m_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
  `harga` varchar(50) DEFAULT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_menu: ~8 rows (approximately)
/*!40000 ALTER TABLE `m_menu` DISABLE KEYS */;
INSERT INTO `m_menu` (`id`, `nama`, `gambar`, `harga`, `kategori`, `sha`) VALUES
	(1, 'TEST', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan', NULL),
	(2, 'Onde Onde', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '35000', 'Makanan', NULL),
	(3, 'Es Teh', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '5000', 'Minuman', NULL),
	(4, 'Es Jeruk', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '7000', 'Minuman', NULL),
	(5, 'Es Dawet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '10000', 'Minuman', NULL),
	(6, 'Es Cendol', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '15000', 'Minuman', NULL),
	(7, 'Nasi Goreng', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan', NULL),
	(8, 'Nasi Liwet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '17000', 'Makanan', NULL);
/*!40000 ALTER TABLE `m_menu` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_order
CREATE TABLE IF NOT EXISTS `m_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_kurir` int(11) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `tanggal_waktu` datetime DEFAULT '0000-00-00 00:00:00',
  `status` int(11) DEFAULT '1' COMMENT '1:new order, 2:accept by kurir, 3:current pengiriman, 4:pengiriman selese, 5:cancel by admin or user',
  `keterangan` text,
  `delivery_fee` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_order: ~30 rows (approximately)
/*!40000 ALTER TABLE `m_order` DISABLE KEYS */;
INSERT INTO `m_order` (`id`, `id_user`, `id_kurir`, `alamat`, `latitude`, `longitude`, `tanggal_waktu`, `status`, `keterangan`, `delivery_fee`) VALUES
	(1, 1, 0, NULL, NULL, NULL, '2017-06-05 23:58:09', 1, NULL, NULL),
	(2, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:39', 1, NULL, NULL),
	(3, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:54', 1, NULL, NULL),
	(4, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:57', 1, NULL, NULL),
	(5, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:58', 1, NULL, NULL),
	(6, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:58', 1, NULL, NULL),
	(7, 1, 0, NULL, NULL, NULL, '2017-06-06 00:08:45', 1, NULL, NULL),
	(8, 1, 0, NULL, NULL, NULL, '2017-06-18 23:52:33', 1, NULL, NULL),
	(9, 1, 0, 'Jalan J.A. Suprapto 1C No.241', '-7.965931485933775', '112.63204883784056', '2017-07-10 20:48:52', 1, NULL, NULL),
	(10, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-10 20:49:50', 1, NULL, NULL),
	(11, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-11 23:37:46', 1, 'nothing', '50000'),
	(12, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-11 23:40:18', 1, 'nothing', '50000'),
	(13, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-11 23:56:10', 1, 'test', '50000'),
	(14, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-11 23:58:04', 1, 'nothing', '50000'),
	(15, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-11 23:58:58', 1, 'nothing', '50000'),
	(16, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 00:00:57', 1, 'nothing', '50000'),
	(17, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 00:02:02', 1, 'nothing', '50000'),
	(18, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 00:43:42', 1, 'nothing', '50000'),
	(19, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 00:48:15', 1, 'nothing', '50000'),
	(20, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 01:05:25', 1, 'nothing', '50000'),
	(21, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 01:05:57', 1, 'nothing', '50000'),
	(22, 1, 0, 'Jalan Jaksa Agung Suprapto No.51', '-7.969398310596278', '112.63182755559681', '2017-07-12 01:06:13', 1, 'nothing', '50000'),
	(23, 1, 0, 'Jalan Jaksa Agung Suprapto No.59', '-7.966829655662079', '112.63291954994203', '2017-07-12 12:11:28', 1, 'nothing', '50000'),
	(24, 1, 0, 'Jalan Lawu No.12', '-7.971189984354405', '112.62377690523864', '2017-07-12 15:23:58', 1, 'nothing', '40000'),
	(25, 1, 0, 'Jalan Lawu No.12', '-7.971189984354405', '112.62377690523864', '2017-07-12 15:26:13', 1, 'nothing', '40000'),
	(26, 1, 0, 'Jalan Kolonel Sugiono No.339-343', '-8.009258870260487', '112.62974347919226', '2017-07-12 17:54:38', 1, 'nothing', '90000'),
	(27, 1, 0, 'Jalan Kolonel Sugiono No.339-343', '-8.009258870260487', '112.62974347919226', '2017-07-12 17:58:33', 1, 'nothing', '90000'),
	(28, 1, 0, 'Jalan Raya Gadang No.35', '-8.011161258465417', '112.62892238795759', '2017-07-12 20:49:25', 1, 'nothing', '90000'),
	(29, 1, 0, 'Jalan Raya Gadang No.35', '-8.011161258465417', '112.62892238795759', '2017-07-12 20:52:09', 1, 'nothing', '90000'),
	(30, 1, 0, 'Jalan Puncak Borobudur No.6510', '-7.9359448', '112.6245207', '2017-07-17 14:57:10', 1, 'nothing', '10000');
/*!40000 ALTER TABLE `m_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_outlet
CREATE TABLE IF NOT EXISTS `m_outlet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) DEFAULT NULL,
  `outlet` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `long` varchar(50) DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_outlet_m_resto` (`id_resto`),
  CONSTRAINT `FK_m_outlet_m_resto` FOREIGN KEY (`id_resto`) REFERENCES `m_resto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_outlet: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_outlet` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_outlet` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_resto
CREATE TABLE IF NOT EXISTS `m_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_resto: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_resto` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_resto` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_user
CREATE TABLE IF NOT EXISTS `m_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `key` varchar(150) DEFAULT NULL,
  `tanggal_buat` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_user: ~2 rows (approximately)
/*!40000 ALTER TABLE `m_user` DISABLE KEYS */;
INSERT INTO `m_user` (`id`, `nama`, `email`, `password`, `alamat`, `location`, `key`, `tanggal_buat`) VALUES
	(1, 'Muhammad Handharbeni', 'mhandharbeni@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, 'f5769193ed-bx2-0186766941-s6cvw', '2017-06-05 22:33:30'),
	(2, 'Muhammad Handharbenis', 'mhandharbeni@gmail.coms', '202cb962ac59075b964b07152d234b70', NULL, NULL, '6897f11721-Ub2-1466047941-l2hlq', '2017-06-14 09:17:21');
/*!40000 ALTER TABLE `m_user` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.tools_value
CREATE TABLE IF NOT EXISTS `tools_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.tools_value: ~1 rows (approximately)
/*!40000 ALTER TABLE `tools_value` DISABLE KEYS */;
INSERT INTO `tools_value` (`id`, `key`, `value`) VALUES
	(1, 'km', '10000');
/*!40000 ALTER TABLE `tools_value` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_order
CREATE TABLE IF NOT EXISTS `t_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_order: ~36 rows (approximately)
/*!40000 ALTER TABLE `t_order` DISABLE KEYS */;
INSERT INTO `t_order` (`id`, `id_order`, `id_menu`, `jumlah`, `harga`, `total_harga`, `keterangan`) VALUES
	(2, 7, 1, 5, 20000, 100000, NULL),
	(3, 16, 2, 3, 35000, 105000, 'nothing'),
	(4, 16, 1, 3, 20000, 60000, 'nothing'),
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
/*!40000 ALTER TABLE `t_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_tracking
CREATE TABLE IF NOT EXISTS `t_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurir` int(11) DEFAULT NULL,
  `latitude` varchar(150) DEFAULT 'nothing',
  `longitude` varchar(150) DEFAULT 'nothing',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_tracking: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_tracking` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

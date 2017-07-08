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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_menu: ~8 rows (approximately)
/*!40000 ALTER TABLE `m_menu` DISABLE KEYS */;
INSERT INTO `m_menu` (`id`, `nama`, `gambar`, `harga`, `kategori`) VALUES
	(1, 'TEST', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan'),
	(2, 'Onde Onde', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '35000', 'Makanan'),
	(3, 'Es Teh', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '5000', 'Minuman'),
	(4, 'Es Jeruk', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '7000', 'Minuman'),
	(5, 'Es Dawet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '10000', 'Minuman'),
	(6, 'Es Cendol', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '15000', 'Minuman'),
	(7, 'Nasi Goreng', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '20000', 'Makanan'),
	(8, 'Nasi Liwet', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '17000', 'Makanan');
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_order: ~8 rows (approximately)
/*!40000 ALTER TABLE `m_order` DISABLE KEYS */;
INSERT INTO `m_order` (`id`, `id_user`, `id_kurir`, `alamat`, `latitude`, `longitude`, `tanggal_waktu`, `status`, `keterangan`) VALUES
	(1, 1, 0, NULL, NULL, NULL, '2017-06-05 23:58:09', 1, NULL),
	(2, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:39', 1, NULL),
	(3, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:54', 1, NULL),
	(4, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:57', 1, NULL),
	(5, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:58', 1, NULL),
	(6, 1, 0, NULL, NULL, NULL, '2017-06-06 00:00:58', 1, NULL),
	(7, 1, 0, NULL, NULL, NULL, '2017-06-06 00:08:45', 1, NULL),
	(8, 1, 0, NULL, NULL, NULL, '2017-06-18 23:52:33', 1, NULL);
/*!40000 ALTER TABLE `m_order` ENABLE KEYS */;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.tools_value: ~0 rows (approximately)
/*!40000 ALTER TABLE `tools_value` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_order: ~1 rows (approximately)
/*!40000 ALTER TABLE `t_order` DISABLE KEYS */;
INSERT INTO `t_order` (`id`, `id_order`, `id_menu`, `jumlah`, `harga`, `total_harga`, `keterangan`) VALUES
	(2, 7, 1, 5, 20000, 100000, NULL);
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

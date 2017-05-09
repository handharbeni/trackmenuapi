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
  `harga` varchar(50) DEFAULT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_menu: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_menu` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_order
CREATE TABLE IF NOT EXISTS `m_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_kurir` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT '0000-00-00',
  `status` int(11) DEFAULT '1' COMMENT '1:new order, 2:accept by kurir, 3:current pengiriman, 4:pengiriman selese, 5:cancel by admin or user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_order: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_user
CREATE TABLE IF NOT EXISTS `m_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `key` varchar(150) DEFAULT NULL,
  `tanggal_buat` date DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_user: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_user` DISABLE KEYS */;
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_order: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_tracking
CREATE TABLE IF NOT EXISTS `t_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurir` int(11) DEFAULT NULL,
  `latitude` varchar(150) DEFAULT NULL,
  `longitude` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_tracking: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_tracking` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

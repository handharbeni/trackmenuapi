-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.57-0ubuntu0.14.04.1 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for burger_tahu
DROP DATABASE IF EXISTS `burger_tahu`;
CREATE DATABASE IF NOT EXISTS `burger_tahu` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `burger_tahu`;

-- Dumping structure for table burger_tahu.m_admin
DROP TABLE IF EXISTS `m_admin`;
CREATE TABLE IF NOT EXISTS `m_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_outlet` int(11) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `key` varchar(250) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_admin: ~5 rows (approximately)
/*!40000 ALTER TABLE `m_admin` DISABLE KEYS */;
INSERT INTO `m_admin` (`id`, `id_outlet`, `username`, `password`, `key`, `tanggal`) VALUES
	(1, 0, 'superuser', '21232f297a57a5a743894a0e4a801fc3', 'superuserkey', '2017-08-26');
/*!40000 ALTER TABLE `m_admin` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_kurir
DROP TABLE IF EXISTS `m_kurir`;
CREATE TABLE IF NOT EXISTS `m_kurir` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `foto_profil` text,
  `no_hp` varchar(15) DEFAULT NULL,
  `no_plat` varchar(20) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_kurir: ~4 rows (approximately)
/*!40000 ALTER TABLE `m_kurir` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_kurir` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_menu
DROP TABLE IF EXISTS `m_menu`;
CREATE TABLE IF NOT EXISTS `m_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
  `harga` varchar(50) DEFAULT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_menu: ~8 rows (approximately)
/*!40000 ALTER TABLE `m_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_menu` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_order
DROP TABLE IF EXISTS `m_order`;
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
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_order: ~3 rows (approximately)
/*!40000 ALTER TABLE `m_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_outlet
DROP TABLE IF EXISTS `m_outlet`;
CREATE TABLE IF NOT EXISTS `m_outlet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) DEFAULT NULL,
  `outlet` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `long` varchar(50) DEFAULT NULL,
  `tanggal_waktu` datetime DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  `deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_m_outlet_m_resto` (`id_resto`),
  CONSTRAINT `FK_m_outlet_m_resto` FOREIGN KEY (`id_resto`) REFERENCES `m_resto` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_outlet: ~4 rows (approximately)
/*!40000 ALTER TABLE `m_outlet` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_outlet` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_resto
DROP TABLE IF EXISTS `m_resto`;
CREATE TABLE IF NOT EXISTS `m_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resto` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_resto: ~1 rows (approximately)
/*!40000 ALTER TABLE `m_resto` DISABLE KEYS */;
INSERT INTO `m_resto` (`id`, `resto`) VALUES
	(1, 'Burger Tahu');
/*!40000 ALTER TABLE `m_resto` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_stok
DROP TABLE IF EXISTS `m_stok`;
CREATE TABLE IF NOT EXISTS `m_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_stok_m_menu` (`id_menu`),
  CONSTRAINT `FK_m_stok_m_menu` FOREIGN KEY (`id_menu`) REFERENCES `m_menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_stok: ~1 rows (approximately)
/*!40000 ALTER TABLE `m_stok` DISABLE KEYS */;
INSERT INTO `m_stok` (`id`, `id_menu`, `date_add`, `jumlah`) VALUES
	(1, 1, '2017-08-26 22:15:00', 3);
/*!40000 ALTER TABLE `m_stok` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_user
DROP TABLE IF EXISTS `m_user`;
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
  `blacklist` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_user: ~1 rows (approximately)
/*!40000 ALTER TABLE `m_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_user` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.tools_value
DROP TABLE IF EXISTS `tools_value`;
CREATE TABLE IF NOT EXISTS `tools_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.tools_value: ~1 rows (approximately)
/*!40000 ALTER TABLE `tools_value` DISABLE KEYS */;
INSERT INTO `tools_value` (`id`, `key`, `value`) VALUES
	(1, 'km', '6000');
/*!40000 ALTER TABLE `tools_value` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_banner
DROP TABLE IF EXISTS `t_banner`;
CREATE TABLE IF NOT EXISTS `t_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(5) DEFAULT '0',
  `nama` varchar(50) DEFAULT NULL,
  `keterangan` text NOT NULL,
  `gambar` text NOT NULL,
  `link` tinytext,
  `sha` tinytext,
  `added_by` varchar(50) DEFAULT NULL,
  `added_datetime` datetime DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `modified_datetime` datetime DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_banner: ~3 rows (approximately)
/*!40000 ALTER TABLE `t_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_banner` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_order
DROP TABLE IF EXISTS `t_order`;
CREATE TABLE IF NOT EXISTS `t_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `keterangan` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_order: ~4 rows (approximately)
/*!40000 ALTER TABLE `t_order` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_pemakaian_stok
DROP TABLE IF EXISTS `t_pemakaian_stok`;
CREATE TABLE IF NOT EXISTS `t_pemakaian_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_t_pemakaian_stok_m_order` (`id_order`),
  KEY `FK_t_pemakaian_stok_m_menu` (`id_menu`),
  CONSTRAINT `FK_t_pemakaian_stok_m_menu` FOREIGN KEY (`id_menu`) REFERENCES `m_menu` (`id`),
  CONSTRAINT `FK_t_pemakaian_stok_m_order` FOREIGN KEY (`id_order`) REFERENCES `m_order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_pemakaian_stok: ~2 rows (approximately)
/*!40000 ALTER TABLE `t_pemakaian_stok` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_pemakaian_stok` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_rating
DROP TABLE IF EXISTS `t_rating`;
CREATE TABLE IF NOT EXISTS `t_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) NOT NULL DEFAULT '0',
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_outlet` int(11) NOT NULL DEFAULT '0',
  `id_kurir` int(11) NOT NULL DEFAULT '0',
  `tipe` enum('MENU','OUTLET','KURIR') DEFAULT NULL,
  `rating` int(1) NOT NULL DEFAULT '0',
  `keterangan` text,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_rating: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_rating` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_rating` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_tracking
DROP TABLE IF EXISTS `t_tracking`;
CREATE TABLE IF NOT EXISTS `t_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurir` int(11) DEFAULT NULL,
  `latitude` varchar(150) DEFAULT 'nothing',
  `longitude` varchar(150) DEFAULT 'nothing',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_tracking: ~4 rows (approximately)
/*!40000 ALTER TABLE `t_tracking` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_tracking` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

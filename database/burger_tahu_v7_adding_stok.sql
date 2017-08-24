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
CREATE DATABASE IF NOT EXISTS `burger_tahu` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `burger_tahu`;

-- Dumping structure for table burger_tahu.m_admin
CREATE TABLE IF NOT EXISTS `m_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_outlet` int(11) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `key` varchar(250) DEFAULT NULL,
  `tanggal` date NOT NULL DEFAULT '0000-00-00',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_admin: ~4 rows (approximately)
/*!40000 ALTER TABLE `m_admin` DISABLE KEYS */;
INSERT INTO `m_admin` (`id`, `id_outlet`, `username`, `password`, `key`, `tanggal`, `deleted`) VALUES
	(1, 0, 'superuser', '202cb962ac59075b964b07152d234b70', 'superuserkey', '2017-07-17', 0),
	(2, 1, 'outletsuhat', '202cb962ac59075b964b07152d234b70', 'superkeyoutletsuhat', '2017-07-17', 0),
	(3, 3, 'outletblimbing', '202cb962ac59075b964b07152d234b70', 'superkeyoutletblimbing', '2017-07-17', 0),
	(4, 2, 'outletpanjen', '202cb962ac59075b964b07152d234b70', 'superkeypanjen', '2017-07-17', 0);
/*!40000 ALTER TABLE `m_admin` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_kurir
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
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_kurir: ~1 rows (approximately)
/*!40000 ALTER TABLE `m_kurir` DISABLE KEYS */;
INSERT INTO `m_kurir` (`id`, `nama`, `username`, `password`, `foto_profil`, `no_hp`, `no_plat`, `key`, `tanggal`, `deleted`) VALUES
	(1, 'Kurir Satu', 'kurirsatu', '202cb962ac59075b964b07152d234b70', 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png', '08977997161', 'N 4605 BX', 'keykurirsatu', '0000-00-00', 0);
/*!40000 ALTER TABLE `m_kurir` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_menu
CREATE TABLE IF NOT EXISTS `m_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) NOT NULL DEFAULT '0',
  `nama` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'http://s3.amazonaws.com/37assets/svn/765-default-avatar.png',
  `harga` varchar(50) DEFAULT NULL,
  `kategori` enum('Makanan','Minuman') DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_menu_m_resto` (`id_resto`),
  CONSTRAINT `FK_m_menu_m_resto` FOREIGN KEY (`id_resto`) REFERENCES `m_resto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_menu: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_menu` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_order
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
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_order: ~7 rows (approximately)
/*!40000 ALTER TABLE `m_order` DISABLE KEYS */;
INSERT INTO `m_order` (`id`, `id_user`, `id_kurir`, `id_outlet`, `alamat`, `latitude`, `longitude`, `tanggal_waktu`, `status`, `keterangan`, `delivery_fee`, `sha`, `deleted`) VALUES
	(1, 1, 0, 1, 'puri cempaka putih', '-8.011161258465417', '112.62892238795759', '2017-06-05 23:58:09', 4, 'nothing', '90000', 'afgadfg', 0),
	(2, 1, 0, 1, 'puri cempaka putih', '-8.011161258465417', '112.62892238795759', '2017-06-05 23:58:09', 4, 'nothing', '10000', 'kjhgdfga', 0),
	(29, 2, 0, 1, 'Jalan Raya Gadang No.35', '-8.011161258465417', '112.62892238795759', '2017-07-12 20:52:09', 1, 'nothing', '90000', 'adfgasd', 0),
	(30, 2, 0, NULL, 'Jalan Puncak Borobudur No.6510', '-7.9359448', '112.6245207', '2017-07-17 14:57:10', 1, 'nothing', '10000', 'asdgfasdf', 0),
	(31, 1, 0, 1, 'puri', '-8.011161258465417', '112.62892238795759', '2017-08-12 00:06:46', 5, 'nothing', '6000', '58956e3aasdfba-z2P-0164742051-jiqv1', 0),
	(32, 1, 0, 2, 'Gadang, Sukun', '-8.0164648', '112.6273143', '2017-08-12 00:26:20', 5, 'nothing', '6000', '6aef01ebgfda85-TGp-5452742051-q7yiz', 0),
	(33, 1, 0, 2, 'Jl. Merdeka Timur No.2, Kiduldalem, Klojen, Kota Malang, Jawa Timur 65119, Indonesia', '-7.982692499999991', '112.63089453125', '2017-08-13 00:52:23', 1, 'nothing', '6000', 'dfg', 0);
/*!40000 ALTER TABLE `m_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_outlet
CREATE TABLE IF NOT EXISTS `m_outlet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_resto` int(11) DEFAULT NULL,
  `outlet` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `lat` varchar(50) DEFAULT NULL,
  `long` varchar(50) DEFAULT NULL,
  `tanggal_waktu` datetime DEFAULT NULL,
  `sha` varchar(50) DEFAULT NULL,
  `deleted` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_outlet_m_resto` (`id_resto`),
  CONSTRAINT `FK_m_outlet_m_resto` FOREIGN KEY (`id_resto`) REFERENCES `m_resto` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_outlet: ~3 rows (approximately)
/*!40000 ALTER TABLE `m_outlet` DISABLE KEYS */;
INSERT INTO `m_outlet` (`id`, `id_resto`, `outlet`, `alamat`, `lat`, `long`, `tanggal_waktu`, `sha`, `deleted`) VALUES
	(1, 1, 'Burger Tahu Malang Suhat 2', 'Burger Tahu Malang Suhat 2', '-7.9414821', '112.6208363', '2017-07-21 13:56:56', '123241', 0),
	(2, 1, 'Burger Tahu Malang Outlet 007 Unikama', 'Burger Tahu Malang Outlet 007 Unikama', '-8.0072883', '112.6167771', '2017-07-21 13:56:58', '21', 0),
	(3, 1, 'Burger Tahu Malang Outlet 005 Sigura-gura', 'Burger Tahu Malang Outlet 005 Sigura-gura', '-7.9566922', '112.6034991', '2017-07-21 13:56:59', '312', 0);
/*!40000 ALTER TABLE `m_outlet` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_resto
CREATE TABLE IF NOT EXISTS `m_resto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resto` varchar(50) DEFAULT NULL,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_resto: ~1 rows (approximately)
/*!40000 ALTER TABLE `m_resto` DISABLE KEYS */;
INSERT INTO `m_resto` (`id`, `resto`, `deleted`) VALUES
	(1, 'Burger Tahu', 0);
/*!40000 ALTER TABLE `m_resto` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_stok
CREATE TABLE IF NOT EXISTS `m_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_menu` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_m_stok_m_menu` (`id_menu`),
  CONSTRAINT `FK_m_stok_m_menu` FOREIGN KEY (`id_menu`) REFERENCES `m_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_stok: ~0 rows (approximately)
/*!40000 ALTER TABLE `m_stok` DISABLE KEYS */;
/*!40000 ALTER TABLE `m_stok` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.m_user
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.m_user: ~2 rows (approximately)
/*!40000 ALTER TABLE `m_user` DISABLE KEYS */;
INSERT INTO `m_user` (`id`, `nama`, `email`, `password`, `no_hp`, `alamat`, `location`, `key`, `tanggal_buat`, `blacklist`) VALUES
	(1, 'Muhammad Handharbeni', 'mhandharbeni@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, 'f5769193ed-bx2-0186766941-s6cvw', '2017-06-05 22:33:30', 0),
	(2, 'Muhammad Handharbenis', 'mhandharbeni@gmail.coms', '202cb962ac59075b964b07152d234b70', NULL, NULL, NULL, '6897f11721-Ub2-1466047941-l2hlq', '2017-06-14 09:17:21', 0);
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
	(1, 'km', '2000');
/*!40000 ALTER TABLE `tools_value` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_banner
CREATE TABLE IF NOT EXISTS `t_banner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(5) DEFAULT '0',
  `keterangan` text NOT NULL,
  `gambar` text NOT NULL,
  `tanggal_waktu` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_banner: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_banner` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_banner` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_order: ~39 rows (approximately)
/*!40000 ALTER TABLE `t_order` DISABLE KEYS */;
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
	(37, 30, 7, 1, 20000, 20000, 'nothing'),
	(38, 31, 1, 5, 20000, 100000, 'nothing'),
	(39, 32, 8, 10, 20000, 200000, 'nothing'),
	(40, 33, 5, 2, 10000, 20000, 'nothing');
/*!40000 ALTER TABLE `t_order` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_pemakaian_stok
CREATE TABLE IF NOT EXISTS `t_pemakaian_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_order` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_t_pemakaian_stok_m_order` (`id_order`),
  KEY `FK_t_pemakaian_stok_m_menu` (`id_menu`),
  CONSTRAINT `FK_t_pemakaian_stok_m_order` FOREIGN KEY (`id_order`) REFERENCES `m_order` (`id`),
  CONSTRAINT `FK_t_pemakaian_stok_m_menu` FOREIGN KEY (`id_menu`) REFERENCES `m_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_pemakaian_stok: ~0 rows (approximately)
/*!40000 ALTER TABLE `t_pemakaian_stok` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_pemakaian_stok` ENABLE KEYS */;

-- Dumping structure for table burger_tahu.t_tracking
CREATE TABLE IF NOT EXISTS `t_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kurir` int(11) DEFAULT NULL,
  `latitude` varchar(150) DEFAULT 'nothing',
  `longitude` varchar(150) DEFAULT 'nothing',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table burger_tahu.t_tracking: ~1 rows (approximately)
/*!40000 ALTER TABLE `t_tracking` DISABLE KEYS */;
INSERT INTO `t_tracking` (`id`, `id_kurir`, `latitude`, `longitude`) VALUES
	(1, 1, '-8.01112', '112.62892238795759');
/*!40000 ALTER TABLE `t_tracking` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

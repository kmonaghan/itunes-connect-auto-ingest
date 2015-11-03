# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.35-0ubuntu0.12.10.2)
# Database: itunes_test
# Generation Time: 2014-09-19 14:03:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table daily_raw
# ------------------------------------------------------------

DROP TABLE IF EXISTS `daily_raw`;

CREATE TABLE `daily_raw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `provider` char(5) DEFAULT 'APPLE',
  `provider_country` char(2) DEFAULT 'US',
  `sku` varchar(100) DEFAULT NULL,
  `developer` varchar(4000) DEFAULT NULL,
  `title` varchar(600) DEFAULT NULL,
  `version` varchar(100) DEFAULT NULL,
  `product_type_identifier` varchar(20) DEFAULT NULL,
  `units` decimal(18,2) DEFAULT NULL,
  `developer_proceeds` decimal(18,2) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `customer_currency` char(3) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL,
  `currency_proceeds` char(3) DEFAULT NULL,
  `apple_identifier` decimal(18,0) DEFAULT NULL,
  `customer_price` decimal(18,2) DEFAULT NULL,
  `promo_code` varchar(10) DEFAULT NULL,
  `parent_identifier` varchar(100) DEFAULT NULL,
  `subscription` varchar(10) DEFAULT NULL,
  `period` varchar(30) DEFAULT NULL,
  `category` varchar(30) DEFAULT NULL,
  `cmb` varchar(30) DEFAULT NULL,
  `device` varchar(12) DEFAULT NULL,
  `supported_platforms` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

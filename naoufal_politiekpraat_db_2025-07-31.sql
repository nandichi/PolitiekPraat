# ************************************************************
# Sequel Ace SQL dump
# Version 20080
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: localhost (MySQL 8.0.42)
# Database: naoufal_politiekpraat_db
# Generation Time: 2025-07-31 6:54:28 PM +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table political_parties
# ------------------------------------------------------------

DROP TABLE IF EXISTS `political_parties`;

CREATE TABLE `political_parties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `party_key` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `leader` varchar(255) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `leader_photo` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `leader_info` text NOT NULL,
  `standpoints` json NOT NULL,
  `current_seats` int NOT NULL,
  `polling` json NOT NULL,
  `perspectives` json NOT NULL,
  `color` varchar(7) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `party_key` (`party_key`),
  KEY `idx_active` (`is_active`),
  KEY `idx_party_key` (`party_key`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: id_cards
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `card_types`
--

DROP TABLE IF EXISTS `card_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `name` char(50) NOT NULL,
  `template_file` varchar(100) NOT NULL,
  `card_images_generator` varchar(200) NOT NULL,
  `folder` varchar(200) NOT NULL,
  `preview_width` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card_types`
--

LOCK TABLES `card_types` WRITE;
/*!40000 ALTER TABLE `card_types` DISABLE KEYS */;
INSERT INTO `card_types` VALUES (1,'staff','Staff','/var/www/html/assets/card_template/card_template.fodt','/var/www/html/staff/assets/scripts/card_image_generator','staff',500),(2,'patron','Patron','/var/www/html/assets/card_template_new.fodt','/var/www/html/patron/assets/scripts/card_image_generator','patron',860);
/*!40000 ALTER TABLE `card_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `image_name` varchar(50) NOT NULL,
  `image_data` mediumblob NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbr` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `wallpaper_image` varchar(50) NOT NULL,
  `printer` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'wpl','Willard Library','willard_wallpaper.jpg','Zebra-ZXP32-USB-Printer-Serial=Z3J152300276');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patron_records`
--

DROP TABLE IF EXISTS `patron_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patron_records` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `patron_id` varchar(14) NOT NULL DEFAULT '00000000000000',
  `patron_fname` varchar(50) NOT NULL,
  `patron_mname` varchar(50) DEFAULT NULL,
  `patron_lname` varchar(50) NOT NULL,
  `mugshot_dimensions` varchar(25) NOT NULL,
  `mugshot_image` mediumblob NOT NULL,
  `snapshot_image` mediumblob NOT NULL,
  `selected_width` int(11) NOT NULL,
  `selected_height` int(11) NOT NULL,
  `selected_x1` int(11) NOT NULL,
  `selected_x2` int(11) NOT NULL,
  `selected_y1` int(11) NOT NULL,
  `selected_y2` int(11) NOT NULL,
  `brightness` int(11) NOT NULL,
  `contrast` int(11) NOT NULL,
  `fotd` mediumblob NOT NULL,
  `pdf` mediumblob NOT NULL,
  `front_image_dimensions` varchar(25) NOT NULL,
  `front_image` mediumblob NOT NULL,
  `back_image_dimensions` varchar(25) NOT NULL,
  `back_image` mediumblob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `station_ip` varchar(15) NOT NULL,
  `station_hostname` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7605 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `staff_records`
--

DROP TABLE IF EXISTS `staff_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_records` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `line1` varchar(50) NOT NULL,
  `line2` varchar(50) NOT NULL,
  `mugshot_dimensions` varchar(25) NOT NULL,
  `mugshot_image` mediumblob NOT NULL,
  `fotd` mediumblob NOT NULL,
  `front_image_dimensions` varchar(25) NOT NULL,
  `front_image` mediumblob NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `station_ip` varchar(15) NOT NULL,
  `station_hostname` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_records`
--

LOCK TABLES `staff_records` WRITE;
/*!40000 ALTER TABLE `staff_records` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userName` char(50) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `userPass` char(50) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `isAdmin` tinyint(2) NOT NULL DEFAULT '-1',
  `userGroup` int(10) unsigned DEFAULT '1',
  `sessionID` char(50) DEFAULT NULL,
  `lastLog` datetime DEFAULT NULL,
  `userRemark` char(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `userName` (`userName`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='Created by the AdminPro Class MySQL Setup ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variables` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `variable` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `variable` (`variable`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variables`
--

LOCK TABLES `variables` WRITE;
/*!40000 ALTER TABLE `variables` DISABLE KEYS */;
INSERT INTO `variables` VALUES (1,'current_id','00000000000000'),(2,'card_height','638'),(3,'card_width','1013'),(4,'LnameFontSize','40'),(5,'Lname_x','350'),(6,'FnameFontSize','24'),(7,'fname_x','380'),(8,'mugshot_x','700'),(9,'mugshot_y','275'),(10,'mugshot_h','360'),(11,'mugshot_w','270'),(12,'BarcodeFontSize','20'),(13,'Barcode_text_x ','540'),(14,'barcode_center_x','330'),(15,'barcode_center_y','450'),(16,'barcode_height','100'),(17,'barcode_width','3'),(18,'barcode_angle','0'),(19,'barcode_type','code39'),(20,'front_logo_x','0'),(21,'front_logo_y','0'),(22,'front_logo_h','268'),(23,'front_logo_w','1013'),(24,'webpage_idle_timeout','5');
/*!40000 ALTER TABLE `variables` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-08 12:30:55

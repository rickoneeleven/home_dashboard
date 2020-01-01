CREATE DATABASE  IF NOT EXISTS `home` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `home`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: home
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Table structure for table `darksky_daily`
--

DROP TABLE IF EXISTS `darksky_daily`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `darksky_daily` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated` datetime DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL,
  `temperatureMax` tinyint(4) DEFAULT NULL,
  `temperatureMin` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `darksky_daily`
--

LOCK TABLES `darksky_daily` WRITE;
/*!40000 ALTER TABLE `darksky_daily` DISABLE KEYS */;
/*!40000 ALTER TABLE `darksky_daily` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `darksky_summary`
--

DROP TABLE IF EXISTS `darksky_summary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `darksky_summary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summary_min` varchar(200) NOT NULL,
  `summary_hour` varchar(200) DEFAULT NULL,
  `summary_day` varchar(200) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `timestamp` bigint(20) DEFAULT NULL,
  `current_temp` tinyint(4) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `icon` varchar(30) DEFAULT NULL,
  `icon_coming` varchar(30) DEFAULT NULL,
  `precipIntensity` varchar(7) DEFAULT NULL,
  `precipProbability` varchar(7) DEFAULT NULL,
  `windGust` varchar(7) DEFAULT NULL,
  `windGust_coming` varchar(7) DEFAULT NULL,
  `precipProbability_coming` varchar(7) DEFAULT NULL,
  `precipIntensity_coming` varchar(7) DEFAULT NULL,
  `temp_coming` varchar(7) DEFAULT NULL,
  `apparentTemp` varchar(7) DEFAULT NULL,
  `apparentTemp_coming` varchar(7) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `darksky_summary`
--

LOCK TABLES `darksky_summary` WRITE;
/*!40000 ALTER TABLE `darksky_summary` DISABLE KEYS */;
/*!40000 ALTER TABLE `darksky_summary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `other`
--

DROP TABLE IF EXISTS `other`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `other` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  `value` varchar(60) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `other`
--

LOCK TABLES `other` WRITE;
/*!40000 ALTER TABLE `other` DISABLE KEYS */;
INSERT INTO `other` VALUES (1,'themed_day','0','2018-07-20 01:12:01'),(2,'magic_happens','0','2019-12-31 07:25:52'),(3,'upcoming_magic_manual','0','2019-12-06 01:11:01');
/*!40000 ALTER TABLE `other` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-31 17:48:18

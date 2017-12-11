-- MySQL dump 10.13  Distrib 5.5.57, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bitrobot
-- ------------------------------------------------------
-- Server version	5.5.57-0ubuntu0.14.04.1

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
-- Table structure for table `bitrobot_admin`
--

DROP TABLE IF EXISTS `bitrobot_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitrobot_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `nickname` text NOT NULL,
  `telephone` text NOT NULL,
  `email` text NOT NULL,
  `groups` text NOT NULL,
  `comments` text NOT NULL,
  `create_time` text NOT NULL,
  `active_time` text NOT NULL,
  `last_login` text NOT NULL,
  `token` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitrobot_admin`
--

LOCK TABLES `bitrobot_admin` WRITE;
/*!40000 ALTER TABLE `bitrobot_admin` DISABLE KEYS */;
INSERT INTO `bitrobot_admin` VALUES (1,'root','root','root','','','','','','','','',0);
/*!40000 ALTER TABLE `bitrobot_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitrobot_files`
--

DROP TABLE IF EXISTS `bitrobot_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitrobot_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` text NOT NULL,
  `path` text NOT NULL,
  `title` text NOT NULL,
  `comments` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitrobot_files`
--

LOCK TABLES `bitrobot_files` WRITE;
/*!40000 ALTER TABLE `bitrobot_files` DISABLE KEYS */;
INSERT INTO `bitrobot_files` VALUES (1,'aaasdfd','201712/6c5d1044e1288f68fa277c7f58219baf.bin','sdcvcv','cccca',0),(2,'aaasdfd','','1231','ccccaa',1);
/*!40000 ALTER TABLE `bitrobot_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitrobot_user`
--

DROP TABLE IF EXISTS `bitrobot_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitrobot_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `nickname` text NOT NULL,
  `telephone` text NOT NULL,
  `email` text NOT NULL,
  `groups` text NOT NULL,
  `comments` text NOT NULL,
  `create_time` text NOT NULL,
  `active_time` text NOT NULL,
  `last_login` text NOT NULL,
  `token` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitrobot_user`
--

LOCK TABLES `bitrobot_user` WRITE;
/*!40000 ALTER TABLE `bitrobot_user` DISABLE KEYS */;
INSERT INTO `bitrobot_user` VALUES (4,'ada','ada','asdfsssss','183','f','','aa','1512304045','','','',0),(5,'adaa','adaa','','dd','','','','1512304143','','','',1),(6,'ada','ada','','','','','','1512305855','','','',1);
/*!40000 ALTER TABLE `bitrobot_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-11 20:55:23

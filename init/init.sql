-- MySQL dump 10.13  Distrib 5.5.52, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: jworder
-- ------------------------------------------------------
-- Server version	5.5.52-0ubuntu0.14.04.1

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
-- Table structure for table `jw_category`
--

DROP TABLE IF EXISTS `jw_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_category`
--

LOCK TABLES `jw_category` WRITE;
/*!40000 ALTER TABLE `jw_category` DISABLE KEYS */;
INSERT INTO `jw_category` VALUES (1,0,'原料'),(2,0,'产品'),(3,0,'客户'),(4,0,'供应商'),(5,0,'业务员'),(6,0,'仓库');
/*!40000 ALTER TABLE `jw_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_customer`
--

DROP TABLE IF EXISTS `jw_customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `category` int(11) NOT NULL,
  `telephone` text NOT NULL,
  `email` text NOT NULL,
  `province` text NOT NULL,
  `city` text NOT NULL,
  `district` text NOT NULL,
  `address` text NOT NULL,
  `salesman` int(11) NOT NULL,
  `comments` text NOT NULL,
  `wechat_userid` int(11) NOT NULL,
  `wechat_notify` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_customer`
--

LOCK TABLES `jw_customer` WRITE;
/*!40000 ALTER TABLE `jw_customer` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_customer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_depot`
--

DROP TABLE IF EXISTS `jw_depot`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_depot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `address` text NOT NULL,
  `manager` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `comments` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_depot`
--

LOCK TABLES `jw_depot` WRITE;
/*!40000 ALTER TABLE `jw_depot` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_depot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_group`
--

DROP TABLE IF EXISTS `jw_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `permissions` text NOT NULL,
  ` status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_group`
--

LOCK TABLES `jw_group` WRITE;
/*!40000 ALTER TABLE `jw_group` DISABLE KEYS */;
INSERT INTO `jw_group` VALUES (1,'admin','user,salesorder,purchaserefund,salesanalysing,balance,purchaseorder,group,currentdepot,product,setting,productorder,productordermodify,materialorder,material,customer,vendor,salesman,depot,materialordermodify,reportsheet,category,customerprice,wechatuser,wechatnotify,wechatadmin',0);
/*!40000 ALTER TABLE `jw_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_material`
--

DROP TABLE IF EXISTS `jw_material`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `unit` text NOT NULL,
  `standard` text NOT NULL,
  `barcode` text NOT NULL,
  `qrcode` text NOT NULL,
  `serial` text NOT NULL,
  `vendor` text NOT NULL,
  `mode` text NOT NULL,
  `comments` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_material`
--

LOCK TABLES `jw_material` WRITE;
/*!40000 ALTER TABLE `jw_material` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_material` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_materialorder`
--

DROP TABLE IF EXISTS `jw_materialorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_materialorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` text NOT NULL,
  `depot1` int(11) NOT NULL,
  `depot2` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `operator` int(11) NOT NULL,
  `operatorname` text NOT NULL,
  `comments` text NOT NULL,
  `ordertime` text NOT NULL,
  `ensuretime` text NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `totalprice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_materialorder`
--

LOCK TABLES `jw_materialorder` WRITE;
/*!40000 ALTER TABLE `jw_materialorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_materialorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_materialorderrecord`
--

DROP TABLE IF EXISTS `jw_materialorderrecord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_materialorderrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `materialid` int(11) NOT NULL,
  `serial` text NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `unit` text NOT NULL,
  `standard` text NOT NULL,
  `count` int(11) NOT NULL,
  `totalprice` text NOT NULL,
  `comments` text NOT NULL,
  `mode` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_materialorderrecord`
--

LOCK TABLES `jw_materialorderrecord` WRITE;
/*!40000 ALTER TABLE `jw_materialorderrecord` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_materialorderrecord` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_notice`
--

DROP TABLE IF EXISTS `jw_notice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `wid` int(11) NOT NULL,
  `notice` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`,`wid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_notice`
--

LOCK TABLES `jw_notice` WRITE;
/*!40000 ALTER TABLE `jw_notice` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_notice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_price`
--

DROP TABLE IF EXISTS `jw_price`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `price` text NOT NULL,
  `refresh_time` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`product`,`customer`),
  KEY `cid` (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_price`
--

LOCK TABLES `jw_price` WRITE;
/*!40000 ALTER TABLE `jw_price` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_price` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_product`
--

DROP TABLE IF EXISTS `jw_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `unit` text NOT NULL,
  `standard` text NOT NULL,
  `barcode` text NOT NULL,
  `qrcode` text NOT NULL,
  `serial` text NOT NULL,
  `vendor` text NOT NULL,
  `mode` text NOT NULL,
  `comments` text NOT NULL,
  `price` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_product`
--

LOCK TABLES `jw_product` WRITE;
/*!40000 ALTER TABLE `jw_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_productorder`
--

DROP TABLE IF EXISTS `jw_productorder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_productorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serial` text NOT NULL,
  `depot1` int(11) NOT NULL,
  `depot2` int(11) NOT NULL,
  `customer` int(11) NOT NULL,
  `operator` int(11) NOT NULL,
  `operatorname` text NOT NULL,
  `comments` text NOT NULL,
  `ordertime` text NOT NULL,
  `ensuretime` text NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `totalprice` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_productorder`
--

LOCK TABLES `jw_productorder` WRITE;
/*!40000 ALTER TABLE `jw_productorder` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_productorder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_productorderrecord`
--

DROP TABLE IF EXISTS `jw_productorderrecord`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_productorderrecord` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `serial` text NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `unit` text NOT NULL,
  `standard` text NOT NULL,
  `count` int(11) NOT NULL,
  `largess` int(11) NOT NULL,
  `finalprice` text NOT NULL,
  `comments` text NOT NULL,
  `mode` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_productorderrecord`
--

LOCK TABLES `jw_productorderrecord` WRITE;
/*!40000 ALTER TABLE `jw_productorderrecord` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_productorderrecord` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_salesman`
--

DROP TABLE IF EXISTS `jw_salesman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_salesman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `telephone` text NOT NULL,
  `status` int(11) NOT NULL,
  `gender` text NOT NULL,
  `leave_date` text,
  `category` int(11) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_salesman`
--

LOCK TABLES `jw_salesman` WRITE;
/*!40000 ALTER TABLE `jw_salesman` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_salesman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_setting`
--

DROP TABLE IF EXISTS `jw_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `value` text NOT NULL,
  `type` int(11) NOT NULL COMMENT '0-text 1-check',
  `hidden` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_setting`
--

LOCK TABLES `jw_setting` WRITE;
/*!40000 ALTER TABLE `jw_setting` DISABLE KEYS */;
INSERT INTO `jw_setting` VALUES (1,'KEY_ENABLE_PERMISSION_CHECK','开启权限分离','1',1,0),(2,'KEY_ENABLE_ROOT','允许root用户登陆','1',1,0),(3,'KEY_SHOW_QRCODE_IN_ORDER','在订单上显示公众号二维码','0',1,0),(4,'KEY_WECHAT_APPID','微信AppID','wx0d0986063a320391',0,0),(5,'KEY_WECHAT_APPSECRET','微信AppSecret','9f57b883ed31540dd383d0266234b34f',0,0),(6,'KEY_WECHAT_ADMINNOTIFY','微信管理员通知模版消息ID','f_bgJhKycjG5OX8Tm6arsZX76jaKhiT0JBx7pqAGRtI',0,0),(7,'KEY_WECHAT_CUSTOMERNOTIFY','微信客户通知模版消息ID','f_bgJhKycjG5OX8Tm6arsZX76jaKhiT0JBx7pqAGRtI',0,0);
/*!40000 ALTER TABLE `jw_setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_user`
--

DROP TABLE IF EXISTS `jw_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_user` (
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
-- Dumping data for table `jw_user`
--

LOCK TABLES `jw_user` WRITE;
/*!40000 ALTER TABLE `jw_user` DISABLE KEYS */;
INSERT INTO `jw_user` VALUES (1,'root','root','root','','','','','0','','','',0);
/*!40000 ALTER TABLE `jw_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_vendor`
--

DROP TABLE IF EXISTS `jw_vendor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` text NOT NULL,
  `title` text NOT NULL,
  `telephone` text NOT NULL,
  `email` text NOT NULL,
  `province` text NOT NULL,
  `city` text NOT NULL,
  `district` text NOT NULL,
  `address` text NOT NULL,
  `salesman` int(11) NOT NULL,
  `comments` text NOT NULL,
  `wechat_userid` int(11) NOT NULL,
  `wechat_notify` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_vendor`
--

LOCK TABLES `jw_vendor` WRITE;
/*!40000 ALTER TABLE `jw_vendor` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_vendor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_wechatadmin`
--

DROP TABLE IF EXISTS `jw_wechatadmin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_wechatadmin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wechatuser` int(11) NOT NULL,
  `notify` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `wechatuser` (`wechatuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_wechatadmin`
--

LOCK TABLES `jw_wechatadmin` WRITE;
/*!40000 ALTER TABLE `jw_wechatadmin` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_wechatadmin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jw_wechatuser`
--

DROP TABLE IF EXISTS `jw_wechatuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jw_wechatuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` text NOT NULL,
  `nickname` text NOT NULL,
  `headimgurl` text NOT NULL,
  `sex` int(11) NOT NULL,
  `city` text NOT NULL,
  `province` text NOT NULL,
  `country` text NOT NULL,
  `subscribe` int(11) NOT NULL,
  `remark` text NOT NULL,
  `groupid` int(11) NOT NULL,
  `tagid_list` text NOT NULL,
  `status` int(11) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_wechatuser`
--

LOCK TABLES `jw_wechatuser` WRITE;
/*!40000 ALTER TABLE `jw_wechatuser` DISABLE KEYS */;
/*!40000 ALTER TABLE `jw_wechatuser` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-21 12:57:01

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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_category`
--

LOCK TABLES `jw_category` WRITE;
/*!40000 ALTER TABLE `jw_category` DISABLE KEYS */;
INSERT INTO `jw_category` VALUES (1,0,'原料'),(2,0,'产品'),(3,0,'客户'),(4,0,'供应商'),(5,0,'业务员'),(6,0,'仓库'),(8,1,'酒原料'),(9,2,'酒类'),(10,2,'食品类'),(13,8,'大瓶酒'),(16,4,'供应商类别1'),(17,4,'供应商类别2'),(18,4,'供应商类别3'),(19,16,'供应商子类别1A'),(20,16,'供应商子类别1B'),(21,17,'供应商子类别2A'),(22,17,'供应商子类别2B'),(23,3,'买酒的'),(24,3,'买肉的'),(25,5,'卖肉的'),(26,5,'卖酒的'),(27,6,'冷库'),(28,6,'酒窖'),(29,6,'普通仓库'),(30,10,'烤肠'),(31,10,'丸子'),(32,10,'调理食品'),(33,3,'买人的'),(37,1,'肉原料'),(38,37,'鸡肉'),(39,37,'牛肉');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_customer`
--

LOCK TABLES `jw_customer` WRITE;
/*!40000 ALTER TABLE `jw_customer` DISABLE KEYS */;
INSERT INTO `jw_customer` VALUES (1,'酒王','河南王总',23,'13522668874','','河南省','郑州市','','',0,'',0,0,0),(2,'肉刘','北京刘总',24,'13200548879','','北京市','','','',0,'',0,0,0),(3,'肠张','山东张总',24,'','','山东省','','','',0,'',0,0,0),(4,'张三','张三',23,'','','','','','',0,'',0,0,0),(5,'李四','李四',23,'','','','','','',0,'',0,0,0),(6,'王五','王五',24,'','','','','','',0,'',0,0,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_depot`
--

LOCK TABLES `jw_depot` WRITE;
/*!40000 ALTER TABLE `jw_depot` DISABLE KEYS */;
INSERT INTO `jw_depot` VALUES (3,'冷库1','addr1',1,27,'dd',0),(4,'酒库2','addr2',3,28,'c',0),(5,'仓库3','addr344',3,29,'dasdf',0),(6,'仓库4','addr3',3,29,'',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_group`
--

LOCK TABLES `jw_group` WRITE;
/*!40000 ALTER TABLE `jw_group` DISABLE KEYS */;
INSERT INTO `jw_group` VALUES (1,'admin','user,salesorder,purchaserefund,salesanalysing,balance,purchaseorder,group,currentdepot,product,setting,productorder,productordermodify,materialorder,material,customer,vendor,salesman,depot,materialordermodify,reportsheet,category,customerprice,wechatuser,wechatnotify,wechatadmin',0),(2,'库管1','salesorder,salesrefund,purchaseorder,purchaserefund,vendor,balance',0),(5,'test1','material,vendor,salesman,product,customer,salesrefund,salesanalysing,group,depot',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_material`
--

LOCK TABLES `jw_material` WRITE;
/*!40000 ALTER TABLE `jw_material` DISABLE KEYS */;
INSERT INTO `jw_material` VALUES (1,8,'原酒1','原酒1','吨','车','1234567','ZXCVVBNB','12221112','茅台','卡车','备注1',0),(2,8,'牛肉','牛肉','千克','10','12321','22222','12345','新西兰','新西兰牛','备注2',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_materialorder`
--

LOCK TABLES `jw_materialorder` WRITE;
/*!40000 ALTER TABLE `jw_materialorder` DISABLE KEYS */;
INSERT INTO `jw_materialorder` VALUES (1,'OD98C0D4T2017080212354576C2535694467',4,0,0,0,'','','1501648545','1501667183',-2,2,'0'),(4,'OD1C2D5T20170802123858D775CD82754E1',5,0,2,0,'','','1501652784','',1,0,''),(5,'OD98C0D4T2017080212354655F1C9A9490B8',4,0,0,0,'','sss','1501666607','',-2,0,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_materialorderrecord`
--

LOCK TABLES `jw_materialorderrecord` WRITE;
/*!40000 ALTER TABLE `jw_materialorderrecord` DISABLE KEYS */;
INSERT INTO `jw_materialorderrecord` VALUES (1,1,1,'','','','','',101,'','',''),(4,4,1,'','','','','',12,'2332','',''),(5,5,1,'','','','','',101,'','',''),(6,5,2,'','','','','',12,'0','','');
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_notice`
--

LOCK TABLES `jw_notice` WRITE;
/*!40000 ALTER TABLE `jw_notice` DISABLE KEYS */;
INSERT INTO `jw_notice` VALUES (4,3,2,1),(6,3,3,0),(9,5,1,0),(11,2,2,0),(12,6,4,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_price`
--

LOCK TABLES `jw_price` WRITE;
/*!40000 ALTER TABLE `jw_price` DISABLE KEYS */;
INSERT INTO `jw_price` VALUES (1,1,2,'1001','1501334552'),(7,1,3,'10011','1501679503'),(8,4,6,'120','1501338230'),(9,3,4,'100','1501338245'),(11,6,4,'95','1501338259'),(12,3,5,'100','1501384874'),(13,4,1,'12012','1501680315');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_product`
--

LOCK TABLES `jw_product` WRITE;
/*!40000 ALTER TABLE `jw_product` DISABLE KEYS */;
INSERT INTO `jw_product` VALUES (1,9,'酒1','酒1','瓶','500ml','10002001','ASDFGHJILMN','10002001','生产','十年窖藏','备注1','100',0),(2,9,'酒2','酒2','瓶','600ml','100312','QWERTYT','100312','生产','20年','备注2','68',0),(3,30,'台湾烤肠','台湾烤肠','','','','','','','','','100',0),(4,31,'牛肉丸','牛肉丸','','','','','','','','','120',0),(5,31,'鸡肉丸','鸡肉丸','','','','','','','','','80',0),(6,32,'笑脸片','笑脸片','','','','','','','','','95',0),(7,30,'台湾烤肠','台湾烤肠','','','','','','','','','120',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_productorder`
--

LOCK TABLES `jw_productorder` WRITE;
/*!40000 ALTER TABLE `jw_productorder` DISABLE KEYS */;
INSERT INTO `jw_productorder` VALUES (10,'OD98C2D3T2017073121390656D7FACD6A13C',3,0,2,0,'王金娜','aasdd','1501591670','1501591670',-2,2,'816'),(18,'OD98C1D4T20170801214928085449905B0A2',4,0,1,0,'','','1501595368','1501595410',-2,2,'14808'),(20,'OD98C3D5T2017080214313781C2B88BF8CED',5,0,3,0,'','','1501655497','1501655596',-2,2,'816'),(21,'OD98C3D4T201708022111187DBDF7CF',4,0,3,0,'','','1501679478','1501680019',-2,2,'10011'),(22,'OD98C6D5T20170802212717904A429B',5,0,6,0,'','枯干','1501680437','1501680490',-2,2,'10000'),(24,'OD98C3D3T20170821122913D5390737',3,0,3,1,'','','1503289786','1503289794',-2,2,'10011'),(25,'OD98C3D4T20170821123035E2A7DB86',4,0,3,1,'','','1503289835','1503289840',-2,2,'10011');
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
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_productorderrecord`
--

LOCK TABLES `jw_productorderrecord` WRITE;
/*!40000 ALTER TABLE `jw_productorderrecord` DISABLE KEYS */;
INSERT INTO `jw_productorderrecord` VALUES (1,10,2,'OD98C2D3T2017073121390656D7FACD6A13C','酒2','酒2','瓶','600ml',12,0,'68','备注2',''),(22,18,1,'10002001','酒1','酒1','瓶','500ml',21,0,'100','备注1','十年窖藏'),(23,18,3,'','台湾烤肠','台湾烤肠','','',112,0,'100','',''),(24,18,4,'','牛肉丸','牛肉丸','','',12,1,'120','',''),(25,18,2,'100312','酒2','酒2','瓶','600ml',1,0,'68','备注2','20年'),(27,20,2,'100312','酒2','酒2','瓶','600ml',12,0,'68','备注2','20年'),(28,21,1,'10002001','酒1','酒1','瓶','500ml',1,0,'10011','备注1','十年窖藏'),(29,22,3,'','台湾烤肠','台湾烤肠','','',100,2,'100','',''),(31,24,1,'10002001','酒1','酒1','瓶','500ml',1,0,'10011','备注1','十年窖藏'),(32,25,1,'10002001','酒1','酒1','瓶','500ml',1,0,'10011','备注1','十年窖藏');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_salesman`
--

LOCK TABLES `jw_salesman` WRITE;
/*!40000 ALTER TABLE `jw_salesman` DISABLE KEYS */;
INSERT INTO `jw_salesman` VALUES (1,'业务员1','1832220034',0,'女',NULL,25,'cccc1'),(2,'业务员2','13522116688',0,'男',NULL,26,'cc2');
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_user`
--

LOCK TABLES `jw_user` WRITE;
/*!40000 ALTER TABLE `jw_user` DISABLE KEYS */;
INSERT INTO `jw_user` VALUES (1,'root','root','root','','','','','0','','','',0),(3,'user1','user1','User1','User1Telephone','User1Email','5,2,1','ssssc','1501046491','','','',0);
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_vendor`
--

LOCK TABLES `jw_vendor` WRITE;
/*!40000 ALTER TABLE `jw_vendor` DISABLE KEYS */;
INSERT INTO `jw_vendor` VALUES (1,4,'大胸李','大胸李','','','山东省','','','',0,'',0,0,0),(2,4,'猪肉王','猪肉王','','','','','','',0,'',0,0,0),(3,19,'卡车刘','卡车刘','tetetete','eeeee','','','','aaaaaa',0,'cccccccc',0,0,0),(4,20,'纸箱赵','纸箱赵111','纸箱赵电话','纸箱赵邮箱','辽宁省','本溪市','平山区','纸箱赵地址',0,'纸箱赵备注',0,0,0),(5,21,'新增张','新增张','新增张电话','新增张邮箱','辽宁省','锦州市','黑山县','新增张地址',0,'新增张备注',0,0,0),(6,22,'新增崔','新增崔','','','北京市','北京市市辖区','石景山区','',0,'',0,0,0),(7,21,'新增刘','新增刘','','','','','','',0,'',0,0,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_wechatadmin`
--

LOCK TABLES `jw_wechatadmin` WRITE;
/*!40000 ALTER TABLE `jw_wechatadmin` DISABLE KEYS */;
INSERT INTO `jw_wechatadmin` VALUES (5,2,1),(6,3,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jw_wechatuser`
--

LOCK TABLES `jw_wechatuser` WRITE;
/*!40000 ALTER TABLE `jw_wechatuser` DISABLE KEYS */;
INSERT INTO `jw_wechatuser` VALUES (1,'or7ebuJF0M9ZPruCYjpTrICuw45Y','昆','http://wx.qlogo.cn/mmopen/IJdPq631CVEvQHn7uHat4cC0SuGaz0pMIR2Rv4AEk8VAcPByhgf6UbSYBdmBexSU3Sia4lo007JnLOj3VciamZpw/0',1,'通州','北京','中国',1,'',0,'[]',0,''),(2,'or7ebuOfuEcp0iqdgjunHUy-AF9A','__蜘plusplus','http://wx.qlogo.cn/mmopen/ajNVdqHZLLBYib2JiaBasABe7GRHibYHrtZMBuIR2RJMqJbhibS7Gia10mERqCMHhBDyDEJadMRESk9icLic6HeRCb4Jw/0',1,'青岛','山东','中国',1,'',0,'[]',0,''),(3,'or7ebuOUr7uQxuNLWhiTiZGLtpb0','杨莉莉','http://wx.qlogo.cn/mmopen/ajNVdqHZLLDIQQ81EDeBSfy2ur2W8zQWqBCKWcjlf03Q55NaNchrOQpY3ufwunpSaPDvVHSsGaXPml1Pet1L2g/0',2,'青岛','山东','中国',1,'',0,'[]',0,''),(4,'or7ebuMzAhKwaxin2_kufeO07h7s','程炳美','http://wx.qlogo.cn/mmopen/wFgzywXAs362Vq9aTicDSbaOrOyyBDF5P8ObVXjaz009MqG07zBN73peiaUAiaIcpPAtcpKAh7dbwLE4ZSo1WIHBFfSkSicZ55Ee/0',2,'潍坊','山东','中国',1,'',0,'[]',0,'');
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

-- Dump completed on 2017-08-21 12:52:07

-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: localhost    Database: pigeonreportstest
-- ------------------------------------------------------
-- Server version	5.6.17

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
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reportID` int(11) NOT NULL,
  `reportName` varchar(22) CHARACTER SET utf8 NOT NULL,
  `reportPhone` varchar(19) CHARACTER SET utf8 NOT NULL,
  `reportEmail` varchar(28) CHARACTER SET utf8 NOT NULL,
  `reportDepartment` text CHARACTER SET utf8 NOT NULL,
  `reportRequest` text CHARACTER SET utf8 NOT NULL,
  `reportCustomRequest` varchar(48) CHARACTER SET utf8 DEFAULT NULL,
  `reportSummary` varchar(60) CHARACTER SET utf8 NOT NULL,
  `reportDetails` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `reportPriority` varchar(15) CHARACTER SET utf8 NOT NULL,
  `reportDate` varchar(11) CHARACTER SET utf8 NOT NULL,
  `reportTime` varchar(10) CHARACTER SET utf8 NOT NULL,
  `duration` int(11) NOT NULL DEFAULT '1',
  `admin_priority` varchar(8) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `admin_notes` varchar(300) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `markedForDeletion` tinyint(1) NOT NULL DEFAULT '0',
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `dateResolved` varchar(11) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,232,'Mia Cai','905-525-9140 x24342','resadm@mcmaster.ca','Residence Admissions','Internet/Network Issues',NULL,'PC not connecting to Starrez Portal',NULL,'High','10/07/2015','anytime',5,'High','',0,1,'13/6/2015'),(2,198,'Jaimie Dickson','905-525-9140 x24070','jdickso@mcmaster.ca','Residence Admissions','PC Issues',NULL,'Outlook Contacts not syncing properly',NULL,'Medium','13/07/2015','3:30 PM',6,'Medium','',0,0,''),(7,1366,'Mark Gonzalez','905-525-9140 x22929','resadm@mcmaster.ca','Res Life','Other','Adobe','PDF\'s not opening','','Low','07/08/2015','anytime',1,'Low','This is awesome. Remember to take extra ethernet cords over and the adobe pro installation disc (2015) as well.',0,0,''),(8,1408,'Sarah Benedict','905-525-9140 x22929','benedisi@mcmaster.ca','Res Life','Other','Second Screen','Display not working','','Low','07/08/2015','anytime',10,'Low','',0,0,''),(9,1832,'Yash','905-525-9140','housit1@mcmaster.ca','Residence Admissions','PC Setup','','Kevin B ADS Computer','','High','07/08/2015','anytime',1,'Inactive','',0,0,''),(10,1439,'Yash','9055259140','hh@mcmaster.ca','Residence Admissions','PC Issues','','Setup ADS systems','','Medium','07/08/2015','anytime',12,'Low','',0,0,''),(11,1612,'Bruddah','905-525-9140','bruddah@mcmaster.ca','Res Life','Other','Bruh why do u do dis','Someone better fix this','Woa too much','High','07/09/2015','2:30 PM',1,'Inactive','',0,1,'13/6/2015'),(12,1629,'Money','9055259140','cash@bank.ca','Residence Admissions','Shared Drives','','Break it down','','Low','03/08/2015','9:00 am',1,'','',0,0,''),(13,1945,'Mia','905-525-9140','sdfkdfs@mcmaster.ca','WQ Service Centre','PC Issues','','broken pc','','High','19/07/2015','2:00 PM',1,'','',0,0,''),(14,1780,'Gopala','1234567891','gopal@biztbaa.com','Res Life','Printer Issue','','Cannot scan documents','','High','14/07/2015','anytime',3,'Low','blabla really.',0,0,''),(17,3676,'Sam','9055259140','s@mcmaster.ca','Res Life','PC Issues','','Malware!!','','High','14/07/2015','anytime',1,'','',0,0,''),(18,4056,'Wowa','9055259140','bla@mcmaster.ca','Conference Services','PC Issues','','Woah Kathryn not cool','','Low','15/07/2015','anytime',1,'','',0,0,''),(19,4286,'Insane','9055259140','h@mcmaster.ca','Conference Services','Shared Drives','','bruhdhh','','Low','15/07/2015','anytime',1,'','',0,0,''),(20,4439,'MOAR','9055259140','hh@mcmaster.ca','Res Facilities','Printer Issue','','come on','','Medium','16/07/2015','anytime',1,'','',0,0,''),(21,45100,'reality check','9055259140','h@mcmaster.ca','Res Facilities','PC Issues','','Maybe','','Low','14/07/2015','2:30PM',1,'Medium','',0,0,''),(22,4294,'Dug Smug','9055449990','rade@mcmaster.ca','WQ Service Centre','PC Setup','','fart noises in computer','none','Low','14/07/2015','2:30 PM',1,'High','',0,0,''),(23,4986,'hduhwdhwu','9055259140','h@mcmaster.ca','Res Life','PC Issues','','Blablaba','','High','14/07/2015','anytime',1,'Low','',0,1,'14/6/2015');
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-15  9:37:20

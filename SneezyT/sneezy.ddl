-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: sneezy
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.04.2

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
-- Table structure for table `Event`
--

DROP TABLE IF EXISTS `Event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Event` (
  `EventId` int(11) NOT NULL AUTO_INCREMENT,
  `EventTypeId` int(11) NOT NULL,
  `EventDate` datetime NOT NULL,
  PRIMARY KEY (`EventId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `EventType`
--

DROP TABLE IF EXISTS `EventType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `EventType` (
  `EventTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `EventName` varchar(250) NOT NULL,
  PRIMARY KEY (`EventTypeId`),
  UNIQUE KEY `EventName` (`EventName`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `FoodType`
--

DROP TABLE IF EXISTS `FoodType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FoodType` (
  `FoodTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodLongName` varchar(250) NOT NULL,
  `FoodName` varchar(255) NOT NULL,
  `Water` float DEFAULT NULL,
  `Energ_Kcal` float DEFAULT NULL,
  `Protein` float DEFAULT NULL,
  `Lipid_Tot` float DEFAULT NULL,
  `Ash` float DEFAULT NULL,
  `Carbohydrt` float DEFAULT NULL,
  `Fiber_TD` float DEFAULT NULL,
  `Sugar_Tot` float DEFAULT NULL,
  `Calcium` float DEFAULT NULL,
  `Iron` float DEFAULT NULL,
  `Magnesium` float DEFAULT NULL,
  `Phosphorus` float DEFAULT NULL,
  `Potassium` float DEFAULT NULL,
  `Sodium` float DEFAULT NULL,
  `Zinc` float DEFAULT NULL,
  `Copper` float DEFAULT NULL,
  `Manganese` float DEFAULT NULL,
  `Selenium` float DEFAULT NULL,
  `Vit_C` float DEFAULT NULL,
  `Thiamin` float DEFAULT NULL,
  `Riboflavin` float DEFAULT NULL,
  `Niacin` float DEFAULT NULL,
  `Panto_Acid` float DEFAULT NULL,
  `Vit_B6` float DEFAULT NULL,
  `Folate_Tot` float DEFAULT NULL,
  `Folic_Acid` float DEFAULT NULL,
  `Food_Folate` float DEFAULT NULL,
  `Folate_DFE` float DEFAULT NULL,
  `Choline_Tot` float DEFAULT NULL,
  `Vit_B12` float DEFAULT NULL,
  `Vit_A_IU` float DEFAULT NULL,
  `Vit_A_RAE` float DEFAULT NULL,
  `Retinol` float DEFAULT NULL,
  `Alpha_Carot` float DEFAULT NULL,
  `Beta_Carot` float DEFAULT NULL,
  `Beta_Crypt` float DEFAULT NULL,
  `Lycopene` float DEFAULT NULL,
  `LutPlusZea` float DEFAULT NULL,
  `Vit_E` float DEFAULT NULL,
  `Vit_D_mcg` float DEFAULT NULL,
  `ViVit_D_IU` float DEFAULT NULL,
  `Vit_K` float DEFAULT NULL,
  `Fat_Sat` float DEFAULT NULL,
  `Fat_Mono` float DEFAULT NULL,
  `Fat_Poly` float DEFAULT NULL,
  `Cholestrl` float DEFAULT NULL,
  `GmWt_1` float DEFAULT NULL,
  `GmWt_Desc1` text,
  `GmWt_2` float DEFAULT NULL,
  `GmWt_Desc2` text,
  `Refuse_Pct` float DEFAULT NULL,
  PRIMARY KEY (`FoodTypeId`),
  UNIQUE KEY `Food` (`FoodLongName`)
) ENGINE=InnoDB AUTO_INCREMENT=7829 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Meal`
--

DROP TABLE IF EXISTS `Meal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Meal` (
  `MealId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodTypeId` int(11) NOT NULL,
  `MealDate` datetime NOT NULL,
  PRIMARY KEY (`MealId`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-28 13:13:58

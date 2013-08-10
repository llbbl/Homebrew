-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 10, 2013 at 12:51 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sneezy`
--

-- --------------------------------------------------------

--
-- Table structure for table `Environment`
--

CREATE TABLE IF NOT EXISTS `Environment` (
  `EnvironmentId` int(11) NOT NULL AUTO_INCREMENT,
  `EnvironmentTypeId` int(11) NOT NULL,
  `EnvironmentDate` datetime NOT NULL,
  `EnvironmentNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`EnvironmentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

-- --------------------------------------------------------

--
-- Table structure for table `EnvironmentType`
--

CREATE TABLE IF NOT EXISTS `EnvironmentType` (
  `EnvironmentTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `EnvironmentName` varchar(250) NOT NULL,
  PRIMARY KEY (`EnvironmentTypeId`),
  UNIQUE KEY `EnvironmentName` (`EnvironmentName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `Event-old`
--

CREATE TABLE IF NOT EXISTS `Event-old` (
  `EventId` int(11) NOT NULL AUTO_INCREMENT,
  `EventTypeId` int(11) NOT NULL,
  `EventDate` datetime NOT NULL,
  `EventNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`EventId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=165 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventType-old`
--

CREATE TABLE IF NOT EXISTS `EventType-old` (
  `EventTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `EventName` varchar(250) NOT NULL,
  PRIMARY KEY (`EventTypeId`),
  UNIQUE KEY `EventName` (`EventName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `Food`
--

CREATE TABLE IF NOT EXISTS `Food` (
  `FoodId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodTypeId` int(11) NOT NULL,
  `FoodDate` datetime NOT NULL,
  `FoodNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`FoodId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5276 ;

-- --------------------------------------------------------

--
-- Table structure for table `FoodBrand`
--

CREATE TABLE IF NOT EXISTS `FoodBrand` (
  `FoodBrandId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodBrand` varchar(255) NOT NULL,
  PRIMARY KEY (`FoodBrandId`),
  UNIQUE KEY `FoodBrand` (`FoodBrand`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `FoodType`
--

CREATE TABLE IF NOT EXISTS `FoodType` (
  `FoodTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodLongName` varchar(250) DEFAULT NULL,
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
  UNIQUE KEY `IX_Food_FoodName` (`FoodName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8055 ;

-- --------------------------------------------------------

--
-- Table structure for table `Meal-old`
--

CREATE TABLE IF NOT EXISTS `Meal-old` (
  `MealId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodTypeId` int(11) NOT NULL,
  `MealDate` datetime NOT NULL,
  `MealNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`MealId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3286 ;

-- --------------------------------------------------------

--
-- Table structure for table `Medicine`
--

CREATE TABLE IF NOT EXISTS `Medicine` (
  `MedicineId` int(11) NOT NULL AUTO_INCREMENT,
  `MedicineTypeId` int(11) NOT NULL,
  `MedicineDate` datetime NOT NULL,
  `MedicineNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`MedicineId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=772 ;

-- --------------------------------------------------------

--
-- Table structure for table `MedicineType`
--

CREATE TABLE IF NOT EXISTS `MedicineType` (
  `MedicineTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `MedicineName` varchar(250) NOT NULL,
  PRIMARY KEY (`MedicineTypeId`),
  UNIQUE KEY `MedicineName` (`MedicineName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `PollenFile`
--

CREATE TABLE IF NOT EXISTS `PollenFile` (
  `FileDate` datetime NOT NULL,
  `AllergyReport` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Reaction`
--

CREATE TABLE IF NOT EXISTS `Reaction` (
  `ReactionId` int(11) NOT NULL AUTO_INCREMENT,
  `ReactionTypeId` int(11) NOT NULL,
  `ReactionDate` datetime NOT NULL,
  `ReactionNote` text,
  `IsDeleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ReactionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=448 ;

-- --------------------------------------------------------

--
-- Table structure for table `ReactionType`
--

CREATE TABLE IF NOT EXISTS `ReactionType` (
  `ReactionTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `ReactionName` varchar(250) NOT NULL,
  PRIMARY KEY (`ReactionTypeId`),
  UNIQUE KEY `ReactionName` (`ReactionName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

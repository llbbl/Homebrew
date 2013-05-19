-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 18, 2013 at 11:37 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sneezy`
--

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

DROP TABLE IF EXISTS `Event`;
CREATE TABLE IF NOT EXISTS `Event` (
  `EventId` int(11) NOT NULL AUTO_INCREMENT,
  `EventTypeId` int(11) NOT NULL,
  `EventDate` datetime NOT NULL,
  `EventNote` text,
  PRIMARY KEY (`EventId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=120 ;

-- --------------------------------------------------------

--
-- Table structure for table `EventType`
--

DROP TABLE IF EXISTS `EventType`;
CREATE TABLE IF NOT EXISTS `EventType` (
  `EventTypeId` int(11) NOT NULL AUTO_INCREMENT,
  `EventName` varchar(250) NOT NULL,
  PRIMARY KEY (`EventTypeId`),
  UNIQUE KEY `EventName` (`EventName`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `FoodBrand`
--

DROP TABLE IF EXISTS `FoodBrand`;
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

DROP TABLE IF EXISTS `FoodType`;
CREATE TABLE IF NOT EXISTS `FoodType` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7912 ;

-- --------------------------------------------------------

--
-- Table structure for table `Meal`
--

DROP TABLE IF EXISTS `Meal`;
CREATE TABLE IF NOT EXISTS `Meal` (
  `MealId` int(11) NOT NULL AUTO_INCREMENT,
  `FoodTypeId` int(11) NOT NULL,
  `MealDate` datetime NOT NULL,
  `MealNote` text,
  PRIMARY KEY (`MealId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3152 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

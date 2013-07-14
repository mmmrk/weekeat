-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2012 at 11:13 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `food`
--
CREATE DATABASE `food` DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci;
USE `food`;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'n''tgått', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00'),
(2, 'fulsnusk', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE IF NOT EXISTS `dish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_swedish_ci DEFAULT NULL,
  `recipe` text COLLATE utf8_swedish_ci,
  `category_id` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`id`, `name`, `url`, `recipe`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 'snarr', 'http://url.com/test', NULL, 1, '2012-05-14 22:24:18', NULL),
(4, 'raggarballar med svängdörr', NULL, '4st raggare\r\n4st svängdörrar\r\nrikligt med blod\r\n\r\n1. hugg av raggarballarna\r\n2. placera raggarballarna i svängdörrarna\r\n3. fyll på med blod', 2, '2012-05-14 22:24:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `eatage`
--

CREATE TABLE IF NOT EXISTS `eatage` (
  `date` date NOT NULL,
  `dish_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`date`),
  KEY `dish_id` (`dish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `eatage`
--

INSERT INTO `eatage` (`date`, `dish_id`) VALUES
('2012-05-14', 4);

-- --------------------------------------------------------

--
-- Stand-in structure for view `uneaten`
--
CREATE TABLE IF NOT EXISTS `uneaten` (
`id` int(10) unsigned
,`name` varchar(100)
,`url` varchar(200)
,`recipe` text
,`category_id` tinyint(3) unsigned
,`created_at` datetime
,`updated_at` datetime
);
-- --------------------------------------------------------

--
-- Structure for view `uneaten`
--
DROP TABLE IF EXISTS `uneaten`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `uneaten` AS select `dish`.`id` AS `id`,`dish`.`name` AS `name`,`dish`.`url` AS `url`,`dish`.`recipe` AS `recipe`,`dish`.`category_id` AS `category_id`,`dish`.`created_at` AS `created_at`,`dish`.`updated_at` AS `updated_at` from `dish` where (not(`dish`.`id` in (select `eatage`.`dish_id` from `eatage`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `dish_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Constraints for table `eatage`
--
ALTER TABLE `eatage`
  ADD CONSTRAINT `eatage_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 19, 2012 at 09:01 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

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
-- Stand-in structure for view `available_dishes`
--
CREATE TABLE IF NOT EXISTS `available_dishes` (
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
-- Table structure for table `category_eatage`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `category_eatage` AS select `food`.`category`.`id` AS `id`,`food`.`category`.`name` AS `category`,count(`food`.`eatage`.`dish_id`) AS `times_eaten` from ((`category` left join `dish` on((`food`.`dish`.`category_id` = `food`.`category`.`id`))) left join `eatage` on((`food`.`eatage`.`dish_id` = `food`.`dish`.`id`))) group by `food`.`category`.`id`,`food`.`category`.`name` order by count(`food`.`eatage`.`dish_id`) desc;
-- in use (#1356 - View 'food.category_eatage' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

-- --------------------------------------------------------

--
-- Table structure for table `category_usage`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `category_usage` AS select `food`.`category`.`id` AS `id`,`food`.`category`.`name` AS `category`,count(`food`.`dish`.`id`) AS `times_used` from (`category` left join `dish` on((`food`.`dish`.`category_id` = `food`.`category`.`id`))) group by `food`.`category`.`id`,`food`.`category`.`name` order by count(`food`.`dish`.`id`) desc;
-- in use (#1356 - View 'food.category_usage' references invalid table(s) or column(s) or function(s) or definer/invoker of view lack rights to use them)

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`id`, `name`, `url`, `recipe`, `category_id`, `created_at`, `updated_at`) VALUES
(3, 'snarr', 'http://url.com/test', NULL, 1, '2012-05-14 22:24:18', NULL),
(4, 'raggarballar med svängdörr', NULL, '4st raggare\r\n4st svängdörrar\r\nrikligt med blod\r\n\r\n1. hugg av raggarballarna\r\n2. placera raggarballarna i svängdörrarna\r\n3. fyll på med blod', 2, '2012-05-14 22:24:18', NULL),
(5, 'dolmsoppa', NULL, 'ett tjog dolmar\r\n10L vatten\r\nsalt\r\n1 msk kryddpeppar\r\n3 lagerblad\r\n2 stora morötter\r\n2 stora lökar ( HA HA? )\r\n\r\nsula ner allt, värm på, inmundera, njut', 1, '2012-05-17 01:18:11', NULL),
(6, 'Gammartblask', '', '1 del blask\r\n\r\nLåt stå\r\nServera', 3, '2012-05-17 16:23:12', NULL),
(7, 'Dunderhonung', 'http://bamsesfarmor.se/dunderhonung', '', 2, '2012-05-17 16:36:12', NULL),
(8, 'testerester', 'http://mat.nu/ok?', NULL, 2, '0000-00-00 00:00:00', NULL),
(10, 'testerester5', 'http://mat.nu/ok?', NULL, 2, '2012-05-20 00:00:00', NULL),
(11, 'testerester3', 'http://mat.nu/ok?', NULL, 2, '0000-00-00 00:00:00', NULL);

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
('2012-05-18', 3),
('2012-05-13', 4),
('2012-05-14', 4),
('2012-05-19', 6),
('2012-05-01', 7),
('2012-05-20', 8);

-- --------------------------------------------------------

--
-- Table structure for table `label`
--

CREATE TABLE IF NOT EXISTS `label` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `label`
--

INSERT INTO `label` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'n''tgått', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00'),
(2, 'fulsnusk', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00'),
(3, 'läskeblask', NULL, '2012-05-15 03:12:54', NULL),
(4, 'snubbidubb', NULL, '2012-05-17 13:10:59', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `ready_to_eat`
--
CREATE TABLE IF NOT EXISTS `ready_to_eat` (
`id` int(11) unsigned
,`name` varchar(100)
,`url` varchar(200)
,`recipe` text
,`category_id` tinyint(4) unsigned
,`created_at` datetime
,`updated_at` datetime
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `uneaten_dishes`
--
CREATE TABLE IF NOT EXISTS `uneaten_dishes` (
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
-- Structure for view `available_dishes`
--
DROP TABLE IF EXISTS `available_dishes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `available_dishes` AS select `dish`.`id` AS `id`,`dish`.`name` AS `name`,`dish`.`url` AS `url`,`dish`.`recipe` AS `recipe`,`dish`.`category_id` AS `category_id`,`dish`.`created_at` AS `created_at`,`dish`.`updated_at` AS `updated_at` from `dish` where (not(`dish`.`id` in (select `eatage`.`dish_id` from `eatage` where (`eatage`.`date` < (curdate() - interval 10 day)))));

-- --------------------------------------------------------

--
-- Structure for view `ready_to_eat`
--
DROP TABLE IF EXISTS `ready_to_eat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ready_to_eat` AS select `full`.`id` AS `id`,`full`.`name` AS `name`,`full`.`url` AS `url`,`full`.`recipe` AS `recipe`,`full`.`category_id` AS `category_id`,`full`.`created_at` AS `created_at`,`full`.`updated_at` AS `updated_at` from `available_dishes` `full` union select `uneaten_dishes`.`id` AS `id`,`uneaten_dishes`.`name` AS `name`,`uneaten_dishes`.`url` AS `url`,`uneaten_dishes`.`recipe` AS `recipe`,`uneaten_dishes`.`category_id` AS `category_id`,`uneaten_dishes`.`created_at` AS `created_at`,`uneaten_dishes`.`updated_at` AS `updated_at` from `uneaten_dishes`;

-- --------------------------------------------------------

--
-- Structure for view `uneaten_dishes`
--
DROP TABLE IF EXISTS `uneaten_dishes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `uneaten_dishes` AS select `dish`.`id` AS `id`,`dish`.`name` AS `name`,`dish`.`url` AS `url`,`dish`.`recipe` AS `recipe`,`dish`.`category_id` AS `category_id`,`dish`.`created_at` AS `created_at`,`dish`.`updated_at` AS `updated_at` from `dish` where (not(`dish`.`id` in (select `eatage`.`dish_id` from `eatage`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dish`
--
ALTER TABLE `dish`
  ADD CONSTRAINT `dish_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `label` (`id`);

--
-- Constraints for table `eatage`
--
ALTER TABLE `eatage`
  ADD CONSTRAINT `eatage_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

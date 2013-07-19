-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 13, 2013 at 09:48 PM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weekeat`
--
CREATE DATABASE `weekeat` DEFAULT CHARACTER SET utf8 COLLATE utf8_swedish_ci;
USE `weekeat`;

-- --------------------------------------------------------

--
-- Stand-in structure for view `available_dishes`
--
CREATE TABLE IF NOT EXISTS `available_dishes` (
`id` int(10) unsigned
,`name` varchar(100)
,`url` varchar(200)
,`recipe` text
,`created_at` datetime
,`updated_at` datetime
);
-- --------------------------------------------------------

--
-- Table structure for table `dish`
--

CREATE TABLE IF NOT EXISTS `dish` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `description` varchar(200) COLLATE utf8_swedish_ci NOT NULL,
  `url` varchar(200) COLLATE utf8_swedish_ci DEFAULT NULL,
  `recipe` text COLLATE utf8_swedish_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `dish`
--

INSERT INTO `dish` (`id`, `name`, `description`, `url`, `recipe`, `created_at`, `updated_at`) VALUES
(3, 'snarr', 'x', 'http://url.com/test', NULL, '2012-05-14 22:24:18', NULL),
(4, 'raggarballar med svängdörr', 'x', NULL, '4st raggare\r\n4st svängdörrar\r\nrikligt med blod\r\n\r\n1. hugg av raggarballarna\r\n2. placera raggarballarna i svängdörrarna\r\n3. fyll på med blod', '2012-05-14 22:24:18', NULL),
(5, 'dolmsoppa', 'x', NULL, 'ett tjog dolmar\r\n10L vatten\r\nsalt\r\n1 msk kryddpeppar\r\n3 lagerblad\r\n2 stora morötter\r\n2 stora lökar ( HA HA? )\r\n\r\nsula ner allt, värm på, inmundera, njut', '2012-05-17 01:18:11', NULL),
(6, 'Gammartblask', 'X', NULL, '1 del blask\r\n\r\nLåt stå\r\nServera', '2012-05-17 16:23:12', NULL),
(7, 'Dunderhonung', 'x', 'http://bamsesfarmor.se/dunderhonung', '', '2012-05-17 16:36:12', NULL),
(8, 'testerester', 'x', 'http://mat.nu/ok?', NULL, '0000-00-00 00:00:00', NULL),
(10, 'testerester5', 'x', 'http://mat.nu/ok?', NULL, '2012-05-20 00:00:00', NULL),
(11, 'testerester3', 'x', 'http://mat.nu/ok?', NULL, '0000-00-00 00:00:00', NULL),
(12, 'lilltestarn', 'x', 'http://url.com', NULL, '2012-05-20 16:29:18', NULL),
(13, 'stortestarn', 'x', 'http://url.com', NULL, '2012-05-20 16:30:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dish_tags`
--

CREATE TABLE IF NOT EXISTS `dish_tags` (
  `dish_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`dish_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `dish_tags`
--

INSERT INTO `dish_tags` (`dish_id`, `tag_id`, `created_at`, `updated_at`) VALUES
(4, 1, '0000-00-00 00:00:00', NULL),
(5, 2, '0000-00-00 00:00:00', NULL),
(7, 2, '0000-00-00 00:00:00', NULL),
(7, 4, '0000-00-00 00:00:00', NULL),
(10, 2, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meal`
--

CREATE TABLE IF NOT EXISTS `meal` (
  `date` date NOT NULL,
  `dish_id` int(10) unsigned NOT NULL,
  `shopping_list` text COLLATE utf8_swedish_ci,
  PRIMARY KEY (`date`),
  KEY `dish_id` (`dish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `meal`
--

INSERT INTO `meal` (`date`, `dish_id`, `shopping_list`) VALUES
('0000-00-00', 5, NULL),
('2012-05-01', 7, NULL),
('2012-05-13', 4, NULL),
('2012-05-14', 4, NULL),
('2012-05-18', 3, NULL),
('2012-05-19', 6, NULL),
('2012-05-20', 8, NULL),
('2012-05-21', 7, NULL),
('2012-05-23', 12, NULL),
('2012-05-24', 13, NULL),
('2012-05-25', 10, NULL),
('2012-06-04', 5, NULL),
('2012-07-14', 7, NULL),
('2012-07-23', 7, NULL),
('2012-07-25', 11, NULL),
('2012-08-13', 5, NULL),
('2012-10-01', 5, NULL),
('2012-10-13', 7, NULL),
('2012-10-20', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_swedish_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8_swedish_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'n''tgått', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00'),
(2, 'fulsnusk', NULL, '2012-05-14 22:21:23', '0000-00-00 00:00:00'),
(3, 'läskeblask', NULL, '2012-05-15 03:12:54', NULL),
(4, 'snubbidubb', NULL, '2012-05-17 13:10:59', NULL),
(5, 'etikett', NULL, '2012-05-23 23:06:20', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `tag_usage`
--
CREATE TABLE IF NOT EXISTS `tag_usage` (
`id` int(10) unsigned
,`tag` varchar(100)
,`times_used` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `meal_tag`
--
CREATE TABLE IF NOT EXISTS `meal_tag` (
`id` int(10) unsigned
,`tag` varchar(100)
,`times_eaten` bigint(21)
);
-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

CREATE TABLE IF NOT EXISTS `priority` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `priority`
--

INSERT INTO `priority` (`id`, `name`) VALUES
(1, 'low'),
(2, 'medium'),
(3, 'high'),
(4, 'critical');

-- --------------------------------------------------------

--
-- Stand-in structure for view `ready_to_eat`
--
CREATE TABLE IF NOT EXISTS `ready_to_eat` (
`id` int(11) unsigned
,`name` varchar(100)
,`url` varchar(200)
,`recipe` text
,`created_at` datetime
,`updated_at` datetime
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `ready_to_eat_tags`
--
CREATE TABLE IF NOT EXISTS `ready_to_eat_tags` (
`id` int(10) unsigned
,`name` varchar(100)
);
-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(50) COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `state`) VALUES
(1, 'pending'),
(2, 'ongoing'),
(3, 'done'),
(4, 'ignored'),
(5, 'on hold');

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(200) COLLATE utf8_swedish_ci NOT NULL,
  `priority_id` tinyint(3) unsigned NOT NULL,
  `status_id` tinyint(3) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `priority_id` (`priority_id`),
  KEY `status_id` (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `item`, `priority_id`, `status_id`, `created_at`, `updated_at`, `completed_at`) VALUES
(1, 'rebuild db: category->tag', 4, 3, '2012-05-24 02:01:38', NULL, '2012-05-24 02:05:54'),
(2, 'refit code: category->tag', 4, 3, '2012-05-24 02:01:38', NULL, '2012-05-24 02:05:54'),
(3, 'tag_controller', 4, 3, '2012-05-24 02:03:31', NULL, '2012-05-24 02:05:54'),
(4, 'tag views', 3, 3, '2012-05-24 02:03:31', NULL, '2012-05-24 02:05:54'),
(5, 'application tabs', 3, 3, '2012-05-24 02:08:53', NULL, '2012-05-24 02:08:53'),
(6, 'new meal by random tag', 2, 3, '2012-05-24 02:08:53', NULL, '2012-05-24 04:04:22'),
(7, 'row count below lists', 1, 1, '2012-05-24 02:27:37', NULL, NULL),
(8, 'redo display order on meals', 1, 5, '2012-05-24 02:30:02', '2012-05-26 16:40:13', NULL),
(9, 'default tab, today entry', 1, 1, '2012-05-24 02:34:25', NULL, NULL),
(10, 'add todo stats', 1, 1, '2012-05-24 02:36:23', NULL, NULL),
(11, 'test new dish form', 2, 1, '2012-05-24 02:45:32', NULL, NULL),
(12, 'redo controller/view error handling', 2, 1, '2012-05-24 02:49:18', NULL, NULL),
(13, 'todays dish header', 1, 1, '2012-05-24 02:50:55', NULL, NULL),
(14, 'todo (controller, db, views)', 2, 3, '2012-05-24 02:52:37', NULL, '2012-05-24 04:05:14'),
(15, 'calendar view', 2, 3, '2012-05-25 23:01:30', '2012-05-26 16:41:13', '2012-05-26 16:28:20'),
(16, 'shopping list', 2, 1, '2012-05-25 23:02:20', '2012-05-26 16:38:50', NULL),
(17, 'new dish + today', 2, 1, '2012-05-25 23:12:28', NULL, NULL),
(18, 'new header/main menu', 3, 1, '2012-05-25 23:24:50', NULL, NULL),
(19, 'week view', 2, 3, '2012-05-26 16:42:14', NULL, '2012-05-26 16:42:14');

-- --------------------------------------------------------

--
-- Stand-in structure for view `uneaten_dishes`
--
CREATE TABLE IF NOT EXISTS `uneaten_dishes` (
`id` int(10) unsigned
,`name` varchar(100)
,`url` varchar(200)
,`recipe` text
,`created_at` datetime
,`updated_at` datetime
);
-- --------------------------------------------------------

--
-- Structure for view `available_dishes`
--
DROP TABLE IF EXISTS `available_dishes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `available_dishes` AS select `dish`.`id` AS `id`,`dish`.`name` AS `name`,`dish`.`url` AS `url`,`dish`.`recipe` AS `recipe`,`dish`.`created_at` AS `created_at`,`dish`.`updated_at` AS `updated_at` from `dish` where (not(`dish`.`id` in (select `meal`.`dish_id` from `meal` where (`meal`.`date` between (curdate() - interval 10 day) and (curdate() + interval 10 day)))));

-- --------------------------------------------------------

--
-- Structure for view `tag_usage`
--
DROP TABLE IF EXISTS `tag_usage`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tag_usage` AS select `tag`.`id` AS `id`,`tag`.`name` AS `tag`,count(`dish_tags`.`dish_id`) AS `times_used` from (`tag` left join `dish_tags` on((`dish_tags`.`tag_id` = `tag`.`id`))) group by `tag`.`id`,`tag`.`name` order by count(`dish_tags`.`dish_id`) desc;

-- --------------------------------------------------------

--
-- Structure for view `meal_tag`
--
DROP TABLE IF EXISTS `meal_tag`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `meal_tag` AS select `tag`.`id` AS `id`,`tag`.`name` AS `tag`,count(`meal`.`dish_id`) AS `times_eaten` from ((`tag` left join `dish_tags` on((`dish_tags`.`tag_id` = `tag`.`id`))) left join `meal` on((`meal`.`dish_id` = `dish_tags`.`dish_id`))) group by `tag`.`id`,`tag`.`name` order by count(`meal`.`dish_id`) desc;

-- --------------------------------------------------------

--
-- Structure for view `ready_to_eat`
--
DROP TABLE IF EXISTS `ready_to_eat`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ready_to_eat` AS select `available_dishes`.`id` AS `id`,`available_dishes`.`name` AS `name`,`available_dishes`.`url` AS `url`,`available_dishes`.`recipe` AS `recipe`,`available_dishes`.`created_at` AS `created_at`,`available_dishes`.`updated_at` AS `updated_at` from `available_dishes` union all select `uneaten_dishes`.`id` AS `id`,`uneaten_dishes`.`name` AS `name`,`uneaten_dishes`.`url` AS `url`,`uneaten_dishes`.`recipe` AS `recipe`,`uneaten_dishes`.`created_at` AS `created_at`,`uneaten_dishes`.`updated_at` AS `updated_at` from `uneaten_dishes`;

-- --------------------------------------------------------

--
-- Structure for view `ready_to_eat_tags`
--
DROP TABLE IF EXISTS `ready_to_eat_tags`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ready_to_eat_tags` AS select `tag`.`id` AS `id`,`tag`.`name` AS `name` from ((`tag` join `dish_tags` on((`dish_tags`.`tag_id` = `tag`.`id`))) join `ready_to_eat` on((`ready_to_eat`.`id` = `dish_tags`.`dish_id`)));

-- --------------------------------------------------------

--
-- Structure for view `uneaten_dishes`
--
DROP TABLE IF EXISTS `uneaten_dishes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `uneaten_dishes` AS select `dish`.`id` AS `id`,`dish`.`name` AS `name`,`dish`.`url` AS `url`,`dish`.`recipe` AS `recipe`,`dish`.`created_at` AS `created_at`,`dish`.`updated_at` AS `updated_at` from `dish` where (not(`dish`.`id` in (select `meal`.`dish_id` from `meal`)));

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dish_tags`
--
ALTER TABLE `dish_tags`
  ADD CONSTRAINT `dish_tags_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`),
  ADD CONSTRAINT `dish_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`);

--
-- Constraints for table `meal`
--
ALTER TABLE `meal`
  ADD CONSTRAINT `meal_ibfk_1` FOREIGN KEY (`dish_id`) REFERENCES `dish` (`id`);

--
-- Constraints for table `todo`
--
ALTER TABLE `todo`
  ADD CONSTRAINT `todo_ibfk_3` FOREIGN KEY (`priority_id`) REFERENCES `priority` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `todo_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

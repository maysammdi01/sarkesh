-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 27, 2014 at 09:38 AM
-- Server version: 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `blocks`
--

CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `value` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL DEFAULT '0',
  `plugin` int(11) NOT NULL,
  `position` varchar(45) NOT NULL,
  `permissions` varchar(45) DEFAULT NULL,
  `pages` varchar(45) DEFAULT NULL,
  `show_header` tinyint(1) DEFAULT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `plugin_idx` (`plugin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `blocks`
--

INSERT INTO `blocks` (`id`, `name`, `value`, `plugin`, `position`, `permissions`, `pages`, `show_header`, `rank`) VALUES
(6, 'content', '0', 3, 'content', NULL, '', 0, 0),
(7, 'login_block', '0', 2, 'sidebar1', NULL, NULL, 1, 3),
(12, 'select_lang', '0', 4, 'sidebar1', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `card`
--

CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cardname` int(11) unsigned DEFAULT NULL,
  `price` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `card`
--

INSERT INTO `card` (`id`, `cardname`, `price`) VALUES
(1, 43434, 3434);

-- --------------------------------------------------------

--
-- Table structure for table `contentcatalogue`
--

CREATE TABLE IF NOT EXISTS `contentcatalogue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_name` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `contentcatalogue`
--

INSERT INTO `contentcatalogue` (`id`, `access_name`, `name`) VALUES
(16, 'news', 'news');

-- --------------------------------------------------------

--
-- Table structure for table `contentcontent`
--

CREATE TABLE IF NOT EXISTS `contentcontent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `header` varchar(200) DEFAULT NULL,
  `date` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `show_author` tinyint(4) NOT NULL,
  `show_date` tinyint(4) NOT NULL,
  `can_comment` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `contentcontent`
--

INSERT INTO `contentcontent` (`id`, `header`, `date`, `user`, `show_author`, `show_date`, `can_comment`) VALUES
(1, 'Hello World!', 1412751997, 1, 1, 1, 1),
(2, 'babak', 1415538001, 1, 1, 1, 1),
(3, '', 1417006562, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contentparts`
--

CREATE TABLE IF NOT EXISTS `contentparts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `pattern` int(30) NOT NULL,
  `options` text NOT NULL,
  `position` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contentparts`
--

INSERT INTO `contentparts` (`id`, `content`, `rank`, `pattern`, `options`, `position`) VALUES
(1, 1, 0, 0, 'This is first post in sarkesh', 'content');

-- --------------------------------------------------------

--
-- Table structure for table `contentpatterns`
--

CREATE TABLE IF NOT EXISTS `contentpatterns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(30) NOT NULL,
  `catalogue` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `options` varchar(500) NOT NULL,
  `position` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `contentpatterns`
--

INSERT INTO `contentpatterns` (`id`, `label`, `catalogue`, `rank`, `type`, `options`, `position`) VALUES
(11, 'body', 16, 0, 'Textarea', 'editor:1;', '');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `place` int(11) NOT NULL,
  `address` varchar(300) NOT NULL,
  `date` varchar(11) NOT NULL,
  `user` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `place`, `address`, `date`, `user`, `size`) VALUES
(5, 'sorkhpost.jpg', 1, 'http://localhostupload/files/sorkhpost.jpg', '1412063025', 0, 173942),
(6, '84825656190514sorkhpost.jpg', 1, 'http://localhostupload/files/84825656190514sorkhpost.jpg', '1412063103', 0, 173942),
(7, '1713023707270sorkhpost.jpg', 1, 'http://localhostupload/files/1713023707270sorkhpost.jpg', '1412063110', 0, 173942),
(8, '90723523637279sorkhpost.jpg', 1, 'http://localhostupload/files/90723523637279sorkhpost.jpg', '1412754683', 0, 173942);

-- --------------------------------------------------------

--
-- Table structure for table `file_places`
--

CREATE TABLE IF NOT EXISTS `file_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `options` text NOT NULL,
  `state` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `file_places`
--

INSERT INTO `file_places` (`id`, `name`, `class_name`, `options`, `state`) VALUES
(1, 'Local Strong', 'files_local', 'upload/files/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `url` text NOT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  `rank` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `links`
--

INSERT INTO `links` (`id`, `ref_id`, `label`, `url`, `enable`, `rank`) VALUES
(1, 1, 'Home', '<FRONT>', 1, 0),
(2, 1, 'Forums', '?plugin=forum', 1, 1),
(3, 1, 'Downloads', '?plugin=content&action=show&cat=download', 1, 1),
(4, 1, 'About us', '?plugin=content&action=show&id=about_us', 1, 3),
(6, 1, 'TEST', 'http://google.com', 1, 0),
(7, 3, 'about us', 'dddd', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `localize`
--

CREATE TABLE IF NOT EXISTS `localize` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main` int(1) NOT NULL,
  `name` varchar(90) NOT NULL,
  `language` varchar(7) NOT NULL,
  `language_name` varchar(30) DEFAULT 'English - United States',
  `home` varchar(100) NOT NULL,
  `email` varchar(30) NOT NULL,
  `calendar` varchar(20) NOT NULL DEFAULT 'gregorian',
  `direction` varchar(4) NOT NULL DEFAULT 'LTR',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `localize`
--

INSERT INTO `localize` (`id`, `main`, `name`, `language`, `language_name`, `home`, `email`, `calendar`, `direction`) VALUES
(1, 0, 'Sarkesh', 'en_US', 'English - United States', '?plugin=users&action=register', 'info@sarkesh.org', 'gregorian', 'LTR'),
(2, 1, 'سرکش', 'fa_IR', 'فارسی - ایران', '?plugin=users&action=register', 'info@sarkesh.org', 'jallali', 'RTL');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `header` varchar(50) DEFAULT NULL,
  `direction` varchar(1) NOT NULL DEFAULT 'h',
  `position` varchar(50) NOT NULL,
  `localize` varchar(10) NOT NULL DEFAULT 'en_US',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  `administrator_admin_panel` tinyint(4) NOT NULL DEFAULT '0',
  `content_cat_insert` int(11) NOT NULL DEFAULT '0',
  `content_cat_edit` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `enable`, `administrator_admin_panel`, `content_cat_insert`, `content_cat_edit`) VALUES
(1, 'Administrators', 1, 1, 1, 1),
(2, 'users', 1, 0, 0, 0),
(3, 'Not activated', 0, 0, 0, 0),
(4, 'guest', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `enable` tinyint(1) NOT NULL,
  `can_edite` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `plugins`
--

INSERT INTO `plugins` (`id`, `name`, `enable`, `can_edite`) VALUES
(2, 'users', 1, 0),
(3, 'administrator', 1, 0),
(4, 'languages', 1, 0),
(6, 'hello', 1, 1),
(11, 'files', 1, 0),
(12, 'msg', 1, 0),
(13, 'log', 1, 0),
(14, 'content', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `registry`
--

CREATE TABLE IF NOT EXISTS `registry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin` int(11) NOT NULL,
  `a_key` varchar(45) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  KEY `fk_plugin_idx` (`plugin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `registry`
--

INSERT INTO `registry` (`id`, `plugin`, `a_key`, `value`) VALUES
(1, 3, 'validator_last_check', '1387753577'),
(2, 3, 'validator_max_time', '77000'),
(3, 3, 'cookie_max_time', '77000'),
(4, 3, 'jquery', '1'),
(5, 3, 'editor', '0'),
(6, 2, 'register', '1'),
(7, 3, 'bootstrap', '1'),
(8, 2, 'active_from_email', '1'),
(9, 2, 'default_permation', '2'),
(12, 3, 'pace_theme', 'pace-theme-center-simple'),
(13, 2, 'register_captcha', '1'),
(15, 3, 'default_timezone', 'America/Los_Angeles'),
(16, 3, 'active_theme', 'simple'),
(17, 3, '1st_template', '0'),
(18, 2, 'register_date_format', 'y/m/d'),
(19, 14, 'date_format', 'y/m/d');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(100) NOT NULL,
  `permission` int(1) NOT NULL,
  `register_date` int(11) NOT NULL,
  `validator` int(11) DEFAULT NULL,
  `forget` varchar(11) DEFAULT NULL,
  `last_login` int(11) DEFAULT NULL,
  `login_key` int(11) unsigned DEFAULT NULL,
  `photo` int(11) NOT NULL DEFAULT '0',
  `permation` int(11) unsigned DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `permission`, `register_date`, `validator`, `forget`, `last_login`, `login_key`, `photo`, `permation`, `state`, `code`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 'info@test.org', 1, 1412751997, 55, '', 1412751997, 91, 5, NULL, NULL, NULL),
(6, 'morrning', '90deff4b32c134f32e3f0d7e8a2aad92', 'alizadeh.babak@gmail.com', 0, 1412786925, NULL, NULL, NULL, NULL, 0, 2, 'NA', '1d48txim52'),
(7, '', '', '', 0, 0, NULL, NULL, NULL, 71, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `validator`
--

CREATE TABLE IF NOT EXISTS `validator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `source` varchar(45) NOT NULL,
  `special_id` varchar(45) NOT NULL,
  `valid_time` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

--
-- Dumping data for table `validator`
--

INSERT INTO `validator` (`id`, `source`, `special_id`, `valid_time`) VALUES
(38, 'USERS_LOGIN', 'lt8n72wnub', '1396278409'),
(39, 'USERS_LOGIN', '2w83crc462', '1396552530'),
(40, 'USERS_LOGIN', 'mury4ymxnw', '1396590297'),
(41, 'USERS_LOGIN', '2z373wajoe', '1396874206'),
(42, 'USERS_LOGIN', 'paq2ivf5mn', '1397334024'),
(43, 'USERS_LOGIN', 'l2rfq2dqem', '1397334329'),
(45, 'USERS_ACTIVE', 'u85erc8xwk', '1397505851'),
(46, 'USERS_LOGIN', 'wm16a6365n', '1398237718'),
(47, 'USERS_LOGIN', '0zng1vgc2f', '1398426134'),
(48, 'USERS_LOGIN', 'jfcjaqz3n0', '1399013450'),
(49, 'USERS_LOGIN', 'l9l43zebcp', '1399039852'),
(50, 'USERS_LOGIN', '7v40usd1f3', '1399134305'),
(51, 'USERS_LOGIN', 'o8uxh6jswn', '1399352212'),
(54, 'USERS_LOGIN', 'qj4knruvgw', '1399639054'),
(55, 'USERS_LOGIN', 'onf0cooh46', '1399700686'),
(56, 'USERS_LOGIN', 'irbd4su8gv', '1408439000'),
(58, 'USERS_LOGIN', 'ocn800irph', '1410600455'),
(60, 'USERS_LOGIN', '0loll6eyus', '1410955410'),
(61, 'USERS_LOGIN', 'r7d3wc5rn5', '1412054296'),
(62, 'USERS_LOGIN', 'eohvw8utqa', '1412226494'),
(63, 'USERS_LOGIN', '4jabehrv3c', '1413050550'),
(64, 'USERS_LOGIN', 'cv4aup05ux', '1413134654'),
(65, 'USERS_LOGIN', 'qmha9cnhkn', '1413395101'),
(68, 'USERS_LOGIN', 'zqfpwanfy1', '1413478762'),
(69, 'USERS_LOGIN', '7d6p9cxtd3', '1413603302'),
(71, 'USERS_LOGIN', '5sn336oa7l', '1413939386'),
(72, 'USERS_LOGIN', '0hki41zbw3', '1414135411'),
(73, 'USERS_LOGIN', 'x560owqe7m', '1414216563'),
(74, 'USERS_LOGIN', '729zwfz4oj', '1414308771'),
(75, 'USERS_LOGIN', 'ktdx4xkenr', '1414402931'),
(78, 'USERS_LOGIN', 'xbu7wq8140', '1414514690'),
(79, 'USERS_LOGIN', 'e5bag4xzqn', '1414591849'),
(81, 'USERS_LOGIN', 'az90gqycdd', '1414776502'),
(82, 'USERS_LOGIN', '04axdn0j00', '1414836657'),
(83, 'USERS_LOGIN', '4v9vy85bhc', '1414997992'),
(84, 'USERS_LOGIN', 'yqj73jy553', '1415263163'),
(85, 'USERS_LOGIN', 'lckuywkymz', '1415351476'),
(86, 'USERS_LOGIN', 'szb3rasbrj', '1415609846'),
(87, 'USERS_LOGIN', 'rfmzyvinqp', '1416196034'),
(88, 'USERS_LOGIN', 'h7zk6rtq70', '1416464195'),
(89, 'USERS_LOGIN', '37zg1v0m7v', '1416890765'),
(90, 'USERS_LOGIN', 'wbvgt5kmkk', '1417082946');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocks`
--
ALTER TABLE `blocks`
  ADD CONSTRAINT `plugin` FOREIGN KEY (`plugin`) REFERENCES `plugins` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `registry`
--
ALTER TABLE `registry`
  ADD CONSTRAINT `fk_plugin` FOREIGN KEY (`plugin`) REFERENCES `plugins` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

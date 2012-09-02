-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306

-- Generation Time: Apr 21, 2010 at 10:28 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `TEMP`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_chat`
--

CREATE TABLE `admin_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` varchar(25) COLLATE utf8_lithuanian_ci NOT NULL,
  `msg` longtext COLLATE utf8_lithuanian_ci NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `balsavimas`
--

CREATE TABLE `balsavimas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `info` set('vis','nar') COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `ips` text COLLATE utf8_lithuanian_ci,
  `nariai` text COLLATE utf8_lithuanian_ci,
  `autorius` int(11) DEFAULT NULL,
  `laikas` int(10) DEFAULT NULL,
  `ijungtas` set('TAIP','NE') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'TAIP',
  `klausimas` text COLLATE utf8_lithuanian_ci,
  `pirmas` text COLLATE utf8_lithuanian_ci,
  `antras` text COLLATE utf8_lithuanian_ci,
  `trecias` text COLLATE utf8_lithuanian_ci,
  `ketvirtas` text COLLATE utf8_lithuanian_ci,
  `penktas` text COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `chat_box`
--

CREATE TABLE `chat_box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nikas` varchar(150) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `msg` varchar(250) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `niko_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `chat_box`
--


-- --------------------------------------------------------

--
-- Table structure for table `duk`
--

CREATE TABLE `duk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klausimas` varchar(200) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `atsakymas` text COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `duk`
--


-- --------------------------------------------------------

--
-- Table structure for table `d_forumai`
--

CREATE TABLE `d_forumai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pav` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `place` int(11) DEFAULT NULL,
  `teises` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `d_straipsniai`
--

CREATE TABLE `d_straipsniai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pav` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `last_data` int(10) DEFAULT NULL,
  `last_nick` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `autorius` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `uzrakinta` set('taip','ne') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'ne',
  `sticky` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `d_temos`
--

CREATE TABLE `d_temos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `pav` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `aprasymas` varchar(255) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `last_data` int(10) DEFAULT NULL,
  `last_nick` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `teises` varchar(250) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `d_temos`
--


-- --------------------------------------------------------

--
-- Table structure for table `d_zinute`
--

CREATE TABLE `d_zinute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `nick` int(11) DEFAULT NULL,
  `zinute` text COLLATE utf8_lithuanian_ci,
  `laikas` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `galerija`
--

CREATE TABLE `galerija` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pavadinimas` char(100) COLLATE utf8_lithuanian_ci DEFAULT 'Be pavadinimo',
  `file` char(100) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'none.png',
  `foto` text COLLATE utf8_lithuanian_ci,
  `apie` longtext COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `autorius` int(6) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL,
  `categorija` int(3) DEFAULT '1',
  `teises` varchar(2) COLLATE utf8_lithuanian_ci DEFAULT 'N;',
  `rodoma` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NE',
  PRIMARY KEY (`ID`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `grupes`
--

CREATE TABLE `grupes` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(128) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `aprasymas` mediumtext COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `teises` varchar(150) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;',
  `pav` varchar(256) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `path` varchar(150) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '0',
  `kieno` varchar(255) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `place` int(11) NOT NULL DEFAULT '1',
  `mod` text COLLATE utf8_lithuanian_ci,
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=3 ;



-- --------------------------------------------------------

--
-- Table structure for table `kas_prisijunges`
--

CREATE TABLE `kas_prisijunges` (
  `id` int(11) NOT NULL,
  `uid` varchar(11) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '',
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `file` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `user` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `agent` varchar(255) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `ref` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `clicks` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  KEY `ip` (`ip`),
  KEY `file` (`file`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;



-- --------------------------------------------------------

--
-- Table structure for table `knyga`
--

CREATE TABLE `knyga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nikas` varchar(150) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `msg` varchar(250) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `msg` (`msg`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `kom`
--

CREATE TABLE `kom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kid` int(11) NOT NULL DEFAULT '0',
  `pid` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '0',
  `zinute` text COLLATE utf8_lithuanian_ci,
  `nick` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `nick_id` int(11) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;


--
-- Table structure for table `logai`
--

CREATE TABLE `logai` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `action` text COLLATE utf8_lithuanian_ci,
  `time` int(10) DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `naujienos`
--

CREATE TABLE `naujienos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `kategorija` int(2) NOT NULL DEFAULT '1',
  `naujiena` mediumtext COLLATE utf8_lithuanian_ci,
  `daugiau` text COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `data` int(10) DEFAULT NULL,
  `autorius` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `kom` set('taip','ne') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'taip',
  `rodoma` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NE',
  `sticky` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `naujienos`
--

INSERT INTO `naujienos` VALUES(1, 'Welcome to the introduction of MightMedia CMS ', 0, 'You have successfully installed <a target="_blank" href="http://www.mightmedia.lt"> MightMedia CMS </a>. They are the authors of the <a target =" _blank href = "http://coders.lt"> <strong> CodeRS </strong> </a>. \r\n', '', 'lt', 1213129845, 'Admin', 'taip', 'TAIP', 0);

-- --------------------------------------------------------

--
-- Table structure for table `newsgetters`
--

CREATE TABLE `newsgetters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `newsgetters`
--

-- --------------------------------------------------------

--
-- Table structure for table `nuorodos`
--

CREATE TABLE `nuorodos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat` int(3) NOT NULL DEFAULT '1',
  `url` varchar(200) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'http://',
  `pavadinimas` varchar(200) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '0',
  `click` int(11) NOT NULL DEFAULT '0',
  `nick` int(5) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  `apie` text COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `active` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NE',
  `balsai` int(255) NOT NULL DEFAULT '0',
  `balsavo` text COLLATE utf8_lithuanian_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `nustatymai`
--

CREATE TABLE `nustatymai` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `key` varchar(128) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `val` text COLLATE utf8_lithuanian_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=20 ;

--
-- Dumping data for table `nustatymai`
--

INSERT INTO `nustatymai` VALUES(1, 'Pavadinimas', 'MightMedia CMS');
INSERT INTO `nustatymai` VALUES(2, 'Apie', 'About');
INSERT INTO `nustatymai` VALUES(3, 'Keywords', 'CMS, mightmedia, coders');
INSERT INTO `nustatymai` VALUES(4, 'Copyright', '<a href="http://www.mightmedia.org" target="_blank">MightMedia CMS</a>');
INSERT INTO `nustatymai` VALUES(5, 'Palaikymas', '1');
INSERT INTO `nustatymai` VALUES(6, 'Maintenance', 'Came back later.');
INSERT INTO `nustatymai` VALUES(7, 'Chat_limit', '5');
INSERT INTO `nustatymai` VALUES(8, 'News_limit', '5');
INSERT INTO `nustatymai` VALUES(9, 'Stilius', 'default');
INSERT INTO `nustatymai` VALUES(10, 'Bandymai', '3');
INSERT INTO `nustatymai` VALUES(11, 'fotodyd', '450');
INSERT INTO `nustatymai` VALUES(12, 'minidyd', '150');
INSERT INTO `nustatymai` VALUES(13, 'galbalsuot', '1');
INSERT INTO `nustatymai` VALUES(14, 'fotoperpsl', '10');
INSERT INTO `nustatymai` VALUES(15, 'galkom', '1');
INSERT INTO `nustatymai` VALUES(16, 'pirminis', 'naujienos');
INSERT INTO `nustatymai` VALUES(17, 'keshas', '1');
INSERT INTO `nustatymai` VALUES(18, 'kmomentarai_sveciams', '0');
INSERT INTO `nustatymai` VALUES(19, 'F_urls', ';');
INSERT INTO `nustatymai` VALUES(20, 'galorder', 'data');
INSERT INTO `nustatymai` VALUES(21, 'galorder_type', 'DESC');
INSERT INTO `nustatymai` VALUES(22, 'Editor', 'markitup');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pavadinimas` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `file` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `show` enum('Y','N') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'Y',
  `teises` varchar(150) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;',
  `parent` int(150) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `page`
--

INSERT INTO `page` VALUES(1, 'Forum', 'lt', 'frm.php', 3, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(2, 'News', 'lt', 'naujienos.php', 1, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(3, 'About', 'lt', 'apie.php', 5, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(4, 'Register', 'lt', 'reg.php', 13, 'N', 'N;', 0);
INSERT INTO `page` VALUES(5, 'Password', 'lt', 'slaptazodzio_priminimas.php', 12, 'N', 'N;', 0);
INSERT INTO `page` VALUES(6, 'Profile', 'lt', 'edit_user.php', 11, 'N', 'N;', 0);
INSERT INTO `page` VALUES(7, 'Search', 'lt', 'search.php', 6, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(8, 'PM', 'lt', 'pm.php', 0, 'N', 'N;', 0);
INSERT INTO `page` VALUES(9, 'Profile', 'lt', 'view_user.php', 9, 'N', 'N;', 0);
INSERT INTO `page` VALUES(10, 'Users', 'lt', 'nariai.php', 10, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(11, 'Gallery', 'lt', 'galerija.php', 4, 'Y', 'N;', 0);
INSERT INTO `page` VALUES(12, 'Articles', 'lt', 'straipsnis.php', 2, 'Y', 'N;', 0);

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE `panel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `panel` varchar(100) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `file` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `align` enum('R','L','C') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'C',
  `rodyti` varchar(4) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `show` enum('Y','N') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'Y',
  `teises` varchar(150) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `panel`
--

INSERT INTO `panel` VALUES(1, 'User', 'lt', 'vartotojas.php', 1, 'L', 'Taip', 'Y', 'N;');
INSERT INTO `panel` VALUES(2, 'Menu', 'lt', 'meniu.php', 2, 'L', 'Taip', 'Y', 'N;');
INSERT INTO `panel` VALUES(4, 'Shout Box', 'lt', 'shoutbox.php', 3, 'R', 'Taip', 'Y', 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `private_msg`
--

CREATE TABLE `private_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `to` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '...',
  `msg` text COLLATE utf8_lithuanian_ci,
  `read` set('YES','NO') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NO',
  `date` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `private_msg`
--


-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rating_id` varchar(80) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `rating_num` int(11) DEFAULT NULL,
  `IP` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `psl` varchar(255) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `salis`
--

CREATE TABLE `salis` (
  `iso` char(2) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT '',
  `name` varchar(80) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `printable_name` varchar(80) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `iso3` char(3) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Dumping data for table `salis`
--

INSERT INTO `salis` VALUES('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440);
INSERT INTO `salis` VALUES('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643);
INSERT INTO `salis` VALUES('US', 'UNITED STATES', 'United States', 'USA', 840);

-- --------------------------------------------------------

--
-- Table structure for table `siuntiniai`
--

CREATE TABLE `siuntiniai` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `paspaudimai` decimal(11,0) NOT NULL DEFAULT '0',
  `pavadinimas` char(100) COLLATE utf8_lithuanian_ci DEFAULT 'Untitled',
  `file` char(100) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'none.png',
  `foto` text COLLATE utf8_lithuanian_ci,
  `apie` longtext COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `autorius` int(6) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL,
  `categorija` int(3) DEFAULT '1',
  `teises` varchar(2) COLLATE utf8_lithuanian_ci DEFAULT 'N;',
  `rodoma` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NE',
  PRIMARY KEY (`ID`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `straipsniai`
--

CREATE TABLE `straipsniai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `pav` varchar(255) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `t_text` text COLLATE utf8_lithuanian_ci,
  `f_text` longtext COLLATE utf8_lithuanian_ci,
  `lang` varchar(3) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `date` int(11) DEFAULT NULL,
  `autorius` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `autorius_id` int(11) DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `click` int(11) DEFAULT NULL,
  `kom` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'ne',
  `rodoma` varchar(4) COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'NE',
  `kat` int(125) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `straipsniai`
--


-- --------------------------------------------------------

--
-- Table structure for table `s_punktai`
--

CREATE TABLE `s_punktai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pav` varchar(255) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `s_punktai`
--

INSERT INTO `s_punktai` VALUES(1, 'CMS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(10) unsigned DEFAULT NULL,
  `nick` varchar(15) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `levelis` int(2) NOT NULL DEFAULT '3',
  `pass` varchar(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `slaptas` char(32) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `icq` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `msn` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `skype` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `yahoo` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `aim` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `url` varchar(50) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `salis` varchar(3) COLLATE utf8_lithuanian_ci DEFAULT 'LT',
  `miestas` varchar(20) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `vardas` varchar(15) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `pavarde` varchar(25) COLLATE utf8_lithuanian_ci DEFAULT NULL,
  `gim_data` date DEFAULT NULL,
  `parasas` text COLLATE utf8_lithuanian_ci,
  `forum_temos` int(11) NOT NULL DEFAULT '0',
  `forum_atsakyta` int(11) NOT NULL DEFAULT '0',
  `taskai` decimal(11,0) NOT NULL DEFAULT '0',
  `balsai` int(11) NOT NULL DEFAULT '0',
  `balsavo` text COLLATE utf8_lithuanian_ci,
  `pm_viso` int(11) NOT NULL DEFAULT '50',
  `reg_data` int(10) DEFAULT NULL,
  `login_data` int(10) DEFAULT NULL,
  `login_before` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, NULL, 'Admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'info@localhost', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LT', NULL, NULL, NULL, NULL, 'System Admin', 0, 0, '0', 0, NULL, 500, NULL, NULL, NULL);

CREATE TABLE `poll_answers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer` text CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL,
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

CREATE TABLE `poll_questions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question` text CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL,
  `radio` int(1) NOT NULL DEFAULT '0',
  `shown` int(1) NOT NULL DEFAULT '0',
  `only_guests` int(1) NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT '1',
  `author_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'Admin',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

CREATE TABLE `poll_votes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL DEFAULT '0',
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer_id` int(255) NOT NULL DEFAULT '0',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;
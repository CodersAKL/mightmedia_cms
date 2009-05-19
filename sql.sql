-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Darbinė stotis: mysql2.bendras.com
-- Atlikimo laikas:  2008 m. Gruodžio 29 d.  15:30
-- Serverio versija: 5.0.32
-- PHP versija: 5.2.2-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Duombazė: `mightmediatvs`
--

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `admin_chat`
--

CREATE TABLE IF NOT EXISTS `admin_chat` (
  `id` int(11) NOT NULL auto_increment,
  `admin` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `msg` longtext collate utf8_lithuanian_ci NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;




-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `balsavimas`
--

CREATE TABLE IF NOT EXISTS `balsavimas` (
  `id` int(11) NOT NULL auto_increment,
  `info` set('vis','nar') collate utf8_lithuanian_ci default NULL,
  `ips` text collate utf8_lithuanian_ci,
  `nariai` text collate utf8_lithuanian_ci default NULL,
  `autorius` int(11) default NULL,
  `laikas` int(10) default NULL,
  `ijungtas` set('TAIP','NE') collate utf8_lithuanian_ci NOT NULL default 'TAIP',
  `klausimas` text collate utf8_lithuanian_ci default NULL,
  `pirmas` text collate utf8_lithuanian_ci default NULL,
  `antras` text collate utf8_lithuanian_ci default NULL,
  `trecias` text collate utf8_lithuanian_ci default NULL,
  `ketvirtas` text collate utf8_lithuanian_ci default NULL,
  `penktas` text collate utf8_lithuanian_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `chat_box`
--

CREATE TABLE IF NOT EXISTS `chat_box` (
  `id` int(11) NOT NULL auto_increment,
  `nikas` varchar(150) collate utf8_lithuanian_ci default NULL,
  `msg` varchar(250) collate utf8_lithuanian_ci default NULL,
  `time` datetime default NULL,
  `niko_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų struktūra lentelei `duk`
--

CREATE TABLE IF NOT EXISTS `duk` (
  `id` int(11) NOT NULL auto_increment,
  `klausimas` varchar(200) collate utf8_lithuanian_ci default NULL,
  `atsakymas` text collate utf8_lithuanian_ci default NULL,
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_forumai`
--

CREATE TABLE IF NOT EXISTS `d_forumai` (
  `id` int(11) NOT NULL auto_increment,
  `pav` varchar(100) collate utf8_lithuanian_ci default NULL,
  `place` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;





-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_straipsniai`
--

CREATE TABLE IF NOT EXISTS `d_straipsniai` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `pav` varchar(100) collate utf8_lithuanian_ci default NULL,
  `last_data` int(10) default NULL,
  `last_nick` varchar(25) collate utf8_lithuanian_ci default NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci default NULL,
  `uzrakinta` set('taip','ne') collate utf8_lithuanian_ci NOT NULL default 'ne',
  `sticky` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_temos`
--

CREATE TABLE IF NOT EXISTS `d_temos` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) default NULL,
  `pav` varchar(100) collate utf8_lithuanian_ci default NULL,
  `aprasymas` varchar(255) collate utf8_lithuanian_ci default NULL,
  `last_data` int(10) default NULL,
  `last_nick` varchar(25) collate utf8_lithuanian_ci default NULL,
  `place` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `d_temos`
--



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_zinute`
--

CREATE TABLE IF NOT EXISTS `d_zinute` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) default NULL,
  `sid` int(11) default NULL,
  `nick` int(11) default NULL,
  `zinute` text collate utf8_lithuanian_ci default NULL,
  `laikas` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;




-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `galerija`
--

CREATE TABLE IF NOT EXISTS `galerija` (
  `ID` int(11) NOT NULL auto_increment,
  `pavadinimas` char(100) collate utf8_lithuanian_ci default 'Be pavadinimo',
  `file` char(100) collate utf8_lithuanian_ci NOT NULL default 'none.png',
  `foto` text collate utf8_lithuanian_ci,
  `apie` longtext collate utf8_lithuanian_ci,
  `autorius` int(6) NOT NULL default '0',
  `data` int(10) default NULL,
  `categorija` int(3) default '1',
  `teises` varchar(2) collate utf8_lithuanian_ci default NULL,
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;




-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `grupes`
--

CREATE TABLE IF NOT EXISTS `grupes` (
  `id` int(3) NOT NULL auto_increment,
  `pavadinimas` varchar(128) collate utf8_lithuanian_ci default NULL,
  `aprasymas` mediumtext collate utf8_lithuanian_ci default NULL,
  `teises` varchar(150) collate utf8_lithuanian_ci NOT NULL default 'i:0;',
  `pav` varchar(256) collate utf8_lithuanian_ci default NULL,
  `path` varchar(150) collate utf8_lithuanian_ci NOT NULL default '0',
  `kieno` varchar(255) collate utf8_lithuanian_ci default NULL,
  `place` int(11) NOT NULL default 1,
  `mod` text collate utf8_lithuanian_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;





-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kas_prisijunges`
--

CREATE TABLE IF NOT EXISTS `kas_prisijunges` (
  `id` int(11) NOT NULL,
  `uid` varchar(11) collate utf8_lithuanian_ci default NULL,
  `timestamp` int(15) NOT NULL default '0',
  `ip` varchar(40) collate utf8_lithuanian_ci default NULL,
  `file` varchar(100) collate utf8_lithuanian_ci default NULL,
  `user` varchar(100) collate utf8_lithuanian_ci default NULL,
  `agent` varchar(255) collate utf8_lithuanian_ci default NULL,
  `ref` varchar(100) collate utf8_lithuanian_ci default NULL,
  `clicks` float NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `ip` (`ip`),
  KEY `file` (`file`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `knyga`
--

CREATE TABLE IF NOT EXISTS `knyga` (
  `id` int(11) NOT NULL auto_increment,
  `nikas` varchar(150) collate utf8_lithuanian_ci default NULL,
  `msg` varchar(250) collate utf8_lithuanian_ci default NULL,
  `time` int(10) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `msg` (`msg`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kom`
--

CREATE TABLE IF NOT EXISTS `kom` (
  `id` int(11) NOT NULL auto_increment,
  `kid` int(11) NOT NULL default '0',
  `pid` varchar(255) collate utf8_lithuanian_ci NOT NULL default '0',
  `zinute` text collate utf8_lithuanian_ci default NULL,
  `nick` char(50) collate utf8_lithuanian_ci default NULL,
  `nick_id` int(11) NOT NULL default '0',
  `data` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;


--
-- Sukurta duomenų struktūra lentelei `logai`
--

CREATE TABLE IF NOT EXISTS `logai` (
  `id` int(10) NOT NULL auto_increment,
  `action` text collate utf8_lithuanian_ci default NULL,
  `time` int(10) default NULL,
  `ip` varchar(15) collate utf8_lithuanian_ci NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;



-- --------------------------------------------------------


--
-- Sukurta duomenų struktūra lentelei `naujienos`
--

CREATE TABLE IF NOT EXISTS `naujienos` (
  `id` int(11) NOT NULL auto_increment,
  `pavadinimas` varchar(100) collate utf8_lithuanian_ci default NULL,
  `kategorija` int(2) NOT NULL default '1',
  `naujiena` mediumtext collate utf8_lithuanian_ci default NULL,
  `daugiau` text collate utf8_lithuanian_ci default NULL,
  `data` int(10) default NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci default NULL,
  `kom` set('taip','ne') collate utf8_lithuanian_ci NOT NULL default 'taip',
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `naujienos`
--

INSERT INTO `naujienos` (`pavadinimas`, `kategorija`, `naujiena`, `daugiau`, `data`, `autorius`, `kom`, `rodoma`) VALUES 
('Sveikiname įdiegus MightMedia TVS', 0, 'Jūs sėkmingai įdiegėte <a target="_blank" href="http://www.mightmedia.lt">MightMedia TVS</a> . Jos autoriai <a target="_blank" href="http://code.google.com/p/coders/"><strong>CodeRS</strong></a> . Prašome nepasisavinti autorinių teisių, priešingu atveju jūs pažeisite GNU teises.', 'Kiekvienam puslapyje privalomas užrašas apačioje "<a target="_blank" href="http://www.mightmedia.lt/">MightMedia</a>" su nuoroda į <a target="_blank" href="http://www.mightmedia.lt/">http://www.mightmedia.lt</a>\r\n', 1213129845, 'Admin', 'taip', 'TAIP');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `nuorodos`
--

CREATE TABLE IF NOT EXISTS `nuorodos` (
  `id` int(11) NOT NULL auto_increment,
  `cat` int(3) NOT NULL default '1',
  `url` varchar(200) collate utf8_lithuanian_ci NOT NULL default 'http://',
  `pavadinimas` varchar(200) character set utf8 collate utf8_lithuanian_ci NOT NULL default '0',
  `click` int(11) NOT NULL default '0',
  `nick` int(5) default NULL,
  `date` int(10) default NULL,
  `apie` text character set utf8 collate utf8_lithuanian_ci default NULL,
  `active` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  `balsai` int(255) NOT NULL default '0',
  `balsavo` text character set utf8 collate utf8_lithuanian_ci default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `nustatymai`
--

CREATE TABLE IF NOT EXISTS `nustatymai` (
  `id` int(6) NOT NULL auto_increment,
  `key` varchar(128) collate utf8_lithuanian_ci default NULL,
  `val` text collate utf8_lithuanian_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `nustatymai`
--

INSERT INTO `nustatymai` (`key`, `val`) VALUES
('Pavadinimas', 'MightMedia TVS'),
('Apie', '<br />'),
('Keywords', ''),
('Render', '1'),
('Copyright', '<a href="http://www.mightmedia.lt" target="_blank">MightMedia TVS</a>'),
('Pastas', 'info@mrcbug.com'),
('Registracija', '1'),
('Palaikymas', '0'),
('Maintenance', 'Remontuojame<br />'),
('Chat_limit', '5'),
('News_limit', '5'),
('Stilius', 'default'),
('Bandymai', '3'),
('fotodyd', '450'),
('minidyd', '150'),
('galbalsuot', '1'),
('fotoperpsl', '10'),
('galkom', '1'),
('pirminis', 'naujienos.php'),
('keshas', '1'),
('kalba', 'lt.php');
-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL auto_increment,
  `pavadinimas` varchar(100) collate utf8_lithuanian_ci default NULL,
  `file` varchar(50) collate utf8_lithuanian_ci default NULL,
  `place` int(11) default NULL,
  `show` enum('Y','N') collate utf8_lithuanian_ci NOT NULL default 'Y',
  `teises` varchar(150) collate utf8_lithuanian_ci NOT NULL default 'a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"0";}',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `page`
--

INSERT INTO `page` (`pavadinimas`, `file`, `place`, `show`) VALUES
('Forumas', 'frm.php', 4, 'Y'),
('Naujienos', 'naujienos.php', 3, 'Y'),
('Apie', 'apie.php', 2, 'Y'),
('Registracija', 'reg.php', 0, 'N'),
('Nuorodos', 'nuorodos.php', 0, 'Y'),
('Slaptažodis', 'slaptazodzio_priminimas.php', 2, 'N'),
('Profilio redagavimas', 'edit_user.php', 1, 'N'),
('Paieška', 'search.php', 6, 'Y'),
('Kontaktai', 'kontaktas.php', 7, 'Y'),
('Prisijungę', 'online.php', 1, 'Y'),
('Archyvas', 'deze.php', 0, 'N'),
('Asmeniniai pranešimai', 'pm.php', 0, 'N'),
('Profilis', 'view_user.php', 0, 'N'),
('Nariai', 'nariai.php', 0, 'Y'),
('Straipsniai', 'straipsnis.php', 0, 'Y');

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `panel`
--

CREATE TABLE IF NOT EXISTS `panel` (
  `id` int(11) NOT NULL auto_increment,
  `panel` varchar(100) collate utf8_lithuanian_ci default NULL,
  `file` varchar(50) collate utf8_lithuanian_ci default NULL,
  `place` int(11) default NULL,
  `align` enum('R','L') collate utf8_lithuanian_ci NOT NULL default 'L',
  `show` enum('Y','N') collate utf8_lithuanian_ci NOT NULL default 'Y',
  `teises` varchar(150) collate utf8_lithuanian_ci NOT NULL default 'a:3:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"0";}',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `panel`
--

INSERT INTO `panel` (`panel`, `file`, `place`, `align`, `show`) VALUES
('Narys', 'vartotojas.php', 1, 'L', 'Y'),
('Meniu', 'meniu.php', 2, 'L', 'Y'),
('Kalendorius', 'kalendorius.php', 3, 'R', 'Y'),
('Rėksnių dėžė', 'shoutbox.php', 4, 'R', 'Y');


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `private_msg`
--

CREATE TABLE IF NOT EXISTS `private_msg` (
  `id` int(11) NOT NULL auto_increment,
  `from` varchar(25) collate utf8_lithuanian_ci default NULL,
  `to` varchar(25) collate utf8_lithuanian_ci default NULL,
  `title` varchar(100) collate utf8_lithuanian_ci NOT NULL default '...',
  `msg` text collate utf8_lithuanian_ci default NULL,
  `read` set('YES','NO') collate utf8_lithuanian_ci NOT NULL default 'NO',
  `date` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `private_msg`
--


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `rating_id` varchar(80) default NULL,
  `rating_num` int(11) default NULL,
  `IP` varchar(25) default NULL,
  `psl` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `ratings`
--



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `salis`
--

CREATE TABLE IF NOT EXISTS `salis` (
  `iso` char(2) collate utf8_lithuanian_ci default NULL,
  `name` varchar(80) collate utf8_lithuanian_ci default NULL,
  `printable_name` varchar(80) collate utf8_lithuanian_ci default NULL,
  `iso3` char(3) collate utf8_lithuanian_ci default NULL,
  `numcode` smallint(6) default NULL,
  PRIMARY KEY  (`iso`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci;

--
-- Sukurta duomenų kopija lentelei `salis`
--

INSERT INTO `salis` (`iso`, `name`, `printable_name`, `iso3`, `numcode`) VALUES
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
('US', 'UNITED STATES', 'United States', 'USA', 840);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `siuntiniai`
--

CREATE TABLE IF NOT EXISTS `siuntiniai` (
  `ID` int(11) NOT NULL auto_increment,
  `pavadinimas` char(100) collate utf8_lithuanian_ci default 'Be pavadinimo',
  `file` char(100) collate utf8_lithuanian_ci NOT NULL default 'none.png',
  `foto` text collate utf8_lithuanian_ci,
  `apie` longtext collate utf8_lithuanian_ci,
  `autorius` int(6) NOT NULL default '0',
  `data` int(10) default NULL,
  `categorija` int(3) default '1',
  `teises` varchar(2) collate utf8_lithuanian_ci default NULL,
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `straipsniai`
--

CREATE TABLE IF NOT EXISTS `straipsniai` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) default NULL,
  `pav` varchar(255) collate utf8_lithuanian_ci default NULL,
  `t_text` text collate utf8_lithuanian_ci default NULL,
  `f_text` longtext collate utf8_lithuanian_ci default NULL,
  `date` int(11) default NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci default NULL,
  `autorius_id` int(11) default NULL,
  `vote` int(11) default NULL,
  `click` int(11) default NULL,
  `kom` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'ne',
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  `kat` int(125) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `straipsniai`
--


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `s_punktai`
--

CREATE TABLE IF NOT EXISTS `s_punktai` (
  `id` int(11) NOT NULL auto_increment,
  `pav` varchar(255) collate utf8_lithuanian_ci default NULL,
  `sid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `s_punktai`
--

INSERT INTO `s_punktai` (`pav`, `sid`) VALUES
('Atviras Kodas', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `ip` int(10) unsigned default NULL,
  `nick` varchar(15) collate utf8_lithuanian_ci default NULL,
  `levelis` int(2) NOT NULL default '3',
  `pass` varchar(32) collate utf8_lithuanian_ci default NULL,
  `email` varchar(50) collate utf8_lithuanian_ci default NULL,
  `slaptas` char(32) collate utf8_lithuanian_ci default NULL,
  `icq` varchar(50) collate utf8_lithuanian_ci default NULL,
  `msn` varchar(50) collate utf8_lithuanian_ci default NULL,
  `skype` varchar(50) collate utf8_lithuanian_ci default NULL,
  `yahoo` varchar(50) collate utf8_lithuanian_ci default NULL,
  `aim` varchar(50) collate utf8_lithuanian_ci default NULL,
  `url` varchar(50) collate utf8_lithuanian_ci default NULL,
  `salis` varchar(3) collate utf8_lithuanian_ci default 'LT',
  `miestas` varchar(20) collate utf8_lithuanian_ci default NULL,
  `vardas` varchar(15) collate utf8_lithuanian_ci default NULL,
  `pavarde` varchar(25) collate utf8_lithuanian_ci default NULL,
  `gim_data` date default NULL,
  `parasas` text collate utf8_lithuanian_ci default NULL,
  `forum_temos` int(11) NOT NULL default '0',
  `forum_atsakyta` int(11) NOT NULL default '0',
  `taskai` decimal(11,0) NOT NULL default '0',
  `balsai` int(11) NOT NULL default '0',
  `balsavo` text collate utf8_lithuanian_ci default NULL,
  `pm_viso` int(11) NOT NULL default '50',
  `reg_data` int(10) default NULL,
  `login_data` int(10) default NULL,
  `login_before` int(10) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci ;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`nick`, `levelis`, `pass`, `email`, `salis`, `parasas`, `pm_viso`) VALUES
('Admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'info@mrcbug.com', 'LT', 'Svetainės administratorius', 500);


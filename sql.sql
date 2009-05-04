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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=10 ;




-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `balsavimas`
--

CREATE TABLE IF NOT EXISTS `balsavimas` (
  `id` int(11) NOT NULL auto_increment,
  `info` set('vis','nar') collate utf8_lithuanian_ci NOT NULL,
  `ips` text collate utf8_lithuanian_ci,
  `nariai` text collate utf8_lithuanian_ci NOT NULL,
  `autorius` int(11) NOT NULL,
  `laikas` int(10) NOT NULL,
  `ijungtas` set('TAIP','NE') collate utf8_lithuanian_ci NOT NULL default 'TAIP',
  `klausimas` text collate utf8_lithuanian_ci NOT NULL,
  `pirmas` text collate utf8_lithuanian_ci NOT NULL,
  `antras` text collate utf8_lithuanian_ci NOT NULL,
  `trecias` text collate utf8_lithuanian_ci NOT NULL,
  `ketvirtas` text collate utf8_lithuanian_ci NOT NULL,
  `penktas` text collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `chat_box`
--

CREATE TABLE IF NOT EXISTS `23_chat_box` (
  `id` int(11) NOT NULL auto_increment,
  `nikas` varchar(150) collate utf8_lithuanian_ci NOT NULL,
  `msg` varchar(250) collate utf8_lithuanian_ci NOT NULL,
  `time` datetime NOT NULL,
  `niko_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=49 ;

--
-- Sukurta duomenų struktūra lentelei `duk`
--

CREATE TABLE IF NOT EXISTS `duk` (
  `id` int(11) NOT NULL auto_increment,
  `klausimas` varchar(200) collate utf8_lithuanian_ci NOT NULL,
  `atsakymas` text collate utf8_lithuanian_ci NOT NULL,
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=9 ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_forumai`
--

CREATE TABLE IF NOT EXISTS `d_forumai` (
  `id` int(11) NOT NULL auto_increment,
  `pav` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `place` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=9 ;





-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_straipsniai`
--

CREATE TABLE IF NOT EXISTS `d_straipsniai` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `pav` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `last_data` int(10) NOT NULL,
  `last_nick` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `uzrakinta` set('taip','ne') collate utf8_lithuanian_ci NOT NULL default 'ne',
  `sticky` int(1) NOT NULL default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=4 ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_temos`
--

CREATE TABLE IF NOT EXISTS `d_temos` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL,
  `pav` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `aprasymas` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `last_data` int(10) NOT NULL,
  `last_nick` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `place` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=7 ;

--
-- Sukurta duomenų kopija lentelei `d_temos`
--



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `d_zinute`
--

CREATE TABLE IF NOT EXISTS `d_zinute` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `nick` int(11) NOT NULL,
  `zinute` text collate utf8_lithuanian_ci NOT NULL,
  `laikas` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=22 ;




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
  `data` int(10) NOT NULL,
  `categorija` int(3) default '1',
  `teises` varchar(2) collate utf8_lithuanian_ci NOT NULL,
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=29 ;




-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `grupes`
--

CREATE TABLE IF NOT EXISTS `grupes` (
  `id` int(3) NOT NULL auto_increment,
  `pavadinimas` varchar(128) collate utf8_lithuanian_ci NOT NULL,
  `aprasymas` mediumtext collate utf8_lithuanian_ci NOT NULL,
  `teises` int(2) NOT NULL default '1',
  `pav` varchar(256) collate utf8_lithuanian_ci NOT NULL,
  `path` varchar(150) collate utf8_lithuanian_ci NOT NULL default '0',
  `kieno` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `place` int(11) NOT NULL default 1,
  `mod` text collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=36 ;





-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kas_prisijunges`
--

CREATE TABLE IF NOT EXISTS `kas_prisijunges` (
  `id` int(11) NOT NULL,
  `uid` varchar(11) collate utf8_lithuanian_ci NOT NULL,
  `timestamp` int(15) NOT NULL default '0',
  `ip` varchar(40) collate utf8_lithuanian_ci NOT NULL,
  `file` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `user` varchar(100) collate utf8_lithuanian_ci default NULL,
  `agent` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `ref` varchar(100) collate utf8_lithuanian_ci default NULL,
  `clicks` float default NULL,
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
  `nikas` varchar(150) collate utf8_lithuanian_ci NOT NULL,
  `msg` varchar(250) collate utf8_lithuanian_ci NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `msg` (`msg`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `kom`
--

CREATE TABLE IF NOT EXISTS `kom` (
  `id` int(11) NOT NULL auto_increment,
  `kid` int(11) NOT NULL default '0',
  `pid` varchar(255) collate utf8_lithuanian_ci NOT NULL default '0',
  `zinute` text collate utf8_lithuanian_ci NOT NULL,
  `nick` char(50) collate utf8_lithuanian_ci NOT NULL,
  `nick_id` int(11) NOT NULL default '0',
  `data` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=72 ;


--
-- Sukurta duomenų struktūra lentelei `logai`
--

CREATE TABLE IF NOT EXISTS `logai` (
  `id` int(10) NOT NULL auto_increment,
  `action` text collate utf8_lithuanian_ci NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(15) collate utf8_lithuanian_ci NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=87 ;



-- --------------------------------------------------------


--
-- Sukurta duomenų struktūra lentelei `naujienos`
--

CREATE TABLE IF NOT EXISTS `naujienos` (
  `id` int(11) NOT NULL auto_increment,
  `pavadinimas` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `kategorija` int(2) NOT NULL default '1',
  `naujiena` mediumtext collate utf8_lithuanian_ci NOT NULL,
  `daugiau` text collate utf8_lithuanian_ci NOT NULL,
  `data` int(10) NOT NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `kom` set('taip','ne') collate utf8_lithuanian_ci NOT NULL default 'taip',
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=15 ;

--
-- Sukurta duomenų kopija lentelei `naujienos`
--

INSERT INTO `naujienos` (`id`, `pavadinimas`, `kategorija`, `naujiena`, `daugiau`, `data`, `autorius`, `kom`, `rodoma`) VALUES 
(1, 'Sveikiname įdiegus MightMedia TVS', 0, 'Jūs sėkmingai įdiegėte <a target="_blank" href="http://www.mightmedia.lt">MightMedia TVS</a> . Jos autoriai <a target="_blank" href="http://code.google.com/p/coders/"><strong>CodeRS</strong></a> . Prašome nepasisavinti autorinių teisių, priešingu atveju jūs pažeisite GNU teises.', 'Kiekvienam puslapyje privalomas užrašas apačioje "<a target="_blank" href="http://www.mightmedia.lt/">MightMedia</a>" su nuoroda į <a target="_blank" href="http://www.mightmedia.lt/">http://www.mightmedia.lt</a>\r\n', 1213129845, 'Admin', 'taip', 'TAIP');

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
  `nick` int(5) NOT NULL,
  `date` int(10) NOT NULL,
  `apie` text character set utf8 collate utf8_lithuanian_ci NOT NULL,
  `active` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  `balsai` int(255) NOT NULL default '0',
  `balsavo` text character set utf8 collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=11 ;


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `nustatymai`
--

CREATE TABLE IF NOT EXISTS `nustatymai` (
  `id` int(6) NOT NULL auto_increment,
  `key` varchar(128) collate utf8_lithuanian_ci NOT NULL,
  `val` text collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=22 ;

--
-- Sukurta duomenų kopija lentelei `nustatymai`
--

INSERT INTO `nustatymai` (`id`, `key`, `val`) VALUES
(1, 'Pavadinimas', 'MightMedia TVS'),
(2, 'Apie', '<br />'),
(3, 'Keywords', ''),
(4, 'Render', '1'),
(5, 'Copyright', '<a href="http://www.mightmedia.lt" target="_blank">MightMedia TVS</a>'),
(6, 'Pastas', 'info@mrcbug.com'),
(7, 'Registracija', '1'),
(8, 'Palaikymas', '0'),
(9, 'Maintenance', 'Remontuojame<br />'),
(10, 'Chat_limit', '5'),
(11, 'News_limit', '5'),
(12, 'Stilius', 'default'),
(13, 'Bandymai', '3'),
(14, 'fotodyd', '450'),
(15, 'minidyd', '150'),
(16, 'galbalsuot', '1'),
(17, 'fotoperpsl', '10'),
(18, 'galkom', '1'),
(19, 'pirminis', 'naujienos.php'),
(20, 'kalba', 'lt.php');
-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(11) NOT NULL auto_increment,
  `pavadinimas` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `file` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `place` int(11) NOT NULL,
  `show` enum('Y','N') collate utf8_lithuanian_ci NOT NULL default 'Y',
  `teises` int(30) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=71 ;

--
-- Sukurta duomenų kopija lentelei `page`
--

INSERT INTO `page` (`id`, `pavadinimas`, `file`, `place`, `show`, `teises`) VALUES
(1, 'Forumas', 'frm.php', 4, 'Y', 0),
(2, 'Naujienos', 'naujienos.php', 3, 'Y', 0),
(3, 'Apie', 'apie.php', 2, 'Y', 0),
(4, 'Registracija', 'reg.php', 0, 'N', 0),
(5, 'Nuorodos', 'nuorodos.php', 0, 'Y', 0),
(6, 'Slaptažodis', 'slaptazodzio_priminimas.php', 2, 'N', 0),
(7, 'Profilio redagavimas', 'edit_user.php', 1, 'N', 0),
(8, 'Paieška', 'search.php', 6, 'Y', 0),
(9, 'Kontaktai', 'kontaktas.php', 7, 'Y', 0),
(10, 'Prisijungę', 'online.php', 1, 'Y', 0),
(11, 'Archyvas', 'deze.php', 0, 'N', 0),
(12, 'Asmeniniai pranešimai', 'pm.php', 0, 'N', 0),
(13, 'Profilis', 'view_user.php', 0, 'N', 0),
(14, 'Nariai', 'nariai.php', 0, 'Y', 0),
(15, 'Straipsniai', 'straipsnis.php', 0, 'Y', 0);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `panel`
--

CREATE TABLE IF NOT EXISTS `panel` (
  `id` int(11) NOT NULL auto_increment,
  `panel` varchar(100) collate utf8_lithuanian_ci NOT NULL,
  `file` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `place` int(11) NOT NULL,
  `align` enum('R','L') collate utf8_lithuanian_ci NOT NULL default 'L',
  `show` enum('Y','N') collate utf8_lithuanian_ci NOT NULL default 'Y',
  `teises` int(30) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=37 ;

--
-- Sukurta duomenų kopija lentelei `panel`
--

INSERT INTO `panel` (`id`, `panel`, `file`, `place`, `align`, `show`, `teises`) VALUES
(1, 'Narys', 'vartotojas.php', 1, 'L', 'Y', 0),
(2, 'Meniu', 'meniu.php', 2, 'L', 'Y', 0);
(3, 'Kalendorius', 'kalendorius.php', 1, 'R', 'Y', 0);
(4, 'Rėksnių dėžė', 'shoutbox.php', 2, 'R', 'Y', 0);


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `private_msg`
--

CREATE TABLE IF NOT EXISTS `private_msg` (
  `id` int(11) NOT NULL auto_increment,
  `from` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `to` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `title` varchar(100) collate utf8_lithuanian_ci NOT NULL default '...',
  `msg` text collate utf8_lithuanian_ci NOT NULL,
  `read` set('YES','NO') collate utf8_lithuanian_ci NOT NULL default 'NO',
  `date` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=23 ;

--
-- Sukurta duomenų kopija lentelei `private_msg`
--


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL auto_increment,
  `rating_id` varchar(80) NOT NULL,
  `rating_num` int(11) NOT NULL,
  `IP` varchar(25) NOT NULL,
  `psl` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

--
-- Sukurta duomenų kopija lentelei `ratings`
--



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `salis`
--

CREATE TABLE IF NOT EXISTS `salis` (
  `iso` char(2) collate utf8_lithuanian_ci NOT NULL,
  `name` varchar(80) collate utf8_lithuanian_ci NOT NULL,
  `printable_name` varchar(80) collate utf8_lithuanian_ci NOT NULL,
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
  `data` int(10) NOT NULL,
  `categorija` int(3) default '1',
  `teises` varchar(2) collate utf8_lithuanian_ci NOT NULL,
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=10 ;



-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `straipsniai`
--

CREATE TABLE IF NOT EXISTS `straipsniai` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `pav` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `t_text` text collate utf8_lithuanian_ci NOT NULL,
  `f_text` longtext collate utf8_lithuanian_ci NOT NULL,
  `date` int(11) NOT NULL,
  `autorius` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `autorius_id` int(11) NOT NULL,
  `vote` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  `kom` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'ne',
  `rodoma` varchar(4) collate utf8_lithuanian_ci NOT NULL default 'NE',
  `kat` int(125) default 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=1 ;

--
-- Sukurta duomenų kopija lentelei `straipsniai`
--


-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `s_punktai`
--

CREATE TABLE IF NOT EXISTS `s_punktai` (
  `id` int(11) NOT NULL auto_increment,
  `pav` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=2 ;

--
-- Sukurta duomenų kopija lentelei `s_punktai`
--

INSERT INTO `s_punktai` (`id`, `pav`, `sid`) VALUES
(1, 'Atviras Kodas', 1);

-- --------------------------------------------------------

--
-- Sukurta duomenų struktūra lentelei `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `ip` int(10) unsigned default NULL,
  `nick` varchar(15) collate utf8_lithuanian_ci NOT NULL,
  `levelis` int(2) NOT NULL default '3',
  `pass` varchar(32) collate utf8_lithuanian_ci NOT NULL,
  `email` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `slaptas` char(32) collate utf8_lithuanian_ci default NULL,
  `icq` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `msn` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `skype` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `yahoo` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `aim` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `url` varchar(50) collate utf8_lithuanian_ci NOT NULL,
  `salis` varchar(3) collate utf8_lithuanian_ci NOT NULL,
  `miestas` varchar(20) collate utf8_lithuanian_ci NOT NULL,
  `vardas` varchar(15) collate utf8_lithuanian_ci NOT NULL,
  `pavarde` varchar(25) collate utf8_lithuanian_ci NOT NULL,
  `amzius` int(2) NOT NULL,
  `gim_data` date NOT NULL,
  `parasas` text collate utf8_lithuanian_ci NOT NULL,
  `forum_temos` int(11) NOT NULL default '0',
  `forum_atsakyta` int(11) NOT NULL default '0',
  `taskai` decimal(11,0) NOT NULL default '0',
  `atsiusta_straipsniu` int(11) NOT NULL default '0',
  `atsiusta_pamoku` int(11) NOT NULL default '0',
  `atsiusta_scriptu` int(11) NOT NULL default '0',
  `atsiusta_naujienu` int(11) NOT NULL default '0',
  `balsai` int(11) NOT NULL default '0',
  `balsavo` text collate utf8_lithuanian_ci NOT NULL,
  `pm_viso` int(11) NOT NULL default '50',
  `reg_data` int(10) NOT NULL,
  `login_data` int(10) NOT NULL,
  `login_before` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=9 ;

--
-- Sukurta duomenų kopija lentelei `users`
--

INSERT INTO `users` (`id`, `ip`, `nick`, `levelis`, `pass`, `email`, `slaptas`, `icq`, `msn`, `skype`, `yahoo`, `aim`, `url`, `salis`, `miestas`, `vardas`, `pavarde`, `amzius`, `gim_data`, `parasas`, `forum_temos`, `forum_atsakyta`, `taskai`, `atsiusta_straipsniu`, `atsiusta_pamoku`, `atsiusta_scriptu`, `atsiusta_naujienu`, `balsai`, `balsavo`, `pm_viso`, `reg_data`, `login_data`) VALUES
(1, 2130706433, 'Admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'info@mrcbug.com', NULL, '', '', '', '', '', '', 'LT', '', '', '', 0, '', '', 0, 0, 0, 0, 0, 0, 0, 0, '', 50, 2008, 1213131454);


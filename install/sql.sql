
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_chat`
--

CREATE TABLE `admin_chat` (
  `id` int(11) NOT NULL,
  `admin` varchar(255) NOT NULL,
  `msg` longtext NOT NULL,
  `date` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chat_box`
--

CREATE TABLE `chat_box` (
  `id` int(11) NOT NULL,
  `nikas` varchar(255) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `niko_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chat_box`
--

INSERT INTO `chat_box` (`id`, `nikas`, `msg`, `time`, `niko_id`) VALUES
(1, 'Sistema', 'Labas, Pasauli :)', '2012-09-09 13:48:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `duk`
--

CREATE TABLE `duk` (
  `id` int(11) NOT NULL,
  `klausimas` varchar(255) DEFAULT NULL,
  `atsakymas` text,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `order` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `d_forumai`
--

CREATE TABLE `d_forumai` (
  `id` int(11) NOT NULL,
  `pav` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `place` int(11) DEFAULT NULL,
  `teises` varchar(255) NOT NULL DEFAULT 'N;'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `d_straipsniai`
--

CREATE TABLE `d_straipsniai` (
  `id` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `pav` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `last_data` int(10) DEFAULT NULL,
  `last_nick` varchar(255) DEFAULT NULL,
  `autorius` varchar(255) DEFAULT NULL,
  `uzrakinta` set('taip','ne') NOT NULL DEFAULT 'ne',
  `sticky` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `d_temos`
--

CREATE TABLE `d_temos` (
  `id` int(11) NOT NULL,
  `fid` int(11) DEFAULT NULL,
  `pav` varchar(255) DEFAULT NULL,
  `aprasymas` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `last_data` int(10) DEFAULT NULL,
  `last_nick` varchar(255) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `teises` varchar(255) NOT NULL DEFAULT 'N;'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `d_zinute`
--

CREATE TABLE `d_zinute` (
  `id` int(11) NOT NULL,
  `tid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL,
  `nick` int(11) DEFAULT NULL,
  `zinute` text,
  `laikas` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `galerija`
--

CREATE TABLE `galerija` (
  `ID` int(11) NOT NULL,
  `pavadinimas` varchar(255) DEFAULT 'Be pavadinimo',
  `file` varchar(255) NOT NULL DEFAULT 'none.png',
  `foto` text,
  `apie` longtext,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `autorius` int(6) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL,
  `categorija` int(3) DEFAULT '1',
  `teises` varchar(255) DEFAULT 'N;',
  `kom` set('taip','ne') NOT NULL DEFAULT 'taip',
  `rodoma` varchar(4) NOT NULL DEFAULT 'NE'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `grupes`
--

CREATE TABLE `grupes` (
  `id` int(3) NOT NULL,
  `pavadinimas` varchar(255) DEFAULT NULL,
  `aprasymas` mediumtext,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `teises` varchar(255) NOT NULL DEFAULT 'N;',
  `pav` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL DEFAULT '0',
  `kieno` varchar(255) DEFAULT NULL,
  `place` int(11) NOT NULL DEFAULT '1',
  `mod` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kas_prisijunges`
--

CREATE TABLE `kas_prisijunges` (
  `id` int(11) NOT NULL,
  `uid` varchar(11) NOT NULL DEFAULT '',
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(45) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `agent` varchar(255) DEFAULT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `clicks` float NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `knyga`
--

CREATE TABLE `knyga` (
  `id` int(11) NOT NULL,
  `nikas` varchar(255) DEFAULT NULL,
  `msg` varchar(255) DEFAULT NULL,
  `time` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kom`
--

CREATE TABLE `kom` (
  `id` int(11) NOT NULL,
  `kid` int(11) NOT NULL DEFAULT '0',
  `pid` varchar(255) NOT NULL DEFAULT '0',
  `zinute` text,
  `nick` varchar(255) DEFAULT NULL,
  `nick_id` int(11) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `logai`
--

CREATE TABLE `logai` (
  `id` int(10) NOT NULL,
  `action` text,
  `time` int(10) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `naujienos`
--

CREATE TABLE `naujienos` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(255) DEFAULT NULL,
  `kategorija` int(2) NOT NULL DEFAULT '1',
  `naujiena` mediumtext,
  `daugiau` text,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `data` int(10) DEFAULT NULL,
  `autorius` varchar(255) DEFAULT NULL,
  `kom` set('taip','ne') NOT NULL DEFAULT 'taip',
  `rodoma` varchar(4) NOT NULL DEFAULT 'NE',
  `sticky` smallint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `naujienos`
--

INSERT INTO `naujienos` (`id`, `pavadinimas`, `kategorija`, `naujiena`, `daugiau`, `lang`, `data`, `autorius`, `kom`, `rodoma`, `sticky`) VALUES
(1, 'Sveikiname įdiegus MightMedia TVS v.1.46', 0, 'Jūs sėkmingai įdiegėte <a target="_blank" title="MightMedia TVS" href="http://www.mightmedia.lt">MightMedia TVS</a> . Jos autoriai <a target="_blank" href="http://code.google.com/p/coders/"><strong>CodeRS</strong></a> . Prašome nepasisavinti autorinių teisių, priešingu atveju jūs pažeisite GNU teises.', 'Kiekvienam puslapyje privalomas užrašas apačioje "<a target="_blank" href="http://www.mightmedia.lt/">MightMedia</a>" su nuoroda į <a target="_blank" href="http://www.mightmedia.lt/">http://www.mightmedia.lt</a>\r\n', 'lt', 1346622467, 'Sistema', 'taip', 'TAIP', 0);

-- --------------------------------------------------------

--
-- Table structure for table `newsgetters`
--

CREATE TABLE `newsgetters` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nuorodos`
--

CREATE TABLE `nuorodos` (
  `id` int(11) NOT NULL,
  `cat` int(3) NOT NULL DEFAULT '1',
  `url` varchar(255) DEFAULT NULL,
  `pavadinimas` varchar(255) NOT NULL DEFAULT '0',
  `click` int(11) NOT NULL DEFAULT '0',
  `nick` int(5) DEFAULT NULL,
  `date` int(10) DEFAULT NULL,
  `apie` text,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `active` varchar(4) NOT NULL DEFAULT 'NE',
  `balsai` int(255) NOT NULL DEFAULT '0',
  `balsavo` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nustatymai`
--

CREATE TABLE `nustatymai` (
  `id` int(6) NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `val` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nustatymai`
--

INSERT INTO `nustatymai` (`id`, `key`, `val`) VALUES
(1, 'Pavadinimas', 'MightMedia TVS'),
(2, 'Apie', 'Trumpai apie svetainę'),
(3, 'Keywords', 'TVS, mightmedia, coders'),
(4, 'Copyright', '<a href="http://www.mightmedia.lt" target="_blank">MightMedia TVS</a>'),
(5, 'Palaikymas', '1'),
(6, 'Maintenance', 'Atsiprašome<br /> Svetainė šiuo metu yra tvarkoma.<br /> Prisijungti gali tik administratoriai.'),
(7, 'Chat_limit', '5'),
(8, 'News_limit', '5'),
(9, 'Stilius', 'apelsinas'),
(10, 'Bandymai', '3'),
(11, 'fotodyd', '450'),
(12, 'minidyd', '150'),
(13, 'galbalsuot', '1'),
(14, 'fotoperpsl', '10'),
(15, 'galkom', '1'),
(16, 'pirminis', 'naujienos'),
(17, 'keshas', '0'),
(18, 'kmomentarai_sveciams', '0'),
(19, 'F_urls', ';'),
(20, 'galorder', 'data'),
(21, 'galorder_type', 'DESC'),
(22, 'Editor', 'markitup'),
(23, 'hyphenator', '1'),
(24, 'Pastas', ''),
(25, 'kalba', 'lt.php'),
(26, 'googleanalytics', '');

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `file` varchar(255) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `show` enum('Y','N') NOT NULL DEFAULT 'Y',
  `teises` varchar(255) NOT NULL DEFAULT 'N;',
  `parent` int(150) NOT NULL DEFAULT '0',
  `metatitle` text DEFAULT NULL,
  `metadesc` text DEFAULT NULL,
  `metakeywords` text DEFAULT NULL
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `page`
--

INSERT INTO `page` (`id`, `pavadinimas`, `lang`, `file`, `place`, `show`, `teises`, `parent`) VALUES
(1, 'Naujienos', 'lt', 'puslapiai/naujienos.php', 1, 'Y', 'N;', 0),
(2, 'Apie', 'lt', 'puslapiai/apie.php', 5, 'Y', 'N;', 0),
(3, 'Paieška', 'lt', 'puslapiai/search.php', 6, 'Y', 'N;', 0),
(4, 'Kontaktai', 'lt', 'puslapiai/kontaktas.php', 6, 'Y', 'N;', 0);

-- --------------------------------------------------------

--
-- Table structure for table `panel`
--

CREATE TABLE `panel` (
  `id` int(11) NOT NULL,
  `panel` varchar(255) DEFAULT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `file` varchar(255) DEFAULT NULL,
  `place` int(11) DEFAULT NULL,
  `align` enum('R','L','C') NOT NULL DEFAULT 'C',
  `rodyti` varchar(4) DEFAULT NULL,
  `show` enum('Y','N') NOT NULL DEFAULT 'Y',
  `teises` varchar(255) NOT NULL DEFAULT 'N;'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panel`
--

INSERT INTO `panel` (`id`, `panel`, `lang`, `file`, `place`, `align`, `rodyti`, `show`, `teises`) VALUES
(1, 'Kalendorius', 'lt', 'blokai/kalendorius.php', 1, 'R', 'Taip', 'Y', 'N;'),
(2, 'Meniu', 'lt', 'blokai/meniu.php', 2, 'L', 'Taip', 'Y', 'N;');

-- --------------------------------------------------------

--
-- Table structure for table `poll_answers`
--

CREATE TABLE `poll_answers` (
  `id` int(255) NOT NULL,
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer` text NOT NULL,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `poll_questions`
--

CREATE TABLE `poll_questions` (
  `id` int(255) NOT NULL,
  `question` text NOT NULL,
  `radio` int(1) NOT NULL DEFAULT '0',
  `shown` int(1) NOT NULL DEFAULT '0',
  `only_guests` int(1) NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT '1',
  `author_name` varchar(255) NOT NULL DEFAULT 'Admin',
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `question_id` int(11) NOT NULL DEFAULT '0',
  `answer_id` int(11) NOT NULL DEFAULT '0',
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `private_msg`
--

CREATE TABLE `private_msg` (
  `id` int(11) NOT NULL,
  `from` varchar(255) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL DEFAULT '...',
  `msg` text,
  `read` set('YES','NO') NOT NULL DEFAULT 'NO',
  `date` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `rating_id` varchar(255) DEFAULT NULL,
  `rating_num` int(11) DEFAULT NULL,
  `IP` varchar(255) DEFAULT NULL,
  `psl` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `salis`
--

CREATE TABLE `salis` (
  `iso` varchar(2) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `printable_name` varchar(255) DEFAULT NULL,
  `iso3` varchar(3) DEFAULT NULL,
  `numcode` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `salis`
--

INSERT INTO `salis` (`iso`, `name`, `printable_name`, `iso3`, `numcode`) VALUES
('LT', 'LITHUANIA', 'Lithuania', 'LTU', 440),
('RU', 'RUSSIAN FEDERATION', 'Russian Federation', 'RUS', 643),
('US', 'UNITED STATES', 'United States', 'USA', 840);

-- --------------------------------------------------------

--
-- Table structure for table `siuntiniai`
--

CREATE TABLE `siuntiniai` (
  `ID` int(11) NOT NULL,
  `paspaudimai` decimal(11,0) NOT NULL DEFAULT '0',
  `pavadinimas` varchar(255) DEFAULT 'Be pavadinimo',
  `file` varchar(255) NOT NULL DEFAULT 'none.png',
  `foto` text,
  `apie` longtext,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `autorius` int(6) NOT NULL DEFAULT '0',
  `data` int(10) DEFAULT NULL,
  `categorija` int(3) DEFAULT '1',
  `teises` varchar(255) DEFAULT 'N;',
  `rodoma` varchar(4) NOT NULL DEFAULT 'NE'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `straipsniai`
--

CREATE TABLE `straipsniai` (
  `id` int(11) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `pav` varchar(255) DEFAULT NULL,
  `t_text` text,
  `f_text` longtext,
  `lang` varchar(3) NOT NULL DEFAULT 'lt' COMMENT 'Language',
  `date` int(11) DEFAULT NULL,
  `autorius` varchar(255) DEFAULT NULL,
  `autorius_id` int(11) DEFAULT NULL,
  `vote` int(11) DEFAULT NULL,
  `click` int(11) DEFAULT NULL,
  `kom` varchar(4) NOT NULL DEFAULT 'ne',
  `rodoma` varchar(4) NOT NULL DEFAULT 'NE',
  `kat` int(125) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` varchar(3) DEFAULT 0,
  `options` varchar(255) DEFAULT NULL
  
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `name`, `status`, `options`) VALUES
(1, 'articles', '1', ''),
(2, 'downloads', '1', ''),
(3, 'external_users', '1', ''),
(4, 'faq', '1', ''),
(5, 'forum', '1', ''),
(6, 'gallery', '1', ''),
(7, 'links', '1', ''),
(8, 'polls', '1', '')

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `nick` varchar(150) DEFAULT NULL,
  `levelis` int(2) NOT NULL DEFAULT '3',
  `pass` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `slaptas` varchar(255) DEFAULT NULL,
  `icq` varchar(255) DEFAULT NULL,
  `msn` varchar(255) DEFAULT NULL,
  `skype` varchar(255) DEFAULT NULL,
  `yahoo` varchar(255) DEFAULT NULL,
  `aim` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `salis` varchar(3) DEFAULT 'LT',
  `miestas` varchar(255) DEFAULT NULL,
  `vardas` varchar(255) DEFAULT NULL,
  `pavarde` varchar(255) DEFAULT NULL,
  `gim_data` date DEFAULT NULL,
  `parasas` text,
  `forum_temos` int(11) NOT NULL DEFAULT '0',
  `forum_atsakyta` int(11) NOT NULL DEFAULT '0',
  `taskai` decimal(11,0) NOT NULL DEFAULT '0',
  `balsai` int(11) NOT NULL DEFAULT '0',
  `balsavo` text,
  `pm_viso` int(11) NOT NULL DEFAULT '50',
  `reg_data` int(10) DEFAULT NULL,
  `login_data` int(10) DEFAULT NULL,
  `login_before` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `ip`, `nick`, `levelis`, `pass`, `email`, `slaptas`, `icq`, `msn`, `skype`, `yahoo`, `aim`, `url`, `salis`, `miestas`, `vardas`, `pavarde`, `gim_data`, `parasas`, `forum_temos`, `forum_atsakyta`, `taskai`, `balsai`, `balsavo`, `pm_viso`, `reg_data`, `login_data`, `login_before`) VALUES
(1, NULL, 'Admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'info@localhost', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LT', NULL, NULL, NULL, NULL, 'Svetainės administratorius', 0, 0, '0', 0, NULL, 500, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_chat`
--
ALTER TABLE `admin_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_box`
--
ALTER TABLE `chat_box`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `duk`
--
ALTER TABLE `duk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `d_forumai`
--
ALTER TABLE `d_forumai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `d_straipsniai`
--
ALTER TABLE `d_straipsniai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `d_temos`
--
ALTER TABLE `d_temos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `d_zinute`
--
ALTER TABLE `d_zinute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galerija`
--
ALTER TABLE `galerija`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `grupes`
--
ALTER TABLE `grupes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `kas_prisijunges`
--
ALTER TABLE `kas_prisijunges`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `ip` (`ip`),
  ADD KEY `file` (`file`(250)),
  ADD KEY `timestamp` (`timestamp`);

--
-- Indexes for table `knyga`
--
ALTER TABLE `knyga`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kom`
--
ALTER TABLE `kom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logai`
--
ALTER TABLE `logai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `naujienos`
--
ALTER TABLE `naujienos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `newsgetters`
--
ALTER TABLE `newsgetters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nuorodos`
--
ALTER TABLE `nuorodos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `nustatymai`
--
ALTER TABLE `nustatymai`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `panel`
--
ALTER TABLE `panel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `poll_answers`
--
ALTER TABLE `poll_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `poll_questions`
--
ALTER TABLE `poll_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `private_msg`
--
ALTER TABLE `private_msg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salis`
--
ALTER TABLE `salis`
  ADD PRIMARY KEY (`iso`);

--
-- Indexes for table `siuntiniai`
--
ALTER TABLE `siuntiniai`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `straipsniai`
--
ALTER TABLE `straipsniai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang` (`lang`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nick` (`nick`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`),

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_chat`
--
ALTER TABLE `admin_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_box`
--
ALTER TABLE `chat_box`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `duk`
--
ALTER TABLE `duk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_forumai`
--
ALTER TABLE `d_forumai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_straipsniai`
--
ALTER TABLE `d_straipsniai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_temos`
--
ALTER TABLE `d_temos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `d_zinute`
--
ALTER TABLE `d_zinute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `galerija`
--
ALTER TABLE `galerija`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `grupes`
--
ALTER TABLE `grupes`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `knyga`
--
ALTER TABLE `knyga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `kom`
--
ALTER TABLE `kom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `logai`
--
ALTER TABLE `logai`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `naujienos`
--
ALTER TABLE `naujienos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `newsgetters`
--
ALTER TABLE `newsgetters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `nuorodos`
--
ALTER TABLE `nuorodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `nustatymai`
--
ALTER TABLE `nustatymai`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `panel`
--
ALTER TABLE `panel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `poll_answers`
--
ALTER TABLE `poll_answers`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_questions`
--
ALTER TABLE `poll_questions`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `poll_votes`
--
ALTER TABLE `poll_votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `private_msg`
--
ALTER TABLE `private_msg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `siuntiniai`
--
ALTER TABLE `siuntiniai`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `straipsniai`
--
ALTER TABLE `straipsniai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
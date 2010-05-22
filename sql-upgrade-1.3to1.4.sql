ALTER TABLE `page` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pavadinimas` , ADD INDEX ( `lang` );
ALTER TABLE `panel` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `panel` , ADD INDEX ( `lang` );
ALTER TABLE `siuntiniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `straipsniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `f_text` , ADD INDEX ( `lang` );
ALTER TABLE `nuorodos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `naujienos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `daugiau` , ADD INDEX ( `lang` );
ALTER TABLE `grupes` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `aprasymas` , ADD INDEX ( `lang` );
ALTER TABLE `galerija` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `apie` , ADD INDEX ( `lang` );
ALTER TABLE `d_temos` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `aprasymas` , ADD INDEX ( `lang` );
ALTER TABLE `d_straipsniai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pav` , ADD INDEX ( `lang` );
ALTER TABLE `d_forumai` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `pav` , ADD INDEX ( `lang` );
ALTER TABLE `duk` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `atsakymas` , ADD INDEX ( `lang` );
ALTER TABLE `balsavimas` ADD `lang` VARCHAR( 3 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language' AFTER `penktas` , ADD INDEX ( `lang` );
ALTER TABLE `naujienos` ADD `sticky` SMALLINT( 1 ) NOT NULL DEFAULT '0' AFTER `rodoma`; 
ALTER TABLE `d_temos` ADD `teises` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;' AFTER `place`; 
ALTER TABLE `d_forumai` ADD `teises` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;' AFTER `place`; 
CREATE TABLE `newsgetters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
INSERT INTO `nustatymai` VALUES(20, 'galorder', 'data');
INSERT INTO `nustatymai` VALUES(21, 'galorder_type', 'DESC');
INSERT INTO `nustatymai` VALUES(22, 'Editor', 'markitup');
CREATE TABLE `poll_questions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `radio` int(1) NOT NULL DEFAULT '0',
  `shown` int(1) NOT NULL DEFAULT '0',
  `only_guests` int(1) NOT NULL,
  `author_id` int(11) NOT NULL DEFAULT '1',
  `author_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'Admin',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `poll_votes` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL DEFAULT '0',
  `question_id` int(255) NOT NULL DEFAULT '0',
  `answer_id` int(255) NOT NULL DEFAULT '0',
  `lang` varchar(3) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'lt' COMMENT 'Language',
  PRIMARY KEY (`id`),
  KEY `lang` (`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
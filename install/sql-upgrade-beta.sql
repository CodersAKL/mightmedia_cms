ALTER TABLE `panel` CHANGE `align` `align` enum('R', 'L', 'C') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'C';
ALTER TABLE `panel` ADD `rodyti` varchar(4) COLLATE utf8_lithuanian_ci DEFAULT NULL DEFAULT 'Taip';
ALTER TABLE `siuntiniai` ADD `paspaudimai` decimal(11,0) NOT NULL DEFAULT '0';
ALTER TABLE `galerija` ADD `kom` set('taip','ne') COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'taip';
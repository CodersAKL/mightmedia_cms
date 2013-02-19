INSERT INTO `nustatymai` (`key`, `val`) VALUES ('keshas', '0');
ALTER TABLE `grupes` CHANGE `teises` `teises` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;';
DELETE FROM `nustatymai` WHERE `key`='Pastas';
ALTER TABLE `page` CHANGE `teises` `teises` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;';
ALTER TABLE `page` ADD `parent` INT( 150 ) NOT NULL DEFAULT '0' AFTER `teises` ;
UPDATE `page` SET `teises`='N;';
ALTER TABLE `panel` CHANGE `teises` `teises` VARCHAR( 150 ) CHARACTER SET utf8 COLLATE utf8_lithuanian_ci NOT NULL DEFAULT 'N;';
UPDATE `panel` SET `teises`='N;';
INSERT INTO `nustatymai` ( `key` , `val` )VALUES ( NULL , 'kmomentarai_sveciams', '0' );

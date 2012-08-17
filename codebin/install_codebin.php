<?php
ob_start();
session_start();
include_once (dirname(__file__) . "/priedai/conf.php");
include_once (dirname(__file__) . "/priedai/prisijungimas.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<?php header_info(); ?>
	</head>
	<body>
<?php
if(!isset($_GET['id'])){
$text='<center><h1><a href="?id,2">Sukrti duomenų bazės lentelę</a></h1></center>';
lentele("Įdiegti CODEBIN",$text);
}elseif($_GET['id']==2){
$mysql=mysql_query("CREATE TABLE IF NOT EXISTS `".LENTELES_PRIESAGA."codebin` (
  `id` int(11) NOT NULL auto_increment,
  `data` int(255) NOT NULL,
  `nick_id` int(11) NOT NULL default '0',
  `nick` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `pav` varchar(255) collate utf8_lithuanian_ci NOT NULL,
  `cod` text collate utf8_lithuanian_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_lithuanian_ci AUTO_INCREMENT=21 ;");
if($mysql)$text='CODEBIN modulis įdiegtas, ištrinkite install_codebin.php failą ir įjunkite codebin.php puslapį administravime.';else $text='Klaida. Diegimas nepavyko, patikrinkite mysql prisijungimo duomenis.'.mysql_error();
lentele("Įdiegti CODEBIN",$text);
}

?>
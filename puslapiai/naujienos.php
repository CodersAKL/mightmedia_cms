<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/


if (!defined("OK")) {
	header("location: ?");
	exit;
}
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$kid = (int)$url['k'];
} else {
	$kid = 0;
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}

if ($conf['Palaikymas'] == 1) {
	klaida("{$lang['admin']['maintenance']}", $conf['Maintenance']);
}
$limit = $conf['News_limit'];
$viso = kiek("naujienos", "WHERE `rodoma`='TAIP'");
$text = '';
$data = '';


// Jei niekas nepaspaudziama, o atidaromas pirminis puslapis
if ($kid == 0) {

	$sql = mysql_query1("SELECT SQL_CACHE *, (SELECT SQL_CACHE COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP'
			ORDER BY `data` DESC
			LIMIT {$p},{$limit}", 300);


	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			if (isset($conf['puslapiai']['naujienos.php']['id'])) {
				$extra = "<div style='float: right;'>" . (($row['kom'] == 'taip') ? "<a href='?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . "'>{$lang['news']['read']} • {$lang['news']['comments']} (" . $row['viso'] . ")</a>" : "<a href='?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . "'>{$lang['news']['read']}</a>") . "</div><br />";
			}

			$kategorijos_pav = mysql_query1("SELECT `pav`,`id` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id` = " . escape($row['kategorija']) . " limit 1");
			$pav = "<table><tr valign='top'>";
			if (isset($kategorijos_pav['pav'])) {
				if (isset($conf['puslapiai']['naujkat.php']['id'])) {
					$pav .= "<td style='padding-right:10px'><div class='avataras' style='margin-right:25px'><a href='?id," . $conf['puslapiai']['naujkat.php']['id'] . ";k," . (int)$kategorijos_pav['id'] . "'><img src='images/naujienu_kat/" . input($kategorijos_pav['pav']) . "' alt='img' border='0' /></a></div></td>";
				} else {
					$pav .= "<td style='padding-right:10px'><div class='avataras' style='margin-right:25px'><img src='images/naujienu_kat/" . input($kategorijos_pav['pav']) . "' alt='img' border='0' /></div></td>";
				}

			}
			$pav .= "<td>";
			lentele($row['pavadinimas'], $pav . $row['naujiena'] . "</td></tr></table>" . $extra, false, array(menesis((int)date('m', strtotime(date('Y-m-d H:i:s ', $row['data'])))), (int)date('d', strtotime(date('Y-m-d H:i:s ', $row['data'])))));
		}
	} else {
		lentele("{$lang['news']['news']}", "{$lang['news']['nonews']}");
	}

	if ($viso > $limit) {
		lentele("{$lang['system']['pages']}", puslapiai($p, $limit, $viso, 10));
	}
	unset($sql, $row, $extra, $pav);
}
if ($kid != 0) {
	if (isset($kategorijos_pav['pav'])) {
		$sql = "SELECT SQL_CACHE `" . LENTELES_PRIESAGA . "naujienos`.*, `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises` FROM `" . LENTELES_PRIESAGA . "naujienos` Inner Join `" . LENTELES_PRIESAGA . "grupes` ON `" . LENTELES_PRIESAGA . "naujienos`.`kategorija` = `" . LENTELES_PRIESAGA . "grupes`.`id` WHERE `" . LENTELES_PRIESAGA . "naujienos`.`rodoma`='TAIP'  AND `" . LENTELES_PRIESAGA . "naujienos`.`id` = " . escape($kid) . " limit 1";
		$sql = mysql_query1($sql, 300);
	} else {
		$sql = mysql_query1("
			SELECT SQL_CACHE *, (SELECT SQL_CACHE COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP' AND `id` = " . escape($kid) . " limit 1", 300);
	}
	if (sizeof($sql) > 0) {
		if (!isset($sql['teises'])) {
			$sql['teises'] = 0;
		}
		if (teises($sql['teises'], $_SESSION['level'])) {
			$title = $sql['pavadinimas'];
			$text = "<div class='naujiena'>" . $sql['naujiena'] . "";
			if (!empty($sql['daugiau'])) {
				$text .= '<div class="line"></div>' . $sql['daugiau'];
			}
			$text .= "</div><hr />" . date('Y-m-d H:i:s ', $sql['data']) . ",  <b>" . $sql['autorius'] . "</b>";
			//$text .= "</div>";

			//Atvaizduojam naujieną, likę argumentai - mėnesis žodžiais ir diena skaičiumi
			lentele($title, $text, false, array(menesis((int)date('m', strtotime($sql['data']))), (int)date('d', strtotime($sql['data']))));
			//Susijusios naujienos
			$susijus = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija`=" . escape($sql['kategorija']) . " AND `id`!=" . escape($_GET['k']) . " ORDER by `data` DESC LIMIT 50", 30000);
			if (sizeof($susijus) > 0) {
				$naujienos = "<ul id=\"naujienos\">";
				foreach ($susijus as $susijusios) {
					$naujienos .= "<li><a href=\"?id," . $_GET['id'] . ";k," . $susijusios['id'] . "\">" . $susijusios['pavadinimas'] . "</a> (" . date('Y-m-d H:i:s', $susijusios['data']) . ")</li>";
				}
				$naujienos .= "</ul>";
				lentele($lang['news']['related'], $naujienos);
			}
			//Rodom komentarus
			if ($sql['kom'] == 'taip') {
				include ("priedai/komentarai.php");
				komentarai($kid, true);
			}
			unset($text, $title, $data);
		} else {
			(!defined('LEVEL') ? klaida($lang['system']['forbidden'], $lang['news']['notallowed']) : klaida("{$lang['system']['error']}", $lang['news']['notallowed']));
			redirect("?id," . (int)$_GET['id'], "meta");
		}
	} else {
		klaida("{$lang['system']['error']}", "{$lang['news']['notexists']}");
		redirect("?id," . (int)$_GET['id'], "meta");
	}
}

?>
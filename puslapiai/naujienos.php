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
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) $kid = (int)$url['k']; else	$kid = 0;
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) $p = (int)$url['p']; else $p = 0;

if ($conf['Palaikymas'] == 1) klaida("{$lang['admin']['maintenance']}", $conf['Maintenance']);
include_once ("rating.php");
$limit = $conf['News_limit'];
$viso = kiek("naujienos", "WHERE `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
$text = '';
$data = '';


// Jei niekas nepaspaudziama, o atidaromas pirminis puslapis
if ($kid == 0) {

	$sql = mysql_query1("
			SELECT SQL_CACHE *, (SELECT SQL_CACHE COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP'
			AND `lang` = ".escape(lang())."
			ORDER BY `sticky` DESC,`data` DESC
			LIMIT {$p},{$limit}", 100);

	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			if (isset($conf['puslapiai']['naujienos.php']['id'])) {
				$extra = "<span class='read_more' style='float: right;display:block;'>" . (($row['kom'] == 'taip'&&isset($conf['kmomentarai_sveciams'])&&$conf['kmomentarai_sveciams'] != 3) ? "<a href='".url("?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] .";".seo_url($row['pavadinimas'],$row['id'])). "'>{$lang['news']['read']} • {$lang['news']['comments']} (" . $row['viso'] . ")</a>" : "<a href='".url("?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ). "'>{$lang['news']['read']}</a>") . "</span><br />";
			}

			$kategorijos_pav = mysql_query1("SELECT `pav`,`id`,`teises` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id` = " . escape($row['kategorija']) . " AND `lang` = ".escape(lang())." limit 1");
			$pav = "";
			if (isset($kategorijos_pav['pav'])) {
				if (isset($conf['puslapiai']['naujkat.php']['id'])) {
					$pav .= "<div style='float:left;margin-right:25px' class='avataras'><a href='".url("?id," . $conf['puslapiai']['naujkat.php']['id'] . ";k," . (int)$kategorijos_pav['id'] ). "'><img src='images/naujienu_kat/" . input($kategorijos_pav['pav']) . "' alt='img' border='0' /></a></div>";
				} else {
					$pav .= "<div class='avataras' style='float:left;margin-right:25px'><img src='images/naujienu_kat/" . input($kategorijos_pav['pav']) . "' alt='img' border='0' /></div>";
				}

			}
			$pav .= "";

			if(!isset($kategorijos_pav['pav'])|| teises($kategorijos_pav['teises'], $_SESSION['level'])) {
        if($row['sticky'] != 0)
          echo '<div class="sticky" id="news_'.$row['id'].'">';
				lentele($row['pavadinimas'], '<div>'.$pav . $row['naujiena'] .'<br />'.  $extra.'</div>', rating_form($page,$row['id']));
				if($row['sticky'] != 0)
          echo '</div>';
			}
		}
	} else {
		lentele($lang['news']['news'], $lang['news']['nonews']);
	}

	if ($viso > $limit)
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));

	unset($sql, $row, $extra, $pav);
}
if ($kid != 0) {
	$sql = "SELECT SQL_CACHE `" . LENTELES_PRIESAGA . "naujienos`.*, `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises` FROM `" . LENTELES_PRIESAGA . "naujienos` Inner Join `" . LENTELES_PRIESAGA . "grupes` ON `" . LENTELES_PRIESAGA . "naujienos`.`kategorija` = `" . LENTELES_PRIESAGA . "grupes`.`id` WHERE `" . LENTELES_PRIESAGA . "naujienos`.`rodoma`='TAIP'  AND `" . LENTELES_PRIESAGA . "naujienos`.`id` = " . escape($kid) . " limit 1";
	$sql = mysql_query1($sql, 3600);

	if(empty($sql['naujiena'])) {
		$sql = mysql_query1("
			SELECT SQL_CACHE *, (SELECT SQL_CACHE COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom`
				WHERE `pid`='puslapiai/naujienos'
				AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
				WHERE `rodoma`= 'TAIP'
				AND `id` = " . escape($kid) . "
				AND `lang` = ".escape(lang())."
			limit 1", 3600);
	}

	if (isset($sql['naujiena'])&& !empty($sql['naujiena'])) {
		addtotitle($sql['pavadinimas']);
		if (teises((isset($sql['teises'])?$sql['teises']:0), $_SESSION['level'])) {
			$title = $sql['pavadinimas'];
			$text = "<div class='naujiena'>" . $sql['naujiena'];
			if (!empty($sql['daugiau'])) {
				$text = '<div class="naujiena">' . $sql['daugiau'];
			}
			$text .= "</div><div class='line'></div>" . date('Y-m-d H:i:s', $sql['data']) . ",  <b>" . $sql['autorius'] . "</b>";

			//Atvaizduojam naujieną
			lentele($title, $text, rating_form($page, $sql['id']));
			//Susijusios naujienos
			$susijus = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija`=" . escape($sql['kategorija']) . " AND `id`!=" . escape($_GET['k']) . " AND `lang` = ".escape(lang())." AND `rodoma`= 'TAIP' ORDER by `data` DESC LIMIT 5", 30000);
			if (sizeof($susijus) > 0) {
				$naujienos = "<ul id=\"naujienos\">";
				foreach ($susijus as $susijusios) {
					$naujienos .= "<li><a href=\"".url("?id," . $_GET['id'] . ";k," . $susijusios['id'] ). "\" title=\"{$susijusios['pavadinimas']}\">" . trimlink($susijusios['pavadinimas'],55) . "</a> (" . date('Y-m-d H:i:s', $susijusios['data']) . ")</li>";
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

		}
	} else {
		klaida($lang['system']['error'], $lang['news']['notexists']);
	}
}

?>
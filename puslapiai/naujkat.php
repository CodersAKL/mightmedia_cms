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

//nustatom pid ir kid
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$k = escape(ceil((int)$url['k']));
} else {
	$k = 0;
}
//kiek irasu per psl
$limit = 50;
$text = '';
//Paulius svaigsta su kategoriju sarasu
$sqlas = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos'  ORDER BY `pavadinimas`");
if ($sqlas && sizeof($sqlas) > 0) {
	foreach ($sqlas as $sql) {
		$path = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas` LIMIT 1");
		$path1 = explode(",", $path['path']);

		if ($path1[(count($path1) - 1)] == $k) {
			$sqlkiek = kiek('naujienos', "WHERE `kategorija`=" . escape($sql['id']) . " AND `rodoma`='TAIP'");
			$info[] = array(" " => "<img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' />", "{$lang['category']['about']}" => "<h2><a href='?id," . $url['id'] . ";k," . $sql['id'] . "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "<br>", "{$lang['category']['news']}" => $sqlkiek, );
		}
	}
}
include_once ("priedai/class.php");
$bla = new Table();
if (isset($info)) {
	lentele("{$lang['system']['categories']}", $bla->render($info), false);
}
//Rodom naujienas esancias kategorijoj

$sql = mysql_query1("
			SELECT SQL_CACHE *, (SELECT SQL_CACHE COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP' AND `kategorija`=$k
			ORDER BY `data` DESC
			LIMIT {$p},{$limit}", 86400);
$viso = count($sql);
if ($viso > 0) {
	$sqlas = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $k . "' AND `kieno`='naujienos' ORDER BY `pavadinimas` LIMIT 1", 86400);
	//$sqlas = mysql_fetch_assoc($sqlas);
	if ($viso > $limit) {
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
	}
	if ($k >= 0) {
		if (count($sql) > 0) {
			if (teises($sqlas['teises'], $_SESSION['level']) || LEVEL == 1) {

				//$text = '<ul>';
				if (sizeof($sql) > 0) {
					foreach ($sql as $row) {
						if (isset($conf['puslapiai']['naujienos.php']['id'])) {
							//$text .= "<li><a href=?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . ">" . $row['pavadinimas'] . "</a></li>\n";
							if (isset($conf['puslapiai']['naujienos.php']['id'])) {
								$extra = "<div style='float: right;'>" . (($row['kom'] == 'taip') ? "<a href='?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . "'>{$lang['news']['read']} • {$lang['news']['comments']} (" . $row['viso'] . ")</a>" : "<a href='?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . "'>{$lang['news']['read']}</a>") . "</div><br />";
							}
							lentele($row['pavadinimas'], "<table><tr valign='top'><td>" . $row['naujiena'] . "</td></tr></table>" . $extra, false, array(menesis((int)date('m', strtotime(date('Y-m-d H:i:s ', $row['data'])))), (int)date('d', strtotime(date('Y-m-d H:i:s ', $row['data'])))));
						}
					}
				}
				//$text .= '</ul>';
				//lentele((isset($sqlas['pavadinimas']) ? $sqlas['pavadinimas'] : $lang['category']['-']), $text, false);
			} else {
				klaida($lang['system']['warning'], "{$lang['category']['cant']}.");
			}
		} else {
			klaida($lang['system']['warning'], "{$lang['category']['no_news']}.");
		}
	}
}

?>
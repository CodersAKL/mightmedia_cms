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
if ($sqlas && mysql_num_rows($sqlas) > 0) {
	while ($sql = mysql_fetch_assoc($sqlas)) {
		$path = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas`"));
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
$sql = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija`='" . $k . "' AND `rodoma`='TAIP' ORDER BY `data` DESC LIMIT $p, $limit");
$viso = mysql_num_rows($sql);
if ($viso > 0) {
	$sqlas = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $k . "' AND `kieno`='naujienos' ORDER BY `pavadinimas` LIMIT 1");
	$sqlas = mysql_fetch_assoc($sqlas);
	if ($viso > $limit) {
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
	}
	if ($k >= 0) {
		if (mysql_num_rows($sql) > 0) {
			if (LEVEL >= $sqlas['teises'] || LEVEL == 1 || LEVEL == 2) {
        
        $text = '<ul>';
				while ($row = mysql_fetch_assoc($sql)) {
					if (isset($conf['puslapiai']['naujienos.php']['id'])) {
						$text .= "<li><a href=?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] . ">" . $row['pavadinimas'] . "</a></li>\n";
					}
				}
				$text .= '</ul>';
				lentele((isset($sqlas['pavadinimas']) ? $sqlas['pavadinimas'] : $lang['category']['-']), $text, false);
			} else {
				klaida($lang['system']['warning'], "{$lang['category']['cant']}.");
			}
		} else {
			klaida($lang['system']['warning'], "{$lang['category']['no_news']}.");
		}
	}
}

?>
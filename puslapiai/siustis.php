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
//patikrinam ar teisingai uzkrautas puslapis
if (!defined("OK")) {
	header('location: ?');
	exit;
}

if (isset($url['c']) && isnum($url['c']) && $url['c'] > 0) {
	$cid = (int)$url['c'];
} else {
	$cid = 0;
} // kategorijos id
if (isset($url['v']) && isnum($url['v']) && $url['v'] > 0) {
	$vid = (int)$url['v'];
} else {
	$vid = 0;
} // ziurimo siuntinio id
if (isset($url['r']) && isnum($url['r']) && $url['r'] > 0) {
	$rid = (int)$url['r'];
} else {
	$rid = 0;
} // pranesti id
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$k = (int)$url['k'];
} else {
	$k = 0;
} // pranesti id

//kategorijos
if ($vid == 0) {
	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai'  ORDER BY `pavadinimas`");
	if ($sqlas && mysql_num_rows($sqlas) > 0) {
		while ($sql = mysql_fetch_assoc($sqlas)) {
			$path = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas`"));
			$path1 = explode(",", $path['path']);

			if ($path1[(count($path1) - 1)] == $k) {
				$sqlkiek = kiek('siuntiniai', "WHERE `categorija`=" . escape($sql['id']) . " AND `rodoma`='TAIP'");
				$info[] = array(" " => "<a href='?id," . $url['id'] . ";k," . $sql['id'] . "'><img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' /></a>", "{$lang['category']['about']}" => "<h2><a href='?id," . $url['id'] . ";k," . $sql['id'] . "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "<br />", "{$lang['category']['downloads']}" => $sqlkiek, );
			}
		}
		include_once ("priedai/class.php");
		$bla = new Table();
		if (isset($info)) {
			lentele("{$lang['system']['categories']}", $bla->render($info), false);
		}
	}
}
//pabaiga
# Rodom siuntini
if ($vid > 0) {
	$sql = mysql_query1("
  SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
   `" . LENTELES_PRIESAGA . "grupes`.`id` AS `kid`,
    `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`apie`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
  `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "siuntiniai` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "siuntiniai`.`ID` = '$vid' AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
   AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
  LIMIT 0, 1
  ");
	if (MYSQL_NUM_rows($sql) == 0) {
		$sql = mysql_query1("
  SELECT
  `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`apie`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
  `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
  `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "siuntiniai`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "siuntiniai`.`ID` = '$vid' AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
   AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
  LIMIT 0, 1
  ");
	}
	if (MYSQL_NUM_rows($sql) > 0) {
		$sql = mysql_fetch_assoc($sql);
		if (!isset($sql['teises']) || teises($sql['teises'], $_SESSION['level'])) {
			if (isset($sql['Nick'])) {
				$autorius = user($sql['Nick'], $sql['nick_id'], $sql['levelis']);
			} else {
				$autorius = $lang['system']['guest'];
			}

			include_once ("priedai/class.php");
			$ble = new Table();
			if (isset($sql['Kategorija'])) {
				$info2[0]["{$lang['system']['category']}"] = "<b>" . $sql['Kategorija'] . "</b><br /><div class='avataras'><img src='images/naujienu_kat/" . input($sql['img']) . "' alt='" . input($sql['Kategorija']) . "' /></div>";
			}
			$info2[0][$sql['pavadinimas'] . " {$lang['download']['info']}"] = "<div style='vertical-align: top'> <b>{$lang['download']['about']}:</b> " . $sql['apie'] . "<br /><b>{$lang['download']['date']}:</b> " . date('Y-m-d H:i:s ', $sql['data']) . "<br /><hr /><a href=\"siustis.php?d," . $sql['id'] . "\"><img src=\"images/icons/disk.png\" alt=\"" . $sql['file'] . "\" border=\"0\" /></a></div>";

			lentele("{$lang['download']['downloads']} >> " . (isset($sql['Kategorija']) ? $sql['Kategorija'] . " >> " : "") . $sql['pavadinimas'] . "", $ble->render($info2) . "<a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>");

			include_once ("priedai/komentarai.php");
			komentarai($vid);
		} else {
			klaida($lang['system']['error'], $lang['download']['notallowed']);
		}
	} else {
		klaida($lang['system']['error'], $lang['system']['pagenotfounfd']);
		// redirect("?id,6","meta");
	}

}
# rodom visus siuntinius
else {
	if ($k > 0) {
		$sql_s = mysql_query1("
   SELECT
   `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
   `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
    `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
    `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
    `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
    FROM
    `" . LENTELES_PRIESAGA . "grupes`
    Inner Join `" . LENTELES_PRIESAGA . "siuntiniai` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija`
    Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
    WHERE
  `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
  AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
    ORDER BY
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
    LIMIT 0, 50
  ");
	} else {
		$sql_s = mysql_query1(" SELECT
    `" . LENTELES_PRIESAGA . "siuntiniai`.`pavadinimas`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`id`,
    `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
    `" . LENTELES_PRIESAGA . "users`.`id` AS `nick_id`,
    `" . LENTELES_PRIESAGA . "users`.`levelis` AS `levelis`,
    `" . LENTELES_PRIESAGA . "siuntiniai`.`file`
    FROM
    `" . LENTELES_PRIESAGA . "siuntiniai`
    Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "siuntiniai`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
    WHERE
  `" . LENTELES_PRIESAGA . "siuntiniai`.`categorija` =  '$k'
  AND
   `" . LENTELES_PRIESAGA . "siuntiniai`.`rodoma` =  'TAIP'
    ORDER BY
    `" . LENTELES_PRIESAGA . "siuntiniai`.`data` DESC
    LIMIT 0, 50");
	}
	if (mysql_num_rows($sql_s) > 0) {
		include_once ("priedai/class.php");
		$bla = new Table();
		$info = array();
		while ($sql = mysql_fetch_assoc($sql_s)) {
			if (!isset($sql['teises']) || teises($sql['teises'], $_SESSION['level'])) {
				if (isset($sql['Nick'])) {
					$autorius = user($sql['Nick'], $sql['nick_id'], $sql['levelis']);
				} else {
					$autorius = '';
				}
				$info[] = array( //""=> $extra,
					"{$lang['download']['title']}:" => "<a href=\"" . url("v," . $sql['id'] . "") . "\">" . $sql['pavadinimas'] . "</a>", "{$lang['download']['date']}:" => date('Y-m-d H:i:s ', $sql['data']), "{$lang['download']['download']}:" => "<a href=\"siustis.php?d," . $sql['id'] . "\"><img src=\"images/icons/disk.png\" alt=\"" . $sql['file'] . "\" border=\"0\" /></a>");

			} else {
				klaida($lang['system']['error'], $lang['download']['notallowed']);
			}
		}
		$name = mysql_fetch_assoc(mysql_query1("SELECT pavadinimas FROM " . LENTELES_PRIESAGA . "grupes WHERE id=  '$k'"));
		lentele("{$lang['download']['downloads']} >> " . $name['pavadinimas'] . "", $bla->render($info) . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>");


	}

	unset($bla, $info, $sql, $sql_d, $vid);
}
if (kiek("siuntiniai", "WHERE rodoma='TAIP'") <= 0)
	klaida($lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>");

?>
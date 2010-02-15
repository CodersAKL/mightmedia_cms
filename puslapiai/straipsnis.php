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
$limit = 50;
$text = '';

//Kategorijų sąrašas
$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai'  ORDER BY `pavadinimas`", 86400);
if ($sqlas && sizeof($sqlas) > 0 && !isset($url['m'])) {
	foreach ($sqlas as $sql) {
		$path = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $sql['id'] . "' ORDER BY `pavadinimas` LIMIT 1", 86400);
		$path1 = explode(",", $path['path']);

		if ($path1[(count($path1) - 1)] == $k) {
			$sqlkiek = kiek('straipsniai', "WHERE `kat`=" . escape($sql['id']) . " AND `rodoma`='TAIP'");
			$info[] = array(" " => "<img src='images/naujienu_kat/" . $sql['pav'] . "' alt='Kategorija' border='0' />", "{$lang['category']['about']}" => "<h2><a href='".url("?id," . $url['id'] . ";k," . $sql['id'] ). "'>" . $sql['pavadinimas'] . "</a></h2>" . $sql['aprasymas'] . "<br>", "{$lang['category']['articles']}" => $sqlkiek, );
		}
	}
	include_once ("priedai/class.php");
	$bla = new Table();
	if (isset($info)) {
		lentele("{$lang['system']['categories']}", $bla->render($info), false);
	}

}
//Kategorijų sąrašo pabaiga
//Jei pasirinkta kategoriją
if ($k >= 0 && empty($url['m'])) {

	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' AND `kat`='" . $k . "' ORDER BY `date` DESC LIMIT $p, $limit", 86400);
	$pav = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='$k' LIMIT 1", 86400);
	$viso = count($sql);
	if ($viso > 0) {
		if (teises($pav['teises'], $_SESSION['level'])) {
			if (sizeof($sql) > 0) {
        lentele($pav['pavadinimas'], $pav['aprasymas']."<br /><i>{$lang['category']['articles']}: {$viso}</i>");
				foreach ($sql as $row) {
					if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
						//$text .= "<h1>" . $row['pav'] . "</h1>		<i>" . $row['t_text'] . "</i><br><a href=".url("?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] ). ">{$lang['article']['read']}</a><hr></hr>\n";
            lentele($row['pav'], "<i>" . $row['t_text'] . "</i><br><a href=".url("?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] ). ">{$lang['article']['read']}</a>");
					}
				}

				//lentele($pav['pavadinimas'], $text, false, array('Viso', $viso));
			}
		} else {
			klaida($lang['system']['warning'], "{$lang['article']['cant']}.");
		}
		if ($viso > $limit) {
			lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
		}
		unset($text, $row, $sql);

	}
} elseif (!empty($url['m'])) {

	$row = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' AND `id`=" . escape((int)$url['m']) . " LIMIT 1", 86400);

	$sqlas = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape($row['kat']) . " AND `kieno`='straipsniai' ORDER BY `pavadinimas` LIMIT 1", 86400);
	//$sqlas = mysql_fetch_assoc($sqlas);
	if (teises($sqlas['teises'], $_SESSION['level'])&&!empty($row['date'])) {
		$text = "<i>" . $row['t_text'] . "</i><br><hr></hr><br>\n
		" . $row['f_text'] . "
		<hr />{$lang['article']['date']}: " . date('Y-m-d H:i:s', $row['date']) . ", {$lang['article']['author']}: <b>" . $row['autorius'] . "</b>";
		lentele("<a href=\"".url("?id,{$_GET['id']};k,{$row['kat']}\">".$sqlas['pavadinimas'] ). "</a> > " . $row['pav'], $text);
		include ("priedai/komentarai.php");

		komentarai($url['m'], true);
	} else {
		klaida($lang['system']['warning'], "{$lang['article']['cant']}.");
	}
}
if (count($_GET) == 1) {
	if (kiek("straipsniai", "WHERE rodoma='TAIP'") <= 0)
		klaida($lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>");
}

?>
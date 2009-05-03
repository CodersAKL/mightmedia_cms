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

$sql_p = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
while ($row_p = mysql_fetch_assoc($sql_p)) {
	if (is_file("paneles/" . $row_p['file'])) {
		include_once ("paneles/" . $row_p['file']);
		if (!isset($title)) {
			$title = $row_p['panel'];
		}
		if ($row_p['show'] == 'Y' && isset($text) && !empty($text) && isset($_SESSION['level']) && ($_SESSION['level'] == $row_p['teises'] || $_SESSION['level'] == 1 || $row_p['teises'] == 0)) {
			lentele_r($title, $text);
			unset($title, $text);
		} elseif (isset($text) && !empty($text) && $row_p['show'] == 'N' && isset($_SESSION['level']) && ($_SESSION['level'] == $row_p['teises'] || $_SESSION['level'] == 1 || $row_p['teises'] == 0)) {
			echo $text;
			unset($text, $title);
		} else {
			unset($text, $title);
		}
	} else {
		echo lentele_r("{$lang['system']['error']}", "{$lang['system']['nopanel']}.", $row_p['file']);
	}
}
unset($sql_p, $row_p);

?>
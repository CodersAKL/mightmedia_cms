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

$sql_p = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = ".escape(lang())." ORDER BY `place` ASC", 120);
foreach ($sql_p as $row_p) {
	if ( teises($row_p['teises'], $_SESSION['level'])) {
		if (is_file("paneles/" . basename($row_p['file']))) {
			include_once ("paneles/" . basename($row_p['file']));
			if (empty($title)) {
				$title = $row_p['panel'];
			}
			if ($row_p['show'] == 'Y' && isset($text) && !empty($text) && isset($_SESSION['level']) && teises($row_p['teises'], $_SESSION['level'])) {
				lentele_l($title, $text);
				unset($title, $text);
			} elseif (isset($text) && !empty($text) && $row_p['show'] == 'N' && isset($_SESSION['level']) && teises($row_p['teises'], $_SESSION['level'])) {
				echo $text;
				unset($text, $title);
			} else {
				unset($text, $title);
			}
		} else {
			echo lentele_l($lang['system']['error'], $lang['system']['nopanel'].", ".$row_p['file']);
		}
	}
}
unset($sql_p, $row_p,$title,$text);

?>

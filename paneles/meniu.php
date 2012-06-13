<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' AND `lang` = ".escape(lang())." ORDER BY `place` ASC");

if (sizeof($res) > 0) {
	foreach ($res as $row) {
		if(teises($row['teises'], $_SESSION['level']))
			$data[$row['parent']][] = $row;
	}
	$text = "<div id=\"navigation\"><ul>" . build_menu($data) . "</ul></div>";
} else {
	$text = "";
}
unset($data, $res);

?>
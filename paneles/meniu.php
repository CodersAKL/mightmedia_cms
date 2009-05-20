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

$sql1 = mysql_query1("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "page` ORDER BY `place` ASC",2000);
$text = '<div id="vertikalus_meniu"><ul>';
foreach ($sql1 as $row1) {
	if ($row1['show'] == "Y" && puslapis($row1['file'])) {
		$text .= '<li><a href="?id,' . $row1['id'] . '">' . $row1['pavadinimas'] . '</a></li>';

	}
}
$text .= '</ul></div>';
unset($row1, $sql1);

?>
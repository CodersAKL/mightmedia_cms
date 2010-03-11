<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS �2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' ORDER BY `place` ASC");
foreach ($res as $row){
	if(teises($row['teises'], $_SESSION['level']))
	$data[$row['parent']][] = $row;
}
$text = '<div id="navigation"><ul>'.build_menu($data).'</ul></div>';
unset($data, $res);

?>
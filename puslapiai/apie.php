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


lentele("{$lang['about']['about']} - " . $conf['Pavadinimas'],  $conf['Apie']);

$adminai = '';
//Kešuojam 24 valandom
$sql = mysql_query1("SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1", 86400);
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		if ($row['levelis'] == 1) {
			$adminai .= user($row['nick'], $row['id'], $row['levelis']) . "<br />";
		} 
	}
}
if (!empty($adminai))
	lentele("{$lang['about']['admins']}:", $adminai);
?>
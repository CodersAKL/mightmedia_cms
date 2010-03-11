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

$sqli = mysql_query1("SELECT count(id) as svec, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user!='Svečias') as users, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "users) as useriai, 
(SELECT `nick` FROM " . LENTELES_PRIESAGA . "users order by id DESC LIMIT 1 ) as useris,
(SELECT `id` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as userid,
(SELECT `levelis` FROM " . LENTELES_PRIESAGA . "users order by id DESC  LIMIT 1 ) as lvl
 FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' AND user='Svečias'
");

foreach ($sqli as $sql) {
	$text = '
<b>' . $lang['online']['users'] . ':</b><br />
' . $lang['online']['usrs'] . ': ' . (int)$sql['users'] . '<br />
' . $lang['online']['guests'] . ': ' . (int)$sql['svec'] . '<br />
<b>' . $lang['online']['info'] . ':</b><br />
' . $lang['online']['registeredmembers'] . ': ' . (int)$sql['useriai'] . '<br />
' . $lang['online']['lastregistered'] . ': <br />' . user($sql['useris'], $sql['userid'], $sql['lvl']) . '
 ';
	
}
unset($sqli);
?>
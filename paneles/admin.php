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

$arredaguoti = 'Ne';

$sqli = mysql_query1("SELECT count(id) as kom, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "naujienos WHERE " . LENTELES_PRIESAGA . "naujienos.rodoma='NE') as news, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "straipsniai WHERE " . LENTELES_PRIESAGA . "straipsniai.rodoma='NE') as straipsniai2, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "siuntiniai WHERE " . LENTELES_PRIESAGA . "siuntiniai.rodoma='NE') as siuntiniai,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "galerija WHERE " . LENTELES_PRIESAGA . "galerija.rodoma='NE') as foto,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "nuorodos WHERE " . LENTELES_PRIESAGA . "nuorodos.active='NE') as nuorodos 
FROM " . LENTELES_PRIESAGA . "kom") or die(mysql_error());
//$sql = mysql_fetch_assoc($sql);
foreach ($sqli as $sql) {
	$text = '<ul>';
	if (isset($conf['puslapiai']['naujienos.php']['id'])) {
		$text .= '<li><a href="?id,999;a,2;v,5">Neaktyvių naujienų: ' . $sql['news'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['siustis.php']['id'])) {
		$text .= '<li><a href="?id,999;a,13;v,6">Neaktyvių siuntinių: ' . $sql['siuntiniai'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['nuorodos.php']['id'])) {
		$text .= '<li><a href="?id,999;a,7;v,1">Neaktyvių nuorodų: ' . $sql['nuorodos'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
		$text .= '<li><a href="?id,999;a,4;v,6">Neaktyvių straipsnių: ' . $sql['straipsniai2'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['galerija.php']['id'])) {
		$text .= '<li><a href="?id,999;a,22;v,7">Neaktyvių nuotraukų: ' . $sql['foto'] . '</a></li>';
	}

	$text .= '</ul> ';
	unset($sql);
}

?>
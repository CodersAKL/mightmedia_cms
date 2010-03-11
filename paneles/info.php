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

$sqli = mysql_query1("SELECT count(id) as kom, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "naujienos WHERE " . LENTELES_PRIESAGA . "naujienos.rodoma='TAIP') as news, (SELECT count(id) FROM " . LENTELES_PRIESAGA . "users)as users,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "d_straipsniai) as straipsniai,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "straipsniai WHERE " . LENTELES_PRIESAGA . "straipsniai.rodoma='TAIP') as straipsniai2, 
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "d_zinute) as zin,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "siuntiniai WHERE " . LENTELES_PRIESAGA . "siuntiniai.rodoma='TAIP') as siuntiniai,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "galerija WHERE " . LENTELES_PRIESAGA . "galerija.rodoma='TAIP') as foto,
(SELECT count(id) FROM " . LENTELES_PRIESAGA . "nuorodos WHERE " . LENTELES_PRIESAGA . "nuorodos.active='TAIP') as nuorodos 
FROM " . LENTELES_PRIESAGA . "kom");
foreach ($sqli as $sql) {
	$text = '<ul>';
	if (isset($conf['puslapiai']['nariai.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['nariai.php']['id'] ). '">Narių: ' . $sql['users'] . '</a></li>';
	}
	$text .= '<li><a href="#">Komentarų: ' . $sql['kom'] . '</a></li>';
	if (isset($conf['puslapiai']['naujienos.php']['id'])) {
		$text .= '<li><a href="?id,' . $conf['puslapiai']['naujienos.php']['id'] . '">Naujienų: ' . $sql['news'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['frm.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['frm.php']['id'] ). '">Forumo temų: ' . $sql['straipsniai'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['frm.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['frm.php']['id'] ). '">Forumo pranešimų: ' . $sql['zin'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['siustis.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['siustis.php']['id'] ). '">Siuntinių: ' . $sql['siuntiniai'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['nuorodos.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['nuorodos.php']['id'] ). '">Nuorodų: ' . $sql['nuorodos'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['straipsnis.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['straipsnis.php']['id'] ). '">Straipsnių: ' . $sql['straipsniai2'] . '</a></li>';
	}
	if (isset($conf['puslapiai']['galerija.php']['id'])) {
		$text .= '<li><a href="'.url('?id,' . $conf['puslapiai']['galerija.php']['id'] ). '">Nuotraukų: ' . $sql['foto'] . '</a></li>';
	}

	$text .= '</ul> ';

}
unset($sqli);
?>
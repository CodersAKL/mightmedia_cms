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

//patikrinam ar teisingai uzkrautas puslapis
if (!defined("OK")) {
	header('location: ?');
	exit();
}

if (defined("LEVEL") && LEVEL == 1) { //ADMINAS
	$q = "SELECT * FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp`>'" . $timeout . "'";
} elseif (!defined("LEVEL")) { //SVECIAS
	$q = "SELECT `id`, `uid`, `user`, `file` FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp`>'" . $timeout . "' LIMIT 10";
} else { //USERIS
	$q = "SELECT `id`, `uid`, `user`, `file` FROM `" . LENTELES_PRIESAGA . "kas_prisijunges` WHERE `timestamp`>'" . $timeout . "' ORDER BY `clicks` ASC LIMIT 50";
}

$result = mysql_query1($q);
$u = count($result);
$i = 0;

foreach ($result as $row) {
	$info[$i] = array("Slapyvardis" => user($row['user'], $row['id']), 'Kur' => '<a href="?' . $row['file'] . '"><img src="images/icons/link.png" alt="page" border="0" class="middle"/></a>');
	if (defined("LEVEL") && LEVEL >= 30) {
		$info[$i]['IP'] = "<a href='http://www.dnsstuff.com/tools/whois.ch?ip=" . $row['ip'] . "&src=ShowIP' target='_blank' title='" . $row['ip'] . "'>" . trimlink($row['ip'], 5) . "</a>";
	}
	$i++;
}

include_once ("priedai/class.php");
$bla = new Table();

$text = $bla->render($info);

//mysql_free_result($result);
unset($row, $result, $q, $u, $i, $info, $bla);

?>
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
	exit;
}
unset($extra);

//$timeout = (time() - 300);
//echo $timeout;
if (defined("LEVEL") && LEVEL == 1) { //ADMINAS
	$q = "SELECT * FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "'";
} elseif (!defined("LEVEL")) { //SVECIAS
	$q = "SELECT `id`, `uid`, `file`, `user`, `clicks`, `timestamp` FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' LIMIT 10";
} else { //USERIS
	$q = "SELECT `id`, `uid`, `user`, `clicks`, `file`, `timestamp` FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE `timestamp`>'" . $timeout . "' ORDER BY `clicks` ASC LIMIT 50";
}

$result = mysql_query1($q);
$u = count($result);
$i = 0;


foreach ($result as $row) {
	//if (!empty($row['user'])) {
	$narsykle = ((isset($row['agent'])) ? browser($row['agent']) : '?');
	$info[$i] = array("{$lang['online']['who']}" => user($row['user'], $row['id']), "{$lang['online']['timestamp']}" => date('i:s', $timestamp - $row['timestamp']), "{$lang['online']['clicks']}" => $row['clicks']);
	$flag = getUserCountry(); //"http://api.wipmania.com/$row['ip']";

	if (defined("LEVEL") && LEVEL == 1) {
		$info[$i]['IP'] = "<a href='http://whois.serveriai.lt/" . $row['ip'] . "' target='_blank' title='" . $row['ip'] . "'>" . $row['ip'] . "</a>";
		$info[$i][$lang['online']['page']] = '<a href="?' . $row['file'] . '"><img src="images/icons/link.png" alt="page" border="0" class="middle"/></a>';
		$info[$i][$lang['online']['browser']] = "<div>" . $narsykle . "</div>";
		$info[$i]['OS'] = get_user_os();
		$info[$i][$lang['online']['country']] = '<img src="images/icons/flags/' . $flag . '.png " height="12" />';
	}
	$i++;
}

include_once ("priedai/class.php");
$bla = new Table();
lentele("{$lang['online']['users']} - " . $u, $bla->render($info));

//mysql_free_result($result);
//unset($user,$nekvepuoja,$file,$img,$content,$i,$u,$q,$row,$extra);



function getUserCountry() {
    $url = 'http://api.wipmania.com/'.getip().'?'.adresas();
    $ch = curl_init();
    $headers = "Typ: phpcurl\r\n";
    $headers .= "Ver: 1.0\r\n";
    $headers .= "Connection: Close\r\n\r\n";
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($headers));
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}
?>
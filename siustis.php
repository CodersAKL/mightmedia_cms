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

session_start();

include_once ("priedai/conf.php");
ob_start();
header("Content-type: text/html; charset=utf-8");
//Jei svetaine uzdaryta remontui ir jei kreipiasi ne administratorius
if ($conf['Palaikymas'] == 1 && (!defined("LEVEL") || LEVEL > 1)) {
	redirect("remontas.php");
	exit;
}
//Nustatome atsisiuntimo ID
if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) {
	$d = (int)$url['d'];
} else {
	$d = 0;
}
//FUNKCIJOS
if (isset($d) && $d > 0) {
	$sql = mysql_query1("SELECT `file`,`categorija` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = " . escape($d) . " LIMIT 1");
	if (count($sql) > 0) {
		$row = mysql_query1("SELECT `teises` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id` = '" . $sql['categorija'] . "'");
		if (isset($_SESSION['level']) && (!$row || teises($row['teises'], $_SESSION['level']))) {
			download("siuntiniai/" . $sql['file'] . "", ".htaccess|.|..|remontas.php|index.php|config.php|conf.php");
		} else {
			die(klaida($lang['system']['sorry'], $lang['download']['cant']));
		}
	} else {
		header("Content-type: text/html; charset=utf-8");
		header("HTTP/1.0 404 Not Found");
		die(klaida($lang['system']['error'], $lang['download']['notfound']));
	}
} else {
	header("Content-type: text/html; charset=utf-8");
	header("HTTP/1.0 404 Not Found");
	die(klaida($lang['system']['error'], $lang['download']['notfound']));
}
//Siunciam nurodyta faila i narsykle. Pratestavau ant visu operaciniu ir narsykliu.
function download($file, $filter) {
	global $sql;
	$filter = explode("|", $filter);
	if (!in_array($file, $filter) && is_file($file)) {
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			$file = preg_replace('/\./', '%2e', $file, substr_count($file, '.') - 1);
		}
		if (is_file($file)) {
			if (connection_status() == 0) {
				if (get_user_os() == "MAC") {
					header("Content-Type: application/x-unknown\n");
					header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"\n");
				} elseif (get_browser_info() == "MSIE") {
					$disposition = (!eregi("\.zip$", basename($file))) ? 'attachment' : 'inline';
					header('Content-Description: File Transfer');
					header('Content-Type: application/force-download');
					header('Content-Length: ' . (string )(filesize($file)));
					header("Content-Disposition: $disposition; filename=\"" . basename($file) . "\"\n");
					header("Cache-Control: cache, must-revalidate");
					header('Pragma: public');
				} elseif (get_browser_info() == "OPERA") {
					header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"\n");
					header("Content-Type: application/octetstream\n");
				} else {
					header("Content-Disposition: attachment; filename=\"" . basename($file) . "\"\n");
					header("Content-Type: application/octet-stream\n");
				}
				header("Content-Length: " . (string )(filesize($file)) . "\n\n");
				readfile('' . $file . '');
				exit;
			} else {
				header("location: ".$_SERVER['PHP_SELF']);
				exit;
			}
		} else {
			klaida($lang['system']['error'], $lang['download']['notfound']);
			header("HTTP/1.0 404 Not Found");
		}
	} else {
		header("location: " . $sql['file']);
	}
}

?>
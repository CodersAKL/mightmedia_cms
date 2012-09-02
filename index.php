<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */
ob_start();
header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");
if (!isset($_SESSION))
	session_start();
define('ROOT', '');

//kad rodytu per kiek laiko sugeneravo koda
$m1 = explode(" ", microtime());
$stime = $m1[1] + $m1[0];

//Iterpiam nustatymu faila jei ne perkialiam i instaliacija
clearstatcache();

if (is_file('priedai/conf.php') && filesize('priedai/conf.php') > 10) {
	include_once ("priedai/conf.php");
} elseif (is_file('install/index.php') && !isset($conf['Palaikymas'])) {
	header('location: install/index.php');
	exit();
} else {
	die('<h1>Sistemos klaida / System error</h1>Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.');
}
include_once ('priedai/prisijungimas.php');
if (!isset($conf)) {
	include_once ('priedai/funkcijos.php');
}
/* Puslapiu aprasymas */
if (isset($url['id']) && !empty($url['id']) && isnum($url['id'])) {
	$pslid = (int) $url['id'];
} else {
	$pslid = $conf['puslapiai'][$conf['pirminis'] . '.php']['id'];
	$page = 'puslapiai/' . $conf['pirminis'];
	$page_pavadinimas = $conf['puslapiai'][$conf['pirminis'] . '.php']['pavadinimas'];
	$_GET['id'] = $pslid;
	$url['id'] = $pslid;
}
if (isset($pslid) && isnum($pslid) && $pslid > 0) {
	$sql1 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `id` = " . escape((int) $pslid) . " and `lang` = " . escape(lang()) . " LIMIT 1", 259200); //keshas  3dienos.

	if (!empty($sql1)) {
		if (!preg_match("/\.php$/i", $sql1['file'])) {
			header("Location:{$sql1['file']}");
			exit;
		}
		if (puslapis($sql1['file'])) {
			$page = 'puslapiai/' . basename($sql1['file'], '.php');
			$page_pavadinimas = $sql1['pavadinimas'];
		} else {
			$page = "puslapiai/klaida";
			$page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
		}
		if (!file_exists($page . '.php')) {
			$page = "puslapiai/klaida";
			$page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
		}
	} else {
		$page = "puslapiai/klaida";
		$page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
	}
}
//Jei svetaine uzdaryta remontui ir jei jungiasi ne administratorius
if ($conf['Palaikymas'] == 1) {
	if (!isset($_SESSION['id']) || $_SESSION['level'] != 1) {
		redirect("remontas.php");
	}
}
if (!empty($_GET['lang'])) {
	$_SESSION['lang'] = basename($_GET['lang'], '.php');
	redirect(url("?id," . $_GET['id']));
}
/*if (!empty($_SESSION['lang']) && is_file(ROOT . 'lang/' . basename($_SESSION['lang']) . '.php')) {
	require(ROOT . 'lang/' . basename($_SESSION['lang'], '.php') . '.php');
}*/

include_once ("priedai/header.php");
//Tikrinam ar setup.php failas paљalintas. Saugumo sumetimais
if (is_file('setup.php') && $_SESSION['level'] == 1 && !@unlink('setup.php')) {
	die('<h1>Demesio / Warning</h1><h3>Neištrintas setup.php failas.</h3> Tai saugumo spraga. Prašome pašalinkite šį failą iš serverio arba pakeiskite jo pavadinimą. /Please, remove setup.php file from server.</h3>');
}
include_once 'stiliai/' . $conf['Stilius'] . '/sfunkcijos.php';
if (empty($_GET['ajax'])) {
	include_once ('stiliai/' . $conf['Stilius'] . '/index.php');
} else {
	include_once($page . ".php");
}

mysql_close($prisijungimas_prie_mysql);
$m2 = explode(" ", microtime());
$etime = $m2[1] + $m2[0];
$ttime = ($etime - $stime);
$ttime = number_format($ttime, 7);
if ($_SESSION['level'] == 1)
	echo '<!-- Generated ' . apvalinti($ttime, 2) . 's. -->';
ob_end_flush();

?>
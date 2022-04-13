<?php

ob_start();

header("Cache-control: public");
header("Content-type: text/html; charset=utf-8");

if (! isset($_SESSION)) {
	session_start();
}

define('ROOT', '');

// Code generation time
$m1    = explode(" ", microtime());
$stime = $m1[1] + $m1[0];

//Iterpiam nustatymu faila jei ne perkialiam i instaliacija
clearstatcache();

if (is_file('config.php')) {
	include_once 'config.php';
	if (DEBUG) {
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 'On');
	}
} elseif (is_file('install/index.php') && ! isset($conf['Palaikymas'])) {
	header('location: install/index.php');
	exit;
} else {
	die('<h1>Sistemos klaida / System error</h1>Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.');
}

/**
 * Connection to DB
 */
include_once 'core/inc/inc.db_ready.php';

/**
 * BOOT
 */
include_once 'core/boot.php';

include_once 'core/inc/inc.auth.php';

// if ( !isset( $conf ) ) {
// 	include_once 'funkcijos.php';
// }


// Pages LOAD by routes
include_once 'core/functions/functions.routes.php';
include_once 'core/inc/inc.routes.php';
// TODO: remove
// if (isset($url['id']) && ! empty($url['id']) && isnum($url['id'])) {
//     $pslid = (int)$url['id'];
// } else {
//     $pslid            = $conf['pages'][$conf['pirminis'] . '.php']['id'];
//     $page             = 'content/pages/' . $conf['pirminis'];
//     $page_pavadinimas = $conf['pages'][$conf['pirminis'] . '.php']['pavadinimas'];
//     $_GET['id']       = $pslid;
//     $url['id']        = $pslid;
// }

// if (isset($pslid) && isnum($pslid) && $pslid > 0) {
//     if ($sql1 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `id` = " . escape((int)$pslid) . " and `lang` = " . escape(lang()) . " LIMIT 1", 259200)) {
//         if (empty($sql1['builder']) || (! empty($sql1['builder']) && $sql1['builder'] == 'cms')) {
//             if (!preg_match("/\.php$/i", $sql1['file'])) {
//                 header("Location:{$sql1['file']}");
//                 exit;
//             }
//             if (puslapis($sql1['file'])) {
//                 //todo: optimize it after v2
//                 if (is_file($sql1['file'])) {
//                     $page = dirname($sql1['file']) . '/' . basename($sql1['file'], '.php');
//                 } else {
//                     $page = 'content/pages/' . basename($sql1['file'], '.php');
//                 }

//                 $page_pavadinimas = $sql1['pavadinimas'];
//                 $pageMetaData = [
//                     "title"			=> $sql1['metatitle'],
//                     "description" 	=> $sql1['metadesc'],
//                     "keywords" 		=> $sql1['metakeywords']
//                 ];
//                 $page_type = 'cms';
//             } else {
//                 $page             = "content/pages/klaida";
//                 $page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
//                 $page_type = 'cms';
//             }

//             if (!file_exists($page . '.php')) {
//                 $page             = "content/pages/klaida";
//                 $page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
//                 $page_type = 'cms';
//             }
//         } elseif ($sql1['builder'] == 'assembler') {
//             $page             = "content/pages/page_assembler"; // TODO: remove
//             $pageId = $sql1['file'];
//             $page_type = 'assembler';
//         }
//     } else {
//         $page             = "content/pages/klaida";
//         $page_pavadinimas = '404 - ' . $lang['system']['pagenotfounfd'] . '';
//         $page_type = 'cms';
//     }
// }

//Jei svetaine uzdaryta remontui ir jei jungiasi ne administratorius
if ($conf['Palaikymas'] == 1) {
	if (empty(getSession('id')) || getSession('level') != 1) {
		include_once __DIR__ . "/content/themes/" . $conf['Stilius'] . "/functions.php";

		maintenance($lang['admin']['maintenance'], $conf['Maintenance']);
		exit;
	}
}

if (! empty($_GET['lang'])) {
	setSession('lang', basename($_GET['lang'], '.php'));
	redirect(url("?id," . $_GET['id']));
}
/*if (!empty($_SESSION['lang']) && is_file(ROOT . 'content/lang/' . basename($_SESSION['lang']) . '.php')) {
	require(ROOT . 'content/lang/' . basename($_SESSION['lang'], '.php') . '.php');
}*/

include_once 'core/inc/inc.header.php';
//Tikrinam ar setup.php failas paљalintas. Saugumo sumetimais
// if ( is_dir( 'install/' ) && getSession('level') == 1 && !@unlink( 'install/index.php' ) ) {
// 	die( '<h1>Demesio / Warning</h1><h3>Neištrintas install aplankalas.</h3> Tai saugumo spraga. Prašome pašalinkite šį aplankalą iš serverio arba pakeiskite jo pavadinimą. /Please, remove install folder from server.</h3>' );
// }

include_once 'content/themes/' . $conf['Stilius'] . '/functions.php';

if (empty($_GET['ajax'])) {
	include_once 'content/themes/' . $conf['Stilius'] . '/index.php';
} else {
	include_once $page . '.php';
}

mysqli_close($prisijungimas_prie_mysql);

$m2    = explode(" ", microtime());
$etime = $m2[1] + $m2[0];
$ttime = ($etime - $stime);
$ttime = number_format($ttime, 7);

if (getSession('level') == 1) {
	echo '<!-- Generated ' . apvalinti($ttime, 2) . 's. -->'; // TODO: remove
}

ob_end_flush();

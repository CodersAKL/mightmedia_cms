<?php

/**
 * BOOT
 */
include_once 'core/boot.php';

//Extensions configs include
$extPath = ROOT . 'content/extensions/';
$extensions = getDirs($extPath);

if(! empty($extensions)) {
	foreach ($extensions as $extension) {
		$fileExt = $extPath . $extension . '/config.php';

		if(file_exists($fileExt) && getExtensionStatus($extension)) {
			require_once $fileExt;
		}
	}
}

include_once 'core/inc/inc.auth.php';

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
if (getOption('maintenance') == 1) {
	if (empty(getSession('id')) || getSession('level') != 1) {
		include_once __DIR__ . "/content/themes/" . $conf['Stilius'] . "/functions.php";

		maintenance($lang['admin']['maintenance'], $conf['Maintenance']);
		exit;
	}
}
// TODO: check and remove
// include_once 'core/inc/inc.header.php';
//Tikrinam ar setup.php failas paљalintas. Saugumo sumetimais
// if ( is_dir( 'install/' ) && getSession('level') == 1 && !@unlink( 'install/index.php' ) ) {
// 	die( '<h1>Demesio / Warning</h1><h3>Neištrintas install aplankalas.</h3> Tai saugumo spraga. Prašome pašalinkite šį aplankalą iš serverio arba pakeiskite jo pavadinimą. /Please, remove install folder from server.</h3>' );
// }

/**
 * Language
 */
$lang = [];

if (lang() && is_file(ROOT . 'content/lang/' . lang() . '.php' )) {
	require ROOT . 'content/lang/' . lang() . '.php';
	$extensions = getActiveExtensions();
	foreach ($extensions as $extension) {
		if (is_file(ROOT . 'content/extensions/' . $extension['name'] . '\/content/lang/' . lang() . '.php')){
			require ROOT . 'content/extensions/' . $extension['name'] . '\/content/lang/' . lang() . '.php';
		}
	}
}

if (getOption('site_lang')) {
	$dir = ROOT . 'content/extensions/translation/' . lang();
	$path = $dir . '/translations.php';
	if(file_exists($path)){
		$translations = unserialize(file_get_contents($path));
		foreach ($translations as $translation) {
			$lang[$translation['group']][$translation['key']] = $translation['translation'];
		}
	}
	unset($path);
	unset($dir);
} else {
    require_once ROOT . 'content/lang/lt.php';
}

include_once 'content/themes/' . getOption('site_theme') . '/functions.php';

if (empty($_GET['ajax'])) {
	include_once 'content/themes/' . getOption('site_theme') . '/index.php';
} else {
	include_once $page . '.php';
}



ob_end_flush();
<?php

/**
 * BOOT
 */
include_once 'core/boot.php';

// todo: check
//Extensions configs include
// $extPath = ROOT . 'content/extensions/';
// $extensions = getDirs($extPath);

// if(! empty($extensions)) {
// 	foreach ($extensions as $extension) {
// 		$fileExt = $extPath . $extension . '/config.php';

// 		if(file_exists($fileExt) && getExtensionStatus($extension)) {
// 			require_once $fileExt;
// 		}
// 	}
// }

include_once 'core/inc/inc.auth.php';

//TODO: maintenance page
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



ob_end_flush();
<?php
// ban for trying open file
if (basename($_SERVER['PHP_SELF']) == 'boot.php') {
	ban(getip(), $lang['system']['forhacking']);
}

// TODO: ADD new check
//Jeigu nepavyko nuskaityti nustatymų
// if (! isset($conf) || empty($conf)) {
// 	die("<center><h1>Klaida 3</h1><br/>Svetainė laikinai neveikia. <h4>Prašome užsukti vėliau</h4></center>");
// }

// Nustatom maksimalu leidziama keliamu failu dydi
$max_upload   = (int)( ini_get( 'upload_max_filesize' ) );
$max_post     = (int)( ini_get( 'post_max_size' ) );
$memory_limit = (int)( ini_get( 'memory_limit' ) );
$upload_mb    = min( $max_upload, $max_post, $memory_limit );
define( "MFDYDIS", $upload_mb * 1024 * 1024 );
//ini_set("memory_limit", MFDYDIS);
define( "OK", TRUE );

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
define('MAIN_DIR', $_SERVER['DOCUMENT_ROOT'] . DS);
// TODO: change
// define('ROOTAS', dirname( realpath( __FILE__ ) ) . '/../' );
// if(! defined('ROOT')) {
// 	define('ROOT', dirname( realpath( __FILE__ ) ) . '/../' );
// }

date_default_timezone_set(TIME_ZONE);

/**
 * Connection to DB
 */
require_once ROOT . 'core/inc/inc.db_ready.php';

/**
 * Core functions
 */

require_once ROOT . 'core/functions/functions.core.php';



/**
 * Load core
 * TODO: add this array to config
 */
$loadCoreConfigsArray = [
    'class',
    'functions',
];

foreach ($loadCoreConfigsArray as $loadCoreConfigsSlug) {
    require_once ROOT . 'core/configs/config.' . $loadCoreConfigsSlug . '.php';
}

$loadCoreFunctionsArray = [
	'deprecated',
	'cache',
    'calendar',
    'categories',
    // 'core',
    'date',
    'db',
    'file',
    'hooks',
    'http',
    'images',
    'pages',
    'string',
    'url',
	'users',
	'extensions',
	'routes',
];

foreach ($loadCoreFunctionsArray as $loadCoreFunctionSlug) {
    require_once ROOT . 'core/functions/functions.' . $loadCoreFunctionSlug . '.php';
}

/**
 * Language
 */
$lang = [];

if (! empty(getSession('lang')) && is_file(ROOT . 'content/lang/' . basename(getSession('lang')) . '.php' )) {
	require ROOT . 'content/lang/' . basename(getSession('lang'), '.php') . '.php';
	$extensions = getActiveExtensions();
	foreach ($extensions as $extension) {
		if (is_file(ROOT . 'content/extensions/' . $extension['name'] . '\/content/lang/' . basename(getSession('lang'), '.php') . '.php')){
			require ROOT . 'content/extensions/' . $extension['name'] . '\/content/lang/' . basename(getSession('lang'), '.php') . '.php';
		}
	}
}

if (isset($conf['kalba'])) {
	$dir = ROOT . 'content/extensions/translation/' . getSession('lang');
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

//Isvalom POST'us nuo xss
if (! empty($_POST)) {
	include_once ROOT . 'core/functions/functions.safe_html.php';
	foreach ($_POST as $key => $value) {
		if ( !is_array( $value ) ) {
			$post[$key] = safe_html( $value );
		} else {
			$post[$key] = $value;
		}
	}
	unset( $_POST );
	$_POST = $post;
}

// Tvarkom $_SERVER globalus.
$_SERVER['PHP_SELF']     = cleanurl( $_SERVER['PHP_SELF'] );
$_SERVER['QUERY_STRING'] = isset( $_SERVER['QUERY_STRING'] ) ? cleanurl( $_SERVER['QUERY_STRING'] ) : "";
$_SERVER['REQUEST_URI']  = isset( $_SERVER['REQUEST_URI'] ) ? cleanurl( $_SERVER['REQUEST_URI'] ) : "";
$PHP_SELF                = cleanurl( $_SERVER['PHP_SELF'] );

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

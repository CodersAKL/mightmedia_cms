<?php
ob_start();
// header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
// header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if (! isset($_SESSION)) {
	session_start();
}

// define root
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);

// load config
if (is_file(ROOT . 'config.php')) {
	include_once ROOT . 'config.php';

	if(DEBUG) {
		// ini_set('error_reporting', E_ALL);
		error_reporting(E_ALL);
		ini_set('display_errors', true);
		ini_set('display_startup_errors', true);
		// Error/Exception file logging engine.
		ini_set('log_errors', true);
		// Logging file path
		ini_set('error_log', ROOT . 'errors.log');
	}
} elseif ( is_file( ROOT . 'install/index.php' ) ) {
	header( 'location: ../install/index.php' );
	exit;
} else {
	die('System error: CMS is not installed.');
}

date_default_timezone_set(TIME_ZONE);


require_once ROOT . 'core/libs/vendor/autoload.php';

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



/**
 * Connection to DB
 */
require_once ROOT . 'core/inc/inc.db_ready.php';

/**
 * Core functions
 */

require_once ROOT . 'core/functions/functions.core.php';

// set CSRF token data
if(! getSession('token')) {
	setSessions(
		[
			'token'			=> bin2hex(random_bytes(32)),
			'token_expire'	=> time() + 3600 // 1 hour = 3600 secs TODO: change time
		]
	);
}

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
	'db',
	'deprecated',
	'options',
	'url',
	'cache',
    'hooks',
    'http',
    'pages',
    'string',
	'users',
	'extensions',
	'routes',
	'form',
	'post_type',
];

foreach ($loadCoreFunctionsArray as $loadCoreFunctionSlug) {
    require_once ROOT . 'core/functions/functions.' . $loadCoreFunctionSlug . '.php';
}

// check CSRF token on POST
if(isset($_POST) && ! empty($_POST)) {
	if(isset($_POST['token']) && ! empty($_POST['token'])) {
		checkCSRFreal($_POST['token']);
	} else {
		die('No posting without CSRF token!');
	}
}


// TODO: check and change or remove
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
$_SERVER['PHP_SELF']     = cleanUrl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING'] ) ? cleanUrl($_SERVER['QUERY_STRING']) : '';
$_SERVER['REQUEST_URI']  = isset($_SERVER['REQUEST_URI'] ) ? cleanUrl($_SERVER['REQUEST_URI']) : '';
$PHP_SELF                = cleanUrl($_SERVER['PHP_SELF']);

// current theme functions file load
require_once ROOT . 'content/themes/' . getOption('site_theme') . '/functions.php';

// INIT hooks
doAction('boot');

// Pages LOAD by routes
require_once ROOT . 'core/inc/inc.routes.php';


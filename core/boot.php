<?php

// ban for trying open file
if (basename($_SERVER['PHP_SELF']) == 'boot.php') {
	ban(getip(), $lang['system']['forhacking']);
}

// Nustatom maksimalu leidziama keliamu failu dydi
$max_upload   = (int)( ini_get( 'upload_max_filesize' ) );
$max_post     = (int)( ini_get( 'post_max_size' ) );
$memory_limit = (int)( ini_get( 'memory_limit' ) );
$upload_mb    = min( $max_upload, $max_post, $memory_limit );
define( "MFDYDIS", $upload_mb * 1024 * 1024 );
//ini_set("memory_limit", MFDYDIS);
define( "OK", TRUE );
define('ROOTAS', dirname( realpath( __FILE__ ) ) . '/../' );
if(! defined('ROOT')) {
	define('ROOT', dirname( realpath( __FILE__ ) ) . '/../' );
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
    'cache',
    'calendar',
    'categories',
    'core',
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
];

foreach ($loadCoreFunctionsArray as $loadCoreFunctionSlug) {
    require_once ROOT . 'core/functions/functions.' . $loadCoreFunctionSlug . '.php';
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

/**
 * Vartotojų lygiai
 *
 * @return array
 */
//TODO: rewrite this shit
unset($sql, $row);
if (basename($_SERVER['PHP_SELF']) != 'upgrade.php' && basename($_SERVER['PHP_SELF']) != 'setup.php') {
	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno` = 'vartotojai' AND `lang`=" . escape(lang()) . " ORDER BY `id` DESC");

	if (count($sql) > 0) {
		foreach ($sql as $row) {
			$levels[(int)$row['id']] = array(
				'pavadinimas' => $row['pavadinimas'],
				'aprasymas'   => $row['aprasymas'],
				'pav'         => input( $row['pav'] )
			);
		}
	}
	$levels[1] = array(
		'pavadinimas' => $lang['system']['admin'],
		'aprasymas'   => $lang['system']['admin'],
		'pav'         => 'admin.png'
	);
	$levels[2] = array(
		'pavadinimas' => $lang['system']['user'],
		'aprasymas'   => $lang['system']['user'],
		'pav'         => 'user.png'
	);

	$conf['level'] = $levels;
	unset($levels, $sql, $row);

	/**
	 * Gaunam visus puslapius ir suformuojam masyvą
	 */
	$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape( lang() ) . " ORDER BY `place` ASC", 120 );
	foreach ($sql as $row) {
		$keyName 	= basename($row['file']);
		$niceName 	= (isset( $lang['pages'][$keyName]) ? $lang['pages'][$keyName] : nice_name($keyName));
		
		$conf['pages'][$keyName] = [
			'id'          	=> $row['id'],
			'pavadinimas' 	=> input($row['pavadinimas']),
			'file'        	=> input($row['file']),
			'place'       	=> (int)$row['place'],
			'show'        	=> $row['show'],
			'teises'      	=> $row['teises'],
		];

		$conf['titles'][$row['id']]											= $niceName;
		$conf['titles_id'][strtolower(str_replace( ' ', '_', $niceName))] 	= $row['id'];
	}
	// Nieko geresnio nesugalvojau
	$dir                        = explode( '/', dirname( $_SERVER['PHP_SELF'] ) );
	$conf['titles']['999']      = $dir[count( $dir ) - 1] . '/admin';
	$conf['titles_id']['admin'] = 999;
	// Sutvarkom nuorodas
	if ( isset( $_SERVER['QUERY_STRING'] ) && !empty( $_SERVER['QUERY_STRING'] ) ) {
		$_GET = url_arr( cleanurl( $_SERVER['QUERY_STRING'] ) );
		if ( isset( $_GET['id'] ) ) {
			$element    = strtolower( $_GET['id'] );
			$_GET['id'] = ( ( isset( $conf['titles_id'][$element] ) && $conf['F_urls'] != '0' ) ? $conf['titles_id'][$element] : $_GET['id'] );
		}
		$url = $_GET;
	} else {
		$url = [];
	}
}

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

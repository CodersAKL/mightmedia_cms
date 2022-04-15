<?php
// todo: everything to boot.php ?
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if (! isset($_SESSION)) {
	session_start();
}

if (! defined('ROOT')) {
	define('ROOT', '../');
}

if (is_file(ROOT . 'config.php')) {
	include_once ROOT . 'config.php';
	if(DEBUG) {
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 'On');
	}
	
} elseif (is_file(ROOT . 'install/index.php')) {
	header('location: ../install/index.php');
	exit;
} else {
	die('System error: CMS is not installed.');
}

/**
 * BOOT
 */
include_once ROOT . 'core/boot.php';

$base   = explode( '/', dirname( $_SERVER['PHP_SELF'] ) );
$folder = $base[count( $base ) - 1];
if (! isset($conf['Admin_folder']) || $conf['Admin_folder'] != $folder ) {
	setSettingsValue($folder, 'Admin_folder');
}

define('LEVEL', getSession('level'));

include_once ROOT . 'core/inc/inc.header.php';

//kalbos
$kalbos   = getFiles( ROOT . 'content/lang/' );
$language = '<ul class="sf-menu" id="lang"><li><a href=""><img src="' . ROOT . 'core/assets/images/icons/flags/' . lang() . '.png" alt="' . lang() . '"/></a><ul>';

foreach ( $kalbos as $file ) {
	if ( $file['type'] == 'file' && basename( $file['name'], '.php' ) != lang() ) {
		$language .= '<li><a href="' . url( '?id,999;lang,' . basename( $file['name'], '.php' ) ) . '"><img src="' . ROOT . 'core/assets/images/icons/flags/' . basename( $file['name'], '.php' ) . '.png" alt="' . basename( $file['name'], '.php' ) . '" class="language flag ' . basename( $file['name'], '.php' ) . '" /></a></li>';
	}
}
$language .= '</ul></li></ul>';

if (! empty($_GET['lang'])) {
	setSession('lang', basename($_GET['lang'], '.php'));
	redirect(url( "?id," . $_GET['id']));
}


if ( empty(getSession('username')) || getSession('level') != 1 ) {
	redirect( ROOT . 'index.php' );
}
if ( isset( $_GET['do'] ) ) {
	forgotSession(
		[
			'username',
			'level',
			'password',
			'id'
		]
	);

	redirect( ROOT . 'index.php' );
}

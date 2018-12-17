<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if ( !isset( $_SESSION ) ) {
	session_start();
}

if ( !defined( 'ROOT' ) ) {
	//_ROOT = $root;
	define( 'ROOT', '../' );
} else {
	define( 'ROOT', $root );
}

if ( is_file( ROOT . 'priedai/conf.php' ) && filesize( ROOT . 'priedai/conf.php' ) > 1 ) {

	include_once ROOT . 'priedai/conf.php';

	$base   = explode( '/', dirname( $_SERVER['PHP_SELF'] ) );
	$folder = $base[count( $base ) - 1];
	//echo $folder;
	if ( !isset( $conf['Admin_folder'] ) || $conf['Admin_folder'] != $folder ) {
		setSettingsValue($folder, 'Admin_folder');
	}

	
	/**
	 * BOOT
	 */
	include_once ROOT . 'core/boot.php';


	define( 'LEVEL', $_SESSION[SLAPTAS]['level'] );

	include_once ROOT . 'priedai/header.php';

} elseif (is_file(ROOT . 'install/index.php')) {
	// header('location: ' . ROOT . 'install/index.php');
	exit;

} else {
	die( klaida( 'Sistemos klaida / System error', 'Atsiprašome svetaine neidiegta. Truksta sisteminiu failu. / CMS is not installed.' ) );
}
//kalbos
$kalbos   = getFiles( ROOT . 'lang/' );
$language = '<ul class="sf-menu" id="lang"><li><a href=""><img src="' . ROOT . 'images/icons/flags/' . lang() . '.png" alt="' . lang() . '"/></a><ul>';

foreach ( $kalbos as $file ) {
	if ( $file['type'] == 'file' && basename( $file['name'], '.php' ) != lang() ) {
		$language .= '<li><a href="' . url( '?id,999;lang,' . basename( $file['name'], '.php' ) ) . '"><img src="' . ROOT . 'images/icons/flags/' . basename( $file['name'], '.php' ) . '.png" alt="' . basename( $file['name'], '.php' ) . '" class="language flag ' . basename( $file['name'], '.php' ) . '" /></a></li>';
	}
}
$language .= '</ul></li></ul>';

if ( !empty( $_GET['lang'] ) ) {
	$_SESSION[SLAPTAS]['lang'] = basename( $_GET['lang'], '.php' );
	redirect( url( "?id," . $_GET['id'] ) );
}

if ( !empty( $_SESSION[SLAPTAS]['lang'] ) && is_file( ROOT . 'lang/' . basename( $_SESSION[SLAPTAS]['lang'] ) . '.php' ) ) {
	require( ROOT . 'lang/' . basename( $_SESSION[SLAPTAS]['lang'], '.php' ) . '.php' );
	$extensions = getActiveExtensions();
	foreach ($extensions as $extension) {
		if (is_file( ROOT . 'extensions/' . $extension['name'] . '\/lang/' . basename( $_SESSION[SLAPTAS]['lang'], '.php' ) . '.php' )){
			require( ROOT . 'extensions/' . $extension['name'] . '\/lang/' . basename( $_SESSION[SLAPTAS]['lang'], '.php' ) . '.php' );
		}
	}
}
if ( empty( $_SESSION[SLAPTAS]['username'] ) || $_SESSION[SLAPTAS]['level'] != 1 ) {
	redirect( ROOT . 'index.php' );
}
if ( isset( $_GET['do'] ) ) {
	unset( $_SESSION[SLAPTAS]['username'], $_SESSION[SLAPTAS]['level'], $_SESSION[SLAPTAS]['password'], $_SESSION[SLAPTAS]['id'] );
	redirect( ROOT . 'index.php' );
}

<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );
if ( !isset( $_SESSION ) ) {
	session_start();
}
/* detect root */
$out_page = TRUE;
$inc      = "priedai/conf.php";
$root     = '';
while ( !file_exists( $root . $inc ) && strlen( $root ) < 70 ) {
	$root = "../" . $root;
}

#check if the file actually exists or if we crashed out.
if ( !file_exists( $root . $inc ) ) {
	die( "Kritine klaida." . $root . $inc );
}
if ( is_file( $root . 'priedai/conf.php' ) && filesize( $root . 'priedai/conf.php' ) > 1 ) {

	if ( !defined( 'ROOT' ) ) {
		//_ROOT = $root;
		define( 'ROOT', '../' );
	} else {
		define( 'ROOT', $root );
	}

	include_once( $root . 'priedai/conf.php' );
	include_once( $root . 'priedai/header.php' );
	$base   = explode( '/', dirname( $_SERVER['PHP_SELF'] ) );
	$folder = $base[count( $base ) - 1];
	//echo $folder;
	if ( !isset( $conf['Admin_folder'] ) || $conf['Admin_folder'] != $folder ) {
		mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape( $folder ) . ",'Admin_folder')  ON DUPLICATE KEY UPDATE `val`=" . escape( $folder ) );
	}

	//Inkludinam tai ko mums reikia
	require_once( $root . 'priedai/funkcijos.php' );
	define( 'LEVEL', $_SESSION[SLAPTAS]['level'] );

} elseif ( is_file( $root . 'install/index.php' ) ) {
	header( 'location: ' . $root . 'install/index.php' );
	exit();
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
}
if ( empty( $_SESSION[SLAPTAS]['username'] ) || $_SESSION[SLAPTAS]['level'] != 1 ) {
	redirect( ROOT . 'index.php' );
}
if ( isset( $_GET['do'] ) ) {
	unset( $_SESSION[SLAPTAS]['username'], $_SESSION[SLAPTAS]['level'], $_SESSION[SLAPTAS]['password'], $_SESSION[SLAPTAS]['id'] );
	redirect( ROOT . 'index.php' );
}
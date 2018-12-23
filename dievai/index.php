<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if ( !isset( $_SESSION ) ) {
	session_start();
}

if (! defined( 'ROOT' ) ) {
	define( 'ROOT', '../' );
}

if (is_file(ROOT . 'config.php')) {
	include_once ROOT . 'config.php';
	if(DEBUG) {
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 'On');
	}
} elseif ( is_file( ROOT . 'install/index.php' ) ) {
	header( 'location: ../install/index.php' );
	exit;
} else {
	die('System error: CMS is not installed.');
}
/**
 * Connection to DB
 */
include_once ROOT . 'core/inc/inc.db_ready.php';
/**
 * BOOT
 */
include_once ROOT . 'core/boot.php';

include_once ROOT . 'core/inc/inc.auth.php';

require 'themes/material/config.php';
require 'themes/material/functions.php';

include 'functions/functions.core.php';
include 'themes/material/login.php';
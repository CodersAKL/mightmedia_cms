<?php
ob_start();
header( "Cache-control: public" );
header( "Content-type: text/html; charset=utf-8" );
header( 'P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"' );

if ( !isset( $_SESSION ) ) {
	session_start();
}

if ( !defined( 'ROOT' ) ) {
	define( 'ROOT', '../' );
} else {
	define( 'ROOT', $root );
}

if ( is_file( '../priedai/conf.php' ) && filesize( '../priedai/conf.php' ) > 1 ) {
	include_once ( "../priedai/conf.php" );
} elseif ( is_file( '../install/index.php' ) ) {
	header( 'location: ../install/index.php' );
	exit();
} else {
	die( klaida( 'Sistemos klaida / System error', 'Atsiprašome svetaine neįdiegta. Trūksta sisteminių failų. / CMS is not installed.' ) );
}

include_once ( "../priedai/prisijungimas.php" );

require 'themes/material/config.php';
require 'themes/material/functions.php';

include 'config/functions.php';
include 'themes/material/login.php';
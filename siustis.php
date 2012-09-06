<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

session_start();

include_once ( "priedai/conf.php" );
include_once ( "priedai/header.php" );

ob_start();
header( "Content-type: text/html; charset=utf-8" );
// Jei svetaine uzdaryta remontui ir jei kreipiasi ne administratorius
if ( $conf['Palaikymas'] == 1 && $_SESSION['level'] > 1 ) {
	redirect( "remontas.php" );
	exit;
}
// Nustatome atsisiuntimo ID
$d = isset( $url['d'] ) ? (int)$url['d'] : 0;

// FUNKCIJOS
if ( isset( $d ) && $d > 0 ) {
	//duomenys apie faila
	$sql = mysql_query1( "SELECT `file`,`categorija` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = " . escape( $d ) . " LIMIT 1" );
	if ( isset( $sql['file'] ) ) {
		//teisiu tikrinimas
		$row = mysql_query1( "SELECT `teises` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id` = " . escape( $sql['categorija'] ) . " LIMIT 1" );
		if ( !$row || teises( $row['teises'], $_SESSION['level'] ) ) {
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET paspaudimai = paspaudimai + 1 WHERE `ID`=" . escape( $d ) . "" );
			download( "siuntiniai/" . $sql['file'] );
		} else {
			die( klaida( $lang['system']['sorry'], $lang['download']['cant'] ) );
		}
	} else {
		header( "Content-type: text/html; charset=utf-8" );
		header( "HTTP/1.0 404 Not Found" );
		die( klaida( $lang['system']['error'], $lang['download']['notfound'] ) );
	}
} else {
	header( "Content-type: text/html; charset=utf-8" );
	header( "HTTP/1.0 404 Not Found" );
	die( klaida( $lang['system']['error'], $lang['download']['notfound'] ) );
}

?>
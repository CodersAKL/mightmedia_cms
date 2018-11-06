<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas//A.Cenkus - zlotas - ire $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 237 $
 * @$Date: 2009-07-10 22:11:22 +0300 (Pn, 10 Lie 2009) // 2011-05-28 $
 *
 **/

if ( isset( $_SESSION[SLAPTAS]['id'] ) && $_SESSION[SLAPTAS]['id'] ) {
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'Pateikti siuntinį' ) {
		if ( isset( $_POST['url'] ) && isset( $_POST['Pavadinimas'] ) ) {

			//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
			//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
			//
			$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`)
    VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $_POST['url'] ) . ",
    " . escape( $_POST['Aprasymas'] ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ")" );

			if ( $result ) {
				msg( $lang['system']['info'], "{$lang['download']['sumbit_scc']}" );
			} else {
				klaida( $lang['system']['error'], "{$lang['download']['doc']}: <font color='#FF0000'>" . $filename . "</font> {$lang['download']['not_uploaded']}." );
			}
			//unset($result,$_POST['action'],$_FILES['failas'],$file);
			redirect( "?id," . $_GET['id'] . ";", "meta" );
		} else {
			klaida( $lang['system']['warning'], "{$lang['admin']['news_required']}." );
		}
	}
	$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
	include_once ( ROOTAS . "priedai/kategorijos.php" );
	kategorija( "siuntiniai", TRUE );
	if ( sizeof( $sql ) > 0 ) {
		$kategorijos = cat( 'siuntiniai', 0 );
	}
	$kategorijos[0] = "--";
	include_once ( ROOTAS . "priedai/class.php" );
	$bla   = new forma();
	$forma = array( "Form"                                  => array( "action" => '', "method" => "post", "name" => "action" ),
	                "{$lang['admin']['download_fileurl']}:" => array( "name" => "url", "type" => "text", "value" => "http://", "class"=> "input" ),
	                "{$lang['system']['name']}:"            => array( "type" => "text", "value" => '', "name" => "Pavadinimas", "class"=> "input" ),
	                "{$lang['system']['category']}:"        => array( "type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "class"=> "input" ),
	                "{$lang['system']['about']}:"           => array( "type" => "string", "value" => editorius( 'spaw', 'mini', 'Aprasymas' ) ),
	                ""                                      => array( "type" => "submit", "name" => "action", "value" => "{$lang['admin']['download_Create']}" ), );
	lentele( $lang['admin']['download_Create'], $bla->form( $forma ) );

} else {
	klaida( $lang['system']['warning'], $lang['system']['pleaselogin'] );
}

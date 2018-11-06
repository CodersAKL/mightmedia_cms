<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 729 $
 * @$Date: 2010-08-26 18:49:19 +0300 (Kt, 26 Rgp 2010) $
 **/
if ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['links_create'] ) {
	// if (isset($_POST['name']) && isset($_POST['apie'])) {
	//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
	//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
	//
	// Nustatom kintamuosius
	$pattern     = "/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i";
	$url         = strip_tags( $_POST['url'] );
	$apie        = strip_tags( $_POST['apie'] );
	$pavadinimas = strip_tags( $_POST['name'] );
	$kategorija  = strip_tags( $_POST['kat'] );
	$autoriusid  = ( isset( $_SESSION[SLAPTAS]['id'] ) ? $_SESSION[SLAPTAS]['id'] : 0 );
	/*  if (empty($url) || empty($pavadinimas)) {
		  $error = "{$lang['admin']['links_allfields']}.";
		}*/
	// if (!isset($error)) {
	if ( !preg_match( $pattern, $url ) ) {
		klaida( $lang['system']['error'], $lang['admin']['links_bad'] );
	} else {
		$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "nuorodos` SET
	`cat` = " . escape( $kategorija ) . ",
	`url` = " . escape( $url ) . ",
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`nick` = " . escape( $autoriusid ) . ",
	`date` = " . time() . ",
	`apie` = " . escape( $apie ) . ",
	`active` = 'NE',
	`lang` = " . escape( lang() ) . "" );

		if ( $result ) {
			msg( "{$lang['system']['done']}", "{$lang['admin']['links_sent']}." );
		} else {
			klaida( $lang['system']['error'], "{$lang['admin']['links_allfields']}." );
		}
	}

	/*  } else {
				klaida($lang['system']['error'], $error);
		}*/


	//For ajax - get extra info about the page
	/*	if (!empty($_POST['tikrink'])) {
		$info = svetaines_info(strip_tags($_POST['tikrink']));
		exit(clean_str(!empty($info['description'])?$info['description']:$info['title']));
	}*/
	//redirect(url("?id," . $_GET['id']), "meta");
	/*} else {
		klaida($lang['system']['warning'], "{$lang['admin']['news_required']}.");
	}*/
}
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
include_once ( ROOTAS . "priedai/kategorijos.php" );
kategorija( "nuorodos", TRUE );
if ( sizeof( $sql ) > 0 ) {
	$kategorijos = cat( 'nuorodos', 0 );
}
$kategorijos[0] = "--";
include_once ( "priedai/class.php" );
$bla      = new forma();
$nuorodos = array( "Form"                        => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "reg" ),
                   $lang['system']['category']   => array( "type" => "select", "value" => $kategorijos, "name" => "kat" ),
                   $lang['admin']['links_title'] => array( "type" => "text", "value" => "", "name" => "name" ),
	//"Url" => array("type" => "text", "extra" => "title=\"http://\" onchange=\"$.post('".url("?id,{$_GET['id']};ajax,1")."',{ tikrink: $(this).val() }, function(data) { $('#temp').html(data); $('#apie').val($('#temp').html())});\"", "name" => "url"),
                   "Url"                         => array( "type" => "text", "value" => "http://", "name" => "url" ),
	//TODO: AJAX get extra info about the page
                   $lang['admin']['links_about'] => array( "type" => "string", "value" => editorius( 'spaw', 'mini', 'apie' ) ),
                   " "                           => array( "type" => "submit", "name" => "action", "value" => $lang['admin']['links_create'] ) );

lentele( $lang['admin']['links_create'], $bla->form( $nuorodos ) );

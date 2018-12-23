<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

if ( isset( $_POST['action'] ) && $_POST['action'] == 'Pateikti' ) {
	//print_r($_POST);
	if ( isset( $_POST['naujiena'] ) && isset( $_POST['pav'] ) ) {
		//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
		//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
		//
		$naujiena = explode( '===page===', $_POST['naujiena'] );
		$izanga   = $naujiena[0];
		$placiau  = empty( $naujiena[1] ) ? '' : $naujiena[1];
		//$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
		$komentaras  = 'taip';
		$pavadinimas = strip_tags( $_POST['pav'] );
		$category  = (int)$_POST['category'];
		$result      = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, kategorija, rodoma, lang) VALUES (" . escape( $pavadinimas ) . ", " . escape( $izanga ) . ", " . escape( $placiau ) . ",  '" . time() . "', '" . ( isset( $_SESSION[SLAPTAS]['username'] ) ? $_SESSION[SLAPTAS]['username'] : 'Svečias' ) . "', " . escape( $komentaras ) . ", " . escape( $category ) . ", 'NE', " . escape( lang() ) . ")" );
		if ( $result ) {
			msg( "{$lang['system']['done']}", "{$lang['news']['sumbit_scc']}." );
		} else {
			klaida( $lang['system']['error'], "{$lang['news']['sumbit_no']}." );
		}
		redirect( url( "?id," . $_GET['id'] ), "meta" );
	} else {
		klaida( $lang['system']['warning'], "{$lang['admin']['news_required']}." );
	}
}
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
include_once config('functions', 'dir') . 'functions.categories.php';
category( "naujienos", TRUE );
if ( sizeof( $sql ) > 0 ) {
	$categories = cat( 'naujienos', 0 );
}
$categories[0] = "--";
include_once config('class', 'dir') . 'class.form.php';
$bla      = new Form();
$new = array(
	"Form"                           => array( "action" => "", "method" => "post", "name" => "reg" ),
	"{$lang['system']['name']}:"     => array( "type" => "text", "value" => '', "name" => "pav", "class"=> "input" ),
	//"{$lang['comments']['comments']}:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"),
	"{$lang['system']['category']}:" => array( "type" => "select", "value" => $categories, "name" => "category", "class" => "input", "class"=> "input" ),
	"{$lang['admin']['news_more']}:" => array( "type" => "string", "value" => editorius( 'tinymce', 'standartinis', 'naujiena' ) ),
	""                               => array( "type" => "submit", "name" => "action", "value" => "{$lang['news']['submit']}" )
);
lentele($lang['news']['submiting'], $bla->form( $new ) );

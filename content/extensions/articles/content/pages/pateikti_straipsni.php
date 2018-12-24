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
	if ( isset( $_POST['pav'] ) && isset( $_POST['str'] ) ) {
		//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
		//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
		//
		$straipsnis = explode( '===page===', $_POST['str'] );
		$apr        = $straipsnis[0];
		$str        = empty( $straipsnis[1] ) ? '' : $straipsnis[1];
		// $komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
		$komentaras  = 'taip';
		$category  = (int)$_POST['category'];
		$pavadinimas = strip_tags( $_POST['pav'] );
		$autorius    = (! empty(getSession('username')) ? getSession('username') : '-');
		$autoriusid  = (! empty(getSession('id')) ? getSession('id') : 0);
		if ( empty( $apr ) || empty( $pavadinimas ) ) {
			$error = "{$lang['admin']['article_emptyfield']}.";
		}
		if ( !isset( $error ) ) {
			$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "straipsniai` SET
	    	`kat` = " . escape( $category ) . ",
			`pav` = " . escape( $pavadinimas ) . ",
			`t_text` = " . escape( $apr ) . ",
			`f_text` = " . escape( $str ) . ",
			`date` = " . time() . ",
			`autorius` = " . escape( $autorius ) . ",
			`autorius_id` = " . escape( $autoriusid ) . ",
			`kom` = " . escape( $komentaras ) . ",
			`rodoma` = 'NE',
			`lang` = " . escape( lang() ) . "" );
			if ( $result ) {
				msg( $lang['system']['info'], "{$lang['article']['sumbit_scc']}." );
			} else {
				klaida( $lang['system']['error'], "{$lang['article']['sumbit_no']}." );
			}
		} else {
			klaida( $lang['system']['error'], $error );
		}
		redirect( url( "?id," . $_GET['id'] ), "meta" );
	} else {
		klaida( $lang['system']['warning'], "{$lang['admin']['news_required']}." );
	}
}
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
include_once config('functions', 'dir') . 'functions.categories.php';
category( "straipsniai", TRUE );
if ( sizeof( $sql ) > 0 ) {
	$categories = cat( 'straipsniai', 0 );
}
$categories[0] = "--";
include_once config('class', 'dir') . 'class.form.php';
$bla        = new Form();
$straipsnis = array( "Form"                           => array( "action" => url( "?id," . $conf['pages'][basename( __file__ )]['id'] ), "method" => "post", "name" => "reg" ),
                     "{$lang['system']['name']}:"     => array( "type" => "text", "value" => "", "name" => "pav", "class"=> "input" ),
	//"{$lang['comments']['comments']}:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"), 
                     "{$lang['system']['category']}:" => array( "type" => "select", "value" => $categories, "name" => "category", "class" => "input", "class"=> "input" ),
                     "{$lang['admin']['article']}:"   => array( "type" => "string", "value" => editorius( 'jquery', 'standartinis', 'str' ) ),
                     ""                               => array( "type" => "submit", "name" => "action", "value" => "{$lang['article']['submit']}" ), );
lentele( "{$lang['article']['submiting']}", $bla->form( $straipsnis ) );

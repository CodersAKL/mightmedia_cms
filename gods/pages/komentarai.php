<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 157 $
 * @$Date: 2009-06-01 15:23:34 +0300 (Mon, 01 Jun 2009) $
 * */
//Surenkam puslapius kuriuose ira komentarai
if ( isset( $_POST['del'] ) ) {
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape( $_POST['pg'] ) . "" );
	header( "location: " . $_SERVER['HTTP_REFERER'] . "" );
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
//
if ( !isset( $_POST['pg'] ) && !isset( $_GET['s'] ) ) {
	$sql = mysql_query1( "SELECT `pid` FROM `" . LENTELES_PRIESAGA . "kom` GROUP BY `pid` ORDER BY `pid` DESC" );
	if ( !empty( $sql ) ) {
		foreach ( $sql as $row ) {
			$pgs[$row['pid']] = ( strlen( getLangText('pages', str_replace( 'content/content/pages/', '', $row['pid'] ) . '.php') ) > 0 ? getLangText('pages', str_replace( 'content/pages/', '', $row['pid']) . '.php') : str_replace( 'content/pages/', '', $row['pid'] ) );
		}

		$form = array( "Form" => array( "action" => "", "method" => "post", "name" => "com" ), getLangText('online',  'page') . ":" => array( "type" => "select", "value" => $pgs, "name" => "pg" ), " " => array( "type" => "submit", "name" => "select", "value" => getLangText('admin',  'page_select') ), "  " => array( "type" => "submit", "name" => "del", "value" => getLangText('admin', 'del_comments')));
		
		$formClass = new Form($forma);
		lentele(getLangText('admin', 'adm_comments'), $formClass->render());
	} else {
		klaida( getLangText('system', 'warning'), getLangText('system', 'no_items') );
	}
}
if ( isset( $_POST['select'] ) || isset( $_GET['s'] ) ) {
	if ( isset( $_GET['d'] ) ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape( $_GET['d'] ) . " LIMIT 1" );
	}
	if ( isset( $_GET['e'] ) ) {
		if ( !isset( $_POST['edit'] ) ) {
			$row  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape( $_GET['e'] ) . " LIMIT 1" );
			$form = array( 
				"Form" => array( "action" => "", "method" => "post" ), 
				getLangText('contact', 'message') . ":" => array( "type" => "textarea", "value" => input( $row['zinute'] ), "name" => "msg", "extra" => "rows=5" ),
				" "    => array( "type" => "submit", "name" => "edit", "value" => getLangText('admin', 'edit') )
			);

			$formClass = new Form($forma);
			lentele(getLangText('sb', 'edit'), $formClass->render());
		} else {
			$msg = trim( $_POST['msg'] ) . "\n[sm] [i] " . getLangText('sb',  'editedby') . ": " . getSession('username') . " [/i] [/sm]";
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "kom` SET `zinute` = " . escape( strip_tags( $msg ) ) . " WHERE `id` =" . escape( $url['e'] ) . " LIMIT 1" );
			
			if ( mysqli_affected_rows($prisijungimas_prie_mysql) > 0 ) {
				msg( getLangText('system', 'done'), getLangText('sb', 'updated') );
			}
		}
	}

	$pg   = ( isset( $_POST['pg'] ) ? $_POST['pg'] : base64_decode( $_GET['s'] ) );
	$viso = kiek( "kom", "WHERE `pid`=" . escape( $pg ) . "" );

	$sqlQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape( $pg ) . " ORDER BY `id` DESC LIMIT {$p}, {$limit}";
	
	if ($sql = mysql_query1($sqlQuery)) {
		foreach ( $sql as $row ) {
			if ( $row['nick_id'] == 0 ) {
				$duom = @unserialize( $row['nick'] );
				$nick = user( $duom[0], $row['nick_id'] ) . (getSession('level') == 1 ? " (" . $duom[1] . ")" : "" );
			} else {
				$nick = user( $row['nick'], $row['nick_id'] );
			}

			$info[] = array(
				getLangText('new', 'author')      => $nick,
				getLangText('contact', 'message') => smile( bbchat( trimlink( input( $row['zinute'] ), 150 ) ) ),
				" "                         => "<a href=\"" . url( "s," . str_replace( '=', '', base64_encode( $pg ) ) . ";d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . getLangText('system', 'delete_confirm') . "')\" title='" . getLangText('admin',  'delete') . "'><img src=\"" . ROOT . "core/assets/images/icons/cross.png\" alt=\"[" . getLangText('admin',  'delete') . "]\" border=\"0\" class=\"middle\" /></a><a href=\"" . url( "s," . str_replace( '=', '', base64_encode( $pg ) ) . ";e," . $row['id'] . "" ) . "\" title='" . getLangText('admin',  'edit') . "'><img src=\"" . ROOT . "core/assets/images/icons/pencil.png\" alt=\"[" . getLangText('admin',  'edit') . "]\" border=\"0\" class=\"middle\" /></a>"
			);
		}

		$tableClass = new Table($info);
		lentele(getLangText('admin', 'adm_comments'), $tableClass->render());
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( getLangText('system', 'warning'), getLangText('system', 'no_items') );
	}
}
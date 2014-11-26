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
include_once ( ROOT . "priedai/class.php" );
$bla = new forma();

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
			$pgs[$row['pid']] = ( isset( $lang['pages'][str_replace( 'puslapiai/', '', $row['pid'] ) . '.php'] ) ? $lang['pages'][str_replace( 'puslapiai/', '', $row['pid'] ) . '.php'] : str_replace( 'puslapiai/', '', $row['pid'] ) );
		}
		$form = array( "Form" => array( "action" => "", "method" => "post", "name" => "com" ), "{$lang['online']['page']}:" => array( "type" => "select", "value" => $pgs, "name" => "pg" ), " " => array( "type" => "submit", "name" => "select", "value" => "{$lang['admin']['page_select']}" ), "  " => array( "type" => "submit", "name" => "del", "value" => "{$lang['admin']['del_comments']}" ) );

		lentele( "{$lang['admin']['adm_comments']}", $bla->form( $form ) );
	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_items'] );
	}
}
if ( isset( $_POST['select'] ) || isset( $_GET['s'] ) ) {
	if ( isset( $_GET['d'] ) ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape( $_GET['d'] ) . " LIMIT 1" );
	}
	if ( isset( $_GET['e'] ) ) {
		$bla = new forma();
		if ( !isset( $_POST['edit'] ) ) {
			$row  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape( $_GET['e'] ) . " LIMIT 1" );
			$form = array( "Form" => array( "action" => "", "method" => "post" ), "{$lang['contact']['message'] }:" => array( "type" => "textarea", "value" => input( $row['zinute'] ), "name" => "msg", "extra" => "rows=5", "class" => "input" ),
			               " "    => array( "type" => "submit", "name" => "edit", "value" => $lang['admin']['edit'] ) );
			lentele( $lang['sb']['edit'], $bla->form( $form ) );
		} else {
			$msg = trim( $_POST['msg'] ) . "\n[sm] [i] {$lang['sb']['editedby']}: " . $_SESSION[SLAPTAS]['username'] . " [/i] [/sm]";
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "kom` SET `zinute` = " . escape( strip_tags( $msg ) ) . " WHERE `id` =" . escape( $url['e'] ) . " LIMIT 1" );
			if ( mysqli_affected_rows($prisijungimas_prie_mysql) > 0 ) {
				msg( $lang['system']['done'], $lang['sb']['updated'] );
			}
		}
	}
	$pg   = ( isset( $_POST['pg'] ) ? $_POST['pg'] : base64_decode( $_GET['s'] ) );
	$viso = kiek( "kom", "WHERE `pid`=" . escape( $pg ) . "" );
	$sql  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape( $pg ) . " ORDER BY `id` DESC LIMIT {$p}, {$limit}" );
	if ( !empty( $sql ) ) {
		$tbl = new Table();
		foreach ( $sql as $row ) {
			if ( $row['nick_id'] == 0 ) {
				$duom = @unserialize( $row['nick'] );
				$nick = user( $duom[0], $row['nick_id'] ) . ( $_SESSION[SLAPTAS]['level'] == 1 ? " (" . $duom[1] . ")" : "" );
			} else {
				$nick = user( $row['nick'], $row['nick_id'] );
			}
			$info[] = array(
				$lang['new']['author']      => $nick,
				$lang['contact']['message'] => smile( bbchat( trimlink( input( $row['zinute'] ), 150 ) ) ),
				" "                         => "<a href=\"" . url( "s," . str_replace( '=', '', base64_encode( $pg ) ) . ";d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a><a href=\"" . url( "s," . str_replace( '=', '', base64_encode( $pg ) ) . ";e," . $row['id'] . "" ) . "\" title='{$lang['admin']['edit']}'><img src=\"" . ROOT . "images/icons/pencil.png\" alt=\"[{$lang['admin']['edit']}]\" border=\"0\" class=\"middle\" /></a>" );
		}

		lentele( "{$lang['admin']['adm_comments']}", '' . $tbl->render( $info ) . '' );
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_items'] );
	}
}
?>

<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 140 $
 * @$Date: 2009-05-25 20:56:43 +0300 (Pr, 25 Geg 2009) $
 **/

include_once( 'priedai/class.php' );
$forma = new forma();
//jeigu prisijunges narys
if ( isset( $_SESSION[SLAPTAS]['username'] ) ) {
	$el    = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
	$email = $el['email'];
//jeigu gauna linka is emailo deaktyvacijai
} elseif ( isset( $_GET['e'] ) ) {
	$email = input( base64decode( $_GET['e'] ) );
}
//jeigu paspaudzia mygtuka
if ( isset( $_POST['email'] ) ) {
	if ( $_SESSION[SLAPTAS]['code'] == strip_tags( strtoupper( $_POST['kode'] ) ) ) {
		if ( check_email( $_POST['email'] ) ) {
			$sql = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "newsgetters` WHERE `email`=" . escape( $_POST['email'] ) . " LIMIT 1" );
			if ( isset( $sql['email'] ) ) {
				mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "newsgetters` WHERE `email`=" . escape( $_POST['email'] ) . "" );
				msg( $lang['system']['done'], $lang['news']['unordered'] );
				redirect( url( '?id,' . $_GET['id'] ), 'meta' );
			} else {
				mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "newsgetters` (`email`) VALUES (" . escape( $_POST['email'] ) . ")" );
				msg( $lang['system']['done'], $lang['news']['ordered'] );
				redirect( url( '?id,' . $_GET['id'] ), 'meta' );
			}
		} else {
			klaida( $lang['system']['warning'], $lang['reg']['bademail'] );
		}
	} else {
		klaida( $lang['system']['warning'], $lang['reg']['wrongcode'] );
	}
}
$form = array( "Form"                     => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "get" ),
               "{$lang['reg']['email']}:" => array( "type" => "text", "value" => ( isset( $email ) ? input( $email ) : "" ), "name" => "email", "class" => "input" ),
               kodas()                    => array( "type"=> "text", "name"=> "kode", "class"=> "chapter" ),
               " "                        => array( "type" => "submit", "name" => "submit", "value" => $lang['news']['Order/Unorder'] ) );
lentele( $page_pavadinimas, $forma->form( $form ) );

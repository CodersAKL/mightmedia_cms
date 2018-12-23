<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * @Apie: Kontaktas su svetainės administratorium
 **/

if ( isset( $_POST['kontaktas'] ) && $_POST['kontaktas'] == 'Siųsti' && strtoupper( $_POST['code'] ) === getSession('code') && !empty( $_POST['zinute'] ) && !empty( $_POST['vardas'] ) ) {

	$title = strip_tags( $_POST['pavadinimas'] );
	$from  = strip_tags( $_POST['vardas'] );
	$email = strip_tags( $_POST['email'] );

	$msg = "{$lang['contact']['email']}: <b>" . $email . "</b><br/>\n{$lang['contact']['name']}: <b>" . $from . "</b><br/>\n{$lang['contact']['from']}: <b>" . adresas() . "</b><br/><b>IP:</b>" . getip() . "<br/>\n----<br/>\n" . nl2br( htmlspecialchars( $_POST['zinute'] ) );
	$to  = $conf['Pastas'];

	ini_set( "sendmail_from", $email );

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: ' . input( $from ) . ' <' . $email . '>' . "\r\n";

	if ( mail( $to, input( $title ), $msg, $headers ) ) {
		msg( "{$lang['system']['done']}", "{$lang['contact']['sent']}" );
		redirect( url( "?id," . (int)$_GET['id'] ), "meta" );
	}

} elseif ( isset( $_POST ) && !empty( $_POST['kontaktas'] ) ) {
	klaida( $lang['system']['error'], "{$lang['contact']['bad']}" );
} else {
	if (! empty(getSession('username'))) {
		$from  = getSession('username');
		$email = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape( $from ) . " LIMIT 1" );
		$email = $email['email'];
	}
}

include_once config('class', 'dir') . 'class.form.php';
$bla  = new Form();
$form = array(
	"Form"                           => array( "action" => url( "?id," . $conf['pages'][basename( __file__ )]['id'] ), "method" => "post", "name" => "kontaktas" ),
	"{$lang['contact']['subject']}:" => array( "type" => "text", "class"=> "input", "value" => ( isset( $title ) && !empty( $title ) ? input( $title ) : '' ), "name" => "pavadinimas", "class"=> "input" ),
	"{$lang['contact']['name']}:"    => array( "type" => "text", "class"=> "input", "value" => ( isset( $from ) && !empty( $from ) ? input( $from ) : '' ), "name" => "vardas", "class"=> "input" ),
	"{$lang['contact']['email']}:"   => array( "type" => "text", "class"=> "input", "value" => ( isset( $email ) ? input( $email ) : '' ), "name" => "email", "class"=> "input" ),
	"{$lang['contact']['message']}:" => array( "type" => "textarea", "value" => ( isset( $_POST['zinute'] ) && !empty( $_POST['zinute'] ) ? input( $_POST['zinute'] ) : '' ), "name" => "zinute", "extra" => "rows=5", "class"=> "input" ),
	kodas()                          => array( "type" => "text", "value" => "", "name"=> "code", "class"=> "chapter" ),
	" "                              => array( "type" => "submit", "name" => "kontaktas", "value" => "{$lang['contact']['submit']}" )
);
lentele( $lang['contact']['form'], $bla->form( $form ) );
unset( $form, $result, $from, $error, $to, $msg, $email, $title );
//PABAIGA - atvaizdavimo

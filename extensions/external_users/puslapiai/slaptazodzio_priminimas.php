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

//paziurim ar vartotojas neprisijunges, jei prisijunges tai jam nera cia ka veikti
if ( isset( $_SESSION[SLAPTAS]['username'] ) ) {
	header( "Location: " . url( "?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}" ) );
}

//nuskaitom saugos koda is nuorodos - jeigu toks egzistuoja patikrinam ar tinka ir vydom slaptazodzio atstatyma
if ( isset( $url['c'] ) && !empty( $url['c'] ) && strlen( $url['c'] ) == 11 ) {
	$kode  = input( strip_tags( $url['c'] ) );
	$sqlis = mysql_query1( "SELECT `nick`,`email`,`slaptas` FROM `" . LENTELES_PRIESAGA . "users` WHERE slaptas=" . escape( $kode ) . " LIMIT 1" );
	if ( !isset( $sqlis['nick'] ) ) {
		$error = "{$lang['pass']['wrongcode']}";
	} else {
		//$sql = mysql_fetch_assoc($sql);
		$slaptas = random_name();
		$nick    = $sqlis['nick'];

		$up = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `slaptas`='', pass=" . escape( koduoju( $slaptas ) ) . " WHERE `nick`=" . escape( $nick ) . " LIMIT 1" ) or die( mysqli_error($prisijungimas_prie_mysql) );

		if ( !empty( $up ) ) {
			msg( $lang['system']['done'], sprintf( $lang['user']['hello'], $nick ) . ",<br/>{$lang['pass']['new']} <b>" . $slaptas . "</b><br/>" );
		} else {
			klaida( $lang['system']['systemerror'], "{$lang['system']['contactadmin']}." );
		}
	}
} //priesingu atveju pranesam apie klaida
elseif ( !empty( $url['c'] ) ) {
	klaida( $lang['system']['sorry'], "{$lang['pass']['wrongcode']}." );
	redirect( "?", "meta" ); //peradresuojam i pagrindini psulapi
	$error = ''; //kad nerodytu formos
}

if ( isset( $_POST['action'] ) && $_POST['action'] == 'siusti' ) {
	$error = '';
	$kode  = strip_tags( strtoupper( $_POST['kode'] ) );
	if ( $kode != $_SESSION[SLAPTAS]['code'] ) {
		$error = "{$lang['pass']['wrongcode']}<br />";
	} elseif ( $_POST['email'] == $_POST['email1'] ) {
		$email = input( strip_tags( $_POST['email'] ) );
		$sql   = mysql_query1( "SELECT `nick`,`email` FROM `" . LENTELES_PRIESAGA . "users` WHERE email=" . escape( $email ) . " LIMIT 1" );
		if ( !isset( $sql['nick'] ) ) {
			$error .= " {$lang['pass']['wrongemail']}.<br />";
			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "{$lang['pass']['wrongemail']}({$lang['pass']['remain']}) : " . $email ) . ", '" . time() . "', '" . escape( getip() ) . "')" );
		} else {
			$slaptas = random_name();
			require_once( 'priedai/class.phpmailer-lite.php' );
			$mail = new PHPMailerLite();
			$mail->IsMail();
			$mail->CharSet = 'UTF-8';
			$body          = "<b>" . $sql['nick'] . "</b>,<br/>{$lang['pass']['mail']} <a href='" . url( "?id," . $_GET['id'] . ";c," . $slaptas ) . "'>" . url( "?id," . $_GET['id'] . ";c," . $slaptas ) . "</a>
<hr>";
			$mail->SetFrom( $admin_email, $conf['Pavadinimas'] );
			$mail->AddAddress( $email, $sql['nick'] );
			$mail->Subject = strip_tags( $conf['Pavadinimas'] ) . " " . $lang['pass']['remain'];
			$mail->MsgHTML( $body );
			if ( $mail->Send() ) {
				msg( $lang['system']['done'], "{$lang['pass']['sent']}." );
			} else {
				klaida( $lang['system']['sorry'], $lang['system']['error'] . ":" . $mail->ErrorInfo );
			}

			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `slaptas` = " . escape( $slaptas ) . " WHERE nick=" . escape( $sql['nick'] ) . " LIMIT 1" );
			mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( "{$lang['pass']['remain']}: Nick: " . $sql['nick'] . " Email: " . input( $sql['email'] ) ) . ", '" . time() . "', '" . escape( getip() ) . "')" );

			echo "<img src='priedai/human.php' style='display:none' />";
		}
	} else {
		$error .= "{$lang['pass']['notmatch']}<br />";
	}
}
if ( isset( $error ) ) {
	if ( !empty( $error ) || $error != "" ) {
		klaida( $lang['system']['sorry'], $error );
	}
} elseif ( !isset( $_POST['action'] ) && !isset( $url['c'] ) ) {
	include_once ( ROOTAS . "priedai/class.php" );

	$bla   = new forma();
	$forma = array(
		"Form"                       => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "siusti", "extra"=> "onSubmit=\"return checkMail('reg','email')\"" ),
		"{$lang['pass']['email']}:"  => array( "type" => "text", "name" => "email", "extra"=> "title='{$lang['pass']['email']}'" ),
		"{$lang['pass']['email2']}:" => array( "type" => "text", "name" => "email1", "extra"=> "title='{$lang['pass']['email']}'" ),
		kodas()                      => array( "type"=> "text", "name"=> "kode", "class"=> "chapter" ),
		" \r"                        => array( "type" => "submit", "name" => "Submit_link", "value" => "{$lang['pass']['send']}" ),
		" \r\r"                      => array( "type" => "hidden", "name" => "action", "value" => "siusti" )
	);

	lentele( $lang['pass']['remain'], $bla->form( $forma ) );
	unset( $text );
}

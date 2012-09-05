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

$p = isset( $url['p'] ) ? $url['p'] : 0; //esamo puslapio nr
$limit = 50; //Kiek įrašų puslapyje
$viso = kiek( "chat_box" );

// Jei tai moderatorius
if ( ar_admin( 'com' ) ) {
	// Jei paspaudė trinti
	if ( isset( $url['d'] ) && !empty( $url['d'] ) ) {
		$id = (int) $url['d'];
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE `id` = " . escape( $id ) . " LIMIT 1" );
		if ( mysql_affected_rows() > 0 ) {
			msg( $lang['system']['done'], $lang['sb']['deleted'] );
		} else {
			klaida( $lang['system']['error'], mysql_error() );
		}
		redirect( url( "?id," . $url['id'] . ";p,$p" ), "meta" );
	}
	// Jei adminas paspaude redaguoti
	if ( isset( $url['r'] ) && !empty( $url['r'] ) ) {
		$nick = $_SESSION['username'];
		$nick_id = $_SESSION['id'];
		// Jei redaguotas pranesimas nesiunciamas
		if ( !isset( $_POST['chat_box'] ) ) {
			$msg = mysql_query1( "SELECT `msg` FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE `id`=" . escape( ceil( (int) $url['r'] ) ) . " LIMIT 1" );

			$form_array = array(
				"Form" => array(
					"action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] . ";r," . $_GET['r'] ),
					"method" => "post",
					"name"   => "chat_box_edit"
				),
				$lang['guestbook']['message'] => array(
					"type"  => "textarea",
					"value" => input( $msg['msg'] ),
					"name"  => "msg",
					"extra" => "rows=5",
					"class" => "input"
				),
				" " => array(
					"type"  => "submit",
					"name"  => "chat_box",
					"value" => $lang['admin']['edit']
				)
			);

			include_once ( "priedai/class.php" ); // Formu iš masyvo generavimas
			$form = new forma(); // Sukuriam formos objektą
			lentele( $lang['sb']['edit'], $form->form( $form_array ) );

		} else {
			$msg = trim( $_POST['msg'] ) . "\n[sm] [i] {$lang['sb']['editedby']}: " . $_SESSION['username'] . " [/i] [/sm]";
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "chat_box` SET `msg` = " . escape( strip_tags( $msg ) ) . " WHERE `id` =" . escape( $url['r'] ) . " LIMIT 1" );
			// Pranesimas ir nukreipimas
			msg( $lang['system']['done'], $lang['sb']['updated'] );
			redirect( url( "?id,{$_GET['id']};p,$p#" . escape( $url['r'] ) ), "meta" );

		}
	}
}
// Atvaizduojam pranešimus su puslapiavimu - LIMITAS nurodytas viršuje
$sql2 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "chat_box` ORDER BY `time` DESC LIMIT $p, $limit" );
if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}
if ( sizeof( $sql2 ) > 0 ) {

	$text = '';
	$i = 0;

	foreach ( $sql2 as $row ) {
		$admin_tools = "";
		$i++;
		$tr = $i % 2 ? '2' : '';
		if ( ar_admin( 'com' ) ) {
			$admin_tools = "<span style=\"float: right;\"><a href='" . url( "d," . $row['id'] . "" ) . "' onclick=\"return confirm('{$lang['system']['delete_confirm']}') \"><img height=\"15\" src='images/icons/cross.png' alt='[{$lang['admin']['delete'] }]' title='{$lang['admin']['delete'] }' class='middle' border='0' /></a>
			<a href='" . url( "r," . $row['id'] . "" ) . "'><img height=\"15\" src='images/icons/pencil.png' alt='[{$lang['admin']['edit'] }]' title='{$lang['admin']['edit'] }' class='middle' border='0' /></a> </span>";
		}

		$text .= "<div class=\"tr{$tr}\"><em>{$admin_tools}<a href=\"" . url( "?id," . $url['id'] . ";p,$p#" . $row['id'] ) . "\" name=\"" . $row['id'] . "\" id=\"" . $row['id'] . "\"><img src=\"images/icons/bullet_black.png\" alt=\"#\" class=\"middle\" border=\"0\" /></a> " . user( $row['nikas'], $row['niko_id'] ) . " (" . $row['time'] . ")</em><br />" . smile( bbchat( $row['msg'] ) ) . "</div>";

	}
} else {
	$text = $lang['sb']['empty'];
}
lentele( $lang['sb']['archive'], $text );
if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}
unset( $admin_tools, $text, $viso, $limit );

//PABAIGA - atvaizdavimo


?>
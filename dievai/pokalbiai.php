<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 221 $
 * @$Date: 2009-07-07 16:21:09 +0300 (Tue, 07 Jul 2009) $
 **/

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 5;
$viso  = kiek( 'admin_chat' );
//

unset( $extra );
if ( isset( $_POST['admin_chat_send'] ) && $_POST['admin_chat_send'] == $lang['admin']['send'] && !empty( $_POST['admin_chat'] ) ) {

	if ( isset( $_POST['pm'] ) && $_POST['pm'] != 'x' ) {

		$extra = "[i]{$lang['admin']['globalmessagefor']}:[b]" . $conf['level'][$_POST['pm']]['pavadinimas'] . "[/b][/i]\n---\n";


		if ( $_POST['pm'] == 0 ) {
			$extra = "[i]{$lang['admin']['globalmessagefor']}: [b] {$lang['admin']['all']} [/b][/i]\n---\n";

			$sql = mysql_query1( "SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users`" );
		} else {
			$extra = "[i]{$lang['admin']['globalmessagefor']}:[b]" . $conf['level'][$_POST['pm']]['pavadinimas'] . "[/b][/i]\n---\n";
			$sql   = mysql_query1( "SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis = '" . $_POST['pm'] . "'" );
		}
		if ( sizeof( $sql ) > 0 ) {
			foreach ( $sql as $row ) {
				if ( kiek( "private_msg", "WHERE `to`=" . escape( $row['nick'] ) . "" ) < 51 ) {
					mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "private_msg` (`from` , `to` , `title` , `msg` , `date`) VALUES (" . escape( $_SESSION[SLAPTAS]['username'] ) . ", " . escape( $row['nick'] ) . ", '" . $lang['admin']['readme'] . "!', " . escape( $_POST['admin_chat'] ) . ", '" . time() . "')" );
				}
			}
		}
	}


	mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "admin_chat` (admin, msg, date) VALUES(" . escape( $_SESSION[SLAPTAS]['username'] ) . "," . escape( $extra . $_POST['admin_chat'] ) . ",'" . time() . "')" ) or die( mysqli_error($prisijungimas_prie_mysql) );
	redirect( $_SERVER['HTTP_REFERER'] );
}
//trinam zinute
if ( isset( $url['d'] ) && !isset( $url['a'] ) && isnum( $url['d'] ) && $url['d'] > 0 && LEVEL == 1 ) {
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "admin_chat` WHERE id=" . (int)$url['d'] . "" );
	redirect( $_SERVER['HTTP_REFERER'] );
}
//redaguojam zinute
if ( isset( $url['r'] ) && !isset( $url['d'] ) && !isset( $url['a'] ) && isnum( $url['r'] ) && $url['r'] > 0 ) {
	if ( !isset( $_POST['admin_chat_send'] ) ) {
		$extra = mysql_query1( "SELECT msg FROM `" . LENTELES_PRIESAGA . "admin_chat` WHERE id=" . escape( (int)$url['r'] ) . " LIMIT 1" );
		$extra = $extra['msg'];
	} elseif ( $_POST['admin_chat_send'] == $lang['admin']['edit'] ) {
		mysqli_query( "UPDATE `" . LENTELES_PRIESAGA . "admin_chat` SET `msg`=" . escape( $_POST['admin_chat'] ) . ",`date` = '" . time() . "' WHERE `admin`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND id=" . escape( (int)$url['r'] ) . " LIMIT 1" );
		//header("Location: ".url("?id," . $url['id']));
		redirect( $_SERVER['PHP_SELF'] );
	}
}
$lygiai = array_keys( $conf['level'] );
$teises = "<option value='x'>{$lang['admin']['noone']}";
$teises .= "<option value='0'>{$lang['admin']['all']}";
foreach ( $lygiai as $key ) {
	$teises .= '<option value=' . $key . '>' . $conf['level'][$key]['pavadinimas'] . '';
}
$text = "
		<form name=\"admin_chat\" action=\"\" method=\"post\" id=\"chat\">
		<fieldset style='padding:3px'><legend>{$lang['admin']['pmto']}:</legend>
	<select name=\"pm\"   style=\"width:95%;\" >';
                {$teises}
                </select>
	
		
		</fieldset>
		<center>
		<br/>
		<textarea name=\"admin_chat\" rows=\"7\" style=\"width:95%;\" class='input'>" . ( ( isset( $extra ) && isset( $url['r'] ) ) ? input( $extra ) : '' ) . "</textarea>
		<br/>
		" . bbk( "admin_chat" ) . "
        <br/>
        <input name=\"admin_chat_send\" type=\"submit\" value=\"" . ( ( isset( $url['r'] ) && isset( $extra ) ) ? $lang['admin']['edit'] : $lang['admin']['send'] ) . "\">
		</form>
		</center><br/>";

$sql = mysql_query1( "SELECT `" . LENTELES_PRIESAGA . "admin_chat`.*, `" . LENTELES_PRIESAGA . "users`.`email` AS `email` FROM `" . LENTELES_PRIESAGA . "admin_chat` Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "admin_chat`.`admin` = `" . LENTELES_PRIESAGA . "users`.`nick` ORDER BY date DESC LIMIT " . escape( $p ) . "," . $limit );
if ( sizeof( $sql ) > 0 ) {
	$i = 0;
	foreach ( $sql as $row ) {
		$text .= "
				<div class='" . ( is_int( $i / 2 ) ? 'tr2' : 'tr' ) . "'><em><a href=\"" . url( "d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\">[{$lang['admin']['delete']}]</a> " . ( ( $_SESSION[SLAPTAS]['username'] == $row['admin'] ) ? "<a href=\"" . url( "r," . $row['id'] . "" ) . "\">[{$lang['admin']['edit']}]</a> " : "" ) . $row['admin'] . " [" . date( 'Y-m-d H:i:s ', $row['date'] ) . "] - " . kada( date( 'Y-m-d H:i:s ', $row['date'] ) ) . " " . naujas( $row['date'], $row['admin'] ) . "</em><br />
				" . bbcode( $row['msg'] ) . "<br /></div>
		";
		$i++;
	}
}
lentele( "{$lang['admin']['admin_chat']}", $text );

if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}

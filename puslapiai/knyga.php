<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = escape( ceil( (int)$url['p'] ) );
} else {
	$p = 0;
}
$limit = 10;
$viso  = kiek( "knyga" );
include_once ( "priedai/class.php" );
$bla = new forma();
//jei tai moderatorius
if ( $_SESSION['level'] == 1 ) {
	//jei adminas paspaude trinti
	if ( isset( $url['d'] ) && !empty( $url['d'] ) && isnum( $url['d'] ) ) {
		$id = (int)$url['d'];
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "knyga` WHERE `id` = " . escape( $id ) . " LIMIT 1" );
		if ( mysql_affected_rows() > 0 ) {
			msg( $lang['system']['done'], "{$lang['guestbook']['deleted']}" );
		} else {
			klaida( $lang['system']['error'], mysql_error() );
		}
		redirect( url( "?id," . (int)$_GET['id'] . ";p,$p" ), 'header' );
	}
	//Jei adminas paspaude redaguoti
	if ( isset( $url['r'] ) && !empty( $url['r'] ) && $url['r'] > 0 && isnum( $url['r'] ) ) {
		$nick    = $_SESSION['username'];
		$nick_id = $_SESSION['id'];
		if ( empty( $_POST ) ) {
			$msg = mysql_query1( "SELECT `msg` FROM `" . LENTELES_PRIESAGA . "knyga` WHERE `id`=" . escape( ceil( (int)$url['r'] ) ) . " LIMIT 1" );

			$form = array(
				"Form"                        => array( "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "knyga_edit" ),
				$lang['guestbook']['message'] => array( "type" => "textarea", "value" => $msg['msg'], "name" => "msg", "extra" => "rows=5", "class" => "input" ),
				" "                           => array( "type" => "submit", "name" => "knyga", "value" => $lang['admin']['edit'] )
			);
			lentele( $lang['guestbook']['Editmessage'], $bla->form( $form ) );
		} elseif ( isset( $_POST['knyga'] ) && $_POST['knyga'] == $lang['admin']['edit'] && !empty( $_POST['msg'] ) ) {
			$msg = trim( $_POST['msg'] ) . "\n[sm][i]Redagavo: " . $_SESSION['username'] . "[/i][/sm]";
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "knyga` SET `msg` = " . escape( htmlspecialchars( $msg ) ) . " WHERE `id` =" . escape( $url['r'] ) . " LIMIT 1" );
			if ( mysql_affected_rows() > 0 ) {
				msg( $lang['system']['done'], $lang['guestbook']['messageupdated'] );
			}
			redirect( url( "?id,{$_GET['id']};p,$p#" . escape( $url['r'] ) ), "meta" );
		}
	} /*kam sitas cia elseif (isset($url['a']) && $url['a'] > 0) {
		$id = (int) $url['a'];
		mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "knyga` SET `aproved` = IF(aproved=1, 0, 1) WHERE `id` =" . escape($id) . " LIMIT 1");
		redirect(url("?id,{$_GET['id']};p,{$p}"));
	}*/
}
//Atvaizduojam pranesimus su puslapiavimu - LIMITAS nurodytas virsuje
$sql2 = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "knyga` ORDER BY `time` DESC LIMIT $p, $limit" );
if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}

$text = '';
$i    = 0;
if ( sizeof( $sql2 ) > 0 ) {
	foreach ( $sql2 as $row ) {
		$i++;
		if ( defined( "LEVEL" ) && LEVEL == 1 ) {
			$extra = "<span style=\"float: right;\"><a href='" . url( "d," . $row['id'] . "" ) . "' onclick=\"return confirm('{$lang['system']['delete_confirm']}')\"><img src='images/icons/cross_small.png' alt='[d]' title='{$lang['admin']['delete']}' class='middle' border='0' /></a> <a href='" . url( "r," . $row['id'] . "" ) . "'><img src='images/icons/pencil_small.png' alt='[{$lang['admin']['edit']}]' title='{$lang['admin']['edit']}' class='middle' border='0' /></a></span>  ";
		} else {
			$extra = '';
		}
		if ( is_int( $i / 2 ) ) {
			$tr = "2";
		} else {
			$tr = "";
		}
		$text .= "
		<div class=\"tr$tr\">
			<em>
				" . $extra
			. input( $row['nikas'] ) . " (" . date( 'Y-m-d H:i:s', $row['time'] ) . ") - " . kada( date( 'Y-m-d H:i:s', $row['time'] ) ) . "
			</em><br />
			" . smile( bbchat( wrap( $row['msg'], 80 ) ) ) . "
		</div>";
		/*<a href=\"" . url("?id," . $_GET['id'] . ";a," . $row['id'] . ";p," . $p) . "\" name=\"" . $row['id'] . "\" id=\"" . $row['id'] . "\">
					<img src=\"images/icons/" . ($row['aproved'] == 1 ? "tick_circle.png" : "status_offline.png") . "\" alt=\"#\" class=\"middle\" border=\"0\" />
				</a>*/
	}
}

if ( isset( $_POST['knyga'] ) && $_POST['knyga'] == $lang['guestbook']['submit'] && strtoupper( $_POST['code'] ) == $_SESSION['code'] && !empty( $_POST['zinute'] ) && !empty( $_POST['vardas'] ) ) {
	$msg  = htmlspecialchars( $_POST['zinute'] );
	$nick = $_POST['vardas'];

	mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "knyga` (`nikas`, `msg`, `time` ) VALUES (" . escape( $nick ) . ", " . escape( $msg ) . ", '" . time() . "');" );

	header( 'Location: ' . url( '?id,' . (int)$_GET['id'] ) );
}


if ( !isset( $_GET['r'] ) ) {
	$form = array(
		"Form"                        => array( "action" => "", "method" => "post", "name" => "knyga" ),
		$lang['guestbook']['name']    => array( "type" => "text", "class" => "input", "value" => ( isset( $_SESSION['username'] ) && !empty( $_SESSION['username'] ) ? input( $_SESSION['username'] ) : '' ), "name" => "vardas", "class" => "input" ),
		$lang['guestbook']['message'] => array( "type" => "textarea", "value" => "", "name" => "zinute", "extra" => "rows=5", "class" => "input" ),
		kodas()                       => array( "type" => "text", "value" => "", "name" => "code", "class" => "chapter" ),
		" "                           => array( "type" => "submit", "name" => "knyga", "value" => $lang['guestbook']['submit'] )
	);

	hide( $lang['guestbook']['write'], $bla->form( $form ), TRUE );
}
if ( strlen( $text ) < 1 ) {
	$text = $lang['guestbook']['empty'];
}
lentele( $lang['guestbook']['guestbook'], $text );

if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}
unset( $extra, $text, $forma );
//PABAIGA - atvaizdavimo
?>
<?php

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
$viso  = kiek( "private_msg" );
//
// Trinam laiska
if ( isset( $url['d'] ) && isnum( $url['d'] ) ) {
	if ( $url['d'] == "0" && isset( $_POST['to'] ) && !empty( $_POST['to'] ) && $_POST['del_all'] == $lang['admin']['delete'] ) {
		$sql = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape( $_POST['to'] ) . "" );
		if ( $sql ) {
			msg( $lang['system']['done'], "<b>" . input( $_POST['to'] ) . "</b> {$lang['admin']['pm_msgsdeleted']}." );
			redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );
		} else {
			klaida( $lang['system']['error'], $lang['admin']['pm_deleteerror'] );
		}
	}
	if ( $url['d'] == "0" && isset( $_POST['from'] ) && !empty( $_POST['from'] ) && $_POST['del_all'] == $lang['admin']['delete'] ) {
		$sql = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `from`=" . escape( $_POST['from'] ) . "" );
		//$i = mysqli_affected_rows($prisijungimas_prie_mysql);
		if ( $sql ) {
			msg( $lang['system']['done'], "<b>" . input( $_POST['from'] ) . "</b> {$lang['admin']['pm_msgsdeleted']}." );
			redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );
		} else {
			klaida( $lang['system']['error'], $lang['admin']['pm_deleteerror'] );
		}
	}
	if ( !empty( $url['d'] ) && $url['d'] > 0 ) {
		$sql = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE id=" . escape( (int)$url['d'] ) );
		if ( $sql ) {
			msg( $lang['system']['done'], "{$lang['admin']['pm_deleted']}." );
			redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );
		} else {
			klaida( $lang['system']['error'], $lang['admin']['pm_deleteerror'] );
		}
	}

}


//perziureti laiska
if ( isset( $url['v'] ) ) {
	if ( !empty( $url['v'] ) && (int)$url['v'] > 0 ) {
		$sql = mysql_query1( "SELECT `msg`, `from`,`to`, `title` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `id`=" . escape( (int)$url['v'] ) . " LIMIT 1" );
		if ( count( $sql ) > 0 ) {
			$laiskas = "
				<b>{$lang['admin']['pm_sender']}:</b>  " . $sql['from'] . "<br />
				<b>{$lang['admin']['pm_reciever']}:</b> " . $sql['to'] . "<br />
				<b>{$lang['admin']['pm_subject']}:</b> " . ( isset( $sql['title'] ) && !empty( $sql['title'] ) ? input( trimlink( $sql['title'], 40 ) ) : $lang['admin']['pm_nosubject'] ) . "<br><br />
				<b>{$lang['admin']['pm_message']}:</b><br />" . bbcode( $sql['msg'] ) . "<br /><br />
				<form name=\"replay_pm\" action='' method=\"post\">
					 <input class=\"submit\" type=\"button\" value=\"{$lang['admin']['delete']}\" onclick=\"location.href='" . url( "d," . $url['v'] . ";v,0" ) . "'\"/>
				</form>
				";
			lentele( $lang['admin']['pm_message'], $laiskas );
		} else {
			klaida( $lang['system']['error'], $lang['admin']['pm_nomessage'] );
		}
	}
}


//paruosiam klase lenteliu paisymui
include_once ( ROOT . "priedai/class.php" );

//laisku sarasas
unset( $info );
$info = array();
$sql  = mysql_query1( "
			SELECT SUBSTRING(`msg`,1,50) AS `msg`,
			(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`from`) AS `from_id`,
			(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`to`) AS `to_id`,
			`" . LENTELES_PRIESAGA . "private_msg`.`id`, `" . LENTELES_PRIESAGA . "private_msg`.`from` AS `from_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`to` AS `to_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`title`, `" . LENTELES_PRIESAGA . "private_msg`.`read`, `" . LENTELES_PRIESAGA . "private_msg`.`date`
			FROM `" . LENTELES_PRIESAGA . "private_msg` ORDER BY `id` DESC LIMIT " . escape( $p ) . "," . $limit );
if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		if ( $row['read'] == "NO" ) {
			$extra = "<img src='" . ROOT . "images/pm/pm_new.png' />";
		} else {
			$extra = "<img src='" . ROOT . "images/pm/pm_read.png' />";
		}

		$info[] = array(
			" "                               => $extra,
			"{$lang['admin']['pm_sender']}"   => user( $row['from_nick'], $row['from_id'] ),
			"{$lang['admin']['pm_reciever']}" => user( $row['to_nick'], $row['to_id'] ),
			"{$lang['admin']['pm_subject'] }" => "<a href=\"" . url( "?id,{$_GET['id']};a," . $_GET['a'] . ";v," . $row['id'] ) . "\" title=\"<b>Laiško ištrauka:</b> " . input( trim( strip_tags( str_replace( array( '[', ']' ), '', $row['msg'] ) ) ) ) . "...\" style=\"display:block\">" . ( isset( $row['title'] ) && !empty( $row['title'] ) ? trimlink( input( $row['title'] ), 10 ) : 'Be temos' ) . "</a>",
			"{$lang['admin']['pm_date']}"     => date( 'Y-m-d H:i:s ', $row['date'] ), "{$lang['admin']['action']}" => "
			<a href=\"" . url( "d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a>" );
	}
}
//nupiesiam laisku lentele
$bla = new Table();
lentele( $lang['admin']['pm_messages'], ( count( $info ) > 0 ? $bla->render( $info ) : $lang['sb']['empty'] ) );

if ( $viso > $limit ) {
	lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
}

unset( $info, $row, $viso, $limit, $p );

//laisku trinimas "kam siustu laisku"
$sql = mysql_query1( "SELECT count(*) AS 'viso', `to` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `to` ORDER BY `to`" );
if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		$select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
	}
	$nustatymai = array( "Form" => array( "action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";d,0", "method" => "post", "name" => "reg" ), "{$lang['admin']['pm_deleteto']}:" => array( "type" => "select", "value" => $select, "selected" => $_SESSION[SLAPTAS]['username'], "name" => "to" ), "" => array( "type" => "submit", "name" => "del_all", "value" => $lang['admin']['delete'] ) );
	$bla        = new forma();
	lentele( $lang['admin']['pm_deleteto'], $bla->form( $nustatymai ) );
	unset( $nustatymai, $select, $sql );
}

//laisku tinimas "nuo ko gautu"
$sql = mysql_query1( "SELECT count(*) AS 'viso', `from` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `from` ORDER BY `from`" );
if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		$select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
	}
	$nustatymai = array( "Form" => array( "action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";d,0", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg" ), "{$lang['admin']['pm_deletefrom']}:" => array( "type" => "select", "value" => $select, "selected" => $_SESSION[SLAPTAS]['username'], "name" => "from" ), "" => array( "type" => "submit", "name" => "del_all", "value" => $lang['admin']['delete'] ) );
	$bla        = new forma();
	lentele( $lang['admin']['pm_deletefrom'], $bla->form( $nustatymai ) );
	unset( $nustatymai, $select, $sql );
}
unset( $text );
//unset($_POST);
?>
<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 315 $
 * @$Date: 2009-09-19 10:39:57 +0300 (Sat, 19 Sep 2009) $
 * */

if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
//trinam irasa
if ( isset( $url['d'] ) && isnum( $url['d'] ) && $_SESSION[SLAPTAS]['level'] == 1 ) {
	if ( $url['d'] == "0" && isset( $_POST['ip'] ) && !empty( $_POST['ip'] ) && $_POST['del_all'] == $lang['admin']['delete'] && isnum( $_POST['ip'] ) ) {
		$sql = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "logai` WHERE `ip` = " . escape( $_POST['ip'] ) );
		msg( $lang['system']['done'], "<b>" . long2ip( $_POST['ip'] ) . "</b> {$lang['admin']['logs_logsdeleted']}." );
		redirect( url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "meta" );
	} elseif ( !empty( $url['d'] ) ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "logai` WHERE `id` = " . escape( $url['d'] ) . " LIMIT 1;" );
		header( "location: " . url( "?id," . $url['id'] . ";a,{$_GET['a']}" ) );
	}
//rodom irasa
} elseif ( isset( $url['v'] ) && !empty( $url['v'] ) && isnum( $url['v'] ) ) {
	$sql = mysql_query1( "SELECT id, ip action, time FROM `" . LENTELES_PRIESAGA . "logai` WHERE id=" . escape( $url['v'] ) . " LIMIT 1" );
	lentele( $sql['ip'] . " - " . date( 'Y-m-d H:i:s', $sql['time'] ), input( $sql['action'] ) );
}
//valom zurnala
if ( !empty( $url['t'] ) ) {
	mysql_query1( "TRUNCATE TABLE `" . LENTELES_PRIESAGA . "logai`" );
	mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( " " . $_SESSION[SLAPTAS]['username'] . ":{$lang['admin']['logs_logsdeleted']}." ) . ", '" . time() . "', INET_ATON(" . escape( getip() ) . "))" );
	header( "location: " . url( "?id," . $url['id'] . ";a,{$_GET['a']}" ) );
//rodom zurnala
} else {
	$viso = kiek( "logai" );
	$sql  = mysql_query1( "SELECT `" . LENTELES_PRIESAGA . "logai`.`id`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) as ip, `" . LENTELES_PRIESAGA . "logai`.`action`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) AS ip1, `" . LENTELES_PRIESAGA . "logai`.`time`,	IF(`" . LENTELES_PRIESAGA . "users`.`nick` <> '', `" . LENTELES_PRIESAGA . "users`.`nick`, 'Svečias') AS nick, IF(`" . LENTELES_PRIESAGA . "users`.`id` <> '', `" . LENTELES_PRIESAGA . "users`.`id`, '0') AS nick_id, IF(`" . LENTELES_PRIESAGA . "users`.`levelis` <> '', `" . LENTELES_PRIESAGA . "users`.`levelis`, '0') AS levelis	FROM `" . LENTELES_PRIESAGA . "logai` Left Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "logai`.`ip` = `" . LENTELES_PRIESAGA . "users`.`ip`	ORDER BY `id` DESC LIMIT {$p}, {$limit}" );

	$info = array();

	if ( sizeof( $sql ) > 0 ) {
		foreach ( $sql as $row ) {
			if ( $row['nick'] == $lang['system']['guest'] ) {
				$kas = $lang['system']['guest'];
			} else {
				$kas = user( $row['nick'], $row['nick_id'], $row['levelis'] );
			}
			$info[] = array( $lang['admin']['logs_log'] => "<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};v," . $row['id'] ) . "\" title=\"{$lang['admin']['logs_date']}: <b>" . date( 'Y-m-d H:i:s', $row['time'] ) . "</b><br/>IP: <b>" . $row['ip1'] . "</b><br/>{$lang['admin']['logs_log']}: <i>" . wrap1( input( $row['action'] ), 50 ) . "</i><br/>\">" . trimlink( input( strip_tags( $row['action'] ) ), 100 ) . "</a>", $lang['admin']['logs_user'] => $kas, $lang['admin']['action'] => "<a href=\"" . url( "d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a> <a href='" . url( "?id," . $url['id'] . ";a,{$admin_pagesid['banai']};b,1;ip," . $row['ip'] ) . "' title='{$lang['admin']['badip']}'><img src=\"" . ROOT . "images/icons/delete.png\" alt=\"[{$lang['admin']['badip']}]\" border=\"0\" class=\"middle\" /></a>" );
		}
		$bla                                     = new Table();
		$bla->width[$lang['admin']['action']]    = '50px';
		$bla->width[$lang['admin']['logs_user']] = '150px';
		lentele( "{$lang['admin']['logai']} - {$lang['admin']['logs_yourip']}: <font color='red'>" . getip() . "</font>", $bla->render( $info ) );
	} else {
		msg( $lang['system']['warning'], $lang['admin']['logs_nologs'] );
	}

	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}


	$sql = mysql_query1( "SELECT count(*) as viso, ip, INET_NTOA(ip) AS ip1 FROM `" . LENTELES_PRIESAGA . "logai` GROUP BY ip ORDER BY time DESC" );
	if ( sizeof( $sql ) > 0 ) {
		foreach ( $sql as $row ) {
			$select[$row['ip']] = $row['ip1'] . " - " . $row['viso'];
		}

		$delete = array( "Form" => array( "action" => url( "?id," . $url['id'] . ";a," . $url['a'] . ";d,0" ), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg" ), "{$lang['admin']['logs_deletebyip']}:" => array( "type" => "select", "value" => $select, "selected" => ip2long( $_SERVER['REMOTE_ADDR'] ), "name" => "ip" ), "" => array( "type" => "submit", "name" => "del_all", "extra" => "onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"", "value" => $lang['admin']['delete'] ) );
		
		$formClass = new Form($delete);
		lentele($lang['admin']['logs_delete'], $formClass->form());

		$delete = array( "Form" => array( "action" => url( "?id," . $url['id'] . ";a," . $url['a'] . ";t,1" ), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg" ), $lang['admin']['logs_clear'] => array( "type" => "submit", "name" => "del_all", "value" => "Valyti", "extra" => "onclick=\"return confirm('{$lang['system']['delete_confirm']}')\"" ) );
		
		$formClass = new Form($delete);
		lentele($lang['admin']['logs_clear'], $formClass->form());
	}
}
unset( $row, $bla, $info, $sql, $select, $viso, $nustatymai );
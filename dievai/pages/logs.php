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
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> long2ip($_POST['ip']) . ' ' . $lang['admin']['logs_logsdeleted']
			]
		);

	} elseif ( !empty( $url['d'] ) ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "logai` WHERE `id` = " . escape( $url['d'] ) . " LIMIT 1;" );
		
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['logs_logsdeleted']
			]
		);
	}
//rodom irasa
} elseif ( isset( $url['v'] ) && !empty( $url['v'] ) && isnum( $url['v'] ) ) {
	$sql = mysql_query1( "SELECT id, ip, action, time FROM `" . LENTELES_PRIESAGA . "logai` WHERE id=" . escape( $url['v'] ) . " LIMIT 1" );
	lentele( $sql['ip'] . " - " . date( 'Y-m-d H:i:s', $sql['time'] ), input( $sql['action'] ) );
}
//valom zurnala
if ( !empty( $url['t'] ) ) {
	mysql_query1( "TRUNCATE TABLE `" . LENTELES_PRIESAGA . "logai`" );
	mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape( " " . $_SESSION[SLAPTAS]['username'] . ":{$lang['admin']['logs_logsdeleted']}." ) . ", '" . time() . "', '" . escape( getip() ) . "')" );
	
	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> $lang['admin']['posts_deleted']
		]
	);
//rodom zurnala
} else {
	$viso = kiek( "logai" );
		
	$info = [];
	$sqlQuery = "SELECT `" . LENTELES_PRIESAGA . "logai`.`id`, `" . LENTELES_PRIESAGA . "logai`.`ip` AS ip, `" . LENTELES_PRIESAGA . "logai`.`action`, `" . LENTELES_PRIESAGA . "logai`.`ip` AS ip1, `" . LENTELES_PRIESAGA . "logai`.`time`,	IF(`" . LENTELES_PRIESAGA . "users`.`nick` <> '', `" . LENTELES_PRIESAGA . "users`.`nick`, 'Svečias') AS nick, IF(`" . LENTELES_PRIESAGA . "users`.`id` <> '', `" . LENTELES_PRIESAGA . "users`.`id`, '0') AS nick_id, IF(`" . LENTELES_PRIESAGA . "users`.`levelis` <> '', `" . LENTELES_PRIESAGA . "users`.`levelis`, '0') AS levelis	FROM `" . LENTELES_PRIESAGA . "logai` Left Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "logai`.`ip` = `" . LENTELES_PRIESAGA . "users`.`ip`	ORDER BY `id` DESC LIMIT {$p}, {$limit}";

	if ($sql  = mysql_query1($sqlQuery)) {
		foreach ( $sql as $row ) {
			if ( $row['nick'] == $lang['system']['guest'] ) {
				$kas = $lang['system']['guest'];
			} else {
				$kas = user( $row['nick'], $row['nick_id'], $row['levelis'] );
			}
			$info[] = array( $lang['admin']['logs_log'] => "<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};v," . $row['id'] ) . "\" title=\"{$lang['admin']['logs_date']}: <b>" . date( 'Y-m-d H:i:s', $row['time'] ) . "</b><br/>IP: <b>" . $row['ip1'] . "</b><br/>{$lang['admin']['logs_log']}: <i>" . wrap1( input( $row['action'] ), 50 ) . "</i><br/>\">" . trimlink( input( strip_tags( $row['action'] ) ), 100 ) . "</a>", $lang['admin']['logs_user'] => $kas, $lang['admin']['action'] => "<a href=\"" . url( "d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a> <a href='" . url( "?id," . $url['id'] . ";a," . getAdminPagesbyId('bans') . ";b,1;ip," . $row['ip'] ) . "' title='{$lang['admin']['badip']}'><img src=\"" . ROOT . "images/icons/delete.png\" alt=\"[{$lang['admin']['badip']}]\" border=\"0\" class=\"middle\" /></a>" );
		}
		
		$title = $lang['admin']['logs'] . ' - ' . $lang['admin']['logs_yourip'] . ': <span color="red">' . getip() . '</span>';
		
		$tableClass = new Table($info);
		$tableClassla->width[$lang['admin']['action']]	= '50px';
		$tableClass->width[$lang['admin']['logs_user']]	= '150px';

		lentele($title, $tableClass->render());

	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $lang['admin']['logs_nologs']
			]
		);
	}

	// if list is bigger than limit, then we show list with pagination
	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}

	$sql = mysql_query1("SELECT count(`id`) as `viso`, `ip` FROM `" . LENTELES_PRIESAGA . "logai` GROUP BY `ip`, `id` ORDER BY `time` DESC");
	
	if (! empty($sql)) {
		foreach ( $sql as $row ) {
			$select[$row['ip']] = $row['ip'] . " - " . $row['viso'];
		}

		$delete = [
			"Form" => [
				"action" 	=> url( "?id," . $url['id'] . ";a," . $url['a'] . ";d,0" ),
				"method" 	=> "post",
				"name" 		=> "reg"
			],

			$lang['admin']['logs_deletebyip'] => [
				"type" 		=> "select",
				"value" 	=> $select,
				"selected" 	=> ip2long( $_SERVER['REMOTE_ADDR'] ),
				"name" 		=> "ip"
			],

			"" => [
				"type" 		=> "submit",
				"name" 		=> "del_all",
				'form_line'	=> 'form-not-line',
				"extra" 	=> "onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"",
				"value" 	=> $lang['admin']['delete']
			]
		];
		
		$formClass = new Form($delete);
		lentele($lang['admin']['logs_delete'], $formClass->form());

		$delete = [
			"Form" => [
				"action" 	=> url( "?id," . $url['id'] . ";a," . $url['a'] . ";t,1" ), 
				"method" 	=> "post",
				"name" 		=> "reg"
			], 

			$lang['admin']['logs_clear'] => [
				"type" 		=> "submit",
				"name" 		=> "del_all",
				"value" 	=> $lang['admin']['logs_clear'],
				'form_line'	=> 'form-not-line',
				"extra" 	=> "onclick=\"return confirm('{$lang['system']['delete_confirm']}')\""
			]
		];
		
		$formClass = new Form($delete);
		lentele($lang['admin']['logs_clear'], $formClass->form());
	}
}

unset($row, $info, $sql, $select, $viso, $nustatymai);
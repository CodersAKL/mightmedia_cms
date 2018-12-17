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
if (isset($url['d']) && isnum($url['d'])) {
	if ($url['d'] == "0" && isset( $_POST['to'] ) && !empty( $_POST['to'] ) && $_POST['del_all'] == $lang['admin']['delete'] ) {
		$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape($_POST['to']);

		if (mysql_query1($deleteQuery)) {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['pm_msgsdeleted']
				]
			);
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['pm_deleteerror']
				]
			);
		}
	}
	if ($url['d'] == "0" && isset($_POST['from']) && ! empty($_POST['from']) && $_POST['del_all'] == $lang['admin']['delete']) {
		$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `from`=" . escape($_POST['from']);

		if (mysql_query1($deleteQuery)) {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['pm_msgsdeleted']
				]
			);
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['pm_deleteerror']
				]
			);
		}
	}
	if (! empty($url['d']) && $url['d'] > 0) {
		$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE id=" . escape((int)$url['d']);

		if (mysql_query1($deleteQuery)) {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['pm_deleted']
				]
			);
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['pm_deleteerror']
				]
			);
		}
	}

}

//perziureti laiska
if ( isset( $url['v'] ) ) {
	if ( !empty( $url['v'] ) && (int)$url['v'] > 0 ) {
		$pmQuery = "SELECT `msg`, `from`,`to`, `title` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `id`=" . escape((int)$url['v']) . " LIMIT 1";

		if ($sql = mysql_query1($pmQuery)) {
			$laiskas = "
				<b>{$lang['admin']['pm_sender']}:</b>  " . $sql['from'] . "<br />
				<b>{$lang['admin']['pm_reciever']}:</b> " . $sql['to'] . "<br />
				<b>{$lang['admin']['pm_subject']}:</b> " . ( isset( $sql['title'] ) && !empty( $sql['title'] ) ? input( trimlink( $sql['title'], 40 ) ) : $lang['admin']['pm_nosubject'] ) . "<br><br />
				<b>{$lang['admin']['pm_message']}:</b><br />" . bbcode( $sql['msg'] ) . "<br /><br />
				<a class='btn bg-red waves-effect' href='" . url( "d," . $url['v'] . ";v,0" ) . ">" . $lang['admin']['delete'] . "</a>";

			lentele( $lang['admin']['pm_message'], $laiskas );
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['pm_nomessage']
				]
			);
		}
	}
}

//laisku sarasas
unset($info);
$info = [];
$sqlQuery ="SELECT SUBSTRING(`msg`,1,50) AS `msg`,
(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`from`) AS `from_id`,
(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`to`) AS `to_id`,
`" . LENTELES_PRIESAGA . "private_msg`.`id`, `" . LENTELES_PRIESAGA . "private_msg`.`from` AS `from_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`to` AS `to_nick`, `" . LENTELES_PRIESAGA . "private_msg`.`title`, `" . LENTELES_PRIESAGA . "private_msg`.`read`, `" . LENTELES_PRIESAGA . "private_msg`.`date`
FROM `" . LENTELES_PRIESAGA . "private_msg` ORDER BY `id` DESC LIMIT " . escape( $p ) . "," . $limit;

if ($sql = mysql_query1($sqlQuery)) {
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
			<a href=\"" . url( "d," . $row['id'] . "" ) . "\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a>"
		);
	}
}
//nupiesiam laisku lentele
if(! empty($info)) {
	$formClass = new Table($info);
	lentele($lang['admin']['pm_messages'], $formClass->render());
	// if list is bigger than limit, then we show list with pagination
	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}
} else {
	notifyMsg(
		[
			'type'		=> 'error',
			'message' 	=> $lang['user']['pm_empty_msg']
		]
	);
}

unset($info, $row, $viso, $limit, $p);

//laisku trinimas "kam siustu laisku"
$sqlQuery = "SELECT count(*) AS 'viso', `to` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `to` ORDER BY `to`";

if ($sql = mysql_query1($sqlQuery)) {
	foreach ($sql as $row) {
		$select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
	}
	$nustatymai = [
		"Form" 							=> [
			"action" 	=> "?id," . $url['id'] . ";a," . $url['a'] . ";d,0", 
			"method" 	=> "post", 
			"name" 		=> "reg"
		], 

		$lang['admin']['pm_deleteto'] 	=> [
			"type" 		=> "select", 
			"value" 	=> $select, 
			"selected" 	=> $_SESSION[SLAPTAS]['username'], 
			"name" 		=> "to"
		], 

		"" 								=> [
			"type" 	=> "submit", 
			"name" 	=> "del_all", 
			"value" => $lang['admin']['delete']
		]
	];
	
	$formClass = new Form($nustatymai);	
	lentele($lang['admin']['pm_deleteto'], $formClass->form());

	unset( $nustatymai, $select, $sql );
}

//laisku tinimas "nuo ko gautu"
$sql = mysql_query1( "SELECT count(*) AS 'viso', `from` AS 'nick' FROM `" . LENTELES_PRIESAGA . "private_msg` GROUP BY `from` ORDER BY `from`" );
if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		$select[$row['nick']] = $row['nick'] . " - " . $row['viso'];
	}
	$nustatymai = [
		"Form" 							=> [
			"action" 	=> "?id," . $url['id'] . ";a," . $url['a'] . ";d,0", 
			"method" 	=> "post", 
			"name" 		=> "reg"
		], 

		$lang['admin']['pm_deletefrom'] => [
			"type" 		=> "select", 
			"value" 	=> $select, 
			"selected" 	=> $_SESSION[SLAPTAS]['username'], 
			"name" 		=> "from"
		], 

		"" 								=> [
			"type" 	=> "submit", 
			"name" 	=> "del_all", 
			"value" => $lang['admin']['delete']
		]
	];
	
	$formClass = new Form($nustatymai);	
	lentele($lang['admin']['pm_deletefrom'], $formClass->form());

	unset($nustatymai, $select, $sql);
}

unset($text);
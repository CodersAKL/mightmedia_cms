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

if ( !isset( $_SESSION[SLAPTAS]['username'] ) ) {
	header( "Location: " . url( "?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}" ) );
}
unset( $text );
if ( isset( $url['u'] ) && !empty( $url['u'] ) ) {
	$user = input( base64_decode( $url['u'] ) );
} else {
	$user = '';
}
if ( isset( $url['i'] ) && isnum( $url['i'] ) && $url['i'] > 0 ) {
	$pid = (int)$url['i'];
} else {
	$pid = 0;
} //kam atsakom
if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] > 0 ) {
	$did = (int)$url['d'];
} else {
	$did = 0;
} //ka trinam
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
} //puslapiavimas
if ( isset( $url['a'] ) && isnum( $url['a'] ) && $url['a'] >= 0 ) {
	$a = (int)$url['a'];
} else {
	$a = 0;
}


$limit     = 30;
$uzeris    = mysql_query1( "SELECT `pm_viso`,`nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE nick='" . $_SESSION[SLAPTAS]['username'] . "' LIMIT 1" );
$pm_sk     = kiek( "private_msg", "WHERE `to`=" . escape( $uzeris['nick'] ) );
$date['m'] = 'Viso';
$date['d'] = $pm_sk;
// ################# Trinam zinute ###########################
if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] >= 0 && isset( $_SESSION[SLAPTAS]['username'] ) ) {
	if ( $url['d'] == 0 ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) );
		header( "Location: " . url( "?id," . $url['id'] . ";p," . $url['p'] ) );
	} elseif ( (int)$url['d'] > 0 ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND `id`=" . escape( (int)$url['d'] ) );
		header( "Location: " . url( "?id," . $url['id'] . ";p," . $url['p'] ) );
	}
}
//print_r($_POST);
if ( isset( $_POST['pm_s'] ) ) {
	foreach ( $_POST['pm_s'] as $a=> $b ) {
		$trinti[] = "`id`=" . escape( $b );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND  " . implode( $trinti, " OR " ) . "" );
	header( "Location:" . url( "?id,{$_GET['id']};a,1" ) );
	exit;
}
// ################# Siunciam zinute ##########################
if ( isset( $_POST['action'] ) && $_POST['action'] == 'pm_send' && isset( $_SESSION[SLAPTAS]['username'] ) ) {
	$from = $_SESSION[SLAPTAS]['username'];
	$to   = $_POST['to'];
	if ( $to == $_SESSION[SLAPTAS]['username'] ) {
		$error = "{$lang['user']['pm_error']}<br />";
	}
	$title = ( isset( $_POST['title'] ) && !empty( $_POST['title'] ) ? $_POST['title'] : $lang['user']['pm_nosubject'] );
	if ( !isset( $title ) ) {
		$title = $lang['user']['pm_nosubject'];
	}
	$msg  = $_POST['msg'];
	$date = time();
	$sql  = mysql_query1( "SELECT nick,email FROM `" . LENTELES_PRIESAGA . "users` WHERE nick=" . escape( $to ) . " LIMIT 1" );
	if ( !isset( $sql['nick'] ) ) {
		$error = $lang['user']['pm_noreceiver'];
	}
	if ( !isset( $error ) ) {
		if ( kiek( "private_msg", "WHERE `to`=" . escape( $to ) . "" ) < 51 ) {
			$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "private_msg` (`from`, `to`, `title`, `msg`, `read`, `date`) VALUES (" . escape( $from ) . ", " . escape( $to ) . ", " . escape( $title ) . ", " . escape( $msg ) . ", 'NO', '" . $date . "')" );
			if ( !$result ) {
				$error = $lang['user']['pm_error'];
			}
			if ( $result ) {
				$error = $lang['user']['pm_sent'];
				msg( $lang['user']['pm_sent'], $error );
				unset( $result, $error, $sql, $_POST );
				redirect( url( "?id," . $url['id'] ), "meta" );
			}
		} else {
			klaida( $lang['system']['error'], "{$lang['user']['pm_users'] } <b>" . $to . "</b> {$lang['user']['pm_full']}." );
			redirect( url( "?id," . $url['id'] ), "meta" );
		}
	} else {
		klaida( $lang['system']['error'], $error );
	}
}
// ######### Paneles rodymas ir zinuciu isvedimas ######################
$text   = "
<fieldset>
<legend>{$lang['user']['pm_freespace']}</legend>
<table border=0>
	<tr>
		<td>
		{$lang['user']['pm_left']}: <b>" . ( $uzeris['pm_viso'] - $pm_sk ) . " {$lang['user']['pm_of']} <b>" . $uzeris['pm_viso'] . "</b></b><br />
		";
$pm_img = substr( $pm_sk, 0, -1 );
$pm_img = "<img src='images/pm/" . ( empty( $pm_img ) ? '0' : $pm_img ) . ".gif'/>";
$text .= $pm_img . "
		</td>
	</tr>
	<tr>
		<td></td>
	</tr>
</table>
</fieldset>

<fieldset>
<legend>{$lang['user']['pm_actions']}</legend>
	<table width='100%'>
		<tr>
			<td>
				<div class=\"blokas\"><center><a href='" . url( "?id," . $url['id'] . ";n,1" ) . "'><img src=\"images/pm/new.png\" alt=\"{$lang['user']['pm_new']}\" />{$lang['user']['pm_new']}</a></center></div>
				<div class=\"blokas\"><center><a href='" . url( "?id," . $url['id'] . ";a,1" ) . "'><img src=\"images/pm/inbox.png\" alt=\"{$lang['user']['pm_inbox']}\" />{$lang['user']['pm_inbox']}</a></center></div>
				<div class=\"blokas\"><center><a href='" . url( "?id," . $url['id'] . ";a,2" ) . "'><img src=\"images/pm/outbox.png\" alt=\"{$lang['user']['pm_outbox']}\" />{$lang['user']['pm_outbox']}</a></center></div>
				<div class=\"blokas\"><center><a onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" href='" . url( "?id," . $url['id'] . ";d,0" ) . "' ><img src=\"images/pm/delete_all.png\" alt=\"{$lang['user']['pm_delete_all']}\" />{$lang['user']['pm_delete_all']}</a></center></div>
			</td>
		</tr>
	</table>
 </fieldset>";


// ################### Siusti nauja zinute arba atsakyti i esancia ######################################
if ( isset( $url['n'] ) ) {
	if ( !empty( $url['n'] ) && (int)$url['n'] ) {
		// ############### Jei nera paspaustas atsakyti mygtukas sukuriam paprasta forma #################
		//if (isset($error) && !empty($error)) { msg("Dėmesio!",$error); }
		if ( isset( $user ) && (int)$pid > 0 ) {
			$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `from`=" . escape( $user ) . " AND `id`=" . escape( $pid ) . " AND `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " LIMIT 1" );
			if ( $sql['read'] == "NO" ) {
				mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "private_msg` SET `read`='YES' WHERE `id`=" . escape( $pid ) );
			}
		}
		$text .= "
				<fieldset>
				<legend>" . ( ( isset( $user ) && (int)$pid > 0 ) ? "{$lang['user']['pm_reply']}" : "{$lang['user']['pm_send']}" ) . " </legend>
				<form name=\"msg\" action=\"" . url( "?id," . $conf['puslapiai'][basename( __FILE__ )]['id'] ) . "\" method=\"post\">
					<table border=0 width=\"100%\">
					<tr>
						<td width=\"15%\" class=\"sarasas\">{$lang['user']['pm_to']}:</td>
						<td>
							<input type=\"text\" name=\"to\" value=\"" . ( isset( $user ) && $_SESSION[SLAPTAS]['username'] != $user ? strtolower( $user ) : '' ) . "\" />
						</td>
					</tr>
					<tr>
						<td class=\"sarasas\">{$lang['user']['pm_subject']}:</td>
						<td><input name=\"title\" type=\"text\" size=\"50\" value=\"" . ( ( isset( $user ) && (int)$pid > 0 ) ? "Re: " . input( trimlink( $sql['title'], 40 ) ) : "" ) . "\" style=\"width:95%\"></td>
					</tr>
					<tr>
						<td valign='top' align='left' class=\"sarasas\">{$lang['user']['pm_message']}:</td>
						<td><textarea name=\"msg\" rows=\"10\" cols=\"50\" wrap=\"on\" style=\"width:95%\">" . ( ( isset( $user ) && (int)$pid > 0 ) ? "[quote=" . $user . "]" . input( trim( preg_replace( array( "#\[quote=(http://)?(.*?)\](.*?)\[/quote]#si", "[/quote]" ), "", $sql['msg'] ) ) . "[/quote]\n\n" ) : "" ) . "</textarea>
						<br />
						" . bbk( "msg" ) . "
						<br />
						<input type=\"submit\" value=\"" . ( ( isset( $user ) && (int)$pid > 0 ) ? "{$lang['user']['pm_reply']}" : "{$lang['user']['pm_send']}" ) . "\">
						<input type=\"hidden\" name=\"action\" value=\"pm_send\" />
					</td>
					</tr>
					</table>
				</form>
			</fieldset><script>addText('msg', '', '');</script>
			";
		//}
	} else {
		header( "Location: " . url( "?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}" ) );
	}
}

lentele( $lang['user']['pm_panel'], $text );
unset( $text );

// ##################### Perziureti zinute ######################
if ( isset( $url['v'] ) ) {
	if ( !empty( $url['v'] ) && (int)$url['v'] > 0 && isnum( $url['v'] ) ) {

		$sql = mysql_query1( "SELECT `msg`, `from`,`to`, `title`,(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`from`) AS `from_id` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE (`to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " OR `from`=" . escape( $_SESSION[SLAPTAS]['username'] ) . ") AND `id`=" . escape( $url['v'] ) . " LIMIT 1" );
		if ( $sql ) {
			addtotitle( $sql['title'] );
			$laiskas = "
				<div class=\"pm_read\"><b>{$lang['user']['pm_from']}:</b>  " . input( $sql['from'] ) . "<br><b>{$lang['user']['pm_to']}:</b> " . $sql['to'] . "<br /> <b>{$lang['user']['pm_subject']}:</b> " . ( isset( $sql['title'] ) && !empty( $sql['title'] ) ? input( $sql['title'] ) : "{$lang['user']['pm_nosubject']}" ) . "<br><br><b>{$lang['user']['pm_message']}:</b><br />" . bbcode( $sql['msg'] ) . "<br /><br /></div>
				" . ( strtolower( $sql['to'] ) == strtolower( $_SESSION[SLAPTAS]['username'] ) ? "<form name=\"replay_pm\" action='" . url( "?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace( "=", "", base64_encode( $sql['from'] ) ) . ";i," . $url['v'] ) . "' method=\"post\">
					<input type=\"submit\" value=\"{$lang['user']['pm_reply']}\"/> <input type=\"button\" value=\"{$lang['user']['pm_delete']}\" onclick=\"location.href='" . url( "d," . $url['v'] . ";v,0" ) . "'\"/>
				</form>" : "" ) . "
				";

			lentele( "{$lang['user']['pm_message']}", $laiskas );

			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "private_msg` SET `read`='YES' WHERE `id`=" . escape( $url['v'] ) . " AND `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . "" );
		}
	}
}
if ( $_SESSION[SLAPTAS]['level'] > 0 && $a == 1 && !isset( $s ) ) {
	include_once ( ROOTAS . "priedai/class.php" );
	$sql = mysql_query1( "SELECT `id`, `read`,`from`, IF(`from` = '', 'Sve�?ias',`from`) AS `Nuo`,(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`from`) AS `from_id`, INSERT(LEFT(`msg`,80),80,3,'...') AS `Žinutė`, IF(`title` = '', 'Be pavadinimo',INSERT(LEFT(`title`,80),80,3,'...')) AS `Pavadinimas`, `date` AS `Data` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `to`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " ORDER BY `" . LENTELES_PRIESAGA . "private_msg`.`date` DESC LIMIT $p,$limit" );
	if ( sizeof( $sql ) > 0 ) {

		$bla  = new Table();
		$info = array();

		foreach ( $sql as $row ) {
			if ( $row['read'] == "NO" ) {
				$extra = "<img src='".ROOT."/images/pm/pm_new.png' />";
			} else {
				$extra = "<img src='".ROOT."/images/pm/pm_read.png' />";
			}
			$info[] = array( "<input type=\"checkbox\" name='visi' onclick='checkedAll(\"pm\");'/>"=> "<input type=\"checkbox\" name=\"pm_s[]\" value=\"{$row['id']}\" />", "" => $extra, "{$lang['user']['pm_subject']}:" => "<a href='" . url( "?id," . $url['id'] . ";v," . $row['id'] ) . "' style=\"display: block\">" . ( isset( $row['Pavadinimas'] ) && !empty( $row['Pavadinimas'] ) ? input( trimlink( $row['Pavadinimas'], 40 ) ) : "{$lang['user']['pm_nosubject']}" ) . "</a></div>", "{$lang['user']['pm_from']}:" => user( $row['Nuo'], $row['from_id'] ), "{$lang['user']['pm_time']}:" => kada( date( 'Y-m-d H:i:s ', $row['Data'] ) ), " " => "<a href='" . url( "?id," . $url['id'] . ";n,1;u," . str_replace( "=", "", input( base64_encode( $row['from'] ) ) ) . ";i," . $row['id'] ) . "'><img src='images/pm/replay.png' border=0 alt=\"{$lang['user']['pm_reply']}\" title=\"{$lang['user']['pm_reply']}\"/></a><a href='" . url( 'd,' . $row['id'] . '' ) . "' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='images/pm/delete.png' border=0 alt=\"{$lang['user']['pm_delete']}\" title=\"{$lang['user']['pm_delete']}\"/></a>" );
		}
		lentele( "{$lang['user']['pm_inbox']}", puslapiai( $p, $limit, $pm_sk, 10 ) . "<br/><form method=\"post\" id=\"pm\" astion=\"\">" . $bla->render( $info ) . "<input type=\"submit\" onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\" value=\"{$lang['system']['delete']}\" /></form><br/>" . puslapiai( $p, $limit, $pm_sk, 10 ) );
	} else {
		lentele( $lang['user']['pm_inbox'], "{$lang['user']['pm_empty_msg']}" );
	}
}
if ( $_SESSION[SLAPTAS]['level'] > 0 && $a == 2 && !isset( $s ) ) {
	include_once ( ROOTAS . "priedai/class.php" );
	$sql = mysql_query1( "SELECT `id`, `read`, IF(`to` = '', 'Svečias',`to`) AS `to`, INSERT(LEFT(`msg`,80),80,3,'...') AS `Žinutė`, IF(`title` = '', 'Be pavadinimo',INSERT(LEFT(`title`,80),80,3,'...')) AS `Pavadinimas`,(SELECT `id` AS `nick_id` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= `" . LENTELES_PRIESAGA . "private_msg`.`to`) AS `to_id`, `date` AS `Data` FROM `" . LENTELES_PRIESAGA . "private_msg` WHERE `from`=" . escape( $_SESSION[SLAPTAS]['username'] ) . " ORDER BY `" . LENTELES_PRIESAGA . "private_msg`.`date` DESC LIMIT $p,$limit" );
	if ( count( $sql ) > 0 ) {
		$bla  = new Table();
		$info = array();

		foreach ( $sql as $row ) {
			if ( $row['read'] == "NO" ) {
				$extra = "<img src='".ROOT."/images/pm/pm_new.png' />";
			} else {
				$extra = "<img src='".ROOT."/images/pm/pm_read.png' />";
			}
			$info[] = array( "" => $extra, "{$lang['user']['pm_subject']}:" => "<a href='" . url( "?id," . $url['id'] . ";v," . $row['id'] ) . "' title=\"{$lang['user']['pm_time']}: <b>" . date( 'Y-m-d H:i:s', $row['Data'] ) . "</b><br/>{$lang['user']['pm_message']}: <i>" . nl2br( input( str_replace( array( "[", "]" ), "", strip_tags( $row['Žinutė'] ) ) ) ) . "</i><br/>\" style=\"display: block\">" . trimlink( $row['Pavadinimas'], 40 ) . "</a>", "{$lang['user']['pm_to']}:" => user( $row['to'], $row['to_id'] ), "{$lang['user']['pm_time']}:" => kada( date( 'Y-m-d H:i:s ', $row['Data'] ) ) );
		}
		asort( $info );
		lentele( $lang['user']['pm_outbox'], puslapiai( $p, $limit, $pm_sk, 10 ) . "<br/>" . $bla->render( $info ) . "<br/>" . puslapiai( $p, $limit, $pm_sk, 10 ), "" );
	} else {
		lentele( $lang['user']['pm_outbox'], $lang['user']['pm_empty_msg'] );
	}
}
unset( $title, $info, $text, $extra );

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
//subkategorijos
$sid = isset( $url['s'] ) ? (int)$url['s'] : 0;
//temos
$tid = isset( $url['t'] ) ? (int)$url['t'] : 0;
//veiksmai
$aid = isset( $url['a'] ) ? (int)$url['a'] : 0;
//edit
$eid = isset( $url['e'] ) ? (int)$url['e'] : 0;
//puslapiai
$pid = isset( $url['p'] ) ? (int)$url['p'] : 0;
//trinti msg
$did = isset( $url['d'] ) ? (int)$url['d'] : 0;
//trint tema
$kid = isset( $url['k'] ) ? (int)$url['k'] : 0;
//Uzrakinamos temos ID
$lid = isset( $url['l'] ) ? (int)$url['l'] : 0;
//redagavimo
$rid = isset( $url['r'] ) ? (int)$url['r'] : 0;
//citatos
$qid = isset( $url['q'] ) ? (int)$url['q'] : 0;

include_once ( "priedai/class.php" );
$imagedir = ( file_exists( "stiliai/{$conf['Stilius']}/forum/" ) ? "stiliai/{$conf['Stilius']}/forum/" : "images/forum/" );
//kur tu?
$kur = mysql_query1( "SELECT pav, (SELECT pav FROM " . LENTELES_PRIESAGA . "d_straipsniai Where id={$tid} AND `lang` = " . escape( lang() ) . ")AS tema,(SELECT count(id) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE sid={$tid} AND tid={$sid}  AND `lang` = " . escape( lang() ) . ")AS zinute,(SELECT count(id) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE tid={$sid} AND `lang` = " . escape( lang() ) . ")AS subzinute,(SELECT count(id) FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`={$sid} AND `lang` = " . escape( lang() ) . ")AS temos FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`={$sid} AND `lang` = " . escape( lang() ) . " LIMIT 1", 120 );
//Sausainiai naujiems fiksuoti
if ( $sid > 0 ) {
	setcookie( "sub_{$sid}", $kur['subzinute'], time() + ( 60 * 60 * 24 * 365 ) );
	$_COOKIE["sub_{$sid}"] = $kur['subzinute'];
}
if ( $tid > 0 ) {
	setcookie( "nauji_{$tid}", $kur['zinute'], time() + ( 60 * 60 * 24 * 365 ) );
	$_COOKIE["nauji_{$tid}"] = $kur['zinute'];
}
//print_r($_COOKIE);

$tema = "";
$sub  = "";
if ( isset( $kur['pav'] ) && !empty( $kur['pav'] ) ) {
	$sub = " > <a href=\"" . url( "?id," . $url['id'] . ";s," . $sid ) . "\">" . input( $kur['pav'] ) . "</a> (" . $kur['temos'] . ")";
	if ( !empty( $kur['tema'] ) ) {
		$tema = " > <a href=\"" . url( "?id," . $url['id'] . ";s," . $sid . ";t,$tid" ) . "\">" . input( $kur['tema'] ) . "</a> (" . $kur['zinute'] . ")";
	}
	lentele( $lang['forum']['forum'], "<a href=\"" . url( "?id," . $url['id'] ) . "\">{$lang['forum']['forum']}</a>" . $sub . $tema );
}

//kategoriju sarasas
if ( $sid == 0 && $aid == 0 && $kid == 0 && $lid == 0 && $rid == 0 ) {
//Didelė užklausa į kategorijas/subkategorijas :P gera ką?
	$sqlis = mysql_query1( "SELECT
	`" . LENTELES_PRIESAGA . "d_temos`.`pav`,
	`" . LENTELES_PRIESAGA . "d_temos`.`last_data`,
	`" . LENTELES_PRIESAGA . "d_temos`.`last_nick`,
	`" . LENTELES_PRIESAGA . "d_temos`.`fid`,
	`" . LENTELES_PRIESAGA . "d_temos`.`id`as temid,
	`" . LENTELES_PRIESAGA . "d_temos`.`aprasymas`,
	`" . LENTELES_PRIESAGA . "d_forumai`.`pav` AS `kategorija`,
	`" . LENTELES_PRIESAGA . "d_forumai`.`id` AS `katid`,
	(SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=`temid`) AS zinutes,
    (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=`temid`) AS temos
    FROM `" . LENTELES_PRIESAGA . "d_forumai`
    inner Join `" . LENTELES_PRIESAGA . "d_temos` ON `" . LENTELES_PRIESAGA . "d_forumai`.`id`=`" . LENTELES_PRIESAGA . "d_temos`.`fid` 
	WHERE  `" . LENTELES_PRIESAGA . "d_forumai`.`lang` = " . escape( lang() ) . "
	ORDER by `" . LENTELES_PRIESAGA . "d_forumai`.`place` ASC, `" . LENTELES_PRIESAGA . "d_temos`.`place` ASC", 60 );
	//$info = array();
	if ( sizeof( $sqlis ) > 0 ) {
		foreach ( $sqlis as $kat ) {
			//þinuèiø kategorijoj
			$zinutes = (int)$kat['zinutes'];
			//temø kategorijoj
			$temos = (int)$kat['temos'];
			//nustatom ar yra naujø praneðimø
			if ( ( !isset( $_COOKIE['sub_' . $kat['temid']] ) && (int)$kat['last_data'] > 0 && $zinutes > 0 ) || ( isset( $_COOKIE['sub_' . $kat['temid']] ) && (int)$_COOKIE['sub_' . $kat['temid']] < $zinutes ) ) {
				$extra = "<img src='{$imagedir}forumas_naujas.png' alt='{$lang['forum']['newpost']}' title='{$lang['forum']['newpost']}' />";
			} else {
				$extra = "<img src='{$imagedir}forumas.png' alt='{$lang['forum']['topic']}' title='{$lang['forum']['topic']}' />";
			}
			//subkategorijø atvaizdavimo formatas
			$info[$kat['katid']][]                = array( $lang['forum']['forum'] => "<div style=\"margin:0;padding:0;\"><div style=\"float:left; margin: 2px;\">$extra</div><a href='" . url( "?id," . $url['id'] . ";s," . $kat['temid'] ) . "'>" . input( $kat['pav'] ) . "</a> <span class=\"small_about\"style='font-size:9px;width:auto;display:block;'>" . input( $kat['aprasymas'] ) . "</span></div>", $lang['forum']['topics'] => $temos, $lang['forum']['replies'] => $zinutes, $lang['forum']['lastpost'] => ( ( $zinutes > 0 ) ? $kat['last_nick'] . ' <br /> ' . ( ( $kat['last_data'] == '0000000000' ) ? '' : kada( date( 'Y-m-d H:i:s', $kat['last_data'] ) ) ) : '-' ) );
			$blai                                 = new Table();
			$blai->width[$lang['forum']['forum']] = '45%';
			$subai[$kat['katid']]                 = $blai->render( $info[$kat['katid']] );
			$kateg[$kat['katid']]                 = input( $kat['kategorija'] );
		}
		//atvaizduojam kategorijas subkategorijom
		$cont = '';
		foreach ( $kateg as $t => $name ) {
			$table[$t][] = array( "<span style=\"float: left; font-weight: bold;\">{$name}</span>" => '<div style="width: 96%; padding: 5px;">' . $subai[$t] . '</div>' );
			$draw[$t]    = new Table();
			$cont .= $draw[$t]->render( $table[$t] ) . '<br />';
		}
		lentele( $lang['forum']['forum'], $cont );
	} else {
		klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
	}
}

//temu sarasas
if ( $sid > 0 && $tid == 0 && $aid == 0 && $kid == 0 && $lid == 0 && $rid == 0 ) {
	$teise = mysql_query1( "SELECT `teises` FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape( $_GET['s'] ) . " LIMIT 1" );
	if ( isset( $_SESSION[SLAPTAS]['username'] ) && teises( unserialize( $teise['teises'] ), $_SESSION[SLAPTAS]['level'] ) ) {
		echo "<br /><a href='" . url( "a,1" ) . "'><img src='{$imagedir}" . lang() . "/nauja_tema.png' border=0 alt='{$lang['forum']['newpost']}'/></a><br/><br/>";
	}
	$limit = 20;
	$tem   = mysql_query1( "
	SELECT " . LENTELES_PRIESAGA . "d_straipsniai.* ,
	" . LENTELES_PRIESAGA . "d_straipsniai.id as strid ,
	(SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=" . escape( $sid ) . " AND `sid`=strid AND `lang` = " . escape( lang() ) . ") AS viso
	from " . LENTELES_PRIESAGA . "d_straipsniai 
	WHERE " . LENTELES_PRIESAGA . "d_straipsniai.tid=" . escape( $sid ) . " AND `" . LENTELES_PRIESAGA . "d_straipsniai`.`lang` = " . escape( lang() ) . " ORDER by " . LENTELES_PRIESAGA . "d_straipsniai.sticky DESC, " . LENTELES_PRIESAGA . "d_straipsniai.last_data DESC LIMIT " . $pid . ", " . $limit . "", 120 );
	if ( sizeof( $tem ) > 0 ) {
		foreach ( $tem as $temos ) {
			$zinutes = $temos['viso'];
			$limit   = 20;
			$visos   = $kur['temos'];

			if ( $temos['uzrakinta'] == 'taip' ) {
				$extra = "<img src='{$imagedir}uzrakinta.png' alt='{$lang['forum']['locked']}' title='{$lang['forum']['locked']}' />";
			} elseif ( ( !isset( $_COOKIE['nauji_' . $temos['id']] ) && (int)$temos['last_data'] != '0000000000' && $zinutes > 0 ) || ( isset( $_COOKIE['nauji_' . $temos['id']] ) && $_COOKIE['nauji_' . $temos['id'] . ''] < $zinutes ) ) {
				$extra = "<img src='{$imagedir}tema_nauja.png' alt='new' />";
			} else {
				$extra = "<img src='{$imagedir}tema.png' alt='{$lang['forum']['topic']}' title='{$lang['forum']['topic']}' />";
			}
			if ( $temos['sticky'] == '1' ) {
				$svarbu = "<img src='{$imagedir}svarbu.png' alt='{$lang['forum']['sticky']}' title='{$lang['forum']['sticky']}' />";
			} else {
				$svarbu = "";
			}

			$info[] = array( $lang['forum']['topic']    => "<div style=\"float:left; margin: 2px;\">{$extra}{$svarbu}</div><a href='" . url( "?id," . $url['id'] . ";s," . $sid . ";t," . $temos['id'] ) . "'>" . input( $temos['pav'] ) . "</a>",
			                 $lang['forum']['replies']  => $zinutes,
			                 $lang['forum']['lastpost'] => ( ( $zinutes > 0 ) ? $temos['last_nick'] . ' <br /> ' . ( ( $temos['last_data'] == '0000000000' ) ? '' : '<a href="' . url( '?id,' . $_GET['id'] . ';s,' . $_GET['s'] . ';t,' . $temos['id'] . ';p,' . ( (int)( $zinutes / 15 - 0.1 ) * 15 ) ) . '#end">' . kada( date( 'Y-m-d H:i:s', $temos['last_data'] ) ) ) . '</a>' : '-' ) );
			//' . naujas($row['last_data']) . '

		}

		$bla                                 = new Table();
		$bla->width[$lang['forum']['topic']] = '45%';
		$temos                               = $bla->render( $info );
	} else {
		$temos = $lang['forum']['notopics'];
	}
	lentele( $lang['forum']['topics'], $temos );
	if ( isset( $visos ) && $visos > 20 ) {
		lentele( $lang['system']['pages'], puslapiai( $pid, $limit, $visos, 10 ) );
	}
}
//RODOM tema
if ( $tid > 0 && $sid > 0 && $kid == 0 && $lid == 0 && $rid == 0 && $aid == 0 ) {
	$teises = mysql_query1( "SELECT `teises` FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape( $sid ) . " LIMIT 1" );
	if ( isset( $teises['teises'] ) && teises( unserialize( $teises['teises'] ), $_SESSION[SLAPTAS]['level'] ) ) {
		//trinam posta
		if ( $did > 0 && ar_admin( 'frm' ) ) {


			//Cia nesugalvojau kaip visas 3 sujungt :(
			$msql = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `forum_atsakyta`=`forum_atsakyta`-1 , `taskai`=`taskai`-1 WHERE id=(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape( $did ) . " LIMIT 1);" );
			mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape( $did ) . "" );


			if ( $msql ) {
				msg( $lang['system']['done'], "{$lang['forum']['msgdeleted']}" );
				redirect( url( "?id," . $url['id'] . ";t,$tid;s,$sid" ), "meta" );
			} else {
				klaida( $lang['system']['error'], "{$lang['forum']['msgbadid']}" );
				redirect( url( "?id," . $url['id'] . ";t,$tid;s,$sid" ), "meta" );
			}
		}

		$tsql       = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape( (int)$sid ) . " AND `id`=" . escape( (int)$tid ) . " AND `lang` = " . escape( lang() ) . " limit 1" );
		$straipsnis = $tsql['pav'];
		if ( !empty( $straipsnis ) ) {
			if ( ar_admin( 'frm' ) ) {
				$f_text = '';

				if ( $tsql['uzrakinta'] == "taip" ) {
					$f_text .= "<a href='" . url( "?id," . $url['id'] . ";s,$sid;t," . $tid . ";l," . $tid ) . "'><img src='{$imagedir}atrakinti.png' border=0 class='middle' alt='{$lang['forum']['unlock']}' title='{$lang['forum']['unlock']}'/></a>";
				}
				if ( $tsql['uzrakinta'] == "ne" ) {
					$f_text .= "<a href='" . url( "?id," . $url['id'] . ";s,$sid;t," . $tid . ";l," . $tid ) . "'><img src='{$imagedir}uzrakinti.png' border=0 class='middle' alt='{$lang['forum']['lock']}' title='{$lang['forum']['lock']}' /></a>";
				}
				$f_text .= "<a href='" . url( "?id," . $url['id'] . ";s,$sid;t," . $tid . ";k," . $tid ) . "' onclick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='{$imagedir}trinti_tema.png' border=0 class='middle' alt='{$lang['admin']['delete']}' title='{$lang['admin']['delete']}'/></a>";
				$f_text .= "<a href='" . url( "?id," . $url['id'] . ";s,$sid;t," . $tid . ";r," . $tid ) . "'><img src='{$imagedir}redaguoti_tema.png' border=0 class='middle' alt='{$lang['admin']['edit']}' title='{$lang['admin']['edit']}'/></a>";
				lentele( $lang['forum']['func'], $f_text );
			}
			$viso   = $kur['zinute'];
			$limit  = 15;
			$gaunam = mysql_query1( "SELECT " . LENTELES_PRIESAGA . "users.nick, " . LENTELES_PRIESAGA . "users.taskai, " . LENTELES_PRIESAGA . "users.levelis, " . LENTELES_PRIESAGA . "users.gim_data, " . LENTELES_PRIESAGA . "users.email, " . LENTELES_PRIESAGA . "users.id, " . LENTELES_PRIESAGA . "users.miestas, " . LENTELES_PRIESAGA . "users.icq, " . LENTELES_PRIESAGA . "users.msn, " . LENTELES_PRIESAGA . "users.skype, " . LENTELES_PRIESAGA . "users.aim, " . LENTELES_PRIESAGA . "users.url, " . LENTELES_PRIESAGA . "users.parasas, " . LENTELES_PRIESAGA . "users.forum_atsakyta, " . LENTELES_PRIESAGA . "d_zinute.id AS `zid`, " . LENTELES_PRIESAGA . "d_zinute.nick AS `nikas`, `tid`, `sid`,`zinute`,`laikas` FROM " . LENTELES_PRIESAGA . "users INNER JOIN `" . LENTELES_PRIESAGA . "d_zinute` ON " .
				LENTELES_PRIESAGA . "d_zinute.nick=" . LENTELES_PRIESAGA . "users.id WHERE `sid`='" . $tid . "' ORDER BY laikas ASC LIMIT {$pid},{$limit}" );

			$a       = 0;
			$turinys = '';
			unset( $row );
			foreach ( $gaunam as $row ) {
				if ( $pid == '0' ) {
					$a++;
				} else {
					$a = 2;
				}
				if ( isset( $conf['level'][$row['levelis']]['pavadinimas'] ) ) {
					$grupe = $conf['level'][$row['levelis']]['pavadinimas'];
				} else {
					$grupe = '--';
				}

				$extra = "";
				$tool  = "";
				if ( isset( $_SESSION[SLAPTAS]['id'] ) && $row['nikas'] == $_SESSION[SLAPTAS]['id'] || ar_admin( 'frm' ) ) {
					$tool .= " <a style='float: right; margin-right:2px;' href='" . url( "e," . $row['zid'] . "" ) . "#end' title='" . $lang['system']['edit'] . "'><img src='{$imagedir}" . lang() . "/redaguoti.png' border='0' alt='[r]'/></a> ";
					if ( $a != 1 ) {
						$tool .= " <a style='float: right; margin-right:2px;' href='" . url( "?id," . $url['id'] . ";t," . $tid . ";s," . $sid . ";d," . $row['zid'] ) . "' title='" . $lang['system']['delete'] . "'  onclick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='{$imagedir}" . lang() . "/trinti.png' border='0' alt='[t]'/></a> ";
					}
				}
				$reply = ( $_SESSION[SLAPTAS]['level'] > 0 ? ' <a style="float: right; margin-right:2px;"  href="' . url( "q," . $row['zid'] . "#end" ) . '" title="' . $lang['admin']['pm_reply'] . '"><img src="' . $imagedir . lang() . '/atsakyti.png" border="0" alt="re"></a> ' : '' );
				$turinys .= "<div style=\"\" class=\"tr\">
			  <div style=\"margin-bottom: 6px;\" >{$reply}{$tool}<em> " . user( $row['nick'], $row['id'], $row['levelis'] ) . " (" . ( ( $row['laikas'] == '0000000000' ) ? '---' : date( 'Y-m-d H:i:s', $row['laikas'] ) ) . ") " . naujas( $row['laikas'], $row['nick'] ) . "</em></div>
			  <div class=\"avataras\" align=\"left\">" . avatar( $row['email'], 55 ) . "</div><div class=\"tr2\" style=\"\">" . bbcode( $row['zinute'] ) . "<br />" . ( !empty( $row['parasas'] ) ? "<div class=\"signature\">" . bbcode( input( $row['parasas'] ) ) . "</div>" : "" ) . "</div></div>";


				unset( $extra );
			}
			addtotitle( $straipsnis );
			lentele( $straipsnis, puslapiai( $pid, $limit, $viso, 10 ) . $turinys . puslapiai( $pid, $limit, $viso, 10 ) . "<a name='end' id='end'></a>" );

			$tikrinam = mysql_query1( "SELECT `uzrakinta` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `id`=" . escape( $tid ) . " AND `tid`=" . escape( $sid ) . " limit 1" );


			if ( isset( $_SESSION[SLAPTAS]['id'] ) && $kid == 0 && $lid == 0 && $rid == 0 && $eid > 0 ) {

				$extra = '';
				if ( !empty( $_POST['msg'] ) && $_POST['action'] == 'f_update' ) {
					if ( ar_admin( 'frm' ) ) {
						mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_zinute` SET `zinute`=" . escape( $_POST['msg'] . "\n[sm][i]{$lang['forum']['edited_by']}: " . $_SESSION[SLAPTAS]['username'] . " " . date( 'Y-m-d H:i:s', time() ) . "[/i][/sm]" ) . " WHERE `id`=" . escape( $eid ) );
					} else {
						mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_zinute` SET `zinute`=" . escape( $_POST['msg'] . "\n[sm][i]{$lang['forum']['edited_by']}: " . $_SESSION[SLAPTAS]['username'] . " " . date( 'Y-m-d H:i:s', time() ) . "[/i][/sm]" ) . " WHERE `id`=" . escape( $eid ) . " AND `nick`=" . escape( $_SESSION[SLAPTAS]['id'] ) );
					}
					//redirect( url( "?id," . $url['id'] . ";s,$sid;t,$tid;p,{$_GET['p']}" ) );
					redirect( url( "?id," . $url['id'] . ";s,$sid;t,$tid;p,{$pid}" ) );
					//redirect($_SERVER['HTTP_REFERAL']);
				} else {
					if ( ar_admin( 'frm' ) ) {
						$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape( $eid ) . "  LIMIT 1";
					} else {
						$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape( $eid ) . " AND `nick`='" . escape( $_SESSION[SLAPTAS]['id'] ) . "' LIMIT 1";
					}
					$sql   = mysql_query1( $sql, 15 );
					$extra = $sql['zinute'];
				}
			}
			//  Siunciam zinute
			if ( isset( $_POST['action'] ) && $_POST['action'] == 'f_send' && isset( $_POST['msg'] ) && $tikrinam['uzrakinta'] == "ne" ) {
				if ( !isset( $_SESSION[SLAPTAS]['username'] ) ) {
					redirect( url( "?id,{$conf['puslapiai'][$conf['pirminis'] . '.php']['id']}" ) );
				}
				if ( strlen( str_replace( " ", "", $_POST['msg'] ) ) > 0 ) {
					$zinute = $_POST['msg'];
					if ( $tid == 0 ) {
						redirect( url( "?id,{$conf['puslapiai'][$conf['pirminis'] . '.php']['id']}" ) );
					}
					if ( $sid == 0 ) {
						redirect( url( "?id,{$conf['puslapiai'][$conf['pirminis'] . '.php']['id']}" ) );
					}
					if ( $tikrinam['uzrakinta'] == "taip" ) {
						redirect( url( "?id," . $url['id'] . ";t," . $tid . ";s," . $sid ) );
					} else {

						if ( isset( $_POST['post_uid'] ) && $_POST['post_uid'] > 0 ) {
							$uid = (int)$_POST['post_uid'];
						} else {
							$uid = $_SESSION[SLAPTAS]['id'];
						}


						//is 4 dvi uzklausos :), nemoku sujungt update ir insert :(
						mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_temos`,`" . LENTELES_PRIESAGA . "users`,`" . LENTELES_PRIESAGA . "d_straipsniai`
									  SET
									  `" . LENTELES_PRIESAGA . "d_temos`.`last_data`= '" . time() . "',
									  `" . LENTELES_PRIESAGA . "d_temos`.`last_nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . ",
									  `" . LENTELES_PRIESAGA . "d_straipsniai`.`last_data`= '" . time() . "', `" . LENTELES_PRIESAGA . "d_straipsniai`.`last_nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . ",
									  `" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`=`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`+1 ,
									  `" . LENTELES_PRIESAGA . "users`.`taskai`=`" . LENTELES_PRIESAGA . "users`.`taskai`+1
									  WHERE `" . LENTELES_PRIESAGA . "users`.`nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . "
									  AND `" . LENTELES_PRIESAGA . "d_straipsniai`.`id`=" . escape( $tid ) . " AND `" . LENTELES_PRIESAGA . "d_temos`.`id`=" . escape( $sid ) . "
						" );
						mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "d_zinute` (`tid`, `sid`, `nick`, `zinute`, `laikas`) VALUES (" . escape( $sid ) . ", " . escape( $tid ) . ", " . escape( $uid ) . ", " . escape( $zinute ) . ", '" . time() . "')" );
						redirect( url( "?id,{$_GET['id']};s,{$_GET['s']};t,{$_GET['t']};p," . ( (int)( $kur['zinute'] / 15 ) * 15 ) . "#end" ) );

						unset( $zinute, $uid, $f_atsakyta );
					}
				} else {
					klaida( $lang['system']['warning'], $lang['forum']['message_short'] );
				}
			}


			if ( isset( $_SESSION[SLAPTAS]['username'] ) && $tikrinam['uzrakinta'] == "ne" ) {
				$citata = "";
				if ( $qid > 0 ) {

					$cit = mysql_query1( "SELECT *,nick as nikas,(SELECT nick from " . LENTELES_PRIESAGA . "users where id=nikas)as nickas from " . LENTELES_PRIESAGA . "d_zinute where id='" . (int)$qid . "' limit 1", 30 );

					if ( isset( $cit['zinute'] ) ) {
						$citata = "[quote=" . input( $cit['nickas'] ) . "]" . input( $cit['zinute'] ) . "\n[/quote]";
					}
				}
				echo "<script type=\"text/javascript\">$(document).ready(function() {
$('.perveiza').click(function() {
$.post('javascript/forum/preview.php', {'msg':$('textarea#msg').val()}, function(data) {
$(\"#perveiza\").empty().append($(data));
}, \"text\");
});
});
</script>";
				$bla   = new forma();
				$forma = array(
					"Form"                    => array( "action" => "", "method" => "post", "name" => "msg" ),
					"    "                    => array( "type" => "string", "value" => "<div id='perveiza'></div>" ),
					" "                       => array( "type" => "string", "value" => bbs( 'msg' ) ),
					$lang['forum']['message'] => array( "type" => "textarea", "rows" => "8", "value" => ( ( !empty( $extra ) ) ? input( $extra ) : $citata ), "name" => "msg", "class" => "input", "id" => "msg" ),
					"  "                      => array( "type" => "string", "value" => bbk( 'msg' ) ),
					"     "                   => array( "type" => "hidden", "name" => "action", "value" => ( ( !empty( $extra ) ) ? "f_update" : "f_send" ) ),
					""                        => array( "type" => "string", "value"=> "<input type=\"button\" class=\"perveiza\" value=\"{$lang['forum']['perview']}\" /> <input type=\"submit\" class=\"submit\" value=\"" . ( ( !empty( $extra ) ) ? $lang['admin']['edit'] : $lang['forum']['submit'] ) . "\" />" ) );

				lentele( $lang['forum']['newpost'], $bla->form( $forma ) );
			}
		}
	} else {
		klaida( $lang['system']['sorry'], $lang['forum']['not_allowed'] );
	}
} // Uzrakinam/Atrakinam tema
elseif ( (int)$lid != 0 && $kid == 0 && $rid == 0 ) {
	if ( ar_admin( 'frm' ) ) {

		$sql = mysql_query1( "SELECT `uzrakinta` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape( $sid ) . " AND `id`=" . escape( $lid ) . " limit 1" );
		if ( isset( $sql['uzrakinta'] ) ) {
			$lock = $sql['uzrakinta'];

			if ( $lock == "ne" ) {
				$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `uzrakinta`='taip' WHERE `id`=" . escape( $lid ) . "" );
			} elseif ( $lock == "taip" ) {
				$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `uzrakinta`='ne' WHERE `id`=" . escape( $lid ) . "" );
				;
			}

			redirect( url( "?id," . $url['id'] . ";s," . $sid . ";t,$tid" ) );
		}
	}
}

// Trinti tema
elseif ( (int)$kid && (int)$kid && (int)$kid > 0 ) {
	if ( ar_admin( 'frm' ) ) {
		//atimam autoriui tema
		mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` SET `forum_temos`=`forum_temos`-1 WHERE id=(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape( $kid ) . " ORDER BY laikas ASC LIMIT 1) LIMIT 1" );
		$gis = mysql_query1( "SELECT nick FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape( $kid ) . "" );
		foreach ( $gis as $stulpelis ) {
			mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users` set `taskai`=`taskai`-1,`forum_atsakyta`=`forum_atsakyta`- 1 where id=" . escape( $stulpelis['nick'] ) . "" );
		}
		//istrinam zinutes ir tema
		$result = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `id`=" . escape( $kid ) . "" );
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape( $kid ) . "" );
		if ( $result ) {
			redirect( url( "?id," . $url['id'] . ";s," . $sid ) );
		}
	}
//redaguojam tema
} elseif ( ar_admin( 'frm' ) && (int)$rid != 0 ) {
	unset( $tsql );
	$tsql = mysql_query1( "SELECT * FROM " . LENTELES_PRIESAGA . "d_straipsniai WHERE `id`=" . escape( (int)$rid ) . " LIMIT 1", 15 );
	if ( isset( $tsql['pav'] ) ) {
		$sub_kategr = mysql_query1( "SELECT * FROM " . LENTELES_PRIESAGA . "d_temos ORDER BY `pav` AND `lang` = " . escape( lang() ) . " DESC" );
		foreach ( $sub_kategr as $row ) {
			$kategorijos[$row['id']] = $row['pav'];
		}

		$bla  = new forma();
		$form = array(
			"Form"                                   => array(
				"action" => "",
				"method" => "post",
				"name"   => "rename" ),

			"{$lang['admin']['forum_subcategory']}:" => array(
				"type"     => "select",
				"class"    => "select",
				"value"    => $kategorijos,
				"name"     => "keliam",
				"selected" => $tsql['tid'] ),

			"{$lang['admin']['forum_cangeto']}:"     => array(
				"type"  => "text",
				"class" => "input",
				"value" => $tsql['pav'],
				"name"  => "name" ),

			"{$lang['forum']['sticky']}?:"           => array(
				"type"     => "select",
				"class"    => "select",
				"value"    => array(
					"1" => $lang['admin']['yes'],
					"0" => $lang['admin']['no'] ),
				"name"     => "sticky",
				"selected" => $tsql['sticky'] ),

			""                                       => array(
				"type"  => "submit",
				"name"  => "sub",
				"value" => "{$lang['admin']['edit']}" )
		);
		lentele( $tsql['pav'], $bla->form( $form ) );

		if ( isset( $_POST['name'] ) ) {
			$new    = $_POST['name'];
			$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_zinute` SET `tid`=" . escape( $_POST['keliam'] ) . " WHERE `sid`=" . escape( $tsql['id'] ) . "" );
			$result .= mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `tid`=" . escape( $_POST['keliam'] ) . ", `pav`=" . escape( $new ) . ", `sticky`=" . escape( (int)$_POST['sticky'] ) . " WHERE `id`=" . escape( $tsql['id'] ) . "" );
			redirect( url( "?id,{$url['id']};s," . $_POST['keliam'] . ";t,{$rid}" ) );
		}
	}
}

//temos kurimas
elseif ( $aid == 1 && $kid == 0 && $lid == 0 && $rid == 0 ) {
	$teise = mysql_query1( "SELECT `teises` FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape( $_GET['s'] ) . " LIMIT 1" );
	if ( teises( unserialize( $teise['teises'] ), $_SESSION[SLAPTAS]['level'] ) ) {
		if ( isset( $_POST['post_msg'] ) ) {
			if ( !isset( $_SESSION[SLAPTAS]['username'] ) ) {
				redirect( url( "?id,{$conf['puslapiai'][$conf['pirminis'] . '.php']['id']}" ) );
			}
			if ( isset( $_POST['post_uid'] ) && $_POST['post_uid'] > 0 ) {
				$uid = (int)$_POST['post_uid'];
			} else {
				$uid = $_SESSION[SLAPTAS]['id'];
			}
			$pavadinimas = /*input(*/
				$_POST['post_pav'] /*)*/
			;
			$zinute      = /*input(*/
				$_POST['post_msg'] /*)*/
			;
			$error       = "";
			if ( empty( $pavadinimas ) ) {
				$error .= "{$lang['forum']['topicname?']}<br/>";
			}
			if ( empty( $zinute ) || strlen( str_replace( " ", "", $zinute ) ) < 1 ) {
				$error .= $lang['forum']['message?'];
			}
			$result = mysql_query1( "SELECT `id` FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape( $sid ) . " AND `lang` = " . escape( lang() ) . " limit 1" );
			if ( $result == 0 ) {
				$error .= "{$lang['forum']['badurl']}.<br/>";
			}
			unset( $result );
			if ( strlen( $error ) < 1 ) {
				unset( $error );
				$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "d_straipsniai` (`tid`, `pav`, `autorius`,`last_data`,`last_nick`,`lang`) VALUES(" . escape( $sid ) . ", " . escape( $pavadinimas ) . ", " . escape( $_SESSION[SLAPTAS]['username'] ) . ", '" . time() . "', " . escape( $_SESSION[SLAPTAS]['username'] ) . ", " . escape( lang() ) . ")" );
				if ( !$result ) {
					$error .= "<b> " . mysqli_error($prisijungimas_prie_mysql) . "</b>.";
				}
				if ( !isset( $error ) ) {
					unset( $result );
					//`pav`=" . escape($pavadinimas) . " AND
					$inf = mysql_query1( "SELECT max(id) AS `id` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE  autorius=" . escape( $_SESSION[SLAPTAS]['username'] ) . " limit 1" );

					mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "users`,`" . LENTELES_PRIESAGA . "d_temos`
								   SET
								  `" . LENTELES_PRIESAGA . "users`.`forum_temos`=`" . LENTELES_PRIESAGA . "users`.`forum_temos`+1 ,
								  `" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`=`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`+1 ,
								  `" . LENTELES_PRIESAGA . "users`.`taskai`=`" . LENTELES_PRIESAGA . "users`.`taskai`+1 ,
								  `" . LENTELES_PRIESAGA . "d_temos`.`last_data`= '" . time() . "',
								  `" . LENTELES_PRIESAGA . "d_temos`.`last_nick`=" . escape( $_SESSION[SLAPTAS]['username'] ) . "
								  WHERE `" . LENTELES_PRIESAGA . "users`.nick=" . escape( $_SESSION[SLAPTAS]['username'] ) . " AND `" . LENTELES_PRIESAGA . "d_temos`.`id`=" . escape( $sid ) . "" ) or die( mysqli_error($prisijungimas_prie_mysql) );
					$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "d_zinute` (`tid`, `sid`, `nick`, `zinute`, `laikas`) VALUES (" . escape( $sid ) . ", (SELECT max(id) FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE  autorius=" . escape( $_SESSION[SLAPTAS]['username'] ) . "), " . escape( $uid ) . ", " . escape( $zinute ) . ", '" . time() . "')" );
					if ( !$result ) {
						$error .= "<b> " . mysqli_error($prisijungimas_prie_mysql) . "</b>";
					}
					if ( !isset( $error ) ) {
						redirect( url( "?id," . $url['id'] . ";s," . $sid . ";t," . $inf['id'] ) );
					}
				}
			} else {
				klaida( $lang['system']['error'], $error );
			}
			unset( $uid, $pavadinimas, $zinute, $error, $result, $inf );
		}
echo "<script type=\"text/javascript\">$(document).ready(function() {
        $('.perveiza').click(function() {
          $.post('javascript/forum/preview.php', {'msg':$('textarea#msg').val()}, function(data) {
             $(\"#perveiza\").empty().append($(data));
             }, \"text\");
        });
     });
  </script>";
		$bla   = new forma();
		$forma = array(
			"Form"                      => array(
				"action" => "",
				"method" => "post",
				"name"   => "post_msg" ),

			"    "                      => array(
				"type"  => "string",
				"value" => "<div id='perveiza'></div>" ),

			$lang['forum']['topicname'] => array(
				"type"  => "text",
				"class" => "input",
				"name"  => "post_pav" ),

			" "                         => array(
				"type"  => "string",
				"value" => bbs( 'post_msg' ) ),

			$lang['forum']['message']   => array(
				"type"  => "textarea",
				"rows"  => "8",
				"value" => ( ( !empty( $extra ) ) ? input( $extra ) : '' ),
				"name"  => "post_msg",
				"class" => "input",
				"id"    => "msg" ),

			"  "                        => array(
				"type"  => "string",
				"value" => bbk( 'post_msg' ) ),

			""                          => array(
				"type" => "string",
				"value"=> "<input type=\"button\" class=\"perveiza\" value=\"{$lang['forum']['perview']}\" />
				<input type=\"submit\" class=\"submit\" value=\"{$lang['forum']['submit']}\" />" )
		);

		addtotitle( $lang['forum']['newtopic'] );
		lentele( $lang['forum']['newtopic'], $bla->form( $forma ) );
	}
}

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

//subkategorijos
if (isset($url['s']) && isnum($url['s']) && $url['s'] > 0) {
	$sid = (int)$url['s'];
} else {
	$sid = 0;
}
//temos
if (isset($url['t']) && isnum($url['t']) && $url['t'] > 0) {
	$tid = (int)$url['t'];
} else {
	$tid = 0;
}
//veiksmai
if (isset($url['a']) && isnum($url['a']) && $url['a'] > 0) {
	$aid = (int)$url['a'];
} else {
	$aid = 0;
}
//edit
if (isset($url['e']) && isnum($url['e']) && $url['e'] > 0) {
	$eid = (int)$url['e'];
} else {
	$eid = 0;
}
//puslapiai
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$pid = (int)$url['p'];
} else {
	$pid = 0;
}
//trinti msg
if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) {
	$did = (int)$url['d'];
} else {
	$did = 0;
}
//trint tema
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$kid = (int)$url['k'];
} else {
	$kid = 0;
}
// Uzrakinamos temos ID
if (isset($url['l']) && isnum($url['l']) && $url['l'] > 0) {
	$lid = (int)$url['l'];
} else {
	$lid = 0;
}
//redagavimo
if (isset($url['r']) && isnum($url['r']) && $url['r'] > 0) {
	$rid = (int)$url['r'];
} else {
	$rid = 0;
}
//citatos
if (isset($url['q']) && isnum($url['q']) && $url['q'] > 0) {
	$qid = (int)$url['q'];
} else {
	$qid = 0;
}
include_once ("priedai/class.php");
//kur tu?
$kur = mysql_query1("SELECT pav, (SELECT pav from " . LENTELES_PRIESAGA . "d_straipsniai Where id=$tid AND `lang` = ".escape(lang()).")AS tema,(SELECT count(id) from " . LENTELES_PRIESAGA . "d_zinute Where sid=$tid AND tid=$sid  AND `lang` = ".escape(lang()).")AS zinute,(SELECT count(id) from " . LENTELES_PRIESAGA . "d_zinute Where tid=$sid AND `lang` = ".escape(lang()).")AS subzinute,(SELECT count(id) from " . LENTELES_PRIESAGA . "d_straipsniai Where tid=$sid AND `lang` = ".escape(lang()).")AS temos FROM " . LENTELES_PRIESAGA . "d_temos WHERE id=$sid AND `lang` = ".escape(lang())." limit 1",120);
//Sausainiai naujiems fiksuoti
if ($sid > 0) {
	setcookie("sub_$sid", $kur['subzinute'], time() + (60 * 60 * 24 * 365));
	$_COOKIE["sub_$sid"] = $kur['subzinute'];
}
if ($tid > 0) {
	setcookie("nauji_$tid", $kur['zinute'], time() + (60 * 60 * 24 * 365));
	$_COOKIE["nauji_$tid"] = $kur['zinute'];
}
//print_r($_COOKIE);

$tema = "";
$sub = "";
if (isset($kur['pav']) && !empty($kur['pav'])) {
	$sub = " > <a href='".url("?id," . $url['id'] . ";s," . $sid ). "'>" . $kur['pav'] . "</a> (" . $kur['temos'] . ")";
	if (!empty($kur['tema'])) {
		$tema = " > <a href='".url("?id," . $url['id'] . ";s," . $sid . ";t,$tid'>" . $kur['tema'] ). "</a> (" . $kur['zinute'] . ")";
	}
	lentele($lang['forum']['forum'], "<a href='".url("?id," . $url['id']) . "'>{$lang['forum']['forum']}</a>" . $sub . $tema);
}

//kategoriju sarasas
if ($sid == 0 && $aid == 0 && $kid == 0 && $lid == 0 && $rid == 0) {
//Didelė užklausa į kategorijas/subkategorijas :P gera ką?
	$sqlis = mysql_query1("SELECT
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
	WHERE  `" . LENTELES_PRIESAGA . "d_forumai`.`lang` = ".escape(lang())."
	ORDER by `" . LENTELES_PRIESAGA . "d_forumai`.`place` ASC, `" . LENTELES_PRIESAGA . "d_temos`.`place` ASC",60);
	//$info = array();
	if (sizeof($sqlis) > 0) {
		foreach ($sqlis as $kat) {
		//þinuèiø kategorijoj
			$zinutes = (int)$kat['zinutes'];
			//temø kategorijoj
			$temos = (int)$kat['temos'];
			//nustatom ar yra naujø praneðimø
			if ((!isset($_COOKIE['sub_' . $kat['temid']]) && (int)$kat['last_data'] > 0 && $zinutes > 0) || (isset($_COOKIE['sub_' . $kat['temid']]) && (int)$_COOKIE['sub_' . $kat['temid']] < $zinutes)) {
				$extra = "<img src='images/forum/folder_new.gif' alt='new' />";
			} else {
				$extra = "<img src='images/forum/folder.gif' alt='{$lang['forum']['topic']}' />";
			}
			//subkategorijø atvaizdavimo formatas
			$info[$kat['katid']][] = array("#" => $extra, "{$lang['forum']['forum']}" => "<div ><a href='".url("?id," . $url['id'] . ";s," . $kat['temid'] ). "'>" . $kat['pav'] . "</a> <i style='font-size:9px;width:auto;display:block;'>" . $kat['aprasymas'] . "</i></div>", "{$lang['forum']['topics']}" => $temos, "{$lang['forum']['replies']}" => $zinutes, "{$lang['forum']['lastpost']}" => (($zinutes>0)? $kat['last_nick'] . ' - ' . (($kat['last_data'] == '0000000000') ? '' : kada(date('Y-m-d H:i:s ', $kat['last_data']))):'-'));
			$blai = new Table();
			$subai[$kat['katid']] = $blai->render($info[$kat['katid']]);
			$kateg[$kat['katid']] = $kat['kategorija'];
		}
		//atvaizduojam kategorijas subkategorijom
		foreach ($kateg as $t => $name) {
		//$lang['forum']['nosubcat']
			lentele($name, $subai[$t]);
		}

	}else {
		klaida($lang['system']['warning'],$lang['system']['nocategories']);
	}
}

//temu sarasas
if ($sid > 0 && $tid == 0 && $aid == 0 && $kid == 0 && $lid == 0 && $rid == 0) {
	if (isset($_SESSION['username'])) {
		echo "<br /><a href='" . url("a,1") . "'><img src='images/forum/post.gif' border=0 alt='{$lang['forum']['newpost']}'/></a><br/><br/>";
	}
	$limit = 20;
	$tem = mysql_query1("
	SELECT " . LENTELES_PRIESAGA . "d_straipsniai.* ,
	" . LENTELES_PRIESAGA . "d_straipsniai.id as strid ,
	(SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=" . escape($sid) . " AND `sid`=strid AND `lang` = ".escape(lang()).") AS viso
	from " . LENTELES_PRIESAGA . "d_straipsniai 
	WHERE " . LENTELES_PRIESAGA . "d_straipsniai.tid=" . escape($sid) . " AND `" . LENTELES_PRIESAGA . "d_straipsniai`.`lang` = ".escape(lang())." ORDER by " . LENTELES_PRIESAGA . "d_straipsniai.sticky DESC, " . LENTELES_PRIESAGA . "d_straipsniai.last_data DESC LIMIT " . $pid . ", " . $limit . "",120);
	if (sizeof($tem) > 0) {
		foreach ($tem as $temos) {
		//$tsql = mysql_fetch_assoc(mysql_query1("SELECT count(id) AS viso FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=" . escape($sid) . " AND `sid`=" . escape($temos['id']) . ""));
			$zinutes = $temos['viso'];
			$limit = 20;
			$viso = $kur['temos'];

			if ($temos['uzrakinta'] == 'taip') {
				$extra = "<img src='images/forum/locked.png' alt='{$lang['forum']['locked']}' />";
			} elseif ((!isset($_COOKIE['nauji_' . $temos['id']]) && (int)$temos['last_data'] != '0000000000' && $zinutes > 0) || $_COOKIE['nauji_' . $temos['id'] . ''] < $zinutes) {
				$extra = "<img src='images/forum/theme_new.png' alt='new' />";
			} else {

				$extra = "<img src='images/forum/theme.png' alt='{$lang['forum']['topic']}' />";
			}
			if ($temos['sticky'] == '1') {
				$sticky = "<img src='images/forum/sticky.gif' alt='{$lang['forum']['sticky']}' />";
			} else {
				$sticky = "";
			}

			$info[] = array("#" => $extra . $sticky, "{$lang['forum']['topic']}" => "<div style='width:auto;'><a href='".url("?id," . $url['id'] . ";s," . $sid . ";t," . $temos['id'] ). "' style='display:block'>" . $temos['pav'] . "</a></div>", "{$lang['forum']['replies']}" => $zinutes, "{$lang['forum']['lastpost']}" =>(($zinutes>0)?$temos['last_nick'] . ' - ' . (($temos['last_data'] == '0000000000') ? '' : '<a href="'.url('?id,'.$_GET['id'].';s,'.$_GET['s'].';t,'.$temos['id'].';p,'.((int)($zinutes/15-0.1)*15)).'#end">'.kada(date('Y-m-d H:i:s ', $temos['last_data']))).'</a>':'-'));//' . naujas($row['last_data']) . '

		}

		$bla = new Table();
		$temos = $bla->render($info);

	} else {
		$temos = $lang['forum']['notopics'];
	}
	lentele($lang['forum']['topics'], $temos);
	if (isset($viso) && $viso > 20) {
		lentele($lang['system']['pages'], puslapiai($pid, $limit, $viso, 10));
	}
}
//tema
if ($tid > 0 && $sid > 0 && $kid == 0 && $lid == 0 && $rid == 0 && $aid == 0) {

//trinam posta
	if (isset($tid) && isset($sid) && isset($did) && $did > 0 && isset($_SESSION['level']) && ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1)) {


	//Cia nesugalvojau kaip visas 3 sujungt :(
		$msql = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `forum_atsakyta`=`forum_atsakyta`-1 , `taskai`=`taskai`-1 WHERE id=(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape($did) . " LIMIT 1);
");
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape($did) . "");


		if ($msql) {
			msg($lang['system']['done'], "{$lang['forum']['msgdeleted']}");
			redirect(url("?id," . $url['id'] . ";t,$tid;s,$sid"), "meta");
		} else {
			klaida($lang['system']['error'], "{$lang['forum']['msgbadid']}");
			redirect(url("?id," . $url['id'] . ";t,$tid;s,$sid"), "meta");
		}
	}

	$tsql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape((int)$sid) . " AND `id`=" . escape((int)$tid) . " AND `lang` = ".escape(lang())." limit 1");
	$straipsnis = $tsql['pav'];
	if (!empty($straipsnis)) {
		if (isset($_SESSION['level']) && ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1)) {
			$f_text = '';

			if ($tsql['uzrakinta'] == "taip") {
				$f_text .= "<a href='".url("?id," . $url['id'] . ";s,$sid;t," . $tid . ";l," . $tid ). "'><img src='images/forum/atrakinti.gif' border=0 class='middle' alt='{$lang['forum']['unlock']}' title='{$lang['forum']['unlock']}'/></a>";
			}
			if ($tsql['uzrakinta'] == "ne") {
				$f_text .= "<a href='".url("?id," . $url['id'] . ";s,$sid;t," . $tid . ";l," . $tid ). "'><img src='images/forum/uzrakinti.gif' border=0 class='middle' alt='{$lang['forum']['lock']}' title='{$lang['forum']['lock']}' /></a>";
			}
			$f_text .= "<a href='".url("?id," . $url['id'] . ";s,$sid;t," . $tid . ";k," . $tid ). "' onclick=\"return confirm('" . $lang['faq']['delete'] . "?')\"><img src='images/forum/trinti.gif' border=0 class='middle' alt='{$lang['admin']['delete']}' title='{$lang['admin']['delete']}'/></a>";
			$f_text .= "<a href='".url("?id," . $url['id'] . ";s,$sid;t," . $tid . ";r," . $tid ). "'><img src='images/forum/redaguoti.png' border=0 class='middle' alt='{$lang['admin']['edit']}'title='{$lang['admin']['edit']}'/></a>";
			lentele($lang['forum']['func'], $f_text);
		}
		$viso = $kur['zinute'];
		$limit = 15;
		$gaunam = mysql_query1("SELECT " . LENTELES_PRIESAGA . "users.nick, " . LENTELES_PRIESAGA . "users.taskai, " . LENTELES_PRIESAGA . "users.levelis, " . LENTELES_PRIESAGA . "users.gim_data, " . LENTELES_PRIESAGA . "users.email, " . LENTELES_PRIESAGA . "users.id, " . LENTELES_PRIESAGA . "users.miestas, " . LENTELES_PRIESAGA . "users.icq, " . LENTELES_PRIESAGA . "users.msn, " . LENTELES_PRIESAGA . "users.skype, " . LENTELES_PRIESAGA . "users.aim, " . LENTELES_PRIESAGA . "users.url, " . LENTELES_PRIESAGA . "users.yahoo, " . LENTELES_PRIESAGA . "users.forum_atsakyta, " . LENTELES_PRIESAGA . "d_zinute.id AS `zid`, " . LENTELES_PRIESAGA . "d_zinute.nick AS `nikas`, `tid`, `sid`,`zinute`,`laikas` FROM " . LENTELES_PRIESAGA . "users INNER JOIN `" . LENTELES_PRIESAGA . "d_zinute` ON " .
			 LENTELES_PRIESAGA . "d_zinute.nick=" . LENTELES_PRIESAGA . "users.id WHERE `sid`='" . $tid . "' ORDER BY laikas ASC LIMIT $pid,$limit");

		$a = 0;
		$turinys = '';
		unset($row);
		foreach ($gaunam as $row) {
			if ($pid == '0') {
				$a++;
			} else {
				$a = 2;
			}
			if (isset($conf['level'][$row['levelis']]['pavadinimas'])) {
				$grupe = $conf['level'][$row['levelis']]['pavadinimas'];
			} else {
				$grupe = '--';
			}

			$extra = "";
			$tool = "";

			if (!empty($row['msn'])) {
				$extra .= "<a href='http://members.msn.com/" . urlencode($row['msn']) . "' target='_blank'><img src='images/forum/icon_msnm.gif' border=0 alt='msn' /></a>  ";
			}

			if (!empty($row['skype'])) {
				$extra .= "<a href='skype:" . urlencode($row['skype']) . "?chat' target='_blank'><img src='http://mystatus.skype.com/smallicon/" . urlencode($row['skype']) . "' width='16px' hight='16' border=0 alt='skype' /></a>  ";
			}
			if (!empty($row['url'])) {
				$extra .= "<a href='" . $row['url'] . "' target='_blank'><img src='images/forum/icon_www.gif' border=0 alt='www' /></a>  ";
			}

			if (isset($_SESSION['id']) && $row['nikas'] == $_SESSION['id'] || isset($_SESSION['level']) && (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {
				$tool .= "<a href='" . url("e," . $row['zid'] . "") . "#end'><img src='images/forum/icon_edit.gif' border='0' alt='edit'/></a>";
				if ($a != 1) {
					if (isset($_SESSION['level']) && (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {
						$tool .= "<a href='".url("?id," . $url['id'] . ";t," . $tid . ";s," . $sid . ";d," . $row['zid'] ). "'><img src='images/forum/icon_trinti.gif' border='0' alt='trinti'/></a>";
					}
				}
			}


			$turinys .= ' <fieldset><table width="100%" class="table" id="' . $row['zid'] . '" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <th width="20%" class="th" nowrap="nowrap" align="center">' . user($row['nick'], $row['id'], $row['levelis'], $grupe . '<br />' . $lang['forum']['points'] . ': ' . $row['taskai'] . '<br/>' . $lang['forum']['messages'] . ': ' . $row['forum_atsakyta']) . '</th>
    <th width="80%" class="th" nowrap="nowrap" align="right">' . $lang['forum']['date'] . ': ' . (($row['laikas'] == '0000000000') ? '---' : date('Y-m-d H:i:s ', $row['laikas'])) . '</th>
  </tr>
  <tr class="tr">
    <td class="td" height="93" align="center" valign="middle">' . avatar($row['email']) . '</td>
    <td class="td" valign="top"><br />' . bbcode(wrap($row['zinute'],60)) . '</td>
  </tr>
  <tr class="tr2">
    <td class="td2" ><a href="javascript:window.scroll(0,0)"><font size="1">▲</font></a></td>
    <td class="td2">' . $extra . '<small style="float:right;">' . $tool . ' '.($_SESSION['level']>0?'<a href="' . url("q," . $row['zid'] . "") . '"><img src="images/forum/atsakyti.gif" border="0" alt="re"></a>':'').'</small></td>
  </tr>
</table> </fieldset>';

			unset($extra);
		}
		addtotitle($straipsnis);
		lentele($straipsnis, puslapiai($pid, $limit, $viso, 10) . $turinys . puslapiai($pid, $limit, $viso, 10) . "<a name='end' id='end'></a>");

		$tikrinam = mysql_query1("SELECT `uzrakinta` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `id`=" . escape($tid) . " AND `tid`=" . escape($sid) . " limit 1");


		if (isset($_SESSION['id']) && $kid == 0 && $lid == 0 && $rid == 0 && $eid > 0) {

			$extra = '';
			if (!empty($_POST['msg']) && $_POST['action'] == 'f_update') {
				if ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {
					mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_zinute` SET `zinute`=" . escape($_POST['msg'] . "\n[sm][i]{$lang['forum']['edited_by']}: " . $_SESSION['username'] . " " . date('Y-m-d H:i:s ', time()) . "[/i][/sm]") . " WHERE `id`=" . escape($eid));
				} else {
					mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_zinute` SET `zinute`=" . escape($_POST['msg'] . "\n[sm][i]{$lang['forum']['edited_by']}: " . $_SESSION['username'] . " " . date('Y-m-d H:i:s ', time()) . "[/i][/sm]") . " WHERE `id`=" . escape($eid) . " AND `nick`=" . escape($_SESSION['id']));
				}
				redirect(url("?id," . $url['id'] . ";s,$sid;t,$tid;p,{$_GET['p']}"));
				//redirect($_SERVER['HTTP_REFERAL']);
			} else {
				if ((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {
					$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape($eid) . "  LIMIT 1";
				} else {
					$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `id`=" . escape($eid) . " AND `nick`='" . escape($_SESSION['id']) . "' LIMIT 1";
				}
				$sql = mysql_query1($sql,15);
				$extra = $sql['zinute'];
			}
		}
		//  Siunciam zinute
		if (isset($_POST['action']) && $_POST['action'] == 'f_send' && isset($_POST['msg']) && $tikrinam['uzrakinta'] == "ne") {
			if (!isset($_SESSION['username'])) {
				redirect(url("?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}"));
			}
			if (strlen(str_replace(" ", "", $_POST['msg'])) > 0) {
				$zinute = $_POST['msg'];
				if ($tid == 0) {
					redirect(url("?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}"));
				}
				if ($sid == 0) {
					redirect(url("?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}"));
				}
				if ($tikrinam['uzrakinta'] == "taip") {
					redirect(url("?id," . $url['id'] . ";t," . $tid . ";s," . $sid ));
				} else {

					if (isset($_POST['post_uid']) && $_POST['post_uid'] > 0) {
						$uid = (int)$_POST['post_uid'];
					} else {
						$uid = $_SESSION['id'];
					}


					//is 4 dvi uzklausos :), nemoku sujungt update ir insert :(
					mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_temos`,`" . LENTELES_PRIESAGA . "users`,`" . LENTELES_PRIESAGA . "d_straipsniai`
SET 
`" . LENTELES_PRIESAGA . "d_temos`.`last_data`= '" . time() . "', 
`" . LENTELES_PRIESAGA . "d_temos`.`last_nick`=" . escape($_SESSION['username']) . ", 
`" . LENTELES_PRIESAGA . "d_straipsniai`.`last_data`= '" . time() . "', `" . LENTELES_PRIESAGA . "d_straipsniai`.`last_nick`=" . escape($_SESSION['username']) . ",
`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`=`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`+1 , 
`" . LENTELES_PRIESAGA . "users`.`taskai`=`" . LENTELES_PRIESAGA . "users`.`taskai`+1 
WHERE `" . LENTELES_PRIESAGA . "users`.`nick`=" . escape($_SESSION['username']) . " 
AND `" . LENTELES_PRIESAGA . "d_straipsniai`.`id`=" . escape($tid) . " AND `" . LENTELES_PRIESAGA . "d_temos`.`id`=" . escape($sid) . "
");
					mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "d_zinute` (`tid`, `sid`, `nick`, `zinute`, `laikas`) VALUES (" . escape($sid) . ", " . escape($tid) . ", " . escape($uid) . ", " . escape($zinute) . ", '" . time() . "')");
					redirect(url("?id,{$_GET['id']};s,{$_GET['s']};t,{$_GET['t']};p,".((int)($kur['zinute']/15)*15)."#end"));

					unset($zinute, $uid, $f_atsakyta);
				}
			} else {
				klaida($lang['system']['warning'], $lang['forum']['message_short']);
			}
		}


		if (isset($_SESSION['username']) && $tikrinam['uzrakinta'] == "ne") {
			$citata = "";
			if ($qid > 0) {

				$cit = mysql_query1("SELECT *,nick as nikas,(SELECT nick from " . LENTELES_PRIESAGA . "users where id=nikas)as nickas from " . LENTELES_PRIESAGA . "d_zinute where id='" . (int)$qid . "' limit 1",30);

				if (isset($cit['zinute'])) {
					$citata = "[quote=(" . date('Y-m-d H:i:s', $cit['laikas']) . ") " . $cit['nickas'] . "]" . $cit['zinute'] . "\n[/quote]";
				}


			}
						echo "<script type=\"text/javascript\">$(document).ready(function() {
  $('.perveiza').click(function() {
      $.post('javascript/forum/preview.php', {'msg':$('textarea#msg').val()}, function(data) {
          $(\"#perveiza\").empty().append($(data));
      }, \"text\");
  });
});</script>
";
			$bla = new forma();
			$forma = array(
				 "Form" => array("action" => "", "method" => "post", "name" => "msg"),
				 "    " => array("type" => "string", "value" => "<div id='perveiza'></div>"),
				 " " => array("type" => "string", "value" => bbs('msg')),
				 $lang['forum']['message'] => array("type" => "textarea", "rows" => "8", "value" => ((!empty($extra)) ? input($extra) : $citata), "name" => "msg", "class" => "input", "id"=>"msg"),
				 "  " => array("type" => "string", "value" => bbk('msg')),
				 "       \n" => array("type" => "button","class"=>"perveiza", "value" => "{$lang['forum']['perview']}"),
				 "   " => array("type" => "submit", "value" => ((!empty($extra)) ? "{$lang['admin']['edit']}" : "{$lang['forum']['submit']}")),
				 "     " => array("type" => "hidden", "name" => "action", "value" => ((!empty($extra)) ? "f_update" : "f_send"))
			);
			hide($lang['forum']['newpost'], $bla->form($forma), (!empty($extra)) ? false : true);
		}

	}
}
// Uzrakinam/Atrakinam tema
elseif ((int)$lid != 0 && $kid == 0 && $rid == 0) {
	if (isset($_SESSION['level']) && (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {

		$sql = mysql_query1("SELECT `uzrakinta` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `tid`=" . escape($sid) . " AND `id`=" . escape($lid) . " limit 1");
		if (isset($sql['uzrakinta'])) {
			$lock = $sql['uzrakinta'];

			if ($lock == "ne") {
				$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `uzrakinta`='taip' WHERE `id`=" . escape($lid) . "");

			} elseif ($lock == "taip") {
				$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `uzrakinta`='ne' WHERE `id`=" . escape($lid) . "");
				;
			}

			redirect(url("?id," . $url['id'] . ";s," . $sid . ";t,$tid"));


		}

	}
}

// Trinti tema
elseif ((int)$kid && (int)$kid && (int)$kid > 0) {
	if (isset($_SESSION['level']) && (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) {

	//atimam autoriui tema
		mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `forum_temos`=`forum_temos`-1 WHERE id=(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape($kid) . " ORDER BY laikas ASC LIMIT 1) LIMIT 1");


		$gis = mysql_query1("SELECT nick FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape($kid) . "");
		foreach ($gis as $stulpelis) {
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` set `taskai`=`taskai`-1,`forum_atsakyta`=`forum_atsakyta`- 1 where id=" . escape($stulpelis['nick']) . "");
		}
		//istrinam zinuters ir tema

		$result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `id`=" . escape($kid) . "");
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `sid`=" . escape($kid) . "");
		if ($result) {
			redirect(url("?id," . $url['id'] . ";s," . $sid));
		}

	}
}
//redaguojam tema
elseif (((isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('frm', unserialize($_SESSION['mod']))) || $_SESSION['level'] == 1) && (int)$rid != 0) {
	unset($tsql);
	$tsql = mysql_query1("SELECT pav,sticky FROM " . LENTELES_PRIESAGA . "d_straipsniai WHERE `id`=" . escape((int)$rid) . " limit 1",15);
	if (isset($tsql['pav'])) {

		$bla = new forma();
		$form = array(
			 "Form" => array("action" => "", "method" => "post", "name" => "rename"),
			 "{$lang['admin']['forum_cangeto']}:" => array("type" => "text", "class" => "input", "value" => $tsql['pav'], "name" => "name"),
			 "{$lang['forum']['sticky']}?:" => array("type" => "select", "class" => "select", "value" => array("1" => $lang['admin']['yes'], "0" => $lang['admin']['no']), "name" => "sticky", "class" => "select", "selected" => $tsql['sticky']),
			 "  " => array("type" => "submit", "name" => "sub", "value" => "{$lang['admin']['edit']}")
		);
		lentele($tsql['pav'], $bla->form($form));

		if (isset($_POST['name'])) {

			$new = input($_POST['name']);
			$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_straipsniai` SET `pav`='" . $new . "', `sticky`='" . (int)$_POST['sticky'] . "' WHERE `id`='" . escape((int)$rid) . "'");
			redirect(url("?id,{$url['id']};s,$sid;t,$rid"));
		}
	}
}

//temos kurimas
elseif ($aid == 1 && $kid == 0 && $lid == 0 && $rid == 0) {
	if (isset($_POST['post_msg'])) {
		if (!isset($_SESSION['username'])) {
			redirect(url("?id,{$conf['puslapiai'][$conf['pirminis'].'.php']['id']}"));
		}
		if (isset($_POST['post_uid']) && $_POST['post_uid'] > 0) {
			$uid = (int)$_POST['post_uid'];
		} else {
			$uid = $_SESSION['id'];
		}
		$pavadinimas = input($_POST['post_pav']);
		$zinute = $_POST['post_msg'];
		$error = "";
		if (empty($pavadinimas)) {
			$error .= "{$lang['forum']['topicname?']}<br/>";
		}
		if (empty($zinute) || strlen(str_replace(" ", "", $zinute)) < 1) {
			$error .= $lang['forum']['message?'];
		}
		$result = mysql_query1("SELECT `id` FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`=" . escape($sid) . " AND `lang` = ".escape(lang())." limit 1");
		if ($result == 0) {
			$error .= "{$lang['forum']['badurl']}.<br/>";
		}
		unset($result);
		if (strlen($error) < 1) {
			unset($error);
			$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "d_straipsniai` (`tid`, `pav`, `autorius`,`last_data`,`last_nick`,`lang`) VALUES(" . escape($sid) . ", " . escape($pavadinimas) . ", " . escape($_SESSION['username']) . ", '" . time() . "', " . escape($_SESSION['username']) . ", ".escape(lang()).")");
			if (!$result) {
				$error .= "<b> " . mysql_error() . "</b>.";
			}
			if (!isset($error)) {
				unset($result);
				//`pav`=" . escape($pavadinimas) . " AND
				$inf = mysql_query1("SELECT max(id) AS `id` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE  autorius=" . escape($_SESSION['username']) . " limit 1");
				
				mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users`,`" . LENTELES_PRIESAGA . "d_temos`
 SET 
`" . LENTELES_PRIESAGA . "users`.`forum_temos`=`" . LENTELES_PRIESAGA . "users`.`forum_temos`+1 ,
`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`=`" . LENTELES_PRIESAGA . "users`.`forum_atsakyta`+1 ,
`" . LENTELES_PRIESAGA . "users`.`taskai`=`" . LENTELES_PRIESAGA . "users`.`taskai`+1 ,
`" . LENTELES_PRIESAGA . "d_temos`.`last_data`= '" . time() . "', 
`" . LENTELES_PRIESAGA . "d_temos`.`last_nick`=" . escape($_SESSION['username']) . "
WHERE `" . LENTELES_PRIESAGA . "users`.nick=" . escape($_SESSION['username']) . " AND `" . LENTELES_PRIESAGA . "d_temos`.`id`=" . escape($sid) . "") or die(mysql_error());
				$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "d_zinute` (`tid`, `sid`, `nick`, `zinute`, `laikas`) VALUES (" . escape($sid) . ", (SELECT max(id) FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE  autorius=" . escape($_SESSION['username']) . "), " . escape($uid) . ", " . escape($zinute) . ", '" . time() . "')");
				if (!$result) {
					$error .= "<b> " . mysql_error() . "</b>";
				}
				if (!isset($error)) {
					redirect(url("?id," . $url['id'] . ";s," . $sid . ";t," . $inf['id']));
				}
			}
		} else {
			klaida($lang['system']['error'], $error);
		}
		unset($uid, $pavadinimas, $zinute, $error, $result, $inf);
	}
		echo "<script type=\"text/javascript\">$(document).ready(function() {
  $('.perveiza').click(function() {
      $.post('javascript/forum/preview.php', {'msg':$('textarea#msg').val()}, function(data) {
          $(\"#perveiza\").empty().append($(data));
      }, \"text\");
  });
});</script>
";
	$bla = new forma();
	$forma = array(
		 "Form" => array("action" => "", "method" => "post", "name" => "post_msg"),
		 "    " => array("type" => "string", "value" => "<div id='perveiza'></div>"),
		 $lang['forum']['topicname'] => array("type" => "text", "class" => "input", "name" => "post_pav"),
		 		 " " => array("type" => "string", "value" => bbs('post_msg')),
		 		 $lang['forum']['message'] => array("type" => "textarea", "rows" => "8", "value" => ((!empty($extra)) ? input($extra) : ''), "name" => "post_msg", "class" => "input", "id"=>"msg"),
		 "  " => array("type" => "string", "value" => bbk('post_msg')),
		 "       \n" => array("type" => "button","class"=>"perveiza", "value" => "{$lang['forum']['perview']}"),
		 "   " => array("type" => "submit", "value" => $lang['forum']['submit'])
	);
	addtotitle($lang['forum']['newtopic']);
	lentele($lang['forum']['newtopic'], $bla->form($forma));
}

?>
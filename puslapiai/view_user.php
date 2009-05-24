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

if (isset($url['m']) && $url['m'] > 0) {
	include_once ("priedai/rating_functions.php");
	if (isset($_SESSION['id']) && $_SESSION['id'] != $url['m']) {
		$vote = pullRating($url['m'], true, true, true);
	} else {
		$vote = pullRating($url['m'], true, true, true, 'novote');
	}

	$sql = mysql_query1("SELECT *, INET_NTOA(ip) AS ip FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`='" . $url['m'] . "' LIMIT 1");
	if (isset($sql['nick'])) {
		//$sql2 = mysql_query1("SELECT * FROM " . LENTELES_PRIESAGA . "kas_prisijunges WHERE user='" . $sql['nick'] . "' AND `timestamp`>'" . $timeout . "' LIMIT 1");
		if (isset($user_online[(int)$url['m']])&&$user_online[(int)$url['m']]==true) {
			$prisijunges = 'images/icons/status_online.png';
			$pr = $lang['user']['user_online'];
		} else {
			$prisijunges = 'images/icons/status_offline.png';
			$pr = $lang['user']['user_ofline'];
		}
		if (isset($conf['level'][$sql['levelis']]['pavadinimas'])) {
			$grupe = $conf['level'][$sql['levelis']]['pavadinimas'];
		} else {
			$grupe = '-';
		}
		//Profilio rodymas
		if ($_SESSION['level'] == 1) {
			$admin = '<a href="?id,999;a,'.$admin_pagesid['vartotojai'].';r,' . $sql['id'] . '" title="' . $lang['admin']['edit'] . '"><img src="images/icons/pencil.png" class="middle" border="0" alt="edit" /></a> <a href="?id,999;a,'.$admin_pagesid['vartotojai'].';d,' . $sql['id'] . '" onclick="if (!confirm("' . $lang['admin']['delete'] . '")) return false;\" title="' . $lang['admin']['delete'] . '"><img src="images/icons/cross.png" class="middle" border="0" alt="delete" /></a>  <a title="' . $lang['admin']['badip'] . '" href="?id,999;a,'.$admin_pagesid['banai'].';b,1;ip,' . $sql['ip'] . '"><img src="images/icons/delete.png" class="middle" border="0" alt="ban" /></a>';
		} else {
			$admin = '';
		}
		$text = '<table align="center" border="0" cellpadding="0" cellspacing="1" class="table" width="100%">
			<tr >
				<td height="14" class="th" width="140"><b>' . $sql['nick'] . '</b> <img src="' . $prisijunges . '" title="' . $pr . '" style="vertical-align:middle" alt="' . $pr . '" /></td>
				<td height="14" class="th" width="50%">' . $lang['user']['user_info'] . '</td>
				<td height="14" class="th" width="50%">' . $lang['user']['user_contacts'] . '</td>
			</tr>
			<tr >
				<td height="14" class="td" width="140">
          <center>
            ' . avatar($sql['email'], '80') . '<br />
            <small>' . $grupe . '<br />' . $admin . '</small>
          </center>
        </td>
				<td height="58" valign="top" class="td" width="190">
          <small>
            <b>' . $lang['user']['user_name'] . ':</b> ' . $sql['vardas'] . '<br />
            <b>' . $lang['user']['user_secondname'] . ':</b> ' . $sql['pavarde'] . '<br />
            <b>' . $lang['user']['user_age'] . ':</b> ' . (amzius($sql['gim_data']) > 0 ? amzius($sql['gim_data']) : "-") . '<br />
            <b>' . $lang['user']['user_city'] . ':</b> ' . $sql['miestas'] . '<br />
            <b>' . $lang['user']['user_registered'] . ': </b> ' . date('Y-m-d H:i:s ', $sql['reg_data']) . '<br />
            <b>' . $lang['user']['user_lastvisit'] . ': </b> ' . kada(date('Y-m-d H:i:s ', $sql['login_data'])) . '<br />
            <b>' . $lang['user']['user_points'] . ': </b> ' . $sql['taskai'] . '<br />
          </small>
        </td>
				<td height="58" valign="top" class="td" width="140">
          <small>
            <b>ICQ:</b> ' . $sql['icq'] . '<br />
            <b>MSN:</b> ' . $sql['msn'] . '<br />
            <b>Skype:</b> ' . $sql['skype'] . '<br />
            <b>Yahoo:</b> ' . $sql['yahoo'] . '<br />
            <b>AIM:</b> ' . $sql['aim'] . '<br />
            <b>WWW:</b> ' . linkas($sql['url']) . '
          </small>
				</td>
			</tr>
			<tr>
				<td  class="th" height="14" width="140">' . $lang['forum']['forum'] . '</td>
				<td class="th" height="14" colspan="2"  width="280">' . $lang['user']['user_signature'] . '</td>
			</tr>
			<tr>
				<td class="td" rowspan="3" height="87" valign="top" width="140"><small>
					<b>' . $lang['forum']['topic'] . ':</b> ' . $sql['forum_temos'] . '<br />
					<b>' . $lang['forum']['messages'] . ':</b>	' . $sql['forum_atsakyta'] . '<br /></small>
        </td>
				<td class="td" colspan="2" height="18" width="280">' . bbcode($sql['parasas']) . '</td>
			</tr>		
				<tr>
				<td class="th" height="14" colspan="2" >' . $lang['user']['user_rate'] . '</td>
			</tr>
      <tr>
        <td class="td" colspan="2"> 
          ' . $vote . '
        </td>
      </tr>
		</table>
';
		// Jei kitas vartotojas perziuri kita vartotoja BET ne SAVE
		if (isset($_SESSION['id']) && $_SESSION['id'] != $url['m']) {
			if (isset($conf['puslapiai']['pm.php']['id'])) {
				$text .= "
				<center>
				<form name=\"send_pm\" action='?id," . $conf['puslapiai']['pm.php']['id'] . ";n,1;u," . str_replace("=", "", base64_encode($sql['nick'])) . "' method=\"post\">
				<input type=\"submit\" value=\"{$lang['user']['user_pm']}\" />
				</form>
				</center>
			";
			}
		}


		lentele("{$lang['user']['user_profile']}", $text);
		unset($text);


		// Jeigu pereziurima TIK informacija, o vartotojas SAVES NEZIURI per nustatyta puslapi
		//komentarų nereikės
		//if(isset($_SESSION['id'])){	include("priedai/komentarai.php");komentarai($url['m'],true);}
	}
} else {
	klaida($lang['system']['error'], "{$lang['system']['pagenotfounfd']}.");
}

?>
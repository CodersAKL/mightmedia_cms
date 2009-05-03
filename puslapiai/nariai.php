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
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}

include_once ("priedai/class.php");
$limit = 30;
$viso = kiek("users");
//vartotojų sarašas
$sql = mysql_query1("SELECT id, INET_NTOA(ip) AS ip, reg_data, gim_data, login_data, nick, vardas, pavarde, email, levelis FROM `" . LENTELES_PRIESAGA . "users` LIMIT $p, $limit");
$i = 0;

if (mysql_num_rows($sql) > 0) {
	while ($row = mysql_fetch_assoc($sql)) {
		if (isset($conf['level'][$row['levelis']]['pavadinimas'])) {
			$grupe = $conf['level'][$row['levelis']]['pavadinimas'];
		} else {
			$grupe = '-';
		}
		$i++;
		$info[] = array("{$lang['ulist']['username']}" => user($row['nick'], $row['id'], $row['levelis']), "{$lang['ulist']['group']}" => $grupe);
		if (defined("LEVEL") && LEVEL == 1) {
			$info[($i - 1)][$lang['ulist']['email']] = preg_replace("#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "<a href=\"javascript:mailto:mail('\\1','\\2');\">\\1_(at)_\\2</a>", $row['email']);
			$info[($i - 1)][$lang['admin']['action']] = "<a href='?id,999;a,6;r," . $row['id'] . "' title='{$lang['admin']['edit']}'><img src='images/icons/pencil.png' class='middle' border='0'></a> <a href='?id,999;a,6;d," . $row['id'] . "' onclick=\"if (!confirm('{$lang['admin']['delete']}?')) return false;\" title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' class='middle' border='0'></a>  <a href='?id,999;a,11;b,1;ip," . $row['ip'] . "' title='{$lang['admin']['badip']}'><img src='images/icons/delete.png' class='middle' border='0'></a>";
		}
	}
	//nupiesiam adminu lentele
	$bla = new Table();
	lentele("{$lang['ulist']['list']} - $viso", $bla->render($info), false);
	if ($viso > $limit) {
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
	}

}
unset($info, $bla, $i, $sql);

?>
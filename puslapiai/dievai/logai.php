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

if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
	header('location: ?');
}

if (isset($url['o']) && !empty($url['o'])) {
	switch ($url['o']) {
		case "{$lang['admin']['logs_log']}":
			{
				$order = "action";
				break;
			}
		case "#":
			{
				$order = "id";
				break;
			}
		case "{$lang['admin']['logs_user']}":
			{
				$order = "nick";
				break;
			}
		case "IP":
			{
				$order = "ip";
				break;
			}
		default:
			{
				$order = "id";
				break;
			}
	}
} else {
	$order = "id";
}

//trinam irasa
if (isset($url['d']) && isnum($url['d']) && LEVEL == 1) {
	if ($url['d'] == "0" && isset($_POST['ip']) && !empty($_POST['ip']) && $_POST['del_all'] == $lang['admin']['delete'] && isnum($_POST['ip'])) {
		$sql = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "logai` WHERE `ip` = " . escape($_POST['ip']));
		msg($lang['system']['done'], "<b>" . long2ip($_POST['ip']) . "</b> {$lang['admin']['logs_logsdeleted']}.");
		redirect("?id," . $url['id'] . ";a,{$_GET['a']}", "meta");
	} elseif (!empty($url['d'])) {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "logai` WHERE `id` = " . escape($url['d']) . " LIMIT 1;");
		header("location: ?id," . $url['id'] . ";a,{$_GET['a']}");
	}
} elseif (isset($url['v']) && !empty($url['v']) && isnum($url['v'])) {
	$sql = mysql_query1("SELECT id, INET_NTOA(ip) AS ip, action, time FROM `" . LENTELES_PRIESAGA . "logai` WHERE id=" . escape($url['v']) . " LIMIT 1");
	lentele($sql['ip'] . " - " . kada($sql['time']), input($sql['action']));
}
if (!empty($url['t'])) {
	mysql_query1("TRUNCATE TABLE `" . LENTELES_PRIESAGA . "logai`");
	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "logai` (`action` ,`time` ,`ip`) VALUES (" . escape("Administratorius: " . $_SESSION['username'] . " ištrynė logus.") . ", '" . time() . "', INET_ATON(" . escape(getip()) . "))");
	header("location: ?id," . $url['id'] . ";a,{$_GET['a']}");
} else {
	include_once ("priedai/class.php");
	//$sql = mysql_query1("SELECT id, INET_NTOA(ip) AS ip, action, time FROM `logai` ORDER BY $order DESC LIMIT 0 , 100 ");
	if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
		$p = escape(ceil((int)$url['p']));
	} else {
		$p = 0;
	}
	$limit = 35;
	$viso = kiek("logai");

	$sql = mysql_query1("
		SELECT `" . LENTELES_PRIESAGA . "logai`.`id`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) as ip, `" . LENTELES_PRIESAGA . "logai`.`action`, INET_NTOA(`" . LENTELES_PRIESAGA . "logai`.`ip`) AS ip1, `" . LENTELES_PRIESAGA . "logai`.`time`,
		IF(`" . LENTELES_PRIESAGA . "users`.`nick` <> '', `" . LENTELES_PRIESAGA . "users`.`nick`, 'Svečias') AS nick,
		IF(`" . LENTELES_PRIESAGA . "users`.`id` <> '', `" . LENTELES_PRIESAGA . "users`.`id`, '0') AS nick_id,
		IF(`" . LENTELES_PRIESAGA . "users`.`levelis` <> '', `" . LENTELES_PRIESAGA . "users`.`levelis`, '0') AS levelis
		FROM `" . LENTELES_PRIESAGA . "logai` Left Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "logai`.`ip` = `" . LENTELES_PRIESAGA . "users`.`ip`
	ORDER BY $order DESC LIMIT $p, $limit
		");
	$viso = kiek('logai');

	$info = array();

	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			if ($row['nick'] == $lang['system']['guest']) {
				$kas = $lang['system']['guest'];
			} else {
				$kas = user($row['nick'], $row['nick_id'], $row['levelis']);
			}
			$info[] = array( //"Nr:"=>$row['id'],
				"{$lang['admin']['logs_log']}" => "<a href=\"" . url("v," . $row['id'] . "") . "\" title=\"
						<br/>
						{$lang['admin']['logs_date']}: <b>" . date('Y-m-d H:i:s', $row['time']) . "</b><br/>
						IP: <b>" . $row['ip1'] . "</b><hr/>
						{$lang['admin']['logs_log']}: <i>" . wrap1(input($row['action']), 50) . "</i><br/>
						\">" . trimlink(input(strip_tags($row['action'])), 50) . "</a>", //"Veiksmas"=>trimlink(input($row['action']),50),
				//"IP"=>$row['ip1'],
			"{$lang['admin']['logs_user']}" => $kas, //"Kada"=>kada($row['time']),
				"{$lang['admin']['action']}" => "<a href=\"" . url("d," . $row['id'] . "") . "\" title='{$lang['admin']['delete']}'><img src=\"images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a> <a href='?id," . $url['id'] . ";a,{$admin_pagesid['banai']};b,1;ip," . $row['ip'] . "' title='{$lang['admin']['badip']}'><img src=\"images/icons/delete.png\" alt=\"[{$lang['admin']['badip']}]\" border=\"0\" class=\"middle\" /></a>");
		}
		$bla = new Table();
		lentele("{$lang['admin']['logai']} - {$lang['admin']['logs_yourip']}: <font color='red'>" . getip() . "</font>", $bla->render($info));
	} else {
		msg("{$lang['admin']['logs']}", "{$lang['admin']['logs_nologs']}.");
	}
	if ($viso > $limit) {
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
	}
	$sql = mysql_query1("SELECT count(*) as viso, ip, INET_NTOA(ip) AS ip1 FROM `" . LENTELES_PRIESAGA . "logai` GROUP BY ip ORDER BY time DESC");
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			$select[$row['ip']] = $row['ip1'] . " - " . $row['viso'];
		}
		$nustatymai = array("Form" => array("action" => "?id," . $url['id'] . ";a," . $url['a'] . ";d,0", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"), "{$lang['admin']['logs_deletebyip']}:" => array("type" => "select", "value" => $select, "selected" => ip2long($_SERVER['REMOTE_ADDR']), "name" => "ip"), "" => array("type" => "submit", "name" => "del_all", "value" => $lang['admin']['delete']));
		$bla = new forma();
		lentele("{$lang['admin']['logs_deletebyip']}", $bla->form($nustatymai));

		$nustatymai = array("Form" => array("action" => "?id," . $url['id'] . ";a," . $url['a'] . ";t,1", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"), $lang['admin']['logs_clear'] => array("type" => "submit", "name" => "del_all", "value" => "Valyti", "extra" => "onclick=\"return confirm('{$lang['system']['delete_confirm']}')\""));
		$bla = new forma();
		lentele($lang['admin']['logs_clear'], $bla->form($nustatymai));

	}
}
unset($row, $bla, $info, $sql, $select, $viso, $nustatymai, $order);
//unset($_POST);


?>
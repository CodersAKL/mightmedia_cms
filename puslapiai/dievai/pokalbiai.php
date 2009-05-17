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

if (!defined("OK") || !ar_admin(basename(__file__))) {
	header('location: ?');
	exit();
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
$limit = 30;
$viso = kiek('admin_chat');

unset($extra);
if (isset($_POST['admin_chat_send']) && $_POST['admin_chat_send'] == $lang['admin']['send'] && !empty($_POST['admin_chat'])) {
	//printf("<pre>%s</pre>",print_r($_POST,true));
	//Irasom zinute
	if (isset($_POST['pm']) && $_POST['pm'] != 'x') {

		$extra = "[i]{$lang['admin']['globalmessagefor']}:[b]" . $conf['level'][$_POST['pm']]['pavadinimas'] . "[/b][/i]\n---\n";

		//foreach($_POST['pm'] as $key => $val) {
		if ($_POST['pm'] == 0) {
			$extra = "[i]{$lang['admin']['globalmessagefor']}: [b] {$lang['admin']['all']} [/b][/i]\n---\n";

			$sql = mysql_query1("SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users`");
		} else {
			$extra = "[i]{$lang['admin']['globalmessagefor']}:[b]" . $conf['level'][$_POST['pm']]['pavadinimas'] . "[/b][/i]\n---\n";
			$sql = mysql_query1("SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis = '" . $_POST['pm'] . "'");
		}
		if (sizeof($sql) > 0) {
			foreach ($sql as $row) {
				if (kiek("private_msg", "WHERE `to`=" . escape($row['nick']) . "") < 51) {
					mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "private_msg` (`from` , `to` , `title` , `msg` , `date`) VALUES (" . escape($_SESSION['username']) . ", " . escape($row['nick']) . ", '" . $lang['admin']['readme'] . "!', " . escape($_POST['admin_chat']) . ", '" . time() . "')");
				}
			}
			//}
		}
	}


	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "admin_chat` (admin, msg, date) VALUES(" . escape($_SESSION['username']) . "," . escape($extra . $_POST['admin_chat']) . ",'" . time() . "')") or die(mysql_error());
	header("Location: ?id,{$_GET['id']};a,{$_GET['a']}");
}
//trinam zinute
if (isset($url['d']) && !isset($url['a']) && isnum($url['d']) && $url['d'] > 0 && LEVEL == 1) {
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "admin_chat` WHERE id=" . (int)$url['d'] . "");
	header("Location: ?id," . $url['id'] . "");
}
//else { klaida('Atsiprašome', 'Jums nesuteiktos teisės trinti administratorių pranešimus'); }
//redaguojam zinute
if (isset($url['r']) && !isset($url['d']) && !isset($url['a']) && isnum($url['r']) && $url['r'] > 0) {
	if (!isset($_POST['admin_chat_send'])) {
		$extra = mysql_query1("SELECT msg FROM `" . LENTELES_PRIESAGA . "admin_chat` WHERE id=" . escape((int)$url['r']) . " LIMIT 1");
		$extra = $extra['msg'];
	} elseif ($_POST['admin_chat_send'] == $lang['admin']['edit']) {
		mysql_query("UPDATE `" . LENTELES_PRIESAGA . "admin_chat` SET `msg`=" . escape($_POST['admin_chat']) . ",`date` = '" . time() . "' WHERE `admin`=" . escape($_SESSION['username']) . " AND id=" . escape((int)$url['r']) . " LIMIT 1");
		header("Location: ?id," . $url['id'] . "");
	}
}
$lygiai = array_keys($conf['level']);
$teises = "<option value='x'>{$lang['admin']['noone']}";
$teises .= "<option value='0'>{$lang['admin']['all']}";
foreach ($lygiai as $key) {
	$teises .= '<option value=' . $key . '>' . $conf['level'][$key]['pavadinimas'] . '';
}
//klaida("Neveikia","Remontuoju");
$text = "
		<form name=\"admin_chat\" action=\"\" method=\"post\" id=\"chat\">
		<fieldset style='padding:3px'><legend>{$lang['admin']['pmto']}:</legend>
	<select name=\"pm\"   style=\"width:95%;\" >';
                $teises
                </select>
	
		
		</fieldset>
		<center>
		<br/>
		<textarea name=\"admin_chat\" rows=7 cols=\"5\" style='width:80%'>" . ((isset($extra) && isset($url['r'])) ? input($extra) : '') . "</textarea>
		<br/>
		" . bbk("admin_chat") . "
        <br/>
        <input name=\"admin_chat_send\" type=\"submit\" value=\"" . ((isset($url['r']) && isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['send']) . "\">
		</form>
		</center><br/>";
//hide("Rašyti pranešimą",$text,false,false,"admin_chat"); $text = '';
//puslapiavimas
if ($viso > $limit) {
	lentele("{$lang['system']['pages']}:", puslapiai($p, $limit, $viso, 10));
}
//$sql = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."admin_chat` ORDER BY date DESC LIMIT ".escape($p).",".$limit."");
$sql = mysql_query1("SELECT `" . LENTELES_PRIESAGA . "admin_chat`.*, `" . LENTELES_PRIESAGA . "users`.`email` AS `email` FROM `" . LENTELES_PRIESAGA . "admin_chat` Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "admin_chat`.`admin` = `" . LENTELES_PRIESAGA . "users`.`nick` ORDER BY date DESC LIMIT " . escape($p) . "," . $limit);
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		$text .= "
				<div class='title'><em><a href=\"" . url("d," . $row['id'] . "") . "\" >[{$lang['admin']['delete']}]</a> " . (($_SESSION['username'] == $row['admin']) ? "<a href=\"" . url("r," . $row['id'] . "") . "\">[{$lang['admin']['edit']}]</a> " : "") . $row['admin'] . " [" . date('Y-m-d H:i:s ', $row['date']) . "] - " . kada(date('Y-m-d H:i:s ', $row['date'])) . " " . naujas($row['date'], $row['admin']) . "</em></div>
				<blockquote>" . bbcode($row['msg']) . "<br/></blockquote><hr></hr>
		";
	}
}
lentele("{$lang['admin']['admin_chat']}", $text);
if ($viso > $limit) {
	lentele("{$lang['system']['pages']}:", puslapiai($p, $limit, $viso, 10));
}

?>
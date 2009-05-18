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

if (!defined("LEVEL") || !defined("OK")) {
	header("Location: ?");
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
$limit = 50;
$viso = kiek("chat_box");

//jei tai moderatorius
if ($_SESSION['level'] == 1 || (isset($_SESSION['mod']) && strlen($_SESSION['mod']) > 1)) {
	//jei paspaude trinti
	if (isset($url['d']) && !empty($url['d']) && isnum($url['d'])) {
		$id = (int)$url['d'];
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE `id` = " . escape($id) . " LIMIT 1");
		if (mysql_affected_rows() > 0) {
			msg($lang['system']['done'], $lang['sb']['deleted']);
		} else {
			klaida($lang['system']['error'], mysql_error());
		}
		redirect("?id," . $url['id'] . ";p,$p", $_SERVER['HTTP_REFERER']);
	}
	//Jei adminas paspaude redaguoti
	if (isset($url['r']) && !empty($url['r']) && $url['r'] > 0 && isnum($url['r'])) {
		$nick = $_SESSION['username'];
		$nick_id = $_SESSION['id'];
		if (empty($_POST)) {
			$msg = mysql_query1("SELECT `msg` FROM `" . LENTELES_PRIESAGA . "chat_box` WHERE `id`=" . escape(ceil((int)$url['r'])) . " LIMIT 1");
			$msg = '<form name="chat_box_edit" action="" method="post">
					<textarea name="msg" rows="3" cols="25" wrap="on" style="width:265px">' . input($msg['msg']) . '</textarea>
					<br />
					<input type="submit" name="chat_box" value="' . $lang['admin']['edit'] . '" />
					</form>
					';

			$text = $msg;
			lentele($lang['sb']['edit'], $text);
		} elseif (isset($_POST['chat_box']) && $_POST['chat_box'] == $lang['admin']['edit'] && !empty($_POST['msg'])) {
			$msg = trim($_POST['msg']) . "\n[sm] [i] {$lang['sb']['editedby']}: " . $_SESSION['username'] . " [/i] [/sm]";
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "chat_box` SET `msg` = " . escape($msg) . " WHERE `id` =" . escape($url['r']) . " LIMIT 1");
			if (mysql_affected_rows() > 0) {
				msg($lang['system']['done'], $lang['sb']['updated']);
			} else {
				klaida($lang['system']['error'], mysql_error());
			}

		}
	}
}
//Atvaizduojam pranesimus su puslapiavimu - LIMITAS nurodytas virsuje
$sql2 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "chat_box` ORDER BY `time` DESC LIMIT $p, $limit");
if ($viso > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
if (sizeof($sql2) > 0) {

	$text = '';
	$i = 0;

	foreach ($sql2 as $row) {
		$extra = '';
		$i++;
		if ($_SESSION['level'] == 1 || (isset($_SESSION['mod']) && strlen($_SESSION['mod']) > 1)) {
			$extra .= "<a href='" . url("d," . $row['id'] . "") . "'><img src='images/icons/control_delete_small.png' alt='[{$lang['admin']['delete'] }]' title='{$lang['admin']['delete'] }' class='middle' border='0' /></a> <a href='" . url("r," . $row['id'] . "") . "'><img src='images/icons/brightness_small_low.png' alt='[{$lang['admin']['edit'] }]' title='{$lang['admin']['edit'] }' class='middle' border='0' /></a> ";
		} else {
			$extra = '';
		}
		if (is_int($i / 2))
			$tr = "2";
		else
			$tr = "";
		$text .= "<div class=\"tr$tr\"><em><a href=\"?id," . $url['id'] . ";p,$p#" . $row['id'] . "\" name=\"" . $row['id'] . "\" id=\"" . $row['id'] . "\"><img src=\"images/icons/bullet_black.png\" alt=\"#\" class=\"middle\" border=\"0\" /></a> " . user($row['nikas'], $row['niko_id']) . " $extra (" . $row['time'] . ") - " . kada($row['time']) . "</em><br />" . smile(bbchat($row['msg'])) . "</div>";

	}
} else {
	$text = $lang['sb']['empty'];
}
lentele($lang['sb']['archive'], $text);
if ($viso > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
unset($extra, $text);

//PABAIGA - atvaizdavimo


?>
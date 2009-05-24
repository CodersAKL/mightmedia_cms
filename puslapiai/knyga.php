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
$limit = 10;
$viso = kiek("knyga");


//jei tai moderatorius
if (defined("LEVEL") && LEVEL == 1) {
	//jei adminas paspaude trinti
	if (isset($url['d']) && !empty($url['d']) && isnum($url['d'])) {
		$id = (int)$url['d'];
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "knyga` WHERE `id` = " . escape($id) . " LIMIT 1");
		if (mysql_affected_rows() > 0) {
			msg($lang['system']['done'], "{$lang['guestbook']['deleted']}");
		} else {
			klaida($lang['system']['error'], mysql_error());
		}
		redirect("?id," . (int)$_GET['id'] . ";p,$p", 'header');
	}
	//Jei adminas paspaude redaguoti
	if (isset($url['r']) && !empty($url['r']) && $url['r'] > 0 && isnum($url['r'])) {
		$nick = $_SESSION['username'];
		$nick_id = $_SESSION['id'];
		if (empty($_POST)) {
			$msg = mysql_query1("SELECT `msg` FROM `" . LENTELES_PRIESAGA . "knyga` WHERE `id`=" . escape(ceil((int)$url['r'])) . " LIMIT 1");
			$msg = '
			<form name="knyga_edit" action="" method="post">
        <textarea name="msg" rows="3" cols="25" wrap="on" class="input">' . input($msg['msg']) . '</textarea>
        <br />
        <input type="submit" name="knyga" value="' . $lang['admin']['edit'] . '" />
      </form>
      ';

			$text = $msg;
			lentele($lang['guestbook']['Editmessage'], $text);
		} elseif (isset($_POST['knyga']) && $_POST['knyga'] == $lang['admin']['edit'] && !empty($_POST['msg'])) {
			$msg = trim($_POST['msg']) . "\n[sm][i]Redagavo: " . $_SESSION['username'] . "[/i][/sm]";
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "knyga` SET `msg` = " . escape($msg) . " WHERE `id` =" . escape($url['r']) . " LIMIT 1");
			if (mysql_affected_rows() > 0) {
				msg($lang['system']['done'], "{$lang['guestbook']['messageupdated']}");
			} else {
				klaida($lang['system']['error'], mysql_error());
			}
			//redirect("?id,9;p,$p#".escape($url['r'])."","meta");
		}
	}
}
//Atvaizduojam pranesimus su puslapiavimu - LIMITAS nurodytas virsuje
$sql2 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "knyga` ORDER BY `time` DESC LIMIT $p, $limit");
if ($viso > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}

$text = '';
if (sizeof($sql2) > 0) {
	foreach ($sql2 as $row) {
		$extra = '';
		if (defined("LEVEL") && LEVEL == 1) {
			$extra .= "<a href='" . url("d," . $row['id'] . "") . "'><img src='images/icons/control_delete_small.png' alt='[d]' title='{$lang['admin']['delete']}' class='middle' border='0' /></a> <a href='" . url("r," . $row['id'] . "") . "'><img src='images/icons/brightness_small_low.png' alt='[{$lang['admin']['edit']}]' title='{$lang['admin']['edit']}' class='middle' border='0' /></a>  ";
		} else {
			$extra = '';
		}
		$text .= "<div class=\"title\"><em><a href=\"?id," . (int)$_GET['id'] . ";p,$p#knyg_" . $row['id'] . "\" name=\"knyg_" . $row['id'] . "\" id=\"knyg_" . $row['id'] . "\"><img src=\"images/icons/bullet_black.png\" alt=\"#\" class=\"middle\" border=\"0\" /></a> " . input($row['nikas']) . " $extra (" . date('Y-m-d H:i:s ', $row['time']) . ") - " . kada(date('Y-m-d H:i:s ', $row['time'])) . "</em></div><div class=\"sarasas\">" . smile(bbchat($row['msg'])) . "</div><br/>";

	}
}

if (isset($_POST['knyga']) && $_POST['knyga'] == $lang['guestbook']['submit'] && strtoupper($_POST['code']) == $_SESSION['code'] && !empty($_POST['zinute']) && !empty($_POST['vardas'])) {
	$msg = htmlspecialchars($_POST['zinute']);
	$nick = $_POST['vardas'];

	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "knyga` (`nikas`, `msg`, `time` ) VALUES (" . escape($nick) . ", " . escape($msg) . ", '" . time() . "');");

	header('Location: ?id,' . (int)$_GET['id']);
}

$forma = '
 <form name="knyga" action="" method="post">
 ' . $lang['guestbook']['name'] . ':<br />
 <input type="text" name="vardas" value="' . (isset($_SESSION['username']) && !empty($_SESSION['username']) ? input($_SESSION['username']) : '') . '" size="20" class="input" /><br />
 ' . $lang['guestbook']['message'] . ':<br />
		<textarea name="zinute" rows="3" cols="10" class="input"></textarea>
		<br />' . $lang['guestbook']['code'] . ':<br />
		<input type="text" name="code" value="" size="20" class="chapter" /> &nbsp; &nbsp; ' . kodas() . '<br />

		<br />
		<input type="submit" name="knyga" value="' . $lang['guestbook']['submit'] . '" />
		</form>
';

hide($lang['guestbook']['write'], $forma, 'knyga', false);
if (strlen($text) < 1) {
	$text = $lang['guestbook']['empty'];
}
lentele($lang['guestbook']['guestbook'], $text);

if ($viso > $limit) {
	lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
}
unset($extra, $text, $forma);
//PABAIGA - atvaizdavimo


?>
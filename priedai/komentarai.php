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
if (!defined("OK")) {
	header("location: ?");
}
function komentarai($id, $hide = false) {
	global $url, $page, $lang, $conf;
	if (isset($url['id']) && isnum($url['id']) && $url['id'] > 0 && isnum($id) && $id > 0) {
		if (isset($_SESSION['id']) || $conf['kmomentarai_sveciams'] == 1) {
			/*$text = "
			<center>
			<form name=\"n_kom\" id=\"n_kom\" action=\"\" method=\"post\">
			" . bbs('n_kom') . " <textarea name=\"n_kom\" rows=5 cols=80 wrap=\"on\" style=\"width:90%\"></textarea><br/>
			<input type=\"hidden\" name=\"id\" value=\"" . $id . "\">
			<input type=\"submit\" name=\"Naujas\" value=\"{$lang['comments']['send']}\">
			</form>
			</center>";*/
			include_once ("priedai/class.php");
			$bla = new forma();
			$form = array("Form" => array("action" => "", "method" => "post", "name" => "n_kom"), "{$lang['guestbook']['name']}:" => (!isset($_SESSION['id']) ? array("type" => "text", "value" => (isset($_COOKIE['komentatorius']) ? $_COOKIE['komentatorius'] : ""), "name" => "name") : array("type" => "string", "value" => "<b>" . $_SESSION['username'] . "</b>")),"  \r\r\r\r\r"=>array("type"=>"string","value"=>bbs('n_kom')), "{$lang['guestbook']['message']}:" => array("type" => "textarea", "value" => "", "class" => "input", "name" => "n_kom", "extra" => "rows=\"5\" cols=\"3\""), (!isset($_SESSION['id']) ? kodas() : "") => (!isset($_SESSION['id']) ? array("type" => "text", "value" => "", "name" => "code", "class" => "chapter") : ""), " " => array("type" => "submit", "name" => "Naujas", "value" => "{$lang['comments']['send']}"), "  " => array("type" => "hidden",
				"value" => $id, "name" => "id"));


			hide("{$lang['comments']['write']}", $bla->form($form), $hide);
		} else {
			hide("{$lang['comments']['write']}", $lang['system']['pleaselogin']);
		}
		//$sql = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."kom` WHERE kid = ".escape($id)." AND pid = ".escape((int)$url['id'])." ORDER BY `data` DESC LIMIT 50");
		$sql = mysql_query1("SELECT *, (SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `" . LENTELES_PRIESAGA . "kom`.`nick_id`=`id`) AS email,
                (SELECT `levelis` FROM `" . LENTELES_PRIESAGA . "users` WHERE `" . LENTELES_PRIESAGA . "kom`.`nick_id`=`id`) AS levelis FROM `" . LENTELES_PRIESAGA . "kom` WHERE kid = " . escape($id) . " AND pid = " . escape($page) . " ORDER BY `data` DESC LIMIT 50");
		$text = ''; $tr = '';
		$i = 0;
		foreach ($sql as $row) {
			$i++;
			$tr = $i % 2 ? '2' : '';
			$text .= "<div class=\"tr$tr\"><div class=\"title\"><a href=\"#k:" . $row['id'] . "\" id=\"k:" . $row['id'] . "\"> <img src=\"images/icons/bullet_black.png\" alt=\"#\" class=\"middle\" border=\"0\" /> </a> ";
			if (defined("LEVEL") && (LEVEL == 1 || (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('com', unserialize($_SESSION['mod']))))) {
				$text .= "<a href='" . url("dk," . $row['id'] . "") . "' onclick=\"return confirm('{$lang['admin']['delete']}?') \">[{$lang['admin']['delete']}]</a> ";
			}
			if ($row['nick_id'] == 0) {
				$duom = @unserialize($row['nick']);
				$nick = user($duom[0], $row['nick_id'], $row['levelis']) . ($_SESSION['level'] == 1 ? " (" . $duom[1] . ")" : "");
			} else {
				$nick = user($row['nick'], $row['nick_id'], $row['levelis']);
			}
			$text .= $nick;
			$text .= " (" . date('Y-m-d H:i:s ', $row['data']) . ") " . naujas($row['data'], $row['nick']) . "</div>" . smile(bbchat(wrap(input($row['zinute']), 80))) . "</div>";
			//  <div class=\"avatar\" align=\"left\" style=\"display:inline;margin:4px;padding:2px;height:auto;\">" . avatar($row['email'], 40) . "</div>
		}
		if (!empty($text)) {
			lentele($lang['comments']['comments'], $text);
		}
	}
}

//Irasom nauja komentara jei nurodytas puslapis, gal perdidele salyga bet saugumo sumetimais :)
if (isset($_POST['n_kom']) && !empty($_POST['n_kom']) && !empty($_POST['Naujas']) && $_POST['Naujas'] == $lang['comments']['send'] && isset($_POST['id']) && !empty($_POST['id']) && isnum($_POST['id']) && (isset($_SESSION['id']) || $conf['kmomentarai_sveciams'] == 1)) {
	if ((isset($_POST['code']) && strtoupper($_POST['code']) == $_SESSION['code']) || isset($_SESSION['id'])) {
		if (isset($_SESSION['id'])) {
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+1 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
		} else {
			if (!isset($_COOKIE['komentatorius'])||$_POST['name']!=$_COOKIE['komentatorius']) {
				setcookie("komentatorius", $_POST['name'], time() + 60 * 60 * 24 * 30);
			}
		}
		$nick_id = (isset($_SESSION['id']) ? $_SESSION['id'] : 0);
		$nick = (isset($_SESSION['username']) ? $_SESSION['username'] : (!empty($_POST['name']) ? serialize(array($_POST['name'], getip())) : serialize(array($lang['system']['guest'], getip()))));
		mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "kom` (`kid`, `pid`, `zinute`, `nick`, `nick_id`, `data`) VALUES (" . escape($_POST['id']) . ", " . escape($page) . ", " . escape($_POST['n_kom']) . ", " . escape($nick) . ", " . escape($nick_id) . ", '" . time() . "')");
		unset($_POST['Naujas']);
		header("location: " . $_SERVER['HTTP_REFERER']);
	} else {
		klaida($lang['system']['error'], $lang['reg']['wrongcode']);
	}
}
//print_r($_SESSION);
//echo in_array('com',unserialize($_SESSION['mod']));
// Trinam komentara
if (isset($url['dk']) && isnum($url['dk']) && $url['dk'] > 0 && isset($url['id']) && !empty($url['id']) && isnum($url['id']) && defined("LEVEL") && (LEVEL == 1 || (isset($_SESSION['mod']) && is_array(unserialize($_SESSION['mod'])) && in_array('com', unserialize($_SESSION['mod']))))) {
	$id = (int)$url['dk'];
	$sql = mysql_query1("SELECT nick, nick_id FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape($id) . " LIMIT 1");
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai-1 WHERE nick=" . escape($sql['nick']) . " AND `id` = " . escape($sql['nick_id']) . "");
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape($id) . " LIMIT 1");
	unset($id);
	header("location: " . $_SERVER['HTTP_REFERER'] . "");
}

?>

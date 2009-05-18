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

session_start();
include_once ("priedai/conf.php");
include_once ("priedai/prisijungimas.php");
// Tikrinam su ajax registracijoje ivedamus duomenis.
// Speju cia gali buti saugumo spraga.
// Nebaigta funkcija su Kodo ivedimu ziurint i paveiskliuka.
if (isset($_GET) && !empty($_GET)) {

	if (isset($_GET['nick']) && !empty($_GET['nick'])) {
		if (preg_match('/[^A-Za-z0-9]/', $_GET['nick'])) {
			$error = "<img src=\"images/icons/cross.png\" alt=\"X\" align=\"absmiddle\" /> {$lang['reg']['onlylnn']}";
			echo $error;
		} else {
			$vardas = htmlentities($_GET['nick'], ENT_QUOTES);
			if (isset($vardas)) {
				$einfo = count(mysql_query("SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape($vardas) . " LIMIT 1"));
				if ($einfo != 0) {
					$error = "<img src=\"images/icons/cross.png\" alt=\"X\" align=\"absmiddle\" /> {$lang['reg']['takenusername']}";
				} else {
					$error = "<img src=\"images/icons/tick.png\" alt=\"√\" align=\"absmiddle\" />";
				}
				echo $error;
			}
		}
	} elseif (isset($_GET['email']) && !empty($_GET['email'])) {
		$email = htmlentities($_GET['email'], ENT_QUOTES);
		if (isset($email)) {
			$einfo = count(mysql_query("SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `email`=" . escape($email) . " LIMIT 1"));
			if ($einfo != 0) {
				$error = "<img src=\"images/icons/cross.png\" alt=\"X\" align=\"absmiddle\" /> {$lang['reg']['emailregistered']}.";
			} else {
				$error = "<img src=\"images/icons/tick.png\" alt=\"√\" align=\"absmiddle\" />";
			}
		}
		echo $error;
	} elseif (isset($_GET['kode']) && !empty($_GET['kode'])) {
		if (preg_match('/[^A-Za-z0-9]/', $_GET['kode'])) {
			$error = "<img src=\"images/icons/cross.png\" alt=\"X\" align=\"absmiddle\" /> {$lang['reg']['onlylnn']}";
		} else {
			$kode = strtoupper(strip_tags($_GET['kode']));
			if (isset($_SESSION['code']) && !empty($_SESSION['code'])) {
				if ($kode != $_SESSION['code']) {
					$error = "<img src=\"images/icons/cross.png\" alt=\"X\" align=\"absmiddle\" /> {$lang['reg']['wrongcode']}";
				} else {
					$error = "<img src=\"images/icons/tick.png\" alt=\"√\" align=\"absmiddle\" />";
				}
			}
		}
		echo $error;
	}

	//Jei ajax kreipiasi del chatbox
	/*
	if (isset($_GET['shoutbox']) && $_GET['shoutbox']) {
	$extras = '';
	$chat_box = '';
	$chat = mysql_query1("SELECT SQL_CACHE `" . LENTELES_PRIESAGA . "chat_box`.*,`" .
	LENTELES_PRIESAGA . "users`.`nick`,`" . LENTELES_PRIESAGA . "users`.`levelis`
	FROM `" . LENTELES_PRIESAGA . "chat_box` Inner Join `" . LENTELES_PRIESAGA .
	"users` ON `" . LENTELES_PRIESAGA . "chat_box`.`niko_id` = `" .
	LENTELES_PRIESAGA . "users`.`id`
	ORDER BY `time` DESC LIMIT " . escape((int)$conf['Chat_limit']));
	while ($row = mysql_fetch_assoc($chat)) {
	if (defined("LEVEL") && (LEVEL == 1 || LEVEL == 2) && isset($conf['puslapiai']['deze.php']['id'])) {
	$extras = "
	<a href='?id," . $conf['puslapiai']['deze.php']['id'] . ";d," . $row['id'] .
	"'><img src='images/icons/bullet_delete.png' alt='[d]' class='middle' border='0' /></a>
	<a href='?id," . $conf['puslapiai']['deze.php']['id'] . ";r," . $row['id'] .
	"'><img src='images/icons/bullet_orange.png' alt='[r]' class='middle' border='0' /></a>
	
	";
	}
	$chat_box .= '	
	' . user($row['nick'], $row['niko_id'], $row['levelis']) . $extras . ' <br />
	' . wrap(smile(bbchat($row['msg'])), 18) . '<br />
	';
	}

	echo $chat_box;
	}
	}
	if (isset($_POST['chat_box']) && !empty($_POST['chat_box']) && $_POST['chat_box'] ==
	$lang['sb']['send'] && !empty($_POST['chat_msg']) && isset($_SESSION['username']) &&
	!empty($_SESSION['username'])) {
	$msg = htmlspecialchars($_POST['chat_msg']);
	$nick = $_SESSION['username'];
	$nick_id = $_SESSION['id'];
	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA .
	"chat_box` (`nikas`, `msg`, `time`, `niko_id`) VALUES (" . escape($nick) . ", " .
	escape($msg) . ", " . escape(time()) . ", " . escape($nick_id) . ");");
	} */
	//else
}
if (isset($_POST['dir'])) {

	//Patikrinam ar tai adminas
	if (!defined("LEVEL") || (LEVEL > 2) || !defined("OK") || !isset($_SESSION['username'])) {
		include_once ("priedai/prisijungimas.php");
		admin_login_form($lang['system']['pleaselogin']);
	}
	$denny = "toolbar_reklama.php|conf.php|localhost.php|mg2_settings.php|sms_reklama.php|_config-rating.php|.svn|sms_config.php|.htaccess|sql.sql";
	$denny = explode("|", $denny);

	$root = '.';
	$_POST['dir'] = urldecode($_POST['dir']);

	if (file_exists($root . $_POST['dir'])) {
		$files = scandir($root . $_POST['dir']);
		natcasesort($files);
		if (count($files) > 2) {

			echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
			// All dirs
			foreach ($files as $file) {
				if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_POST['dir'] . $file) && !in_array($file, $denny)) {
					echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
				}
			}
			// All files
			foreach ($files as $file) {
				if (file_exists($root . $_POST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_POST['dir'] . $file) && !in_array($file, $denny)) {
					$ext = preg_replace('/^.*\./', '', $file);
					echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
				}
			}
			echo "</ul>";
		}
	}
}

?>
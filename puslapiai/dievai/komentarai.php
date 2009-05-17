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
if (isset($url['k']) && isnum($url['k']) && $url['k'] > 0) {
	$kid = (int)$url['k'];
} else {
	$kid = 0;
}
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$pid = (int)$url['p'];
} else {
	$pid = 0;
}
if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) {
	$did = (int)$url['d'];
} else {
	$did = 0;
}

/* MySQL Lenta
CREATE TABLE `kom` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`kid` INT NOT NULL DEFAULT '0',
`pid` INT NOT NULL DEFAULT '0',
`zinute` TEXT NOT NULL ,
`nick` CHAR( 50 ) NOT NULL ,
`nick_id` INT NOT NULL DEFAULT '0',
`data` DATETIME NOT NULL DEFAULT '0000-00-00 00:00'
) ENGINE = MYISAM ;
*/

//Surenkam puslapius kuriuose ira komentarai
$sql = mysql_query1("SELECT pid FROM `" . LENTELES_PRIESAGA . "kom` GROUP BY pid ORDER BY pid DESC");
$text = '
<select name="pid" onChange="top.location.href=\'?id,' . $url['id'] . ';a,' . $_GET['a'] . ';p,\' + this.value;">
<option value="0" disabled="disabled" selected="selected">---</option>';
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		//reikia surasyti puslapiu pavadinimus pagal id - Seip nebutina bet kad aiskiau butu
		if ($row['pid'] == 22) {
			$extra = "Straipsniai";
		} elseif ($row['pid'] == 19) {
			$extra = "Pamokos mIRC";
		} elseif ($row['pid'] == 50) {
			$extra = "Naujienos";
		} elseif ($row['pid'] == 6) {
			$extra = "Siuntiniai";
		} elseif ($row['pid'] == 7) {
			$extra = "CodeBin";
		} elseif ($row['pid'] == 4) {
			$extra = "Kitas testas";
		} elseif ($row['pid'] == 3) {
			$extra = "Dar vienas testas";
		} else {
			$extra = '';
		}
		$text .= "<option value=\"" . $row['pid'] . "\" " . (($pid == $row['pid']) ? "selected=\"selected\"" : "") . ">" . $row['pid'] . " $extra</option>";
	}
} else {
	$text .= '<option value="0" disabled="disabled" selected="selected">Komentarų nėra</option>';
}
$text .= '</select>';
lentele("Pasirinkit puslapį", $text);

if ($pid > 0) {
	$sql = mysql_query1("SELECT kid FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid = " . escape($pid) . " GROUP BY kid ORDER BY pid DESC");
	$text = '
	<select name="kid" onChange="top.location.href=\'?id,' . $url['id'] . ';a,' . $_GET['a'] . ';p,' . $pid . ';k,\' + this.value;">
	<option value="0" disabled="disabled" selected="selected">---</option>';
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			$text .= "<option value=\"" . $row['kid'] . "\" " . (($kid == $row['kid']) ? "selected=\"selected\"" : "") . ">" . $row['kid'] . "</option>";
		}
		$text .= '</select>';
		lentele("Pasirinkit puslapio ID", $text);
	} else {
		$text .= '<option value="0" disabled="disabled" selected="selected">Komentarų nėra</option>';
	}
}

if ($url['id'] == 46 && $url['a'] == 10 && $kid > 0 && $pid > 0) {
	//$sql = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."kom` WHERE kid = ".escape((int)$url['k'])." AND pid = ".escape((int)$url['p'])." ORDER BY `data` DESC");
	$sql = mysql_query1("SELECT *, (SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `" . LENTELES_PRIESAGA . "kom`.`nick_id`=`id`) AS email FROM `" . LENTELES_PRIESAGA . "kom` WHERE kid = " . escape((int)$url['k']) . " AND pid = " . escape((int)$url['p']) . " ORDER BY `data` DESC");
	$text = '';
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {			{
				$text .= "<div class=\"title\"><a href='" . url("d," . $row['id'] . "") . "' onclick=\"return confirm('Ar tikrai norite trinti?') \">[d]</a> <a href='http://85.232.143.6/nvs/index.php?id,47;m," . $row['nick_id'] . "'>" . $row['nick'] . "</a> <a href='?id,45;n,1;u," . $row['nick'] . "'><img src='images/pm/mail.png' alt='mail' border='0'/></a> (" . $row['data'] . ")" . "</div>
			<blockquote><table><tr valign='top'><td><div class='avataras'>" . avatar($row['email'], 40) . "</div></td><td>" . smile(bbchat(wrap(input($row['zinute']), 80))) . "</td></tr></table></blockquote>";
			}
		}
	else {
		klaida("Klaida", "komentarų nėra");
	}
	lentele("Pasirinkto puslapio komentarai", $text);
	$text = "
		<center>
		<form name=\"n_kom\" action=\"\" method=\"post\">
			<textarea name=\"n_kom\" rows=5 cols=40 wrap=\"on\"></textarea><br/>
			<input type=\"submit\" name=\"Naujas\" value=\"Siųsti\">
		</form>
		</center>
	";
	lentele("Naujas komentaras", $text);
}

//Irasom nauja komentara jei nurodytas puslapis, gal perdidele salyga bet saugumo sumetimais :)
if ($url['id'] == 46 && $url['a'] == 10 && $kid > 0 && $pid > 0 && isset($_POST) && isset($_POST['n_kom']) && !empty($_POST['n_kom']) && isset($_POST['n_kom']) && !empty($_POST['Naujas']) && $_POST['Naujas'] == "Siųsti" && defined("LEVEL") && LEVEL >= 20) {
	mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "kom` (`kid`, `pid`, `zinute`, `nick`, `nick_id`, `data`) VALUES (" . escape($url['k']) . ", " . escape($url['p']) . ", " . escape($_POST['n_kom']) . ", " . escape($_SESSION['username']) . ", " . escape($_SESSION['id']) . ", '" . time() . "')");
	header("location: ?id," . $url['id'] . ";a," . $_GET['a'] . ";k," . $kid . ";p," . $pid . "");
}

// Trinam komentara
if ($did > 0 && $url['id'] == 46 && $url['a'] == 10 && defined("LEVEL") && LEVEL >= 20) {
	$id = (int)$url['d'];
	$sql = mysql_query1("SELECT nick, nick_id FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape($id) . " LIMIT 1");
	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai-1 WHERE nick=" . escape($sql['nick']) . " AND `id` = " . escape($sql['nick_id']) . "");
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE id=" . escape($id) . " LIMIT 1");
	unset($id);
	header("location: ?id," . $url['id'] . ";a," . $_GET['a'] . ";k," . $url['k'] . ";p," . $url['p'] . "");
}
//unset($_POST);


?>

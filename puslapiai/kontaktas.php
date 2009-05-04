<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * @Apie: Kontaktas su svetainės administratorium
 **/

if (isset($_POST['kontaktas']) && $_POST['kontaktas'] == 'Siųsti' && strtoupper($_POST['code']) === $_SESSION['code'] && !empty($_POST['zinute']) && !empty($_POST['vardas'])) {

	$title = strip_tags($_POST['pavadinimas']);
	$from = strip_tags($_POST['vardas']);
	$email = strip_tags($_POST['email']);

	$msg = "{$lang['contact']['email']}: <b>" . $email . "</b><br/>\n{$lang['contact']['name']}: <b>" . $from . "</b><br/>\n{$lang['contact']['from']}: <b>" . adresas() . "</b><br/>\n----<br/>\n" . nl2br(htmlspecialchars($_POST['zinute']));
	$to = $conf['Pastas'];

	ini_set("sendmail_from", $email);

	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	$headers .= 'From: ' . input($from) . ' <' . $email . '>' . "\r\n";

	if (mail($to, input($title), $msg, $headers)) {
		msg("{$lang['system']['done']}", "{$lang['contact']['sent']}");
		redirect("?id," . (int)$_GET['id'], "meta");
	}

} elseif (isset($_POST) && !empty($_POST['kontaktas'])) {
	klaida("{$lang['system']['error']}", "{$lang['contact']['bad']}");
} else {
	if (isset($_SESSION['username'])) {
		$from = $_SESSION['username'];
		$email = mysql_fetch_assoc(mysql_query1("SELECT `email` FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`=" . escape($from) . " LIMIT 1"));
		$email = $email['email'];
	}
}

$forma = '
 <form name="knyga" method="post">
 ' . $lang['contact']['subject'] . ':<br /><input type="text" name="pavadinimas" value="' . (isset($title) && !empty($title) ? input($title) : '') . '" size="20" style="width:90%;"><br />
' . $lang['contact']['name'] . ':<br /><input type="text" name="vardas" value="' . (isset($from) && !empty($from) ? input($from) : '') . '" size="20" style="width:90%;"><br />
 ' . $lang['contact']['email'] . ':<br /><input type="text" name="email" value="' . (isset($email) ? input($email) : '') . '" size="20" style="width:90%;"><br />
 ' . $lang['contact']['message'] . ':<br />
		<textarea name="zinute" rows="13" cols="10" style="width:90%">' . (isset($_POST['zinute']) && !empty($_POST['zinute']) ? input($_POST['zinute']) : '') . '</textarea>
		<br /><br />
		<input type="text" name="code" value="" size="20" style="float:left;height:38px;text-align:center;text-transform:uppercase;font-weight:bold;vertical-align:middle"> &nbsp; &nbsp; ' . kodas() . '<br />

		<br />
		<input type="submit" name="kontaktas" value="' . $lang['contact']['submit'] . '" />
		</form>
';

lentele($lang['contact']['form'], $forma);

unset($forma, $result, $from, $forma, $error, $to, $msg, $email, $title);
//PABAIGA - atvaizdavimo


?>
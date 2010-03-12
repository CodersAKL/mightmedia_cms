<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 356 $
 * @$Date: 2009-11-11 00:08:55 +0200 (Wed, 11 Nov 2009) $
 **/


//if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
if (!defined("OK") || !ar_admin(basename(__file__))) {

	header('location: ?');
	exit();
}

/*$buttons = <<< HTML
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,1'">{$lang['admin']['poll_create']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,2'">{$lang['admin']['poll_edit']}</button>
HTML;*/
$buttons="<div id=\"admin_menu\" class=\"btns\"><a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,1")."\"class=\"btn\"><span><img src=\"".ROOT."images/icons/heart__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['poll_create']}</span></a>  <a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,2")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/heart__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['poll_edit']}</span></a></div>";
lentele($lang['admin']['poll'], $buttons);
//if (empty($url['v'])) {
//	$url['v'] = 0;


	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE `ijungtas`='TAIP'  ORDER BY `id` DESC LIMIT 1");
	if (sizeof($sql) > 0) {
		if (!empty($sql['klausimas'])) {
			$info = $sql['klausimas'];
			$text = "<b>$info</b></br>";
		} else {
			$text = $lang['admin']['poll_no'];
		}
	}

	lentele("{$lang['admin']['poll_active']}", $text);
	unset($text, $a, $total, $info2, $info, $sql, $is);
//}
if (isset($url['v']) && (int)$url['v'] == 1) {
	$text = "
<form name='b_create' action='?id," . $_GET['id'] . ";a," . $_GET['a'] . "' method='post'>
	<table border=0>
		<tr>
			<td>{$lang['admin']['poll_question']}:</td>
			<td><input name='b_kl' type='text' size=50 value=''></td>
		</tr>
		<tr>
			<td>{$lang['admin']['poll_votecan']}:</td>
			<td>
				<select size='1' name='leid'>
					<option value='vis'>{$lang['admin']['poll_all']}</option>
					<option value='nar'>{$lang['admin']['poll_membs']}</option>
				</select>
			</td>
		</tr>
		<tr><td colspan=2>
		<input name='1' type='text' size=50 value=''><br/>
				<input name='2' type='text' size=50 value=''><br/>
						<input name='3' type='text' size=50 value=''><br/>
		<input name='4' type='text' size=50 value=''><br/>
		<input name='5' type='text' size=50 value=''><br/>
{$lang['admin']['poll_info']}.

		</td></tr>
		<tr>
			<td></td>
			<td>
				
			</td>
		</tr>
		</table>
		<input name='b_create' type='submit' value='{$lang['admin']['poll_create']}'><br>
</form>
	";
	lentele("{$lang['admin']['poll_create']}", $text);
	unset($text);
}

if (isset($_POST['b_delete']) && $_POST['b_delete'] == $lang['admin']['delete']) {
	$result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE `id`= " . escape((int)$_POST['id']) . " LIMIT 1");
	header("Location: ".url("?id," . $_GET['id'] . ";a," . $_GET['a']));
}
if (isset($_POST['b_edit']) && $_POST['b_edit'] == $lang['admin']['edit']) {
	$result2 = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "balsavimas` SET info='" . $_POST['leid'] . "', `ijungtas` = " . escape($_POST['ar']) . " WHERE `id`='" . $url['n'] . "' LIMIT 1 ;");
	header("Location: ".url("?id," . $_GET['id'] . ";a," . $_GET['a']));
}
if (isset($_POST['b_delete']) && $_POST['b_delete'] == $lang['admin']['edit']) {


	$edit = "
<form name='b_edit' action='?id," . $_GET['id'] . ";a," . $_GET['a'] . ";n," . $_POST['id'] . "' method='post'>	
	Ar rodyti apklausą?
	<select size=1 name='ar'>
		<option name='ar' value='TAIP'>{$lang['admin']['yes']}</option>
		<option name='ar' value='NE'>{$lang['admin']['no']}</option>
	</select>
					
	</br>{$lang['admin']['poll_votecan']}:
	<select size=1 name='leid'>
		<option value='vis'>{$lang['admin']['poll_all']}</option>
		<option value='nar'>{$lang['admin']['poll_membs']}</option>
	</select>
	<input name='b_edit' type='submit' value='{$lang['admin']['edit']}'><br>
</form>";
	lentele("{$lang['admin']['poll_edit']}", $edit);

}
if (isset($url['v']) &&(int)$url['v'] == 2) {
	$sql2 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "balsavimas`");
	if (sizeof($sql2) > 0) {
		$text = "
	<form name='b_delete' action='?id," . $_GET['id'] . ";a," . $_GET['a'] . "' method='post'>
		<select size='1' name='id'>
	";

		foreach ($sql2 as $row) {
			if (isset($row['klausimas'])) {
				$text .= "<option  value=" . $row['id'] . ">" . $row['klausimas'] . "</option>";
			}
		}

		$text .= "
		</select>
		<input name='b_delete' type='submit' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" value='{$lang['admin']['delete']}'>
		<input name='b_delete' type='submit' value='{$lang['admin']['edit']}'>
	</form>
	";
		lentele("{$lang['admin']['poll_edit']}", $text);
	}
	unset($sql, $row, $text, $info);
}


if (isset($_POST['b_create']) && $_POST['b_create'] == $lang['admin']['poll_create']) {
	$kl = $_POST['b_kl'];
	$ats1 = (isset($_POST[1]) && !empty($_POST[1]) ? strip_tags($_POST[1]) . ';0' : ';0');
	$ats2 = (isset($_POST[2]) && !empty($_POST[2]) ? strip_tags($_POST[2]) . ';0' : ';0');
	$ats3 = (isset($_POST[3]) && !empty($_POST[3]) ? strip_tags($_POST[3]) . ';0' : ';0');
	$ats4 = (isset($_POST[4]) && !empty($_POST[4]) ? strip_tags($_POST[4]) . ';0' : ';0');
	$ats5 = (isset($_POST[5]) && !empty($_POST[5]) ? strip_tags($_POST[5]) . ';0' : ';0');

	$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "balsavimas` (`info`, `autorius`, `laikas`, `klausimas`, `pirmas`, `antras`, `trecias`, `ketvirtas`,`penktas`) VALUES ('" . $_POST['leid'] . "', '" . $_SESSION['id'] . "', '" . time() . "','" . $kl . "','" . $ats1 . "','" . $ats2 . "','" . $ats3 . "','" . $ats4 . "','" . $ats5 . "')");
	delete_cache("SELECT * ,autorius ,(SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE id=autorius LIMIT 1)AS nick FROM `" . LENTELES_PRIESAGA . "balsavimas` WHERE ijungtas='TAIP' ORDER BY `laikas` DESC LIMIT 1");
	if ($result) {
		msg("{$lang['system']['done']}", "{$lang['admin']['poll_created']}.");
	}
	redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");
}
unset($a, $ats1, $ats2, $ats3, $ats4, $ats5, $balsas, $sujungti);
//unset($_POST['b_create'], $_POST['b_delete']);

?>
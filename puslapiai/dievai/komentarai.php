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


//Surenkam puslapius kuriuose ira komentarai
include_once ("priedai/class.php");
$bla = new forma();

if (isset($_POST['del'])) {
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape($_POST['pg']) . "");
	header("location: " . $_SERVER['HTTP_REFERER'] . "");
}
if (isset($_POST['del2'])) {
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape($_POST['page']) . " AND `kid`=" . escape($_POST['pg']) . "");
	header("location: " . $_SERVER['HTTP_REFERER'] . "");
}
if (!isset($_POST['pg'])) {
	$sql = mysql_query1("SELECT `pid` FROM `" . LENTELES_PRIESAGA . "kom` GROUP BY `pid` ORDER BY `pid` DESC");

	foreach ($sql as $row) {
		$pgs[$row['pid']] = $row['pid'];
	}
	$form = array("Form" => array("action" => "", "method" => "post", "name" => "com"), "{$lang['online']['page']}:" => array("type" => "select", "value" => $pgs, "name" => "pg"), " " => array("type" => "submit", "name" => "select", "value" => "{$lang['admin']['page_select']}"), "  " => array("type" => "submit", "name" => "del", "value" => "{$lang['admin']['del_comments']}"));

	lentele("{$lang['admin']['adm_comments']}", $bla->form($form));
}
if (isset($_POST['select'])) {
	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "kom` where `pid`=" . escape($_POST['pg']) . " GROUP BY `kid`");
	foreach ($sql as $row) {
		$pgs[$row['kid']] = $row['kid'];
	}
	$form = array("Form" => array("action" => "", "method" => "post", "name" => "com2"), "{$lang['admin']['comments_kid']}:" => array("type" => "select", "value" => $pgs, "name" => "pg"), "\r\r " => array("type" => "hidden", "value" => $_POST['pg'], "name" => "page"), //" " => array("type" => "submit", "name" => "select2", "value" => "{$lang['admin']['page_select']}"),
		"  " => array("type" => "submit", "name" => "del2", "value" => "{$lang['admin']['del_comments']}"));

	lentele("{$lang['admin']['adm_comments']}", $bla->form($form));

}
if (isset($_POST['select2'])) {
	//čia jei norės pasidarys kas nors
}

?>

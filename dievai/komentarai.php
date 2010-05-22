<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 157 $
 * @$Date: 2009-06-01 15:23:34 +0300 (Mon, 01 Jun 2009) $
 **/


//Surenkam puslapius kuriuose ira komentarai
include_once (ROOT."priedai/class.php");
$bla = new forma();

if (isset($_POST['del'])) {
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape($_POST['pg']) . "");
	header("location: " . $_SERVER['HTTP_REFERER'] . "");
}
/*
if (isset($_POST['del2'])) {
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`=" . escape($_POST['page']) . " AND `kid`=" . escape($_POST['pg']) . "");
	header("location: " . $_SERVER['HTTP_REFERER'] . "");
}*/
if (!isset($_POST['pg']) && !isset($_GET['s'])) {
	$sql = mysql_query1("SELECT `pid` FROM `" . LENTELES_PRIESAGA . "kom` GROUP BY `pid` ORDER BY `pid` DESC");
	if (!empty($sql)) {
		foreach ($sql as $row) {
			$pgs[$row['pid']] = (isset($lang['pages'][str_replace('puslapiai/', '', $row['pid']).'.php']) ? $lang['pages'][str_replace('puslapiai/', '', $row['pid']).'.php'] : str_replace('puslapiai/', '', $row['pid']));
		}
		$form = array("Form" => array("action" => "", "method" => "post", "name" => "com"), "{$lang['online']['page']}:" => array("type" => "select", "value" => $pgs, "name" => "pg"), " " => array("type" => "submit", "name" => "select", "value" => "{$lang['admin']['page_select']}"), "  " => array("type" => "submit", "name" => "del", "value" => "{$lang['admin']['del_comments']}"));

		lentele("{$lang['admin']['adm_comments']}", $bla->form($form));
	} else
		klaida($lang['system']['warning'], $lang['system']['no_items']);
}
if (isset($_POST['select']) || isset($_GET['s'])) {
  if(isset($_GET['d'])){
    mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape($_GET['d']) . " LIMIT 1");
  }
  if(isset($_GET['e'])){
    $bla = new forma();
    if(!isset($_POST['edit'])){
    $row = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `id`=" . escape($_GET['e']) . " LIMIT 1");
    $form = array("Form" => array("action" => "", "method" => "post"), "{$lang['contact']['message'] }:" => array("type" => "textarea", "value" => input($row['zinute']), "name" => "msg","extra" => "rows=5", "class"=>"input"),
		" " => array("type" => "submit", "name" => "edit", "value" =>  $lang['admin']['edit']));
			lentele($lang['sb']['edit'], $bla->form($form));
  } else{
			$msg = trim($_POST['msg']) . "\n[sm] [i] {$lang['sb']['editedby']}: " . $_SESSION['username'] . " [/i] [/sm]";
			mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "kom` SET `zinute` = " . escape(strip_tags($msg)) . " WHERE `id` =" . escape($url['e']) . " LIMIT 1");
			if (mysql_affected_rows() > 0) {
				msg($lang['system']['done'], $lang['sb']['updated']);
			}

		}
	}
  $pg = (isset($_POST['pg']) ? $_POST['pg'] : base64_decode($_GET['s']));
	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "kom` where `pid`=" . escape($pg) . "");
	if (!empty($sql)) {
    $tbl = new Table();
		foreach ($sql as $row) {
				if ($row['nick_id'] == 0) {
					$duom = @unserialize($row['nick']);
					$nick = user($duom[0], $row['nick_id']) . ($_SESSION['level'] == 1 ? " (" . $duom[1] . ")" : "");
				} else {
					$nick = user($row['nick'], $row['nick_id']);
				}
		            $info[] = array(
$lang['new']['author'] => $nick, $lang['contact']['message'] => smile(bbchat(trimlink(input($row['zinute']), 150))),$lang['admin']['action'] => "<a href=\"" . url("s,".str_replace('=', '', base64_encode($pg)).";d," . $row['id'] . "") . "\" onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" title='{$lang['admin']['delete']}'><img src=\"" . ROOT . "images/icons/cross.png\" alt=\"[{$lang['admin']['delete']}]\" border=\"0\" class=\"middle\" /></a><a href=\"" . url("s,".str_replace('=', '', base64_encode($pg)).";e," . $row['id'] . "") . "\" title='{$lang['admin']['edit']}'><img src=\"" . ROOT . "images/icons/pencil.png\" alt=\"[{$lang['admin']['edit']}]\" border=\"0\" class=\"middle\" /></a>");
			//$pgs[$row['kid']] = $row['kid'];
		}
    echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
			</style>
			<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$(\'#com table\').dataTable( {
			  "bInfo": false,
			  "bProcessing": true,
						"aoColumns": [
              { "sWidth": "10%", "sType": "html" },
							{ "sWidth": "75%", "sType": "string" },
							{ "sWidth": "20px", "sType": "html", "bSortable": false}
						]
					} );
				} );
			</script>';
		/*$form = array("Form" => array("action" => "", "method" => "post", "name" => "com2"), "{$lang['admin']['comments_kid']}:" => array("type" => "select", "value" => $pgs, "name" => "pg"), "\r\r " => array("type" => "hidden", "value" => $_POST['pg'], "name" => "page"), //" " => array("type" => "submit", "name" => "select2", "value" => "{$lang['admin']['page_select']}"),
			"  " => array("type" => "submit", "name" => "del2", "value" => "{$lang['admin']['del_comments']}"));*/

		lentele("{$lang['admin']['adm_comments']}", '<div id="com">'.$tbl->render($info).'</div>');
	} else
		klaida($lang['system']['warning'], $lang['system']['no_items']);
}


?>

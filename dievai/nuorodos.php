<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 366 $
 * @$Date: 2009-12-03 20:46:01 +0200 (Thu, 03 Dec 2009) $
 **/

if (!defined("OK") || !ar_admin(basename(__file__))) {
	redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}

$buttons = "
<div class=\"btns\">
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,1")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/chain__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['links_unpublished']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,5")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/chain__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['links_create']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,4")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/chain__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['links_edit']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,2")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,3")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
</div>

";

if (empty($_GET['v'])) {
	$_GET['v'] = 0;
}
lentele($lang['admin']['nuorodos'], $buttons);

unset($buttons);
include_once (ROOT."priedai/kategorijos.php");
kategorija("nuorodos");

$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos' AND `path`=0 ORDER BY `id` DESC");
if (sizeof($sql) > 0) {

	$kategorijoss = cat('nuorodos', 0);
}

$kategorijos[0] = "--";
$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` ORDER BY `pavadinimas` DESC");
if (sizeof($sql2) > 0) {
	foreach ($sql2 as $row2) {
		$nuorodos[$row2['id']] = $row2['pavadinimas'];
	}
}

require_once (ROOT."priedai/class.php");

$bla = new forma();

if (isset($_POST['edit']) && $_POST['edit'] == $lang['system']['edit']) {

	$pavadinimas = input(strip_tags($_POST['name']));
	$url = input(strip_tags($_POST['url']));
	$aktyvi = input(strip_tags($_POST['ar']));
	$aprasymas = input(strip_tags($_POST['apie']));
	$kategorija = ceil((int)$_POST['Kategorijos_id']);
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`apie` = " . escape($aprasymas) . ",
			`active` = " . escape($aktyvi) . ",
			`url` = " . escape($url) . ",
			`cat` = " . escape($kategorija) . "
			WHERE `id`=" . escape($_POST['nuorodos_id']) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['links_updated']}");
	} else {
		klaida($lang['system']['error'], "<br><b>" . mysql_error() . "</b>");
	}
}


if (isset($_GET['r'])) {
	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE id='" . $_GET['r'] . "' LIMIT 1");
	$argi = array("TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}");
	$nuorodos_redagavimas = array("Form" => array("action" => url("?id,{$_GET['id']};a,{$_GET['a']};v,1"), "method" => "post", "name" => "edit"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijoss, "name" => "Kategorijos_id"), "{$lang['admin']['links_title']}:" => array("type" => "text", "value" => $sql['pavadinimas'], "name" => "name"), "{$lang['admin']['links_about']}:" => array("type" => "textarea", "value" => $sql['apie'], "name" => "apie"), "{$lang['admin']['link']}:" => array("type" => "text", "value" => $sql['url'], "name" => "url"), "{$lang['admin']['links_active']}:" => array("type" => "select", "value" => $argi, "name" => "ar"), "" => array("type" => "hidden", "name" => "nuorodos_id", "value" => $_GET['r']), "{$lang['admin']['edit']}:" => array("type" =>
		"submit", "name" => "edit", "value" => "{$lang['admin']['edit']}"));

	lentele($lang['admin']['links_edit'], $bla->form($nuorodos_redagavimas));

}
//trinam linką
if (isset($_GET['m'])) {
	$result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "nuorodos` 
			WHERE `id`=" . escape($_GET['m']) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['links_Deleted']}");
	} else {
		klaida($lang['system']['error'], "<br><b>" . mysql_error() . "</b>");
	}
}
if(isset($_POST['links_delete'])){
  foreach($_POST['links_delete'] as $a=>$b){
    $trinti[]="`id`=".escape($b);
  }
  mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE ".implode($trinti, " OR ")."");
  header("Location:".$_SERVER['HTTP_REFERER']);
  exit;
}
if (isset($_GET['p'])) {
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET active='TAIP' 
			WHERE `id`=" . escape($_GET['p']) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['links_activated']}.");
	} else {
		klaida($lang['system']['error'], "<br><b>" . mysql_error() . "</b>");
	}
}


elseif ($_GET['v'] == 1) {
	$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE active='NE'");


	include_once (ROOT."priedai/class.php");
	$bla = new Table();
	$info = array();
	if (sizeof($q) > 0) {
		foreach ($q as $sql) {
			$sql2 = mysql_query1("SELECT nick FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $sql['nick'] . "' LIMIT 1");

			$info[] = array("<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('linksch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"links_delete[]\" />", "{$lang['admin']['link']}:" => '<a href="' . $sql['url'] . '" title="<b>' . $sql2['nick'] . '</b>			<br/><br/>' . $lang['admin']['links_about'] . ': <i>' . $sql['apie'] . '</i><br/>' . $lang['admin']['links_author'] . ': <b>' . $sql2['nick'] . '</b><br/>' . $lang['admin']['links_date'] . ': <b>' . date('Y-m-d H:i:s ', $sql['date']) . ' - ' . kada(date('Y-m-d H:i:s ', $sql['date'])) . '</b>" target="_blank">' . $sql['pavadinimas'] . '</a>', "{$lang['admin']['action']}:" => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id'] ). "'title='{$lang['admin']['acept']}'><img src='".ROOT."images/icons/tick_circle.png' alt='a' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};m," . $sql['id']). "'title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='".ROOT."images/icons/cross.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};r," . $sql['id'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>");

		}
		echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$(\'#links table\').dataTable( {
          "bInfo": false,
          "bProcessing": true,
					"aoColumns": [
						{ "bSearchable": false, "sWidth": "10px", "sType": "html", "bSortable": false},
						{ "sWidth": "70%", "sType": "string" },
						
						{ "sWidth": "20px", "sType": "html", "bSortable": false}
					]
				} );
			} );
		</script>';
		lentele($lang['admin']['links_unpublished'], "<form id=\"linksch\" method=\"post\"><div id=\"links\">".$bla->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
	}
} elseif ($_GET['v'] == 4) {
	$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE active='TAIP'");


	include_once (ROOT."priedai/class.php");
	$bla = new Table();
	$info = array();
	if (sizeof($q) > 0) {
		foreach ($q as $sql) {
			$sql2 = mysql_query1("SELECT nick FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $sql['nick'] . "' LIMIT 1");

			$info[] = array("<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('linksch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"links_delete[]\" />",
			 "{$lang['admin']['link']}:" => '<a href="' . $sql['url'] . '" title="<b>' . $sql2['nick'] . '</b>
			<br/><br/>' . $lang['admin']['links_about'] . ': <i>' . $sql['apie'] . '</i><br/>' . $lang['admin']['links_author'] . ': <b>' . $sql2['nick'] . '</b><br/>' . $lang['admin']['links_date'] . ': <b>' . date('Y-m-d H:i:s ', $sql['date']) . ' - ' . kada(date('Y-m-d H:i:s ', $sql['date'])) . '</b>" target="_blank">' . $sql['pavadinimas'] . '</a>', "{$lang['admin']['action']}:" => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};m," . $sql['id'] ). "'title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='".ROOT."images/icons/cross.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};r," . $sql['id'] ). "'title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>");

		}
		echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$(\'#links table\').dataTable( {
          "bInfo": false,
          "bProcessing": true,
					"aoColumns": [
						{ "bSearchable": false, "sWidth": "10px", "sType": "html", "bSortable": false},
						{ "sWidth": "70%", "sType": "string" },
						
						{ "sWidth": "20px", "sType": "html", "bSortable": false}
					]
				} );
			} );
		</script>';
		lentele($lang['admin']['nuorodos'], "<form id=\"linksch\" method=\"post\"><div id=\"links\">".$bla->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
	} else {
		klaida($lang['system']['warning'], $lang['system']['no_items']);
	}
} elseif ($_GET['v'] == 5) { 
   
	if (isset($_POST['Submit_link']) && !empty($_POST['Submit_link'])) {

	// Nustatom kintamuosius
		$url = input(strip_tags($_POST['url']));
		$apie = input(strip_tags($_POST['apie']));
		$pavadinimas = input(strip_tags($_POST['name']));
		$cat = input(strip_tags($_POST['kat']));
    $active = input(strip_tags($_POST['act']));
		// Patikrinam
		//$pattern = "#^(http:\/\/|https:\/\/|www\.)(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?(\/)*$#i";
		$pattern = "#([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si";
		if (!preg_match($pattern, $url)) {
			klaida($lang['system']['error'], "{$lang['admin']['links_bad']}");

		} else {

			$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "nuorodos` (`cat` , `url` ,`pavadinimas` , `nick` , `date` , `apie`, `active`) VALUES (" . escape($cat) . ", " . escape($url) . ", " . escape($pavadinimas) . ", " . escape($_SESSION['id']) . ", '" . time() . "', " . escape($apie) . ", ".escape($active).");");
			if ($result) {
				msg($lang['system']['done'], "{$lang['admin']['links_created']}.");
				redirect(url("?id,{$_GET['id']};a,{$_GET['a']};v,{$_GET['v']}"), 'meta');
			} else {
				klaida($lang['system']['error'], "{$lang['admin']['links_allfields']}");
			}
		}
	}
    $nuorodos = array("Form" => array("action" => "", "method" => "post", "name" => "Submit_link"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijoss, "name" => "kat"), "{$lang['admin']['links_title']}:" => array("type" => "text", "value" => "", "name" => "name"), "Url:" => array("type" => "text", "value" => "http://", "name" => "url"),$lang['admin']['links_active'].":" => array("type" => "select", "value" => array("TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}"), "name" => "act"), "{$lang['admin']['links_about']}:" => array("type" => "textarea", "value" => "", "name" => "apie"), " " => array("type" => "submit", "name" => "Submit_link", "value" => "{$lang['admin']['links_create']}"));

		lentele($lang['admin']['links_create'], $bla->form($nuorodos));
}
unset($bla, $info, $sql, $sql2, $q, $result, $result2);
//unset($_POST);

?>
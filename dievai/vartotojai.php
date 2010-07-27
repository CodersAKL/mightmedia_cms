<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 362 $
 * @$Date: 2009-11-24 16:56:25 +0200 (Tue, 24 Nov 2009) $
 * */
if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
	redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}
include_once (ROOT . "priedai/class.php");
if (!isset($_GET['v']))
	$_GET['v'] = 1;


$buttons = "<div class=\"btns\">
	<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,1") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/users.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['user_list']}</span></a>
	<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,4") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/users__arrow.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['user_find']}</span></a>
	<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,2") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,3") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
</div>";

lentele($lang['admin']['vartotojai'], $buttons);
include_once (ROOT . "priedai/kategorijos.php");
kategorija("vartotojai", true);

if (isset($url['d']) && $url['d'] != "" && $url['d'] != 0) {
	$del = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "users` WHERE id=" . escape((int) $url['d']) . " AND `levelis` > 1");
	header("Location: " . url('d,0'));
}
if (isset($_POST['action']) && $_POST['action'] == $lang['admin']['save'] && $_POST['id'] > 0) {
	$info = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $_POST['id'] . "'" . ($_SESSION['id'] == 1 ? '' : 'AND `levelis` > 1') . " LIMIT 1");

	if (!empty($_POST['tsk'])) {
		$tsk = (int) $_POST['tsk'];
	} else {
		$tsk = $info['taskai'];
	}
	if (!empty($_POST['lvl']) && $_POST['lvl'] < 31) {
		$lvl = (int) $_POST['lvl'];
	} else {
		$lvl = $info['levelis'];
	}
	if (!empty($_POST['slapt'])) {
		$slapt = koduoju($_POST['slapt']);
	} else {
		$slapt = $info['pass'];
	}
	if (!empty($_POST['email'])) {
		$mail = $_POST['email'];
	} else {
		$mail = $info['email'];
	}

	$resut = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET `taskai`='$tsk' , `levelis`='$lvl' , `pass`='$slapt' , `email`='$mail' WHERE id=" . escape((int) $_POST['id']) . "" . ($_SESSION['id'] == 1 ? '' : 'AND `levelis` > 1'));
	if ($resut) {
		msg($lang['system']['done'], $lang['admin']['user_updated']);
		unset($_POST);
	} else {
		klaida($lang['system']['error'], "" . mysql_error() . "");
	}
	unset($result, $info);
}

//Jei redaguojam
if (isset($url['r']) && $url['r'] != "" && $url['r'] != 0) {
	$info = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $url['r'] . "'" . ($_SESSION['id'] == 1 ? '' : ' AND `levelis` > 1') . " LIMIT 1");
	if ($info) {
		$lygiai2 = array_keys($conf['level']);
		foreach ($lygiai2 as $key) {
			$lygiai[$key] = $conf['level'][$key]['pavadinimas'];
		}

		$text = array(
			'Form' => array('action' => url("?id,{$_GET['id']};a,{$_GET['a']}"), "method" => "post", "name" => "reg", 'extra' => "onSubmit=\"return checkMail('change_contacts','email')\""),
			$lang['admin']['user_points'] => array('type' => 'text', 'name' => 'tsk', 'extra' => "onkeyup=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\"", 'value' => (isset($info['taskai']) ? input($info['taskai']) : "")),
			$lang['admin']['user_level'] => array("type" => "select", "value" => $lygiai, "name" => "lvl", "class" => "input", "class" => "input", "selected" => (isset($info['levelis']) ? (int) $info['levelis'] : '')),
			"{$lang['admin']['user_pass']} <a href='#' title='<b>{$lang['system']['warning']}</b><br/>{$lang['admin']['user_passinfo']}<br/>'>[?]</a>" => array('type' => 'password', 'name' => 'slapt'),
			$lang['admin']['user_email'] => array('type' => 'text', 'value' => (isset($info['email']) ? input($info['email']) : "")),
			"" => array('type' => 'string', "value" => '<input type="hidden" name="id" value="' . $url['r'] . '" /><input type="submit" name="action" value="' . $lang['admin']['save'] . '">')
		);
		include_once (ROOT . "priedai/class.php");
		$bla = new forma();
		lentele('<strong>' . input($info['nick']) . '</strong> ', $bla->form($text) . "<br /><small>*{$lang['admin']['user_canteditadmin']}</small>", $lang['admin']['user_details']);
		unset($info, $text);
	} else {
		klaida($lang['system']['warning'], $lang['admin']['user_canteditadmin']);
	}
}

if (isset($_GET['v']) && $_GET['v'] == 1) {
	//Sarašas visų lygių
	$lygiai = array_keys($conf['level']);
	$grupe = "";
	//Užsukam ciklą tiek kartų kiek yra lygių
	foreach ($lygiai as $key) {
		$grupe .= "<img src='" . ROOT . "images/icons/" . $conf['level'][$key]['pav'] . "'> <a href='" . url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v," . $_GET['v'] . ";k," . $key) . "'>" . $conf['level'][$key]['pavadinimas'] . "</a><br>";
	}
	lentele($lang['admin']['user_groups'], $grupe);

	if (isset($_GET['k'])) {

		if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
			$p = escape(ceil((int) $url['p']));
		} else {
			$p = 0;
		}
		$limit = 50;
		//vartotoju sarasas pagal esamą levelį
		//$sql = mysql_query1("SELECT id, INET_NTOA(ip) AS ip, reg_data, login_data, gim_data, nick, vardas, pavarde, email,levelis FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=" . escape((int) $_GET['k']) . " LIMIT $p, $limit");
		$sql = mysql_query1("SELECT id, INET_NTOA(ip) AS ip, reg_data, login_data, gim_data, nick, vardas, pavarde, email,levelis FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=" . escape((int) $_GET['k']));
		$i = 0;
		$viso = kiek("users", "WHERE levelis=" . escape($_GET['k']));

		if (sizeof($sql) > 0) {
			foreach ($sql as $row2) {
				$i++;
				$info2[] = array(
					//"<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('usersch');\" />" => "<input type=\"checkbox\" value=\"{$row2['id']}\" name=\"news_delete[]\" />",
					$lang['admin']['user_name'] => user($row2['nick'], $row2['id'], $row2['levelis']),
					"IP" => $row2['ip'],
					$lang['admin']['user_email'] => preg_replace("#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "<a href=\"javascript:mailto:mail('\\1','\\2');\">\\1_(at)_\\2</a>", $row2['email']),
					$lang['admin']['action'] => "<a href='" . url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";r," . $row2['id']) . "'title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0' class='middle' /></a> <a href='" . url("d," . $row2['id']) . "' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0' class='middle' /></a><a href='" . url("?id," . $_GET['id'] . ";a,{$admin_pagesid['banai']};b,1;ip," . $row2['ip']) . "' title='{$lang['admin']['badip']}'><img src='" . ROOT . "images/icons/delete.png' border='0' class='middle' /></a>"
				);
			}
			$bla = new Table();

			echo '<style type="text/css" title="currentStyle">
			@import "' . ROOT . 'javascript/table/css/demo_page.css";
			@import "' . ROOT . 'javascript/table/css/demo_table.css";
			</style>
			<script type="text/javascript" language="javascript" src="' . ROOT . 'javascript/table/js/jquery.dataTables.js"></script>
			<script type="text/javascript" charset="utf-8">
				$(document).ready(function() {
					$(\'#comments table\').dataTable( {
			  "bInfo": false,
			  //"bProcessing": true,
						"aoColumns": [
							//{ "bSearchable": false, "sWidth": "10px", "sType": "html", "bSortable": false},
							{ "sType": "string" },
							{ "sType": "numeric" },
							{ "sType": "html" },
							{ "sWidth": "20px", "sType": "html", "bSortable": false}
						]
					} );
				} );
			</script>';

			lentele($conf['level'][$_GET['k']]['pavadinimas'] . " ($viso)", "<form id=\"usersch\" method=\"post\"><div id=\"comments\">" . $bla->render($info2) . "</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
			/*if ($viso > $limit) {
				lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));
			}*/
			unset($info2, $i, $bla);
		}
	}
}
if (isset($_GET['v']) && $_GET['v'] == 4) {
	$text = "<form name='rasti' method='post' id='rasti' action=''>{$lang['admin']['user_name']}: <input type='text' name='vardas'><input name='rasti' type='submit' value='{$lang['admin']['user_find']}'></form>";
	lentele("{$lang['admin']['user_find']}", $text);
	if (isset($_POST['rasti']) && isset($_POST['vardas'])) {
		$resultas = mysql_query1("SELECT *, INET_NTOA(ip) AS ip FROM `" . LENTELES_PRIESAGA . "users` WHERE nick LIKE " . escape("%" . $_POST['vardas'] . "%") . "LIMIT 0,100");
		if (sizeof($resultas) > 0) {
			foreach ($resultas as $row2) {
				$info3[] = array($lang['admin']['user_name'] => user($row2['nick'], $row2['id'], $row2['levelis']), "IP" => $row2['ip'], "{$lang['admin']['user_email']}" => preg_replace("#([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "<a href=\"javascript:mailto:mail('\\1','\\2');\">\\1_(at)_\\2</a>", $row2['email']), "{$lang['admin']['action']}" => "<a href='" . url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";r," . $row2['id']) . "'><img src='" . ROOT . "images/icons/pencil.png' border='0' class='middle' /></a> <a href='" . url("d," . $row2['id']) . "' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0' class='middle' /></a><a href='" . url("?id," . $_GET['id'] . ";a,{$admin_pagesid['banai']};b,1;ip," . $row2['ip']) . "' title='{$lang['admin']['badip']}'><img src='" . ROOT . "images/icons/delete.png' border='0' class='middle' /></a>");
			}
			$bla = new Table();
			lentele($lang['admin']['user_list'], $bla->render($info3));
		} else {
			klaida($lang['system']['warning'], "{$lang['admin']['user_notfound']}.");
		}
	}
}
?>
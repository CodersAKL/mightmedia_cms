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
unset($text, $extra);
/*$buttons = <<< HTML
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,2'">{$lang['system']['createcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,3'">{$lang['system']['editcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,5'">{$lang['system']['createsubcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,4'">{$lang['admin']['article_edit']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,7'">{$lang['admin']['article_create']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};v,6'">{$lang['admin']['article_unpublished']}</button>
HTML;*/
$buttons = "
<div class=\"btns\">
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,6\" class=\"btn\"><span><img src=\"images/icons/script__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_unpublished']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,7\" class=\"btn\"><span><img src=\"images/icons/script__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_create']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,4\" class=\"btn\"><span><img src=\"images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_edit']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,2\" class=\"btn\"><span><img src=\"images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,3\" class=\"btn\"><span><img src=\"images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,5\" class=\"btn\"><span><img src=\"images/icons/folders__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['forum_createsub']}</span></a>
</div>";
if (empty($_GET['v'])) {
	$_GET['v'] = 0;
}
lentele($lang['admin']['straipsnis'], $buttons);
unset($buttons);
include_once ("priedai/kategorijos.php");
kategorija("straipsniai", true);

if (isset($_GET['p'])) {
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET rodoma='TAIP' 
			WHERE `id`=" . escape($_GET['p']) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['article_activated']}.");
	} else {
		klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
	}
}
$tags = array("p" => 1, "br" => 0, "a" => 1, "img" => 0, "li" => 1, "ol" => 1, "ul" => 1, "b" => 1, "i" => 1, "em" => 1, "strong" => 1, "del" => 1, "ins" => 1, "u" => 1, "code" => 1, "pre" => 1, "blockquote" => 1, "hr" => 0, "span" => 1, "font" => 1, "h1" => 1, "h2" => 1, "h3" => 1, "table" => 1, "tr" => 1, "td" => 1, "th" => 1, "tbody" => 1, "div" => 1, "embed" => 1);
if (((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete'] && LEVEL == 1 && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['t']) && LEVEL == 1) {
	if (isset($url['t'])) {
		$trinti = (int)$url['t'];
	} elseif (isset($_POST['edit_new'])) {
		$trinti = (int)$_POST['edit_new'];
	}
	$ar = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE id=" . escape($trinti) . " LIMIT 1");
	if ($ar) {
		msg($lang['system']['done'], "{$lang['admin']['article_Deleted']}");
	} else {
		klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/straipsnis' AND kid=" . escape($trinti) . "");
	//redirect("?id,".$_GET['id'].";a,".$_GET['a'],"header");
} elseif (isset($_POST['action']) && isset($_POST['str']) && $_POST['action'] == $lang['admin']['edit']) {
	//apsauga nuo kenksmingo kodo
	include_once ('priedai/safe_html.php');

	$apr = safe_html(str_replace(array("&#39;"), array("'"), $_POST['apr']), $tags);
	$str = safe_html(str_replace(array("&#39;"), array("'"), $_POST['str']), $tags);
	$komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
	$rodoma = (isset($_POST['rodoma']) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE');
	$kategorija = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags($_POST['pav']);
	$id = ceil((int)$_POST['idas']);

	if ($komentaras == 'ne') {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape((int)$_GET['id']) . " AND kid=" . escape($id));
	}

	$resultas = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET
	    `kat` = " . escape($kategorija) . ",
			`pav` = " . escape($pavadinimas) . ",
			`t_text` = " . escape($apr) . ",
			`f_text` = " . escape($str) . ",
			`kom` = " . escape($komentaras) . ",
			`rodoma` = " . escape($rodoma) . "
			WHERE `id`=" . escape($id) . ";
			") or klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
	if ($resultas) {
		msg($lang['system']['done'], "{$lang['admin']['article_updated']}.");
	} else {
		klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
	}

} elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['article_create']) {
	//apsauga nuo kenksmingo kodo
	include_once ('priedai/safe_html.php');

	$apr = safe_html(str_replace(array("&#39;"), array("'"), $_POST['apr']), $tags);
	$str = safe_html(str_replace(array("&#39;"), array("'"), $_POST['str']), $tags);
	$komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
	$kategorija = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags($_POST['pav']);
	$rodoma = (isset($_POST['rodoma']) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE');
	$autorius = $_SESSION['username'];
	$autoriusid = $_SESSION['id'];
	if (empty($str) || empty($pavadinimas)) {
		$error = "{$lang['admin']['article_emptyfield']}.";
	}
	if (!isset($error)) {
		$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "straipsniai` SET
	    `kat` = " . escape($kategorija) . ",
			`pav` = " . escape($pavadinimas) . ",
			`t_text` = " . escape($apr) . ",
			`f_text` = " . escape($str) . ",
			`date` = " . time() . ",
			`autorius` = " . escape($autorius) . ",
			`autorius_id` = " . escape($autoriusid) . ",
			`kom` = " . escape($komentaras) . ",
			`rodoma` = " . escape($rodoma) . "");
		if ($result) {
			msg($lang['system']['done'], "{$lang['admin']['article_created']}");
		} else {
			klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
		}
	} else {
		klaida("{$lang['system']['error']}", $error);
	}
	unset($rodoma, $pavadinimas, $kategorija, $komentaras, $str, $apr, $_POST['action'], $result);
	redirect("?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "meta");

}


//straipsnio redagavimas
elseif (((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['h'])) {
	if (isset($url['h'])) {
		$redaguoti = (int)$url['h'];
	} elseif (isset($_POST['edit_new'])) {
		$redaguoti = (int)$_POST['edit_new'];
	}

	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `id`=" . escape($redaguoti) . " LIMIT 1");
	//$extra = mysql_fetch_assoc($extra);
}
if (isset($_GET['v'])) {
	$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND `path`=0 ORDER BY `id` DESC");
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {

			$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND path!=0 and `path` like '" . $row['id'] . "%' ORDER BY `id` ASC");
			if (count($sql2) > 0) {
				$subcat = '';
				while ($path = mysql_fetch_assoc($sql2)) {

					$subcat .= "->" . $path['pavadinimas'];
					$kategorijos[$row['id']] = $row['pavadinimas'];
					$kategorijos[$path['id']] = $row['pavadinimas'] . $subcat;


				}
			} else {
				$kategorijos[$row['id']] = $row['pavadinimas'];
			}


		}
	}
	/*else
	{
	$kategorijos[] = "{$lang['system']['nocategories']}";
	}*/
	$kategorijos[0] = "--";
}
$sql2 = mysql_query1("SELECT id, pav FROM  `" . LENTELES_PRIESAGA . "straipsniai` ORDER BY ID DESC");
if (sizeof($sql2) > 0) {
	foreach ($sql2 as $row2) {
		$straipsniai[$row2['id']] = $row2['pav'];
	}
} else {
	$straipsniai[] = "{$lang['admin']['article_no']}";
}
include_once ("priedai/class.php");
$bla = new forma();
if ($_GET['v'] == 4) {
	$redagavimas = array("Form" => array("action" => "?id,{$_GET['id']};a,{$_GET['a']};v,7", "method" => "post", "name" => "reg"), "{$lang['admin']['article']}:" => array("type" => "select", "value" => $straipsniai, "name" => "edit_new"), " " => array("type" => "submit", "name" => "action", "value" => "{$lang['admin']['edit']}"), "" => array("type" => "submit", "name" => "action", "value" => "{$lang['admin']['delete']}"));
	lentele($lang['admin']['article_edit'], $bla->form($redagavimas));
}

if ($_GET['v'] == 7 || isset($url['h'])) {
	if ($i = 1) {
		$ar = array("TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}");
		$straipsnis = array("Form" => array("action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "method" => "post", "name" => "reg"), "{$lang['admin']['article_title']}:" => array("type" => "text", "value" => input((isset($extra)) ? $extra['pav'] : ''), "name" => "pav", "class" => "input"), "" => array("type" => "hidden", "name" => "idas", "value" => (isset($extra['id']) ? input($extra['id']) : '')), "{$lang['admin']['article_comments']}:" => array("type" => "select", "value" => array('taip' => $lang['admin']['yes'], 'ne' => $lang['admin']['no']), "name" => "kom", "class" => "input", "class" => "input"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class" => "input", "selected" => (isset($extra['kat']) ?
			input($extra['kat']) : '')), "{$lang['admin']['article_shown']}:" => array("type" => "select", "value" => $ar, "name" => "rodoma", "class" => "input", "class" => "input", "selected" => (isset($extra['rodoma']) ? input($extra['rodoma']) : '')), "{$lang['admin']['article']}:" => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('apr' => 'Straipsnio įžanga', 'str' => 'straipsnis'), array('apr' => (isset($extra)) ? $extra['t_text'] : $lang['admin']['article_preface'], 'str' => (isset($extra)) ? $extra['f_text'] : $lang['admin']['article']))), (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['article_create'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['article_create']));
		if (isset($extra['id'])) {
			$naujiena[''] = array("type" => "text", "name" => "idas", "value" => (isset($extra['id']) ? input($extra['id']) : ''));
		}

		lentele($lang['admin']['article_create'], $bla->form($straipsnis));
	} else {
		klaida("{$lang['system']['warning']}", "{$lang['system']['nocategories']}.");
	}
} elseif ($_GET['v'] == 6) {

	$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE rodoma='NE'");
	if ($q) {

		include_once ("priedai/class.php");
		$bla = new Table();
		$info = array();

		foreach ($q as $sql) {
			$sql2 = mysql_query1("SELECT nick FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $sql['autorius'] . "'");

			$info[] = array("ID" => $sql['id'], "{$lang['admin']['article']}:" => '<a href="#" title="<b>' . $sql['pav'] . '</b>
			<br />' . $lang['admin']['article_author'] . ': <b>' . $sql2['nick'] . '</b><br />' . $lang['admin']['article_date'] . ': <b>' . date('Y-m-d H:i:s ', $sql['date']) . ' - ' . kada(date('Y-m-d H:i:s ', $sql['date'])) . '</b>" target="_blank">' . $sql['pav'] . '</a>', "{$lang['admin']['action']}:" => "<a href='?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id'] . "'title='{$lang['admin']['acept']}'><img src='images/icons/icon_accept.gif' border='0'></a> <a href='?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id'] . "' title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' border='0'></a> <a href='?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id'] . "' title='{$lang['admin']['edit']}'><img src='images/icons/pencil.png' border='0'></a>");

		}
		lentele($lang['admin']['article_unpublished'], $bla->render($info));

	} else {
		klaida($lang['system']['warning'], $lang['system']['no_items']);
	}

}
//unset($_POST);


?>
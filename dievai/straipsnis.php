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
unset($text, $extra);

$buttons = "
<div class=\"btns\">
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,6")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/script__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_unpublished']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,7")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/script__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_create']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,4")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['article_edit']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,2")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,3")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
	
</div>";
if (empty($_GET['v'])) {
	$_GET['v'] = 0;
}
lentele($lang['admin']['straipsnis'], $buttons);
unset($buttons);
include_once (ROOT."priedai/kategorijos.php");
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
//Naujienu trinimas
if(isset($_POST['articles_delete'])){
  foreach($_POST['articles_delete'] as $a=>$b){
    $trinti[]="`id`=".escape($b);
  }
  mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE ".implode($trinti, " OR ")."");
  header("Location:".$_SERVER['HTTP_REFERER']);
  exit;
}
if (/*((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete'] && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || */isset($url['t'])) {
	if (isset($url['t'])) {
		$trinti = (int)$url['t'];
	} /*elseif (isset($_POST['edit_new'])) {
		$trinti = (int)$_POST['edit_new'];
	}*/
	$ar = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE id=" . escape($trinti) . " LIMIT 1");
	if ($ar) {
		msg($lang['system']['done'], "{$lang['admin']['article_Deleted']}");
	} else {
		klaida("{$lang['system']['error']}", " <br><b>" . mysql_error() . "</b>");
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/straipsnis' AND kid=" . escape($trinti) . "");
	//redirect("?id,".$_GET['id'].";a,".$_GET['a'],"header");
} elseif (isset($_POST['action']) && isset($_POST['str']) && $_POST['action'] == $lang['admin']['edit']) {
	
	$apr = $_POST['apr'];
	$str = $_POST['str'];
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
	
	$apr = $_POST['apr'];
	$str = $_POST['str'];
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
	redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");

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
		
		$kategorijos=cat('straipsniai', 0);
	}
	
	$kategorijos[0] = "--";
}
$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "straipsniai` ORDER BY ID DESC");
/*if (sizeof($sql2) > 0) {
	foreach ($sql2 as $row2) {
		$straipsniai[$row2['id']] = $row2['pav'];
	}
} else {
	$straipsniai[] = "{$lang['admin']['article_no']}";
}*/
include_once (ROOT."priedai/class.php");
$bla = new forma();
if ($_GET['v'] == 4) {
		$table = new Table();
			foreach ($sql2 as $row){
        $info[] = array("<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('arch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"articles_delete[]\" />", 
        $lang['admin']['article'] => $row['pav'], 
        $lang['admin']['article_date'] => date('Y-m-d', $row['date']), 
        $lang['admin']['article_preface'] => trimlink(strip_tags($row['t_text']), 55),
        $lang['admin']['edit'] => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ). "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src=\"".ROOT."images/icons//cross.png\" border=\"0\"></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>"
        );
			}
			echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$(\'#news table\').dataTable( {
          "bInfo": false,
          "bProcessing": true,
					"aoColumns": [
						{ "bSearchable": false, "sWidth": "10px", "sType": "html", "bSortable": false},
						{ "sWidth": "10%", "sType": "string" },
						{ "sWidth": "10%", "sType": "date" },
						{ "sWidth": "30%", "sType": "html" },
						{ "sWidth": "20px", "sType": "html", "bSortable": false}
					]
				} );
			} );
		</script>';
			lentele($lang['admin']['article_edit'], "<form id=\"arch\" method=\"post\"><div id=\"news\">".$table->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
	/*$redagavimas = array("Form" => array("action" => url("?id,{$_GET['id']};a,{$_GET['a']};v,7"), "method" => "post", "name" => "reg"), "{$lang['admin']['article']}:" => array("type" => "select", "value" => $straipsniai, "name" => "edit_new"), " " => array("type" => "submit", "name" => "action", "value" => "{$lang['admin']['edit']}"), "" => array("type" => "submit", "name" => "action","extra"=>"onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"", "value" => "{$lang['admin']['delete']}"));
	lentele($lang['admin']['article_edit'], $bla->form($redagavimas));*/
}

if ($_GET['v'] == 7 || isset($url['h'])) {
	if ($i = 1) {
		$ar = array("TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}");
		$straipsnis = array("Form" => array("action" => url("?id," . $_GET['id'] . ";a," . $_GET['a']), "method" => "post", "name" => "reg"), "{$lang['admin']['article_title']}:" => array("type" => "text", "value" => input((isset($extra)) ? $extra['pav'] : ''), "name" => "pav", "class" => "input"), "" => array("type" => "hidden", "name" => "idas", "value" => (isset($extra['id']) ? input($extra['id']) : '')), "{$lang['admin']['article_comments']}:" => array("type" => "select", "value" => array('taip' => $lang['admin']['yes'], 'ne' => $lang['admin']['no']), "name" => "kom", "class" => "input", "class" => "input"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class" => "input", "selected" => (isset($extra['kat']) ?
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

		include_once (ROOT."priedai/class.php");
		$bla = new Table();
		$info = array();

		foreach ($q as $sql) {
			$sql2 = mysql_query1("SELECT `nick` FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`=" . escape($sql['autorius'] ). " LIMIT 1");

			$info[] = array("<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('arch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"articles_delete[]\" />",
			 $lang['admin']['article'] => '<a href="#" title="<b>' . $sql['pav'] . '</b>
			<br />' . $lang['admin']['article_author'] . ': <b>' . $sql2['nick'] . '</b>" target="_blank">' . $sql['pav'] . '</a>', 
			$lang['admin']['article_date'] => date('Y-m-d', $sql['date']), 
			$lang['admin']['article_preface'] => trimlink(strip_tags($sql['t_text']), 55),
			"{$lang['admin']['action']}:" => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id'] ). "'title='{$lang['admin']['acept']}'><img src='".ROOT."images/icons/tick_circle.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id'] ). "' title='{$lang['admin']['delete']}'><img src='".ROOT."images/icons/cross.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>");

		}
		echo '<style type="text/css" title="currentStyle">
			@import "'.ROOT.'javascript/table/css/demo_page.css";
			@import "'.ROOT.'javascript/table/css/demo_table.css";
		</style>
		<script type="text/javascript" language="javascript" src="'.ROOT.'javascript/table/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			$(document).ready(function() {
				$(\'#news table\').dataTable( {
          "bInfo": false,
          "bProcessing": true,
					"aoColumns": [
						{ "bSearchable": false, "sWidth": "10px", "sType": "html", "bSortable": false},
						{ "sWidth": "10%", "sType": "string" },
						{ "sWidth": "10%", "sType": "date" },
						{ "sWidth": "30%", "sType": "html" },
						{ "sWidth": "20px", "sType": "html", "bSortable": false}
					]
				} );
			} );
		</script>';
		lentele($lang['admin']['article_unpublished'], "<form id=\"arch\" method=\"post\"><div id=\"news\">".$bla->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");

	} else {
		klaida($lang['system']['warning'], $lang['system']['no_items']);
	}

}

?>
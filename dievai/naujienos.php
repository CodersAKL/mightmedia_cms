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
//admin_login();
unset($extra);

$buttons = "<div class=\"btns\"><a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,6")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/sticky_note__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_unpublished']}</span></a> <a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,1")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/sticky_note__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_create']}</span></a> <a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,4")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/sticky_note__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_edit']}</span></a>
<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,2")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,3")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
</div>";

lentele($lang['admin']['naujienos'], $buttons);
include_once (ROOT."priedai/kategorijos.php");
kategorija("naujienos", true);
$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `path`=0 AND `lang` = ".escape(lang())." ORDER BY `id` DESC");
if (sizeof($sql) > 0) {

	$kategorijos = cat('naujienos', 0);
}

$kategorijos[0] = "---";
if (isset($_GET['p'])) {
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET rodoma='TAIP' WHERE `id`=" . escape($_GET['p']) . ";");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['news_activated']}.");
	} else {
		klaida($lang['system']['error'], "{$lang['system']['error']}<br><b>" . mysql_error() . "</b>");
	}
}

//Naujienos trinimas
if (((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete']  && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['t'])) {
	if (isset($url['t'])) {
		$trinti = (int)$url['t'];
	} elseif (isset($_POST['edit_new'])) {
		$trinti = (int)$_POST['edit_new'];
	}
	$ar = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE id=" . escape($trinti) . " LIMIT 1");
	if ($ar) {
		msg($lang['system']['done'], $lang['admin']['news_deleted']);
	} else {
		klaida($lang['system']['error'], $lang['system']['error']);
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/naujienos' AND kid=" . escape($trinti) . "");
	redirect($_SERVER['HTTP_REFERER'],"meta");
}
//Naujienu trinimas
if(isset($_POST['news_delete'])){
  foreach($_POST['news_delete'] as $a=>$b){
    $trinti[]="`id`=".escape($b);
  }
  mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE ".implode($trinti, " OR ")."");
  header("Location:".$_SERVER['HTTP_REFERER']);
  exit;
}

//Naujienos redagavimas
elseif (/*((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) ||*/isset($url['h'])) {
	if (isset($url['h'])) {
		$redaguoti = (int)$url['h'];
	} /*elseif (isset($_POST['edit_new'])) {
		$redaguoti = (int)$_POST['edit_new'];
	}*/

	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id`=" . escape($redaguoti) . " LIMIT 1");
} elseif (isset($_POST['Kategorijos_id']) && isNum($_POST['Kategorijos_id']) && $_POST['Kategorijos_id'] > 0 && isset($_POST['Kategorija']) && $_POST['Kategorija'] == $lang['admin']['edit']) {
	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape((int)$_POST['Kategorijos_id']) . " LIMIT 1");
}

//Išsaugojam redaguojamą naujieną
elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['edit']) {
	$naujiena = $_POST['naujiena'];
	$placiau =$_POST['placiau'];
$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
	$kategorija = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags($_POST['pav']);
	$id = ceil((int)$_POST['news_id']);

	if ($komentaras == 'ne') {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape((int)$_GET['id']) . " AND kid=" . escape($id));
	}

	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`kategorija` = " . escape($kategorija) . ",
			`naujiena` = " . escape($naujiena) . ",
			`daugiau` = " . escape($placiau) . ",
			`kom` = " . escape($komentaras) . "
			WHERE `id`=" . escape($id) . ";
			");
	msg($lang['system']['done'], $lang['user']['edit_updated']);
}

//Išsaugojam naujieną
elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['news_create']) {
	$naujiena = $_POST['naujiena'];
	$placiau =  $_POST['placiau'];
	$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
	$pavadinimas = strip_tags($_POST['pav']);
	$kategorija = (int)$_POST['kategorija'];

	if (empty($naujiena) || empty($pavadinimas)) {
		$error = $lang['admin']['news_required'];
	}
	if (!isset($error)) {
		$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, kategorija, rodoma, lang) VALUES (" . escape($pavadinimas) . ", " . escape($naujiena) . ", " . escape($placiau) . ",  '" . time() . "', '" . $_SESSION['username'] . "', " . escape($komentaras) . ", " . escape($kategorija) . ", 'TAIP', ".escape(lang()).")");
		if ($result) {
			msg($lang['system']['done'], "{$lang['admin']['news_created']}");
		} else {
			klaida($lang['system']['error'], "<b>" . mysql_error() . "</b>");
		}
	} else {
		klaida($lang['system']['error'], $error);
	}


	unset($naujiena, $placiau, $komentaras, $pavadinimas, $result, $error, $_POST['action']);
	redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");

}

$sql_news = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = ".escape(lang())." ORDER BY ID DESC");


if (isset($_GET['v'])) {
	include_once (ROOT."priedai/class.php");
	$bla = new forma();

	if ($_GET['v'] == 4) {
		if (count($sql_news) > 0) {
			$table = new Table();
			foreach ($sql_news as $row){
        $info[] = array("<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('newsch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"news_delete[]\" />", 
        $lang['admin']['news_name'] => $row['pavadinimas'], 
        $lang['admin']['news_date'] => date('Y-m-d', $row['data']), 
        $lang['admin']['news_more'] => trimlink(strip_tags($row['naujiena']), 55),
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
			lentele($lang['admin']['edit'], "<form id=\"newsch\" method=\"post\"><div id=\"news\">".$table->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
		} else {
			klaida($lang['system']['warning'], $lang['system']['no_items']);
		}
	} elseif ($_GET['v'] == 1 || isset($_GET['h'])) {
		if ($i = 1) {
			$kom = array('taip' => $lang['admin']['yes'], 'ne' => $lang['admin']['no']);
			$naujiena = array("Form" => array("action" => url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ""), "method" => "post", "name" => "reg"), "{$lang['admin']['news_name']}:" => array("type" => "text", "value" => input((isset($extra)) ? $extra['pavadinimas'] : ''), "name" => "pav", "class" => "input"), $lang['admin']['komentarai'] => array("type" => "select", "selected" => input((isset($extra)) ? $extra['kom'] : ''), "value" => $kom, "name" => "kom", "class" => "input", "class" => "input"), "{$lang['admin']['news_category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class" => "input", "selected" => (isset($extra['kategorija']) ? input($extra['kategorija']) : '')), "{$lang['admin']['news_text']}:" => array("type" => "string", "value" =>
				editorius('jquery', 'standartinis', array('naujiena' => $lang['admin']['news_preface'], 'placiau' => $lang['admin']['news_more']), array('naujiena' => (isset($extra)) ? $extra['naujiena'] : $lang['admin']['news_preface'], 'placiau' => (isset($extra)) ? $extra['daugiau'] : $lang['admin']['news_more']))), (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['news_create'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['news_create']));

			if (isset($extra)) {
				$naujiena[''] = array("type" => "hidden", "name" => "news_id", "value" => (isset($extra) ? input($extra['id']) : ''));
			}

			lentele($lang['admin']['news_create'], $bla->form($naujiena));
		} else {
			klaida($lang['system']['warning'], $lang['system']['nocategories']);
		}
	} elseif ($_GET['v'] == 6) {

		$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE rodoma='NE'");
		if (sizeof($q) > 0) {

			include_once (ROOT."priedai/class.php");
			$bla = new Table();
			$info = array();
			foreach ($q as $sql) {
						$info[] = array(
						"<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('newsch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"news_delete[]\" />",
						"{$lang['admin']['news_name']}:" => '<a href="#" title="<b>' . $sql['pavadinimas'] . '</b>
			<br /><br />
			' . $lang['admin']['news_author'] . ': <b>' . $sql['autorius'] . '</b><br />
			" target="_blank">' . $sql['pavadinimas'] . '</a>', 
			 $lang['admin']['news_date'] => date('Y-m-d', $sql['data']), 
        $lang['admin']['news_more'] => trimlink(strip_tags($sql['naujiena']), 55),
			"{$lang['admin']['action']}:" => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id'] ). "'title='{$lang['admin']['acept']}'><img src='".ROOT."images/icons/tick_circle.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id'] ). "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='".ROOT."images/icons/cross.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>");

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
			lentele($lang['admin']['news_unpublished'], "<form id=\"newsch\" method=\"post\"><div id=\"news\">".$bla->render($info)."</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");

		} else {
			klaida($lang['system']['warning'], $lang['system']['no_items']);
		}
	}


}


unset($sql, $extra, $row);
//unset($_POST);


?>
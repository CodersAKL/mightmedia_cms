<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 366 $
 * @$Date: 2009-12-03 20:46:01 +0200 (Thu, 03 Dec 2009) $
 * */
if (!defined("OK") || !ar_admin(basename(__file__))) {
	redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}
unset($extra);
if (!isset($_GET['v']))	$_GET['v'] = 1;
//Puslapiavimui
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) $p = (int)$url['p']; else $p = 0;
$limit = 15;
//
$buttons = "<div class=\"btns\"><a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,6") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/sticky_note__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_unpublished']}</span></a> 
<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,1") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/sticky_note__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_create']}</span></a> 
<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,4") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/sticky_note__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['news_edit']}</span></a>
<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,2") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
<a href=\"" . url("?id,{$_GET['id']};a,{$_GET['a']};v,3") . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
</div>";

lentele($lang['admin']['naujienos'], $buttons);
include_once (ROOT . "priedai/kategorijos.php");
kategorija("naujienos", true);
$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `path`=0 AND `lang` = " . escape(lang()) . " ORDER BY `id` DESC");
if (sizeof($sql) > 0) {

	$kategorijos = cat('naujienos', 0);
}

$kategorijos[0] = "---";
if (isset($_GET['priimti'])) {
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET rodoma='TAIP' WHERE `id`=" . escape($_GET['priimti']) . ";");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['news_activated']}.");
	} else {
		klaida($lang['system']['error'], "{$lang['system']['error']}<br><b>" . mysql_error() . "</b>");
	}
}

//Naujienos trinimas
if (((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete'] && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['t'])) {
	if (isset($url['t'])) {
		$trinti = (int) $url['t'];
	} elseif (isset($_POST['edit_new'])) {
		$trinti = (int) $_POST['edit_new'];
	}
	$ar = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE id=" . escape($trinti) . " LIMIT 1");
	if ($ar) {
		msg($lang['system']['done'], $lang['admin']['news_deleted']);
	} else {
		klaida($lang['system']['error'], $lang['system']['error']);
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/naujienos' AND kid=" . escape($trinti) . "");
	redirect($_SERVER['HTTP_REFERER'], "meta");
}
//Naujienu trinimas
if (isset($_POST['news_delete'])) {
	foreach ($_POST['news_delete'] as $a => $b) {
		$trinti[] = escape($b);
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id` IN(" . implode(", ", $trinti) . ")");
	header("Location:" . $_SERVER['HTTP_REFERER']);
	exit;
	//echo implode($trinti, " OR ");
	//print_r($_POST['news_delete']);
}

//Naujienos redagavimas
if (/* ((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) || */isset($url['h'])) {
	if (isset($url['h'])) {
		$redaguoti = (int) $url['h'];
	} /* elseif (isset($_POST['edit_new'])) {
	  $redaguoti = (int)$_POST['edit_new'];
	  } */

	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id`=" . escape($redaguoti) . " LIMIT 1");
} elseif (isset($_POST['Kategorijos_id']) && isNum($_POST['Kategorijos_id']) && $_POST['Kategorijos_id'] > 0 && isset($_POST['Kategorija']) && $_POST['Kategorija'] == $lang['admin']['edit']) {
	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape((int) $_POST['Kategorijos_id']) . " LIMIT 1");
}

//Išsaugojam redaguojamą naujieną
elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['edit']) {
	$naujiena = explode('===page===', $_POST['naujiena']);
	//$placiau =  explode('===page===',$_POST['naujiena']);
	$izanga = $naujiena[0];
	$placiau = (empty($naujiena[1]) ? '' : $naujiena[1]);
	$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
	$rodymas = (isset($_POST['rodoma']) ? $_POST['rodoma'] : 'TAIP');
	$kategorija = (int) $_POST['kategorija'];
	$pavadinimas = strip_tags($_POST['pav']);
	$id = ceil((int) $_POST['news_id']);
	$sticky = (isset($_POST['sticky']) ? 1 : 0);
	if ($komentaras == 'ne') {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape((int) $_GET['id']) . " AND kid=" . escape($id));
	}

	mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`kategorija` = " . escape($kategorija) . ",
			`naujiena` = " . escape($izanga) . ",
			`daugiau` = " . escape($placiau) . ",
			`kom` = " . escape($komentaras) . ",
			`rodoma` = " . escape($rodymas) . ",
			`sticky` = " . escape($sticky) . " 
			WHERE `id`=" . escape($id) . ";
			");
	msg($lang['system']['done'], $lang['user']['edit_updated']);
}

//Išsaugojam naujieną
elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['news_create']) {
	$naujiena = explode('===page===', $_POST['naujiena']);
	$izanga = $naujiena[0];
	$placiau = empty($naujiena[1]) ? '' : $naujiena[1];
	$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
	$rodymas = (isset($_POST['rodoma']) ? $_POST['rodoma'] : 'TAIP');
	$pavadinimas = strip_tags($_POST['pav']);
	$kategorija = (int) $_POST['kategorija'];
	$sticky = (isset($_POST['sticky']) ? 1 : 0);
	if (empty($naujiena) || empty($pavadinimas)) {
		$error = $lang['admin']['news_required'];
	}
	if (!isset($error)) {
		$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, rodoma, kategorija, rodoma, lang, sticky) VALUES (" . escape($pavadinimas) . ", " . escape($izanga) . ", " . escape($placiau) . ",  '" . time() . "', '" . $_SESSION['username'] . "', " . escape($komentaras) . ", " . escape($rodymas) . ", " . escape($kategorija) . ", 'TAIP', ".escape(lang()).", ".escape($sticky).")");
		$last_news = mysql_query1("SELECT `id` FROM `" . LENTELES_PRIESAGA . "naujienos` ORDER BY `id` DESC LIMIT 1");
		if(isset($_POST['letter'])) {

			//TODO:Reikalingi email templeytai
			require_once(ROOT . 'priedai/class.phpmailer-lite.php');
			include(ROOT . 'stiliai/'.$conf['Stilius'].'/sfunkcijos.php');
			$mail = new PHPMailerLite();
			$mail->IsMail();
			$mail->CharSet = 'UTF-8';
			$mail->SingleTo = true;
			$nuoroda_sk = "" . url("?id,{$conf['puslapiai']['naujienos.php']['id']};k,{$last_news['id']}") . "";
			$nuoroda_atsis = "" . url("?id," . $conf['puslapiai']['naujienlaiskiai.php']['id']) . "";
			$body = naujienlaiskis($pavadinimas,$izanga,$nuoroda_sk,$nuoroda_atsis);
			$mail->SetFrom($admin_email, $conf['Pavadinimas']);
			$mail->Subject = strip_tags($conf['Pavadinimas']) . " " . $pavadinimas;
			$mail->MsgHTML($body);
			$sql = mysql_query1("SELECT `email` FROM `" . LENTELES_PRIESAGA . "newsgetters`");
			foreach ($sql as $row) {
				if ($mail->ValidateAddress($row['email'])) {
					$name = explode('@', $row['email']);
					//$mail->AddAddress($row['email']);
					$mail->AddBCC($row['email'],$name[0]);
				}
			}
			$mail->Send();
			if ($mail->IsError()) {
				klaida($lang['news']['newsletter?'].' - error',$mail->ErrorInfo);
			}
		}


		if ($result) {
			msg($lang['system']['done'], $lang['admin']['news_created']);
		} else {
			klaida($lang['system']['error'], "<b>" . mysql_error() . "</b>");
		}
	} else {
		klaida($lang['system']['error'], $error);
	}


	unset($naujiena, $placiau, $rodymas, $komentaras, $pavadinimas, $result, $error, $_POST['action']);
	redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");
}

//print_r($sql_news);
if (isset($_GET['v'])) {
	include_once (ROOT . "priedai/class.php");
	$bla = new forma();

	if ($_GET['v'] == 4) {
///FILTRAVIMAS
$viso = kiek("naujienos", "WHERE `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
$sql_news = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = ".escape(lang())." ".(isset($_POST['pavadinimas'])? "AND (`pavadinimas` LIKE " . escape("%" . $_POST['pavadinimas'] . "%") . " ".(!empty($_POST['data'])? " AND `data` <= " .strtotime($_POST['data']) . "":"")." ".(!empty($_POST['naujiena'])? " AND `naujiena` LIKE " . escape("%" . $_POST['naujiena'] . "%") . "":"").")"  : "")." AND rodoma='TAIP' ORDER BY sticky DESC, id DESC LIMIT {$p},{$limit}");
//
if (count($sql_news) > 0) {
$table = new Table();
//
if(isset($_POST['pavadinimas']) && $_POST['data'] && $_POST['naujiena']) {
$val = array($_POST['pavadinimas'], $_POST['data'], $_POST['naujiena']);
} else {
$val = array("","","");
}
$info[] = array("<form method=\"post\">",
$lang['admin']['news_name'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
$lang['admin']['news_date'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"data\" />",
$lang['admin']['news_more'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"naujiena\" />",
 " " => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" /></form>");
//FILTRAVIMAS
	foreach ($sql_news as $row) {
$info[] = array(
"<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('newsch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"news_delete[]\" />",
$lang['admin']['news_name'] => '<span style="cursor:pointer;" title="<b>' . $row['pavadinimas'] . '</b><br />' . $lang['admin']['news_author'] . ': <b>' . $row['autorius'] . '</b>">' .trimlink(strip_tags($row['pavadinimas']), 55) . '<span/></a>',
$lang['admin']['news_date'] => date('Y-m-d', $row['data']),
$lang['admin']['news_more'] => trimlink(strip_tags($row['naujiena']), 55),
"" => "<a href='" . url("?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id']) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a> <a href='" . url("?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id']) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" border=\"0\"></a>"
				);
			}
lentele($lang['admin']['edit'], "<form id=\"newsch\" method=\"post\">" . $table->render($info) . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
	
if ($viso > $limit)
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));

	} else {
			klaida($lang['system']['warning'], $lang['system']['no_items']);
		}
	} elseif ($_GET['v'] == 1 || isset($_GET['h'])) {
		$kom = array('taip' => $lang['admin']['yes'], 'ne' => $lang['admin']['no']);
		$rodoma = array('TAIP' => $lang['admin']['yes'], 'NE' => $lang['admin']['no']);
		$naujiena = array(
			"Form" => array("action" => url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ""), "method" => "post", "name" => "reg"),
			$lang['admin']['news_name'] => array("type" => "text", "value" => input((isset($extra)) ? $extra['pavadinimas'] : ''), "name" => "pav", "class" => "input"),
			$lang['admin']['komentarai'] => array("type" => "select", "selected" => input((isset($extra)) ? $extra['kom'] : ''), "value" => $kom, "name" => "kom", "class" => "input"),
			$lang['admin']['article_shown'] => array("type" => "select", "selected" => input((isset($extra)) ? $extra['rodoma'] : ''), "value" => $rodoma, "name" => "rodoma", "class" => "input"),
			$lang['admin']['news_category'] => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "selected" => (isset($extra['kategorija']) ? input($extra['kategorija']) : '0')),
			//more
			"<a href=\"javascript:rodyk('more')\">{$lang['admin']['news_moreoptions']}</a>" => array("type" => "string", "value" => "<div id=\"more\" style=\"display: none;\">
			" . ((!isset($extra) && isset($conf['puslapiai']['naujienlaiskiai.php']['id'])) ? $lang['news']['newsletter?'] . " <input type=\"checkbox\" name=\"letter\" /><br />" : '') . " 
			" . $lang['admin']['news_sticky'] . " <input type=\"checkbox\" name=\"sticky\" " . ((isset($extra) && $extra['sticky'] == 1) ? 'checked' : '') . " /></div>"),
			//(isset($conf['puslapiai']['naujienlaiskiai.php']['id'])?$lang['news']['newsletter?']:'') => isset($conf['puslapiai']['naujienlaiskiai.php']['id'])?array("type" => "checkbox", "name" => "letter"):'',
			//$lang['admin']['news_sticky'] => array("type" => "checkbox", "name" => "sticky"),
			//more end;
			$lang['admin']['news_text'] => array("type" => "string", "value" => editor('jquery', 'standartinis', array('naujiena' => $lang['admin']['news_preface']), array('naujiena' => (isset($extra) ? $extra['naujiena'] . (empty($extra['daugiau']) ? '' : "\n===page===\n" . $extra['daugiau']) : $lang['admin']['news_preface'])))),
			(isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['news_create'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['news_create'])
		);

		if (isset($extra)) {
			$naujiena[''] = array("type" => "hidden", "name" => "news_id", "value" => (isset($extra) ? input($extra['id']) : ''));
		}
		/* if(!isset($extra) && isset($conf['puslapiai']['naujienlaiskiai.php']['id'])) {
		  $naujiena[$lang['news']['newsletter?']] = array("type" => "checkbox", "name" => "letter");
		  } */
		lentele((!isset($extra) ? $lang['admin']['news_create'] : $lang['admin']['news_edit']), $bla->form($naujiena));
	} elseif ($_GET['v'] == 6) {
///FILTRAVIMAS
$viso = kiek("naujienos", "WHERE `rodoma`='NE' AND `lang` = ".escape(lang())."");
$q = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = ".escape(lang())." ".(isset($_POST['pavadinimas'])? "AND (`pavadinimas` LIKE " . escape("%" . $_POST['pavadinimas'] . "%") . " ".(!empty($_POST['data'])? " AND `data` <= " .strtotime($_POST['data']) . "":"")." ".(!empty($_POST['naujiena'])? " AND `naujiena` LIKE " . escape("%" . $_POST['naujiena'] . "%") . "":"").")"  : "")." AND rodoma='NE' ORDER BY sticky DESC, id DESC LIMIT {$p},{$limit}");
//
if (sizeof($q) > 0) {
include_once (ROOT . "priedai/class.php");
$bla = new Table();
$info = array();
//
if(isset($_POST['pavadinimas']) && $_POST['data'] && $_POST['naujiena']) {
$val = array($_POST['pavadinimas'], $_POST['data'], $_POST['naujiena']);
} else {
$val = array("","","");
}
$info[] = array("<form method=\"post\">",
$lang['admin']['news_name'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
$lang['admin']['news_date'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"data\" />",
$lang['admin']['news_more'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"naujiena\" />",
 " " => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" /></form>");
//FILTRAVIMAS

foreach ($q as $sql) {
$info[] = array(
"<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('newsch');\" />" => "<input type=\"checkbox\" value=\"{$sql['id']}\" name=\"news_delete[]\" />",
$lang['admin']['news_name'] => '<span style="cursor:pointer;" title="<b>' . $sql['pavadinimas'] . '</b><br />' . $lang['admin']['news_author'] . ': <b>' . $sql['autorius'] . '</b>">' .trimlink(strip_tags($sql['pavadinimas']), 55) . '<span/></a>',
$lang['admin']['news_date'] => date('Y-m-d', $sql['data']),
$lang['admin']['news_more'] => trimlink(strip_tags($sql['naujiena']), 55),
" " => "<a href='" . url("?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id']) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' border='0'></a> <a href='" . url("?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id']) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a> <a href='" . url("?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id']) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a>");
			}
lentele($lang['admin']['news_unpublished'], "<form id=\"newsch\" method=\"post\">" . $bla->render($info) . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>");
if ($viso > $limit)
		lentele($lang['system']['pages'], puslapiai($p, $limit, $viso, 10));

		} else {
			klaida($lang['system']['warning'], $lang['system']['no_items']);
		}
	}
}
unset($sql, $extra, $row);
//unset($_POST);
?>
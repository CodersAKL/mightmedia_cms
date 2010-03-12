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
	header('location: ?');
	exit();
}

$buttons = "
<div class=\"btns\">
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,6")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/disk__exclamation.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['download_unpublished']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,1")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/disk__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['download_Create']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,7")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/disk__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['download_edit']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,2")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"".url("?id,{$_GET['id']};a,{$_GET['a']};v,3")."\" class=\"btn\"><span><img src=\"".ROOT."images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
	
</div>";
if (empty($url['s'])) {
	$url['s'] = 0;
}
if (empty($url['v'])) {
	$url['v'] = 0;
}

lentele($lang['admin']['siustis'], $buttons);

unset($buttons);
include_once (ROOT."priedai/kategorijos.php");
kategorija("siuntiniai", true);
$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `path`=0 ORDER BY `id` DESC");
	if (sizeof($sql) > 0) {
    $kategorijoss=cat('siuntiniai', 0);
	}
$kategorijos[0] = "--";
if (isset($_GET['p'])) {
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET rodoma='TAIP' 
			WHERE `id`=" . escape($_GET['p']) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['download_activated']}.");
	} else {
		klaida($lang['system']['error'], " <br /><b>" . mysql_error() . "</b>");
	}
}
if (((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete']  && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['t'])) {
	if (isset($url['t'])) {
		$trinti = (int)$url['t'];
	} elseif (isset($_POST['edit_new'])) {
		$trinti = (int)$_POST['edit_new'];
	}
	$row = mysql_query1("SELECT `file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = " . escape($trinti) . " LIMIT 1");

	if (isset($row['file']) && !empty($row['file'])) {
		@unlink("siuntiniai/" . $row['file']);
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE id=" . escape($trinti) . " LIMIT 1");
	if (mysql_affected_rows() > 0) {
		msg($lang['system']['done'], "{$lang['admin']['download_deleted']}");
	} else {
		klaida($lang['system']['error'], "Trinimo klaida");
	}
	mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/siustis' AND kid=" . escape($trinti) . "");
	//redirect("?id,".$_GET['id'].";a,".$_GET['a'],"header");
}

//Siuntinio redagavimas
elseif (((isset($_POST['edit_new']) && isNum($_POST['edit_new']) && $_POST['edit_new'] > 0)) || isset($url['h'])) {
	if (isset($url['h'])) {
		$redaguoti = (int)$url['h'];
	} elseif (isset($_POST['edit_new'])) {
		$redaguoti = (int)$_POST['edit_new'];
	}
	$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `id`=" . escape($redaguoti) . " LIMIT 1");
	//$extra = mysql_fetch_assoc($extra);
} elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['edit']) {
	$apie =  $_POST['Aprasymas'];
	$pavadinimas = strip_tags($_POST['Pavadinimas']);
	$kategorija = (int)$_POST['cat'];
	$file = strip_tags($_POST['failas2']);
	$id = ceil((int)$_POST['news_id']);


	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`categorija` = " . escape($kategorija) . ",
			`apie` = " . escape($apie) . ",
			`file` = " . escape($file) . "
			WHERE `id`=" . escape($id) . ";
			");
	if ($result) {
		msg($lang['system']['done'], "{$lang['admin']['download_updated']}");
	} else {
		klaida($lang['system']['error'], "<br /><b>" . mysql_error() . "</b>");
	}

} elseif (isset($_POST['action']) && $_POST['action'] == $lang['admin']['download_create']) {
	
	function upload($file, $file_types_array = array("BMP", "JPG", "PNG", "PSD", "ZIP", "RAR", "GIF"), $max_file_size = 1048576, $upload_dir = "siuntiniai") {
		global $lang;
		if ($_FILES["$file"]["name"] != "") {
			$origfilename = $_FILES["$file"]["name"];
			$filename = explode(".", $_FILES["$file"]["name"]);
			$filenameext = strtolower($filename[count($filename) - 1]);
			unset($filename[count($filename) - 1]);
			$filename = implode(".", $filename);
			$filename = substr($filename, 0, 60) . "." . $filenameext;
			$file_ext_allow = false;
			for ($x = 0; $x < count($file_types_array); $x++) {
				if ($filenameext == $file_types_array[$x]) {
					$file_ext_allow = true;
				}
			} // for
			if ($file_ext_allow) {
				if ($_FILES["$file"]["size"] < $max_file_size) {
					$ieskom = array("?", "&", "=", " ", "+", "-", "#");
					$keiciam = array("", "", "", "_", "", "", "");
					$filename = str_replace($ieskom, $keiciam, $filename);
					if (is_file($upload_dir . $filename)) {
						$filename = time() . "_" . $filename;
					}
					move_uploaded_file($_FILES["$file"]["tmp_name"], $upload_dir . $filename);
					chmod($upload_dir . $filename, 0777);
					if (file_exists($upload_dir . $filename)) {
						$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape($_POST['Pavadinimas']) . "," . escape($filename) . ", " . escape($_POST['Aprasymas']) . "," . escape($_SESSION['id']) . ", '" . time() . "', " . escape($_POST['cat']) . ", 'TAIP')");

						if ($result) {
							msg($lang['system']['done'], $lang['admin']['download_created']);
						} else {
							klaida($lang['system']['error'], $lang['system']['error']);
						}
					} else {
						klaida($lang['system']['error'], '<font color="#FF0000">' . $filename . '</font>');
					}
				} else {
					klaida($lang['system']['error'], '<font color="#FF0000">' . $filename . '</font> ' . $lang['admin']['download_toobig'] . '');
				}
			} // if
			else {
				klaida($lang['system']['error'], '<font color="#FF0000">' . $filename . '</font> ' . $lang['admin']['download_badfile'] . '');
			}
		}
	}
	if (isset($_FILES['failas']) && !empty($_FILES['failas'])) {
		if (is_uploaded_file($_FILES['failas']['tmp_name'])) {

			upload("failas", array("jpg", "bmp", "png", "psd", "zip", "rar", "mrc", "dll"), 1048576, "siuntiniai/");
			

		}
	}
	if (isset($_POST['failas2']) && !empty($_POST['failas2'])) {
		$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape($_POST['Pavadinimas']) . "," . escape($_POST['failas2']) . ", " . escape($_POST['Aprasymas']) . "," . escape($_SESSION['id']) . ", '" . time() . "', " . escape($_POST['cat']) . ", 'TAIP')");

		if ($result) {
			msg($lang['system']['done'], $lang['admin']['download_created']);
		} else {
			klaida($lang['system']['error'], $lang['system']['error']);
		}
	}
	unset($_FILES['failas'], $filename, $result, $_POST['Pavadinimas'], $_POST['Aprasymas'], $_POST['action'], $_POST['failas2']);
	redirect("?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "meta");

}


//Trinam kategoriją

unset($naujiena, $placiau, $komentaras, $pavadinimas, $result, $error, $pav);


if (isset($_GET['v'])) {
	include_once (ROOT."priedai/class.php");
	$bla = new forma();
	if ($_GET['v'] == 7) {
		$sql2 = mysql_query1("SELECT id, pavadinimas FROM  `" . LENTELES_PRIESAGA . "siuntiniai` ORDER BY ID DESC");
		if (sizeof($sql2) > 0) {
			foreach ($sql2 as $row2) {
				$siuntiniaii[$row2['id']] = $row2['pavadinimas'];
			}
		} else {
			$siuntiniaii[] = $lang['admin']['download_no'];
		}
		$redagavimas = array("Form" => array("action" => "?id,{$_GET['id']};a,{$_GET['a']};v,1", "method" => "post", "name" => "reg"), "{$lang['admin']['download_download']}:" => array("type" => "select", "value" => $siuntiniaii, "name" => "edit_new"), "{$lang['admin']['edit']}:" => array("type" => "submit", "name" => "action", "value" => "{$lang['admin']['edit']}"), "{$lang['admin']['delete']}:" => array("type" => "submit", "name" => "action","extra"=>"onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"", "value" => "{$lang['admin']['delete']}"));
		lentele($lang['admin']['download_edit'], $bla->form($redagavimas));
	} elseif ($_GET['v'] == 1 || isset($_GET['h'])) {
		if (!isset($nocat)) {
			if (!isset($_POST['tipas']) && !isset($extra)) {
				$type[1] = $lang['admin']['download_uploaded'];
				$type[2] = $lang['admin']['link'];

				$tipas = array("Form" => array("action" => "?id,{$_GET['id']};a,{$_GET['a']};v,1", "method" => "post", "name" => "type"), "{$lang['admin']['download_type']}:" => array("type" => "select", "value" => $type, "name" => "tipas"), "{$lang['admin']['download_select']}:" => array("type" => "submit", "name" => "action", "value" => "{$lang['admin']['download_select']}"));
				lentele($lang['admin']['download_type'], $bla->form($tipas));
			}
			if (isset($_POST['tipas']) || isset($extra)) {
				//if(!isset($_post['tipas'])){$_POST['tipas']=3;}
				$forma = array("Form" => array("enctype" => "multipart/form-data", "action" => "?id," . $_GET['id'] . ";a," . $_GET['a'] . "", "method" => "post", "name" => "action"), (!isset($extra) && @$_POST['tipas'] != 2) ? "{$lang['admin']['download_file']}:" : "" => array("name" => "failas", "type" => (isset($extra) || $_POST['tipas'] != 2) ? "file" : "hidden", "value" => "", "class" => "input"), (isset($extra) || $_POST['tipas'] == 2) ? "{$lang['admin']['download_fileurl']}:" : "" => array("name" => "failas2", "type" => (isset($extra) || $_POST['tipas'] == 2) ? "text" : "hidden", "value" => (isset($extra['pavadinimas'])) ? input($extra['file']) : '', "class" => "input"), "{$lang['admin']['download_download']}:" => array("type" => "text", "value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) :
					'', "name" => "Pavadinimas", "class" => "input"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "class" => "input", "selected" => (isset($extra['categorija']) ? input($extra['categorija']) : '')), "{$lang['admin']['download_about']}:" => array("type" => "string", "value" => editorius('spaw', 'mini', 'Aprasymas', (isset($extra['apie'])) ? $extra['apie'] : '')), //"Paveiksliukas:"=>array("type"=>"text","value"=>(isset($extra['foto']))?input($extra['foto']):'http://',"name"=>"Pav","class"=>"input"),
					(isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['download_create'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['download_create']), );
				if (isset($extra)) {
					$forma[''] = array("type" => "hidden", "name" => "news_id", "value" => (isset($extra) ? input($extra['ID']) : ''));
				}
				lentele($lang['admin']['download_create'], $bla->form($forma));
			}
		} else {
			klaida($lang['system']['warning'], $lang['system']['nocategories']);
		}
	} elseif ($_GET['v'] == 6) {

		$q = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE rodoma='NE'");
		if ($q) {

			include_once (ROOT."priedai/class.php");
			$bla = new Table();
			$info = array();

			foreach ($q as $sql) {
				$sql2 = mysql_query1("SELECT nick FROM `" . LENTELES_PRIESAGA . "users` WHERE id=" . escape($sql['autorius']) . " LIMIT 1");
				if (isset($sql2['nick'])) {
					$autorius = $sql2['nick'];
				} else {
					$autorius = $lang['system']['guest'];
				}
				$info[] = array("ID" => $sql['ID'], "{$lang['admin']['download_download']}:" => '<a href="#" title="<b>' . $sql['pavadinimas'] . '</b>
			<br /><br />
			' . $lang['admin']['download_author'] . ': <b>' . $autorius . '</b><br />
			' . $lang['admin']['download_about'] . ': <b>' . $sql['apie'] . '</b><br />
			' . $lang['admin']['download_date'] . ': <b>' . date('Y-m-d H:i:s ', $sql['data']) . ' - ' . kada(date('Y-m-d H:i:s ', $sql['data'])) . '</b>" target="_blank">' . $sql['pavadinimas'] . '</a>', "{$lang['admin']['action'] }:" => "<a href='".url("?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['ID'] ). "'title='{$lang['admin']['acept']}'><img src='".ROOT."images/icons/tick_circle.png' alt='a' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['ID'] ). "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='".ROOT."images/icons/cross.png' border='0'></a> <a href='".url("?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['ID'] ). "' title='{$lang['admin']['edit']}'><img src='".ROOT."images/icons/pencil.png' border='0'></a>");

			}
			lentele($lang['admin']['download_unpublished'], $bla->render($info));

		} else {
			klaida($lang['system']['warning'], $lang['system']['no_items']);
		}
	}

}


unset($sql, $extra, $row);


?>
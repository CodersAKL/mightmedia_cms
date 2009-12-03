
<script type="text/javascript">
   $(document).ready(function() {
    $("#kaire").sortable({
      handle : '.handle',
      axis: 'y',
      update : function () {
		var order = $('#kaire').sortable('serialize');
		$("#la").show("slow");
		$("#la").hide("slow");
		$.post("<?php echo "?" . $_SERVER['QUERY_STRING']; ?>",{order:order});

		}
    });
	$("#desine").sortable({
      handle : '.handle',
      axis: 'y',
      update : function () {
		var order = $('#desine').sortable('serialize');
		$("#la").show("slow");
		$("#la").hide("slow");
		$.post("<?php echo "?" . $_SERVER['QUERY_STRING']; ?>",{order:order});

		}
    });
});
</script>
<script type="text/javascript" src="javascript/jquery/jquery.asmselect.js"></script>

	<script type="text/javascript">

		
		$(document).ready(function() {
			$("select[multiple]").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: true,
				removeLabel: '<?php echo $lang['system']['delete']; ?>',					
			   highlightAddedLabel: '<?php echo $lang['admin']['added']; ?>: ',
			   highlightRemovedLabel: '<?php echo $lang['sb']['deleted']; ?>: ',	
				sortable: true
			});
			
		}); 
			
		 

	</script>
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

if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
	header('location: http://' . $_SERVER["HTTP_HOST"]);
	exit;
}


//lentele($lang['admin']['paneles'], $buttons);
if (isset($_POST['order'])) {
	$array = str_replace("&", ",", $_POST['order']);
	$array = str_replace("listItem[]=", "", $array);
	$array = explode(",", $array);
	//$array=array($array);
	//print_r($array);
	//$sql=array();
	foreach ($array as $position => $item):
		
		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";
		
		$where .= "$item,";
	endforeach;
	$where = rtrim($where, ", ");
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1($sqlas);
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' ORDER BY `place` ASC");

} else {
	$text = "
<div class=\"btns\">
	<a href=\"?id," . $url['id'] . ";a,{$_GET['a']};n,1\" class=\"btn\"><span><img src=\"images/icons/script__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['panel_select']}</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};n,2\" class=\"btn\"><span><img src=\"images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['panel_create']}</span></a>
</div>";
	$lygiai = array_keys($conf['level']);

	foreach ($lygiai as $key) {
		$teises[$key] = $conf['level'][$key]['pavadinimas'];
	}
	$teises[0] = $lang['admin']['for_guests'];
	if (isset($_POST['Naujaa_pnl']) && $_POST['Naujaa_pnl'] == $lang['admin']['panel_create']) {
		// Nurodote failo pavadinimą:
		$failas = "paneles/" . preg_replace("/[^a-z0-9-]/", "_", strtolower($_POST['pav'])) . ".php";
		$tekstas = str_replace(array('$', 'HTML', '<br>'), array('\$', 'html', '<br/>'), $_POST['pnl']);
		//$tekstas = str_replace('HTML', 'html', $tekstas);

		// Nurodote įrašą kuris bus faile kai jį sukurs:
		//$apsauga = random_name();
		$irasas = '<?php
$text =
<<<HTML
' . stripslashes($tekstas) . '
HTML;
?>';

		//Irasom faila
		$fp = fopen($failas, "w+");
		fwrite($fp, $irasas);
		fclose($fp);
		chmod($failas,0777);

		// Rezultatas:
	//	msg($lang['system']['done'], "{$lang['admin']['panel_created']}.");
	redirect("?id,{$_GET['id']};a,{$_GET['a']};n,1","header");
	}
	if (isset($url['n']) && $url['n'] == 2) {
		$psl = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "Naujaa_pnl"), "{$lang['admin']['panel_name']}:" => array("type" => "text", "value" => "Naujas blokas", "name" => "pav", "class" => "input"), "{$lang['admin']['panel_text']}:" => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('pnl' => 'pnl'), false), "name" => "pnl", "class" => "input", "rows" => "8", "class" => "input"), "" => array("type" => "submit", "name" => "Naujaa_pnl", "value" => "{$lang['admin']['panel_create']}"));
		include_once ("priedai/class.php");
		$bla = new forma();
		lentele("{$lang['admin']['panel_new']}", $bla->form($psl, "{$lang['admin']['panel_new']}"));
	}
	if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`= " . escape((int)$url['d']) . " LIMIT 1");
		delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' ORDER BY `place` ASC");
		redirect("?id," . $url['id'] . ";a,{$_GET['a']}", "header");
	}
	//naujos paneles sukurimas
	elseif (isset($url['n']) && $url['n'] == 1) {
		if (isset($_POST['Nauja_panele']) && $_POST['Nauja_panele'] == $lang['admin']['panel_create']) {
			$panel = input($_POST['Panel']);
			$file = input(basename($_POST['File']));
			if (!file_exists("paneles/" . $file)) {
				klaida($lang['system']['error'], "<font color='red'>" . $file . "</font>");
			} else {
				if (empty($panel) || $panel == '') {
					$panel = basename($file, ".php");
				}
				$align = input($_POST['Align']);
				if (strlen($align) > 1) {
					$align = 'L';
				}
				$show = input($_POST['Show']);
				if (strlen($show) > 1) {
					$align = 'Y';
				}
				$teisess = serialize((isset($_POST['Teises'])?$_POST['Teises']:0));
				$sql = "INSERT INTO `" . LENTELES_PRIESAGA . "panel` (`panel`, `file`, `place`, `align`, `show`, `teises`) VALUES (" . escape($panel) . ", " . escape($file) . ", '0', " . escape($align) . ", " . escape($show) . ", " . escape($teisess) . ")";
				mysql_query1($sql);
				delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' ORDER BY `place` ASC");
				redirect("?id," . $url['id'] . ";a,{$_GET['a']}", "header");
			}
		}
		$failai = getFiles('paneles/');
		//print_r($failai);

		foreach ($failai as $file) {
			if ($file['type'] == 'file') {
				$sql = mysql_query1("SELECT file FROM `" . LENTELES_PRIESAGA . "panel` WHERE file=" . escape(basename($file['name'])) . " LIMIT 1");
				//echo $sql['file'];
				if ($sql['file'] != basename($file['name'])) {
					//$paneles[basename($file['name'])] = basename($file['name']) . ": " . $file['sizetext'] . "\n";
					$paneles[basename($file['name'])] =(isset($lang['blocks'][$file['name']])?$lang['blocks'][$file['name']]:nice_name(basename($file['name'],'.php')));
				}
			}
		}

		if (!isset($paneles) || count($paneles) < 1) {
			klaida($lang['system']['error'], "<h3>{$lang['admin']['panel_no']}.</h3>");
		} else {
			/*	$box = "";
			foreach ($teises as $name => $check) {
			$box .= "<label><input type=\"checkbox\" name=\"Teises[]\" value=\"$name\"/> $check</label><br /> ";
			}*/
			$panele = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel"), "{$lang['admin']['panel_title']}:" => array("type" => "text", "value" => "{$lang['admin']['panel_new']}", "name" => "Panel", "class" => "input"), "{$lang['admin']['panel_name']}:" => array("type" => "select", "value" => $paneles, "name" => "File"), "{$lang['admin']['panel_side']}:" => array("type" => "select", "value" => array("L" => "{$lang['admin']['panel_left']}", "R" => "{$lang['admin']['panel_right']}"), "name" => "Align"), "{$lang['admin']['panel_showtitle']}?" => array("type" => "select", "value" => array("Y" => "{$lang['admin']['yes']}", "N" => "{$lang['admin']['no']}"), "name" => "Show"), "{$lang['admin']['panel_showfor']}:" => array("type" =>
				"select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai"), "" => array("type" => "submit", "name" => "Nauja_panele", "value" => "{$lang['admin']['panel_create']}"));

			include_once ("priedai/class.php");
			$bla = new forma();
			lentele($lang['admin']['panel_new'], $bla->form($panele, $lang['admin']['panel_new']));
		}
	}

	//Paneles redagavimas
	elseif (isset($url['r']) && isnum($url['r']) && $url['r'] > 0) {
		if (isset($_POST['Redaguoti_panele']) && $_POST['Redaguoti_panele'] == "{$lang['admin']['edit']}") {
			$panel = input($_POST['Panel']);
			$teisess = serialize((isset($_POST['Teises'])?$_POST['Teises']:0));
			if (empty($panel) || $panel == '') {
				$panel = $lang['admin']['panel_new'];
			}
			$align = input($_POST['Align']);
			if (strlen($align) > 1) {
				$align = 'L';
			}
			$show = input($_POST['Show']);
			if (strlen($show) > 1) {
				$align = 'Y';
			}

			$sql = "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `panel`=" . escape($panel) . ", `align`=" . escape($align) . ", `show`=" . escape($show) . ",`teises`=" . escape($teisess) . "   WHERE `id`=" . escape((int)$url['r']);
			// print_r($_POST);
			mysql_query1($sql);
			delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' ORDER BY `place` ASC");
			redirect("?id," . $url['id'] . ";a,{$_GET['a']}", "header");
		} else {

			$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape((int)$url['r']) . " LIMIT 1";
			$sql = mysql_query1($sql);
			$selected = unserialize($sql['teises']);
			/*$box = "";
			foreach ($teises as $name => $check) {
			$box .= "<label><input type=\"checkbox\" " . (in_array($name, $selected) ? "checked" : "") . " name=\"Teises[]\" value=\"$name\"/> $check</label><br /> ";
			}*/
			$panele = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel"), "{$lang['admin']['panel_title']}:" => array("type" => "text", "value" => input($sql['panel']), "name" => "Panel", "class" => "input"), "{$lang['admin']['panel_side']}:" => array("type" => "select", "value" => array("L" => "{$lang['admin']['panel_left']}", "R" => "{$lang['admin']['panel_right']}"), "selected" => input($sql['align']), "name" => "Align"), "{$lang['admin']['panel_showtitle']}?" => array("type" => "select", "value" => array("Y" => "{$lang['admin']['yes']}", "N" => "{$lang['admin']['no']}"), "selected" => input($sql['show']), "name" => "Show"), "{$lang['admin']['panel_showfor']}:" => array("type" => "select", "extra" => "multiple=multiple",
				"value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai", "selected" => $selected), "" => array("type" => "submit", "name" => "Redaguoti_panele", "value" => "{$lang['admin']['edit']}"));

			include_once ("priedai/class.php");
			$bla = new forma();
			lentele($sql['panel'], $bla->form($panele, "Bloko redagavimas"));
		}
	}

	//Redaguojam panelės turinį
	elseif (isset($url['e']) && isnum($url['e']) && $url['e'] > 0) {
		$panel_id = (int)$url['e']; //Panelės ID

		if (isset($_POST['Turinys']) && !empty($_POST['Turinys'])) {
			$sql = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape($panel_id) . " LIMIT 1";
			$sql = mysql_query1($sql);
			if (!is_writable('paneles/' . $sql['file'])) {
				klaida($lang['system']['warning'], $lang['admin']['panel_cantedit']);
			} else {
				//echo $_POST['Turinys'];
				//irasom('paneles/' . $sql['file'], $_POST['Turinys']);
				$failas = "paneles/" . $sql['file'];
				$tekstas = str_replace(array('$', '<br>'), array('\$', '<br/>'), $_POST['Turinys']);
				$tekstas = str_replace('HTML', 'html', $tekstas);

				// Nurodote įrašą kuris bus faile kai jį sukurs:
				//$apsauga = random_name();
				$irasas = '<?php
$text =
<<<HTML
' . stripslashes($tekstas) . '
HTML;
?>';
				//Irasom faila
				$fp = fopen($failas, "w+");
				fwrite($fp, $irasas);
				fclose($fp);
				chmod($failas,0777);

			}
		} else {
			$sql = "SELECT `id`, `panel`, `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape($panel_id) . " LIMIT 1";
			$sql = mysql_query1($sql);
			//tikrinam failo struktura

			$lines = file('paneles/' . $sql['file']); // "failas.txt" - failas kuriame ieškoma.
			$resultatai = array();

			$zodiz = '$text ='; // "http" - žodis kurio ieškoma
			for ($i = 0; $i < count($lines); $i++) {
				$temp = trim($lines[$i]);
				if (substr_count($temp, $zodiz) > 0) {
					$resultatai[] = $temp;
					//if(isset($rezultatai[$i]))echo $resultatai[$i];
					$nr = ($i + 1);
				}
			}

			//tikrinimo pabaiga
			if (isset($nr) && $nr == 2) {
				include 'paneles/' . $sql['file'];

				if (isset($text) && is_writable('paneles/' . $sql['file'])) {
					$paneles_txt = $text;
					$panele = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "panel_txt"), $lang['admin']['panel_text'] => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('Turinys' => 'Bloko turinys'), array('Turinys' => (isset($paneles_txt)) ? $paneles_txt : ''))), "" => array("type" => "submit", "name" => "Redaguoti_txt", "value" => "{$lang['admin']['edit']}"));

					include_once ("priedai/class.php");
					$bla = new forma();
					lentele($sql['panel'], $bla->form($panele));
				} else {
					klaida($lang['system']['warning'], $lang['admin']['panel_cantedit']);
				}
			} else {
				klaida($lang['system']['warning'], $lang['admin']['panel_cantedit']);
			}
		}
	}
	//Paneliu lygiavimas
	/*elseif (isset($_POST['sortableListsSubmitted']) && !isset($url['n'])) {
		$orderArray = SLLists::getOrderArray($_POST['paneles_kaire'], 'kaire');
		foreach ($orderArray as $item) {
			$sql = "UPDATE `" . LENTELES_PRIESAGA . "panel` set place=" . escape($item['order']) . " WHERE `id`=" . escape($item['element']);
			mysql_query1($sql);
			delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' ORDER BY `place` ASC");
	delete_cache("SELECT SQL_CACHE * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' ORDER BY `place` ASC");
		}
		$orderArray = SLLists::getOrderArray($_POST['paneles_desine'], 'desine');
		foreach ($orderArray as $item) {
			$sql = "UPDATE `" . LENTELES_PRIESAGA . "panel` set place=" . escape($item['order']) . " WHERE `id`=" . escape($item['element']);
			mysql_query1($sql);
		}
		redirect("?id," . $url['id'] . ";a,9", "header");
	}*/

	//atvaizduojam paneles
	$li = "";
	$li1 = "";
	//$text = "";
	$sql = "SELECT id, panel, place from `" . LENTELES_PRIESAGA . "panel` WHERE align='L' order by place";
	$recordSet = mysql_query1($sql);
	$listArray = array();
	if (sizeof($recordSet) > 0) {
		foreach ($recordSet as $record) {
			//$listArray[] = sprintf($listItemFormat, $record['id'], $record['panel'], $record['id'],$record['id'], $record['id']);

			$li .= '<li id="listItem_' . $record['id'] . '" class="drag_block"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>  
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record['id'] . '" style="align:right"><img src="images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record['id'] . '" style="align:right"><img src="images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a> 
<img src="images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" /> 
' . $record['panel'] . '
</li> ';
		}
	}
	$sql1 = "SELECT id, panel, place from `" . LENTELES_PRIESAGA . "panel` WHERE align='R' order by place";
	$recordSet1 = mysql_query1($sql1);
	//$listArray1 = array();

	if (sizeof($recordSet1) > 0) {
		foreach ($recordSet1 as $record1) {
			//$listArray1[] = sprintf($listItemFormat, $record1['id'], $record1['panel'], $record1['id'],$record1['id'], $record1['id']);
			//$listArray1[] = sprintf($listItemFormat, $record1['id'], $record1['pavadinimas'],$record1['id'], $record1['id'], $record1['id']);
			$li1 .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record1['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record1['id'] . '" style="align:right"><img src="images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record1['id'] . '" style="align:right"><img src="images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a> 
<img src="images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" /> 
' . $record1['panel'] . '
</li> ';
		}
	}


	$text .= '<div id="la" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>

		<table width="100%"><tr><td width="50%" valign="top">
			<fieldset><legend>' . $lang['admin']['panel_left'] . '</legend>
			<ul id="kaire">' . $li . '</ul>';

	$text .= '</fieldset></td>
	<td width="50%" valign="top"><fieldset><legend>' . $lang['admin']['panel_right'] . '</legend>
		<ul id="desine">' . $li1 . '</ul>';

	$text .= "</fieldset>
		</td>
	</tr>
	</table>";
	//$text .= "<button onClick=\"window.location='?id," . $url['id'] . ";a,{$_GET['a']};n,1';\">{$lang['admin']['panel_select']}</button>";
	//$text .= "<button onClick=\"window.location='?id," . $url['id'] . ";a,{$_GET['a']};n,2';\">{$lang['admin']['panel_create']}</button>";

	lentele($lang['admin']['paneles'], $text);

	//Funkcija panelių turiniui įrašyti
	function irasom($Failas, $Info) {
		global $url, $lang;
		if (is_writable($Failas)) {
			if ($fh = fopen($Failas, 'w')) {
				//$apsauga = random_name();
				$tekstas = str_replace('$', '\$', $Info);
				$tekstas = str_replace('HTML', 'html', $tekstas);

				$Info = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
?>';

				if (fwrite($fh, $Info) !== false) {
					msg($lang['system']['done'], $lang['admin']['panel_updated']);
					fclose($fh);
					chmod($Failas,0777);
					redirect("?id," . $url['id'] . ";a," . $url['a'], "meta");
				}
			} else {
				klaida($lang['system']['error'], $lang['system']['systemerror']);
			}
		} else {
			klaida($Failas, $lang['system']['systemerror']);
		}
	}
	unset($text, $_POST);
}

?>
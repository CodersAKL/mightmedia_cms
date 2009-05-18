<script type="text/javascript">
   $(document).ready(function() {
    $("#test-list").sortable({
      handle : '.handle',
      update : function () {
		var order = $('#test-list').sortable('serialize');
		$("#la").show("slow");
		$("#la").hide("slow");
		$.post("<?php

echo "?" . $_SERVER['QUERY_STRING'];

?>",{order:order});

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
				removeLabel: '<?php

echo $lang['system']['delete'];

?>',					
			    highlightAddedLabel: '<?php

echo $lang['admin']['added'];

?>: ',
			    highlightRemovedLabel: '<?php

echo $lang['sb']['deleted'];

?>: ',	
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
//$("#info").load("echo "?".$_SERVER['QUERY_STRING']; ");
unset($text);
if (!defined("LEVEL") || LEVEL > 1 || !defined("OK")) {
	header('location: http://' . $_SERVER["HTTP_HOST"] . '');
	exit;
}
if (isset($_POST['order'])) {
	$array = str_replace("&", ",", $_POST['order']);
	$array = str_replace("listItem[]=", "", $array);
	$array = explode(",", $array);
	//$array=array($array);
	//print_r($array);
	//$sql=array();
	foreach ($array as $position => $item):
		//$sql[] = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "page` SET `place` = ".escape($position)." WHERE `id` = ".escape($item)."")or die(mysql_error());
		//$sql= "(UPDATE `" . LENTELES_PRIESAGA . "page` SET `place` = ".escape($position)." WHERE `id` = ".escape($item).")"
		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";
		//$case_type .= "WHEN $phone_id THEN '" . $number['type'] . "' ";
		$where .= "$item,";
	endforeach;
	$where = rtrim($where, ", ");
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "page` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1($sqlas) or die(mysql_error());

} else {
	$lygiai = array_keys($conf['level']);


	foreach ($lygiai as $key) {
		$teises[$key] = $conf['level'][$key]['pavadinimas'];
	}
	$teises[0] = $lang['admin']['for_guests'];
	//require ('puslapiai/dievai/tools/list.class.php');

	//$sortableLists = new SLLists('javascript/scriptaculous/'); // points to path of scriptaculous JS files

	//$listItemFormat = '<li id="item_%s"><strong>%s</strong> <a href="?id,' . $url['id'] .';a,21;r,%s" style="align:right">[' . $lang['admin']['edit'] .']</a> <a href="?id,' . $url['id'] .';a,21;d,%s" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] .'?\')">[' . $lang['admin']['delete'] . ']</a> <a href="?id,' . $url['id'] .';a,21;e,%s" style="align:right">[' . $lang['admin']['page_text'] . ']</a></li>'; // two arguments are the idField and the displayField
	//$sortableLists->addList('kaire', 'paneles_kaire');
	//$sortableLists->addList('desine','paneles_desine');

	if (isset($_POST['Naujas_puslapis2']) && $_POST['Naujas_puslapis2'] == $lang['admin']['page_create']) {
		// Nurodote failo pavadinimą:
		$failas = "puslapiai/" . preg_replace("/[^a-z0-9-]/", "_", strtolower($_POST['pav'])) . ".php";

		// Nurodote įrašą kuris bus faile kai jį sukurs:
		//$apsauga = random_name();
		$tekstas = str_replace('$', '\$', $_POST['Page']);
		$tekstas = str_replace('HTML', 'html', $tekstas);

		$irasas = '<?php
$text =
<<<HTML
' . stripslashes($tekstas) . '
HTML;
lentele($page_pavadinimas,$text);
?>';

		// Irasom faila
		$fp = fopen($failas, "w+");
		fwrite($fp, $irasas);
		fclose($fp);

		// Rezultatas:
		msg($lang['system']['done'], "{$lang['admin']['page_created']}.");

	}
	/* elseif (isset($_POST['sortableListsSubmitted']) && !isset($url['n'])) {
	//$orderArray = SLLists::getOrderArray($_POST['paneles_kaire'], 'kaire');
	foreach ($orderArray as $item) {
	$sql = "UPDATE `" . LENTELES_PRIESAGA . "page` set place=" . escape($item['order']) .
	" WHERE `id`=" . escape($item['element']);
	mysql_query1($sql);
	}
	}*/
	if (isset($url['d']) && isnum($url['d']) && $url['d'] > 0) {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`= " . escape((int)$url['d']) . " LIMIT 1") or die(mysql_error());
		redirect("?id," . $url['id'] . ";a,21", "header");
	} elseif (isset($url['n']) && $url['n'] == 1) {
		if (isset($_POST['Naujas_puslapis']) && $_POST['Naujas_puslapis'] == $lang['admin']['page_create']) {
			$psl = input($_POST['Page']);
			$teises = serialize($_POST['Teises']);
			$file = input(basename($_POST['File']));
			if (!file_exists("puslapiai/" . $file)) {
				klaida($lang['system']['error'], "<font color='red'>" . $file . "</font> ");
			} else {
				if (empty($psl) || $psl == '') {
					$psl = basename($file, ".php");
				}
				$show = input($_POST['Show']);
				if (strlen($show) > 1) {
					$align = 'Y';
				}
				$sql = "INSERT INTO `" . LENTELES_PRIESAGA . "page` (`pavadinimas`, `file`, `place`, `show`, `teises` ) VALUES (" . escape($psl) . ", " . escape($file) . ", '0', " . escape($show) . ", " . escape($teises) . ")";
				mysql_query1($sql) or die(mysql_error());
				redirect("?id," . $url['id'] . ";a,21", "header");
			}
		}
		$failai = getFiles('puslapiai/');


		foreach ($failai as $file) {
			if ($file['type'] == 'file') {
				$sql = mysql_query1("SELECT pavadinimas FROM `" . LENTELES_PRIESAGA . "page` WHERE file=" . escape(basename($file['name'])) . " LIMIT 1");
				if (($sql) == 0) {
					$puslapiai[basename($file['name'])] = basename($file['name']) . ": " . $file['sizetext'] . "\n";
				}
			}
		}

		if (!isset($puslapiai) || count($puslapiai) < 1) {
			klaida($lang['system']['warning'], "<h3>{$lang['admin']['page_nounused']}</h3>");
		} else {

			//	$box = "";
			//foreach ($teises as $name => $check) {
			//	$box .= "<label><input type=\"checkbox\"  name=\"Teises[]\" value=\"$name\"/> $check</label><br /> ";
			//	}
			//$box = array("type" => "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "punktai[]", "id" => "punktai");

			$psl = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel"), "{$lang['admin']['page_name']}:" => array("type" => "text", "value" => "{$lang['admin']['page_name']}", "name" => "Page", "style" => "width:400px"), "{$lang['admin']['page_file']}:" => array("type" => "select", "value" => $puslapiai, "name" => "File"), "{$lang['admin']['page_show']}" => array("type" => "select", "value" => array("Y" => $lang['admin']['yes'], "N" => "{$lang['admin']['no']}"), "name" => "Show"), "{$lang['admin']['page_showfor']}:" => array("type" => "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai"), "" => array("type" => "submit", "name" =>
				"Naujas_puslapis", "value" => $lang['admin']['page_create']));

			include_once ("priedai/class.php");
			$bla = new forma();
			lentele($lang['admin']['page_create'], $bla->form($psl));
		}
	}
	if (isset($url['n']) && $url['n'] == 2) {
		$psl = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_page2"), "{$lang['admin']['page_filename']}:" => array("type" => "text", "value" => "{$lang['admin']['page_name']}", "name" => "pav", "style" => "width:400px"), "{$lang['admin']['page_text']}:" => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('Page' => 'Page'), false), "name" => "Page", "class" => "input", "rows" => "8", "style" => "width:100%"), "" => array("type" => "submit", "name" => "Naujas_puslapis2", "value" => $lang['admin']['page_create']));
		include_once ("priedai/class.php");
		$bla = new forma();
		lentele($lang['admin']['page_create'], $bla->form($psl, $lang['admin']['page_create']));
	}
	//puslapiai redagavimas
	elseif (isset($url['r']) && isnum($url['r']) && $url['r'] > 0) {
		if (isset($_POST['Redaguoti_psl']) && $_POST['Redaguoti_psl'] == $lang['admin']['edit']) {
			$psl = input($_POST['pslp']);
			$teises = serialize($_POST['Teises']);
			if (empty($psl) || $psl == '') {
				$psl = $lang['admin']['page_text'];
			}
			$align = input($_POST['Align']);
			if (strlen($align) > 1) {
				$align = 'L';
			}
			$show = input($_POST['Show']);
			if (strlen($show) > 1) {
				$align = 'Y';
			}
			$sql = "UPDATE `" . LENTELES_PRIESAGA . "page` SET `pavadinimas`=" . escape($psl) . ", `show`=" . escape($show) . ",`teises`=" . escape($teises) . "  WHERE `id`=" . escape((int)$url['r']);
			mysql_query1($sql);
			redirect("?id," . $url['id'] . ";a,21", "header");
		} else {
			$sql = "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape((int)$url['r']) . " LIMIT 1";
			$sql = mysql_query1($sql, 2500);
			$selected = unserialize($sql['teises']);
			/*$box = "";
			foreach ($teises as $name => $check) {
			$box .= "<label><input type=\"checkbox\" " . (in_array($name, $selected) ? "checked" : "") . " name=\"Teises[]\" value=\"$name\"/> $check</label><br /> ";
			}*/
			$psl = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_psl"), "{$lang['admin']['page_name']}:" => array("type" => "text", "value" => input($sql['pavadinimas']), "name" => "pslp", "style" => "width:100%"), "{$lang['admin']['page_showfor']}:" => array("type" => "select", "value" => $teises, "name" => "Teises", "class" => "input", "style" => "width:100%", "selected" => (isset($sql['teises']) ? input($sql['teises']) : '')), "{$lang['admin']['page_show']}" => array("type" => "select", "value" => array("Y" => $lang['admin']['yes'], "N" => $lang['admin']['no']), "selected" => input($sql['show']), "name" => "Show"), "{$lang['admin']['page_showfor']}:" => array("type" => "select", "extra" => "multiple=multiple", "value" => $teises,
				"class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai", "selected" => $selected), "" => array("type" => "submit", "name" => "Redaguoti_psl", "value" => $lang['admin']['edit']));

			include_once ("priedai/class.php");
			$bla = new forma();
			lentele(input($sql['file'] . " - " . $sql['pavadinimas']), $bla->form($psl, $lang['admin']['edit']));
		}
	}

	//Redaguojam puslapiai turini
	elseif (isset($url['e']) && isnum($url['e']) && $url['e'] > 0) {
		$psl_id = (int)$url['e']; //puslapiai ID

		if (isset($_POST['Redaguoti_txt']) && $_POST['Redaguoti_txt'] == $lang['admin']['edit']) {
			$sql = "SELECT `file`,`pavadinimas` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape($psl_id) . " LIMIT 1";
			$sql = mysql_query1($sql);
			$tekstas = str_replace('$', '\$', $_POST['Page']);
			$tekstas = str_replace('HTML', 'html', $tekstas);

			$irasas = '<?php
$text =
<<<HTML
' . stripslashes($tekstas) . '
HTML;
lentele($page_pavadinimas,$text);
?>';

			// irasom('puslapiai/' . $sql['file'], $irasas);

			// Irasom faila
			$fp = fopen('puslapiai/' . $sql['file'], "w+");
			fwrite($fp, $irasas);
			fclose($fp);
		} else {

			$sql = "SELECT `id`, `pavadinimas`, `file` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape($psl_id) . " LIMIT 1";
			$sql = mysql_query1($sql);
			//tikrinam failo struktura

			$lines = file('puslapiai/' . $sql['file']); // "failas.txt" - failas kuriame ieškoma.
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
				//if (is_writable('puslapiai/' . $sql['file'])) {
				include 'puslapiai/' . $sql['file'];

				$puslapio_txt = $text;

				$puslapis = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "psl_txt"), //"puslapiai avadinimas:"=>array("type"=>"text","value"=>input($sql['psl']),"name"=>"psl","disabled"=>"disabled","style"=>"width:100%"),
					"{$lang['admin']['page_text']}" => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('Page' => 'Page'), array('Page' => $puslapio_txt)), "name" => "Turinys", "style" => "width:100%", "rows" => "10"), "" => array("type" => "submit", "name" => "Redaguoti_txt", "value" => $lang['admin']['edit']));

				include_once ("priedai/class.php");
				$bla = new forma();
				lentele(input($sql['file'] . " - " . $sql['pavadinimas']), $bla->form($puslapis, $lang['admin']['edit']));
			} else {
				klaida($lang['system']['warning'], $lang['admin']['page_cantedit']);
			}
		}
	}

	$li = '';
	$recordSet1 = mysql_query1("SELECT * from `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'Y' order by place");
	$listArray1 = array();
	if (sizeof($recordSet1) > 0) {
		foreach ($recordSet1 as $record1) {
			//$listArray1[] = sprintf($listItemFormat, $record1['id'], $record1['pavadinimas'],$record1['id'], $record1['id'], $record1['id']);
			$li .= '<li id="listItem_' . $record1['id'] . '" style="display:block; border:1px solid grey; width:460px; padding:3px; margin:3px; background-color:#DDDDDD"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record1['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>  
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record1['id'] . '" style="align:right"><img src="images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record1['id'] . '" style="align:right"><img src="images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '" align="right" /></a> 
<img style="cursor:move;vertical-align:middle" src="images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" /> 
' . $record1['pavadinimas'] . '
</li> ';

		}
	}
	//mysql_free_result($recordSet1);
	//$listHTML1 = implode("\n", $listArray1);

	////$tekstas = $sortableLists->SLLists("javascript"); //kelias iki js failu
	//$tekstas .= $sortableLists->printTopJS(); //atspauzdinam pagrindinius javascript
	$tekstas = '';
	$tekstas .= '
<div id="la" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>

		
			<fieldset><legend>' . $lang['admin']['page_navigation'] . '</legend>
			<ul id="test-list">' . $li . '</ul>';
	//$tekstas .= $sortableLists->printBottomJS();
	$tekstas .= '</fieldset>';
	$sql25 = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'N' order by id");
	$tekstas .= '
		
			<fieldset><legend>' . $lang['admin']['page_other'] . '</legend><ul>
			';
	if (sizeof($sql25) > 0) {
		foreach ($sql25 as $sql2) {

			$tekstas .= '<li style="display:block; border:1px solid grey; width:460px; padding:3px; margin:3px; background-color:#DDDDDD;height:16px;"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $sql2['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>  
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $sql2['id'] . '" style="align:right"><img src="images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $sql2['id'] . '" style="align:right"><img src="images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '" align="right" /></a> 

' . $sql2['pavadinimas'] . '
</li> ';
		}
	}
	$tekstas .= '</ul></fieldset>';
	$tekstas .= "<button onClick=\"window.location='?id," . $url['id'] . ";a,21;n,2';\">{$lang['admin']['page_create']}</button>";
	$tekstas .= "<button onClick=\"window.location='?id," . $url['id'] . ";a,21;n,1';\">{$lang['admin']['page_select']}</button>";

	lentele($lang['admin']['pages'], $tekstas);


	//Funkcija puslapiu turiniui irašyti
	function irasom($Failas, $Info) {
		global $url;
		if (is_writable($Failas)) {
			if ($fh = fopen($Failas, 'w')) {
				if (fwrite($fh, $Info) !== false) {
					msg($lang['system']['done'], $lang['system']['done']);
					fclose($fh);
					redirect("?id," . $url['id'] . ";a," . $url['a'], "meta");
				}
			} else {
				klaida($lang['system']['error'], $lang['system']['systemerror']);
			}
		} else {
			klaida($Failas, $lang['system']['systemerror']);
		}
	}
}

?>

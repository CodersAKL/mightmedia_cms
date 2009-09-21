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
function kategorija($kieno, $leidimas = false) {
	global $conf, $url, $lang;
	echo <<< HTML
<script type="text/javascript" src="javascript/jquery/jquery.asmselect.js"></script>

	<script type="text/javascript">

		
		$(document).ready(function() {
			$("select[multiple]").asmSelect({
				addItemTarget: 'bottom',
				animate: true,
				highlight: true,
				removeLabel: '{$lang['system']['delete']}',					
			    highlightAddedLabel: '{$lang['admin']['added']}: ',
			    highlightRemovedLabel: '{$lang['sb']['deleted']}: ',	
				sortable: true
			});
			
		}); 
			
		 

	</script>
HTML;
	if (empty($_GET['v'])) {
		$_GET['v'] = 0;
	}
	$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='$kieno' AND `path`=0 ORDER BY `id` DESC");
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {

			$sql2 = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='$kieno' AND path!=0 and `path` like '" . $row['id'] . "%' ORDER BY `id` ASC");
			if (sizeof($sql2) > 0) {
				$subcat = '';
				foreach ($sql2 as $path) {

					$subcat .= "->" . $path['pavadinimas'];
					$kategorijoss[$row['id']] = $row['pavadinimas'];
					$kategorijoss[$path['id']] = $row['pavadinimas'] . $subcat;


				}
			} else {
				$kategorijoss[$row['id']] = $row['pavadinimas'];
			}


		}
	} /*else {
		$kategorijoss[] = "{$lang['system']['nocategories']}.";
	}*/

	if ($kieno != "vartotojai") {
		$dir = "images/naujienu_kat";
	} else {
		$dir = "images/icons";
	}

	$array = getFiles($dir);
	foreach ($array as $key => $val) {
		if ($array[$key]['type'] == 'file')
			$kategoriju_pav[$array[$key]['name']] = $array[$key]['name'] . ' - ' . $array[$key]['sizetext'];
	}
	include_once ("priedai/class.php");

	$bla = new forma();
	$lygiai = array_keys($conf['level']);
	if ($kieno != 'vartotojai') {

		foreach ($lygiai as $key) {
			$teises[$key] = $conf['level'][$key]['pavadinimas'];
		}
		$teises[0] = $lang['admin']['for_guests'];
	} else {

		for ($i = 1; $i <= 20; $i++) {
			if (!isset($conf['level'][$i])) {
				$teises[$i] = $i;
			}

		}

	}


	if (isset($_POST['action']) && $_POST['action'] == $lang['system']['createcategory']) {
		
		$pavadinimas = $_POST['Pavadinimas'];
		$aprasymas = $_POST['Aprasymas'];
		$pav =htmlspecialchars($_POST['Pav']);
		$moderuoti = ((isset($_POST['punktai'])) ? serialize($_POST['punktai']) : '');

		if (isset($_POST['Teises'])) {
			if ($kieno == 'vartotojai')
				$teises = $_POST['Teises'];
			else
				$teises = serialize($_POST['Teises']);
		} 

		if (isset($_POST['path'])) {
			$path = mysql_query1("Select * from`" . LENTELES_PRIESAGA . "grupes` WHERE id=" . escape($_POST['path']) . " Limit 1");
			if ($path) {
				if ($kieno == 'vartotojai')
					$teises = $_POST['Teises'];
				else
					$teises = serialize($_POST['Teises']);
			}


			if ($path['path'] == 0) {
				$pathas = $path['id'];
			} else {
				$pathas = $path['path'] . "," . $path['id'];
			}
		}else{
			$pathas='0';
		}
		
		if ($kieno == 'vartotojai') {
			$einfo = mysql_query("SELECT `teises` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `teises`='" . $teises . "'AND `kieno`='vartotojai'");

			if (sizeof($einfo) > 0) {
				$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "grupes` (`pavadinimas`, `aprasymas`, `teises`, `pav`, `path`, `kieno`, `mod`) VALUES (" . escape($pavadinimas) . ",  " . escape($aprasymas) . ", " . escape($teises) . ", " . escape($pav) . ", " . escape($pathas) . ", " . escape($kieno) . ", " . escape($moderuoti) . ")");

				if ($result) {
					msg($lang['system']['done'], $lang['system']['categorycreated']);
				}
			} else {
				klaida($lang['system']['warning'], "{$lang['system']['badlevel']}.");
			}
		} else {
			$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "grupes` (`pavadinimas`,`aprasymas`, `teises`, `pav`, `path`, `kieno`, `mod`) VALUES (" . escape($pavadinimas) . ",  " . escape($aprasymas) . ", " . escape($teises) . ", " . escape($pav) . "," . escape($pathas) . "," . escape($kieno) . "," . escape($moderuoti) . ")");
			if ($result) {
				msg("{$lang['system']['done']}", $lang['system']['categorycreated']);
				//print_r($path);
			} else {
				klaida($lang['system']['error'], "{$lang['system']['error']}<br><b>" . mysql_error() . "</b>");
			}
		}
		unset($aprasymas, $pavadinimas, $teises, $pav, $einfo, $result);
	} elseif (isset($_POST['action']) && $_POST['action'] == $lang['system']['editcategory']) {
	
		$pavadinimas = $_POST['Pavadinimas'];
		$aprasymas = $_POST['Aprasymas'];
		$pav = strip_tags($_POST['Pav']);
		$id = ceil((int)$_POST['Kategorijos_id']);
		if ($kieno == 'vartotojai')
			$teises = $_POST['Teises'];
		else
			$teises = (isset($_POST['Teises'])?serialize($_POST['Teises']):serialize(0));
		$moderuoti = ((isset($_POST['punktai'])) ? serialize($_POST['punktai']) : '');
		$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "grupes` SET
			`pavadinimas` = " . escape($pavadinimas) . ",
			`aprasymas` = " . escape($aprasymas) . ",
			`teises` = " . escape($teises) . ",
			`pav` = " . escape($pav) . ",
			`mod` = " . escape($moderuoti) . "
			WHERE `id`=" . escape($id) . ";
			");
		if ($result) {
			msg($lang['system']['done'], $lang['system']['categoryupdated']);
		} else {
			klaida($lang['system']['error'], "{$lang['system']['error']}<br><b>" . mysql_error() . "</b>");
		}
	}
	if (isset($_POST['Kategorijos_id']) && isNum($_POST['Kategorijos_id']) && $_POST['Kategorijos_id'] > 0 && isset($_POST['Kategorija']) && $_POST['Kategorija'] == $lang['system']['edit']) {
		$extra = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='$kieno' AND `id`=" . escape((int)$_POST['Kategorijos_id']) . " LIMIT 1");
		//$extra = mysql_fetch_assoc($extra);
	}
	if ($_GET['v'] == 2 || $_GET['v'] == 5 ) {
		if($_GET['v'] == 5 && !isset($kategorijoss)){
			klaida($lang['system']['warning'],$lang['system']['nocategories']);
		}else{
		if (isset($_POST['Kategorija']) && $_POST['Kategorija'] == $lang['system']['delete']) {
			//Trinamos nuorodos esančios kategorijoje
			if ($kieno == 'nuorodos') {
				$result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE `cat`=" . escape($_POST['Kategorijos_id']) . "");
			}
			//Trinami straipsniai esantys kategorijoje
			if ($kieno == 'straipsniai') {

				$sql = mysql_query1("SELECT `id` FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `kat` = " . escape($_POST['Kategorijos_id']) . "");
				foreach ($sql as $row) {

					mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/straipsnis' AND kid=" . escape($row['id']) . "");
				}

				$result = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `kat`=" . escape($_POST['Kategorijos_id']) . "");

			}
			//Trinam failą iš siuntinių
			if ($kieno == 'siuntiniai') {
				$id = ceil((int)$_POST['Kategorijos_id']);
				$sql = mysql_query1("SELECT `ID`,`file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `categorija` = " . escape($id) . "");
				foreach ($sql as $row) {

					if (isset($row['file']) && !empty($row['file'])) {
						mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/siustis' AND kid=" . escape($row['ID']) . "");
						@unlink("siuntiniai/" . $row['file']);
					}
				}

				mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `categorija`='" . escape($id) . "'");
			}
			//Trinam paveikslėlius kurie yra kategorijoje(galerija)
			if ($kieno == 'galerija') {
				$id = ceil((int)$_POST['Kategorijos_id']);
				$sql = mysql_query1("SELECT `ID`,`file` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `categorija` = " . escape($id) . "");
				foreach ($sql as $row) {
					if (isset($row['file']) && !empty($row['file'])) {
						@unlink("galerija/" . $row['file']);
						@unlink("galerija/mini/" . $row['file']);
						@unlink("galerija/originalai/" . $row['file']);
						mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/galerija' AND kid=" . escape($row['ID']) . "");
					}
				}
				mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `categorija`='" . escape($id) . "'");
			}
			//Trinamos naujienos esančios kategorijoje
			if ($kieno == 'naujienos') {
				$id = ceil((int)$_POST['Kategorijos_id']);
				$sql = mysql_query1("SELECT `id` FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija` = " . escape($_POST['Kategorijos_id']) . "");
				foreach ($sql as $row) {
					mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/naujienos' AND kid=" . escape($row['id']) . "");
				}
				mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija` = '" . escape($id) . "'") or klaida("Klaida", mysql_error());
			}
			//trinama kategorija
			//Jei turi subkategoriju, perkeliam
			$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `path`like '%" . $_POST['Kategorijos_id'] . ",%'");
			if (sizeof($sql) > 0) {
				foreach ($sql as $row) {
					//echo $row['path'];
					$update = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "grupes` set path=" . str_replace(escape($_POST['Kategorijos_id']) . ",", "", $row['path']) . " WHERE id=" . $row['id'] . "");
				}
			}
			//perkelimo pabaiga
			$result23 = mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "grupes` 
			WHERE `id`=".escape($_POST['Kategorijos_id'])."");


			if ($result23) {
				msg($lang['system']['done'], $lang['system']['categorydeleted']);
			} else {
				klaida($lang['system']['error'], " {$lang['system']['error']}");
			}
		}

		//Jei kuriama vartotoju kategorija
		if ($kieno == 'vartotojai') {
			$textas = "{$lang['system']['grouplevel']}:";
			//$puslapiai[""]="";
			$failai = getFiles('puslapiai/dievai', '.htaccess|index.php|index.html|index.htm|index.php3|conf.php|config.php|vartotojai.php|logai.php|upload.php|todo.php|paneles.php|meniu.php|komentarai.php|narsykle.php');
			foreach ($failai as $file) {
				if ($file['type'] == 'file') {

					//$puslapiai[basename($file['name'])] = basename($file['name']);
					$puslapiai[basename($file['name'])] =(isset($lang['admin'][basename($file['name'],'.php')])?$lang['admin'][basename($file['name'],'.php')]:nice_name(basename($file['name'],'.php')));
				}
			}
			$puslapiai['com'] = "<b>" . $lang['admin']['komentarai'] . "(mod)</b>";
			$puslapiai['frm'] = "<b>" . $lang['admin']['frm'] . "(mod)</b>";


			$vartotojai = false;
			if (isset($extra)) {
				$vartotojai = true;
			}
		} else {
			$textas = "{$lang['system']['showfor']}:";
			$vartotojai = false;
		}

		if (count($teises) > 0) {
			if (!empty($extra['mod'])) {
				$ser = unserialize($extra['mod']);
			} else {
				$ser = "";
			}
			//print_r($puslapiai);
			$kategorijos = array("Form" => array("action" => '' . "?id,{$_GET['id']};a,{$_GET['a']}" . '', "method" => "post", "name" => "reg"), "{$lang['system']['name']}:" => array("type" => "text", "value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '', "name" => "Pavadinimas", "class"=>"input"), (isset($extra) || $_GET['v'] == 2 ? "" : "{$lang['system']['subcat/cat']}:") => (isset($extra) ? "" : array("type" => "select", "value" => @$kategorijoss, "name" => "path", "selected" => (isset($extra['kategorija']) ? input($extra['kategorija']) : ""), "disabled" => @$kategorijoss)), "{$lang['system']['about']}:" => array("type" => "textarea", "value" => (isset($extra['aprasymas'])) ? input($extra['aprasymas']) : '', "name" => "Aprasymas", "rows" => "3", "class" => "input",
				"class"=>"input", "id" => "Aprasymas"), "{$lang['system']['picture']}:" => array("type" => "select", "value" => $kategoriju_pav, "name" => "Pav", "class" => "input", "class"=>"input", "selected" => (isset($extra['pav']) ? input($extra['pav']) : ''), "extra" => "onchange=\"$('#kategorijos_img').attr({ src: '" . $dir . "/'+this.value });\""), $lang['admin']['what_moderate'] => "", $textas => "", "" => array("type" => "hidden", "name" => "Kategorijos_id", "value" => (isset($extra['id']) ? input($extra['id']) : '')), (isset($extra)) ? $lang['system']['editcategory'] : $lang['system']['createcategory'] => array("type" => "submit", "name" => "action", "value" => (isset($extra)) ? $lang['system']['editcategory'] : $lang['system']['createcategory']), );
			if ($kieno == 'vartotojai') {
				//	echo $mod;
				$kategorijos[$lang['admin']['what_moderate']] = array("type" => "select", "extra" => "multiple=multiple", "value" => $puslapiai, "class" => "asmSelect", "class"=>"input", "name" => "punktai[]", "id" => "punktai", "selected" => (isset($extra['mod'])) ? $ser : "");
			}
			if ($leidimas == true && $vartotojai == false && $_GET['v'] == 2) {
				if ($kieno == 'vartotojai') {

					$kategorijos[$textas] = array("type" => "select", "value" => $teises, "name" => "Teises", "selected" => (isset($extra['teises']) ? input($extra['teises']) : ""));
				} else {
					/*$box = "";
					foreach ($teises as $name => $check) {
					$box .= "<label><input type=\"checkbox\" " . (isset($extra) && in_array($name, unserialize($extra['teises'])) ? "checked" : "") . " name=\"Teises[]\" value=\"$name\"/> $check</label><br /> ";
					}*/
					$kategorijos[$textas] = array("type" => "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "class"=>"input", "name" => "Teises[]", "id" => "punktai", "selected" => (isset($extra['teises']) ? unserialize($extra['teises']) : "-1"));
				}

			} else {
				$kategorijos[""] = array("type" => "hidden", "name" => "Teises", "value" => (isset($extra['teises']) ? ($kieno == 'vartotojai' ? $extra['teises'] : unserialize($extra['teises'])) : ''));
			}
			$kategorijos[" "] = array("type" => "hidden", "name" => "Kategorijos_id", "value" => (isset($extra['id']) ? input($extra['id']) : ''));
			lentele($lang['system']['categories'], '<center><h2>' . $lang['system']['picture'] . ':</h2><div class="avataras"><img src="' . $dir . '/' . (isset($extra['pav']) ? $extra['pav'] : 'Universal.png') . '" id="kategorijos_img" /></div></center>' . $bla->form($kategorijos));
		} else {
			klaida("{$lang['system']['warning']}", "{$lang['system']['nomorecategories']}.");
		}
}	} elseif ($_GET['v'] == 3) {
	if(isset($kategorijoss)){
		$kategorijos_redagavimas = array("Form" => array("action" => "?id,{$_GET['id']};a,{$_GET['a']};v,2", "method" => "post", "name" => "reg"), "{$lang['system']['category']}:" => array("type" => "select", "value" => $kategorijoss, "name" => "Kategorijos_id"), "{$lang['system']['edit']}:" => array("type" => "submit", "name" => "Kategorija", "value" => "{$lang['system']['edit']}"), "{$lang['system']['delete']}:" => array("type" => "submit", "name" => "Kategorija", "value" => "{$lang['system']['delete']}"));
		lentele($lang['system']['editcategory'], $bla->form($kategorijos_redagavimas));
		}else{klaida($lang['system']['warning'],$lang['system']['nocategories']);}
	}
	delete_cache("SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai'  ORDER BY `pavadinimas`");
	unset($bla, $info, $sql, $sql2, $q, $result, $result2);

}
//print_r($conf['level']);

?>
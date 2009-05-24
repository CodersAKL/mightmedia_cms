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
        $("#test-list2").sortable({
      handle : '.handle',
      update : function () {
		var order2 = $('#test-list2').sortable('serialize');
		$("#la2").show("slow");
		$("#la2").hide("slow");
		$.post("<?php

echo "?" . $_SERVER['QUERY_STRING'];

?>",{order2:order2});

		}
    });
});
</script>
<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 124 $
 * @$Date: 2009-05-24 14:14:40 +0300 (Sk, 24 Geg 2009) $
 **/


if (!defined("OK") || !ar_admin(basename(__file__))) {
	header('location: ?');
	exit();
}


/*$buttons = <<< HTML
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};f,1'">{$lang['system']['createcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};f,2'">{$lang['system']['editcategory']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};f,3'">{$lang['admin']['forum_createsub']}</button>
<button onclick="location.href='?id,{$_GET['id']};a,{$_GET['a']};f,4'">{$lang['admin']['forum_editsub']}</button>
HTML;*/
$buttons="<div id=\"admin_menu\"><a href=\"?id,{$_GET['id']};a,{$_GET['a']};f,1\"><img src=\"images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</a> <a href=\"?id,{$_GET['id']};a,{$_GET['a']};f,2\"><img src=\"images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</a>  <a href=\"?id,{$_GET['id']};a,{$_GET['a']};f,3\"><img src=\"images/icons/folders__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['forum_createsub']}</a>  <a href=\"?id,{$_GET['id']};a,{$_GET['a']};f,4\"><img src=\"images/icons/folders__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['forum_editsub']}</a></div>";
lentele($lang['admin']['forum'], $buttons);

unset($buttons);
//rikiuote
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
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1($sqlas);

}
if (isset($_POST['order2'])) {
	$array = str_replace("&", ",", $_POST['order2']);
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
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1($sqlas);

}
// Paspaustas kazkoks mygtukas
if (isset($_POST['action']) && $_POST['action'] == 'f_sukurimas') {
	$forumas = input($_POST['f_pav']);
	$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "d_forumai` (`pav`) VALUES ('" . $forumas . "')");

	if ($result) {
		msg($lang['system']['done'], $lang['system']['categorycreated']);
	} else {


		klaida($lang['system']['error'], ' <b>' . mysql_error() . '</b>');

	}

	unset($forumas, $result);
}

//Kategorijos redagavimas
if (isset($_POST['keisti']) && $_POST['keisti'] == $lang['admin']['edit']) {
	$f_id = (int)$_POST['f_edit'];
	$f_pav_keitimas = input($_POST['f_pav_keitimas']);
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `pav`='" . $f_pav_keitimas . "' WHERE `id`='" . $f_id . "'");
	if ($result) {
		msg($lang['system']['done'], $lang['system']['categoryupdated']);

	} else {
		klaida($lang['system']['error'], ' <b>' . mysql_error() . '</b>');

	}
	unset($f_info, $forumas, $result);
}
//Kategorijos trynimas (gali but problemu)
if (isset($_GET['d'])) {
	$f_id = (int)$_GET['d'];
	$strid = mysql_query1("SELECT id from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`='" . $f_id . "'");
	if (sizeof($strid) > 0) {
		foreach ($strid as $stridi) {
			$zinsid = mysql_query1("SELECT id from `" . LENTELES_PRIESAGA . "d_straipsniai` where `tid`=" . escape($stridi['id']) . "");
			if (sizeof($zinsid) > 0) {
				foreach ($zinsid as $zinsids) {
					$result2 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_zinute`  WHERE sid=" . escape($zinsids['id']) . "");
				}
			}
			//$result2 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_zinute`  WHERE `tid`='" . $strid['id'] . "' AND sid='" . $f_id . "'");
			$result3 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_straipsniai`  where `tid`=" . escape($stridi['id']) . "");

		}
	}
	$result = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_forumai`  WHERE `id`='" . $f_id . "'");
	$result2 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`='" . $f_id . "'");


	if ($result && $result2) {
		msg($lang['system']['done'], $lang['system']['categorydeleted']);

	} else {
		klaida($lang['system']['error'], ' <b>' . mysql_error() . '</b>');
	}
	unset($f_info, $forumas, $result);
}
//subkategorijos trynimas
if (isset($_GET['t'])) {
	$f_id = (int)$_GET['t'];
	//sita atlieka (istrina subkategorija)
	$result = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `id`='" . $f_id . "'");
	//turetu istrint zinutes
	$sql12 = mysql_query1("SELECT id from `" . LENTELES_PRIESAGA . "d_straipsniai` where `tid`='" . $f_id . "'");
	if (sizeof($sql12) > 0) {
		foreach ($sql12 as $sidas) {
			$result2 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_zinute`  WHERE sid='" . $sidas['id'] . "'") or die(mysql_error());

		}
	}
	//istina temas is kategorijos
	$result2 = mysql_query1("DELETE from `" . LENTELES_PRIESAGA . "d_straipsniai`  WHERE `tid`='" . $f_id . "'");

	if ($result) {
		msg($lang['system']['done'], $lang['admin']['forum_deletesub']);

	} else {
		klaida($lang['system']['error'], ' <b>' . mysql_error() . '</b>');
	}
	unset($f_id, $result2, $result);
}
//Subkategorijos kūrimas
if (isset($_POST['kurk']) && $_POST['kurk'] == $lang['admin']['forum_createsub']) {
	$f_id = (int)$_POST['f_forumas'];
	$f_tema = input($_POST['f_tema']);
	$f_aprasymas = input($_POST['f_aprasymas']);
	$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "d_temos` (`fid`, `pav`, `aprasymas`) VALUES ('" . $f_id . "', '" . $f_tema . "', '" . $f_aprasymas . "')");
	if ($result) {
		msg($lang['system']['done'], $lang['admin']['forum_createdsub']);

	} else {
		klaida($lang['system']['error'], '<b>' . mysql_error() . '</b>');

	}

	unset($f_id, $f_tema, $f_aprasymas, $result);
}
//Subkategorijos redagavimas
if (isset($_POST['subedit']) && $_POST['subedit'] == $lang['admin']['forum_select']) {

	$f_id = (int)$_POST['f_forumas'];
	$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `fid`='" . $f_id . "' ORDER by place");
	if (sizeof($sql) > 0) {
		$tema = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` where `id`='" . (int)$_POST['f_forumas'] . "'  ORDER BY `place` ASC limit 1");

		$li = '';
		foreach ($sql as $record1) {
			$li .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';t,' . $record1['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>  
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record1['id'] . ';f,' . $tema['id'] . '" style="align:right" ><img src="images/icons/pencil.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>  
<img src="images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" /> 
' . $record1['pav'] . '
</li> ';
		}
		$tekstas = '
<div id="la2" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>
			<ul id="test-list2">' . $li . '</ul>';
		lentele($lang['admin']['forum_editsub'], $tekstas);
	}
}
if (isset($_GET['r']) && isset($_GET['f'])) {
	$f_id = (int)$_GET['f'];
	$f_temos_id = (int)$_GET['r'];
	$sql = mysql_query1("SELECT pav FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `id`='" . $f_id . "' limit 1");
	$f_forumas = $sql['pav'];
	unset($sql);
	$t_info = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`='" . $f_temos_id . "' limit 1");
	$f_text = "
					
					<form name=\"subred\" action=\"?id," . $url['id'] . ";a,{$_GET['a']}\" method=\"post\">
					<table>
						<tr>
							<td width='10%'>{$lang['admin']['forum_category']}:</td>
							<td><b>" . $f_forumas . "</b><input type=\"hidden\" name=\"forumo_id\"  value='" . $f_id . "' /></td>
						<tr>
							<td>{$lang['admin']['forum_subcategory']}:</td>
							<td><input name=\"temos_pav\" type=\"text\" value='" . $t_info['pav'] . "' class=\"input\"><input type=\"hidden\" name=\"temos_id\"  value='" . $t_info['id'] . "' /></td>
						</tr>
							<td>{$lang['admin']['forum_subabout']}:</td>
							<td><input name=\"temos_apr\" type=\"text\" value='" . $t_info['aprasymas'] . "' class=\"input\"></td>
						</tr>
					</table>
					<input type=\"submit\"name=\"subred\" value=\"{$lang['admin']['edit']}\" class=\"submit\">
					</form>
				
				";
	lentele($lang['admin']['forum_editsub'], $f_text);
	unset($f_text, $t_info, $f_id, $f_temod_id, $sql, $f_forumas);
}
if (isset($_POST['subred']) && $_POST['subred'] == $lang['admin']['edit']) {
	$f_forumas = (int)$_POST['forumo_id'];
	$f_tema = (int)$_POST['temos_id'];
	$edit_tema = input($_POST['temos_pav']);
	$edit_aprasymas = input($_POST['temos_apr']);
	$result = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET `pav`='" . $edit_tema . "', `aprasymas`='" . $edit_aprasymas . "' WHERE `fid`='" . $f_forumas . "' AND `id`='" . $f_tema . "'");
	if ($result) {
		msg($lang['system']['done'], $lang['admin']['forum_updatedsub']);

	} else {
		klaida($lang['system']['error'], '<b>' . mysql_error() . '</b>');

	}

}
// #############################################################
// Parodome pasirinktos funkcijos laukelius
// ##############################################################
if (isset($url['f'])) {
	if ((int)$url['f'] == 1) {
		$f_text = "
				
						<form name=\"1\" action=\"?id," . $url['id'] . ";a,{$_GET['a']}\" method=\"post\">
							{$lang['admin']['forum_category']}:&nbsp;&nbsp;<input name=\"f_pav\" type=\"text\" value=\"\" class=\"input\">
							<input type=\"submit\" value=\"{$lang['system']['createcategory']}\" class=\"submit\">
							<input type=\"hidden\" name=\"action\"  value=\"f_sukurimas\" />
						</form>
					
					";
		lentele($lang['system']['createcategory'], $f_text);

	}
	//Kategorijos redagavimas
	if ((int)$url['f'] == 2) {
		$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` ORDER BY `place` ASC");
		if (sizeof($sql) > 0) {
			$li = "";

			$tekst = "
<form name=\"keisti\" action=\"?id," . $url['id'] . ";a,{$_GET['a']}\" method=\"post\">
					<table border=0 width=100%>
						<tr>
							<td width='10%'>{$lang['admin']['forum_category']}:</td>
							<td><select size=\"1\" name=\"f_edit\" class=\"select\">";
			foreach ($sql as $record1) {
				$tekst .= "<option value=" . $record1['id'] . ">" . $record1['pav'] . "</option>\n";
				$li .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record1['id'] . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>  
<img src="images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" /> 
' . $record1['pav'] . '
</li> ';
			}
			$tekst .= "</select>
  							</td>
  						</tr>
  						<tr>
  							<td>{$lang['admin']['forum_cangeto']}:</td>
  							<td><input name=\"f_pav_keitimas\" type=\"text\" value=\"\" class=\"input\"></td>
  						</tr>
  					</table>
  					<input type=\"submit\" name=\"keisti\" value=\"{$lang['admin']['edit']}\"> 
";

			$tekstas = '
<div id="la" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>
			<ul id="test-list">' . $li . '</ul>';
			lentele($lang['system']['editcategory'], $tekst);
			lentele($lang['admin']['forum_order'], $tekstas);
		} else {
			klaida($lang['system']['warning'], $lang['system']['nocategories']);
		}
		unset($f_text, $sql, $row);
	}
	//subkat. kūrimo forma
	if ((int)$url['f'] == 3) {
		$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` ORDER BY `place` ASC");
		if (sizeof($sql) > 0) {
			$f_text = "
					<form name=\"kurk\" action=\"?id," . $url['id'] . ";a,{$_GET['a']}\" method=\"post\">
					<table border=0 width=100%>
						<tr>
							<td width='10%'>{$lang['admin']['forum_category']}:</td>
							<td><select size=\"1\" name=\"f_forumas\" class=\"select\">";
			foreach ($sql as $row) {
				$f_text .= "<option value=" . $row['id'] . ">" . $row['pav'] . "</option>\n";
			}
			$f_text .= "</select>
  							</td>
  						</tr>
  						<tr>
  							<td>{$lang['admin']['forum_subcategory']}:</td>
  							<td><input name=\"f_tema\" type=\"text\" value=\"\" class=\"input\"></td>
  						</tr>
  						<tr>
  							<td>{$lang['admin']['forum_subabout']}:</td>
  							<td><input name=\"f_aprasymas\" type=\"text\" value=\"\" class=\"input\">
  						</tr>
  					</table>
  					<input type=\"submit\" name=\"kurk\" value=\"{$lang['admin']['forum_createsub']}\" class=\"submit\"></form>
					
					";
			lentele($lang['admin']['forum_createsub'], $f_text);
		} else {
			klaida($lang['system']['warning'], $lang['system']['nocategories']);
		}
		unset($f_text, $sql, $row);
	}
	//subkat redag?
	if ((int)$url['f'] == 4) {
		$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` ORDER BY `place` ASC");
		if (sizeof($sql) > 0) {
			$f_text = "
					
					<form name=\"subedit\" action=\"?id," . $url['id'] . ";a,{$_GET['a']}\" method=\"post\">
					<table border=0 width=100%>
						<tr>
							<td width='50%'>{$lang['admin']['forum_subwhere']}:</td>
							<td><select size=\"1\" name=\"f_forumas\" class=\"select\">";
			foreach ($sql as $row) {
				$f_text .= "<option value='" . $row['id'] . "'>" . $row['pav'] . "</option>\n";
			}
			$f_text .= "
  								</select>
  							</td>
  						</tr>
  					</table>
  					<input type=\"submit\" name=\"subedit\" value=\"{$lang['admin']['forum_select']}\" class=\"submit\">
					<input type=\"hidden\" name=\"action\"  value=\"f_temos_edit\" />
					</form>
									";
			lentele($lang['admin']['forum_editsub'], $f_text);
		} else {
			klaida($lang['system']['warning'], $lang['system']['nocategories']);
		}

		unset($f_text, $sql, $row);
	}
}
//gadina šiektiek
//unset($_POST);


?>
	
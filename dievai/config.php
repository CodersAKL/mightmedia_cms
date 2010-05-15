<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 360 $
 * @$Date: 2009-11-20 17:27:27 +0200 (Fri, 20 Nov 2009) $
 **/

if (!defined("LEVEL") || !defined("OK")) {
	redirect('location: http://' . $_SERVER["HTTP_HOST"]);
}

if (isset($_POST) && !empty($_POST) && isset($_POST['Konfiguracija'])) {
		$q = array();
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape($_POST['Apie']) . ",'Apie')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['Apie'])."";
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(input(strip_tags($_POST['keywords']))) . ",'keywords')  ON DUPLICATE KEY UPDATE `val`=" . escape(input(strip_tags($_POST['keywords'])))."";
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(input(strip_tags($_POST['pirminis']))) . ",'pirminis')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['pirminis'])."";
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(input(strip_tags($_POST['Pavadinimas']))) . ",'Pavadinimas')  ON DUPLICATE KEY UPDATE `val`=" . escape(input(strip_tags($_POST['Pavadinimas'])));
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape($_POST['Copyright']) . ",'Copyright')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['Copyright']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(input(strip_tags($_POST['Pastas']))) . ",'Pastas')  ON DUPLICATE KEY UPDATE `val`=" . escape(input(strip_tags($_POST['Pastas'])));
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape((int)$_POST['Palaikymas']) . ",'Palaikymas')  ON DUPLICATE KEY UPDATE `val`=" . escape((int)$_POST['Palaikymas']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape((int)$_POST['News_limit']) . ",'News_limit')  ON DUPLICATE KEY UPDATE `val`=" . escape((int)$_POST['News_limit']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape($_POST['Maintenance']). ",'Maintenance')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['Maintenance']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(input(strip_tags($_POST['Stilius']))) . ",'Stilius')  ON DUPLICATE KEY UPDATE `val`=" . escape(input(strip_tags($_POST['Stilius'])));
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(basename($_POST['pirminis'],'.php')) . ",'pirminis')  ON DUPLICATE KEY UPDATE `val`=" . escape(basename($_POST['pirminis'],'.php'));
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape(basename($_POST['kalba'])) . ",'kalba')  ON DUPLICATE KEY UPDATE `val`=" . escape(basename($_POST['kalba']));
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape((int)$_POST['keshas']) . ",'keshas')  ON DUPLICATE KEY UPDATE `val`=" . escape((int)$_POST['keshas']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape((int)$_POST['koment']) . ",'kmomentarai_sveciams')  ON DUPLICATE KEY UPDATE `val`=" . escape((int)$_POST['koment']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape($_POST['F_urls']) . ",'F_urls')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['F_urls']);
	$q[] = "INSERT INTO `" . LENTELES_PRIESAGA . "nustatymai` (`val`,`key`) VALUES (" . escape($_POST['Editor']) . ",'Editor')  ON DUPLICATE KEY UPDATE `val`=" . escape($_POST['Editor']);
	foreach ($q as $sql) {
		mysql_query1($sql);
	}
	delete_cache("SELECT id, reg_data, gim_data, login_data, nick, vardas, levelis, pavarde FROM `" . LENTELES_PRIESAGA . "users` WHERE levelis=1 OR levelis=2");
	redirect(url('?id,999;a,'.$_GET['a'].''));
}

$stiliai = getDirs(ROOT.'stiliai/', 'remontas');
$editors = getDirs('htmlarea/', 'svn');
$editors['textarea'] = 'textarea';
$kalbos = getFiles(ROOT.'lang/');
foreach ($kalbos as $file) {
	if ($file['type'] == 'file') {
		$kalba[basename($file['name'])] = basename($file['name']);
	}
}

if(isset($conf['puslapiai']) && count($conf['puslapiai']) > 0){
  $puslapiai = array_keys($conf['puslapiai']);
  foreach ($puslapiai as $key) {
    $psl[$key] = (isset($lang['pages'][$key])?$lang['pages'][$key]:nice_name(basename($key,'.php')));
  }
} else 
    $psl[]='';
$nustatymai = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "reg"),
	"{$lang['admin']['sitename']}:" => array("type" => "text", "value" => input($conf['Pavadinimas']), "name" => "Pavadinimas", "class" => "input"), 
	"{$lang['admin']['homepage']}:" => array("type" => "select", "value" => $psl, "selected" =>(isset($conf['pirminis'])?$conf['pirminis'].'.php':''), "name" => "pirminis", "class" => "select"), 
	"{$lang['admin']['about']}:" => array("type" => "textarea", "name" => "Apie", "value" => (isset($conf['Apie']) ? $conf['Apie'] : ''), "extra" => "rows=5", "class" => "input"), 
	"{$lang['admin']['keywords']}:" => array("type" => "text", "value" => input($conf['Keywords']), "name" => "keywords", "rows" => "3", "class" => "input"), 
	"{$lang['admin']['copyright']}:" => array("type" => "text", "value" => input($conf['Copyright']), "name" => "Copyright", "class" => "input"), 
	"{$lang['admin']['email']}:" => array("type" => "text", "value" => input($conf['Pastas']), "name" => "Pastas", "class" => "input"), 
	"{$lang['admin']['maintenance']}?:" => array("type" => "select", "value" => array("1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}"), "selected" => input($conf['Palaikymas']), "name" => "Palaikymas", "class" => "select"), 
	"{$lang['admin']['maintenancetext']}:" =>	array("type" => "textarea", "name" => "Maintenance", "value" => (isset($conf['Maintenance']) ? $conf['Maintenance'] : ''), "extra" => "rows=5", "class" => "input"),
"Friendly url:"=>array("type"=>"select","value"=>array('/'=>'/',';'=>';','0'=>$lang['admin']['off']),"selected"=>"".$conf['F_urls']."","name"=>"F_urls"),
	"{$lang['admin']['comm_guests']}:" => array("type" => "select", "value" => array("1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}","3"=>"{$lang['admin']['comments_off']}"), "selected" => input(@$conf['kmomentarai_sveciams']), "name" => "koment", "class" => "select"), 
	"{$lang['admin']['newsperpage']}:" => array("type" => "text", "value" => input($conf['News_limit']), "name" => "News_limit", 'extra' => "on`key`up=\"javascript:this.value=this.value.replace(/[^0-9]/g, '');\"", "class" => "select"), 
	"{$lang['admin']['cache']}:" => array("type" => "select", "value" => array("1" => "{$lang['admin']['yes']}", "0" => "{$lang['admin']['no']}"), "selected" => input($conf['keshas']), "name" => "keshas", "class" => "select"), 
	"{$lang['admin']['theme']}:" => array("type" => "select", "value" => $stiliai, "selected" => input($conf['Stilius']), "name" => "Stilius", "class" => "select"), 
	"{$lang['admin']['lang']}:" => array("type" => "select", "value" => $kalba, "selected" => input($conf['kalba']), "name" => "kalba", "class" => "select"), 
	"{$lang['admin']['editor']}:" => array("type" => "select", "value" => $editors, "selected" => input($conf['Editor']), "name" => "Editor", "class" => "select"), 
	"" => array("type" => "submit", "name" => "Konfiguracija", "value" => "{$lang['admin']['save']}", "class" => "submit")
);


include_once (ROOT."priedai/class.php");
$bla = new forma();
lentele($lang['admin']['config'], $bla->form($nustatymai));


?>
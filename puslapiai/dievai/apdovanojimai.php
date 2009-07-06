<?php

/**
 * @author Paulius
 * @copyright 2009
 */

$buttons="<div class=\"btns\">
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,1\" class=\"btn\"><span><img src=\"images/icons/trophy.png\" alt=\"\" class=\"middle\"/>Apdovanotųjų sąrašas</span></a>
	<a href=\"?id,{$_GET['id']};a,{$_GET['a']};v,2\" class=\"btn\"><span><img src=\"images/icons/trophy.png\" alt=\"\" class=\"middle\"/>Suteikti apdovanojimą</span></a>
</div>";


lentele("Apdovanojimų administravimas", $buttons);
$medal = glob('images/icons/*.png');
$medaliai=array();
foreach($medal as $med){
	$medaliai[$med]=str_replace('images/icons/','',$med);
}
	
		include_once ("priedai/class.php");
		$bla = new forma();
		$ble = new Table();
if(isset($url['d'])){
	mysql_query1("DELETE FROM `".LENTELES_PRIESAGA."apdovanojimai` WHERE `id`=".escape($url['d'])."");
	header("LOCATION: ?id,{$url['id']};a,{$url['a']}");
	die();
}elseif(isset($url['r'])){

	$info = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "apdovanojimai` WHERE id=" . escape($url['r']) . " LIMIT 1");
	if ($info) {
			if(isset($_POST['nuopelnas'])){
		$info = mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "apdovanojimai` SET `nuopelnas`=".escape($_POST['nuopelnas']).", `img`=".escape($_POST['img'])." WHERE id=" . escape($url['r']) . "");
		msg($lang['system']['done'],'Apdovanojimas atnaujintas');
		redirect("?id,{$_GET['id']};a,{$_GET['a']};v,1",'meta');
	}
		$text = array('Form' => array('action' => "", "method" => "post", "name" => "edit"), 'Nuopelnas' => array('type' => 'text', 'name' => 'nuopelnas',  'value' => (isset($info['nuopelnas']) ? $info['nuopelnas'] : "")), "Medalis" => array("type" => "select", "value" => $medaliai,"extra" => "onchange=\"$('#img').attr({ src: this.value });\"", "name" => "img", "class" => "input", "selected" => (isset($info['img']) ? $info['img'] : '')), " " => array("type" => "submit", "value" => "Saugoti", "name" => "saugoti", "class" => "input"));

		lentele('Redaguoti apdovanojimą ', 'Medalis:<img src="'.(isset($info['img']) ? $info['img'] : '').'" id="img" />'.$bla->form($text));
	
	} 

}elseif(isset($url['v'])){
	if($url['v']==1){
			$resultas = mysql_query1("SELECT * FROM `".LENTELES_PRIESAGA."apdovanojimai");
		if (sizeof($resultas) > 0) {
			foreach ($resultas as $row2) {
				$info3[] = array($lang['admin']['user_name'] => user($row2['nick'], $row2['uid']), "Info" =>'<img src="'.$row2['img'].'" class="middle" />'.$row2['nuopelnas'], "{$lang['admin']['action']}" => "<a href='?id," . $_GET['id'] . ";a," . $_GET['a'] . ";r," . $row2['id'] . "'><img src='images/icons/pencil.png' border='0' class='middle' /></a> <a href='?id," . $_GET['id'] . ";a," . $_GET['a'] . ";d," . $row2['id'] . "' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\" title='{$lang['admin']['delete']}'><img src='images/icons/cross.png' border='0' class='middle' /></a>");
			}
			
			lentele("Apdovanoti nariai", $ble->render($info3));

		}
	}
elseif($url['v']==2){
	$sql = mysql_query1("SELECT `nick`,`id` FROM `" . LENTELES_PRIESAGA . "users`");
	if(isset($_POST['nuopelnas'])){
		$user=explode(';',$_POST['narys']);
		mysql_query1("INSERT INTO `".LENTELES_PRIESAGA."apdovanojimai` (`uid`, `nick`, `img`, `nuopelnas`) VALUES (" . escape($user[1]) . ",  " . escape($user[0]) . ", " . escape($_POST['img']) . ", " . escape($_POST['nuopelnas']) . ")");
		msg($lang['system']['done'],'Apdovanojimas suteiktas');
		redirect("?id,{$_GET['id']};a,{$_GET['a']};v,1",'meta');
	}
	foreach($sql as $row){
		$nariai[$row['nick'].';'.$row['id']]=$row['nick'];
	}
			$text = array('Form' => array('action' => "", "method" => "post", "name" => "edit"),
			"Narys" => array("type" => "select", "value" => $nariai, "name" => "narys", "class" => "input"),
			 'Nuopelnas' => array('type' => 'text', 'name' => 'nuopelnas',  'value' => ''), "Medalis" => array("type" => "select", "value" => $medaliai,"extra" => "onchange=\"$('#img').attr({ src: this.value });\"", "name" => "img", "class" => "input"), " " => array("type" => "submit", "value" => "Suteikti", "name" => "saugoti", "class" => "input"));

		lentele('Suteikti apdovanojimą ', 'Medalis:<img src="" id="img" />'.$bla->form($text));
}
}

?>
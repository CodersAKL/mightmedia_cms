<?php

/**
 * @author Paulius
 * @copyright 2009
 */
include_once ("priedai/class.php");
		
		$ble = new Table();
$sql = mysql_query1("SELECT `id` as `apid`,`nick`,`levelis`,(SELECT count(`id`) FROM `".LENTELES_PRIESAGA."apdovanojimai` WHERE `uid`=`apid`)AS `apdovanoj` FROM `".LENTELES_PRIESAGA."users` ORDER by `apdovanoj` DESC LIMIT 10");
  
		if (sizeof($sql) > 0) {
			foreach ($sql as $row2) {
				$info3[] = array($lang['admin']['user_name'] => user($row2['nick'], $row2['apid'],$row2['levelis']),"Grupė"=>(isset($conf['level'][$row2['levelis']]['pavadinimas'])?$conf['level'][$row2['levelis']]['pavadinimas']:'-'), "Apdovanojimų" =>$row2['apdovanoj']);
			}
			
			lentele("Apdovanoti nariai", $ble->render($info3));
}
?>
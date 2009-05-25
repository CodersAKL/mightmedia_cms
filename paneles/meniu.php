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

function build_menu($data, $id=0){
	$re="";
   foreach ($data[$id] as $row){
      if (isset($data[$row['id']])){
         $re.= "<li><a href=\"?id,{$row['id']}\">".$row['pavadinimas']." ></a><ul>";
         $re.=build_menu($data, $row['id']);
         $re.= "</ul></li>";
      } else $re.= "<li><a href=\"?id,{$row['id']}\">".$row['pavadinimas']."</a></li>";
   }
   return $re;
}

$res = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' ORDER BY `place` ASC");
foreach ($res as $row){ $data[$row['parent']][] = $row;}
$text='<div id="navigation"><ul>'.build_menu($data).'</ul></div>';


?>
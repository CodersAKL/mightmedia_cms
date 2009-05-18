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

if (isset($url['m']) && isnum($url['m']) && $url['m'] > 0) {
	$m = escape(ceil((int)$url['m']));
} else {
	$m = 0;
}
$limit = $conf['fotoperpsl'];
if (isset($url['p']) && isnum($url['p']) && $url['p'] > 0) {
	$p = escape(ceil((int)$url['p']));
} else {
	$p = 0;
}
$text = '
<script type="text/javascript" src="javascript/lightbox.js"></script>
	<script src="javascript/scriptaculous/lib/prototype.js" type="text/javascript"></script>
	<script src="javascript/scriptaculous/src/scriptaculous.js?load=effects,builder" type="text/javascript"></script>

	<link rel="stylesheet" href="stiliai/lightbox.css" type="text/css" media="screen" />

';

$sql = mysql_query1("SELECT
  `" . LENTELES_PRIESAGA . "grupes`.`pavadinimas` AS `Kategorija`,
    `" . LENTELES_PRIESAGA . "grupes`.`pav` AS `img`,
        `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises`,
  `" . LENTELES_PRIESAGA . "galerija`.`pavadinimas`,
  `" . LENTELES_PRIESAGA . "galerija`.`id`,
  `" . LENTELES_PRIESAGA . "galerija`.`apie`,
  `" . LENTELES_PRIESAGA . "galerija`.`data`,
  `" . LENTELES_PRIESAGA . "users`.`nick` AS `Nick`,
  `" . LENTELES_PRIESAGA . "galerija`.`file`
  FROM
  `" . LENTELES_PRIESAGA . "grupes`
  Inner Join `" . LENTELES_PRIESAGA . "galerija` ON `" . LENTELES_PRIESAGA . "grupes`.`id` = `" . LENTELES_PRIESAGA . "galerija`.`categorija`
  Inner Join `" . LENTELES_PRIESAGA . "users` ON `" . LENTELES_PRIESAGA . "galerija`.`autorius` = `" . LENTELES_PRIESAGA . "users`.`id`
  WHERE  
   `" . LENTELES_PRIESAGA . "galerija`.`autorius` =  '" . $m . "' AND `" . LENTELES_PRIESAGA . "galerija`.`rodoma` =  'TAIP'
  ORDER BY
  `" . LENTELES_PRIESAGA . "galerija`.`id` DESC
  LIMIT  $p,$limit");
$text .= "<table border=\"0\">
	<tr>
		<td >
";
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		if (isset($conf['puslapiai']['galerija.php']['id'])) {
			$text .= "
				
				<div  class=\"img_left\" >
			<a rel=\"lightbox[" . $url['id'] . "]\" href=\"galerija/" . $row['file'] . "\" title=\"" . $row['pavadinimas'] . ": " . $row['apie'] . "\">
				<img src=\"galerija/mini/" . $row['file'] . "\" alt=\"\" />
			</a><br>
<a href=\"#\" title=\"<b>{$lang['admin']['gallery_author']}:</b> " . $autorius . "<br>
			<b>{$lang['admin']['gallery_date']}:</b> " . date('Y-m-d H:i:s ', $row['data']) . "
			\"><img src='images/icons/information.png' border='0'></a><a href=\"?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['id'] . "\" title=\"{$lang['admin']['gallery_comments']}\"><img src='images/icons/comment.png' alt='C' border='0'></a>
			<a href=\"galerija/originalai/" . $row['file'] . "\" title=\"{$lang['download']['download']}\"><img src='images/icons/disk.png' border='0'></a>
		</div>";

		}
	}
}
$text .= '</td>
	</tr>
</table>';
$name = mysql_query1("SELECT nick FROM " . LENTELES_PRIESAGA . "users WHERE id=  '" . $m . "' LIMIT 1");
lentele("" . $name['nick'] . "", $text);

?>
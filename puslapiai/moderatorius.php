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

if (isset($_SESSION['mod']) && !empty($_SESSION['mod'])) {

	$failai = unserialize($_SESSION['mod']);
	$text = "<table border=\"0\">
	<tr>
		<td >
<div>";
	foreach ($failai as $id => $failas) {
		if ($failas != 'com' && $failas != 'frm') {
			$text .= "<div class=\"blokas\"><center><a href=\"".url("?id," . $url['id'] . ";a," . ($id + 1) ). "\"><img src=\"images/admin/" . basename($failas, ".php") . ".png\" />" .(isset($lang['admin'][basename($failas, ".php")])?$lang['admin'][basename($failas, ".php")]:nice_name($failas)) . "</a></center></div>";
		}
		//$text.=$failas;
	}
	$text .= "</div><br style=\"clear:left\"/></td>
	</tr>
</table>

";

	lentele($page_pavadinimas, $text);
	if (isset($url['a'])) {
		if (file_exists(dirname(__file__) . "/dievai/" . $failai[((int)$url['a'] - 1)])) {
			include_once (dirname(__file__) . "/dievai/" . $failai[((int)$url['a'] - 1)]);
		} else {
			klaida("{$lang['system']['error']}", "{$lang['system']['nopage']}");
		}
	}
}

?>
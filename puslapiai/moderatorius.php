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

  function editor($tipas = 'jquery', $dydis = 'standartinis', $id = false, $value = ''){
    return editorius($tipas, $dydis, $id, $value);
  }
	$failai = unserialize($_SESSION['mod']);
	$text = "<table border=\"0\">
	<tr>
		<td >
<div>";
	foreach ($failai as $id => $failas) {
		if ($failas != 'com' && $failas != 'frm') {
			$text .= "<div class=\"blokas\"><center><a href=\"".url("?id," . $url['id'] . ";a," . ($id + 1) ). "\"><img src=\"images/admin/" . basename($failas, ".php") . ".png\" />" .(isset($lang['admin'][basename($failas, ".php")])?$lang['admin'][basename($failas, ".php")]:nice_name($failas)) . "</a></center></div>";
		}
	}
	$text .= "</div><br style=\"clear:left\"/></td>
	</tr>
</table>

";

	lentele($page_pavadinimas, $text);
	if (isset($url['a'])) {
    if (file_exists(ROOT . "dievai/" . $failai[((int)$url['a'] - 1)])) {
			include_once (ROOT . "dievai/" . $failai[((int)$url['a'] - 1)]);
		} else {
			klaida("{$lang['system']['error']}", "{$lang['system']['nopage']}");
		}
	}
} else 
    klaida("{$lang['system']['error']}", "{$lang['system']['nopage']}");

?>
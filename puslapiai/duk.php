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


$nr = 0;

if (isset($_GET) && !empty($_GET) && defined("LEVEL") && LEVEL == 1) {
	//Jei adminas bando trinti. $_GET['d'] == ID
	if (isset($_GET['d']) && isnum($_GET['d']) && $_GET['d'] > 0) {
		$q = "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape((int)$_GET['d']);
		mysql_query1($q) or die($lang['system']['error']);
		redirect("?id," . $_GET['id'], "header");

	} elseif (isset($_GET['n']) || isset($_GET['e'])) {

		$klausimas = '';
		$atsakymas = '';
		$order = $nr;
		$id = '';

		//Jeigu adminas nori redaguoti
		// url['e'] pasakome koki ID redaguosime
		if (isset($_GET['e']) && isnum($_GET['e']) && $_GET['e'] > 0) {
			$value = $lang['faq']['edit'];
			$id = ceil((int)$_GET['e']);
			if ($id < 0) {
				$id = 0;
			}
			$sql = mysql_fetch_assoc(mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape($id) . " LIMIT 1")) or klaida(mysql_error());
			$klausimas = $sql['klausimas'];
			$atsakymas = $sql['atsakymas'];
			$order = (int)$sql['order'];
		}


		//Jei adminas bando rasyti nauja DUK
		elseif (isset($_GET['n']) && $_GET['n'] == 1) {
			$value = $lang['faq']['submit'];
		}

		$duk = array("Form" => array("action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "duk"), "{$lang['faq']['question']}:" => array("type" => "text", "value" => input($klausimas), "name" => "Klausimas", "style" => "width:100%"), "{$lang['faq']['answer']}:" => array("type" => "string", "value" => editorius('tiny_mce', 'mini', 'Atsakymas', (isset($atsakymas) ? $atsakymas : ''))), "{$lang['faq']['order']}:" => array("type" => "text", "value" => input((int)$order), "name" => "Order", "style" => "width:50%"), "" => array("type" => "hidden", "value" => input($id), "name" => "id", "id" => "id"), "" => array("type" => "submit", "name" => "duk", "value" => $value));

		include_once ("priedai/class.php");
		$bla = new forma();
		lentele("{$lang['faq']['new']}", $bla->form($duk));

	}
}

if (isset($_POST) && !empty($_POST) && isset($_POST['duk']) && defined("LEVEL") && LEVEL == 1) {
	//apsauga nuo kenksmingo kodo
	include_once ('priedai/safe_html.php');
	// nurodome masyva leidziamu elementu DUK
	// - tagai kurie uzdaromi atskirai (<p></p>) pazymeti kaip 1
	// - tagai kuriuos uzdaryti nebutina (<hr>) zymimi kaip 0
	$tags = array("p" => 1, "br" => 0, "a" => 1, "img" => 0, "li" => 1, "ol" => 1, "ul" => 1, "b" => 1, "i" => 1, "em" => 1, "strong" => 1, "del" => 1, "ins" => 1, "u" => 1, "code" => 1, "pre" => 1, "blockquote" => 1, "hr" => 0, "span" => 1, "font" => 1, "h1" => 1, "h2" => 1, "h3" => 1, "table" => 1, "tr" => 1, "td" => 1, "th" => 1, "tbody" => 1, "div" => 1);

	$klausimas = safe_html($_POST['Klausimas'], $tags);
	$atsakymas = safe_html(str_replace(array('<br>'),array('<br />'),$_POST['Atsakymas']), $tags);
	$order = ceil((int)$_POST['Order']);
	$id = ceil((int)$url['e']);

	//jeigu rasom nauja
	if ($_POST['duk'] == $lang['faq']['submit']) {
		$q = "INSERT INTO `" . LENTELES_PRIESAGA . "duk` (`klausimas`,`atsakymas`,`order`) VALUES (
		" . escape($klausimas) . ",
		" . escape($atsakymas) . ",
		" . escape($order) . ");";
		mysql_query1($q);
		redirect("?id," . $url['id'], "header");
	}

	//jeigu redaguojam
	elseif ($_POST['duk'] == $lang['faq']['edit']) {
		$q = "UPDATE `" . LENTELES_PRIESAGA . "duk` SET
		`atsakymas` = " . escape($atsakymas) . ",
		`klausimas` = " . escape($klausimas) . ",
		`order` = " . escape((int)$_POST['Order']) . " WHERE `id`=" . $id . " LIMIT 1 ;";
		mysql_query1($q) or die(mysql_error());
		redirect("?id," . $url['id'], "header");
	}

}

$text = '';
$extra = "<ol>";

$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "duk` ORDER by `order` ASC") or klaida("Klaida", "Atsiprašome - šis puslapis dar kuriamas");
if (mysql_num_rows($sql) > 0) {
	while ($row = mysql_fetch_assoc($sql)) {
		$nr++;
		$extra .= "<li><a href='#" . $row['id'] . "'>" . $row['klausimas'] . "</a></li>\n";
		$text .= "<h3>" . $nr . ". " . (defined("LEVEL") && LEVEL == 1 ? "<a href='" . url("d," . (int)$row['id'] . "") . "' onclick=\"return confirm('" . $lang['faq']['delete'] . "?')\"><img src='images/icons/control_delete_small.png' class='middle' alt='" . $lang['faq']['delete'] . "' border='0' title='" . $lang['faq']['delete'] . "?' /></a><a href='" . url("e," . (int)$row['id'] . "") . "'><img src='images/icons/brightness_small_low.png' class='middle' alt='" . $lang['faq']['edit'] . "' border='0' title='" . $lang['faq']['edit'] . "?' /></a>" : "") . "<a name='" . $row['id'] . "'>" . $row['klausimas'] . "</a></h3>\n<blockquote>" . $row['atsakymas'] . "</blockquote>\n";
	}

}

lentele($lang['faq']['questions'], $extra . "</ol>" . (defined("LEVEL") && LEVEL == 1 ? "<button onclick=\"location.href='?id," . $_GET['id'] . ";n,1'\">{$lang['faq']['new']}</button>" : "") . "<div style='padding-bottom:20px'></div>");
lentele($lang['faq']['answers'], $text);
unset($extra, $text);
/*
* Pabaiga
*/

?>
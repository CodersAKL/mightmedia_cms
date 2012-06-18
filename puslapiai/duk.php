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

if (ar_admin('com')) {  //jei ledzia moderuot komentarus, leidzia ir duk'a
	//Jei adminas bando trinti. $_GET['d'] == ID
	if (isset($_GET['d'])) {
		mysql_query1("DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape((int)$_GET['d']));
		redirect(url("?id," . $_GET['id']), "header");

	} elseif (isset($_GET['n']) || isset($_GET['e'])) {

		$klausimas = '';
		$atsakymas = '';
		$order = 0;
		$id = '';

		//Jeigu adminas nori redaguoti
		// url['e'] pasakome koki ID redaguosime
		if (isset($_GET['e'])) {
			$value = $lang['faq']['edit'];
			$id = (int)$_GET['e'];
			$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape($id) . " LIMIT 1");
			$klausimas = $sql['klausimas'];
			$atsakymas = $sql['atsakymas'];
			$order = (int)$sql['order'];
		}


		//Jei adminas bando rasyti nauja DUK
		elseif (isset($_GET['n']) && $_GET['n'] == 1) {
			$value = $lang['faq']['submit'];
		}

		$duk = array("Form" => array("action" => url("?id,".$conf['puslapiai'][basename(__file__)]['id']), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "duk"), "{$lang['faq']['question']}:" => array("type" => "text", "value" => input($klausimas), "name" => "Klausimas", "class" => "input"), "{$lang['faq']['answer']}:" => array("type" => "string", "value" => editorius('standartinis', 'mini', 'Atsakymas', (isset($atsakymas) ? $atsakymas : ''))), "{$lang['faq']['order']}:" => array("type" => "text", "value" => input((int)$order), "name" => "Order", "class" => "input"), " " => array("type" => "hidden", "value" => input($id), "name" => "eid", "id" => "id"), "" => array("type" => "submit", "name" => "dukas", "value" => $value));
        //verciam msayva i forma
		include_once ("priedai/class.php");
		$form = new forma();
		lentele("{$lang['faq']['new']}", $form->form($duk));

	}


  if (isset($_POST['dukas'])) {
    //echo "asd";
    $klausimas = $_POST['Klausimas'];
    $atsakymas =  $_POST['Atsakymas'];
    $order = (int)$_POST['Order'];
    $id = (int)$_POST['eid'];

    //jeigu rasom nauja
    if ($_POST['dukas'] == $lang['faq']['submit']) {
      $q = "INSERT INTO `" . LENTELES_PRIESAGA . "duk` (`klausimas`,`atsakymas`,`order`,`lang`) VALUES (
      " . escape($klausimas) . ",
      " . escape($atsakymas) . ",
      " . escape($order) . ",
      " . escape(lang()) . ");";
      mysql_query1($q);
      redirect(url("?id," . $url['id']), "header");
    }

    //jeigu redaguojam
    if ($_POST['dukas'] == $lang['faq']['edit']) {
      $q = "UPDATE `" . LENTELES_PRIESAGA . "duk` SET
      `atsakymas` = " . escape($atsakymas) . ",
      `klausimas` = " . escape($klausimas) . ",
      `order` = " . escape((int)$_POST['Order']) . " WHERE `id`=" . $id . " LIMIT 1 ;";
      mysql_query1($q);
      redirect(url("?id," . $url['id']), "header");
    }


  }
}
$text = '';
$extra = "<ol>";
$nr = 0;
$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `lang` = ".escape(lang())." ORDER by `order` ASC", 3600);
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		$nr++;
		$extra .= "<li><a href='".url('?id,'.$_GET['id'])."#" . $row['id']."'>" . $row['klausimas'] . "</a></li>\n";
		$text .= "<h3>" . $nr . ". <a name='" . $row['id'] . "'>" . $row['klausimas'] . "</a>" . ($_SESSION['level'] == 1 ? " <a href='" . url("d," . (int)$row['id'] . "") . "' onclick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='images/icons/cross_small.png' class='middle' alt='" . $lang['faq']['delete'] . "' border='0' title='" . $lang['faq']['delete'] . "' /></a> <a href='" . url("e," . (int)$row['id'] . "") . "'><img src='images/icons/pencil_small.png' class='middle' alt='" . $lang['faq']['edit'] . "' border='0' title='" . $lang['faq']['edit'] . "' /></a>" : "") . "</h3>\n" . $row['atsakymas'] . "\n";
	}

}

lentele($lang['faq']['questions'], $extra . "</ol>" . (ar_admin('com') ? "<button onclick=\"location.href='".url("?id," . $_GET['id'] . ";n,1")."'\">{$lang['faq']['new']}</button>" : "") . "<div style='padding-bottom:20px'></div>");
lentele($lang['faq']['answers'], $text);
unset($extra, $text);
/*
* Pabaiga
*/

?>
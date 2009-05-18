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
$tags = array("p" => 1, "br" => 0, "a" => 1, "img" => 0, "li" => 1, "ol" => 1, "ul" => 1, "b" => 1, "i" => 1, "em" => 1, "strong" => 1, "del" => 1, "ins" => 1, "u" => 1, "code" => 1, "pre" => 1, "blockquote" => 1, "hr" => 0, "span" => 1, "font" => 1, "h1" => 1, "h2" => 1, "h3" => 1, "table" => 1, "tr" => 1, "td" => 1, "th" => 1, "tbody" => 1, "div" => 1, "embed" => 1);
$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' ORDER BY `pavadinimas` DESC");
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		$kategorijos[$row['id']] = $row['pavadinimas'];
	}
	$i = 1;
} else {
	$kategorijos[] = "Kategorijų nėra";
	$i = 0;
}

if (isset($_POST['action']) && $_POST['action'] == 'Pateikti') {
	print_r($_POST);
	if (isset($_POST['naujiena']) && isset($_POST['pav'])) {
		//apsauga nuo kenksmingo kodo
		include_once ('priedai/safe_html.php');

		$naujiena = safe_html(str_replace(array("&#39;"), array("'"), $_POST['naujiena']), $tags);
		//$placiau = safe_html(str_replace(array("&#39;"), array("'"), $_POST['placiau']), $tags);
		$komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
		$pavadinimas = input(strip_tags($_POST['pav']));
		$kategorija = (int)$_POST['kategorija'];
		$autorius = (isset($_SESSION['username']) ? $_SESSION['username'] : 'Svečias');

		if (empty($naujiena) || empty($pavadinimas)) {
			$error = "Nepilnai užpildyti laukeliai";
		}
		if (!isset($error)) {
			$result = mysql_query1("
				INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, kategorija)
				VALUES (" . escape($pavadinimas) . ", " . escape($naujiena) . ", '',  '" . time() . "', '" . $autorius . "', " . escape($komentaras) . ", " . escape($kategorija) . ")");
			if ($result) {
				msg("Informacija", "Naujiena sėkmingai pateikta administracijos peržiūrai");
			} else {
				klaida("Klaida", "Naujiena nepatalpinta. Klaida:<br><b>" . mysql_error() . "</b>");
			}
		} else {
			klaida("Klaida", $error);
		}
		redirect("?id," . $_GET['id'] . ";", "meta");

	} else {
		klaida("Dėmesio", "Užpildykite visus laukelius.");
	}
}
if ($i == 1) {
	include_once ("priedai/class.php");
	$bla = new forma();
	$naujiena = array("Form" => array("action" => "", "method" => "post", "name" => "reg"), "Pavadinimas:" => array("type" => "text", "value" => '', "name" => "pav", "style" => "width:100%"), "Komentarai:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "style" => "width:100%"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "style" => "width:100%"), "Naujiena:" => array("type" => "string", "value" => editorius('tiny_mce', 'standartinis', array('naujiena' => 'Glaustai', 'placiau' => 'Plačiau'))), 'Pateikti' => array("type" => "submit", "name" => "action", "value" => 'Pateikti'));
	lentele('Naujienos rašymas', $bla->form($naujiena));
} else {
	klaida("Dėmesio", "Nėra kategorijų.");
}

?>
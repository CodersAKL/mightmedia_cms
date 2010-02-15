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
	//print_r($_POST);
	if (isset($_POST['naujiena']) && isset($_POST['pav'])) {
		

		$naujiena = $_POST['naujiena'];
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
		redirect(url("?id," . $_GET['id']), "meta");

	} else {
		klaida("Dėmesio", "Užpildykite visus laukelius.");
	}
}
if ($i == 1) {
	include_once ("priedai/class.php");
	$bla = new forma();
	$naujiena = array("Form" => array("action" => "", "method" => "post", "name" => "reg"), "Pavadinimas:" => array("type" => "text", "value" => '', "name" => "pav", "class"=>"input"), "Komentarai:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class"=>"input"), "Naujiena:" => array("type" => "string", "value" => editorius('tiny_mce', 'standartinis', array('naujiena' => 'Glaustai', 'placiau' => 'Plačiau'))), 'Pateikti' => array("type" => "submit", "name" => "action", "value" => 'Pateikti'));
	lentele('Naujienos rašymas', $bla->form($naujiena));
} else {
	klaida("Dėmesio", "Nėra kategorijų.");
}

?>
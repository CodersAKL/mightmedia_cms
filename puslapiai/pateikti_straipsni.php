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


if (isset($_POST['action']) && $_POST['action'] == 'Pateikti') {
	if (isset($_POST['pav']) && isset($_POST['apr'])) {
		$apr = $_POST['apr'];
		$str = $_POST['str'];
		$komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
		$kategorija = (int)$_POST['kategorija'];
		$pavadinimas = strip_tags($_POST['pav']);
		//$rodoma = (isset($_POST['rodoma']) && $_POST['rodoma'] == 'TAIP'?'TAIP':'NE');

		if (isset($_SESSION['username'])) {
			$autorius = $_SESSION['username'];
			$autoriusid = $_SESSION['id'];
		} else {
			$autorius = 'Svečias';
			$autoriusid = '0';
		}

		if (empty($str) || empty($pavadinimas)) {
			$error = "Nepilnai užpildyti laukeliai.";
		}
		if (!isset($error)) {
			$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "straipsniai` SET
	    `kat` = " . escape($kategorija) . ",
			`pav` = " . escape($pavadinimas) . ",
			`t_text` = " . escape($apr) . ",
			`f_text` = " . escape($str) . ",
			`date` = " . time() . ",
			`autorius` = " . escape($autorius) . ",
			`autorius_id` = " . escape($autoriusid) . ",
			`kom` = " . escape($komentaras) . ",
			`rodoma` = 'NE'");
			if ($result) {
				msg("Informacija", "Straipsnis pateiktas administracijos peržiūrai.");
			} else {
				klaida("Klaida", "Straipsnis nepatalpintas. Klaida:<br><b>" . mysql_error() . "</b>");
			}
		} else {
			klaida("Klaida", $error);
		}
		redirect(url("?id," . $_GET['id']), "meta");
	} else {
		klaida("Dėmesio", "Užpildykite visus laukelius.");
	}

}

$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' ORDER BY `pavadinimas` DESC");
if (sizeof($sql) > 0) {
	foreach ($sql as $row) {
		$kategorijos[$row['id']] = $row['pavadinimas'];

	}


	include_once ("priedai/class.php");
	$bla = new forma();

	$straipsnis = array("Form" => array("action" => "", "method" => "post", "name" => "reg"), "Pavadinimas:" => array("type" => "text", "value" => "", "name" => "pav", "class"=>"input"), "Komentarai:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class"=>"input"), "Straipsnis:" => array("type" => "string", "value" => editorius('spaw', 'standartinis', array('apr' => 'Straipsnio įžanga', 'str' => 'straipsnis'), array('apr' => 'Straipsnio įžanga', 'str' => 'Straipsnis'))), 'Pateikti' => array("type" => "submit", "name" => "action", "value" => 'Pateikti'), );


	lentele('Straipsnio rašymas', $bla->form($straipsnis));
} else {
	klaida("Dėmesio", "Nėra kategorijų.");
}

?>
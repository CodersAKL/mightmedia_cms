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

$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' ORDER BY `pavadinimas` AND `lang` = ".escape(lang())." DESC");
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
	$naujiena = explode('===page===', $_POST['naujiena']);
	$izanga = $naujiena[0];
	$placiau = empty($naujiena[1]) ? '' : $naujiena[1];
	$komentaras = (isset($_POST['kom']) ? $_POST['kom'] : 'taip');
	$pavadinimas = strip_tags($_POST['pav']);
	$kategorija = (int) $_POST['kategorija'];
	$result = mysql_query1("INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, kategorija, rodoma, lang) VALUES (" . escape($pavadinimas) . ", " . escape($izanga) . ", " . escape($placiau) . ",  '" . time() . "', '" . (isset($_SESSION['username'])?$_SESSION['username']:'Svečias'). "', " . escape($komentaras) . ", " . escape($kategorija) . ", 'NE', ".escape(lang()).")");
    msg('Atlikta', 'Naujiena pateikta administracijos peržiūrai.');
		redirect(url("?id," . $_GET['id']), "meta");

	} else {
		klaida("Dėmesio", "Užpildykite visus laukelius.");
	}
}
if ($i == 1) {
	include_once ("priedai/class.php");
	$bla = new forma();
	$naujiena = array(
		"Form" => array("action" => "", "method" => "post", "name" => "reg"),
		"Pavadinimas:" => array("type" => "text", "value" => '', "name" => "pav", "class"=>"input"),
		"Komentarai:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"),
		"Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class"=>"input"),
		"Naujiena:" => array("type" => "string", "value" => editorius('tiny_mce', 'standartinis', 'naujiena')),
		'Pateikti' => array("type" => "submit", "name" => "action", "value" => 'Pateikti')
	);
	lentele('Naujienos rašymas', $bla->form($naujiena));
} else {
	klaida("Dėmesio", "Nėra kategorijų.");
}

?>
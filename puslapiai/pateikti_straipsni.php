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
    if (isset($_POST['pav']) && isset($_POST['str'])) {
    $straipsnis = explode('===page===',$_POST['str']);
    $apr = $straipsnis[0];
    $str = empty($straipsnis[1])?'':$straipsnis[1];
    
    $komentaras = (isset($_POST['kom']) && $_POST['kom'] == 'taip' ? 'taip' : 'ne');
    $kategorija = (int)$_POST['kategorija'];
    $pavadinimas = strip_tags($_POST['pav']);
    $autorius = (isset($_SESSION['username']) ? $_SESSION['username'] : 0);
    $autoriusid = (isset($_SESSION['id']) ? $_SESSION['id'] : 0);
    if (empty($apr) || empty($pavadinimas)) {
      $error = "{$lang['admin']['article_emptyfield']}.";
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
			`rodoma` = 'NE',
			`lang` = ".escape(lang())."");
			if ($result) {
				msg("Informacija", "Straipsnis pateiktas administracijos peržiūrai.");
			} else {
				klaida("Klaida", "Straipsnis nepatalpintas.");
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

	$straipsnis = array("Form" => array("action" => url("?id,".$conf['puslapiai'][basename(__file__)]['id']), "method" => "post", "name" => "reg"), "Pavadinimas:" => array("type" => "text", "value" => "", "name" => "pav", "class"=>"input"), "Komentarai:" => array("type" => "select", "value" => array('taip' => 'TAIP', 'ne' => 'NE'), "name" => "kom", "class" => "input", "class"=>"input"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class"=>"input"), "Straipsnis:" => array("type" => "string", "value" => editorius('jquery', 'standartinis', 'str')), 'Pateikti' => array("type" => "submit", "name" => "action", "value" => 'Pateikti'), );


	lentele('Straipsnio rašymas', $bla->form($straipsnis));
} else {
	klaida("Dėmesio", "Nėra kategorijų.");
}

?>
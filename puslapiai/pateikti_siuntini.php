<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * 
 **/
if (isset($_SESSION['id']) && $_SESSION['id']) {
	ini_set("memory_limit", "50M");

	if (isset($_POST['action']) && $_POST['action'] == 'Pateikti siuntinį') {
		if (isset($_FILES) && isset($_POST['Pavadinimas']) && isset($_POST['Aprasymas'])) {
			//debug($_POST);
			//apsauga nuo kenksmingo kodo
			include_once ('priedai/safe_html.php');
			// nurodome masyva leidziamu elementu DUK
			// - tagai kurie uzdaromi atskirai (<p></p>) pazymeti kaip 1
			// - tagai kuriuos uzdaryti nebutina (<hr>) zymimi kaip 0
			$tags = array("p" => 1, "br" => 0, "a" => 1, "img" => 0, "li" => 1, "ol" => 1, "ul" => 1, "b" => 1, "i" => 1, "em" => 1, "strong" => 1, "del" => 1, "ins" => 1, "u" => 1, "code" => 1, "pre" => 1, "blockquote" => 1, "hr" => 0, "span" => 1, "font" => 1, "h1" => 1, "h2" => 1, "h3" => 1, "table" => 1, "tr" => 1, "td" => 1, "th" => 1, "tbody" => 1, "div" => 1);
			function upload($file, $file_types_array = array("BMP", "JPG", "PNG", "PSD", "ZIP"), $max_file_size = 1048576, $upload_dir = "siuntiniai") {
				if ($_FILES["$file"]["name"] != "") {
					$origfilename = $_FILES["$file"]["name"];
					$filename = explode(".", $_FILES["$file"]["name"]);
					$filenameext = strtolower($filename[count($filename) - 1]);
					unset($filename[count($filename) - 1]);
					$filename = implode(".", $filename);
					$filename = substr($filename, 0, 60) . "." . $filenameext;
					$file_ext_allow = false;
					for ($x = 0; $x < count($file_types_array); $x++) {
						if ($filenameext == $file_types_array[$x]) {
							$file_ext_allow = true;
						}
					} // for
					if ($file_ext_allow) {
						if ($_FILES["$file"]["size"] < $max_file_size) {
							$ieskom = array("?", "&", "=", " ", "+", "-", "#");
							$keiciam = array("", "", "", "_", "", "", "");
							$filename = str_replace($ieskom, $keiciam, $filename);
							if (is_file($upload_dir . $filename)) {
								$filename = time() . "_" . $filename;
							}
							move_uploaded_file($_FILES["$file"]["tmp_name"], $upload_dir . $filename);

							if (file_exists($upload_dir . $filename)) {
								if (isset($_SESSION['id'])) {
									$autorius = $_SESSION['id'];
								} else {
									$autorius = '0';
								}
								$result = mysql_query1("
                    INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`)
                    VALUES (" . escape($_POST['Pavadinimas']) . "," . escape($filename) . ",
                    " . escape($_POST['Aprasymas']) . "," . escape($autorius) . ", '" . time() . "', " . escape($_POST['cat']) . ")");

								if ($result) {
									msg("Informacija", "Failas sėkmingai pateiktas administracijos peržiūrai");


								} else {
									klaida('Įkėlimo klaida', 'Dokumentas: <font color="#FF0000">' . $filename . '</font> nebuvo įkeltas. Klaida:<br><b>' . mysql_error() . '</b>');

								}
							} else {
								klaida('Įkėlimo klaida', 'Dokumentas: <font color="#FF0000">' . $filename . '</font> nebuvo įkeltas');
							}
						} else {
							klaida('Įkėlimo klaida', '<font color="#FF0000">' . $filename . '</font> dokumentas perdidelis');
						}
					} // if
					else {
						klaida('Įkėlimo klaida', '<font color="#FF0000">' . $filename . '</font> dokumentas netinkamo plėtinio');
					}
				}
			}
			if (isset($_FILES)) {
				if (is_uploaded_file($_FILES['failas']['tmp_name'])) {
					if (isset($_FILES['failas']) && !empty($_FILES['failas'])) {
						upload("failas", array("jpg", "bmp", "png", "psd", "zip", "rar", "mrc", "dll"), 1048576, "siuntiniai/");
					}
				}
			}
			//unset($result,$_POST['action'],$_FILES['failas'],$file);
			redirect("?id," . $_GET['id'] . ";", "meta");
		} else {
			klaida("Dėmesio", "Užpildykite visus laukelius.");
		}
	}
	$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' ORDER BY `pavadinimas` DESC");
	if (mysql_num_rows($sql) > 0) {
		while ($row = mysql_fetch_assoc($sql)) {
			$kategorijos[$row['id']] = $row['pavadinimas'];
		}
	} else {
		$kategorijos[] = "Kategorijų nėra";
		$nera = 'nera';
	}
	include_once ("priedai/class.php");
	$bla = new forma();
	if (!isset($nera)) {
		$forma = array("Form" => array("enctype" => "multipart/form-data", "action" => '', "method" => "post", "name" => "action"), "Failas:" => array("name" => "failas", "type" => "file", "value" => "", "style" => "width:100%"), "Pavadinimas:" => array("type" => "text", "value" => '', "name" => "Pavadinimas", "style" => "width:100%"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "style" => "width:100%"), "Aprašymas:" => array("type" => "string", "value" => editorius('spaw', 'mini', 'Aprasymas', '')), 'Sukurti siuntinį' => array("type" => "submit", "name" => "action", "value" => 'Pateikti siuntinį'), );

		lentele('Siuntinių kūrimas', $bla->form($forma));
	} else {
		klaida("Dėmesio", "Nėra kategorijų.");
	}
} else {
	klaida("Dėmesio", "Prisijunkite prie sistemos");
}

?>
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

if (isset($_SESSION['id']) && $_SESSION['id']) {
	ini_set("memory_limit", "50M");

	include_once ("priedai/class.php");
	$bla = new forma();
	if (isset($_POST['action']) && $_POST['action'] == 'Pateikti nuotrauką') {
		if (isset($_POST['Aprasymas']) && isset($_POST['Pavadinimas'])) {

			if (isset($_FILES['failas']['name'])) {
				//make sure this directory is writable!
				$path_big = "images/galerija/";
				$path_thumbs = "images/galerija/mini";
				//the new width of the resized image, in pixels.
				$img_thumb_width = $conf['minidyd']; //
				$extlimit = "yes"; //Limit allowed extensions? (no for all extensions allowed)
				//List of allowed extensions if extlimit = yes
				$limitedext = array(".gif", ".jpg", ".png", ".jpeg", ".bmp");
				//the image -> variables
				$file_type = $_FILES['failas']['type'];
				$file_name = $_FILES['failas']['name'];
				$file_size = $_FILES['failas']['size'];
				$file_tmp = $_FILES['failas']['tmp_name'];
				//check if you have selected a file.
				if (!is_uploaded_file($file_tmp)) {
					klaida("Dėmesio", "Nepasirinkote failo.");
				} else {
					//check the file's extension
					$ext = strrchr($file_name, '.');
					$ext = strtolower($ext);
					//uh-oh! the file extension is not allowed!
					if (($extlimit == "yes") && (!in_array($ext, $limitedext))) {
						klaida("Dėmesio", "Blogas plėtinys.");
					}
					//so, whats the file's extension?
					$getExt = explode('.', $file_name);
					$file_ext = $getExt[count($getExt) - 1];
					//create a random file name
					$rand_name = $file_name;
					//$rand_name= rand(0,999999999);
					//the new width variable
					$ThumbWidth = $img_thumb_width;
					if ($file_size) {
						if ($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
							$new_img = imagecreatefromjpeg($file_tmp);
						} elseif ($file_type == "image/x-png" || $file_type == "image/png") {
							$new_img = imagecreatefrompng($file_tmp);
						} elseif ($file_type == "image/gif") {
							$new_img = imagecreatefromgif($file_tmp);
						}
						//list the width and height and keep the height ratio.
						list($width, $height) = getimagesize($file_tmp);
						//calculate the image ratio
						$imgratio = $width / $height;
						if ($width > $ThumbWidth) {
							if ($imgratio > 1) {
								$newwidth = $ThumbWidth;
								$newheight = $ThumbWidth / $imgratio;
							} else {
								$newheight = $ThumbWidth;
								$newwidth = $ThumbWidth * $imgratio;
							}
						} else {
							$newwidth = $width;
							$newheight = $height;
						}
						//function for resize image.
						//if (function_exists(imagecreatetruecolor)){
						$resized_img = imagecreatetruecolor($newwidth, $newheight);
						//}
						/*else {
						klaida('Klaida','Ar tikrai veikia GD v2+ biblioteka? Ji skirta dirbti su nuotraukomis. Susisiekite su šio serverio administratorium.');
						}*/


						//the resizing is going on here!

						imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

						//finally, save the image

						ImageJpeg($resized_img, "$path_thumbs/$rand_name.$file_ext");
						ImageDestroy($resized_img);
						ImageDestroy($new_img);

					}

					if ($file_size) {
						if ($file_type == "image/pjpeg" || $file_type == "image/jpeg") {
							$new_img = imagecreatefromjpeg($file_tmp);
						} elseif ($file_type == "image/x-png" || $file_type == "image/png") {
							$new_img = imagecreatefrompng($file_tmp);
						} elseif ($file_type == "image/gif") {
							$new_img = imagecreatefromgif($file_tmp);
						} elseif ($file_type == "image/bmp") {
							$new_img = imagecreatefrombmp($file_tmp);
						}
						$bigsize = $conf['fotodyd'];
						list($width, $height) = getimagesize($file_tmp);
						//calculate the image ratio
						$imgratio = $width / $height;
						if ($width > $bigsize) {
							if ($imgratio > 1) {
								$newwidth = $bigsize;
								$newheight = $bigsize / $imgratio;
							} else {
								$newheight = $bigsize;
								$newwidth = $bigsize * $imgratio;
							}
						} else {
							$newwidth = $width;
							$newheight = $height;
						}
						$resized_imgbig = imagecreatetruecolor($newwidth, $newheight);
						imagecopyresized($resized_imgbig, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

						//finally, save the image

						ImageJpeg($resized_imgbig, "$path_big/$rand_name.$file_ext");
						ImageDestroy($resized_imgbig);
						ImageDestroy($new_img);

						move_uploaded_file($file_tmp, "$path_big/originalai/$rand_name.$file_ext");
						if (isset($_SESSION['id'])) {
							$autorius = $_SESSION['id'];
						} else {
							$autorius = '0';
						}

						$result = mysql_query1("
INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`,`lang`)
VALUES (
" . escape($_POST['Pavadinimas']) . ",
" . escape($rand_name . "." . $file_ext) . ",
" . escape(strip_tags($_POST['Aprasymas'])) . ",
" . escape($autorius) . ",
'" . time() . "',
" . escape($_POST['cat']) . ",
'NE',
".escape(lang())."
)");

						if ($result) {
							msg("Informacija", "Nuotrauka pateikta administracijos peržiūrai.");
							redirect(url("?id," . $_GET['id']), "meta");

						} else {
							klaida('Įkėlimo klaida', 'Dokumentas: <font color="#FF0000">' . $filename . '</font> nebuvo įkeltas. Klaida:<br><b>' . mysql_error() . '</b>');
						}
						unset($_FILES, $_POST['Pavadinimas'], $_POST['Aprasymas'], $_POST['cat'], $autorius, $filename, $rand_name, $result);
					}
				}
			}
		} else {
			klaida("Dėmesio", "Užpildykite visus laukelius.");
		}

		/**
		 * Funkcija dirbanti su BMP paveiksliukais
		 * @author - nežinomas
		 *
		 * @param resource $filename
		 * @return resource
		 */
		function ImageCreateFromBMP($filename) {
			if (!$f1 = fopen($filename, "rb"))
				return false;
			$FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));
			if ($FILE['file_type'] != 19778)
				return false;

			$BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1, 40));
			$BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
			if ($BMP['size_bitmap'] == 0)
				$BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
			$BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
			$BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
			$BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
			$BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
			$BMP['decal'] = 4 - (4 * $BMP['decal']);
			if ($BMP['decal'] == 4)
				$BMP['decal'] = 0;

			$PALETTE = array();
			if ($BMP['colors'] < 16777216) {
				$PALETTE = unpack('V' . $BMP['colors'], fread($f1, $BMP['colors'] * 4));
			}

			$IMG = fread($f1, $BMP['size_bitmap']);
			$VIDE = chr(0);

			$res = imagecreatetruecolor($BMP['width'], $BMP['height']);
			$P = 0;
			$Y = $BMP['height'] - 1;
			while ($Y >= 0) {
				$X = 0;
				while ($X < $BMP['width']) {
					if ($BMP['bits_per_pixel'] == 24)
						$COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
					elseif ($BMP['bits_per_pixel'] == 16) {
						$COLOR = unpack("n", substr($IMG, $P, 2));
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ($BMP['bits_per_pixel'] == 8) {
						$COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ($BMP['bits_per_pixel'] == 4) {
						$COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
						if (($P * 2) % 2 == 0)
							$COLOR[1] = ($COLOR[1] >> 4);
						else
							$COLOR[1] = ($COLOR[1] & 0x0F);
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ($BMP['bits_per_pixel'] == 1) {
						$COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
						if (($P * 8) % 8 == 0)
							$COLOR[1] = $COLOR[1] >> 7;
						elseif (($P * 8) % 8 == 1)
							$COLOR[1] = ($COLOR[1] & 0x40) >> 6;
						elseif (($P * 8) % 8 == 2)
							$COLOR[1] = ($COLOR[1] & 0x20) >> 5;
						elseif (($P * 8) % 8 == 3)
							$COLOR[1] = ($COLOR[1] & 0x10) >> 4;
						elseif (($P * 8) % 8 == 4)
							$COLOR[1] = ($COLOR[1] & 0x8) >> 3;
						elseif (($P * 8) % 8 == 5)
							$COLOR[1] = ($COLOR[1] & 0x4) >> 2;
						elseif (($P * 8) % 8 == 6)
							$COLOR[1] = ($COLOR[1] & 0x2) >> 1;
						elseif (($P * 8) % 8 == 7)
							$COLOR[1] = ($COLOR[1] & 0x1);
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} else
						return false;
					imagesetpixel($res, $X, $Y, $COLOR[1]);
					$X++;
					$P += $BMP['bytes_per_pixel'];
				}
				$Y--;
				$P += $BMP['decal'];
			}


			fclose($f1);

			return $res;
		}
	}

	$sql = mysql_query1("SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND `lang` = ".escape(lang())." ORDER BY `pavadinimas` DESC");
	if (sizeof($sql) > 0) {
		foreach ($sql as $row) {
			$kategorijos[$row['id']] = $row['pavadinimas'];
		}
	} else {
		$kategorijos[] = "Kategorijų nėra";
	}
	if (sizeof($sql) > 0) {
		$forma = array("Form" => array("enctype" => "multipart/form-data", "action" => url("?id,".$conf['puslapiai'][basename(__file__)]['id']), "method" => "post", "name" => "action"), "Failas:" => array("name" => "failas", "type" => "file", "value" => "", "class"=>"input"), "Pavadinimas:" => array("type" => "text", "value" => '', "name" => "Pavadinimas", "class"=>"input"), "Kategorija:" => array("type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "class"=>"input"), "Aprašymas:" => array("type" => "textarea", "name" => "Aprasymas", "class"=>"input", "rows" => "3", "class" => "input", "value" => ''), //"Paveiksliukas:"=>array("type"=>"text","value"=>(isset($extra['foto']))?input($extra['foto']):'http://',"name"=>"Pav","class"=>"input"),
			'Pateikti nuotrauką' => array("type" => "submit", "name" => "action", "value" => 'Pateikti nuotrauką'), );

		lentele('Pateikti nuotrauką', $bla->form($forma));
	} else {
		klaida("Dėmesio", "Nėra kategorijų.");
	}
} else {
	klaida("Dėmesio", "Prisijunkite prie sistemos.");
}

?>
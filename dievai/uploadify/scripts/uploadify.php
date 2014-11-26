<?php
/*
Uploadify v2.1.0
Release Date: August 24, 2009

Copyright (c) 2009 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 'Off' );
//ini_set("memory_limit", "50M");
session_id( $_POST['PHPSESSID'] );
session_start();
define( 'ROOT', '../../../' );
if ( !empty( $_FILES ) ) {
	require_once( ROOT . 'priedai/conf.php' );
	require_once( ROOT . 'priedai/funkcijos.php' );
	if ( !isset( $_SESSION[SLAPTAS]['level'] ) || $_SESSION[SLAPTAS]['level'] != 1 ) {
		die( 'eik lauk..' );
	}
	//$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	//$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

	$big_img  = ROOT . "images/galerija/"; //Kur bus saugomi didesni paveiksliukai
	$mini_img = ROOT . "images/galerija/mini"; //Kur bus saugomos miniatiuros

	$img_thumb_width = $conf['minidyd']; //Mini paveiksliuku dydis

	//Sarašas leidžiamų failų
	$limitedext = array( "jpg", "JPG", "jpeg", "JPEG", "png", "PNG", "gif", "GIF", "bmp", "BMP" );
	$fileParts  = pathinfo( $_FILES['Filedata']['name'] );
	//$file_type = $_FILES['Filedata']['type'];
	$file_name = $_FILES['Filedata']['name'];
	$file_size = $_FILES['Filedata']['size'];
	$file_tmp  = $_FILES['Filedata']['tmp_name'];
	if ( $file_size <= MFDYDIS ) {
		//Patikrinam ar failas ikeltas sekmingai
		if ( !is_uploaded_file( $file_tmp ) ) {
			//klaida($lang['system']['warning'], "{$lang['admin']['gallery_nofile']}.");
		} else {
			//gaunamm failo galune
			$ext = strrchr( $file_name, '.' );
			$ext = strtolower( $ext );

			//Tikrinam ar tinkamas failas
			if ( !in_array( $fileParts['extension'], $limitedext ) ) {
				//klaida($lang['system']['warning'], "{$lang['admin']['gallery_notimg']}");
			}

			//create a random file name
			$rand_pre  = random();
			$rand_name = $rand_pre . time();

			//the new width variable
			$ThumbWidth = $img_thumb_width;
			if ( $file_size ) {
				if ( $fileParts['extension'] == "jpeg" || $fileParts['extension'] == "jpg" || $fileParts['extension'] == "JPG" ) {
					$img = imagecreatefromjpeg( $file_tmp );
				} elseif ( $fileParts['extension'] == "png" || $fileParts['extension'] == "PNG" ) {
					$img = imagecreatefrompng( $file_tmp );
				} elseif ( $fileParts['extension'] == "gif" || $fileParts['extension'] == "GIF" ) {
					$img = imagecreatefromgif( $file_tmp );
				} elseif ( $fileParts['extension'] == "bmp" || $fileParts['extension'] == "BMP" ) {
					$img = imagecreatefrombmp( $file_tmp );
				}
				//list the width and height and keep the height ratio.
				$width  = imageSX( $img );
				$height = imageSY( $img );

				// Build the thumbnail
				$target_width  = $conf['minidyd'];
				$target_height = $conf['minidyd'];
				$target_ratio  = $target_width / $target_height;

				$img_ratio = $width / $height;

				//calculate the image ratio
				$imgratio = $width / $height;

				if ( $target_ratio > $img_ratio ) {
					$new_height = $target_height;
					$new_width  = $img_ratio * $target_height;
				} else {
					$new_height = $target_width / $img_ratio;
					$new_width  = $target_width;
				}

				if ( $new_height > $target_height ) {
					$new_height = $target_height;
				}
				if ( $new_width > $target_width ) {
					$new_height = $target_width;
				}


				$new_img = ImageCreateTrueColor( $conf['minidyd'], $conf['minidyd'] );
				if ( !@imagefilledrectangle( $new_img, 0, 0, $target_width - 1, $target_height - 1, 0 ) ) { // Fill the image black
					//klaida($lang['system']['error'], 'GD v2+' . $lang['system']['error']);
					exit( 0 );
				}

				if ( !@imagecopyresampled( $new_img, $img, ( $target_width - $new_width ) / 2, ( $target_height - $new_height ) / 2, 0, 0, $new_width, $new_height, $width, $height ) ) {
					//klaida($lang['system']['error'], 'GD v2+' . $lang['system']['error']);
					exit( 0 );
				}

				imagejpeg( $new_img, $mini_img . "/" . $rand_name . $ext, 95 );

				chmod( $mini_img . "/" . $rand_name . $ext, 0777 );
				ImageDestroy( $img );
				ImageDestroy( $new_img );

			}

			if ( $file_size ) {
				if ( $fileParts['extension'] == "jpeg" || $fileParts['extension'] == "jpg" || $fileParts['extension'] == "JPG" ) {
					$new_img = imagecreatefromjpeg( $file_tmp );
				} elseif ( $fileParts['extension'] == "png" || $fileParts['extension'] == "PNG" ) {
					$new_img = imagecreatefrompng( $file_tmp );
				} elseif ( $fileParts['extension'] == "gif" || $fileParts['extension'] == "GIF" ) {
					$new_img = imagecreatefromgif( $file_tmp );
				} elseif ( $fileParts['extension'] == "bmp" || $fileParts['extension'] == "BMP" ) {
					$new_img = imagecreatefrombmp( $file_tmp );
				}

				$bigsize = $conf['fotodyd'];
				list( $width, $height ) = getimagesize( $file_tmp );
				//calculate the image ratio
				$imgratio = $width / $height;
				if ( $width > $bigsize ) {
					if ( $imgratio > 1 ) {
						$newwidth  = $bigsize;
						$newheight = $bigsize / $imgratio;
					} else {
						$newheight = $bigsize;
						$newwidth  = $bigsize * $imgratio;
					}
				} else {
					$newwidth  = $width;
					$newheight = $height;
				}
				$resized_imgbig = imagecreatetruecolor( $newwidth, $newheight );
				imagecopyresampled( $resized_imgbig, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height );

				//finally, save the image

				ImageJpeg( $resized_imgbig, $big_img . "/" . $rand_name . $ext, 95 );
				chmod( $big_img . "/" . $rand_name . $ext, 0777 );
				ImageDestroy( $resized_imgbig );
				ImageDestroy( $new_img );

				move_uploaded_file( $file_tmp, $big_img . "/originalai/" . $rand_name . $ext );
				chmod( $big_img . "/originalai/" . $rand_name . $ext, 0777 );

				$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`, `lang`) VALUES (" . escape( $_FILES['Filedata']['name'] ) . "," . escape( $rand_name . $ext ) . "," . escape( '' ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ",'" . time() . "'," . escape( $_POST['cat'] ) . ",'TAIP', " . escape( lang() ) . ")" );

				if ( $result ) {
					//msg($lang['system']['done'], "{$lang['admin']['gallery_added']}");
				} else {
					//klaida($lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>");
				}
				//unset($_FILES['Filedata'], $filename, $_POST['action']);
				//redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v,1"), "meta");

			}
		}

		echo "1";
	} else {
		klaida( $lang['system']['error'], $lang['admin']['download_toobig'] );
	}
}

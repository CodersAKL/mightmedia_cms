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

if ( isset( $_SESSION[SLAPTAS]['id'] ) && $_SESSION[SLAPTAS]['id'] ) {
	//ini_set("memory_limit", "50M");
	include_once ( "priedai/class.php" );
	$bla = new forma();
	if ( isset( $_POST['action'] ) && $_POST['action'] == 'Pateikti nuotrauką' ) {
		if ( isset( $_POST['Aprasymas'] ) && isset( $_POST['Pavadinimas'] ) ) {
			//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
			//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
			//
			if ( isset( $_FILES['failas']['name'] ) ) {

				$big_img  = ROOT . "images/galerija/"; //Kur bus saugomi didesni paveiksliukai
				$mini_img = ROOT . "images/galerija/mini"; //Kur bus saugomos miniatiuros

				$img_thumb_width = $conf['minidyd']; //Mini paveiksliukų dydis

				//Sarašas leidžiamų failų
				$limitedext = array( ".jpg", ".JPG", ".jpeg", ".JPEG", ".png", ".PNG", ".gif", ".GIF", ".bmp", ".BMP" );
				$file_type  = $_FILES['failas']['type'];
				$file_name  = $_FILES['failas']['name'];
				$file_size  = $_FILES['failas']['size'];
				$file_tmp   = $_FILES['failas']['tmp_name'];

				//Patikrinam ar failas įkeltas sėkmingai
				if ( !is_uploaded_file( $file_tmp ) ) {
					klaida( $lang['system']['warning'], "{$lang['admin']['gallery_nofile']}." );
				} else {
					//gaunamm failo galunę
					$ext = strrchr( $file_name, '.' );
					$ext = strtolower( $ext );

					//Tikrinam ar tinkamas failas
					if ( !in_array( $ext, $limitedext ) ) {
						klaida( $lang['system']['warning'], "{$lang['admin']['gallery_notimg']}" );
					}

					//create a random file name
					$rand_pre  = random();
					$rand_name = $rand_pre . time();

					//the new width variable
					$ThumbWidth = $img_thumb_width;
					if ( $file_size ) {
						if ( $file_type == "image/pjpeg" || $file_type == "image/jpeg" ) {
							$img = imagecreatefromjpeg( $file_tmp );
						} elseif ( $file_type == "image/x-png" || $file_type == "image/png" ) {
							$img = imagecreatefrompng( $file_tmp );
						} elseif ( $file_type == "image/gif" ) {
							$img = imagecreatefromgif( $file_tmp );
						} elseif ( $file_type == "image/bmp" ) {
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
							klaida( $lang['system']['error'], 'GD v2+' . $lang['system']['error'] );
							exit( 0 );
						}

						if ( !@imagecopyresampled( $new_img, $img, ( $target_width - $new_width ) / 2, ( $target_height - $new_height ) / 2, 0, 0, $new_width, $new_height, $width, $height ) ) {
							klaida( $lang['system']['error'], 'GD v2+' . $lang['system']['error'] );
							exit( 0 );
						}

						imagejpeg( $new_img, $mini_img . "/" . $rand_name . $ext, 95 );

						chmod( $mini_img . "/" . $rand_name . $ext, 0777 );
						ImageDestroy( $img );
						ImageDestroy( $new_img );

					}

					if ( $file_size ) {
						if ( $file_type == "image/pjpeg" || $file_type == "image/jpeg" ) {
							$new_img = imagecreatefromjpeg( $file_tmp );
						} elseif ( $file_type == "image/x-png" || $file_type == "image/png" ) {
							$new_img = imagecreatefrompng( $file_tmp );
						} elseif ( $file_type == "image/gif" ) {
							$new_img = imagecreatefromgif( $file_tmp );
						} elseif ( $file_type == "image/bmp" ) {
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

						$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "galerija` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`, `lang`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $rand_name . $ext ) . "," . escape( strip_tags( $_POST['Aprasymas'] ) ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ",'" . time() . "'," . escape( $_POST['cat'] ) . ",'NE', " . escape( lang() ) . ")" );

						if ( $result ) {
							msg( $lang['system']['done'], "{$lang['gallery']['sumbit_scc']}." );
						} else {
							klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
						}
						unset( $_FILES['failas'], $filename, $_POST['action'] );
						redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v,1" ), "meta" );

					}
				}
			}
		} else {
			klaida( $lang['system']['warning'], "{$lang['admin']['news_required']}." );
		}

		/**
		 * Funkcija dirbanti su BMP paveiksliukais
		 * @author - nežinomas
		 *
		 * @param resource $filename
		 *
		 * @return resource
		 */
		function ImageCreateFromBMP( $filename ) {

			if ( !$f1 = fopen( $filename, "rb" ) ) {
				return FALSE;
			}
			$FILE = unpack( "vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread( $f1, 14 ) );
			if ( $FILE['file_type'] != 19778 ) {
				return FALSE;
			}

			$BMP           = unpack( 'Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread( $f1, 40 ) );
			$BMP['colors'] = pow( 2, $BMP['bits_per_pixel'] );
			if ( $BMP['size_bitmap'] == 0 ) {
				$BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
			}
			$BMP['bytes_per_pixel']  = $BMP['bits_per_pixel'] / 8;
			$BMP['bytes_per_pixel2'] = ceil( $BMP['bytes_per_pixel'] );
			$BMP['decal']            = ( $BMP['width'] * $BMP['bytes_per_pixel'] / 4 );
			$BMP['decal'] -= floor( $BMP['width'] * $BMP['bytes_per_pixel'] / 4 );
			$BMP['decal'] = 4 - ( 4 * $BMP['decal'] );
			if ( $BMP['decal'] == 4 ) {
				$BMP['decal'] = 0;
			}

			$PALETTE = array();
			if ( $BMP['colors'] < 16777216 ) {
				$PALETTE = unpack( 'V' . $BMP['colors'], fread( $f1, $BMP['colors'] * 4 ) );
			}

			$IMG  = fread( $f1, $BMP['size_bitmap'] );
			$VIDE = chr( 0 );

			$res = imagecreatetruecolor( $BMP['width'], $BMP['height'] );
			$P   = 0;
			$Y   = $BMP['height'] - 1;
			while ( $Y >= 0 ) {
				$X = 0;
				while ( $X < $BMP['width'] ) {
					if ( $BMP['bits_per_pixel'] == 24 ) {
						$COLOR = unpack( "V", substr( $IMG, $P, 3 ) . $VIDE );
					} elseif ( $BMP['bits_per_pixel'] == 16 ) {
						$COLOR    = unpack( "n", substr( $IMG, $P, 2 ) );
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ( $BMP['bits_per_pixel'] == 8 ) {
						$COLOR    = unpack( "n", $VIDE . substr( $IMG, $P, 1 ) );
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ( $BMP['bits_per_pixel'] == 4 ) {
						$COLOR = unpack( "n", $VIDE . substr( $IMG, floor( $P ), 1 ) );
						if ( ( $P * 2 ) % 2 == 0 ) {
							$COLOR[1] = ( $COLOR[1] >> 4 );
						} else {
							$COLOR[1] = ( $COLOR[1] & 0x0F );
						}
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} elseif ( $BMP['bits_per_pixel'] == 1 ) {
						$COLOR = unpack( "n", $VIDE . substr( $IMG, floor( $P ), 1 ) );
						if ( ( $P * 8 ) % 8 == 0 ) {
							$COLOR[1] = $COLOR[1] >> 7;
						} elseif ( ( $P * 8 ) % 8 == 1 ) {
							$COLOR[1] = ( $COLOR[1] & 0x40 ) >> 6;
						}
						elseif ( ( $P * 8 ) % 8 == 2 ) {
							$COLOR[1] = ( $COLOR[1] & 0x20 ) >> 5;
						}
						elseif ( ( $P * 8 ) % 8 == 3 ) {
							$COLOR[1] = ( $COLOR[1] & 0x10 ) >> 4;
						}
						elseif ( ( $P * 8 ) % 8 == 4 ) {
							$COLOR[1] = ( $COLOR[1] & 0x8 ) >> 3;
						}
						elseif ( ( $P * 8 ) % 8 == 5 ) {
							$COLOR[1] = ( $COLOR[1] & 0x4 ) >> 2;
						}
						elseif ( ( $P * 8 ) % 8 == 6 ) {
							$COLOR[1] = ( $COLOR[1] & 0x2 ) >> 1;
						}
						elseif ( ( $P * 8 ) % 8 == 7 ) {
							$COLOR[1] = ( $COLOR[1] & 0x1 );
						}
						$COLOR[1] = $PALETTE[$COLOR[1] + 1];
					} else {
						return FALSE;
					}
					imagesetpixel( $res, $X, $Y, $COLOR[1] );
					$X++;
					$P += $BMP['bytes_per_pixel'];
				}
				$Y--;
				$P += $BMP['decal'];
			}


			fclose( $f1 );

			return $res;
		}
	}

	$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='galerija' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
	include_once ( ROOTAS . "priedai/kategorijos.php" );
	kategorija( "galerija", TRUE );
	if ( sizeof( $sql ) > 0 ) {
		$kategorijos = cat( 'galerija', 0 );
	}
	$kategorijos[0] = "--";
	$forma          = array( "Form"                              => array( "enctype" => "multipart/form-data", "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "action" ),
	                         "{$lang['admin']['gallery_file']}:" => array( "name" => "failas", "type" => "file", "value" => "", "class"=> "input" ),
	                         "{$lang['system']['name']}:"        => array( "type" => "text", "value" => '', "name" => "Pavadinimas", "class"=> "input" ),
	                         "{$lang['gallery']['photoalbum']}:"    => array( "type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "class"=> "input" ),
	                         "{$lang['system']['about']}:"       => array( "type" => "string", "value" => editorius( 'spaw', 'mini', 'Aprasymas' ) ),
		//"Paveiksliukas:"=>array("type"=>"text","value"=>(isset($extra['foto']))?input($extra['foto']):'http://',"name"=>"Pav","class"=>"input"),
	                         ""                                  => array( "type" => "submit", "name" => "action", "value" => "{$lang['gallery']['submit']}" ) );
	lentele( $lang['gallery']['submiting'], $bla->form( $forma ) );
} else {
	klaida( $lang['system']['warning'], $lang['system']['pleaselogin'] );
}

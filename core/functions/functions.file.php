<?php

/**
 * Nurodytai eilutei iš failo trinti
 *
 * @global string $lang
 *
 * @param string  $fileName
 * @param int     $lineNum
 */
if(! function_exists('delLineFromFile')) {
	function delLineFromFile( $fileName, $lineNum ) {

		global $lang;
		// check the file exists
		if ( !is_writable( $fileName ) ) {
			// print an error
			klaida( $lang['system']['error'], $lang['system']['error'] );
			// exit the function
			exit;
		} else {
			// read the file into an array
			$arr = file( $fileName );
		}

		// the line to delete is the line number minus 1, because arrays begin at zero
		$lineToDelete = $lineNum - 1;

		// check if the line to delete is greater than the length of the file
		if ( $lineToDelete > sizeof( $arr ) ) {
			// print an error
			klaida( $lang['system']['error'], "{$lang['system']['error']} <b>[$lineNum]</b>." );
			// exit the function
			exit;
		}

		//remove the line
		unset( $arr["$lineToDelete"] );

		// open the file for reading
		if ( !$fp = fopen( $fileName, 'w+' ) ) {
			// print an error
			klaida( $lang['system']['error'], "{$lang['system']['error']} ($fileName)" );
			// exit the function
			exit;
		}

		// if $fp is valid
		if ( $fp ) {
			// write the array to the file
			foreach ( $arr as $line ) {
				fwrite( $fp, $line );
			}

			// close the file
			fclose( $fp );
		}

		//msg($lang['system']['done'],"IP {$lang['admin']['unbaned']}.");
	}
}

/**
 * Grąžiname failo plėtinį
 *
 * @param        $name
 * @param string $ext
 *
 * @return string
 */
if(! function_exists('strip_ext')) {
	function strip_ext( $name, $ext = '' ) {

		$ext = utf8_substr( $name, strlen( $ext ) - 4, 4 );
		if ( strpos( $ext, '.' ) === FALSE ) { // jeigu tai folderis
			return "    "; // grąžinam truputį tarpų kad rusiavimas butu ciki, susirūšiuoja - folderiai viršuje
		}

		return $ext; // jei tai failas grąžinam jo plėtinį
	}
}

// grąžina failus iš nurodytos direktorijos ir sukiša Ä¯ masyvą
if(! function_exists('getFiles')) {
	function getFiles($path, $denny = null, $defaultDir = null) {
		global $lang;

		if(empty($denny)) {
			$denny = '.htaccess|index.php|index.html|index.htm|index.php3|config.php';
		}

		$denny     	= explode( '|', $denny );
		$path      	= urldecode( $path );
		$defaultDir = ! empty($defaultDir) ? $defaultDir : $defaultDir;
		$files     	= array();
		$fileNames 	= array();
		$i         	= 0;

		if ( is_dir( $path ) ) {
			if ( $dh = opendir( $path ) ) {
				while ( ( $file = readdir( $dh ) ) !== FALSE ) {
					if ( !in_array( $file, $denny ) ) {
						if ( ( $file == "." ) || ( $file == ".." ) ) {
							continue;
						}
						$fullpath = $path . "/" . $file;
						//$fkey = strtolower($file);
						$fkey = $file;
						while ( array_key_exists( $fkey, $fileNames ) ) {
							$fkey .= " ";
						}

						$a = stat($fullpath);
			
						$files[$fkey]['size'] = $a['size'];

						if ( $a['size'] == 0 ) {
							$files[$fkey]['sizetext'] = "-";
						} else if ( $a['size'] > 1024 && $a['size'] <= 1024 * 1024 ) {
							$files[$fkey]['sizetext'] = ( ceil( $a['size'] / 1024 * 100 ) / 100 ) . " K";
						} //patvarkom failo dydziu atvaizdavima
						else if ( $a['size'] > 1024 * 1024 ) {
							$files[$fkey]['sizetext'] = ( ceil( $a['size'] / ( 1024 * 1024 ) * 100 ) / 100 ) . " Mb";
						} else {
							$files[$fkey]['sizetext'] = $a['size'] . " bytes";
						}

						$files[$fkey]['name'] = $defaultDir . $file;
						$e                    = strip_ext( $file ); // $e failo pletinys - pvz: .gif
						$files[$fkey]['type'] = filetype( $fullpath ); // failo tipas, dir, file ir pan
						$k                    = $e . $file; // kad butu lengvau rusiuoti;
						$fileNames[$i++]      = $k;
					}
				}
				closedir( $dh );
			} else {
				die($lang['system']['error'] . ' ' . $lang['system']['cantread'] . ': ' . $path);
			}
		} else {
			die($lang['system']['error'] . ': ' . $lang['system']['notdir'] . ': ' . $path);
		}
		sort( $fileNames, SORT_STRING ); // surusiuojam
		$sortedFiles = array();
		$i           = 0;
		foreach ( $fileNames as $f ) {
			$f = utf8_substr( $f, 4, strlen( $f ) - 4 ); //sutvarko failo pletinius
			if ( $files[$f]['name'] != '' ) {
				$sortedFiles[$i++] = $files[$f];
			}
		}

		return $sortedFiles;
	}
}

//Grazina direktorijų sarašą
if(! function_exists('getDirs')) {
	function getDirs( $dir, $skip = '' ) {

		if ( $handle = opendir( $dir ) ) {
			while ( FALSE !== ( $file = readdir( $handle ) ) ) {
				if ( $file != "." && $file != ".." && $file != ".svn" && is_dir( $dir . $file ) && ( is_array( $skip ) ? !in_array( $file, $skip ) : TRUE ) && $skip != $file ) {
					$return[$file] = $file;
				}
			}
			closedir( $handle );
		}

		return $return;
	}
}

/**
 * Gaunam informaciją iš XML
 *
 * @param string $xml
 * @param string $tag
 *
 * @return string
 */
if(! function_exists('get_tag_contents')) {
	function get_tag_contents( $xml, $tag ) {

		$result = "";
		$s_tag  = "<$tag>";
		$s_offs = strpos( $xml, $s_tag );

		// Ieškome pabaigos gairės
		if ( $s_offs ) {
			$e_tag  = "</$tag>";
			$e_offs = strpos( $xml, $e_tag, $s_offs );

			// Jei radome gairės pradžią ir pabaigą ištraukiame turinį
			if ( $e_offs ) {
				$result = substr( $xml, $s_offs + strlen( $s_tag ), $e_offs - $s_offs - strlen( $e_tag ) + 1 );
			}
		}

		return $result;
	}
}

if ( !function_exists( 'scandir' ) ) {
	function scandir( $directory, $sorting_order = 0 ) {

		$dh = opendir( $directory );
		while ( FALSE !== ( $filename = readdir( $dh ) ) ) {
			$files[] = $filename;
		}
		if ( $sorting_order == 0 ) {
			sort( $files );
		} else {
			rsort( $files );
		}

		return ( $files );
	}
}

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
if ( isset( $_SESSION[SLAPTAS]['id'] ) && $_SESSION[SLAPTAS]['id'] ) {
//("memory_limit", "50M");

	if ( isset( $_POST['action'] ) && $_POST['action'] == 'Pateikti siuntinį' ) {
		if ( isset( $_FILES ) && isset( $_POST['Pavadinimas'] ) && isset( $_POST['Aprasymas'] ) ) {
			//Tasku pridejimas uz siuntini nutrinkite // noredami kad veiktu
			//mysql_query1("UPDATE `" . LENTELES_PRIESAGA . "users` SET taskai=taskai+2 WHERE nick=" . escape($_SESSION['username']) . " AND `id` = " . escape($_SESSION['id']) . "");
			//
			function upload( $file, $file_types_array = array( "BMP", "JPG", "PNG", "PSD", "ZIP" ), $upload_dir = "siuntiniai" ) {

				if ( $_FILES["$file"]["name"] != "" ) {
					$origfilename = $_FILES["$file"]["name"];
					$filename     = explode( ".", $_FILES["$file"]["name"] );
					$filenameext  = strtolower( $filename[count( $filename ) - 1] );
					unset( $filename[count( $filename ) - 1] );
					$filename       = implode( ".", $filename );
					$filename       = substr( $filename, 0, 60 ) . "." . $filenameext;
					$file_ext_allow = FALSE;
					for ( $x = 0; $x < count( $file_types_array ); $x++ ) {
						if ( $filenameext == $file_types_array[$x] ) {
							$file_ext_allow = TRUE;
						}
					} // for
					if ( $file_ext_allow ) {
						//if ($_FILES["$file"]["size"] < $max_file_size) {
						$ieskom   = array( "?", "&", "=", " ", "+", "-", "#" );
						$keiciam  = array( "", "", "", "_", "", "", "" );
						$filename = str_replace( $ieskom, $keiciam, $filename );
						if ( is_file( $upload_dir . $filename ) ) {
							$filename = time() . "_" . $filename;
						}
						move_uploaded_file( $_FILES["$file"]["tmp_name"], $upload_dir . $filename );

						if ( file_exists( $upload_dir . $filename ) ) {
							if ( isset( $_SESSION[SLAPTAS]['id'] ) ) {
								$autorius = $_SESSION[SLAPTAS]['id'];
							} else {
								$autorius = '0';
							}
							$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $filename ) . ", " . escape( $_POST['Aprasymas'] ) . "," . escape( $autorius ) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ")" );

							if ( $result ) {
								msg( $lang['system']['info'], $lang['download']['sumbit_scc'] );

							} else {
								klaida( $lang['system']['error'], "{$lang['download']['doc']}: <font color='#FF0000'>" . $filename . "</font> {$lang['download']['not_uploaded']}." );

							}
						} else {
							klaida( $lang['system']['error'], "{$lang['download']['doc']}: <font color='#FF0000'>" . $filename . "</font> {$lang['download']['not_uploaded']}." );
						}
						/*} else {
							klaida('Įkėlimo klaida', '<font color="#FF0000">' . $filename . '</font> dokumentas perdidelis');
						}*/
					} // if
					else {
						klaida( $lang['system']['error'], '<font color="#FF0000">' . $filename . "</font> {$lang['download']['not_good']}." );
					}
				}
			}

			if ( isset( $_FILES ) ) {
				if ( is_uploaded_file( $_FILES['failas']['tmp_name'] ) ) {
					if ( isset( $_FILES['failas'] ) && !empty( $_FILES['failas'] ) ) {
						upload( "failas", array( "jpg", "bmp", "png", "psd", "zip", "rar", "mrc", "dll" ), "siuntiniai/" );
					}
				}
			}
			//unset($result,$_POST['action'],$_FILES['failas'],$file);
			redirect( url( "?id," . $_GET['id'] ), "meta" );
		} else {
			klaida( $lang['system']['warning'], "{$lang['admin']['news_required']}." );
		}
	}
	$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `lang` = " . escape( lang() ) . " AND `path`=0 ORDER BY `id` DESC" );
	include_once ( "priedai/kategorijos.php" );
	kategorija( "siuntiniai", TRUE );
	if ( sizeof( $sql ) > 0 ) {
		$kategorijos = cat( 'siuntiniai', 0 );
	}
	$kategorijos[0] = "--";
	include_once ( "priedai/class.php" );
	$bla = new forma();

	$forma = array( "Form"                               => array( "enctype" => "multipart/form-data", "action" => url( "?id," . $conf['puslapiai'][basename( __file__ )]['id'] ), "method" => "post", "name" => "action" ),
	                "{$lang['admin']['download_file']}:" => array( "name" => "failas", "type" => "file", "value" => "", "class"=> "input" ),
	                "{$lang['system']['name']}:"         => array( "type" => "text", "value" => '', "name" => "Pavadinimas", "class"=> "input" ),
	                "{$lang['system']['category']}:"     => array( "type" => "select", "value" => $kategorijos, "name" => "cat", "class" => "input", "class"=> "input" ),
	                "{$lang['system']['about']}:"        => array( "type" => "string", "value" => editorius( 'spaw', 'mini', 'Aprasymas' ) ),
	                ""                                   => array( "type" => "submit", "name" => "action", "value" => "{$lang['admin']['download_Create']}" ), );

	lentele( $lang['admin']['download_Create'], $bla->form( $forma ) );

} else {
	klaida( $lang['system']['warning'], $lang['system']['pleaselogin'] );
}

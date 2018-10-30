<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 366 $
 * @$Date: 2009-12-03 20:46:01 +0200 (Thu, 03 Dec 2009) $
 **/

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
if ( count( $_GET ) < 3 ) {
	$_GET['v'] = 1;
}
//Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit = 15;
//
if(BUTTONS_BLOCK) {
	lentele($lang['admin']['siustis'], buttonsMenu($buttons['downloads']));
}

unset($buttons);

if ( empty( $url['s'] ) ) {
	$url['s'] = 0;
}
if ( empty( $url['v'] ) ) {
	$url['v'] = 0;
}

include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "siuntiniai", TRUE );
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `path`=0 ORDER BY `id` DESC" );
if ( sizeof( $sql ) > 0 ) {
	$kategorijos = cat( 'siuntiniai', 0 );
}
$kategorijos[0] = "--";
if ( isset( $_GET['p'] ) ) {
	$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET rodoma='TAIP'
			WHERE `id`=" . escape( $_GET['p'] ) . ";
			" );
	if ( $result ) {
		msg( $lang['system']['done'], "{$lang['admin']['download_activated']}." );
	} else {
		klaida( $lang['system']['error'], " <br /><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
}
if ( /*((isset($_POST['action']) && $_POST['action'] == $lang['admin']['delete']  && isset($_POST['edit_new']) && $_POST['edit_new'] > 0)) || */
	isset( $url['t'] )
) {
	if ( isset( $url['t'] ) ) {
		$trinti = (int)$url['t'];
	} /*elseif (isset($_POST['edit_new'])) {
		$trinti = (int)$_POST['edit_new'];
	}*/
	$row = mysql_query1( "SELECT `file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = " . escape( $trinti ) . " LIMIT 1" );

	if ( isset( $row['file'] ) && !empty( $row['file'] ) ) {
		@unlink( ROOT . "siuntiniai/" . $row['file'] );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE id=" . escape( $trinti ) . " LIMIT 1" );
	if ( mysqli_affected_rows($prisijungimas_prie_mysql) > 0 ) {
		msg( $lang['system']['done'], "{$lang['admin']['download_deleted']}" );
	} else {
		klaida( $lang['system']['error'], "Trinimo klaida" );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/siustis' AND kid=" . escape( $trinti ) . "" );
	redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";v,7" ), "meta" );
}
// trinam siuntinius
if ( isset( $_POST['siunt_delete'] ) ) {
	foreach ( $_POST['siunt_delete'] as $a=> $b ) {
		$trinti[] = escape( $b );
	}
	$sql = mysql_query1( "SELECT `file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` IN (" . implode( ",", $trinti ) . ")" );
	foreach ( $sql as $row ) {
		if ( isset( $row['file'] ) && !empty( $row['file'] ) ) {
			@unlink( ROOT . "siuntiniai/" . $row['file'] );
		}
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` IN (" . implode( ",", $trinti ) . ")" );
	header( "Location:" . $_SERVER['HTTP_REFERER'] );
	exit;
	//Siuntinio redagavimas
} elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}
	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );
} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$apie        = $_POST['Aprasymas'];
	$pavadinimas = strip_tags( $_POST['Pavadinimas'] );
	$kategorija  = (int)$_POST['cat'];
	$file        = strip_tags( $_POST['failas2'] );
	$id          = ceil( (int)$_POST['news_id'] );
	$rodoma      = ( isset( $_POST['rodoma'] ) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE' );

	$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET
			`pavadinimas` = " . escape( $pavadinimas ) . ",
			`categorija` = " . escape( $kategorija ) . ",
			`apie` = " . escape( $apie ) . ",
			`file` = " . escape( $file ) . ",
			`rodoma` = " . escape( $rodoma ) . "
			WHERE `id`=" . escape( $id ) . ";
			" );
	if ( $result ) {
		msg( $lang['system']['done'], "{$lang['admin']['download_updated']}" );
		redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );
	} else {
		klaida( $lang['system']['error'], "<br /><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['download_create'] ) {

	function upload( $file, $file_types_array = array( "BMP", "JPG", "PNG", "PSD", "ZIP", "RAR", "GIF" ), $upload_dir = "../siuntiniai" ) {

		global $lang;
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
				if ( $_FILES["$file"]["size"] < MFDYDIS ) {
					$ieskom   = array( "?", "&", "=", " ", "+", "-", "#" );
					$keiciam  = array( "", "", "", "_", "", "", "" );
					$filename = str_replace( $ieskom, $keiciam, $filename );
					if ( is_file( $upload_dir . $filename ) ) {
						$filename = time() . "_" . $filename;
					}
					move_uploaded_file( $_FILES["$file"]["tmp_name"], $upload_dir . $filename );
					chmod( $upload_dir . $filename, 0777 );
					if ( file_exists( $upload_dir . $filename ) ) {
						$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $filename ) . ", " . escape( $_POST['Aprasymas'] ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ", 'TAIP')" );

						if ( $result ) {
							msg( $lang['system']['done'], $lang['admin']['download_created'] );
						} else {
							klaida( $lang['system']['error'], $lang['system']['error'] );
						}
					} else {
						klaida( $lang['system']['error'], '<font color="#FF0000">' . $filename . '</font>' );
					}
				} else {
					klaida( $lang['system']['error'], '<font color="#FF0000">' . $filename . '</font> ' . $lang['admin']['download_toobig'] . '' );
				}
			} else {
				klaida( $lang['system']['error'], '<font color="#FF0000">' . $filename . '</font> ' . $lang['admin']['download_badfile'] . '' );
			}
		}
	}

	if ( isset( $_FILES['failas'] ) && !empty( $_FILES['failas'] ) ) {
		if ( is_uploaded_file( $_FILES['failas']['tmp_name'] ) ) {
			upload( "failas", array( "jpg", "bmp", "png", "psd", "zip", "rar", "mrc", "dll", "doc", "ppt", "pdf", "bmp" ), ROOT . "siuntiniai/" );
		}
	}

	if ( isset( $_POST['failas2'] ) && !empty( $_POST['failas2'] ) ) {
		$rodoma = ( isset( $_POST['rodoma'] ) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE' );
		$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $_POST['failas2'] ) . ", " . escape( $_POST['Aprasymas'] ) . "," . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ", " . escape( $rodoma ) . ")" );

		if ( $result ) {
			msg( $lang['system']['done'], $lang['admin']['download_created'] );
		} else {
			klaida( $lang['system']['error'], $lang['system']['error'] );
		}
	}

	unset( $_FILES['failas'], $filename, $result, $_POST['Pavadinimas'], $_POST['Aprasymas'], $_POST['action'], $_POST['failas2'], $rodoma );
	
	redirect( url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "meta" );

}
unset( $naujiena, $placiau, $komentaras, $pavadinimas, $rodoma, $result, $error, $pav );


if ( isset( $_GET['v'] ) ) {
	
	if ( $_GET['v'] == 7 ) {
		
		///FILTRAVIMAS
		$viso = kiek( "siuntiniai", "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . "" );
		$sql2 = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? "AND `data` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='TAIP' ORDER BY ID LIMIT {$p},{$limit}" );
		if ( isset( $_POST['pavadinimas'] ) && $_POST['data'] && $_POST['apie'] ) {
			$val = array( $_POST['pavadinimas'], $_POST['data'], $_POST['apie'] );
		} else {
			$val = array( "", "", "" );
		}
		$info[] = array(
			"#"                        => "<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('siuntssch');\" />",
			$lang['download']['title'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
			$lang['download']['date']  => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"data\" />",
			$lang['download']['about'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"apie\" />",
			$lang['admin']['action']   => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
		);
		//FILTRAVIMAS
		foreach ( $sql2 as $row ) {
			$info[] = array(
				"#"                        => "<input type=\"checkbox\" value=\"{$row['ID']}\" name=\"siunt_delete[]\" />",
				$lang['download']['title'] => input( $row['pavadinimas'] ),
				$lang['download']['date']  => date( 'Y-m-d', $row['data'] ),
				$lang['download']['about'] => trimlink( strip_tags( $row['apie'] ), 55 ),
				$lang['admin']['action']   => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['ID'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" border=\"0\"></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['ID'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			);
		}

		$tableClass = new Table($info);
		$content = "<form id=\"siuntssch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele( $lang['admin']['edit'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}

	} elseif ( $_GET['v'] == 1 || isset( $_GET['h'] ) ) {
		if ( !isset( $nocat ) ) {
			if ( !isset( $_POST['tipas'] ) && !isset( $extra ) ) {
				$type[1] = $lang['admin']['download_uploaded'];
				$type[2] = $lang['admin']['link'];

				$tipas = array(
					"Form"                                 => array(
						"action" => url( "?id,{$_GET['id']};a,{$_GET['a']};v,1" ),
						"method" => "post",
						"name"   => "type" ),

					"{$lang['admin']['download_type']}:"   => array(
						"type"  => "select",
						"value" => $type,
						"name"  => "tipas" ),

					"{$lang['admin']['download_select']}:" => array(
						"type"  => "submit",
						"name"  => "action",
						"value" => "{$lang['admin']['download_select']}" )
				);

				$formClass = new Form($tipas);	
				lentele($lang['admin']['download_Create'], $formClass->form());
			}
			if ( isset( $_POST['tipas'] ) || isset( $extra ) ) {
				$ar    = array( "TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}" );
				$forma = array(
					"Form"                                                                                     => array(
						"enctype" => "multipart/form-data",
						"action"  => url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ),
						"method"  => "post",
						"name"    => "action" ),

					( !isset( $extra ) && @$_POST['tipas'] != 2 ) ? "{$lang['admin']['download_file']}:" : ""  => array(
						"name"  => "failas",
						"type"  => ( isset( $extra ) || $_POST['tipas'] != 2 ) ? "file" : "hidden",
						"value" => "",
						"class" => "input" ),

					( isset( $extra ) || $_POST['tipas'] == 2 ) ? "{$lang['admin']['download_fileurl']}:" : "" => array(
						"name"  => "failas2",
						"type"  => ( isset( $extra ) || $_POST['tipas'] == 2 ) ? "text" : "hidden",
						"value" => ( isset( $extra['pavadinimas'] ) ) ? input( $extra['file'] ) : '',
						"class" => "input" ),

					"{$lang['admin']['download_download']}:"                                                   => array(
						"type"  => "text",
						"value" => ( isset( $extra['pavadinimas'] ) ) ? input( $extra['pavadinimas'] ) : '',
						"name"  => "Pavadinimas",
						"class" => "input" ),

					"{$lang['admin']['article_shown']}:"                                                       => array(
						"type"     => "select",
						"value"    => $ar,
						"name"     => "rodoma",
						"class"    => "input",
						"selected" => ( isset( $extra['rodoma'] ) ? input( $extra['rodoma'] ) : '' ) ),

					"{$lang['system']['category']}:"                                                           => array(
						"type"     => "select",
						"value"    => $kategorijos,
						"name"     => "cat",
						"class"    => "input",
						"selected" => ( isset( $extra['categorija'] ) ? input( $extra['categorija'] ) : '0' ) ),

					"{$lang['admin']['download_about']}:"                                                      => array(
						"type"  => "string",
						"value" => editor( 'jquery', 'mini', 'Aprasymas', ( isset( $extra['apie'] ) ) ? $extra['apie'] : '' ) ),

					" "                                                                                         => array(
						"type"  => "submit",
						"name"  => "action",
						"value" => ( isset( $extra ) ) ? $lang['admin']['edit'] : $lang['admin']['download_create'] )

				);
				if ( isset( $extra ) ) {
					$forma[''] = array(
						"type"  =>
						"hidden",
						"name"  => "news_id",
						"value" => ( isset( $extra ) ? input( $extra['ID'] ) : '' )
					);
				}

				$formClass = new Form($forma);	
				lentele($lang['admin']['download_create'], $formClass->form());
			}
		} else {
			klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
		}
	} elseif ( $_GET['v'] == 6 ) {
		///FILTRAVIMAS
		$viso = kiek( "siuntiniai", "WHERE `rodoma`='NE' AND `lang` = " . escape( lang() ) . "" );
		$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? " AND `date` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY ID LIMIT {$p},{$limit}";
		//
		if ($q = mysql_query1($sqlQuery)) {
			
			$info = [];

			if ( isset( $_POST['pavadinimas'] ) && $_POST['data'] && $_POST['apie'] ) {
				$val = array( $_POST['pavadinimas'], $_POST['data'], $_POST['apie'] );
			} else {
				$val = array( "", "", "" );
			}

			$info[] = array(
				"#"                        => "<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('siuntssch');\" />",
				$lang['download']['title'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
				$lang['download']['date']  => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"data\" />",
				$lang['download']['about'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"apie\" />",
				$lang['admin']['action']   => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
			);
			//FILTRAVIMAS
			foreach ( $q as $sql ) {
				$info[] = array(
					"#"                        => "<input type=\"checkbox\" value=\"{$sql['ID']}\" name=\"siunt_delete[]\" />",
					$lang['download']['title'] => input( $sql['pavadinimas'] ),
					$lang['download']['date']  => date( 'Y-m-d', $sql['data'] ),
					$lang['download']['about'] => trimlink( strip_tags( $sql['apie'] ), 55 ),
					$lang['admin']['action']   => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['ID'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' alt='a' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['ID'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['ID'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
				);

			}

			$tableClass  = new Table($info);
			$content = "<form id=\"siuntssch\" method=\"post\"><div id=\"news\">" . $tableClass->render() . "</div><input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
			lentele( $lang['admin']['download_unpublished'], $content);
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
			}

		} else {
			klaida( $lang['system']['warning'], $lang['system']['no_items'] );
		}
	}
}

unset( $sql, $extra, $row );
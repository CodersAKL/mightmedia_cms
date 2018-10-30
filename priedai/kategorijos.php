<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 * */
?>
<!-- <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT;?>javascript/jquery/jquery.asmselect.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("select[multiple]").asmSelect({
			addItemTarget:'bottom',
			animate:true,
			highlight:true,
			removeLabel:'<?php echo $lang['system']['delete']; ?>',
			highlightAddedLabel:'<?php echo $lang['admin']['added']; ?>: ',
			highlightRemovedLabel:'<?php echo $lang['sb']['deleted']; ?>: ',
			sortable: true
		});
	});
</script>
<script type="text/javascript" src="js/superfish.js"></script>
<script src="js/jquery.treeview.js" type="text/javascript"></script> -->
<?php
//kategoriju medis
function cat( $kieno, $cat_id = 0, $space = 1, $x = '' ) {

	$sql = mysql_query1( 'SELECT * FROM  `' . LENTELES_PRIESAGA . 'grupes` WHERE `kieno` = ' . escape( $kieno ) . ' and `path` = ' . escape( $cat_id ) . ' AND `lang` = ' . escape( lang() ) );

	foreach ( $sql as $select ) {
		$x[$select['id']] = str_repeat( '-', $space ) . $select['pavadinimas'];

		$x = cat( $kieno, $select['id'], ( $space + 1 ), $x );
	}
	return $x;
}

//kitas š..das (neišgėręs nesuprasi), supratau ;)
function kategorija( $kieno, $leidimas = FALSE ) {

	global $conf, $url, $lang;
	if ( empty( $_GET['v'] ) ) {
		$_GET['v'] = 0;
	}
	//Kalba galerijai
	if ( $kieno == 'galerija' ) {
		$lang['system']['subcat/cat']      = $lang['gallery']['subphotoalbum/photoalbum'];
		$lang['system']['nocategories']    = $lang['gallery']['nophotoalbums'];
		$lang['system']['categorydeleted'] = $lang['admin']['gallery_photoalbum_del'];
		$lang['system']['category']        = $lang['gallery']['photoalbum'];
		$lang['system']['categoryupdated'] = $lang['admin']['gallery_photoalbum_up'];
		$lang['system']['editcategory']    = $lang['admin']['gallery_photoalbum_ed'];
		$lang['system']['createcategory']  = $lang['admin']['gallery_photoalbum_cr'];
	}
	$sql = mysql_query1( 'SELECT * FROM  `' . LENTELES_PRIESAGA . 'grupes` WHERE `kieno`=' . escape( $kieno ) . ' AND `lang` = ' . escape( lang() ) . ' ORDER BY `id` DESC' );
	if ( sizeof( $sql ) > 0 ) {

		$kategorijoss = cat( $kieno );
	}
	if ( $kieno != 'vartotojai' ) {
		$dir = 'images/naujienu_kat';
	} else {
		$dir = 'images/icons';
	}

	$array = getFiles( ROOTAS . $dir );
	foreach ( $array as $key => $val ) {
		if ( $array[$key]['type'] == 'file' ) {
			$kategoriju_pav[$array[$key]['name']] = $array[$key]['name'] . ' - ' . $array[$key]['sizetext'];
		}
	}

	if(! class_exists('Table') && ! class_exists('forma')) {
		include_once (ROOTAS . 'priedai/class.php');
	}

	$lygiai = array_keys( $conf['level'] );
	if ( $kieno != 'vartotojai' ) {

		foreach ( $lygiai as $key ) {
			$teises[$key] = $conf['level'][$key]['pavadinimas'];
		}
		$teises[0] = $lang['admin']['for_guests'];
	}

	if ( isset( $_POST['action'] ) && $_POST['action'] == $lang['system']['createcategory'] ) {

		$pavadinimas = input( $_POST['Pavadinimas'] );
		$aprasymas   = $_POST['Aprasymas'];
		if ( $kieno == 'galerija' ) {
			$pav = basename( 'no_picture.png' );
		} else {
			$pav = basename( $_POST['Pav'] );
		}
		$moderuoti = ( ( isset( $_POST['punktai'] ) ) ? serialize( $_POST['punktai'] ) : '' );

		if ( isset( $_POST['Teises'] ) ) {
			if ( $kieno == 'vartotojai' )
				//$teises_in = $_POST['Teises'];
			{
				$teises_in = 'N;';
			} else {
				$teises_in = serialize( $_POST['Teises'] );
			}
		} else {
			$teises_in = 'N;';
		}

		if ( isset( $_POST['path'] ) && !empty( $_POST['path'] ) ) {
			$path   = mysql_query1( 'SELECT * FROM `' . LENTELES_PRIESAGA . 'grupes` WHERE id=' . escape( $_POST['path'] ) . ' LIMIT 1' );
			$pathas = $path['id'];
		} else {
			$pathas = 0;
		}

		if ( $kieno == 'vartotojai' ) {
			$result = mysql_query1( 'INSERT INTO `' . LENTELES_PRIESAGA . 'grupes` (`pavadinimas`, `aprasymas`, `teises`, `pav`, `path`, `kieno`, `mod`) VALUES (' . escape( $pavadinimas ) . ',  ' . escape( $aprasymas ) . ', ' . escape( $teises_in ) . ', ' . escape( $pav ) . ', ' . escape( $pathas ) . ', ' . escape( $kieno ) . ', ' . escape( $moderuoti ) . ')' );

			if ( $result ) {
				msg( $lang['system']['done'], $lang['system']['categorycreated'] );
			}
		} else {
			$result = mysql_query1( 'INSERT INTO `' . LENTELES_PRIESAGA . 'grupes` (`pavadinimas`,`aprasymas`, `teises`, `pav`, `path`, `kieno`, `mod`, `lang`) VALUES (' . escape( $pavadinimas ) . ',  ' . escape( $aprasymas ) . ', ' . escape( $teises_in ) . ', ' . escape( $pav ) . ', ' . escape( $pathas ) . ', ' . escape( $kieno ) . ', ' . escape( $moderuoti ) . ', ' . escape( lang() ) . ')' );
			if ( $result ) {
				msg( $lang['system']['done'], $lang['system']['categorycreated'] );
			} else {
				klaida( $lang['system']['error'], $lang['system']['error'] );
			}
		}
		unset( $aprasymas, $pavadinimas, /* $teises,*/
		$pav, $einfo, $result, $pathas );
	} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['system']['editcategory'] ) {

		$pavadinimas = $_POST['Pavadinimas'];
		$aprasymas   = $_POST['Aprasymas'];
		if ( $kieno == 'galerija' ) {
			$pav = strip_tags( 'no_picture.png' );
		} else {
			$pav = strip_tags( $_POST['Pav'] );
		}
		$id = ceil( (int)$_POST['Kategorijos_id'] );
		if ( $kieno == 'vartotojai' ) {
			$teises_in = 'N;';
		} else {
			$teises_in = ( isset( $_POST['Teises'] ) ? serialize( $_POST['Teises'] ) : 'N;' );
		}
		$moderuoti = ( ( isset( $_POST['punktai'] ) ) ? serialize( $_POST['punktai'] ) : '' );
		$result    = mysql_query1( 'UPDATE `' . LENTELES_PRIESAGA . 'grupes` SET
			`pavadinimas` = ' . escape( $pavadinimas ) . ',
			`aprasymas` = ' . escape( $aprasymas ) . ',
			`teises` = ' . escape( $teises_in ) . ',
			`pav` = ' . escape( $pav ) . ',
			`mod` = ' . escape( $moderuoti ) . '
			' . ( ( isset( $_POST['path'] ) && $_POST['path'] != $id ) ? ', `path`=' . escape( $_POST['path'] ) : '' ) . '
			WHERE `id`= ' . escape( $id ) . ';
			' );
		if ( $result ) {
			msg( $lang['system']['done'], $lang['system']['categoryupdated'] );
		} else {
			klaida( $lang['system']['error'], $lang['system']['error'] );
		}
	}
	if ( isset( $_POST['Kategorijos_id'] ) && isNum( $_POST['Kategorijos_id'] ) && $_POST['Kategorijos_id'] > 0 && isset( $_POST['Kategorija'] ) && $_POST['Kategorija'] == $lang['system']['edit'] ) {
		$extra = mysql_query1( 'SELECT * FROM `' . LENTELES_PRIESAGA . 'grupes` WHERE `kieno`= \'' . $kieno . '\' AND `id` = ' . escape( (int)$_POST['Kategorijos_id'] ) . ' LIMIT 1' );
	}
	//if ($_GET['v'] == 2) {
	if ( isset( $_POST['Kategorija'] ) && $_POST['Kategorija'] == $lang['system']['delete'] ) {
		//Trinamos nuorodos esančios kategorijoje
		if ( $kieno == 'nuorodos' ) {
			$result = mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'nuorodos` WHERE `cat` = ' . escape( $_POST['Kategorijos_id'] ) );
		}
		//Trinami straipsniai esantys kategorijoje
		if ( $kieno == 'straipsniai' ) {

			$sql = mysql_query1( 'SELECT `id` FROM `' . LENTELES_PRIESAGA . 'straipsniai` WHERE `kat` = ' . escape( $_POST['Kategorijos_id'] ) );
			foreach ( $sql as $row ) {

				mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'kom` WHERE pid = \'puslapiai/straipsnis\' AND kid = ' . escape( $row['id'] ) );
			}

			$result = mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'straipsniai` WHERE `kat` = ' . escape( $_POST['Kategorijos_id'] ) );
		}
		//Trinam failą iš siuntinių
		if ( $kieno == 'siuntiniai' ) {
			$id  = ceil( (int)$_POST['Kategorijos_id'] );
			$sql = mysql_query1( 'SELECT `ID`,`file` FROM `' . LENTELES_PRIESAGA . 'siuntiniai` WHERE `categorija` = ' . escape( $id ) );
			foreach ( $sql as $row ) {

				if ( isset( $row['file'] ) && !empty( $row['file'] ) ) {
					mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'kom` WHERE pid = \'puslapiai/siustis\' AND kid = ' . escape( $row['ID'] ) );
					@copy( ROOTAS . 'siuntiniai/' . $row['file'], ROOTAS . 'sandeliukas/' . $row['file'] ); //backup
					@unlink( ROOTAS . 'siuntiniai/' . $row['file'] );
				}
			}

			mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'siuntiniai` WHERE `categorija` = ' . escape( $id ) );
		}
		//Trinam paveikslėlius kurie yra kategorijoje(galerija)
		if ( $kieno == 'galerija' ) {
			$id  = ceil( (int)$_POST['Kategorijos_id'] );
			$sql = mysql_query1( 'SELECT `ID`,`file` FROM `' . LENTELES_PRIESAGA . 'galerija` WHERE `categorija` = ' . escape( $id ) );
			foreach ( $sql as $row ) {
				if ( isset( $row['file'] ) && !empty( $row['file'] ) ) {
					@copy( ROOTAS . 'galerija/originalai/' . $row['file'], ROOTAS . 'sandeliukas/' . $row['file'] ); //backup
					@unlink( ROOTAS . 'galerija/' . $row['file'] );
					@unlink( ROOTAS . 'galerija/mini/' . $row['file'] );
					@unlink( ROOTAS . 'galerija/originalai/' . $row['file'] );
					mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'kom` WHERE pid = \'puslapiai/galerija\' AND kid = ' . escape( $row['ID'] ) );
				}
			}
			mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'galerija` WHERE `categorija` = ' . escape( $id ) );
		}
		//Trinamos naujienos esančios kategorijoje
		if ( $kieno == 'naujienos' ) {
			$id  = ceil( (int)$_POST['Kategorijos_id'] );
			$sql = mysql_query1( 'SELECT `id` FROM `' . LENTELES_PRIESAGA . 'naujienos` WHERE `kategorija` = ' . escape( $_POST['Kategorijos_id'] ) );
			foreach ( $sql as $row ) {
				mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'kom` WHERE pid = \'puslapiai/naujienos\' AND kid = ' . escape( $row['id'] ) );
			}
			mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'naujienos` WHERE `kategorija` = ' . escape( $id ) ) or klaida( $lang['system']['error'], $lang['system']['error'] );
		}
		//trinama kategorija
		//Jei turi subkategoriju, perkeliam
		$sql = mysql_query1( 'SELECT * FROM `' . LENTELES_PRIESAGA . 'grupes` WHERE `path` = ' . escape( $_POST['Kategorijos_id'] ) );
		if ( sizeof( $sql ) > 0 ) {
			foreach ( $sql as $row ) {
				//echo $row['path'];
				$update = mysql_query1( 'UPDATE `' . LENTELES_PRIESAGA . 'grupes` set path = ' . escape( 0 ) . ' WHERE id = ' . $row['id'] );
			}
		}
		//perkelimo pabaiga
		$result23 = mysql_query1( 'DELETE FROM `' . LENTELES_PRIESAGA . 'grupes`	WHERE `id` = ' . escape( $_POST['Kategorijos_id'] ) );


		if ( $result23 ) {
			msg( $lang['system']['done'], $lang['system']['categorydeleted'] );
		} else {
			klaida( $lang['system']['error'], $lang['system']['error'] );
		}
	}
	if ( $_GET['v'] == 2 ) {
		//Jei kuriama vartotoju kategorija
		if ( $kieno == 'vartotojai' ) {
			$textas = $lang['system']['grouplevel'];
			//$puslapiai[""]="";
			$failai = getFiles( ROOTAS . $conf['Admin_folder'], '.htaccess|index.php|index.html|index.htm|index.php3|conf.php|configuration.php|users.php|logs.php|upload.php|todo.php|blocks.php|meniu.php|komentarai.php|narsykle.php|main.php|sfunkcijos.php|pokalbiai.php|dashboard.php|uncache.php|search.php|antivirus.php|sfunkcijos.php' );
			foreach ( $failai as $file ) {
				if ( $file['type'] == 'file' ) {

					$puslapiai[basename( $file['name'] )] = ( isset( $lang['admin'][basename( $file['name'], '.php' )] ) ? $lang['admin'][basename( $file['name'], '.php' )] : nice_name( basename( $file['name'], '.php' ) ) );
				}
			}
			$puslapiai['com'] = '<b>' . $lang['admin']['komentarai'] . '(mod)</b>';
			$puslapiai['frm'] = '<b>' . $lang['admin']['frm'] . '(mod)</b>';


		} else {
			$textas     = $lang['system']['showfor'] . ' <img src="' . ROOT . 'images/icons/help.png" title="' . $lang['system']['about_allow_cat'] . '" />:';
		}

		//if (count($teises) > 0) {
		if ( !empty( $extra['mod'] ) ) {
			$ser = unserialize( $extra['mod'] );
		} else {
			$ser = '';
		}

		//print_r($puslapiai);
		$kategorijoss[0] = '';
		$kategorijos     = array(
			'Form'                                                                                    => array( 'action' => url( "?id,{$_GET['id']};a,{$_GET['a']};v,{$_GET['v']}" ), 'method' => 'post', 'name' => 'reg' ),
			$lang['system']['name']                                                                   => array( 'type' => 'text', 'value' => ( isset( $extra['pavadinimas'] ) ) ? input( $extra['pavadinimas'] ) : '', 'name' => 'Pavadinimas', 'class' => 'input' ),
			( $kieno != 'vartotojai' ? $lang['system']['subcat/cat'] : '' )                           => ( $kieno != 'vartotojai' ? array( 'type' => 'select', 'value' => @$kategorijoss, 'name' => 'path', 'selected' => ( isset( $extra['path'] ) ? input( $extra['path'] ) : '0' ), 'disabled' => @$kategorijoss ) : '' ),
			$lang['system']['about'] . ':'                                                            => array( 'type' => 'textarea', 'value' => ( isset( $extra['aprasymas'] ) ) ? input( $extra['aprasymas'] ) : '', 'name' => 'Aprasymas', 'rows' => '3', 'class' => 'input', 'id' => 'Aprasymas' ),
			'  '                                                                                      => array( 'type' => 'string', 'value' => '<div class="kat" style="float:inherit;"><img src="' . ROOT . '/' . $dir . '/' . ( isset( $extra['pav'] ) ? $extra['pav'] : 'no_picture.png' ) . '" id="kategorijos_img" /></div>' ),
			( $kieno != 'galerija' ? $lang['system']['picture'] . ':' : '' )                          => ( $kieno != 'galerija' ? array( 'type' => 'select', 'value' => $kategoriju_pav, 'name' => 'Pav', 'class' => 'input', 'selected' => ( isset( $extra['pav'] ) ? input( $extra['pav'] ) : 'no_picture.png' ), 'extra' => 'onchange="$(\'#kategorijos_img\').attr({ src: \'' . ROOT . '/' . $dir . '/\'+this.value });"' ) : "" ),
			$lang['admin']['what_moderate']                                                           => '', $textas => '', '' => array( 'type' => 'hidden', 'name' => 'Kategorijos_id', 'value' => ( isset( $extra['id'] ) ? input( $extra['id'] ) : '' ) ),
			( isset( $extra ) ) ? $lang['system']['editcategory'] : $lang['system']['createcategory'] => array( 'type' => 'submit', 'name' => 'action', 'value' => ( isset( $extra ) ) ? $lang['system']['editcategory'] : $lang['system']['createcategory'] )
		);
		if ( $kieno == 'vartotojai' ) {
			//	echo $mod;
			$kategorijos[$lang['admin']['what_moderate']] = array( 'type' => 'select', 'extra' => 'multiple="multiple"', 'value' => $puslapiai, 'class' => 'asmSelect', 'name' => 'punktai[]', 'id' => 'punktai', 'selected' => ( isset( $extra['mod'] ) ) ? $ser : '' );
		}
		if ( /* $leidimas == true && */
			$kieno != 'vartotojai' && $_GET['v'] == 2
		) {


			$kategorijos[$textas] = array( 'type' => 'select', 'extra' => 'multiple="multiple"', 'value' => $teises, 'class' => 'input asmSelect', 'name' => 'Teises[]', 'id' => 'punktai' );

			if ( !empty( $extra['teises'] ) && $extra['teises'] != 'N;' ) {
				$kategorijos[$textas]['selected'] = unserialize( $extra['teises'] );
			}
		} else {
			$kategorijos[''] = array( 'type' => 'hidden', 'name' => 'Teises', 'value' => ( isset( $extra['teises'] ) ? ( $kieno == 'vartotojai' ? $extra['teises'] : unserialize( $extra['teises'] ) ) : '' ) );
		}
		$kategorijos[' '] = array( 'type' => 'hidden', 'name' => 'Kategorijos_id', 'value' => ( isset( $extra['id'] ) ? input( $extra['id'] ) : '' ) );
		
		$formClass = new Form($kategorijos);	
		lentele($lang['system']['categories'], $formClass->form());
	} elseif ( $_GET['v'] == 3 ) {
		if ( isset( $kategorijoss ) ) {
			$kategorijos_redagavimas = array(
				'Form'                      => array( 'action' => url( '?id,' . $_GET['id'] . ';a,' . $_GET['a'] . ';v,2' ), 'method' => 'post', 'name' => 'reg' ),
				$lang['system']['category'] => array( 'type' => 'select', 'value' => $kategorijoss, 'name' => 'Kategorijos_id' ),
				$lang['system']['edit']     => array( 'type' => 'submit', 'name' => 'Kategorija', 'value' => $lang['system']['edit'] ),
				$lang['system']['delete']   => array( 'type' => 'submit', 'name' => 'Kategorija', 'value' => $lang['system']['delete'] )
			);

			$formClass = new Form($kategorijos_redagavimas);	
			lentele($lang['system']['editcategory'], $formClass->form());
		} else {
			klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
		}
	}
	delete_cache( 'SELECT * FROM `' . LENTELES_PRIESAGA . 'grupes` WHERE `kieno` = \'straipsniai\' AND `lang`= ' . escape( lang() ) . ' ORDER BY `pavadinimas`' );
	unset( $formClass, $info, $sql, $sql2, $q, $result, $result2 );
}

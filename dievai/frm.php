<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 124 $
 * @$Date: 2009-05-24 14:14:40 +0300 (Sk, 24 Geg 2009) $
 **/


if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}
?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#test-list").sortable({
			handle:'.handle',
			axis:'y',
			update:function () {
				var order = $('#test-list').sortable('serialize');
				$("#la").show("slow");
				$("#la").hide("slow");
				$.post("<?php echo url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ); ?>", {order:order});
			}
		});
		$("#test-list2").sortable({
			handle:'.handle',
			axis:'y',
			update:function () {
				var order2 = $('#test-list2').sortable('serialize');
				$("#la2").show("slow");
				$("#la2").hide("slow");
				$.post("<?php echo url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ); ?>", {order2:order2});
			}
		});
	});
</script>
<?php
$root = ROOT;
echo <<< HTML
<script type="text/javascript" src="{$root}javascript/jquery/jquery.asmselect.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("select[multiple]").asmSelect({
			addItemTarget: 'bottom',
			animate: true,
			highlight: true,
			removeLabel: '{$lang['system']['delete']}',
			highlightAddedLabel: '{$lang['admin']['added']}: ',
			highlightRemovedLabel: '{$lang['sb']['deleted']}: ',
			sortable: true
		});
	});
</script>
HTML;
include_once ( ROOT . 'priedai/class.php' );
if ( !isset( $_GET['f'] ) && !isset( $_POST['f_forumas'] ) ) {
	$_GET['f'] = 3;
	$url['f']  = 3;
}
//echo $_GET['f'];
$buttons = "
<div class=\"btns\">
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};f,1" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__plus.png\" alt=\"\" class=\"middle\"/>{$lang['system']['createcategory']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};f,2" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folder__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['system']['editcategory']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};f,3" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folders__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['forum_createsub']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};f,4" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/folders__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['forum_editsub']}</span></a>
</div>";
lentele( $lang['admin']['frm'], $buttons );

unset( $buttons );

//rikiuote
if ( isset( $_POST['order'] ) ) {
	$array = str_replace( "&", ",", $_POST['order'] );
	$array = str_replace( "listItem[]=", "", $array );
	$array = explode( ",", $array );

	foreach ( $array as $position => $item ):

		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";

		$where .= "$item,";
	endforeach;
	$where = rtrim( $where, ", " );
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1( $sqlas );

}
if ( isset( $_POST['order2'] ) ) {
	$array = str_replace( "&", ",", $_POST['order2'] );
	$array = str_replace( "listItem[]=", "", $array );
	$array = explode( ",", $array );

	foreach ( $array as $position => $item ):

		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";
		$where .= "$item,";
	endforeach;
	$where = rtrim( $where, ", " );
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET `place`=  CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1( $sqlas );

}
//teisiu sarasas
$lygiai = array_keys( $conf['level'] );
foreach ( $lygiai as $key ) {
	$teises[$key] = $conf['level'][$key]['pavadinimas'];
}
$teises[0] = $lang['admin']['for_guests'];
// Paspaustas kazkoks mygtukas
if ( isset( $_POST['f_sukurimas'] ) ) {
	$forumas = input( $_POST['f_pav'] );
	$result  = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "d_forumai` (`pav`, `lang`) VALUES (" . escape( $forumas ) . ", " . escape( lang() ) . ")" );

	if ( $result ) {
		msg( $lang['system']['done'], $lang['system']['categorycreated'] );
	} else {


		klaida( $lang['system']['error'], ' <b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );

	}

	unset( $forumas, $result );
}

//Kategorijos redagavimas
if ( isset( $_POST['keisti'] ) && $_POST['keisti'] == $lang['admin']['edit'] ) {
	$f_id           = (int)$_POST['f_edit'];
	$f_pav_keitimas = input( $_POST['f_pav_keitimas'] );
	$result         = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_forumai` SET `pav`=" . escape( $f_pav_keitimas ) . " WHERE `id`=" . escape( $f_id ) . "" );
	if ( $result ) {
		msg( $lang['system']['done'], $lang['system']['categoryupdated'] );

	} else {
		klaida( $lang['system']['error'], ' <b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );

	}
	unset( $f_info, $forumas, $result );
}
//Kategorijos trynimas (gali but problemu)
if ( isset( $_GET['d'] ) ) {
	$f_id  = (int)$_GET['d'];
	$strid = mysql_query1( "SELECT `id` from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`=" . escape( $f_id ) . " AND `lang` = " . escape( lang() ) . "" );
	if ( sizeof( $strid ) > 0 ) {
		foreach ( $strid as $stridi ) {
			$zinsid = mysql_query1( "SELECT `id` from `" . LENTELES_PRIESAGA . "d_straipsniai` where `tid`=" . escape( $stridi['id'] ) . " AND `lang` = " . escape( lang() ) . "" );
			if ( sizeof( $zinsid ) > 0 ) {
				foreach ( $zinsid as $zinsids ) {
					$result2 = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_zinute`  WHERE `sid`=" . escape( $zinsids['id'] ) . "" );
				}
			}

			$result3 = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_straipsniai`  where `tid`=" . escape( $stridi['id'] ) . "" );

		}
	}
	$result  = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_forumai`  WHERE `id`=" . escape( $f_id ) . "" );
	$result2 = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `fid`=" . escape( $f_id ) . "" );


	if ( $result && $result2 ) {
		msg( $lang['system']['done'], $lang['system']['categorydeleted'] );

	} else {
		klaida( $lang['system']['error'], ' <b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );
	}
	unset( $f_info, $forumas, $result );
}
//subkategorijos trynimas
if ( isset( $_GET['t'] ) ) {
	$f_id = (int)$_GET['t'];
	//sita atlieka (istrina subkategorija)
	$result = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_temos`  WHERE `id`=" . escape( $f_id ) . "" );
	//turetu istrint zinutes
	$sql12 = mysql_query1( "SELECT id from `" . LENTELES_PRIESAGA . "d_straipsniai` where `tid`=" . escape( $f_id ) . "" );
	if ( sizeof( $sql12 ) > 0 ) {
		foreach ( $sql12 as $sidas ) {
			$result2 = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_zinute`  WHERE sid=" . escape( $sidas['id'] ) . "" );

		}
	}
	//istina temas is kategorijos
	$result2 = mysql_query1( "DELETE from `" . LENTELES_PRIESAGA . "d_straipsniai`  WHERE `lang` = " . escape( lang() ) . " AND `tid`='" . $f_id . "'" );

	if ( $result ) {
		msg( $lang['system']['done'], $lang['admin']['forum_deletesub'] );

	} else {
		klaida( $lang['system']['error'], ' <b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );
	}
	unset( $f_id, $result2, $result );
}
//Subkategorijos kūrimas
if ( isset( $_POST['kurk'] ) && $_POST['kurk'] == $lang['admin']['forum_createsub'] ) {
	$f_id        = (int)$_POST['f_forumas'];
	$f_tema      = input( $_POST['f_tema'] );
	$f_aprasymas = input( $_POST['f_aprasymas'] );
	$result      = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "d_temos` (`fid`, `pav`, `aprasymas`, `lang`, `teises`) VALUES (" . escape( $f_id ) . ", " . escape( $f_tema ) . ", " . escape( $f_aprasymas ) . ", " . escape( lang() ) . ", " . escape( serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) ) ) . ")" );
	if ( $result ) {
		msg( $lang['system']['done'], $lang['admin']['forum_createdsub'] );

	} else {
		klaida( $lang['system']['error'], '<b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );

	}

	unset( $f_id, $f_tema, $f_aprasymas, $result );
}
//Subkategorijos redagavimas
if ( isset( $_POST['subedit'] ) && $_POST['subedit'] == $lang['admin']['forum_select'] ) {

	$f_id = (int)$_POST['f_forumas'];
	$sql  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `lang` = " . escape( lang() ) . " AND `fid`='" . $f_id . "' ORDER by place" );
	if ( sizeof( $sql ) > 0 ) {
		$tema = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` where `id`=" . escape( (int)$_POST['f_forumas'] ) . "  ORDER BY `place` ASC limit 1" );

		$li = '';
		foreach ( $sql as $record1 ) {
			$li .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';t,' . $record1['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record1['id'] . ';f,' . $tema['id'] ) . '" style="align:right" ><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" />
' . $record1['pav'] . '
</li> ';
		}
		$tekstas = '
<div id="la2" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>
			<ul id="test-list2">' . $li . '</ul>';
		lentele( $lang['admin']['forum_editsub'], $tekstas );
	}
}
//subkategorijos redag. forma... gal :)
if ( isset( $_GET['r'] ) && isset( $_GET['f'] ) ) {
	$f_id       = (int)$_GET['f'];
	$f_temos_id = (int)$_GET['r'];
	$sql        = mysql_query1( "SELECT pav FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `id`='" . $f_id . "' limit 1" );
	$f_forumas  = $sql['pav'];
	unset( $sql );
	$t_info = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_temos` WHERE `id`='" . $f_temos_id . "' limit 1" );
	$bla    = new forma();
	$forma  = array( "Form"                                => array( "action" => url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "port" ), "{$lang['admin']['forum_category']}:" => array( "type" => "string", "value" => "<b>" . $f_forumas . "</b><input type=\"hidden\" name=\"forumo_id\"  value='" . $f_id . "' /><input type=\"hidden\" name=\"temos_id\"  value='" . $t_info['id'] . "' />" ), "{$lang['admin']['forum_subcategory']}:"=> array( "type"=> "text", "value"=> $t_info['pav'], "name"=> "temos_pav" ),
	                 "{$lang['admin']['forum_subabout']}:" => array( "type" => "text", "value" => $t_info['aprasymas'], "name" => "temos_apr" ), "{$lang['system']['showfor']} :" => array( "type" => "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "class"=> "input", "name" => "Teises[]", "id" => "punktai", "selected" => unserialize( $t_info['teises'] ) ), "" => array( "type" => "submit", "name" => "subred", "value" => $lang['admin']['edit'] ) );
	lentele( $lang['admin']['forum_editsub'], $bla->form( $forma ) );
	unset( $t_info, $f_id, $f_temod_id, $sql, $f_forumas );
}
if ( isset( $_POST['subred'] ) && $_POST['subred'] == $lang['admin']['edit'] ) {
	$f_forumas      = (int)$_POST['forumo_id'];
	$f_tema         = (int)$_POST['temos_id'];
	$edit_tema      = input( $_POST['temos_pav'] );
	$edit_aprasymas = input( $_POST['temos_apr'] );
	$result         = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "d_temos` SET `pav`=" . escape( $edit_tema ) . ", `aprasymas`=" . escape( $edit_aprasymas ) . ", `teises`=" . escape( serialize( $_POST['Teises'] ) ) . " WHERE `fid`='" . $f_forumas . "' AND `id`=" . escape( $f_tema ) . "" );
	if ( $result ) {
		msg( $lang['system']['done'], $lang['admin']['forum_updatedsub'] );

	} else {
		klaida( $lang['system']['error'], '<b>' . mysqli_error($prisijungimas_prie_mysql) . '</b>' );

	}

}
// #############################################################
// Parodome pasirinktos funkcijos laukelius
// ##############################################################
if ( isset( $url['f'] ) ) {
	if ( (int)$url['f'] == 1 ) {
		$bla   = new forma();
		$forma = array( "Form" => array( "action" => url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "port" ), "{$lang['admin']['forum_category']}:" => array( "type" => "text", "name" => "f_pav" ), " " => array( "type" => "submit", "name" => "f_sukurimas", "value" => $lang['system']['createcategory'] ) );
		lentele( $lang['system']['createcategory'], $bla->form( $forma ) );

	}
	//Kategorijos redagavimas
	if ( (int)$url['f'] == 2 && !isset( $_GET['r'] ) ) {
		$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		if ( sizeof( $sql ) > 0 ) {
			$li = "";
			foreach ( $sql as $record1 ) {
				$cats[$record1['id']] = $record1['pav'];
				$li .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record1['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" />
' . $record1['pav'] . '
</li> ';
			}

			$bla = new forma();

			$forma = array( "Form" => array( "action" => url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "method" => "post", "enctype" => "", "id" => "", "class" => "" ), "{$lang['admin']['forum_category']}:" => array( "type" => "select", "name" => "f_edit", "value"=> $cats ), "{$lang['admin']['forum_cangeto']}:" => array( "type" => "text", "name" => "f_pav_keitimas" ), " " => array( "type" => "submit", "name" => "keisti", "value" => $lang['admin']['edit'] ) );
			lentele( $lang['system']['editcategory'], $bla->form( $forma ) );

			$tekstas = '
<div id="la" style="display:none"><b>' . $lang['system']['updated'] . '</b></div>
			<ul id="test-list">' . $li . '</ul>';
			lentele( $lang['admin']['forum_order'], $tekstas );
		} else {
			klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
		}
		unset( $f_text, $sql, $row );
	}
	//subkat. kūrimo forma
	if ( (int)$url['f'] == 3 && !isset( $_GET['r'] ) ) {
		$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		if ( sizeof( $sql ) > 0 ) {
			$bla = new forma();
			foreach ( $sql as $row ) {
				$categories[$row['id']] = $row['pav'];
			}

			$forma = array( "Form"                                => array( "action" => url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "kurk" ), "{$lang['admin']['forum_category']}:" => array( "type" => "select", "value" => $categories, "name"=> "f_forumas" ), "{$lang['admin']['forum_subcategory']}:"=> array( "type"=> "text", "value"=> "", "name"=> "f_tema" ),
			                "{$lang['admin']['forum_subabout']}:" => array( "type" => "text", "name" => "f_aprasymas" ),
			                "{$lang['system']['showfor']} :"      => array( "type" => "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "class"=> "input", "name" => "Teises[]", "id" => "punktai" ),
			                ""                                    => array( "type" => "submit", "name" => "kurk", "value" => $lang['admin']['forum_createsub'] ) );

			lentele( $lang['admin']['forum_createsub'], $bla->form( $forma ) );
		} else {
			klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
		}
		unset( $f_text, $sql, $row );
	}
	//subkat redag?
	if ( (int)$url['f'] == 4 ) {
		$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "d_forumai` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		if ( sizeof( $sql ) > 0 ) {
			foreach ( $sql as $row ) {
				$forums[$row['id']] = $row['pav'];
			}

			$bla   = new forma();
			$forma = array( "Form" => array( "action" => url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "method" => "post", "enctype" => "", "id" => "", "class" => "" ), "{$lang['admin']['forum_subwhere']}:" => array( "type" => "select", "name" => "f_forumas", "value"=> $forums ),

			                " "    => array( "type" => "submit", "name" => "subedit", "value" => $lang['admin']['forum_select'] ) );
			lentele( $lang['admin']['forum_editsub'], $bla->form( $forma ) );
		} else {
			klaida( $lang['system']['warning'], $lang['system']['nocategories'] );
		}

		unset( $f_text, $sql, $row );
	}
}

?>
	
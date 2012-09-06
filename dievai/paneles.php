<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 375 $
 * @$Date: 2010-02-07 16:15:41 +0200 (Sun, 07 Feb 2010) $
 **/

if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

?>
<script type="text/javascript">
	$(document).ready(function () {
		$("#kaire").sortable({
			handle:'.handle',
			axis:'y',
			update:function () {
				var order = $('#kaire').sortable('serialize');
				$("#la").fadeIn(1500);
				$("#la").fadeOut(3000);
				$.post("<?php echo url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";" ); ?>", {order:order});

			}
		});
		$("#desine").sortable({
			handle:'.handle',
			axis:'y',
			update:function () {
				var order = $('#desine').sortable('serialize');
				$("#la").fadeIn(1500);
				$("#la").fadeOut(3000);
				$.post("<?php echo url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";" ); ?>", {order:order});

			}
		});
		$("#centras").sortable({
			handle:'.handle',
			axis:'y',
			update:function () {
				var order = $('#centras').sortable('serialize');
				$("#la").fadeIn(1500);
				$("#la").fadeOut(3000);
				$.post("<?php echo url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . ";" ); ?>", {order:order});

			}
		});
	});
</script>
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
			sortable:true
		});
	});
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#pasirinkimas").hide();
		$("#pasirinkimas2").hide();
	});
	$(function () {
		$("#lygiuojam").change(function () {
			var selectedValue = $(this).find(":selected").val();
			if (selectedValue == 'C') {
				$("#pasirinkimas").show();
				$("#pasirinkimas2").show();
			} else {
				$(document).ready(function () {
					$("#pasirinkimas").hide();
					$("#pasirinkimas2").hide();

				});
			}
		});
	});
</script>
<?php
//lentele($lang['admin']['blokai'], $buttons);
if ( isset( $_POST['order'] ) ) {
	$array = str_replace( "&", ",", $_POST['order'] );
	$array = str_replace( "listItem[]=", "", $array );
	$array = explode( ",", $array );
	//$array=array($array);
	//print_r($array);
	//$sql=array();
	foreach ( $array as $position => $item ):

		$case_place .= "WHEN " . (int)$item . " THEN '" . (int)$position . "' ";

		$where .= "$item,";
	endforeach;
	$where = rtrim( $where, ", " );
	$sqlas .= "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `place`= CASE id " . $case_place . " END WHERE id IN (" . $where . ")";
	echo $sqlas;
	$result = mysql_query1( $sqlas );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );

} else {
	$text   = "
<div class=\"btns\">
	<a href=\"" . url( "?id," . $url['id'] . ";a,{$_GET['a']};n,1" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/script__plus.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['panel_select']}</span></a>
	<a href=\"" . url( "?id,{$_GET['id']};a,{$_GET['a']};n,2" ) . "\" class=\"btn\"><span><img src=\"" . ROOT . "images/icons/script__pencil.png\" alt=\"\" class=\"middle\"/>{$lang['admin']['panel_create']}</span></a>
</div>";
	$lygiai = array_keys( $conf['level'] );

	foreach ( $lygiai as $key ) {
		$teises[$key] = $conf['level'][$key]['pavadinimas'];
	}
	$teises[0] = $lang['admin']['for_guests'];
	if ( isset( $_POST['Naujaa_pnl'] ) && $_POST['Naujaa_pnl'] == $lang['admin']['panel_create'] ) {
		// Nurodote failo pavadinimą
		$failas  = ROOT . "blokai/" . preg_replace( "/[^a-z0-9-]/", "_", strtolower( $_POST['pav'] ) ) . ".php";
		$tekstas = str_replace( array( '$', 'HTML', '<br>' ), array( '&#36;', 'html', '<br/>' ), $_POST['pnl'] );
		$irasas  = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
?>';

		//Irasom faila
		$fp = fopen( $failas, "w+" );
		fwrite( $fp, $irasas );
		fclose( $fp );
		chmod( $failas, 0777 );
		redirect( url( "?id,{$_GET['id']};a,{$_GET['a']};n,1" ), "header" );
	}
	if ( isset( $url['n'] ) && $url['n'] == 2 ) {
		$psl = array( "Form" => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "Naujaa_pnl" ), "{$lang['admin']['panel_name']}:" => array( "type" => "text", "value" => "Naujas blokas", "name" => "pav", "class" => "input" ), "{$lang['admin']['panel_text']}:" => array( "type" => "string", "value" => editor( 'spaw', 'standartinis', array( 'pnl' => 'pnl' ), FALSE ), "name" => "pnl", "class" => "input", "rows" => "8", "class" => "input" ), "" => array( "type" => "submit", "name" => "Naujaa_pnl", "value" => "{$lang['admin']['panel_create']}" ) );
		include_once ( ROOT . "priedai/class.php" );
		$bla = new forma();
		lentele( "{$lang['admin']['panel_new']}", $bla->form( $psl, "{$lang['admin']['panel_new']}" ) );
	}
	if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] > 0 ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`= " . escape( (int)$url['d'] ) . " LIMIT 1" );
		delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		redirect( url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "header" );
	} //naujos blokai sukurimas
	elseif ( isset( $url['n'] ) && $url['n'] == 1 ) {
		if ( isset( $_POST['Nauja_panele'] ) && $_POST['Nauja_panele'] == $lang['admin']['panel_create'] ) {
			$panel  = input( $_POST['Panel'] );
			$rodyti = input( $_POST['rodyti'] );
			$file   = input( basename( $_POST['File'] ) );
			if ( !file_exists( ROOT . "blokai/" . $file ) ) {
				klaida( $lang['system']['error'], "<font color='red'>" . $file . "</font>" );
			} else {
				if ( empty( $panel ) || $panel == '' ) {
					$panel = basename( $file, ".php" );
				}
				$align = input( $_POST['Align'] );
				if ( strlen( $align ) > 1 ) {
					$align = 'L';
				}
				$show = input( $_POST['Show'] );
				if ( strlen( $show ) > 1 ) {
					$align = 'Y';
				}
				$teisess = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );
				$sql     = "INSERT INTO `" . LENTELES_PRIESAGA . "panel` (`rodyti`, `panel`, `file`, `place`, `align`, `show`, `teises`, `lang`) VALUES (" . escape( $rodyti ) . ", " . escape( $panel ) . ", " . escape( $file ) . ", '0', " . escape( $align ) . ", " . escape( $show ) . ", " . escape( $teisess ) . ", " . escape( lang() ) . ")";
				mysql_query1( $sql );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				redirect( url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "header" );
			}
		}
		$failai = getFiles( ROOT . 'blokai/' );
		foreach ( $failai as $file ) {
			if ( $file['type'] == 'file' ) {
				$sql = mysql_query1( "SELECT `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `file`=" . escape( basename( $file['name'] ) ) . " AND `lang` = " . escape( lang() ) . " LIMIT 1" );
				if ( $sql['file'] != basename( $file['name'] ) ) {
					$blokai[basename( $file['name'] )] = ( isset( $lang['blocks'][$file['name']] ) ? $lang['blocks'][$file['name']] : nice_name( basename( $file['name'], '.php' ) ) );
				}
			}
		}

		if ( !isset( $blokai ) || count( $blokai ) < 1 ) {
			klaida( $lang['system']['error'], "<h3>{$lang['admin']['panel_no']}.</h3>" );
		} else {
			$panele = array( "Form"                                                                                                                                                                                                                                    => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel" ),
			                 "{$lang['admin']['panel_title']}:"                                                                                                                                                                                                        => array( "type" => "text", "value" => "{$lang['admin']['panel_new']}", "name" => "Panel", "class" => "input" ),
			                 "{$lang['admin']['panel_name']}:"                                                                                                                                                                                                         => array( "type" => "select", "value" => $blokai, "name" => "File" ),
			                 "{$lang['admin']['panel_side']}:"                                                                                                                                                                                                         => array( "id" => "lygiuojam", "type" => "select", "value" => array( "L" => "{$lang['admin']['panel_left']}", "R" => "{$lang['admin']['panel_right']}", "C" => "Centras" ), "name" => "Align" ),
			                 "<div id='pasirinkimas2'>{$lang['admin']['panel_do_show']}:</div>"                                                                                                                                                                        => array( "id" => "pasirinkimas", "type" => "select", "value" => array( "Taip" => "{$lang['admin']['panel_do_all']}", "Ne" => "{$lang['admin']['panel_do_all']}" ), "name" => "rodyti" ),
			                 "{$lang['admin']['panel_showtitle']}?"                                                                                                                                                                                                    => array( "type" => "select", "value" => array( "Y" => "{$lang['admin']['yes']}", "N" => "{$lang['admin']['no']}" ), "name" => "Show" ),
			                 "{$lang['admin']['panel_showfor']}:"                                                                                                                                                                                                      => array( "type"            =>
			                                                                                                                                                                                                                                                                     "select", "extra" => "multiple=multiple", "value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai" ), "" => array( "type" => "submit", "name" => "Nauja_panele", "value" => "{$lang['admin']['panel_create']}" ) );

			include_once ( ROOT . "priedai/class.php" );
			$bla = new forma();
			lentele( $lang['admin']['panel_new'], $bla->form( $panele, $lang['admin']['panel_new'] ) );
		}
	}

	//blokai redagavimas
	elseif ( isset( $url['r'] ) && isnum( $url['r'] ) && $url['r'] > 0 ) {
		if ( isset( $_POST['Redaguoti_panele'] ) && $_POST['Redaguoti_panele'] == "{$lang['admin']['edit']}" ) {
			$panel   = input( $_POST['Panel'] );
			$rodyti  = input( $_POST['rodyti'] );
			$teisess = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );
			if ( empty( $panel ) || $panel == '' ) {
				$panel = $lang['admin']['panel_new'];
			}
			$align = input( $_POST['Align'] );
			if ( strlen( $align ) > 1 ) {
				$align = 'L';
			}
			$show = input( $_POST['Show'] );
			if ( strlen( $show ) > 1 ) {
				$align = 'Y';
			}

			$sql = "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `rodyti`=" . escape( $rodyti ) . ", `panel`=" . escape( $panel ) . ", `align`=" . escape( $align ) . ", `show`=" . escape( $show ) . ",`teises`=" . escape( $teisess ) . ", `lang` = " . escape( lang() ) . " WHERE `id`=" . escape( (int)$url['r'] );
			mysql_query1( $sql );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			delete_cache( "SELECT* FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
			redirect( url( "?id," . $url['id'] . ";a,{$_GET['a']}" ), "header" );
		} else {

			$sql      = "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape( (int)$url['r'] ) . " LIMIT 1";
			$sql      = mysql_query1( $sql );
			$selected = unserialize( $sql['teises'] );
			$panele   = array( "Form"                                                                                                                                                                                                                   => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "new_panel" ),
			                   "{$lang['admin']['panel_title']}:"                                                                                                                                                                                       => array( "type" => "text", "value" => input( $sql['panel'] ), "name" => "Panel", "class" => "input" ),
			                   "{$lang['admin']['panel_side']}:"                                                                                                                                                                                        => array( "id" => "lygiuojam", "type" => "select", "value" => array( "L" => "{$lang['admin']['panel_left']}", "R" => "{$lang['admin']['panel_right']}", "C" => "Centras" ), "selected" => input( $sql['align'] ), "name" => "Align" ),
			                   "<div id='pasirinkimas2'>{$lang['admin']['panel_do_show']}:</div>"                                                                                                                                                       => array( "id" => "pasirinkimas", "type" => "select", "value" => array( "Taip" => "Visuose puslapiuose", "Ne" => "Tik pirminiame" ), "selected" => input( $sql['rodyti'] ), "name" => "rodyti" ),
			                   "{$lang['admin']['panel_showtitle']}?"                                                                                                                                                                                   => array( "type" => "select", "value" => array( "Y" => "{$lang['admin']['yes']}", "N" => "{$lang['admin']['no']}" ), "selected" => input( $sql['show'] ), "name" => "Show" ),
			                   "{$lang['admin']['panel_showfor']}:"                                                                                                                                                                                     => array( "type"  => "select", "extra" => "multiple=multiple",
			                                                                                                                                                                                                                                                      "value" => $teises, "class" => "asmSelect", "style" => "width:100%", "name" => "Teises[]", "id" => "punktai", "selected" => $selected ), "" => array( "type" => "submit", "name" => "Redaguoti_panele", "value" => "{$lang['admin']['edit']}" ) );

			include_once ( ROOT . "priedai/class.php" );
			$bla = new forma();
			lentele( $sql['panel'], $bla->form( $panele ) );
		}
	}

	//Redaguojam panelės turinį
	elseif ( isset( $url['e'] ) && isnum( $url['e'] ) && $url['e'] > 0 ) {
		$panel_id = (int)$url['e']; //Panelės ID

		if ( isset( $_POST['Turinys'] ) && !empty( $_POST['Turinys'] ) ) {
			$sql = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape( $panel_id ) . " LIMIT 1";
			$sql = mysql_query1( $sql );
			if ( !is_writable( ROOT . 'blokai/' . $sql['file'] ) ) {
				klaida( $lang['system']['warning'], $lang['admin']['panel_cantedit'] );
			} else {
				$failas  = ROOT . "blokai/" . $sql['file'];
				$tekstas = str_replace( array( '$', '<br>', 'HTML' ), array( '&#36;', '<br/>', 'html' ), $_POST['Turinys'] );
				$irasas  = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
?>';
				//Irasom faila
				$fp = fopen( $failas, "w+" );
				fwrite( $fp, $irasas );
				fclose( $fp );
				chmod( $failas, 0777 );

			}
		} else {
			$sql = "SELECT `id`, `panel`, `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape( $panel_id ) . " LIMIT 1";
			$sql = mysql_query1( $sql );
			//tikrinam failo struktura

			$lines      = file( ROOT . 'blokai/' . $sql['file'] );
			$resultatai = array();

			$zodiz = '$text ='; // "http" - žodis kurio ieškoma
			for ( $i = 0; $i < count( $lines ); $i++ ) {
				$temp = trim( $lines[$i] );
				if ( substr_count( $temp, $zodiz ) > 0 ) {
					$resultatai[] = $temp;
					//if(isset($rezultatai[$i]))echo $resultatai[$i];
					$nr = ( $i + 1 );
				}
			}

			//tikrinimo pabaiga
			if ( isset( $nr ) && $nr == 2 ) {
				include ROOT . 'blokai/' . $sql['file'];

				if ( isset( $text ) && is_writable( ROOT . 'blokai/' . $sql['file'] ) ) {
					$blokai_txt = $text;
					$panele     = array( "Form" => array( "action" => "", "method" => "post", "enctype" => "", "id" => "", "class" => "", "name" => "panel_txt" ), $lang['admin']['panel_text'] => array( "type" => "string", "value" => editor( 'spaw', 'standartinis', array( 'Turinys' => 'Bloko turinys' ), array( 'Turinys' => ( isset( $blokai_txt ) ) ? $blokai_txt : '' ) ) ), "" => array( "type" => "submit", "name" => "Redaguoti_txt", "value" => "{$lang['admin']['edit']}" ) );

					include_once ( ROOT . "priedai/class.php" );
					$bla = new forma();
					lentele( $sql['panel'], $bla->form( $panele ) );
				} else {
					klaida( $lang['system']['warning'], $lang['admin']['panel_cantedit'] );
				}
			} else {
				klaida( $lang['system']['warning'], $lang['admin']['panel_cantedit'] );
			}
		}
	}


	//atvaizduojam blokus
	$li        = "";
	$li1       = "";
	$li2       = "";
	$sql       = "SELECT `id`, `panel`, `place` FROM `" . LENTELES_PRIESAGA . "panel` WHERE align='L' AND `lang` = " . escape( lang() ) . " order by `place`";
	$recordSet = mysql_query1( $sql );
	if ( sizeof( $recordSet ) > 0 ) {
		foreach ( $recordSet as $record ) {
			$li .= '<li id="listItem_' . $record['id'] . '" class="drag_block"> 
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a>
<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" />
' . $record['panel'] . '
</li> ';
		}
	}
	$sql1       = "SELECT `id`, `panel`, `place` FROM `" . LENTELES_PRIESAGA . "panel` WHERE align='R' AND `lang` = " . escape( lang() ) . " order by `place`";
	$recordSet1 = mysql_query1( $sql1 );
	if ( sizeof( $recordSet1 ) > 0 ) {
		foreach ( $recordSet1 as $record1 ) {

			$li1 .= '<li id="listItem_' . $record1['id'] . '" class="drag_block"> 
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record1['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record1['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record1['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a>
<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" />
' . $record1['panel'] . '
</li> ';
		}
	}
	$sql2       = "SELECT id, panel, place from `" . LENTELES_PRIESAGA . "panel` WHERE align='C' AND `lang` = " . escape( lang() ) . " order by place";
	$recordSet2 = mysql_query1( $sql2 );
	if ( sizeof( $recordSet2 ) > 0 ) {
		foreach ( $recordSet2 as $record2 ) {

			$li2 .= '<li id="listItem_' . $record2['id'] . '" class="drag_block"> 
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $record2['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $record2['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $record2['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a>
<img src="' . ROOT . 'images/icons/arrow_inout.png" alt="move" width="16" height="16" class="handle" />
' . $record2['panel'] . '
</li> ';
		}
	}
	$text .= '
	<div id="la" style="display:none">
	<div class="success">
	<img src="../images/icons/tick.png" title="' . $lang['system']['updated'] . '" align="left" hspace="10" alt=""/>
	&nbsp;&nbsp;' . $lang['system']['updated'] . '</div>
    </div>
			<fieldset style="width: 28%; float:left;">
			<legend>' . $lang['admin']['panel_left'] . '</legend>
			<ul id="kaire">' . $li . '</ul></fieldset>';
	$text .= '<fieldset style="width: 28%;float:left;">
			<legend>' . $lang['admin']['panel_center'] . '</legend>
		<ul id="centras">' . $li2 . '</ul></fieldset>';
	$text .= '<fieldset style="width: 28%;float:left;">
	<legend>' . $lang['admin']['panel_right'] . '</legend>
		   <ul id="desine">' . $li1 . '</ul></fieldset>';


	lentele( $lang['admin']['paneles'], $text );

	//Funkcija panelių turiniui įrašyti
	function irasom( $Failas, $Info ) {

		global $url, $lang;
		if ( is_writable( $Failas ) ) {
			if ( $fh = fopen( $Failas, 'w' ) ) {
				$tekstas = str_replace( array( '$', 'HTML', '<br>' ), array( '&#36;', 'html', '<br />' ), $Info );

				$Info = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
?>';

				if ( fwrite( $fh, $Info ) !== FALSE ) {
					msg( $lang['system']['done'], $lang['admin']['panel_updated'] );
					fclose( $fh );
					chmod( $Failas, 0777 );
					redirect( url( "?id," . $url['id'] . ";a," . $url['a'] ), "meta" );
				}
			} else {
				klaida( $lang['system']['error'], $lang['system']['systemerror'] );
			}
		} else {
			klaida( $Failas, $lang['system']['systemerror'] );
		}
	}
	//unset($text, $_POST);
}

?>
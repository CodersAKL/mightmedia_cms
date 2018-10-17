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

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['paneles'], buttonsMenu($buttons['blocks']));
}

$text   = "";
// user rights
$lygiai = array_keys( $conf['level'] );

foreach ( $lygiai as $key ) {
	$teises[$key] = $conf['level'][$key]['pavadinimas'];
}

$teises[0] = $lang['admin']['for_guests'];

if ( isset( $_POST['Naujaa_pnl'] ) && $_POST['Naujaa_pnl'] == $lang['admin']['panel_create'] ) {
	// Nurodote failo pavadinimą
	//$failas  = ROOT . "blokai/" . preg_replace( "/[^a-z0-9-]/", "_", strtolower( $_POST['pav'] ) ) . ".php";

	$failas = ROOT . "blokai/" . seo_url(basename($_POST['pav']), '') . ".php";

	$tekstas = str_replace( array( '$', 'HTML', '<br>' ), array( '&#36;', 'html', '<br/>' ), $_POST['pnl'] );

	$irasas  = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
?>';
//Tikrinam ar nera tokio pacio failo
	if (file_exists($failas)) {
		klaida($lang['system']['error'], "{$lang['system']['file_exists']}.");
	} else {
		//Irasom faila
		$fp = fopen( $failas, "w+" );
		fwrite( $fp, $irasas );
		fclose( $fp );
		chmod( $failas, 0777 );
		redirect( url( "?id,{$_GET['id']};a,{$_GET['a']};n,1" ), "header" );
		// Rezultatas:
		//msg($lang['system']['done'], "{$lang['admin']['page_created']}.");
		redirect( url( "?id,{$_GET['id']};a,{$_GET['a']};n,1" ), "header" );
	}
}

if ( isset( $url['n'] ) && $url['n'] == 2 ) {
	$psl = array(
		"Form" => array(
			"action" => "",
			"method" => "post",
			"enctype" => "",
			"id" => "",
			"class" => "",
			"name" => "Naujaa_pnl"
		), 
		"{$lang['admin']['panel_name']}:" => array(
			"type" => "text", 
			"value" => "Naujas blokas", 
			"name" => "pav"
		), 
		"{$lang['admin']['panel_text']}:" => array(
			"type" => "string",
			"value" => editor('spaw', 'standartinis', array('pnl' => 'pnl'), FALSE), 
			"name" => "pnl",
			"rows" => "8"
		),
		"" => array(
			"type" => "submit", "name" => "Naujaa_pnl", "value" => "{$lang['admin']['panel_create']}" ) );
	
	$formClass = new Form($psl);	
	lentele($lang['admin']['panel_new'], $formClass->form());
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

		$panele = array(
			"Form"                                 => array(
				"action"  => "",
				"method"  => "post",
				"enctype" => "",
				"id"      => "",
				"class"   => "",
				"name"    => "new_panel" ),

			"{$lang['admin']['panel_title']}:"     => array(
				"type"  => "text",
				"value" => "{$lang['admin']['panel_new']}",
				"name"  => "Panel",
				"class" => "input" ),

			"{$lang['admin']['panel_name']}:"      => array(
				"type"  => "select",
				"value" => $blokai,
				"name"  => "File" ),

			"{$lang['admin']['panel_side']}:"      => array(
				"id"    => "lygiuojam",
				"type"  => "select",
				"value" => array(
					"L" => "{$lang['admin']['panel_left']}",
					"R" => "{$lang['admin']['panel_right']}",
					"C" => "{$lang['admin']['panel_center']}" ),
				"name"  => "Align" ),

			"{$lang['admin']['panel_do_show']}:"	=> array(
				"id"    => "pasirinkimas",
				"type"  => "select",
				"value" => array(
					"Taip" => "{$lang['admin']['panel_do_all']}",
					"Ne"   => "{$lang['admin']['panel_do_one']}" ),
				"name"  => "rodyti",
				'class'	=> 'panel-show'
			),
			
			"{$lang['admin']['panel_showtitle']}" => array(
				"type"  => "select",
				"value" => array(
					"Y" => "{$lang['admin']['yes']}",
					"N" => "{$lang['admin']['no']}" ),
				"name"  => "Show" ),

			"{$lang['admin']['panel_showfor']}:"   => array(
				"type"  => "select",
				"extra" => "multiple",
				"value" => $teises,
				"name"  => "Teises[]",
				"id"    => "punktai" ),

			""                                     => array(
				"type"  => "submit",
				"name"  => "Nauja_panele",
				"value" => "{$lang['admin']['panel_create']}" )
		);

		$formClass = new Form($panele);	
		lentele($lang['admin']['panel_new'], $formClass->form());
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
		$panele = array(
			"Form"                                    => array(
				"action"  => "",
				"method"  => "post",
				"enctype" => "",
				"id"      => "",
				"class"   => "",
				"name"    => "new_panel" ),
			"{$lang['admin']['panel_title']}:"        => array(
				"type"  => "text",
				"value" => input( $sql['panel'] ),
				"name"  => "Panel",
				"class" => "input" ),

			"{$lang['admin']['panel_side']}:"         => array(
				"id"       => "lygiuojam",
				"type"     => "select",
				"value"    => array(
					"L" => "{$lang['admin']['panel_left']}",
					"R" => "{$lang['admin']['panel_right']}",
					"C" => "{$lang['admin']['panel_center']}" ),
				"selected" => input( $sql['align'] ),
				"name"     => "Align" ),

			"{$lang['admin']['panel_do_show']}:"	=> array(
				"id"       => "pasirinkimas",
				"type"     => "select",
				"value"    => array(
					"Taip" => "{$lang['admin']['panel_do_all']}",
					"Ne"   => "{$lang['admin']['panel_do_one']}"),
				"selected" => input( $sql['rodyti'] ),
				"name"     => "rodyti",
				'class'		=> 'panel-show'
			),

			"{$lang['admin']['panel_showtitle']}"    => array(
				"type"     => "select",
				"value"    => array(
					"Y" => "{$lang['admin']['yes']}",
					"N" => "{$lang['admin']['no']}" ),
				"selected" => input( $sql['show'] ),
				"name"     => "Show" ),

			"{$lang['admin']['panel_showfor']}:"      => array(
				"type"     => "select",
				"extra"    => "multiple",
				"value"    => $teises,
				"name"     => "Teises[]",
				"id"       => "punktai",
				"selected" => $selected ),

			""                                        => array(
				"type"  => "submit",
				"name"  => "Redaguoti_panele",
				"value" => "{$lang['admin']['edit']}" )
		);

		$formClass = new Form($panele);	
		lentele($sql['panel'], $formClass->form());
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
				$panele = array(
					"Form"                       => array(
						"action"  => "",
						"method"  => "post",
						"enctype" => "",
						"id"      => "",
						"class"   => "",
						"name"    => "panel_txt" ),

					$lang['admin']['panel_text'] => array(
						"type"  => "string",
						"value" => editor( 'spaw', 'standartinis', array( 'Turinys' => 'Bloko turinys' ), array( 'Turinys' => ( isset( $blokai_txt ) ) ? $blokai_txt : '' ) ) ),

					""                           => array(
						"type"  => "submit",
						"name"  => "Redaguoti_txt",
						"value" => "{$lang['admin']['edit']}" )
				);

				$formClass = new Form($panele);	
				lentele($sql['panel'], $formClass->form());
			} else {
				klaida( $lang['system']['warning'], $lang['admin']['panel_cantedit'] );
			}
		} else {
			klaida( $lang['system']['warning'], $lang['admin']['panel_cantedit'] );
		}
	}
}

function blockContent($data)
{
	global $url, $lang;

	$content = '<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $data['id'] ) . '" style="align:right" onClick="return confirm(\'' . $lang['admin']['delete'] . '?\')"><img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" align="right" /></a>
	<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $data['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" align="right" /></a>
	<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $data['id'] ) . '" style="align:right"><img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['panel_text'] . '" align="right" /></a>
	' . $data['panel'];

	return $content;
}

$liLeft		= "";
$liRight	= "";
$liCenter	= "";

$sqlLeft       = "SELECT `id`, `panel`, `place` FROM `" . LENTELES_PRIESAGA . "panel` WHERE align='L' AND `lang` = " . escape( lang() ) . " order by `place`";
$leftBlocks = mysql_query1($sqlLeft);

if (! empty($leftBlocks)) {
	$liLeft .= '<div class="dd nestable-with-handle"><ol class="dd-list">';
	foreach ($leftBlocks as $leftBlock) {
		
		$content = blockContent($leftBlock);	
		$liLeft .= dragItem($leftBlock['id'], $content);
	}
	$liLeft .= '</ol></div>';
}

$sqlRight	= "SELECT `id`, `panel`, `place` FROM `" . LENTELES_PRIESAGA . "panel` WHERE align='R' AND `lang` = " . escape( lang() ) . " order by `place`";
$rightsBlocks = mysql_query1($sqlRight);
if (! empty($rightsBlocks)) {
	$liRight .= '<div class="dd nestable-with-handle"><ol class="dd-list">';
	foreach ($rightsBlocks as $rightBlock) {
		$content = blockContent($rightBlock);	
		$liRight .= dragItem($rightBlock['id'], $content);
	}
	$liRight .= '</ol></div>';
}

$sqlCenter	= "SELECT id, panel, place from `" . LENTELES_PRIESAGA . "panel` WHERE align='C' AND `lang` = " . escape( lang() ) . " order by place";
$centerBlocks = mysql_query1($sqlCenter);
if (! empty($centerBlocks)) {
	$liCenter .= '<div class="dd nestable-with-handle"><ol class="dd-list">';
	foreach ($centerBlocks as $centerBlock) {

		$content = blockContent($centerBlock);	
		$liCenter .= dragItem($centerBlock['id'], $content);
	}
	$liCenter .= '</ol></div>';
}

?>
<div class="row clearfix">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<?php lentele($lang['admin']['panel_left'], $liLeft); ?>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<?php lentele($lang['admin']['panel_center'], $liCenter); ?>
	</div>
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
		<?php lentele($lang['admin']['panel_right'], $liRight); ?>
	</div>
</div>


<?php

// lentele( $lang['admin']['paneles'], $text );

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
?>
<script type="text/javascript">
	$('.dd').on('change', function () {
        var $this = $(this);
		var serializedData = window.JSON.stringify($($this).nestable('serialize')),
			data = {
				action: 'blocksOrder',
				order: serializedData
			};

		$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
			showNotification('alert-success', response);
		});
    });
</script>
<script type="text/javascript">

	$(function () {
		$(".panel-show").attr('disabled', 'disabled');
		$("#lygiuojam").change(function () {
			var selectedValue = $(this).find(":selected").val();
			if (selectedValue == 'C') {
				$(".panel-show").removeAttr('disabled');
			} else {
				$(".panel-show").attr('disabled', 'disabled');
			}
			$('.panel-show').selectpicker('refresh');
		});
	});
</script>
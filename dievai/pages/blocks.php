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

include 'functions/functions.blocks.php';

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['blocks'], buttonsMenu(buttons('blocks')));
}

$text   = "";
// user rights
$lygiai = array_keys( $conf['level'] );

foreach ( $lygiai as $key ) {
	$teises[$key] = $conf['level'][$key]['pavadinimas'];
}

$teises[0] = $lang['admin']['for_guests'];

if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] > 0 ) {
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`= " . escape( (int)$url['d'] ) . " LIMIT 1" );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	
	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> $lang['admin']['post_deleted']
		]
	);
}

if ( isset( $url['n'] ) && $url['n'] == 2 ) {
	if ( isset($_POST['file_action']) && $_POST['file_action'] == $lang['admin']['panel_create']) {
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
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['file_exists']
				]
			);
		} else {
			//Irasom faila
			$fp = fopen( $failas, "w+" );
			fwrite( $fp, $irasas );
			fclose( $fp );
			chmod( $failas, 0777 );

			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['post_created']
				]
			);
		}
	}
	$blockCreateForm = [
		"Form"							=> [
			"action" 	=> "",
			"method" 	=> "post",
			"enctype"	=> "",
			"id" 		=> "",
			"class" 	=> ""
		], 
		$lang['admin']['panel_name'] 	=> [
			"type" 			=> "text", 
			"placeholder" 	=> "Naujas blokas", 
			"name" 			=> "pav"
		], 
		$lang['admin']['panel_text']	=> [
			"type" 	=> "string",
			"value" => editor('spaw', 'standartinis', ['pnl' => 'pnl'], FALSE), 
			"name" 	=> "pnl",
			"rows" 	=> "8"
		],
		""								=> [
			"type"  	=> "submit",
			"name"  	=> "file_action",
			'form_line'	=> 'form-not-line',
			"value" 	=> $lang['admin']['panel_create']
		]
	];

	$formClass = new Form($blockCreateForm);	
	lentele($lang['admin']['panel_new'], $formClass->form());

} elseif (isset( $url['n'] ) && $url['n'] == 1 || isset( $url['r'] ) && isnum( $url['r'] ) && $url['r'] > 0 ) {
	if (isset($_POST['action'])) {
		$panel  = input( $_POST['Panel'] );
		$rodyti = ! empty($_POST['rodyti']) ? input($_POST['rodyti']) : 'Ne';
		$file   = ! empty($_POST['File']) ? input($_POST['File']) : null;

		if ( ! file_exists(ROOT . $file)) {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $file
				]
			);
		} else {
			if ( empty( $panel ) || $panel == '' ) {
				$panel = basename( $file, ".php" );
			}

			$align = input($_POST['Align']);
			if (strlen( $align ) > 1) {
				$align = 'L';
			}

			if(! empty($_POST['show']) && $_POST['show'] === 'Y') {
				$show = input($_POST['show']);
			} else {
				$show = 'N';
			}

			$teisess = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );

			//create
			if($_POST['action'] == $lang['admin']['panel_create']) {
				$sql     = "INSERT INTO `" . LENTELES_PRIESAGA . "panel` (`rodyti`, `panel`, `file`, `place`, `align`, `show`, `teises`, `lang`) VALUES (" . escape( $rodyti ) . ", " . escape( $panel ) . ", " . escape( $file ) . ", '0', " . escape( $align ) . ", " . escape( $show ) . ", " . escape( $teisess ) . ", " . escape( lang() ) . ")";
				mysql_query1( $sql );
	
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> $lang['admin']['post_created']
					]
				);
			} else if($_POST['action'] == $lang['admin']['edit']) {
				$sql = "UPDATE `" . LENTELES_PRIESAGA . "panel` SET `rodyti`=" . escape( $rodyti ) . ", `panel`=" . escape( $panel ) . ", `align`=" . escape( $align ) . ", `show`=" . escape( $show ) . ",`teises`=" . escape( $teisess ) . ", `lang` = " . escape( lang() ) . " WHERE `id`=" . escape( (int)$url['r'] );
				mysql_query1( $sql );

				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='R' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='L' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
				delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `align`='C' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );

				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> $lang['admin']['post_updated']
					]
				);
			}
			
		}
	}

	$failai = getFiles(ROOT . 'blokai/');
	//extensions
	$failai = applyFilters('cmsBlocks', $failai);

	foreach ($failai as $file) {
		if ($file['type'] == 'file' ) {
			$sql = mysql_query1( "SELECT `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `file`=" . escape( basename( $file['name'] ) ) . " AND `lang` = " . escape( lang() ) . " LIMIT 1" );
			if ($sql['file'] != $file['name']) {
				$blokai[$file['name']] = (isset( $lang['blocks'][$file['name']] ) ? $lang['blocks'][$file['name']] : nice_name( basename( $file['name'], '.php' ) ) );
			}
		}
	}

	if ( !isset( $blokai ) || count( $blokai ) < 1 ) {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $lang['admin']['panel_no']
			]
		);
	} else {
		$info 		= infoIcon($lang['system']['about_allow_pg']);
		
		if(! empty($url['r'])) {
			$sqlBlocks	= "SELECT * FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape( (int)$url['r'] ) . " LIMIT 1";
			$block      = mysql_query1($sqlBlocks);
		} else {
			$block = null;
		}

		$blockForm = [
			"Form"                                 	=> [
				"action"  => "",
				"method"  => "post",
				"enctype" => "",
				"id"      => "",
				"class"   => "",
				"name"    => "new_panel"
			],

			$lang['admin']['panel_title']     		=> [
				"type"  		=> "text",
				"placeholder" 	=> $lang['admin']['panel_new'],
				"name"  		=> "Panel",
				'value'			=> (! empty(input($block['panel'])) ? input($block['panel']) : '')
			],
		];

		if(empty($block)) {
			$blockForm[$lang['admin']['panel_name']]	= [
				"type"  => "select",
				"value" => $blokai,
				"name"  => "File"
			];
		}
		
		$blockForm += 	[
			$lang['admin']['panel_side']      		=> [
				"id"    	=> "lygiuojam",
				"type"  	=> "select",
				"value" 	=> [
					"L" => $lang['admin']['panel_left'],
					"R" => $lang['admin']['panel_right'],
					"C" => $lang['admin']['panel_center']
				],
				"name"  	=> "Align",
				'selected'	=> (! empty($block['align']) ? input($block['align']) : '')
			],

			$lang['admin']['panel_do_show']			=> [
				"id"    	=> "pasirinkimas",
				"type"  	=> "select",
				"value" 	=> [
					"Taip" => $lang['admin']['panel_do_all'],
					"Ne"   => $lang['admin']['panel_do_one']
				],
				"name"  	=> "rodyti",
				'class'		=> 'panel-show',
				'selected'	=> (! empty($block['rodyti']) ? input($block['rodyti']) : '')
			],
			
			$lang['admin']['panel_showtitle']		=> [
				"type"  	=> "switch",
				"value" 	=> 'Y',
				"name"  	=> "show",
				'form_line'	=> 'form-not-line',
				'checked'	=> (! empty($block['show']) ? true : false)
			],

			$lang['admin']['panel_showfor'] . $info	=> [
				"type"  	=> "select",
				"extra" 	=> "multiple",
				"value" 	=> $teises,
				"name"  	=> "Teises[]",
				"id"    	=> "punktai",
				'selected'	=> (! empty($block['teises']) ? unserialize($block['teises']) : '')
			],

			""                                     => [
				"type"  	=> "submit",
				"name"  	=> "action",
				'form_line'	=> 'form-not-line',
				"value" 	=> (! empty($block) ? $lang['system']['edit'] : $lang['admin']['panel_create'])
			]
		];

		$formClass = new Form($blockForm);
		$title = (! empty($block['panel']) ? $block['panel'] : $lang['admin']['panel_new']);
		lentele($title, $formClass->form());
	}

} elseif ( isset( $url['e'] ) && isnum( $url['e'] ) && $url['e'] > 0 ) { //Redaguojam panelės turinį
	$panel_id = (int)$url['e']; //Panelės ID

	if ( isset( $_POST['edit_content'] ) && !empty( $_POST['edit_content'] ) ) {
		$sql = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "panel` WHERE `id`=" . escape( $panel_id ) . " LIMIT 1";
		$sql = mysql_query1( $sql );

		if ( !is_writable( ROOT . 'blokai/' . $sql['file'] ) ) {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['panel_cantedit']
				]
			);
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

			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['post_updated']
				]
			);
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
						"name"    => "panel_txt"
					),

					$lang['admin']['panel_text'] => array(
						"type"  => "string",
						"value" => editor( 'spaw', 'standartinis', array( 'Turinys' => 'Bloko turinys' ), array( 'Turinys' => ( isset( $blokai_txt ) ) ? $blokai_txt : '' ) )
					),

					""                           => array(
						"type"  	=> "submit",
						"name"  	=> "edit_content",
						'form_line'	=> 'form-not-line',
						"value" 	=> $lang['admin']['edit']
					)
				);

				$formClass = new Form($panele);	
				lentele($sql['panel'], $formClass->form());
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> $lang['admin']['panel_cantedit']
					]
				);
			}
		}
	}
} else {
	
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
	<script type="text/javascript">
		//nestable
		$('.dd').nestable({
			maxDepth: 1
		});
		$('.dd').on('change', function () {
			var $this = $(this);
			var serializedData = JSON.stringify($($this).nestable('serialize')),
				data = {
					action: 'blocksOrder',
					action_functions: 'blocks',
					order: serializedData
				};

			$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
				if(response) {
					showNotification('success', response);
				}
			});
		});
	</script>
<?php } ?>

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
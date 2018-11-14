<?php
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: FDisk $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 375 $
 * @$Date: 2010-02-07 16:15:41 +0200 (Sun, 07 Feb 2010) $
 * */

unset( $text );

if ( !defined( "LEVEL" ) || LEVEL > 1 || !defined( "OK" ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] . '' );
}

include 'functions/functions.pages.php';

if(BUTTONS_BLOCK) {
	lentele($lang['admin']['meniu'], buttonsMenu(buttons('pages')));
}

$parent     = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `parent`='0' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
$parents[0] = "";
foreach ( $parent as $parent_row ) {
	$parents[$parent_row['id']] = $parent_row['pavadinimas'];
}
$lygiai = array_keys( $conf['level'] );


foreach ( $lygiai as $key ) {
	$teises[$key] = $conf['level'][$key]['pavadinimas'];
}
$teises[0] = $lang['admin']['for_guests'];


if ( isset( $_POST['Naujas_puslapis2'] ) && $_POST['Naujas_puslapis2'] == $lang['admin']['page_create'] ) {
	// Nurodote failo pavadinimą:
	//$failas = ROOT . "puslapiai/" . preg_replace( "/[^a-z0-9-]/", "_", strtolower( $_POST['pav'] ) ) . ".php";
	$failas = ROOT . "puslapiai/" . seo_url( basename( $_POST['pav'] ), '' ) . ".php";

	// Nurodote įrašą kuris bus faile kai jį sukurs:
	$tekstas = str_replace( array( '$', 'HTML' ), array( '&#36;', 'html' ), $_POST['Page'] );

	$irasas = '<?php
$text =
<<<HTML
' . $tekstas . '
HTML;
lentele($page_pavadinimas,$text);
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
		// Irasom faila
		$fp = fopen( $failas, "w+" );
		fwrite( $fp, $irasas );
		fclose( $fp );
		chmod( $failas, 0777 );
		
		redirect(
			url("?id,{$_GET['id']};a,{$_GET['a']};n,1"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['post_created']
			]
		);
	}
}

if ( isset( $url['d'] ) && isnum( $url['d'] ) && $url['d'] > 0 ) {
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`= " . escape( (int)$url['d'] ) . " LIMIT 1" );
	mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "page` SET `parent`='0' WHERE `parent`=" . escape( (int)$url['d'] ) . "" );
	
	delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
	
	redirect(
		url("?id," . $url['id'] . ";a," . $url['a']),
		"header",
		[
			'type'		=> 'success',
			'message' 	=> $lang['admin']['post_deleted']
		]
	);

} elseif ( isset( $url['n'] ) && $url['n'] == 1 ) {

	if ( isset( $_POST['Naujas_puslapis'] ) && $_POST['Naujas_puslapis'] == $lang['admin']['page_create'] ) {
		$psl    = input( $_POST['Page'] );
		$teises = serialize( ( isset( $_POST['Teises'] ) ? $_POST['Teises'] : 0 ) );
		
		if(! empty($_POST['external_page']) && $_POST['external_page'] === '1') {
			$file   = input($_POST['url']);
		} else {
			$file   = input($_POST['File']);
		}

		if ( empty( $psl ) || $psl == '' ) {
			$psl = basename( $file, ".php" );
		}

		if(! empty($_POST['show']) && $_POST['show'] === 'Y') {
			$show = input($_POST['show']);
		} else {
			$show = 'N';
		}

		$sql = "INSERT INTO `" . LENTELES_PRIESAGA . "page` (`pavadinimas`, `file`, `place`, `show`, `teises`,`parent`, `lang`) VALUES (" . escape( $psl ) . ", " . escape( $file ) . ", '0', " . escape( $show ) . ", " . escape( $teises ) . "," . escape( (int)$_POST['parent'] ) . ", " . escape( lang() ) . ")";
		mysql_query1($sql);
		
		delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
		
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['post_created']
			]
		);
	}

	$pageFiles = getFiles(ROOT . 'puslapiai/', null, 'puslapiai/');
	//extensions
	$pageFiles = applyFilters('cmsPages', $pageFiles);

	foreach ($pageFiles as $file) {
		if ($file['type'] == 'file') {
			$fileName 	= $file['name'];
			$fileTitle	= (isset($lang['pages'][$file['name']]) ? $lang['pages'][$file['name']] : nice_name(basename($file['name'], '.php')));

			if ($file['name'] !== 'klaida.php' && ! isset($conf['puslapiai'][$fileName]['id'])) {
				$pages[$fileName] = $fileTitle;
			}
		}
	}

	if (! isset($pages) || count($pages) < 1) {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $lang['admin']['page_nounused']
			]
		);
	} else {
		$info = infoIcon($lang['system']['about_allow_pg']);
		
		$pageForm = [
			"Form"									=> [
				"action" 	=> "",
				"method" 	=> "post",
				"name" 		=> "new_panel"
			],

			$lang['admin']['page_name']				=> [
				"type"			=> "text",
				"placeholder"	=> $lang['admin']['page_name'],
				"name"  		=> "Page"
			],

			$lang['admin']['page_url']				=> [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'external_page',
				'id'		=> 'external_page',
				'form_line'	=> 'form-not-line',
				'checked'	=> false
			],

			$lang['admin']['page_file']				=> [
				"type"		=> "select",
				"value"		=> $pages,
				"name"		=> "File",
				"id"		=> "failas",
				"row_class"	=> "page-file",
			],

			$lang['admin']['page_link']				=> [
				"type"  		=> "text",
				"name"  		=> "url",
				"id"    		=> "url",
				"row_class"		=> "hidden page-link",
				'placeholder'	=> 'http://'
			],

			"Sub"									=> [
				"type"  => "select",
				"value" => $parents,
				"name"  => "parent"
			],

			$lang['admin']['page_show']				=> [
				"type"  	=> "switch",
				"value" 	=> 'Y',
				"name"  	=> "show",
				'form_line'	=> 'form-not-line',
				'checked'	=> false
			],

			$lang['admin']['page_showfor'] . $info	=> [
				"type"  => "select",
				"extra" => "multiple",
				"value" => $teises,
				"name"  => "Teises[]",
				"id"    => "punktai"
			],
			""										=> [
				"type" 		=> "submit",
				"name" 		=> "Naujas_puslapis",
				'form_line'	=> 'form-not-line',
				"value" 	=> $lang['admin']['page_create']
			]
		];
		
		$formClass = new Form($pageForm);
		lentele($lang['admin']['page_select'], $formClass->form());

		?>
			<script>
				var pageLinkEl = document.querySelector('.page-link');
				var pageFileEl = document.querySelector('.page-file');
				var externalEl = document.querySelector('input[name="external_page"]');

				externalEl.addEventListener('change', function(e) {
					var input = e.currentTarget;

					if(input.checked) {
						pageLinkEl.classList.remove('hidden');
						pageFileEl.classList.add('hidden');
					} else {
						pageLinkEl.classList.add('hidden');
						pageFileEl.classList.remove('hidden');
					}
				});

			</script>
		<?php
	}
} elseif (isset($url['n']) && $url['n'] == 2) {
	$pageForm = [
		"Form"							=> [
			"action"  => "",
			"method"  => "post",
			"name"    => "new_page2"
		],

		$lang['admin']['page_filename'] => [
			"type"  		=> "text",
			"placeholder" 	=> $lang['admin']['page_name'],
			"name"  		=> "pav"
		],

		$lang['admin']['page_text']     => [
			"type"  => "string",
			"value" => editor('spaw', 'standartinis', ['Page' => 'Page'], FALSE),
			"name"  => "Page",
			"rows"  => "8"
		],
		""                                   => [
			"type"  	=> "submit",
			"name"  	=> "Naujas_puslapis2",
			'form_line'	=> 'form-not-line',
			"value" 	=> $lang['admin']['page_create']
		]
	];

	$formClass = new Form($pageForm);
	lentele($lang['admin']['page_create'], $formClass->form());
} elseif (isset($url['n']) && $url['n'] == 3) {
	//tree
	$treeData = '';
	$res   = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang`=" . escape( lang() ) . " ORDER BY `place` ASC" );
	foreach ($res as $row) {
		if (teises($row['teises'], $_SESSION[SLAPTAS]['level'])) {
			$treeData[$row['parent']][] = $row;
		}
	}
	$tree = build_tree($treeData);
	$text = '<ul id="treemenu">' . $tree . '</ul>';

	lentele($lang['system']['tree'], $text);
}
//puslapiai redagavimas
elseif (isset($url['r']) && isnum($url['r']) && $url['r'] > 0) {
	if (isset($_POST['Redaguoti_psl']) && $_POST['Redaguoti_psl'] == $lang['admin']['edit'] ) {
		$psl    = input($_POST['pslp']);
		$teises = serialize((isset($_POST['Teises']) ? $_POST['Teises'] : 0));
		
		if (empty($psl) || $psl == '') {
			$psl = $lang['admin']['page_text'];
		}
		
		if(! empty($_POST['show']) && $_POST['show'] === 'Y') {
			$show = input($_POST['show']);
		} else {
			$show = 'N';
		}

		$sql = "UPDATE `" . LENTELES_PRIESAGA . "page` SET `pavadinimas`=" . escape( $psl ) . ", `show`=" . escape( $show ) . ",`teises`=" . escape( $teises ) . ",`parent`= " . escape( (int)$_POST['parent'] ) . "  WHERE `id`=" . escape( (int)$url['r'] );
		if(mysql_query1($sql)) {
			delete_cache( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );

			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['post_updated']
				]
			);
		} else {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['post_not_updated']
				]
			);
		}				
		
	} else {
		$sql      = "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( (int)$url['r'] ) . " LIMIT 1";
		$sql      = mysql_query1($sql);
		$selected = unserialize($sql['teises']);

		unset($parents[$sql['id']]);
		
		$info = infoIcon($lang['system']['about_allow_pg']);
		
		$psl = [
			"Form"                                 => [
				"action"  => "",
				"method"  => "post",
				"name"    => "new_psl"
			],

			$lang['admin']['page_name']            => [
				"type"  => "text",
				"value" => $sql['pavadinimas'],
				"name"  => "pslp",
				"class" => "input"
			],

			$lang['admin']['page_show']				=> [
				"type"  	=> "switch",
				"value" 	=> 'Y',
				"name"  	=> "show",
				'form_line'	=> 'form-not-line',
				'checked'	=> (input($sql['show']) === 'Y')
			],

			"Sub"                                  => [
				"type"     => "select",
				"value"    => $parents,
				"selected" => input($sql['parent']),
				"name"     => "parent"
			],

			$lang['admin']['page_showfor'] . $info => [
				"type"  => "select",
				"extra" => "multiple",
				"value" => $teises,
				"name"  => "Teises[]",
				"id"    => "punktai"
			],

			""                                     => [
				"type"  	=> "submit",
				"name"  	=> "Redaguoti_psl",
				'form_line'	=> 'form-not-line',
				"value" 	=> $lang['admin']['edit']
			]
		];


		if (! empty($selected)) {
			$psl[$lang['admin']['page_showfor'] . $info]['selected'] = $selected;
		}
		
		$formClass = new Form($psl);
		lentele($sql['pavadinimas'], $formClass->form());
	}
}

//Redaguojam puslapiai turini
elseif ( isset( $url['e'] ) && isnum( $url['e'] ) && $url['e'] > 0 ) {
	$psl_id = (int)$url['e']; //puslapiai ID

	if ( isset( $_POST['Redaguoti_txt'] ) && $_POST['Redaguoti_txt'] == $lang['admin']['edit'] ) {
		$sql     	= "SELECT `file`,`pavadinimas` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( $psl_id ) . " LIMIT 1";
		$sql     	= mysql_query1($sql);
		$tekstas 	= str_replace(['$', 'HTML'], ['&#36;', 'html'], $_POST['Page']);
		$irasas 	= '<?php
$text =
<<<HTML
' . stripslashes( $tekstas ) . '
HTML;
lentele($page_pavadinimas,$text);
?>';

		// Irasom faila
		$fp = fopen(ROOT . 'puslapiai/' . $sql['file'], "w+");
		fwrite($fp, $irasas);
		fclose($fp);
		chmod(ROOT . 'puslapiai/' . $sql['file'], 0777);
	} else {

		$sql = "SELECT `id`, `pavadinimas`, `file` FROM `" . LENTELES_PRIESAGA . "page` WHERE `id`=" . escape( $psl_id ) . " LIMIT 1";
		$sql = mysql_query1( $sql );
		//tikrinam failo struktura

		$lines      = file( ROOT . 'puslapiai/' . $sql['file'] );
		$resultatai = [];

		$zodiz = '$text =';
		for ($i = 0; $i < count($lines); $i++) {
			$temp = trim($lines[$i]);
			if (substr_count($temp, $zodiz) > 0) {
				$resultatai[] = $temp;
				$nr           = ($i + 1);
			}
		}

		//tikrinimo pabaiga
		if (isset($nr) && $nr == 2) {
			$page_pavadinimas = $sql['pavadinimas'];

			include ROOT . 'puslapiai/' . $sql['file'];

			$puslapio_txt = $text;

			$puslapis = [
				"Form"                      => [
					"action"  => "",
					"method"  => "post",
					"name"    => "psl_txt"
				],

				$lang['admin']['page_text'] => [
					"type"  => "string",
					"value" => editor('spaw', 'standartinis', ['Page' => 'Page'], ['Page' => $puslapio_txt]),
					"name"  => "Turinys",
					"rows"  => "10"
				],

				""                          => [
					"type"  	=> "submit",
					"name"  	=> "Redaguoti_txt",
					'form_line'	=> 'form-not-line',
					"value" 	=> $lang['admin']['edit']
				]
			];

			$formClass = new Form($puslapis);
			lentele($sql['pavadinimas'], $formClass->form());
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['page_cantedit']
				]
			);
		}
	}
} else { // Pages list VIEW
	$sqlPages = mysql_query1( "SELECT * from `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'Y' AND `lang` = " . escape( lang() ) . " order by place" );

	foreach ($sqlPages as $row) {
		$data[$row['parent']][] = $row;
	}

	$li      	= ! empty($data) ? build_menu_admin($data) : '';
	$pageMenu 	= '<div class="dd nestable-with-handle">' . $li . '</div>';

	$sqlOtherPages = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`= 'N' AND `lang` = " . escape( lang() ) . " order by id" );
	
	$otherPages = '<ol class="dd-list">';
	if (! empty($sqlOtherPages)) {
		foreach ($sqlOtherPages as $otherPage) {
			$otherPages .= '<li class="dd-handle">
			<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';d,' . $otherPage['id'] ) . '" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')">
			<img src="' . ROOT . 'images/icons/cross.png" title="' . $lang['admin']['delete'] . '" />
			</a>
			<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';r,' . $otherPage['id'] ) . '" >
			<img src="' . ROOT . 'images/icons/wrench.png" title="' . $lang['admin']['edit'] . '" />
			</a>
			<a href="' . url( '?id,' . $url['id'] . ';a,' . $url['a'] . ';e,' . $otherPage['id'] ) . '">
			<img src="' . ROOT . 'images/icons/pencil.png" title="' . $lang['admin']['page_text'] . '"/>
			</a>
			' . $otherPage['pavadinimas'] . '
			</li>';
		}
	}
	$otherPages .= '</ol>';
	?>
		<div class="row clearfix">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<?php lentele($lang['admin']['page_navigation'], $pageMenu); ?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<?php lentele($lang['admin']['page_other'], $otherPages); ?>
			</div>
		</div>
	<?php
}
?>
<script type="text/javascript">
	//nestable
	$('.dd').nestable();
	$('.dd').on('change', function () {
        var $this = $(this);
		var serializedData = JSON.stringify($($this).nestable('serialize')),
			data = {
				action: 'pagesOrder',
				action_functions: 'pages',
				order: serializedData
			};

		$.post("<?php echo url( "?id,999;a,ajax;" ); ?>", data, function(response) {
			if(response) {
				showNotification('success', response);
			}
		});
    });
</script>
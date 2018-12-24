<?php

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if ( count( $_GET ) < 3 ) {
	$_GET['v'] = 5;
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
	lentele( getLangText('admin', 'nuorodos'),  buttonsMenu(buttons('links')));
}

if (empty($_GET['v'])) {
	$_GET['v'] = 0;
}
include_once config('functions', 'dir') . 'functions.categories.php';
category( "nuorodos" );

$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos' AND `path`=0 AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC" );
if ( sizeof( $sql ) > 0 ) {
	$categories = cat( 'nuorodos', 0 );
}
$categories[0] = "--";
$sql2           = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` WHERE `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas` DESC" );
if ( sizeof( $sql2 ) > 0 ) {
	foreach ( $sql2 as $row2 ) {
		$nuorodos[$row2['id']] = $row2['pavadinimas'];
	}
}
//delete link
if ( isset( $_GET['m'] ) ) {
	$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE `id`=" . escape( $_GET['m'] ) . ";";
	if (mysql_query1($deleteQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'links_Deleted')
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}
}
//delete few links
if (isset($_POST['links_delete'])) {
	foreach ($_POST['links_delete'] as $a => $b) {
		$trinti[] = escape( $b );
	}

	$sqlDeleteFew = "DELETE FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE `id` IN(" . implode( ", ", $trinti ) . ")";
	
	if(mysql_query1($sqlDeleteFew)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'posts_deleted')
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> getLangText('system', 'error')
			]
		);
	}
//activate post
} elseif (isset($_GET['c'])) {
	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET active='TAIP' WHERE `id`=" . escape( $_GET['c'] ) . ";";

	if (mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'links_activated')
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}
}

if ( $_GET['v'] == 1 ) {
///FILTRAVIMAS
	$viso = kiek( "nuorodos", "WHERE `active`='NE' AND `lang` = " . escape(lang()));
	$info = [];
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND active='NE' ORDER BY id LIMIT {$p},{$limit}";

	if ($links = mysql_query1($sqlQuery)) {
		//FILTRAVIMAS
		$formData = [
			'url'	=> getLangText('admin', 'link'),
			'date'	=> getLangText('admin', 'links_date'),
			'apie'	=> getLangText('admin', 'links_about'),
		];

		$info[] = tableFilter($formData, $_POST, '#links');
		//FILTRAVIMAS - END
		foreach ($links as $link) {
			$info[]   = [
				"#"								=> '<input type="checkbox" value="' . $link['id'] . '" name="articles_delete[]" class="filled-in" id="links-delete-' . $link['id'] . '"><label for="links-delete-' . $link['id'] . '"></label>',
				getLangText('admin', 'link')        	=> '<a href="' . $link['url'] . '" target="_blank">' . $link['pavadinimas'] . '</a>',
				getLangText('admin', 'links_date')  	=> date( 'Y-m-d', $link['date'] ),
				getLangText('admin', 'links_about') 	=> "<span style='cursor:pointer;' data-toggle='tooltip' title='" . trimlink(strip_tags($link['apie']), 300) . "'>" . trimlink(strip_tags($link['apie']), 55) . "</span>",
				getLangText('admin', 'action')      	=> "<a href='" . url( "?id,{$url['id']};a,{$url['a']};c," . $link['id'] ) . "' data-toggle='tooltip' title='" . getLangText('admin',  'acept') . "'><img src='" . ROOT . "core/assets/images/icons/tick_circle.png' alt='a' border='0'></a>
				<a href='" . url( "?id,{$url['id']};a,{$url['a']};m," . $link['id'] ) . "' data-toggle='tooltip' title='" . getLangText('admin',  'delete') . "' onClick=\"return confirm('" . getLangText('admin', 'delete') . "?')\"><img src='" . ROOT . "core/assets/images/icons/cross.png'></a> 
				<a href='" . url( "?id,{$url['id']};a,{$url['a']};r," . $link['id'] ) . "' data-toggle='tooltip' title='" . getLangText('admin',  'edit') . "'><img src='" . ROOT . "core/assets/images/icons/pencil.png'></a>"
			];
		}

		$tableClass  = new Table($info);
		$content = '<form id="links" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . getLangText('system', 'delete') . '</button></form>';
		lentele( getLangText('admin', 'links_unpublished'), $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( getLangText('system', 'warning'), getLangText('system', 'no_items') );
	}
} elseif ( $_GET['v'] == 4 ) {
///FILTRAVIMAS
	$viso = kiek( "nuorodos", "WHERE `active`='TAIP' AND `lang` = " . escape(lang()));
	$info = [];
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` WHERE `lang` = " . escape(lang()) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND active='TAIP' ORDER BY id LIMIT {$p},{$limit}";

	if ($links = mysql_query1($sqlQuery)) {

		//FILTRAVIMAS
		$formData = [
			'url'	=> getLangText('admin', 'link'),
			'date'	=> getLangText('admin', 'links_date'),
			'apie'	=> getLangText('admin', 'links_about'),
		];

		$info[] = tableFilter($formData, $_POST, '#links');
		//FILTRAVIMAS - END
		foreach ($links as $link) {
			$info[]   = [
				"#"								=> '<input type="checkbox" value="' . $link['id'] . '" name="articles_delete[]" class="filled-in" id="links-delete-' . $link['id'] . '"><label for="links-delete-' . $link['id'] . '"></label>',
				getLangText('admin', 'link')        	=> '<a href="' . $link['url'] . '" target="_blank">' . $link['pavadinimas'] . '</a>',
				getLangText('admin', 'links_date')  	=> date( 'Y-m-d', $link['date'] ),
				getLangText('admin', 'links_about') 	=> "<span style='cursor:pointer;' data-toggle='tooltip' title='" . trimlink(strip_tags($link['apie']), 300) . "'>" . trimlink(strip_tags($link['apie']), 55) . "</span>",
				getLangText('admin', 'action')      	=> "<a href='" . url( "?id,{$url['id']};a,{$url['a']};m," . $link['id'] ) . "' data-toggle='tooltip' title='" . getLangText('admin',  'delete') . "' onClick=\"return confirm('" . getLangText('admin', 'delete') . "?')\"><img src='" . ROOT . "core/assets/images/icons/cross.png'></a> 
				<a href='" . url( "?id,{$url['id']};a,{$url['a']};r," . $link['id'] ) . "' data-toggle='tooltip' title='" . getLangText('admin',  'edit') . "'><img src='" . ROOT . "core/assets/images/icons/pencil.png'></a>"
			];
		}

		$tableClass  = new Table($info);
		$content = '<form id="links" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . getLangText('system', 'delete') . '</button></form>';
		lentele( getLangText('admin', 'nuorodos'), $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( getLangText('system', 'warning'), getLangText('system', 'no_items') );
	}
} elseif ($_GET['v'] == 5 || isset($_GET['r'])) {

	if (isset($_POST['action']) && ! empty($_POST['action']) ) {
		// Nustatom kintamuosius
		$urlLink		= strip_tags($_POST['url']);
		$apie        	= $_POST['Aprasymas'];
		$pavadinimas 	= strip_tags( $_POST['name'] );
		$active			= (isset($_POST['active']) && $_POST['active'] === '1' ? 'TAIP' : 'NE');
		$cat  			= isset($_POST['cat']) ? ceil((int)$_POST['cat']) : 0;
		$pattern 		= "#([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si";
		//url filter
		if (! preg_match($pattern, $urlLink)) {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> getLangText('admin', 'links_bad')
				]
			);
		} else {
			// Create post
			if($_POST['action'] == getLangText('admin', 'links_create')) {
	
				$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "nuorodos` (`cat` , `url` ,`pavadinimas` , `nick` , `date` , `apie`, `active`) VALUES (" . escape( $cat ) . ", " . escape( $urlLink ) . ", " . escape( $pavadinimas ) . ", " . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $apie ) . ", " . escape( $active ) . ");";

				if (mysql_query1($insertQuery)) {
					redirect(
						url("?id," . $url['id'] . ";a," . $url['a']),
						"header",
						[
							'type'		=> 'success',
							'message' 	=> getLangText('admin', 'links_created')
						]
					);
				} else {
					notifyMsg(
						[
							'type'		=> 'error',
							'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
						]
					);
				}
			
			// Edit post
			} elseif($_POST['action'] == getLangText('admin', 'links_edit')) {
		
				$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET
				`pavadinimas` = " . escape($pavadinimas) . ",
				`apie` = " . escape($apie) . ",
				`active` = " . escape($active) . ",
				`url` = " . escape($urlLink) . ",
				`cat` = " . escape($cat) . "
				WHERE `id`=" . escape($_POST['nuorodos_id']) . ";";
			
				if(mysql_query1($updateQuery)) {
					redirect(
						url("?id," . $url['id'] . ";a," . $url['a']),
						"header",
						[
							'type'		=> 'success',
							'message' 	=> getLangText('admin', 'links_updated')
						]
					);
				} else {
					notifyMsg(
						[
							'type'		=> 'error',
							'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
						]
					);
				}
			}
		}
	}

	$extra = null;
	if (isset($_GET['r'])) {
		$selectQuery 	= "SELECT * FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE id='" . escape($_GET['r']) . "' LIMIT 1";
		$extra			= mysql_query1($selectQuery);
	}

	$editOrCreate 	= (isset($extra) ? getLangText('admin', 'links_edit') : getLangText('admin', 'links_create'));

	$nuorodos = [
		"Form"							=> [
			"action" 	=> "", 
			"method" 	=> "post", 
			"name" 		=> "Submit_link"
		],

		getLangText('system', 'category')  	=> [
			"type" 		=> "select", 
			"value" 	=> $categories, 
			"name" 		=> "cat",
			"selected" => (isset($extra['cat']) ? input($extra['cat']) : '0')
		],

		getLangText('admin', 'links_title')	=> [
			"type" 	=> "text", 
			"value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '', 
			"name" 	=> "name"
		],

		"Url"							=> [
			"type" 			=> "text", 
			"placeholder" 	=> "http://", 
			"name" 			=> "url",
			'value'			=> (isset($extra['url'])) ? input($extra['url']) : ''
		], 

		getLangText('admin', 'links_about') 	=> [
			"type" => "string", 
			"value" => editor('jquery', 'standartinis', 'Aprasymas', (isset($extra['apie'])) ? $extra['apie'] : '') 
		],

		getLangText('admin', 'links_active')	=> [
			'type'		=> 'switch',
			'value'		=> 1,
			'name'		=> 'active',
			'id'		=> 'active',
			'form_line'	=> 'form-not-line',
			'checked' 	=> (! empty($extra['active']) && $extra['active'] == 'TAIP' ? true : false),
		],

		""								=> [
			"type" 		=> "submit", 
			"name" 		=> "action", 
			'form_line'	=> 'form-not-line',
			"value" 	=> $editOrCreate
		]
	];

	if(! empty($extra)) {
		$nuorodos['nuorodos_id'] = [
			"type" 	=> "hidden", 
			"name" 	=> "nuorodos_id", 
			"value" => $extra['id']
		];
	}

	$formClass = new Form($nuorodos);
	lentele($editOrCreate, $formClass->form());
}

unset($info);
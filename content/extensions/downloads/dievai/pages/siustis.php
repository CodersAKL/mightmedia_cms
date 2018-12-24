<?php

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
	lentele($lang['admin']['siustis'], buttonsMenu(buttons('downloads')));
}

if ( empty( $url['s'] ) ) {
	$url['s'] = 0;
}
if ( empty( $url['v'] ) ) {
	$url['v'] = 0;
}

include_once config('functions', 'dir') . 'functions.categories.php';
category( "siuntiniai", TRUE );
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='siuntiniai' AND `path`=0 ORDER BY `id` DESC" );
if ( sizeof( $sql ) > 0 ) {
	$categories = cat( 'siuntiniai', 0 );
}
$categories[0] = "--";
//item activate
if (isset($_GET['p'])) {
	$activateQuery = "UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET rodoma='TAIP' WHERE `id`=" . escape($_GET['p']) . ";";

	if (mysql_query1($activateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['download_activated']
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
if (isset($url['t'])) {
	if (isset($url['t'])) {
		$delId = (int)$url['t'];
	}

	$selectQuery = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` = " . escape($delId) . " LIMIT 1";
	
	if($downloadItem = mysql_query1($selectQuery)) {
		if (isset($downloadItem['file'] ) && ! empty($downloadItem['file'])) {
			@unlink(ROOT . "contnent/uploads/" . $downloadItem['file']);
		}
	}

	$deleteQuery = "DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE id=" . escape($delId) . " LIMIT 1";

	if (mysql_query1($deleteQuery)) {
		//comments delete
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='content/pages/siustis' AND kid=" . escape($delId));

		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ';v,7'),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['download_deleted']
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
// trinam siuntinius
if (isset( $_POST['downloads_delete'])) {
	foreach ($_POST['downloads_delete'] as $a => $b ) {
		$trinti[] = escape($b);
	}
	
	$sqlSelectDeleteFew = "SELECT `file` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` IN (" . implode(",", $trinti) . ")";
	
	if($sql = mysql_query1($sqlSelectDeleteFew)) {
		foreach ( $sql as $row ) {
			if ( isset( $row['file'] ) && !empty( $row['file'] ) ) {
				@unlink( ROOT . "contnent/uploads/" . $row['file'] );
			}
		}
		//item delete
		$sqlDeleteFew = "DELETE FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID` IN (" . implode(",", $trinti) . ")";
		if(mysql_query1($sqlDeleteFew)) {
			//comments delete
			mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='content/pages/siustis' AND kid IN (" . implode(",", $trinti) . ")");
				
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a'] . ';v,7'),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['posts_deleted']
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
		
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}

//Siuntinio redagavimas
} elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}

	$selectQuery = "SELECT * FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `id`=" . escape($redaguoti) . " LIMIT 1";
	$extra = mysql_query1($selectQuery);

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$apie        	= $_POST['Aprasymas'];
	$pavadinimas 	= strip_tags( $_POST['Pavadinimas'] );
	$category 	 = (int)$_POST['cat'];
	$file        	= strip_tags( $_POST['failas2'] );
	$id				= ceil( (int)$_POST['news_id'] );
	$rodoma			= (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');

	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "siuntiniai` SET
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`categorija` = " . escape( $category ) . ",
	`apie` = " . escape( $apie ) . ",
	`file` = " . escape( $file ) . ",
	`rodoma` = " . escape( $rodoma ) . "
	WHERE `id`=" . escape( $id ) . ";";

	if(mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['download_updated']
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

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['download_create'] ) {

	if ( isset( $_FILES['failas'] ) && !empty( $_FILES['failas'] ) ) {
		if ( is_uploaded_file( $_FILES['failas']['tmp_name'] ) ) {
			upload( "failas", ["jpg", "bmp", "png", "psd", "zip", "rar", "mrc", "dll", "doc", "ppt", "pdf", "bmp"], ROOT . "contnent/uploads/" );
		}
	}

	if ( isset( $_POST['failas2'] ) && !empty( $_POST['failas2'] ) ) {
		$rodoma			= (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');
		$insertQuery 	= "INSERT INTO `" . LENTELES_PRIESAGA . "siuntiniai` (`pavadinimas`,`file`,`apie`,`autorius`,`data`,`categorija`,`rodoma`) VALUES (" . escape( $_POST['Pavadinimas'] ) . "," . escape( $_POST['failas2'] ) . ", " . escape( $_POST['Aprasymas'] ) . "," . escape(getSession('id')) . ", '" . time() . "', " . escape( $_POST['cat'] ) . ", " . escape( $rodoma ) . ")";

		if (mysql_query1($insertQuery)) {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> $lang['admin']['download_created']
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

	unset( $_FILES['failas'], $filename, $result, $rodoma );
}

if ( isset( $_GET['v'] ) ) {
	//downloads list
	if ( $_GET['v'] == 7 ) {
		
		///FILTRAVIMAS
		$viso = kiek("siuntiniai", "WHERE `rodoma`='TAIP' AND `lang` = " . escape(lang()));
		$selectQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? "AND `data` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='TAIP' ORDER BY ID LIMIT {$p},{$limit}";
		
		if($downloads = mysql_query1($selectQuery)) {
			//FILTRAVIMAS
			$formData = [
				'pavadinimas'	=> $lang['download']['title'],
				'data'			=> $lang['download']['date'],
				'apie'			=> $lang['download']['about'],
			];

			$info[] = tableFilter($formData, $_POST, '#downloads');
			//FILTRAVIMAS - END

			foreach ($downloads as $download) {
				$info[] = [
					"#"							=> '<input type="checkbox" value="' . $download['ID'] . '" name="downloads_delete[]" class="filled-in" id="downloads-delete-' . $download['ID'] . '"><label for="downloads-delete-' . $download['ID'] . '"></label>',
					$lang['download']['title'] 	=> input($download['pavadinimas']),
					$lang['download']['date']  	=> date('Y-m-d', $download['data']),
					$lang['download']['about'] 	=> trimlink(strip_tags($download['apie']), 55),
					$lang['admin']['action']   	=> "<a href='" . url( "?id,{$url['id']};a,{$url['a']};t," . $download['ID'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "core/assets/images/icons/cross.png\"></a> <a href='" . url( "?id,{$url['id']};a,{$url['a']};h," . $download['ID'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "core/assets/images/icons/pencil.png'></a>"
				];
			}

			$tableClass = new Table($info);
			$content = '<form id="downloads" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';
			lentele( $lang['admin']['edit'], $content);
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( $lang['system']['pages'], pages( $p, $limit, $viso, 10 ) );
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['no_items']
				]
			);
		}
		

	} elseif ( $_GET['v'] == 1 || isset( $_GET['h'] ) ) {
		if ( ! isset( $nocat ) ) {
			if ( !isset( $_POST['tipas'] ) && !isset( $extra ) ) {
				$type = [
					1 => $lang['admin']['download_uploaded'],
					2 => $lang['admin']['link']
				];

				$tipas = [
					"Form"                                 => [
						"action" => url( "?id,{$url['id']};a,{$url['a']};v,1" ),
						"method" => "post",
						"name"   => "type"
					],

					$lang['admin']['download_type']   => [
						"type"  => "select",
						"value" => $type,
						"name"  => "tipas"
					],

					$lang['admin']['download_select'] => [
						"type"  	=> "submit",
						"name"  	=> "action",
						'form_line'	=> 'form-not-line',
						"value" 	=> $lang['admin']['download_select']
					]
				];

				$formClass = new Form($tipas);	
				lentele($lang['admin']['download_Create'], $formClass->form());
			}

			if ( isset( $_POST['tipas'] ) || isset( $extra ) ) {
				$editOrCreate = (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['download_create'];

				$downloadForm = [
					"Form"	=> [
						"enctype" => "multipart/form-data",
						"action"  => url( "?id," . $url['id'] . ";a," . $url['a']),
						"method"  => "post",
						"name"    => "action"
					],
				];

				if((! isset($extra ) && @$_POST['tipas'] != 2 )) {
					$downloadForm[$lang['admin']['download_file']] = [
						"name"  => "failas",
						"type"  => 'file',
						"value" => "",
					];
				} elseif(isset($extra) || $_POST['tipas'] == 2) {
					$downloadForm[$lang['admin']['download_fileurl']] = [
						"name"  => "failas2",
						"type"  => 'text',
						"value" => (isset($extra['file'])) ? input($extra['file']) : '',
					];	
				}

				$downloadForm += [
					$lang['admin']['download_download']	=> [
						"type"  => "text",
						"value" => (isset($extra['pavadinimas'])) ? input($extra['pavadinimas']) : '',
						"name"  => "Pavadinimas"
					],

					$lang['system']['category']			=> [
						"type"     => "select",
						"value"    => $categories,
						"name"     => "cat",
						"selected" => (isset($extra['categorija']) ? input($extra['categorija']) : '0')
					],

					$lang['admin']['download_about']	=> [
						"type"  => "string",
						"value" => editor('jquery', 'mini', 'Aprasymas', (isset($extra['apie'])) ? $extra['apie'] : '')
					],

					$lang['admin']['article_shown'] 	=> [
						'type'		=> 'switch',
						'value'		=> 1,
						'name'		=> 'rodoma',
						'id'		=> 'rodoma',
						'form_line'	=> 'form-not-line',
						'checked' 	=> (! empty($extra['rodoma']) && $extra['rodoma'] == 'TAIP' ? true : false),
					],

					""									=> [
						"type"  	=> "submit",
						"name"  	=> "action",
						'form_line'	=> 'form-not-line',
						"value" 	=> $editOrCreate
					]
				];

				if (isset($extra)) {
					$downloadForm['news_id'] = [
						"type"  =>
						"hidden",
						"name"  => "news_id",
						"value" => (isset($extra) ? input($extra['ID']) : '' )
					];
				}

				$formClass = new Form($downloadForm);	
				lentele($lang['admin']['download_create'], $formClass->form());
			}
		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['nocategories']
				]
			);
		}
	//not activated list
	} elseif ( $_GET['v'] == 6 ) {
		///FILTRAVIMAS
		$viso = kiek( "siuntiniai", "WHERE `rodoma`='NE' AND `lang` = " . escape(lang()) . "" );
		$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? " AND `date` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY ID LIMIT {$p},{$limit}";
		//
		if ($downloadsNotActive = mysql_query1($sqlQuery)) {
			
			//FILTRAVIMAS
			$formData = [
				'pavadinimas'	=> $lang['download']['title'],
				'data'			=> $lang['download']['date'],
				'apie'			=> $lang['download']['about'],
			];

			$info[] = tableFilter($formData, $_POST, '#downloads');
			//FILTRAVIMAS - END
			foreach ($downloadsNotActive as $download) {
				$info[] = [
					"#"							=> '<input type="checkbox" value="' . $download['ID'] . '" name="downloads_delete[]" class="filled-in" id="downloads-delete-' . $download['ID'] . '"><label for="downloads-delete-' . $download['ID'] . '"></label>',
					$lang['download']['title'] 	=> input($download['pavadinimas']),
					$lang['download']['date']  	=> date('Y-m-d', $download['data']),
					$lang['download']['about'] 	=> trimlink(strip_tags($download['apie']), 55),
					$lang['admin']['action']   	=> "<a href='" . url( "?id,{$url['id']};a,{$url['a']};p," . $download['ID'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "core/assets/images/icons/tick_circle.png' alt='a'></a><a href='" . url( "?id,{$url['id']};a,{$url['a']};t," . $download['ID'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "core/assets/images/icons/cross.png\"></a> <a href='" . url( "?id,{$url['id']};a,{$url['a']};h," . $download['ID'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "core/assets/images/icons/pencil.png'></a>"
				];
			}

			$tableClass  = new Table($info);
			$content = '<form id="downloads" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';
			lentele( $lang['admin']['download_unpublished'], $content);
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( $lang['system']['pages'], pages( $p, $limit, $viso, 10 ) );
			}

		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['system']['no_items']
				]
			);
		}
	}
}

unset( $sql, $extra, $row );
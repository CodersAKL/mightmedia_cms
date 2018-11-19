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
	lentele( $lang['admin']['nuorodos'],  buttonsMenu(buttons('links')));
}

if ( empty( $_GET['v'] ) ) {
	$_GET['v'] = 0;
}
include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "nuorodos" );

$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='nuorodos' AND `path`=0 AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC" );
if ( sizeof( $sql ) > 0 ) {
	$kategorijos = cat( 'nuorodos', 0 );
}
$kategorijos[0] = "--";
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
				'message' 	=> $lang['admin']['links_Deleted']
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
				'message' 	=> $lang['admin']['posts_deleted']
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> $lang['system']['error']
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
				'message' 	=> $lang['admin']['links_activated']
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
	$viso = kiek( "nuorodos", "WHERE `active`='NE' AND `lang` = " . escape( lang() ) . "" );
	$info = [];
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND active='NE' ORDER BY id LIMIT {$p},{$limit}";

	if ($q = mysql_query1($sqlQuery)) {

		if ( isset( $_POST['pavadinimas'] ) && $_POST['date'] && $_POST['apie'] ) {
			$val = array( $_POST['pavadinimas'], $_POST['date'], $_POST['apie'] );
		} else {
			$val = array( "", "", "" );
		}

		$info[] = array( 
			"#"                           => "<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('linksch');\" />",
			$lang['admin']['link']        => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
			$lang['admin']['links_date']  => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"date\" />",
			$lang['admin']['links_about'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"apie\" />",
			$lang['admin']['action']      => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
		);
		//FILTRAVIMAS

		foreach ($q as $sql) {
			$nariui_l = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='{$sql['nick']}' LIMIT 1" );
			$info[]   = array(
				"#"                           => "<input type=\"checkbox\" value=\"{$sql['id']}\" name=\"links_delete[]\" />",
				$lang['admin']['link']        => '<a href="' . $sql['url'] . '" title="' . $lang['admin']['links_author'] . ': <b>' . $nariui_l['nick'] . '</b><br/>' . $lang['admin']['links_date'] . ': <b>' . date( 'Y-m-d H:i:s ', $sql['date'] ) . ' - ' . kada( date( 'Y-m-d H:i:s ', $sql['date'] ) ) . '</b>" target="_blank">' . $sql['pavadinimas'] . '</a>',
				$lang['admin']['links_date']  => date( 'Y-m-d', $sql['date'] ),
				$lang['admin']['links_about'] => trimlink( strip_tags( $sql['apie'] ), 55 ),
				$lang['admin']['action']      => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};c," . $sql['id'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' alt='a' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};m," . $sql['id'] ) . "'title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};r," . $sql['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			);
		}

		$tableClass = new Table($info);
		$content = "<form id=\"linksch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele( $lang['admin']['links_unpublished'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_items'] );
	}
} elseif ( $_GET['v'] == 4 ) {
///FILTRAVIMAS
	$viso = kiek( "nuorodos", "WHERE `active`='TAIP' AND `lang` = " . escape( lang() ) . "" );
	$info = [];
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "nuorodos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['apie'] ) ? " AND `apie` LIKE " . escape( "%" . $_POST['apie'] . "%" ) . "" : "" ) . ")" : "" ) . " AND active='TAIP' ORDER BY id LIMIT {$p},{$limit}";

	if ($q = mysql_query1($sqlQuery)) {

		if ( isset( $_POST['pavadinimas'] ) && $_POST['date'] && $_POST['apie'] ) {
			$val = array( $_POST['pavadinimas'], $_POST['date'], $_POST['apie'] );
		} else {
			$val = array( "", "", "" );
		}

		$info[] = array(
			"#"                           => "<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('linksch');\" />",
			$lang['admin']['link']        => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pavadinimas\" />",
			$lang['admin']['links_date']  => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"date\" />",
			$lang['admin']['links_about'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"apie\" />",
			$lang['admin']['action']      => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
		);
		//FILTRAVIMAS
		foreach ( $q as $sql ) {
			$nariui_l = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE id='" . $sql['nick'] . "' LIMIT 1" );
			$info[]   = array(
				"#"                           => "<input type=\"checkbox\" value=\"{$sql['id']}\" name=\"links_delete[]\" />",
				$lang['admin']['link']        => '<a href="' . $sql['url'] . '" title="' . $lang['admin']['links_author'] . ': <b>' . $nariui_l['nick'] . '</b><br/>' . $lang['admin']['links_date'] . ': <b>' . date( 'Y-m-d H:i:s ', $sql['date'] ) . ' - ' . kada( date( 'Y-m-d H:i:s ', $sql['date'] ) ) . '</b>" target="_blank">' . $sql['pavadinimas'] . '</a>',
				$lang['admin']['links_date']  => date( 'Y-m-d', $sql['date'] ),
				$lang['admin']['links_about'] => "<span style='cursor:pointer;' title='" . strip_tags( $sql['apie'] ) . "'>" . trimlink( strip_tags( $sql['apie'] ), 55 ) . "</span>",
				$lang['admin']['action']      => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};m," . $sql['id'] ) . "'title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['admin']['delete'] . "?')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};r," . $sql['id'] ) . "'title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			);
		}

		$tableClass  = new Table($info);
		$content = "<form id=\"linksch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele( $lang['admin']['nuorodos'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}

	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_items'] );
	}
} elseif ($_GET['v'] == 5 || isset($_GET['r'])) {

	// if ( isset( $_GET['r'] ) ) {
		
	// 	$argi                 = array( "TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}" );
	// 	$nuorodos_redagavimas = array( 
	// 		"Form"                              => array( "action" => url( "?id,{$_GET['id']};a,{$_GET['a']};v,1" ), "method" => "post", "name" => "edit" ), "{$lang['system']['category']}:" => array( "type" => "select", "value" => $kategorijos, "name" => "Kategorijos_id" ),
	// 		"{$lang['admin']['links_title']}:"  => array( "type" => "text", "value" => $sql['pavadinimas'], "name" => "name" ),
	// 		"{$lang['admin']['links_about']}:"  => array( "type" => "string", "value" => editor( 'jquery', 'mini', 'Aprasymas', ( isset( $sql['apie'] ) ) ? $sql['apie'] : '' ) ),
	// 		"{$lang['admin']['link']}:"         => array( "type" => "text", "value" => $sql['url'], "name" => "url" ),
	// 		"{$lang['admin']['links_active']}:" => array( "type" => "select", "value" => $argi, "name" => "ar" ), 
	// "" => array( "type" => "hidden", "name" => "nuorodos_id", "value" => $_GET['r'] ),
	// 		"{$lang['admin']['edit']}:"         => array( "type" => "submit", "name" => "edit", "value" => "{$lang['admin']['edit']}" )
	// 	);
	
	// 	$formClass = new Form($nuorodos_redagavimas);	
	// 	lentele($lang['admin']['links_edit'], $formClass->form());
	// }


	// Create post
	if (isset($_POST['action']) && ! empty($_POST['action']) ) {
		// Nustatom kintamuosius
		$url         	= strip_tags( $_POST['url'] );
		$apie        	= $_POST['Aprasymas'];
		$pavadinimas 	= strip_tags( $_POST['name'] );
		$active      	= strip_tags( $_POST['active'] );
		$cat  			= isset($_POST['cat']) ? ceil((int)$_POST['cat']) : 0;
		$pattern 		= "#([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#si";
		//url filter
		if (! preg_match($pattern, $url)) {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> $lang['admin']['links_bad']
				]
			);
		} else {
			if($_POST['action'] == $lang['admin']['links_create']) {
	
				$insertQuery = "INSERT INTO `" . LENTELES_PRIESAGA . "nuorodos` (`cat` , `url` ,`pavadinimas` , `nick` , `date` , `apie`, `active`) VALUES (" . escape( $cat ) . ", " . escape( $url ) . ", " . escape( $pavadinimas ) . ", " . escape( $_SESSION[SLAPTAS]['id'] ) . ", '" . time() . "', " . escape( $apie ) . ", " . escape( $active ) . ");";

				if (mysql_query1($insertQuery)) {
					redirect(
						url("?id," . $url['id'] . ";a," . $url['a']),
						"header",
						[
							'type'		=> 'success',
							'message' 	=> $lang['admin']['links_created']
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
				
			} elseif($_POST['action'] == $lang['system']['edit']) {
		
				$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "nuorodos` SET
				`pavadinimas` = " . escape($pavadinimas) . ",
				`apie` = " . escape($aprasymas) . ",
				`active` = " . escape($active) . ",
				`url` = " . escape($url) . ",
				`cat` = " . escape($cat) . "
				WHERE `id`=" . escape($_POST['nuorodos_id']) . ";";
			
				if(mysql_query1($updateQuery)) {
					redirect(
						url("?id," . $url['id'] . ";a," . $url['a']),
						"header",
						[
							'type'		=> 'success',
							'message' 	=> $lang['admin']['links_updated']
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

	$editOrCreate 	= (isset($extra) ? $lang['admin']['links_edit'] : $lang['admin']['links_create']);

	$nuorodos = [
		"Form"							=> [
			"action" 	=> "", 
			"method" 	=> "post", 
			"name" 		=> "Submit_link"
		],

		$lang['system']['category']  	=> [
			"type" 		=> "select", 
			"value" 	=> $kategorijos, 
			"name" 		=> "cat",
			"selected" => (isset($extra['cat']) ? input($extra['cat']) : '0')
		],

		$lang['admin']['links_title']	=> [
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

		$lang['admin']['links_about'] 	=> [
			"type" => "string", 
			"value" => editor('jquery', 'mini', 'Aprasymas', (isset($extra['apie'])) ? $extra['apie'] : '') 
		],

		$lang['admin']['links_active']	=> [
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

unset($info, $sql, $sql2, $q, $result, $result2);
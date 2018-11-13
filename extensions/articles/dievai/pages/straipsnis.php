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
unset( $text, $extra );
if ( count( $_GET ) < 3 ) {
	$_GET['v'] = 7;
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
	lentele($lang['admin']['straipsnis'], buttonsMenu(buttons('articles')));
}

if ( empty( $_GET['v'] ) ) {
	$_GET['v'] = 0;
}

include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "straipsniai", TRUE );

if (isset($_GET['priimti'])) {
	$sqlActivate = "UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET rodoma='TAIP' WHERE `id`=" . escape( $_GET['priimti'] ) . ";";
	if (mysql_query1($sqlActivate)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,6"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['article_activated']
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,6"),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}
	
}
//Posts BULK delete
if ( isset( $_POST['articles_delete'] ) ) {
	foreach ( $_POST['articles_delete'] as $a => $b ) {
		$trinti[] = escape( $b );
	}
	
	$sqlDeleteFew = "DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `id` IN(" . implode( ', ', $trinti ) . ")";

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
}
//Post delete
if (isset($url['t'])) {
	$trinti = (int)$url['t'];
	
	$sqlDelete = "DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE id=" . escape( $trinti ) . " LIMIT 1";

	if (mysql_query1($sqlDelete)) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/straipsnis' AND kid=" . escape( $trinti ) . "" );

		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['post_deleted']
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
// Article update
} elseif ( isset( $_POST['action'] ) && isset( $_POST['str'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$straipsnis  	= explode( '===page===', $_POST['str'] );
	$apr         	= $straipsnis[0];
	$str         	= empty( $straipsnis[1] ) ? '' : $straipsnis[1];
	$komentaras  	= (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
	$rodoma			= (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');
	$kategorija  	= (int)$_POST['kategorija'];
	$pavadinimas 	= strip_tags( $_POST['pav'] );
	$id          	= ceil( (int)$_POST['idas'] );

	if ( $komentaras == 'ne' ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape( (int)$_GET['id'] ) . " AND kid=" . escape( $id ) );
	}

	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET
	    `kat` = " . escape( $kategorija ) . ",
		`pav` = " . escape( $pavadinimas ) . ",
		`t_text` = " . escape( $apr ) . ",
		`f_text` = " . escape( $str ) . ",
		`kom` = " . escape( $komentaras ) . ",
		`rodoma` = " . escape( $rodoma ) . "
		WHERE `id`=" . escape( $id ) . ";";
	
	if(mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['post_updated']
			]
		);
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> mysqli_error($prisijungimas_prie_mysql)
			]
		);
	}

// Article ceation
} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['article_create'] ) {

	$straipsnis  = explode( '===page===', $_POST['str'] );
	$apr         = $straipsnis[0];
	$str         = empty( $straipsnis[1] ) ? '' : $straipsnis[1];
	$kategorija  = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags( $_POST['pav'] );
	$komentaras  = (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
	$rodoma     = (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');
	$autorius    = $_SESSION[SLAPTAS]['username'];
	$autoriusid  = $_SESSION[SLAPTAS]['id'];
	if ( empty( $apr ) || empty( $pavadinimas ) ) {
		$error = $lang['admin']['article_emptyfield'];
	}
	if (! isset($error)) {
		$result = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "straipsniai` SET
	    	`kat` = " . escape( $kategorija ) . ",
			`pav` = " . escape( $pavadinimas ) . ",
			`t_text` = " . escape( $apr ) . ",
			`f_text` = " . escape( $str ) . ",
			`date` = " . time() . ",
			`autorius` = " . escape( $autorius ) . ",
			`autorius_id` = " . escape( $autoriusid ) . ",
			`kom` = " . escape( $komentaras ) . ",
			`rodoma` = " . escape( $rodoma ) . ",
			`lang` = " . escape( lang() ) . "" );
		
			if ($result) {
				redirect(
					url("?id," . $url['id'] . ";a," . $url['a']),
					"header",
					[
						'type'		=> 'success',
						'message' 	=> $lang['admin']['post_created']
					]
				);
			} else {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> mysqli_error($prisijungimas_prie_mysql)
					]
				);
			}
	} else {
		notifyMsg(
			[
				'type'		=> 'error',
				'message' 	=> $error
			]
		);
	}
	unset( $rodoma, $pavadinimas, $kategorija, $komentaras, $str, $apr, $_POST['action'], $result );

}
//straipsnio redagavimas
elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}

	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );
}
if ( isset( $_GET['v'] ) ) {
	$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND `path`=0 AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC" );
	if ( sizeof( $sql ) > 0 ) {

		$kategorijos = cat( 'straipsniai', 0 );
	}

	$kategorijos[0] = "--";
}

if ( $_GET['v'] == 4 ) {
	
	$viso = kiek( "straipsniai", "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . "" );
	$sqlArticles = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "straipsniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pav'] ) ? "AND (`pav` LIKE " . escape( "%" . $_POST['pav'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['t_text'] ) ? " AND `t_text` LIKE " . escape( "%" . $_POST['t_text'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='TAIP' ORDER BY id LIMIT {$p},{$limit}" );
	if(! empty($sqlArticles)) {
		//FILTRAVIMAS
		$formData = [
			'pavadinimas'	=> $lang['admin']['article'],
			'data'			=> $lang['admin']['article_date'],
			'naujiena'		=> $lang['admin']['article_preface'],
		];

		$info[] = tableFilter($formData, $_POST, '#arch');
		//FILTRAVIMAS - END
		foreach ($sqlArticles as $row) {
			$info[] = [
				"#"                         		=> '<input type="checkbox" value="' . $row['id'] . '" name="articles_delete[]" class="filled-in" id="articles-delete-' . $row['id'] . '"><label for="articles-delete-' . $row['id'] . '"></label>',
				$lang['admin']['article']         	=> "<span style='cursor:pointer;' title='" . $lang['admin']['article_author'] . ": <b>" . $row['autorius'] . "</b>' >" . input( $row['pav'] ) . "</span>",
				$lang['admin']['article_date']    	=> date( 'Y-m-d', $row['date'] ),
				$lang['admin']['article_preface'] 	=> "<span style='cursor:pointer;' title='" . strip_tags( $row['t_text'] ) . "'>" . trimlink( strip_tags( $row['t_text'] ), 55 ) . "</span>",
				$lang['admin']['action']          	=> "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" border=\"0\"></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			];
		}

		$tableClass = new Table($info);
		$content = '<form id="arch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';
		lentele($lang['admin']['article_edit'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ($viso > $limit) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
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

if ( $_GET['v'] == 7 || isset( $url['h'] ) ) {
	$editOrCreate 	= (isset($extra) ? $lang['admin']['edit'] : $lang['admin']['article_create']);
	$editorStr 		= (isset( $extra ) ? $extra['t_text'] . ( empty( $extra['f_text'] ) ? '' : "\n===page===\n" . $extra['f_text'] ) : $lang['admin']['article']);
	$articleForm 	= [
		"Form"								=> [
			"action" 	=> url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), 
			"method" 	=> "post", 
			"name" 		=> "reg"
		], 

		$lang['admin']['article_title'] 	=> [
			"type" 	=> "text", 
			"value" => input((isset( $extra ) ) ? $extra['pav'] : ''), 
			"name" 	=> "pav"
		],

		$lang['system']['category']			=> [
			"type" 		=> "select", 
			"value" 	=> $kategorijos, 
			"name" 		=> "kategorija", 
			"selected" 	=> (isset($extra['kat']) ? input($extra['kat']) : '')
		], 

		$lang['admin']['article'] 			=> [
			"type" 	=> "string", 
			"value" => editor( 'jquery', 'standartinis', ['str' => $lang['admin']['article']], ['str' => $editorStr])
		],

		$lang['admin']['article_comments'] 	=> [
			'type'		=> 'switch',
			'value'		=> 1,
			'name'		=> 'kom',
			'id'		=> 'kom',
			'form_line'	=> 'form-not-line',
			'checked' 	=> (! empty($extra['kom']) && $extra['kom'] == 'taip' ? true : false),
		],

		$lang['admin']['article_shown'] 	=> [
			'type'		=> 'switch',
			'value'		=> 1,
			'name'		=> 'rodoma',
			'id'		=> 'rodoma',
			'form_line'	=> 'form-not-line',
			'checked' 	=> (! empty($extra['rodoma']) && $extra['rodoma'] == 'TAIP' ? true : false),
		],


		''									=> [
			"type" 		=> "submit", 
			"name" 		=> "action", 
			'form_line'	=> 'form-not-line',
			"value" 	=> $editOrCreate
		]
	];
	
	if (isset($extra['id'])) {
		$articleForm['idas'] = [
			"type" 	=> "hidden", 
			"name" 	=> "idas", 
			"value" => input($extra['id'])
		];
	}

	$formClass = new Form($articleForm);	
	lentele($lang['admin']['article_create'], $formClass->form());

} elseif ( $_GET['v'] == 6 ) {
	$viso = kiek( "straipsniai", "WHERE `rodoma`='NE' AND `lang` = " . escape( lang() ));
	///FILTRAVIMAS
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "straipsniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pav'] ) ? "AND (`pav` LIKE " . escape( "%" . $_POST['pav'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['t_text'] ) ? " AND `t_text` LIKE " . escape( "%" . $_POST['t_text'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY id DESC LIMIT {$p},{$limit}";
	//
	if ($articleSql = mysql_query1($sqlQuery)) {
		
		$info =[];

		//FILTRAVIMAS
		$formData = [
			'pavadinimas'	=> $lang['admin']['article'],
			'data'			=> $lang['admin']['article_date'],
			'naujiena'		=> $lang['admin']['article_preface'],
		];

		$info[] = tableFilter($formData, $_POST, '#arch');
		//FILTRAVIMAS - END
		foreach ($articleSql as $row) {
			$info[] = [
				"#"                         		=> '<input type="checkbox" value="' . $row['id'] . '" name="articles_delete[]" class="filled-in" id="articles-delete-' . $row['id'] . '"><label for="articles-delete-' . $row['id'] . '"></label>',
				$lang['admin']['article']         	=> "<span style='cursor:pointer;' title='" . $lang['admin']['article_author'] . ": <b>" . $row['autorius'] . "</b>' >" . input( $row['pav'] ) . "</span>",
				$lang['admin']['article_date']    	=> date( 'Y-m-d', $row['date'] ),
				$lang['admin']['article_preface'] 	=> "<span style='cursor:pointer;' title='" . strip_tags( $row['t_text'] ) . "'>" . trimlink( strip_tags( $row['t_text'] ), 55 ) . "</span>",
				$lang['admin']['action']          => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};priimti," . $row['id'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . "' title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			];
		}

		$tableClass  = new Table($info);
		$content ='<form id="arch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';

		lentele($lang['admin']['article_unpublished'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
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
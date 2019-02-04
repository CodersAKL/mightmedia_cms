<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision: 366 $
 * @$Date: 2009-12-03 20:46:01 +0200 (Thu, 03 Dec 2009) $
 * */

if ( !defined( "OK" ) || !ar_admin( basename( __file__ ) ) ) {
	redirect( 'location: http://' . $_SERVER["HTTP_HOST"] );
}

if(BUTTONS_BLOCK) {
	lentele(getLangText('admin', 'naujienos'), buttonsMenu(buttons('news')));
}

unset( $extra );

if ( !isset( $_GET['v'] ) ) {
	$_GET['v'] = 1;
}
// Puslapiavimui
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}
$limit   = 15;

include_once config('functions', 'dir') . 'functions.categories.php';
category("naujienos", TRUE);
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `path`=0 AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC" );
if (! empty($sql)) {

	$categories = cat( 'naujienos', 0 );
}

$categories[0] = "---";

// New activating
if (isset($url['p'])) {
	$sqlActivate = "UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET 
	rodoma='TAIP' 
	WHERE `id`=" . escape($url['p']) . ";";
	
	if (mysql_query1($sqlActivate)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,6"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'news_activated')
			]
		);
	} else {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,6"),
			"header",
			[
				'type'		=> 'error',
				'message' 	=> input(mysqli_error($prisijungimas_prie_mysql))
			]
		);
	}
}

// Naujienos trinimas
if ( ( ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'delete') && isset( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['t'] ) ) {
	
	if ( isset( $url['t'] ) ) {
		$trinti = (int)$url['t'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$trinti = (int)$_POST['edit_new'];
	}

	$sqlDelete = "DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE id=" . escape( $trinti ) . " LIMIT 1";

	if (mysql_query1($sqlDelete)) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='content/pages/naujienos' AND kid=" . escape( $trinti ) . "" );

		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'post_deleted')
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
}
// Naujienu trinimas
if ( isset( $_POST['news_delete'] ) ) {
	foreach ( $_POST['news_delete'] as $a => $b ) {
		$trinti[] = escape( $b );
	}

	$sqlDeleteFew = "DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id` IN(" . implode( ", ", $trinti ) . ")";

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
	
}

// Naujienos redagavimas
if ( isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	}

	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );
} elseif ( isset( $_POST['categories_id'] ) && isNum( $_POST['categories_id'] ) && $_POST['categories_id'] > 0 && isset( $_POST['category'] ) && $_POST['category'] == getLangText('admin', 'edit') ) {
	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape( (int)$_POST['categories_id'] ) . " LIMIT 1" );
} // Išsaugojam redaguojamą naujieną
elseif ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'edit') ) {
	$naujiena = explode( '===page===', $_POST['naujiena'] );
	//$placiau =  explode('===page===',$_POST['naujiena']);
	$izanga      = $naujiena[0];
	$placiau     = (empty($naujiena[1]) ? '' : $naujiena[1] );
	$komentaras  = (isset($_POST['kom']) ? 'taip' : 'ne' );
	$rodymas     = (isset($_POST['rodoma']) ? 'TAIP' : 'NE' );
	$category  = (int)$_POST['category'];
	$pavadinimas = strip_tags( $_POST['pav'] );
	$id          = ceil( (int)$_POST['news_id'] );
	$sticky      = ( isset( $_POST['sticky'] ) ? 1 : 0 );
	if ( $komentaras == 'ne' ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape( (int)$_GET['id'] ) . " AND kid=" . escape( $id ) );
	}
	
	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`kategorija` = " . escape( $category ) . ",
	`naujiena` = " . escape( $izanga ) . ",
	`daugiau` = " . escape( $placiau ) . ",
	`kom` = " . escape( $komentaras ) . ",
	`rodoma` = " . escape( $rodymas ) . ",
	`sticky` = " . escape( $sticky ) . "
	WHERE `id`=" . escape( $id ) . ";
	";
	
	if(mysql_query1($updateQuery)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a']),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> getLangText('admin', 'post_updated')
			]
		);
	}

} //Išsaugojam naujieną
elseif ( isset( $_POST['action'] ) && $_POST['action'] == getLangText('admin', 'news_create') ) {
	$naujiena    = explode( '===page===', $_POST['naujiena'] );
	$izanga      = $naujiena[0];
	$placiau     = empty( $naujiena[1] ) ? '' : $naujiena[1];
	$komentaras  = (isset($_POST['kom']) ? 'taip' : 'ne' );
	$rodymas     = (isset($_POST['rodoma']) ? 'TAIP' : 'NE' );
	$pavadinimas = strip_tags( $_POST['pav'] );
	$category  = (int)$_POST['category'];
	$sticky      = ( isset( $_POST['sticky'] ) ? 1 : 0 );

	if ( empty( $naujiena ) || empty( $pavadinimas ) ) {
		$error = getLangText('admin', 'news_required');
	}

	if ( !isset( $error ) ) {
		$result    = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, rodoma, kategorija, lang, sticky) VALUES (" . escape( $pavadinimas ) . ", " . escape( $izanga ) . ", " . escape( $placiau ) . ",  '" . time() . "', '" . getSession('username') . "', " . escape( $komentaras ) . ", " . escape( $rodymas ) . ", " . escape( $category ) . ",  " . escape( lang() ) . ", " . escape( $sticky ) . ")" );
		$last_news = mysql_query1( "SELECT `id` FROM `" . LENTELES_PRIESAGA . "naujienos` ORDER BY `id` DESC LIMIT 1" );
		if ( isset( $_POST['letter'] ) ) {

			require_once config('class', 'dir') . 'class.phpmailer-lite.php';
			include_once ROOT . 'content/themes/' . $conf['Stilius'] . '/sfunkcijos.php';
			include_once ROOT . 'content/themes/' . $conf['Stilius'] . '/naujienlaiskiui.php';

			$mail = new PHPMailerLite();
			$mail->IsMail();
			$mail->CharSet  = 'UTF-8';
			$mail->SingleTo = TRUE;
			$nuoroda_i_naujiena     = "" . url( "?id,{$conf['pages']['naujienos.php']['id']};k,{$last_news['id']}" ) . "";
			$nuoroda_atsisakyti  = "" . url( "?id," . $conf['pages']['naujienlaiskiai.php']['id'] ) . "";
			$mail->SetFrom( $admin_email, $conf['Pavadinimas'] );
			$mail->Subject = strip_tags( $conf['Pavadinimas'] ) . " " . $pavadinimas;
			$body           = naujienlaiskis($pavadinimas, $izanga, $nuoroda_i_naujiena, $nuoroda_atsisakyti);
			$mail->MsgHTML( $body );
			$sql = mysql_query1( "SELECT `email` FROM `" . LENTELES_PRIESAGA . "newsgetters`" );
			foreach ( $sql as $row ) {
				if ( $mail->ValidateAddress( $row['email'] ) ) {
					$name = explode( '@', $row['email'] );
					//$mail->AddAddress($row['email']);
					$mail->AddBCC( $row['email'], $name[0] );
				}
			}
			$mail->Send();
			if ($mail->IsError()) {
				notifyMsg(
					[
						'type'		=> 'error',
						'message' 	=> $mail->ErrorInfo
					]
				);
			}
		}

		if ( $result ) {
			redirect(
				url("?id," . $url['id'] . ";a," . $url['a']),
				"header",
				[
					'type'		=> 'success',
					'message' 	=> getLangText('admin', 'post_created')
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
				'message' 	=> $error
			]
		);
	}

	unset( $naujiena, $placiau, $rodymas, $komentaras, $pavadinimas, $result, $error, $_POST['action'] );
}

if ( isset( $_GET['v'] ) ) {

	if ( $_GET['v'] == 4 ) {
		///FILTRAVIMAS
		$viso     = kiek( 'naujienos', "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) );
		$sql_news = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? " AND `data` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['naujiena'] ) ? " AND `naujiena` LIKE " . escape( "%" . $_POST['naujiena'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='TAIP' ORDER BY sticky DESC, id DESC LIMIT {$p},{$limit}" );

		if (! empty($sql_news)) {
			//FILTRAVIMAS
			$formData = [
				'pavadinimas'	=> getLangText('admin', 'news_name'),
				'data'			=> getLangText('admin', 'news_date'),
				'naujiena'		=> getLangText('admin', 'news_more'),
			];
			
			$info[] = tableFilter($formData, $_POST, '#newsch');
			//FILTRAVIMAS - END
			foreach ($sql_news as $row) {
				$info[] = [
					"#"                         => '<input type="checkbox" value="' . $row['id'] . '" name="news_delete[]" class="filled-in" id="news-delete-' . $row['id'] . '"><label for="news-delete-' . $row['id'] . '"></label>',
					getLangText('admin', 'news_name') => '<span style="cursor:pointer;" title="<b>' . $row['pavadinimas'] . '</b><br />' . getLangText('admin', 'news_author') . ': <b>' . $row['autorius'] . '</b>">' . trimlink( strip_tags( $row['pavadinimas'] ), 55 ) . '<span/></a>',
					getLangText('admin', 'news_date') => date( 'Y-m-d', $row['data'] ),
					getLangText('admin', 'news_more') => trimlink( strip_tags( $row['naujiena'] ), 55 ),
					getLangText('admin', 'action')    => '<a href="' . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . '" title="' . getLangText('admin', 'edit') . '"><img src="' . ROOT . 'core/assets/images/icons/pencil.png" border="0"></a> <a href="' . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . '" title="' . getLangText('admin', 'delete') . '" onClick="return confirm(\'' . getLangText('system', 'delete_confirm') . '\')"><img src="' . ROOT . 'core/assets/images/icons/cross.png" border="0"></a>'
				];
			}

			$tableClass = new Table($info);
			$content ='<form id="newsch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . getLangText('system', 'delete') . '</button></form>';

			lentele(getLangText('admin', 'edit'), $content);
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
			}

		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> getLangText('system', 'no_items')
				]
			);
		}
	} elseif ( $_GET['v'] == 1 || isset( $_GET['h'] ) ) {

		$newForm = [
			"Form" => [
				"action" => url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . "" ),
				"method" => "post",
				"name"   => "reg"
			],

			getLangText('admin', 'news_name') => [
				"type"  => "text",
				"value" => input( ( isset( $extra ) ) ? $extra['pavadinimas'] : '' ),
				"name"  => "pav",
				"class" => "input"
			],

			getLangText('admin', 'news_category') => [
				"type"     => "select",
				"value"    => $categories,
				"name"     => "category",
				"class"    => "input",
				"selected" => ( isset( $extra['kategorija'] ) ? input( $extra['kategorija'] ) : '0' )
			],

			getLangText('admin', 'news_text') => [
				"type"  => "string",
			    "value" => editor(
					'jquery',
					'standartinis',
					array( 'naujiena' => getLangText('admin', 'news_preface') ),
					array( 'naujiena' => (
						isset( $extra )
							? $extra['naujiena'] . ( empty( $extra['daugiau'] ) ? ''
							: "\n===page===\n" . $extra['daugiau'] ) : getLangText('admin', 'news_preface')
						)
					)
				)
			],
			
			getLangText('admin', 'komentarai') => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'kom',
				'id'		=> 'kom',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['kom']) && $extra['kom'] == 'taip' ? true : false),
			],

			getLangText('admin', 'article_shown') => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'rodoma',
				'id'		=> 'rodoma',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['rodoma']) && $extra['rodoma'] == 'TAIP' ? true : false),
			],

			getLangText('admin', 'news_sticky') => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'sticky',
				'id'		=> 'sticky',
				'form_line'	=> 'form-not-line',
				'checked'	=> isset($extra) && $extra['sticky'] == 1
			]
		];

		$newForm[getLangText('news', 'newsletter?')] = [
			'type'		=> 'switch',
			'value'		=> 1,
			'name'		=> 'letter',
			'id'		=> 'letter',
			'form_line'	=> 'form-not-line',
			'checked'	=> isset($extra) && ! empty($extra['letter']) && $extra['letter'] == 1
		];

		$newForm[''] = [
			"type"  	=> "submit",
			"name"  	=> "action",
			'form_line'	=> 'form-not-line',
			"value" 	=> (isset($extra)) ? getLangText('admin', 'edit') : getLangText('admin', 'news_create')
		];

		if (isset($extra)) {
			if(isset($conf['pages']['naujienlaiskiai.php']['id'])) {
				$newForm[getLangText('news', 'newsletter?')] = [
					'type'		=> 'switch',
					'value'		=> 1,
					'name'		=> 'letter',
					'id'		=> 'letter',
					'form_line'	=> 'form-not-line',
					'checked'	=> isset($extra) && $extra['letter'] == 1
				];
			}

			$newForm['news_id'] =[
				"type"  	=> "hidden",
				"name"  	=> "news_id",
				"value" 	=> (isset( $extra ) ? input( $extra['id']) : '')
			];
		}

		$formClass = new Form($newForm);
		$title = ( !isset( $extra ) ? getLangText('admin', 'news_create') : getLangText('admin', 'news_edit') );
		
		lentele($title, $formClass->render());

	} elseif ( $_GET['v'] == 6 ) {

		///FILTRAVIMAS
		$viso = kiek( "naujienos", "WHERE `rodoma`='NE' AND `lang` = " . escape( lang() ) . "" );
		$selectQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? " AND `data` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['naujiena'] ) ? " AND `naujiena` LIKE " . escape( "%" . $_POST['naujiena'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY sticky DESC, id DESC LIMIT {$p},{$limit}";
		if ($unpublishedNews = mysql_query1($selectQuery)) {
			
			$info = [];
			//
			//FILTER - begin
			$formData = [
				'pavadinimas'	=> getLangText('admin', 'news_name'),
				'data'			=> getLangText('admin', 'news_date'),
				'naujiena'		=> getLangText('admin', 'news_more'),
			];
			
			$info[] = tableFilter($formData, $_POST, '#newsch');
			//FILTRAVIMAS

			foreach ($unpublishedNews as $new) {
				$info[] =[
					"#"                         => '<input type="checkbox" value="' . $new['id'] . '" name="news_delete[]" class="filled-in" id="news-delete-' . $new['id'] . '"><label for="news-delete-' . $new['id'] . '"></label>',
					getLangText('admin', 'news_name') => '<span style="cursor:pointer;" data-toggle="tooltip" title="' . $new['pavadinimas'] . '">' . trimlink( strip_tags( $new['pavadinimas'] ), 55 ) . '</span></a>',
					getLangText('admin', 'news_date') => date( 'Y-m-d', $new['data'] ),
					getLangText('admin', 'news_more') => trimlink( strip_tags( $new['naujiena'] ), 55 ),
					getLangText('admin', 'action')    => "<a href='" . url( "?id,{$url['id']};a,{$url['a']};p," . $new['id'] ) . "' data-toggle='tooltip' title = '" . getLangText('admin',  'acept') . "'><img src='" . ROOT . "core/assets/images/icons/tick_circle.png'></a> 
					<a href='" . url( "?id,{$url['id']};a,{$url['a']};h," . $new['id'] ) . "' data-toggle='tooltip' title = '" . getLangText('admin',  'edit') . "'><img src='" . ROOT . "core/assets/images/icons/pencil.png'></a> 
					<a href='" . url( "?id,{$url['id']};a,{$url['a']};t," . $new['id'] ) . "' data-toggle='tooltip' title = '" . getLangText('admin',  'delete') . "' onclick=\"return confirm('" . getLangText('system', 'delete_confirm') . "')\"><img src='" . ROOT . "core/assets/images/icons/cross.png'></a>"
				];
			}

			$tableClass  = new Table($info);
			$content = '<form id="newsch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . getLangText('system', 'delete') . '</button></form>';
			
			lentele( getLangText('admin', 'news_unpublished'), $content);
			// if list is bigger than limit, then we show list with pagination
			if ( $viso > $limit ) {
				lentele( getLangText('system', 'pages'), pages( $p, $limit, $viso, 10 ) );
			}

		} else {
			notifyMsg(
				[
					'type'		=> 'error',
					'message' 	=> getLangText('system', 'no_items')
				]
			);
		}
	}
}
unset( $sql, $extra, $row );
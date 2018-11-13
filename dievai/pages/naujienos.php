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
	lentele($lang['admin']['naujienos'], buttonsMenu(buttons('news')));
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

include_once ROOT . "priedai/kategorijos.php";
kategorija( "naujienos", TRUE );
$sql = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `path`=0 AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC" );
if (! empty($sql)) {

	$kategorijos = cat( 'naujienos', 0 );
}

$kategorijos[0] = "---";

// New activating
if ( isset( $_GET['priimti'] ) ) {
	$sqlActivate = "UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET rodoma='TAIP' WHERE `id`=" . escape( $_GET['priimti'] ) . ";";
	if (mysql_query1($sqlActivate)) {
		redirect(
			url("?id," . $url['id'] . ";a," . $url['a'] . ";v,6"),
			"header",
			[
				'type'		=> 'success',
				'message' 	=> $lang['admin']['news_activated']
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

// Naujienos trinimas
if ( ( ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['delete'] && isset( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['t'] ) ) {
	
	if ( isset( $url['t'] ) ) {
		$trinti = (int)$url['t'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$trinti = (int)$_POST['edit_new'];
	}

	$sqlDelete = "DELETE FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE id=" . escape( $trinti ) . " LIMIT 1";

	if (mysql_query1($sqlDelete)) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/naujienos' AND kid=" . escape( $trinti ) . "" );

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

// Naujienos redagavimas
if ( isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	}

	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `id`=" . escape( $redaguoti ) . " LIMIT 1" );
} elseif ( isset( $_POST['Kategorijos_id'] ) && isNum( $_POST['Kategorijos_id'] ) && $_POST['Kategorijos_id'] > 0 && isset( $_POST['Kategorija'] ) && $_POST['Kategorija'] == $lang['admin']['edit'] ) {
	$extra = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape( (int)$_POST['Kategorijos_id'] ) . " LIMIT 1" );
} // Išsaugojam redaguojamą naujieną
elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$naujiena = explode( '===page===', $_POST['naujiena'] );
	//$placiau =  explode('===page===',$_POST['naujiena']);
	$izanga      = $naujiena[0];
	$placiau     = ( empty( $naujiena[1] ) ? '' : $naujiena[1] );
	$komentaras  = ( isset( $_POST['kom'] ) ? $_POST['kom'] : 'taip' );
	$rodymas     = ( isset( $_POST['rodoma'] ) ? $_POST['rodoma'] : 'TAIP' );
	$kategorija  = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags( $_POST['pav'] );
	$id          = ceil( (int)$_POST['news_id'] );
	$sticky      = ( isset( $_POST['sticky'] ) ? 1 : 0 );
	if ( $komentaras == 'ne' ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape( (int)$_GET['id'] ) . " AND kid=" . escape( $id ) );
	}
	
	$updateQuery = "UPDATE `" . LENTELES_PRIESAGA . "naujienos` SET
	`pavadinimas` = " . escape( $pavadinimas ) . ",
	`kategorija` = " . escape( $kategorija ) . ",
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
				'message' 	=> $lang['admin']['post_updated']
			]
		);
	}

} //Išsaugojam naujieną
elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['news_create'] ) {
	$naujiena    = explode( '===page===', $_POST['naujiena'] );
	$izanga      = $naujiena[0];
	$placiau     = empty( $naujiena[1] ) ? '' : $naujiena[1];
	$komentaras  = (isset($_POST['kom']) && $_POST['kom'] === '1' ? 'taip' : 'ne');
	$rodymas     = (isset($_POST['rodoma']) && $_POST['rodoma'] === '1' ? 'TAIP' : 'NE');
	$pavadinimas = strip_tags( $_POST['pav'] );
	$kategorija  = (int)$_POST['kategorija'];
	$sticky      = ( isset( $_POST['sticky'] ) ? 1 : 0 );

	if ( empty( $naujiena ) || empty( $pavadinimas ) ) {
		$error = $lang['admin']['news_required'];
	}

	if ( !isset( $error ) ) {
		$result    = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "naujienos` (pavadinimas, naujiena, daugiau, data, autorius, kom, rodoma, kategorija, lang, sticky) VALUES (" . escape( $pavadinimas ) . ", " . escape( $izanga ) . ", " . escape( $placiau ) . ",  '" . time() . "', '" . $_SESSION[SLAPTAS]['username'] . "', " . escape( $komentaras ) . ", " . escape( $rodymas ) . ", " . escape( $kategorija ) . ",  " . escape( lang() ) . ", " . escape( $sticky ) . ")" );
		$last_news = mysql_query1( "SELECT `id` FROM `" . LENTELES_PRIESAGA . "naujienos` ORDER BY `id` DESC LIMIT 1" );
		if ( isset( $_POST['letter'] ) ) {

			require_once ROOT . 'priedai/class.phpmailer-lite.php';
			include_once ROOT . 'stiliai/' . $conf['Stilius'] . '/sfunkcijos.php';
			include_once ROOT . 'stiliai/' . $conf['Stilius'] . '/naujienlaiskiui.php';

			$mail = new PHPMailerLite();
			$mail->IsMail();
			$mail->CharSet  = 'UTF-8';
			$mail->SingleTo = TRUE;
			$nuoroda_i_naujiena     = "" . url( "?id,{$conf['puslapiai']['naujienos.php']['id']};k,{$last_news['id']}" ) . "";
			$nuoroda_atsisakyti  = "" . url( "?id," . $conf['puslapiai']['naujienlaiskiai.php']['id'] ) . "";
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
				'pavadinimas'	=> $lang['admin']['news_name'],
				'data'			=> $lang['admin']['news_date'],
				'naujiena'		=> $lang['admin']['news_more'],
			];
			
			$info[] = tableFilter($formData, $_POST, '#newsch');
			//FILTRAVIMAS - END
			foreach ($sql_news as $row) {
				$info[] = [
					"#"                         => '<input type="checkbox" value="' . $row['id'] . '" name="news_delete[]" class="filled-in" id="news-delete-' . $row['id'] . '"><label for="news-delete-' . $row['id'] . '"></label>',
					$lang['admin']['news_name'] => '<span style="cursor:pointer;" title="<b>' . $row['pavadinimas'] . '</b><br />' . $lang['admin']['news_author'] . ': <b>' . $row['autorius'] . '</b>">' . trimlink( strip_tags( $row['pavadinimas'] ), 55 ) . '<span/></a>',
					$lang['admin']['news_date'] => date( 'Y-m-d', $row['data'] ),
					$lang['admin']['news_more'] => trimlink( strip_tags( $row['naujiena'] ), 55 ),
					$lang['admin']['action']    => '<a href="' . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . '" title="' . $lang['admin']['edit'] . '"><img src="' . ROOT . 'images/icons/pencil.png" border="0"></a> <a href="' . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . '" title="' . $lang['admin']['delete'] . '" onClick="return confirm(\'' . $lang['system']['delete_confirm'] . '\')"><img src="' . ROOT . 'images/icons/cross.png" border="0"></a>'
				];
			}

			$tableClass = new Table($info);
			$content ='<form id="newsch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';

			lentele($lang['admin']['edit'], $content);
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
	} elseif ( $_GET['v'] == 1 || isset( $_GET['h'] ) ) {

		$newForm = [
			"Form" => [
				"action" => url( "?id," . $_GET['id'] . ";a," . $_GET['a'] . "" ),
				"method" => "post",
				"name"   => "reg"
			],

			$lang['admin']['news_name'] => [
				"type"  => "text",
				"value" => input( ( isset( $extra ) ) ? $extra['pavadinimas'] : '' ),
				"name"  => "pav",
				"class" => "input"
			],

			$lang['admin']['news_category'] => [
				"type"     => "select",
				"value"    => $kategorijos,
				"name"     => "kategorija",
				"class"    => "input",
				"selected" => ( isset( $extra['kategorija'] ) ? input( $extra['kategorija'] ) : '0' )
			],

			$lang['admin']['news_text'] => [
				"type"  => "string",
			    "value" => editor(
					'jquery',
					'standartinis',
					array( 'naujiena' => $lang['admin']['news_preface'] ),
					array( 'naujiena' => (
						isset( $extra )
							? $extra['naujiena'] . ( empty( $extra['daugiau'] ) ? ''
							: "\n===page===\n" . $extra['daugiau'] ) : $lang['admin']['news_preface']
						)
					)
				)
			],
			
			$lang['admin']['komentarai'] => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'kom',
				'id'		=> 'kom',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['kom']) && $extra['kom'] == 'taip' ? true : false),
			],

			$lang['admin']['article_shown'] => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'rodoma',
				'id'		=> 'rodoma',
				'form_line'	=> 'form-not-line',
				'checked' 	=> (! empty($extra['rodoma']) && $extra['rodoma'] == 'TAIP' ? true : false),
			],

			$lang['admin']['news_sticky'] => [
				'type'		=> 'switch',
				'value'		=> 1,
				'name'		=> 'sticky',
				'id'		=> 'sticky',
				'form_line'	=> 'form-not-line',
				'checked'	=> isset($extra) && $extra['sticky'] == 1
			]
		];

		$newForm[$lang['news']['newsletter?']] = [
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
			"value" 	=> (isset($extra)) ? $lang['admin']['edit'] : $lang['admin']['news_create']
		];

		if (isset($extra)) {
			if(isset($conf['puslapiai']['naujienlaiskiai.php']['id'])) {
				$newForm[$lang['news']['newsletter?']] = [
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
		$title = ( !isset( $extra ) ? $lang['admin']['news_create'] : $lang['admin']['news_edit'] );
		
		lentele($title, $formClass->form());

	} elseif ( $_GET['v'] == 6 ) {

		///FILTRAVIMAS
		$viso = kiek( "naujienos", "WHERE `rodoma`='NE' AND `lang` = " . escape( lang() ) . "" );
		$q    = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "naujienos` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pavadinimas'] ) ? "AND (`pavadinimas` LIKE " . escape( "%" . $_POST['pavadinimas'] . "%" ) . " " . ( !empty( $_POST['data'] ) ? " AND `data` <= " . strtotime( $_POST['data'] ) . "" : "" ) . " " . ( !empty( $_POST['naujiena'] ) ? " AND `naujiena` LIKE " . escape( "%" . $_POST['naujiena'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY sticky DESC, id DESC LIMIT {$p},{$limit}" );
		//
		if (! empty($q)) {
			
			$info = [];
			//
			//FILTER - begin
			$formData = [
				'pavadinimas'	=> $lang['admin']['news_name'],
				'data'			=> $lang['admin']['news_date'],
				'naujiena'		=> $lang['admin']['news_more'],
			];
			
			$info[] = tableFilter($formData, $_POST, '#newsch');
			//FILTRAVIMAS

			foreach ($q as $sql) {
				$info[] = array(
					"#"                         => '<input type="checkbox" value="' . $row['id'] . '" name="news_delete[]" class="filled-in" id="news-delete-' . $sql['id'] . '"><label for="news-delete-' . $sql['id'] . '"></label>',
					$lang['admin']['news_name'] => '<span style="cursor:pointer;" title="<b>' . $sql['pavadinimas'] . '</b><br />' . $lang['admin']['news_author'] . ': <b>' . $sql['autorius'] . '</b>">' . trimlink( strip_tags( $sql['pavadinimas'] ), 55 ) . '<span/></a>',
					$lang['admin']['news_date'] => date( 'Y-m-d', $sql['data'] ),
					$lang['admin']['news_more'] => trimlink( strip_tags( $sql['naujiena'] ), 55 ),
					$lang['admin']['action']    => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};p," . $sql['id'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src='" . ROOT . "images/icons/cross.png' border='0'></a>"
				);
			}

			$tableClass  = new Table($info);
			$content = '<form id="newsch" method="post">' . $tableClass->render() . '<button type="submit" class="btn bg-red waves-effect">' . $lang['system']['delete'] . '</button></form>';
			
			lentele( $lang['admin']['news_unpublished'], $content);
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
}
unset( $sql, $extra, $row );
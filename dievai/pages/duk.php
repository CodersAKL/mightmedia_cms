<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author: zlotas $
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision: 945 $
 * @$Date: 2012-09-07 01:00:25 +0300 (Pn, 07 Rgs 2012) $
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
	lentele($lang['admin']['faq'], buttonsMenu($buttons['faq']));
}

unset($buttons);

if ( empty( $_GET['v'] ) ) {
	$_GET['v'] = 0;
}

//trinimas
if ( isset( $_POST['articles_delete'] ) ) {
	foreach ( $_POST['articles_delete'] as $a=> $b ) {
		$trinti[] = escape( $b );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` IN(" . implode( ', ', $trinti ) . ")" );
	header( "Location:" . $_SERVER['HTTP_REFERER'] );
	exit;
}

if ( isset( $_GET['t'] ) ) {
	$trinti = (int)$url['t'];
	$ar     = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE id=" . escape( $trinti ) . " LIMIT 1" );
	if ( $ar ) {
		msg( $lang['system']['done'], "{$lang['admin']['faq_deleted']}" );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
//	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape( (int)$_GET['t'] ) );

} elseif ( ( ( isset( $_POST['edit_new'] ) && isNum( $_POST['edit_new'] ) && $_POST['edit_new'] > 0 ) ) || isset( $url['h'] ) ) {
	if ( isset( $url['h'] ) ) {
		$redaguoti = (int)$url['h'];
	} elseif ( isset( $_POST['edit_new'] ) ) {
		$redaguoti = (int)$_POST['edit_new'];
	}
	$sql_ex = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `id` = " . escape( $redaguoti ) . " LIMIT 1" );


} elseif ( isset( $_POST['action'] ) && isset( $_POST['Klausimas'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$klausimas = $_POST['Klausimas'];
	$atsakymas = $_POST['Atsakymas'];
	$order     = (int)$_POST['Order'];
	$id        = ceil( (int)$_POST['eid'] );
	$q = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "duk` SET
			`atsakymas` = " . escape( $atsakymas ) . ",
			`klausimas` = " . escape( $klausimas ) . ",
			`order` = " . escape( $order ) . " WHERE `id`=" . $id . ";
			" ) or klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	if ( $q ) {
		msg( $lang['system']['done'], "{$lang['admin']['faq_updated']}." );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['faq']['new'] ) {
	$klausimas = $_POST['Klausimas'];
	$atsakymas = $_POST['Atsakymas'];
	$order     = (int)$_POST['Order'];
	$q         = mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "duk` (`klausimas`,`atsakymas`,`order`,`lang`) VALUES (
		  " . escape( $klausimas ) . ",
		  " . escape( $atsakymas ) . ",
		  " . escape( $order ) . ",
		  " . escape( lang() ) . ");" );
	if ( $q ) {
		msg( $lang['system']['done'], "{$lang['admin']['faq_created']}." );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
}

if ( $_GET['v'] == 4 ) {
	$viso = kiek( "duk", " WHERE`lang` = " . escape( lang() ) . "" );
	$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `lang` = " . escape( lang() ) . " ORDER by `order` ASC LIMIT {$p},{$limit}" );

	if (  $viso > 0 ) {
		foreach ( $sql as $row ) {

			$info[] = array(  
				"<input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('arch');\" />" => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"articles_delete[]\" />",
					$lang['faq']['order']             => $row['order'],
					$lang['faq']['question']         => trimlink( $row['klausimas'], 55 ),
					$lang['faq']['answer']           => trimlink( $row['atsakymas'], 55 ),
					$lang['admin']['action']          => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . "' title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
				);
		}
		
		$tableClass = new Table($info);
		$content = "<form id=\"arch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele($lang['faq']['questions'], $content);
		
		unset($info);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}
	} else {
		klaida( $lang['system']['error'], $lang['system']['no_items'] );
	}

} elseif ( $_GET['v'] == 7 || isset( $url['h'] ) ) {
	$duk = array(
		"Form"                        => array(
			"action"  => url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ),
			"method"  => "post",
			"enctype" => "",
			"id"      => "",
			"class"   => "",
			"name"    => "reg"
		),
		"{$lang['faq']['question']}:" => array(
			"type"  => "text",
			"value" => input( ( isset( $sql_ex ) ) ? $sql_ex['klausimas'] : '' ),
			"name"  => "Klausimas"
		),
		"{$lang['faq']['answer']}:"   => array(
			"type"  => "string",
			"value" => editor('jquery', 'mini', 'Atsakymas', ( isset( $sql_ex ) ? $sql_ex['atsakymas'] : '' ))
		),
		"{$lang['faq']['order']}:"    => array(
			"type"  => "text",
			"value" => (isset( $sql_ex ) ? (int)$sql_ex['order'] : ''),
			"name"  => "Order"
		),
		" "                           => array(
			"type"  => "hidden",
			"value" => ( isset( $sql_ex['id'] ) ? input( $sql_ex['id'] ) : '' ),
			"name"  => "eid",
			"id"    => "id"
		),
		""                            => array(
			"type"  => "submit",
			"name"  => "action",
			"value" => ( isset( $sql_ex ) ) ? $lang['admin']['edit'] : $lang['faq']['new']
		)
	);

	// Verčiam msayvą į formą
	$formClass = new Form($duk);
	lentele($lang['faq']['edit'], $formClass->form());
}
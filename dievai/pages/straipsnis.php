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
	lentele($lang['admin']['straipsnis'], buttonsMenu($buttons['articles']));

}
unset($buttons);

if ( empty( $_GET['v'] ) ) {
	$_GET['v'] = 0;
}

include_once ( ROOT . "priedai/kategorijos.php" );
kategorija( "straipsniai", TRUE );

if ( isset( $_GET['priimti'] ) ) {
	$result = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET rodoma='TAIP' WHERE `id`=" . escape( $_GET['priimti'] ) . ";" );
	if ( $result ) {
		msg( $lang['system']['done'], "{$lang['admin']['article_activated']}." );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
}
//trinimas
if ( isset( $_POST['articles_delete'] ) ) {
	foreach ( $_POST['articles_delete'] as $a=> $b ) {
		$trinti[] = escape( $b );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `id` IN(" . implode( ', ', $trinti ) . ")" );
	header( "Location:" . $_SERVER['HTTP_REFERER'] );
	exit;
}
if ( isset( $url['t'] ) ) {
	$trinti = (int)$url['t'];
	$ar     = mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE id=" . escape( $trinti ) . " LIMIT 1" );
	if ( $ar ) {
		msg( $lang['system']['done'], "{$lang['admin']['article_Deleted']}" );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}
	mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid='puslapiai/straipsnis' AND kid=" . escape( $trinti ) . "" );
} elseif ( isset( $_POST['action'] ) && isset( $_POST['str'] ) && $_POST['action'] == $lang['admin']['edit'] ) {
	$straipsnis  = explode( '===page===', $_POST['str'] );
	$apr         = $straipsnis[0];
	$str         = empty( $straipsnis[1] ) ? '' : $straipsnis[1];
	$komentaras  = ( isset( $_POST['kom'] ) && $_POST['kom'] == 'taip' ? 'taip' : 'ne' );
	$rodoma      = ( isset( $_POST['rodoma'] ) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE' );
	$kategorija  = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags( $_POST['pav'] );
	$id          = ceil( (int)$_POST['idas'] );

	if ( $komentaras == 'ne' ) {
		mysql_query1( "DELETE FROM `" . LENTELES_PRIESAGA . "kom` WHERE pid=" . escape( (int)$_GET['id'] ) . " AND kid=" . escape( $id ) );
	}

	$resultas = mysql_query1( "UPDATE `" . LENTELES_PRIESAGA . "straipsniai` SET
	    `kat` = " . escape( $kategorija ) . ",
			`pav` = " . escape( $pavadinimas ) . ",
			`t_text` = " . escape( $apr ) . ",
			`f_text` = " . escape( $str ) . ",
			`kom` = " . escape( $komentaras ) . ",
			`rodoma` = " . escape( $rodoma ) . "
			WHERE `id`=" . escape( $id ) . ";
			" ) or klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	if ( $resultas ) {
		msg( $lang['system']['done'], "{$lang['admin']['article_updated']}." );
	} else {
		klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
	}

} elseif ( isset( $_POST['action'] ) && $_POST['action'] == $lang['admin']['article_create'] ) {

	$straipsnis  = explode( '===page===', $_POST['str'] );
	$apr         = $straipsnis[0];
	$str         = empty( $straipsnis[1] ) ? '' : $straipsnis[1];
	$komentaras  = ( isset( $_POST['kom'] ) && $_POST['kom'] == 'taip' ? 'taip' : 'ne' );
	$kategorija  = (int)$_POST['kategorija'];
	$pavadinimas = strip_tags( $_POST['pav'] );
	$rodoma      = ( isset( $_POST['rodoma'] ) && $_POST['rodoma'] == 'TAIP' ? 'TAIP' : 'NE' );
	$autorius    = $_SESSION[SLAPTAS]['username'];
	$autoriusid  = $_SESSION[SLAPTAS]['id'];
	if ( empty( $apr ) || empty( $pavadinimas ) ) {
		$error = "{$lang['admin']['article_emptyfield']}.";
	}
	if ( !isset( $error ) ) {
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
		if ( $result ) {
			msg( $lang['system']['done'], "{$lang['admin']['article_created']}" );
		} else {
			klaida( $lang['system']['error'], " <br><b>" . mysqli_error($prisijungimas_prie_mysql) . "</b>" );
		}
	} else {
		klaida( $lang['system']['error'], $error );
	}
	unset( $rodoma, $pavadinimas, $kategorija, $komentaras, $str, $apr, $_POST['action'], $result );
	//redirect(url("?id," . $_GET['id'] . ";a," . $_GET['a']), "meta");

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
	
	///FILTRAVIMAS
	$viso = kiek( "straipsniai", "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . "" );
	$sql2 = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "straipsniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pav'] ) ? "AND (`pav` LIKE " . escape( "%" . $_POST['pav'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['t_text'] ) ? " AND `t_text` LIKE " . escape( "%" . $_POST['t_text'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='TAIP' ORDER BY id LIMIT {$p},{$limit}" );
	
	if ( isset( $_POST['pav'] ) && $_POST['date'] && $_POST['t_text'] ) {
		$val = array( $_POST['pav'], $_POST['date'], $_POST['t_text'] );
	} else {
		$val = array( "", "", "" );
	}

	$info[] = array(
		"#"                               => " <input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('arch');\" />",
		$lang['admin']['article']         => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pav\" />",
		$lang['admin']['article_date']    => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"date\" />",
		$lang['admin']['article_preface'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"t_text\" />",
		$lang['admin']['action']          => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
	);
	//FILTRAVIMAS
	foreach ( $sql2 as $row ) {
		$info[] = array(
			"#"                               => "<input type=\"checkbox\" value=\"{$row['id']}\" name=\"articles_delete[]\" />",
			$lang['admin']['article']         => "<span style='cursor:pointer;' title='" . $lang['admin']['article_author'] . ": <b>" . $row['autorius'] . "</b>' >" . input( $row['pav'] ) . "</span>",
			$lang['admin']['article_date']    => date( 'Y-m-d', $row['date'] ),
			$lang['admin']['article_preface'] => "<span style='cursor:pointer;' title='" . strip_tags( $row['t_text'] ) . "'>" . trimlink( strip_tags( $row['t_text'] ), 55 ) . "</span>",
			$lang['admin']['action']          => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $row['id'] ) . "' title='{$lang['admin']['delete']}' onClick=\"return confirm('" . $lang['system']['delete_confirm'] . "')\"><img src=\"" . ROOT . "images/icons/cross.png\" border=\"0\"></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $row['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
		);
	}

	if ( !empty( $info ) && count( $info ) ) {
		$tableClass = new Table($info);
		$content = "<form id=\"arch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele($lang['admin']['article_edit'], $content);
	}
	// if list is bigger than limit, then we show list with pagination
	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}
}

if ( $_GET['v'] == 7 || isset( $url['h'] ) ) {
	$ar         = array( "TAIP" => "{$lang['admin']['yes']}", "NE" => "{$lang['admin']['no']}" );
	$editOrCreate = (isset($extra) ? $lang['admin']['edit'] : $lang['admin']['article_create']);
	$straipsnis = array(
		"Form"									=> array( "action" => url( "?id," . $_GET['id'] . ";a," . $_GET['a'] ), "method" => "post", "name" => "reg" ), 
		"{$lang['admin']['article_title']}:" 	=> array( "type" => "text", "value" => input( ( isset( $extra ) ) ? $extra['pav'] : '' ), "name" => "pav", "class" => "input" ), "" => array( "type" => "hidden", "name" => "idas", "value" => ( isset( $extra['id'] ) ? input( $extra['id'] ) : '' ) ), 
		"{$lang['admin']['article_comments']}:" => array( "type" => "select", "value" => array( 'taip' => $lang['admin']['yes'], 'ne' => $lang['admin']['no'] ), "name" => "kom", "class" => "input", "class" => "input" ), 
		"{$lang['system']['category']}:" 		=> array( "type" => "select", "value" => $kategorijos, "name" => "kategorija", "class" => "input", "class" => "input", "selected" => ( isset( $extra['kat'] ) ? input( $extra['kat'] ) : '' ) ), 
		"{$lang['admin']['article_shown']}:"	=> array( "type" => "select", "value" => $ar, "name" => "rodoma", "class" => "input", "class" => "input", "selected" => ( isset( $extra['rodoma'] ) ? input( $extra['rodoma'] ) : '' ) ), 
		"{$lang['admin']['article']}:" 			=> array( "type" => "string", "value" => editor( 'jquery', 'standartinis', array( 'str' => $lang['admin']['article'] ), array( 'str' => ( isset( $extra ) ? $extra['t_text'] . ( empty( $extra['f_text'] ) ? '' : "\n===page===\n" . $extra['f_text'] ) : $lang['admin']['article'] ) ) ) ), 
		$editOrCreate							=> array( "type" => "submit", "name" => "action", "value" => ( isset( $extra ) ) ? $lang['admin']['edit'] : $lang['admin']['article_create'] )
	);
	
	if ( isset( $extra['id'] ) ) {
		$naujiena[''] = array( "type" => "text", "name" => "idas", "value" => ( isset( $extra['id'] ) ? input( $extra['id'] ) : '' ) );
	}

	$formClass = new Form($straipsnis);	
	lentele($lang['admin']['article_create'], $formClass->form());

} elseif ( $_GET['v'] == 6 ) {
	$viso = kiek( "straipsniai", "WHERE `rodoma`='NE' AND `lang` = " . escape( lang() ) . "" );
	///FILTRAVIMAS
	$sqlQuery = "SELECT * FROM  `" . LENTELES_PRIESAGA . "straipsniai` WHERE `lang` = " . escape( lang() ) . " " . ( isset( $_POST['pav'] ) ? "AND (`pav` LIKE " . escape( "%" . $_POST['pav'] . "%" ) . " " . ( !empty( $_POST['date'] ) ? " AND `date` <= " . strtotime( $_POST['date'] ) . "" : "" ) . " " . ( !empty( $_POST['t_text'] ) ? " AND `t_text` LIKE " . escape( "%" . $_POST['t_text'] . "%" ) . "" : "" ) . ")" : "" ) . " AND rodoma='NE' ORDER BY id DESC LIMIT {$p},{$limit}";
	//
	if ($q = mysql_query1($sqlQuery)) {
		
		$info =[];

		if ( isset( $_POST['pav'] ) && $_POST['date'] && $_POST['t_text'] ) {
			$val = array( $_POST['pav'], $_POST['date'], $_POST['t_text'] );
		} else {
			$val = array( "", "", "", );
		}

		$info[] = array( 
			"#"                               => " <input type=\"checkbox\" name=\"visi\" onclick=\"checkedAll('arch');\" />",
			$lang['admin']['article']         => "<input class=\"filtrui\" type=\"text\" value=\"{$val[0]}\" name=\"pav\" />",
			$lang['admin']['article_date']    => "<input class=\"filtrui\" type=\"text\" value=\"{$val[1]}\" name=\"date\" />",
			$lang['admin']['article_preface'] => "<input class=\"filtrui\" type=\"text\" value=\"{$val[2]}\" name=\"t_text\" />",
			$lang['admin']['action']          => "<input type=\"submit\" value=\"{$lang['admin']['filtering']}\" name=\"\" />"
		);
		//FILTRAVIMAS
		foreach ( $q as $sql ) {
			$info[] = array( 
				"#"                               => "<input type=\"checkbox\" value=\"{$sql['id']}\" name=\"articles_delete[]\" />",
				$lang['admin']['article']         => '<span style="cursor:pointer;" title="' . $lang['admin']['article_author'] . ': <b>' . $sql['autorius'] . '</b>" >' . input( $sql['pav'] ) . '</span>',
				$lang['admin']['article_date']    => date( 'Y-m-d', $sql['date'] ),
				$lang['admin']['article_preface'] => "<span style='cursor:pointer;' title='" . strip_tags( $sql['t_text'] ) . "'>" . trimlink( strip_tags( $sql['t_text'] ), 55 ) . "</span>",
				$lang['admin']['action']          => "<a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};priimti," . $sql['id'] ) . "'title='{$lang['admin']['acept']}'><img src='" . ROOT . "images/icons/tick_circle.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};t," . $sql['id'] ) . "' title='{$lang['admin']['delete']}'><img src='" . ROOT . "images/icons/cross.png' border='0'></a> <a href='" . url( "?id,{$_GET['id']};a,{$_GET['a']};h," . $sql['id'] ) . "' title='{$lang['admin']['edit']}'><img src='" . ROOT . "images/icons/pencil.png' border='0'></a>"
			);
		}

		$tableClass  = new Table($info);
		$content = "<form id=\"arch\" method=\"post\">" . $tableClass->render() . "<input type=\"submit\" value=\"{$lang['system']['delete']}\" /></form>";
		lentele($lang['admin']['article_unpublished'], $content);
		// if list is bigger than limit, then we show list with pagination
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}
	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_items'] );
	}
}
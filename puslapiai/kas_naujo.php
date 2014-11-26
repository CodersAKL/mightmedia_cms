<?php
require_once ( "priedai/class.php" );
$bla    = new forma();
$dienos = array();
for ( $i = 0; $i < 101; $i++ )
	$dienos[$i] = $i;
$forma = array(
	"Form"                                          => array( "action" => "", "method" => "post", "name" => "naujienos" ),
	"Kelių dienų naujienas norėtumėte peržvelgti?:" => array( "type" => "select", "value" => $dienos, "name" => "dienos", "class" => "input", "selected" => ( isset( $_POST['dienos'] ) ? (int)$_POST['dienos'] : 7 ) ),
	" "                                             => array( "type" => "submit", "name" => "ziureti", "value" => "Žiūrėti" )
);
lentele( 'Kas naujo?', $bla->form( $forma ) );
/*if (isset($_POST['dienos'])) {*/
if ( isset( $_POST['dienos'] ) ) {
	$nuo = time() - 24 * 3600 * (int)$_POST['dienos'];
	$iki = time();
} elseif ( !empty( $url['d'] ) && isnum( $url['d'] ) ) {
	$nuo = mktime( 0, 0, 0, date( 'm', $url['d'] ), date( 'd', $url['d'] ), date( 'Y', $url['d'] ) ); //verciam timestamp iki nurodytos dienos pradzios
	$iki = (int)$url['d'];
} else {
	$nuo = time() - 24 * 3600 * 7;
	$iki = time();
}
if ( puslapis( 'frm.php' ) ) {
	$q = mysql_query1( "SELECT `id`,`tid`,`pav`,`autorius`,`last_data`,`last_nick` FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `last_data` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " ORDER BY `last_data` DESC" );
	if ( sizeof( $q ) > 0 ) {
		//date('Y-m-d H:i:s', $row['data'])
		$text = '';
		foreach ( $q as $row ) {
			$text .= "\t <a href='" . url( "?id," . $conf['puslapiai']['frm.php']['id'] . ";t," . $row['id'] . ";s," . $row['tid'] . "#end" ) . "'>" . trimlink( $row['pav'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['last_data'] ) . " - " . $row['last_nick'] . ")<br />\n";
		}
		lentele( $lang['new']['forum'], $text );
		unset( $text, $row, $q );
	}
}
//naujienose
if ( puslapis( 'naujienos.php' ) ) {
	$q = mysql_query1( "SELECT `id`, `pavadinimas`,`data`,`autorius` FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `data` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " AND `rodoma`='TAIP' ORDER BY `data` DESC" );
	if ( sizeof( $q ) > 0 ) {
		$text = '';
		foreach ( $q as $row ) {
			$text .= "<a href='" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ) . "'>" . trimlink( $row['pavadinimas'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['data'] ) . " - " . $row['autorius'] . ")<br />\n";
		}
		lentele( $lang['new']['news'], $text );
		unset( $text, $row, $q );
	}
}
//galerijoj
if ( puslapis( 'galerija.php' ) ) {
	$q = mysql_query1( "SELECT `ID`, `apie`, `pavadinimas`,`data`,`autorius` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `data` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " AND `rodoma`='TAIP' ORDER BY `data` DESC" );
	if ( sizeof( $q ) > 0 ) {
		//$text .= "<b>{$lang['new']['gallery']}:</b><br/>";
		$text = '';
		foreach ( $q as $row ) {
			$text .= "<a href='" . url( "?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['ID'] ) . "'>" . trimlink( $row['pavadinimas'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['data'] ) . ")<br />\n";
		}
		lentele( $lang['new']['gallery'], $text );
		unset( $text, $row, $q );
	}
}
//siuntiniuose
if ( puslapis( 'siustis.php' ) ) {
	$q = mysql_query1( "SELECT `ID`, `apie`, `pavadinimas`, `categorija`,`autorius`,`data` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `data` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " AND `rodoma`='TAIP' ORDER BY `data` DESC" );
	if ( sizeof( $q ) > 0 ) {
		//$text .= "<b>{$lang['new']['downloads']}:</b><br/>";
		$text = '';
		foreach ( $q as $row ) {
			$text .= "<a href='" . url( "?id," . $conf['puslapiai']['siustis.php']['id'] . ";k," . $row['categorija'] . ";v," . $row['ID'] ) . "'>" . trimlink( $row['pavadinimas'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['data'] ) . ")<br />\n";
		}
		lentele( $lang['new']['downloads'], $text );
		unset( $text, $row, $q );
	}
}
//straipsniai
if ( puslapis( 'straipsnis.php' ) ) {
	$q = mysql_query1( "SELECT `id`, `t_text`, `pav`, `kat`, `date`, `autorius` FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `date` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " AND `rodoma`='TAIP'  ORDER BY `date` DESC" );
	if ( sizeof( $q ) > 0 ) {
		//$text .= "<b>{$lang['new']['articles']}:</b><br/>";
		$text = '';
		foreach ( $q as $row ) {
			$text .= "<a href='" . url( "?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] ) . "'>" . trimlink( $row['pav'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['date'] ) . " - " . $row['autorius'] . ")<br />\n";
		}
		lentele( $lang['new']['articles'], $text );
		unset( $text, $row, $q );
	}
}
$q = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `data` BETWEEN " . escape( $nuo ) . " AND " . escape( $iki ) . " ORDER BY `data` " );
if ( sizeof( $q ) > 0 ) {
	//$text .= "<b>{$lang['new']['comments']}:</b><br/>";
	$text = '';
	foreach ( $q as $row ) {
		if ( $row['pid'] == 'puslapiai/naujienos' && puslapis( 'naujienos.php' ) ) {
			//$extra = "Naujienos";
			$link = "k," . $row['kid'];
		} elseif ( $row['pid'] == 'puslapiai/siustis' && puslapis( 'siustis.php' ) ) {
			$linkas = mysql_query1( "SELECT categorija FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID`='" . $row['kid'] . "'LIMIT 1" );
			$link   = "k," . $linkas['categorija'] . ";v," . $row['kid'] . "";
			//$extra = "Siuntiniai";
			//$link = "v," . $row['kid'];
		} //siuntiniai ?id,50;k,25#162
		elseif ( $row['pid'] == 'puslapiai/straipsnis' && puslapis( 'straipsnis.php' ) ) {

			//$extra = "Straipsniai";
			$link = "m," . $row['kid'];
		} //?id,22;p,14;t,14#325
		elseif ( $row['pid'] == 'puslapiai/galerija' && puslapis( 'galerija.php' ) ) {
			//$extra = "Galerija";
			$link = "m," . $row['kid'];
		} //?id,46;a,12;v,8#7
		elseif ( $row['pid'] == 'puslapiai/view_user' && puslapis( 'view_user.php' ) ) {
			//$extra = "Vartotojai";
			$link = "m," . $row['kid'];
		} elseif ( $row['pid'] == 'puslapiai/todo' && puslapis( 'todo.php' ) ) {
			//$extra = "Vartotojai";
			$link = "v," . $row['kid'];
		} elseif ( $row['pid'] == 'puslapiai/codebin' && puslapis( 'codebin.php' ) ) {
			//$extra = "Vartotojai";
			$link = "c," . $row['kid'];
		}
		elseif ( $row['pid'] == 'puslapiai/blsavimo_archyvas' && puslapis( 'blsavimo_archyvas.php' ) ) {
			//$extra = "Vartotojai";
			$link = "m," . $row['kid'];
		}

		$file = str_replace( 'puslapiai/', '', $row['pid'] );
		if ( puslapis( $file . ".php" ) ) {
			if ( strlen( $row['nick'] ) > 15 ) {
				$ar     = unserialize( $row['nick'] );
				$author = $ar[0];
			} else {
				$author = $row['nick'];
			}


			$text .= "<a href='" . url( "?id," . $conf['puslapiai'][$file . '.php']['id'] . ";" . $link . "#" . $row['id'] ) . "' title=\"{$lang['new']['author']}: <b>" . $author . "</b><br/>{$lang['new']['date']}: <b>" . date( 'Y-m-d H:i:s ', $row['data'] ) . "</b><br/>\">" . trimlink( $row['zinute'], 40 ) . "</a> (" . date( 'Y-m-d H:i:s', $row['data'] ) . " - " . $author . ")<br />\n";
		}
	}
	lentele( $lang['new']['comments'], $text );
	unset( $text, $row, $q );
}

<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 679 $
 * @$Date: 2010-06-12 19:18:44 +0300 (Št, 12 Bir 2010) $
 **/

if ( isset( $_SESSION[SLAPTAS]['lankesi'] ) && $_SESSION[SLAPTAS]['lankesi'] > 0 ) {
	$text    = '';
	$extra   = '';
	$link    = '';
	$ikonele = "<img src=\"images/icons/status-away.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
	//Forume
	if ( isset( $conf['puslapiai']['frm.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `id`,`id` AS strid,`tid`,`tid` as `temosid`,`pav`,`autorius`,`last_data`,`last_nick`, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "d_zinute` WHERE `tid`=`temosid` AND`sid`=strid AND `lang` = " . escape( lang() ) . " ) AS viso	 FROM `" . LENTELES_PRIESAGA . "d_straipsniai` WHERE `last_data` >= " . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `lang` = " . escape( lang() ) . " ORDER BY `last_data` DESC LIMIT 5" );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['new']['forum']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= "\t {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['frm.php']['id'] . ";t," . $row['id'] . ";s," . $row['tid'] . ";p," . ( (int)( $row['viso'] / 15 - 0.1 ) * 15 ) ) . "#end'>" . trimlink( input( $row['pav'] ), 20 ) . "</a><br />\n";
			}
		}
	}
	//Naujienose
	if ( isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `id`, `pavadinimas` FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `data` >= " . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `data` DESC LIMIT 10", 60 );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['new']['news']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ) . "'>" . trimlink( input( $row['pavadinimas'] ), 20 ) . "</a><br />\n";
			}
		}
	}
	//Galerijoje
	if ( isset( $conf['puslapiai']['galerija.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `ID`, `apie`, `pavadinimas` FROM `" . LENTELES_PRIESAGA . "galerija` WHERE `data`>=" . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `data` DESC LIMIT 10", 60 );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['new']['gallery']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['galerija.php']['id'] . ";m," . $row['ID'] ) . "'>" . trimlink( input( $row['pavadinimas'] ), 20 ) . "</a><br />\n";
			}
		}
	}
	//Siuntiniuose
	if ( isset( $conf['puslapiai']['siustis.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `ID`, `apie`, `pavadinimas`, `categorija` FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `data`>=" . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `data` DESC LIMIT 10", 60 );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['new']['downloads']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['siustis.php']['id'] . ";k," . $row['categorija'] . ";v," . $row['ID'] ) . "'>" . trimlink( input( $row['pavadinimas'] ), 20 ) . "</a><br />\n";
			}
		}
	}
	//Straipsniai
	if ( isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `id`, `t_text`, `pav`, `kat` FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `date`>=" . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `date` DESC LIMIT 10", 60 );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['new']['articles']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] ) . "'>" . trimlink( input( $row['pav'] ), 20 ) . "</a><br />\n";
			}
		}
	}

	//Nuorodos
	if ( isset( $conf['puslapiai']['nuorodos.php']['id'] ) ) {
		$q = mysql_query1( "SELECT `id`, `pavadinimas`, `apie`, `date`, `cat` FROM `" . LENTELES_PRIESAGA . "nuorodos` WHERE `date`>=" . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " AND `active`='TAIP' AND `lang` = " . escape( lang() ) . " ORDER BY `date` DESC LIMIT 10", 60 );
		if ( sizeof( $q ) > 0 ) {
			$text .= "<b>{$lang['pages']['nuorodos.php']}:</b><br/>";
			foreach ( $q as $row ) {
				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['nuorodos.php']['id'] . ";k," . $row['cat'] . ";w," . $row['id'] ) . "' target='_blank' rel='nofollow'>" . trimlink( $row['pavadinimas'], 20 ) . "</a><br />\n";
			}
		}
	}

	//Komentarai
	$q = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "kom` WHERE `data`>=" . escape( $_SESSION[SLAPTAS]['lankesi'] ) . " ORDER BY `data` DESC LIMIT 10", 60 );
	if ( sizeof( $q ) > 0 ) {
		$text .= "<b>{$lang['new']['comments']}:</b><br/>";
		foreach ( $q as $row ) {
			//Naujienos
			if ( $row['pid'] == 'puslapiai/naujienos' && isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
				$link = "k," . $row['kid'];
				//Siuntiniai
			} elseif ( $row['pid'] == 'puslapiai/siustis' && isset( $conf['puslapiai']['siustis.php']['id'] ) ) {
				$linkas = mysql_query1( "SELECT categorija FROM `" . LENTELES_PRIESAGA . "siuntiniai` WHERE `ID`='" . $row['kid'] . "'LIMIT 1", 60 );
				$link   = "k," . $linkas['categorija'] . ";v," . $row['kid'] . "";
				//Strapsniai
			} elseif ( $row['pid'] == 'puslapiai/straipsnis' && isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
				$link = "m," . $row['kid'];
				//Galerija
			} elseif ( $row['pid'] == 'puslapiai/galerija' && isset( $conf['puslapiai']['galerija.php']['id'] ) ) {
				$link = "m," . $row['kid'];
				//Vartotojai
			} elseif ( $row['pid'] == 'puslapiai/view_user' && isset( $conf['puslapiai']['view_user.php']['id'] ) ) {
				$narys = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `id`=" . escape( $row['kid'] ) . " LIMIT 1" );
				$link  = "" . $narys['nick'];
				//Kas naujo
			} elseif ( $row['pid'] == 'puslapiai/todo' && isset( $conf['puslapiai']['todo.php']['id'] ) ) {
				$link = "v," . $row['kid'];
				//Kodu
			} elseif ( $row['pid'] == 'puslapiai/codebin' && isset( $conf['puslapiai']['codebin.php']['id'] ) ) {
				$link = "c," . $row['kid'];
				//Balsavimo archyvas
			} elseif ( $row['pid'] == 'puslapiai/blsavimo_archyvas' && isset( $conf['puslapiai']['blsavimo_archyvas.php']['id'] ) ) {
				$link = "m," . $row['kid'];
			}

			$file = str_replace( 'puslapiai/', '', $row['pid'] );
			if ( isset( $conf['puslapiai'][$file . ".php"]['id'] ) ) {
				if ( $row['nick_id'] == 0 ) {
					$authmas = unserialize( $row['nick'] );
					$author  = $authmas[0];
				} else {
					$author = $row['nick'];
				}


				$text .= " {$ikonele} <a href='" . url( "?id," . $conf['puslapiai']['' . $file . '.php']['id'] . ";" . $link . "#" . $row['id'] ) . "' title=\"{$lang['new']['author']}: <b>" . $author . "</b><br/>{$lang['new']['date']}: <b>" . date( 'Y-m-d H:i:s ', $row['data'] ) . "</b><br/>\">" . trimlink( input( $row['zinute'] ), 20 ) . "</a><br />\n";
			}
		}
	}

	if ( !isset( $text ) || empty( $text ) ) {
		$row_p['show'] = 'N';
		$title         = ' ';
		$text          = ' ';
	}
} else {
	$row_p['show'] = 'N';
	$title         = ' ';
	$text          = ' ';
}

?>
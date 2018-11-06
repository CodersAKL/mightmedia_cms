<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

$p     = isset( $url['p'] ) ? (int)$url['p'] : 0;
$k     = isset( $url['k'] ) ? (int)$url['k'] : 0;
$limit = $conf['News_limit'];
$text  = '';
include_once ( "rating.php" );
//Kategorijų sąrašas
$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='straipsniai' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas`", 86400 );
if ( $sqlas && sizeof( $sqlas ) > 0 && !isset( $url['m'] ) ) {
	foreach ( $sqlas as $sql ) {
		//TODO: skaiciavimas is neriboto gylio.. dabar tik kategorija + sub kategorija skaiciuoja
		if ( $sql['path'] == $k ) {
			//$sqlkiek = kiek('straipsniai', "WHERE ".($k != 0 ? "`kat` =" . escape($sql['path']) . " OR" : "")." `kat` =" . escape($sql['id']) . " AND `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
			$kiek    = mysql_query1( "SELECT count(*) + (SELECT count(*) FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `kat` IN (SELECT `id` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `path`=" . escape( $sql['id'] ) . ")) as `kiek` FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `kat`=" . escape( $sql['id'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 1" );
			$sqlkiek = $kiek['kiek'];
			$info[]  = array(
				$lang['system']['categories'] => "<a style=\"float: left;\" class=\"kat\" href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><img src='images/naujienu_kat/" . input( $sql['pav'] ) . "' alt=\"\"  border=\"0\" /></a><div><a href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><b>" . input( $sql['pavadinimas'] ) . "</b></a><span class=\"small_about\"style='font-size:9px;width:auto;display:block;'><div>" . input( $sql['aprasymas'] ) . "</div><div>{$lang['category']['articles']}: $sqlkiek</div></span></div>" //,
			);
		}
	}
	include_once ( "priedai/class.php" );
	$bla = new Table();
	if ( isset( $info ) ) {
		lentele( $page_pavadinimas, $bla->render( $info ), FALSE );
	}

}
//Kategorijų sąrašo pabaiga
//Jei pasirinkta kategoriją
if ( $k >= 0 && empty( $url['m'] ) ) {

	$pav  = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='$k' AND `lang` = " . escape( lang() ) . " LIMIT 1", 86400 );
	$viso = kiek( "straipsniai", "WHERE `rodoma`='TAIP' AND `kat`='" . $k . "' AND `lang` = " . escape( lang() ) . "" );
	if ( $viso > 0 ) {
		//$sql = mysql_query1("SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' AND `kat`='" . $k . "' AND `lang` = ".escape(lang())." ORDER BY `date` DESC LIMIT $p, $limit", 86400);
		$sql = mysql_query1( "
			SELECT *, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/straipsnis' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "straipsniai`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "straipsniai`
			WHERE `rodoma`= 'TAIP'
			AND `kat`='" . $k . "'
			AND `lang` = " . escape( lang() ) . "
			ORDER BY `date` DESC
			LIMIT {$p},{$limit}", 100 );
		if ( teises( $pav['teises'], $_SESSION[SLAPTAS]['level'] ) ) {

			lentele( ( !empty( $pav['pavadinimas'] ) ? $pav['pavadinimas'] : $lang['pages']['straipsnis.php'] ), $pav['aprasymas'] . "<br /><i>{$lang['category']['articles']}: {$viso}</i>" );
			foreach ( $sql as $row ) {
				$sql_autr = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= '" . $row['autorius'] . "' LIMIT 1" );
				$data     = $row['date'];
				$autorius = user( $row['autorius'], $sql_autr['id'], $sql_autr['levelis'] );

				if ( isset( $conf['puslapiai']['straipsnis.php']['id'] ) ) {
					if ( $row['kom'] == 'taip' && isset( $conf['kmomentarai_sveciams'] ) && $conf['kmomentarai_sveciams'] != 3 ) {
						$kiekis = $row['viso'];
					}
					$nuoroda = "" . url( "?id," . $conf['puslapiai']['straipsnis.php']['id'] . ";m," . $row['id'] . ";" . seo_url( $row['pav'], $row['id'] ) ) . "";
					lentele_c( $row['pav'], $row['t_text'], $nuoroda, $kiekis, $data, $autorius, rating_form( $page, $row['id'] ) );
				}
			}
		} else {
			klaida( $lang['system']['warning'], "{$lang['article']['cant']}." );
		}
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}
		unset( $text, $row, $sql );

	} elseif ( $k > 0 ) {
		klaida( $lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );
	}

} elseif ( !empty( $url['m'] ) ) {
	$row = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "straipsniai` WHERE `rodoma`='TAIP' AND `id`=" . escape( (int)$url['m'] ) . " AND `lang` = " . escape( lang() ) . " LIMIT 1", 86400 );

	$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`=" . escape( $row['kat'] ) . " AND `kieno`='straipsniai' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas` LIMIT 1", 86400 );
	addtotitle( $row['pav'] );
	if ( teises( $sqlas['teises'], $_SESSION[SLAPTAS]['level'] ) && !empty( $row['date'] ) ) {

		$text = $row['t_text'] . "<div class='line'></div>";
		if ( !empty( $row['f_text'] ) ) {
			$text = $row['f_text'] . "<div class='line'></div>";
		}
		$sql_aut = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= '" . $row['autorius'] . "' LIMIT 1" );
		$text .= "{$lang['article']['date']}: " . date( 'Y-m-d H:i:s', $row['date'] ) . ",
	{$lang['article']['author']}: " . user( $row['autorius'], $sql_aut['id'], $sql_aut['levelis'] ) . "";
		//Dalintis
		$text .= "
			<div class='line'></div>
			<!-- AddThis Button BEGIN -->
			<div class='addthis_toolbox addthis_default_style '><a href='http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e7a05051d3cf281' class='addthis_button_compact'>" . $lang['news']['share'] . "</a><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a05051d3cf281'></script><a class='addthis_button_facebook_like' fb:like:layout='button_count'></a><a class='addthis_button_tweet'></a><a class='addthis_button_google_plusone' g:plusone:size='medium'></a></div><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a03fc44b95268'></script>
			<!-- AddThis Button END -->
			";
		lentele( input( $row['pav'] ), $text, rating_form( $page, $row['id'] ) );
		include ( ROOTAS . "priedai/komentarai.php" );

		komentarai( $url['m'], TRUE );
	} else {
		klaida( $lang['system']['warning'], "{$lang['article']['cant']}." );
	}
}
if ( count( $_GET ) == 1 ) {
	if ( kiek( "straipsniai", "WHERE rodoma='TAIP' AND `lang` = " . escape( lang() ) . "" ) <= 0 ) {
		klaida( $lang['system']['warning'], $lang['system']['no_content'] . "<br /><a href=\"javascript: history.go(-1)\">{$lang['download']['back']}</a>" );
	}
}

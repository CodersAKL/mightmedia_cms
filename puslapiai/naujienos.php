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
if ( !defined( "OK" ) ) {
	header( "location: ?" );
	exit;
}
if ( isset( $url['k'] ) && isnum( $url['k'] ) && $url['k'] > 0 ) {
	$kid = (int)$url['k'];
} else    {
	$kid = 0;
}
if ( isset( $url['p'] ) && isnum( $url['p'] ) && $url['p'] > 0 ) {
	$p = (int)$url['p'];
} else {
	$p = 0;
}

if ( $conf['Palaikymas'] == 1 ) {
	klaida( "{$lang['admin']['maintenance']}", $conf['Maintenance'] );
}
include_once ( "rating.php" );
$limit = $conf['News_limit'];
$viso  = kiek( "naujienos", "WHERE `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . "" );
$text  = '';
$data  = '';

// Jei niekas nepaspaudziama, o atidaromas pirminis puslapis
if ( $kid == 0 ) {

	$sql = mysql_query1( "
			SELECT *, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP'
			AND `lang` = " . escape( lang() ) . "
			ORDER BY `sticky` DESC,`data` DESC
			LIMIT {$p},{$limit}", 100 );

	if ( sizeof( $sql ) > 0 ) {
		foreach ( $sql as $row ) {
			if ( isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {
				//Paprasta nuoroda
				//$n_nuoroda = "" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ) . "";
				//SEO nuoroda
				$n_nuoroda = "" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";".seo_url($row['pavadinimas'],";k,".$row['id']) ) . "";
                $kiekis = '';
				if ( $row['kom'] == 'taip' && isset( $conf['kmomentarai_sveciams'] ) && $conf['kmomentarai_sveciams'] != 3 ) {
					$kiekis .= $row['viso'];
				}
			}
			$sql_autr        = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= '" . $row['autorius'] . "' LIMIT 1" );
			$data            = $row['data'];
			$autorius        = user( $row['autorius'], $sql_autr['id'], $sql_autr['levelis'] );
			$kategorijos_pav = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id` = " . escape( $row['kategorija'] ) . " AND `lang` = " . escape( lang() ) . " limit 1" );
			$pav             = "";
			if ( isset( $kategorijos_pav['pav'] ) ) {
				if ( isset( $conf['puslapiai']['naujkat.php']['id'] ) ) {
					$pav .= "<div title='<b>{$lang['system']['category']}: </b>" . input( $kategorijos_pav['pavadinimas'] ) . "' class='kat'><a href='" . url( "?id," . $conf['puslapiai']['naujkat.php']['id'] . ";k," . (int)$kategorijos_pav['id'] ) . "'><img src='images/naujienu_kat/" . input( $kategorijos_pav['pav'] ) . "' alt='img' border='0' /></a></div>";
				} else {
					$pav .= "<div title='<b>{$lang['system']['category']}: </b>" . input( $kategorijos_pav['pavadinimas'] ) . "' class='kat'><img src='images/naujienu_kat/" . input( $kategorijos_pav['pav'] ) . "' alt='img' border='0' /></div>";
				}

			}
			$pav .= "";

			if ( !isset( $kategorijos_pav['pav'] ) || teises( $kategorijos_pav['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
				if ( $row['sticky'] != 0 ) {
					echo '<div class="sticky" id="news_' . $row['id'] . '">';
				}
				lentele_c( $row['pavadinimas'], '' . $pav . $row['naujiena'] . '', $n_nuoroda, $kiekis, $data, $autorius, rating_form( $page, $row['id'] ) );
				if ( $row['sticky'] != 0 ) {
					echo '</div>';
				}
			}
		}
	} else {
		lentele( $lang['news']['news'], $lang['news']['nonews'] );
	}

	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}

	unset( $sql, $row, $extra, $pav, $autorius, $data, $n_nuoroda, $kiekis );
}
if ( $kid != 0 ) {
	$sql = "SELECT `" . LENTELES_PRIESAGA . "naujienos`.*, `" . LENTELES_PRIESAGA . "grupes`.`teises` AS `teises` FROM `" . LENTELES_PRIESAGA . "naujienos` Inner Join `" . LENTELES_PRIESAGA . "grupes` ON `" . LENTELES_PRIESAGA . "naujienos`.`kategorija` = `" . LENTELES_PRIESAGA . "grupes`.`id` WHERE `" . LENTELES_PRIESAGA . "naujienos`.`rodoma`='TAIP'  AND `" . LENTELES_PRIESAGA . "naujienos`.`id` = " . escape( $kid ) . " limit 1";
	$sql = mysql_query1( $sql, 3600 );

	if ( empty( $sql['naujiena'] ) ) {
		$sql = mysql_query1( "
			SELECT *, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom`
				WHERE `pid`='puslapiai/naujienos'
				AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
				WHERE `rodoma`= 'TAIP'
				AND `id` = " . escape( $kid ) . "
				AND `lang` = " . escape( lang() ) . "
			limit 1", 3600 );
	}

	if ( isset( $sql['naujiena'] ) && !empty( $sql['naujiena'] ) ) {
		addtotitle( $sql['pavadinimas'] );
		if ( teises( ( isset( $sql['teises'] ) ? $sql['teises'] : 0 ), $_SESSION[SLAPTAS]['level'] ) ) {
			$title = $sql['pavadinimas'];
			$text  = "<div class='naujiena'>" . $sql['naujiena'];
			if ( !empty( $sql['daugiau'] ) ) {
				$text = '<div class="naujiena">' . $sql['daugiau'];
			}
			$sql_aut = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `nick`= '" . $sql['autorius'] . "' LIMIT 1" );
			$text .= "</div><div class='line'></div>" . date( 'Y-m-d H:i:s', $sql['data'] ) . ",  " . user( $sql['autorius'], $sql_aut['id'], $sql_aut['levelis'] ) . "";

			//Dalintis
			$text .= "<div class='line'></div>
			<!-- AddThis Button BEGIN -->
			<div class='addthis_toolbox addthis_default_style '><a href='http://www.addthis.com/bookmark.php?v=250&amp;pubid=xa-4e7a05051d3cf281' class='addthis_button_compact'>" . $lang['news']['share'] . "</a><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a05051d3cf281'></script><a class='addthis_button_facebook_like' fb:like:layout='button_count'></a><a class='addthis_button_tweet'></a><a class='addthis_button_google_plusone' g:plusone:size='medium'></a></div><script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7a03fc44b95268'></script>
			<!-- AddThis Button END -->
			";

			//Atvaizduojam naujieną
			lentele( $title, $text, rating_form( $page, $sql['id'] ) . '' );
			//Susijusios naujienos
			$susijus = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija`=" . escape( $sql['kategorija'] ) . " AND `id`!=" . escape( $_GET['k'] ) . " AND `lang` = " . escape( lang() ) . " AND `rodoma`= 'TAIP' ORDER by `data` DESC LIMIT 5", 30000 );
			if ( sizeof( $susijus ) > 0 ) {
				$naujienos = "<ul id=\"naujienos\">";
				foreach ( $susijus as $susijusios ) {
					//Paprasta nuoroda
					// " . url( "?id," . $_GET['id'] . ";k," . $susijusios['id'] ) . "
					//SEO nuoroda
					// " . url( "?id," . $_GET['id'] . ";".seo_url($susijusios['pavadinimas'],";k,".$susijusios['id']) ) . "
					$naujienos .= "<li><a href=\"" . url( "?id," . $_GET['id'] . ";".seo_url($susijusios['pavadinimas'],";k,".$susijusios['id']) ) . "\" title=\"{$susijusios['pavadinimas']}\">" . trimlink( $susijusios['pavadinimas'], 55 ) . "</a> (" . date( 'Y-m-d H:i:s', $susijusios['data'] ) . ")</li>";
				}
				$naujienos .= "</ul>";
				lentele( $lang['news']['related'], $naujienos );
			}
			//Rodom komentarus
			if ( $sql['kom'] == 'taip' ) {
				include ( "priedai/komentarai.php" );
				komentarai( $kid, TRUE );
			}
			unset( $text, $title, $data, $kalba );
		} else {
			( !defined( 'LEVEL' ) ? klaida( $lang['system']['forbidden'], $lang['news']['notallowed'] ) : klaida( $lang['system']['error'], $lang['news']['notallowed'] ) );

		}
	} else {
		klaida( $lang['system']['error'], $lang['news']['notexists'] );
	}
}

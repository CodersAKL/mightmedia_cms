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

//nustatom pid ir kid
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
//kiek irasu per psl
$limit = $conf['News_limit'];
$text  = '';
//Paulius svaigsta su kategoriju sarasu
$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `kieno`='naujienos' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas`", 86400 );
if ( $sqlas && sizeof( $sqlas ) > 0 ) {
	foreach ( $sqlas as $sql ) {
		//TODO: skaiciavimas is neriboto gylio.. dabar tik kategorija + sub kategorija skaiciuoja
		if ( $sql['path'] == $k ) {
			//$sqlkiek = kiek('naujienos', "WHERE ".($k == 0 ? "`kategorija` =" . escape($sql['path']) . " OR" : "")." `kategorija` =" . escape($sql['id']) . " AND `rodoma`='TAIP' AND `lang` = ".escape(lang())."");
			$kiek    = mysql_query1( "SELECT count(*) + (SELECT count(*) FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija` IN (SELECT `id` FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `path`=" . escape( $sql['id'] ) . ")) as `kiek` FROM `" . LENTELES_PRIESAGA . "naujienos` WHERE `kategorija`=" . escape( $sql['id'] ) . " AND `rodoma`='TAIP' AND `lang` = " . escape( lang() ) . " LIMIT 1" );
			$sqlkiek = $kiek['kiek'];
			$info[]  = array(
				$lang['system']['categories'] => "<a style=\"float: left;\" class=\"kat\" href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><img src='images/naujienu_kat/" . input( $sql['pav'] ) . "' alt=\"\"  border=\"0\" /></a><div><a href='" . url( "?id," . $url['id'] . ";k," . $sql['id'] . "" ) . "'><b>" . input( $sql['pavadinimas'] ) . "</b></a><span class=\"small_about\"style='font-size:9px;width:auto;display:block;'><div>" . input( $sql['aprasymas'] ) . "</div><div>{$lang['category']['news']}: $sqlkiek</div></span></div>" //,
			);

		}
	}
}
include_once ( "priedai/class.php" );
$bla = new Table();
if ( isset( $info ) ) {
	lentele( "{$lang['system']['categories']}", $bla->render( $info ), FALSE );
}
//Rodom naujienas esancias kategorijoj

$sql = mysql_query1( "
			SELECT *, (SELECT COUNT(*) FROM `" . LENTELES_PRIESAGA . "kom` WHERE `pid`='puslapiai/naujienos' AND `" . LENTELES_PRIESAGA . "kom`.`kid` = `" . LENTELES_PRIESAGA . "naujienos`.`id`) AS `viso`
			FROM `" . LENTELES_PRIESAGA . "naujienos`
			WHERE `rodoma`= 'TAIP' AND `kategorija`=" . escape(=$k ) . "
			AND `lang` = " . escape( lang() ) . "
			ORDER BY `sticky` DESC, `data` DESC
			LIMIT {$p},{$limit}", 86400);
//$viso = count($sql);
$viso = kiek( "naujienos", "WHERE `rodoma`= 'TAIP' AND `kategorija`=" . escape( $k ) . " AND `lang` = " . escape( lang() ) . "" );
if ( $viso > 0 ) {
	$sqlas = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "grupes` WHERE `id`='" . $k . "' AND `kieno`='naujienos' AND `lang` = " . escape( lang() ) . " ORDER BY `pavadinimas` LIMIT 1", 86400 );
	if ( $viso > $limit ) {
		lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
	}
	if ( $k >= 0 ) {
		if ( teises( $sqlas['teises'], $_SESSION['level'] ) || LEVEL == 1 ) {
			foreach ( $sql as $row ) {
				if ( isset( $conf['puslapiai']['naujienos.php']['id'] ) ) {

					$extra = "<div style='float: right;'>" . ( ( $row['kom'] == 'taip' ) ? "<a href='" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ) . "'>{$lang['news']['read']} • {$lang['news']['comments']} (" . $row['viso'] . ")</a>" : "<a href='" . url( "?id," . $conf['puslapiai']['naujienos.php']['id'] . ";k," . $row['id'] ) . "'>{$lang['news']['read']}</a>" ) . "</div><br />";

					lentele( $row['pavadinimas'], "<table><tr valign='top'><td>" . $row['naujiena'] . "</td></tr></table>" . $extra, FALSE, array( menesis( (int)date( 'm', strtotime( date( 'Y-m-d H:i:s', $row['data'] ) ) ) ), (int)date( 'd', strtotime( date( 'Y-m-d H:i:s', $row['data'] ) ) ) ) );
				}
			}
		} else {
			klaida( $lang['system']['warning'], "{$lang['category']['cant']}." );
		}
	}
} elseif ( $k > 0 ) {
	klaida( $lang['system']['warning'], "{$lang['category']['no_news']}." );
}

?>
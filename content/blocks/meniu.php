<?php
unset( $data, $res );
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS
 * @license GNU General Public License v2
 * @$Revision: 811 $
 * @$Date: 2012-06-13 19:05:30 +0300 (Tr, 13 Bir 2012) $
 **/

$res = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "page` WHERE `show`='Y' AND `lang` = " . escape( lang() ) . " ORDER BY `place` ASC" );
$data = array();
if ( sizeof( $res ) > 0 ) {
	foreach ( $res as $row ) {
		if ( teises( $row['teises'], $_SESSION[SLAPTAS]['level'] ) ) {
			$data[$row['parent']][] = $row;
		}
	}
	$text = "<div id=\"navigation\"><ul>" . build_menu( $data ) . "</ul></div>";
} else {
	$text = "";
}
unset( $data, $res );

?>
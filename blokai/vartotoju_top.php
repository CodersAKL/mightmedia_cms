<?php
/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: zlotas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 829 $
 * @$Date: 2012-08-17 10:17:01 +0300 (Pn, 17 Rgp 2012) $
 **/

$duomenys = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "users` WHERE `taskai` > 0 ORDER BY `taskai` DESC LIMIT 10" );
$i        = 0;

if ( sizeof( $duomenys ) > 0 ) {
	$text = "<ul class=\"sarasas\">";
	foreach ( $duomenys as $row ) {
		$i++;
		switch ( $i ) {
			case 1 :
				{
				$img = "<img src=\"images/icons/trophy.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
				break;
				}
			case 2 :
				{
				$img = "<img src=\"images/icons/trophy_silver.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
				break;
				}
			case 3 :
				{
				$img = "<img src=\"images/icons/trophy_bronze.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
				break;
				}
			default :
				{
				$img = "<img src=\"images/icons/brightness_small_low.png\" alt=\"o\" class=\"middle\" border=\"0\"/>";
				}
		}

		$text .= "<li><b>{$i}</b> {$img} " . user( $row['nick'], $row['id'] ) . "</li>";
	}
	$text .= "</ul>";
} else {
	$text          = ' ';
	$row_p['show'] = 'N';
}
unset( $img, $duomenys, $i );

?>
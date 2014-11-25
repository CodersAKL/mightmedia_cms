<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 403 $
 * @$Date: 2010-03-11 15:32:24 +0200 (Thu, 11 Mar 2010) $
 **/

$kalbos = getFiles( ROOTAS . 'lang/' );
$text   = '';
foreach ( $kalbos as $file ) {
	if ( $file['type'] == 'file' && basename( $file['name'], '.php' ) != lang() ) {
		$text .= '<a href="' . url( '?id,' . $_GET['id'] . ';lang,' . basename( $file['name'], '.php' ) ) . '"><img src="images/icons/flags/' . basename( $file['name'], '.php' ) . '.png" alt="' . basename( $file['name'], '.php' ) . '" class="language flag ' . basename( $file['name'], '.php' ) . '" /></a>';
	}
}
?>

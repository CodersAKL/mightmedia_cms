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

if ( !empty( $_SESSION[SLAPTAS]['mod'] ) ) {

	function editor( $tipas = 'jquery', $dydis = 'standartinis', $id = FALSE, $value = '' ) {

		return editorius( $tipas, $dydis, $id, $value );
	}

	$failai = unserialize( $_SESSION[SLAPTAS]['mod'] );
	$text   = "
	<script type=\"text/javascript\" src=\"core/assets/javascript/jquery/jquery-ui-1.7.2.custom.min.js\"></script>
	<table border=\"0\">
	<tr>
		<td >
<div>";
	foreach ( $failai as $id => $failas ) {
		if ( $failas != 'com' && $failas != 'frm' ) {
			$text .= "<div class=\"blokas\"><center><a href=\"" . url( "?id," . $url['id'] . ";a," . ( $id + 1 ) ) . "\"><img src=\"core/assets/images/mod/" . basename( $failas, ".php" ) . ".png\" />" . ( strlen( getLangText('admin', basename( $failas, ".php" )) ) ? getLangText('admin', basename( $failas, ".php" )) : nice_name( $failas ) ) . "</a></center></div>";
		}
	}
	$text .= "</div><br style=\"clear:left\"/></td>
	</tr>
</table>

";

	lentele( $page_pavadinimas, $text );
	unset( $text );
	if ( isset( $url['a'] ) ) {
		if ( file_exists( ROOT . $conf['Admin_folder'] . "/" . $failai[( (int)$url['a'] - 1 )] ) ) {
			include_once ( ROOT . $conf['Admin_folder'] . "/" . $failai[( (int)$url['a'] - 1 )] );
		} else {
			klaida( getLangText('system', 'error'), getLangText('system', 'nopage') );
		}
	}
} else {
	klaida( getLangText('system', 'error'), getLangText('system', 'nopage') );
}

?>
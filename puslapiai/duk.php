<?php

/**
 * @Projektas : MightMedia TVS
 * @Puslapis  : www.coders.lt
 * @$Author$
 * @copyright CodeRS ©2008
 * @license   GNU General Public License v2
 * @$Revision$
 * @$Date$
 **/

$text = <<<HTML
<script type="text/javascript">
$(document).ready(function() {
	$('#duk p').each(function() {
	//$('#duk div').hide();
		var tis = $(this), state = false, answer = tis.next('div').hide();
		tis.click(function() {
			state = !state;
			answer.slideToggle(state);
			tis.toggleClass('active',state);
		});
	});
});
</script>
HTML;
$text .= "<div id='duk'>";
$nr  = 0;
$sql = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "duk` WHERE `lang` = " . escape( lang() ) . " ORDER by `order` ASC", 3600 );
if ( sizeof( $sql ) > 0 ) {
	foreach ( $sql as $row ) {
		$nr++;
		$text .= "<p style='font-size: 12px; font-weight: bold;'><a style='cursor: pointer;'>" . $nr . ". " . $row['klausimas'] . "</a></p>
		<div>" . $row['atsakymas'] . "</div>";
	}

	$text .= "</div>";
	lentele( $lang['faq']['faq_answers'], $text );
	unset( $text );
} else {
	klaida( $lang['system']['error'], $lang['system']['no_items'] );
}

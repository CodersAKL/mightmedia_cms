<?php

/**
 * @Projektas: MightMedia TVS
 * @Puslapis: www.coders.lt
 * @$Author: p.dambrauskas $
 * @copyright CodeRS ©2008
 * @license GNU General Public License v2
 * @$Revision: 811 $
 * @$Date: 2012-06-13 19:05:30 +0300 (Tr, 13 Bir 2012) $
 **/
//paima klausima
$quest = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "poll_questions` WHERE `shown`='1' AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC LIMIT 1", 3600 );
//jei klausimas egzistuoja
if ( isset( $quest['question'] ) ) {
	//atsakymai
	$answers = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_answers` WHERE `question_id`=" . escape( $quest['id'] ) . " ORDER BY `id` ASC", 3600 );
	//balsai
	$votes        = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_votes` WHERE `question_id`=" . escape( $quest['id'] ), 3600 );
	$ip           = getip();
	$show_rezults = FALSE;
	$viso         = 0;
	$voted        = array();

	foreach ( $votes as $vote ) {
		if ( !isset( $voted[$vote['answer_id']] ) ) {
			$voted[$vote['answer_id']] = 1;
		} else {
			$voted[$vote['answer_id']]++;
		}
		if ( $ip == $vote['ip'] ) {
			$show_rezults = TRUE;
		}
		$viso++;
	}
	//jei dar neprabalsuota
	if ( !$show_rezults ) {
		if ( isset( $_POST['answer'] ) && ( $quest['only_guests'] == 0 || ( $quest['only_guests'] == 1 && isset( $_SESSION[SLAPTAS]['username'] ) ) ) ) {
			delete_cache( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_votes` WHERE `question_id`=" . escape( $quest['id'] ) );
			if ( $quest['radio'] == 1 ) {
				mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "poll_votes` (`ip`, `question_id`, `answer_id`) VALUES (" . escape( $ip ) . ", " . escape( $quest['id'] ) . ", " . escape( $_POST['answer'][0] ) . ")" );
			} else {
				foreach ( $_POST['answer'] as $answer )
					mysql_query1( "INSERT INTO `" . LENTELES_PRIESAGA . "poll_votes` (`ip`, `question_id`, `answer_id`) VALUES (" . escape( $ip ) . ", " . escape( $quest['id'] ) . ", " . escape( $answer ) . ")" );
			}
			header( "LOCATION: " . $_SERVER['HTTP_REFERER'] );
		}
		$text = "<b style=\"text-align: center;\">" . input( $quest['question'] ) . "</b><form method=\"post\">";
		//vartiantai
		foreach ( $answers as $row ) {
			$text .= "<label><input type=\"" . ( $quest['radio'] == 1 ? 'radio' : 'checkbox' ) . "\" name=\"answer[]\" class=\"middle\" value=\"{$row['id']}\" /> " . input( $row['answer'] ) . "</label><br />";
		}
		if ( $quest['only_guests'] == 0 || ( $quest['only_guests'] == 1 && isset( $_SESSION[SLAPTAS]['username'] ) ) ) {
			$text .= '<div style="text-align: center;"><input name="vote" type="submit" value="' . $lang['poll']['vote'] . '" /></div>';
		} else {
			$text .= $lang['poll']['cant'];
		}
		$text .= "</form>";
	} else {
		$text = "<b style=\"text-align: center;\">" . input( $quest['question'] ) . "</b><br />";
		foreach ( $answers as $row ) {
			$voted[$row['id']] = ( isset( $voted[$row['id']] ) ? $voted[$row['id']] : 0 );
			$text .= input( $row['answer'] ) . " (" . $voted[$row['id']] . ")<br />
        <div style=\"width:" . round( (int)( 100 / $viso * $voted[$row['id']] ) ) . "%;background:url(" . adresas() . "images/balsavimas/center.png) top left repeat-x; height:10px\">
         	<div style=\"float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px\"></div>
			<div style=\"float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px\"></div>
		</div><br />";
		}
		$text .= '<br />	' . $lang['poll']['author'] . ': ' . user( $quest['author_name'], $quest['author_id'] ) . '';
		if ( puslapis( 'blsavimo_archyvas.php' ) ) {
			$text .= "<br /><a href=\"" . url( '?id,' . $conf['puslapiai']['blsavimo_archyvas.php']['id'] . ';m,' . $quest['id'] ) . "\">{$lang['news']['comments']}</a><br /><a href=\"" . url( '?id,' . $conf['puslapiai']['blsavimo_archyvas.php']['id'] ) . "\"> {$lang['poll']['archive']}</a>";
		}
	}
} else {
	$text = "<b>{$lang['poll']['no']}</b><br />";
}
?>
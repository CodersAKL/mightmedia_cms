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

if ( !isset( $_GET['m'] ) ) {
	$limit = 20;
	$viso  = kiek( "poll_questions", "WHERE `shown`='1' AND `lang` = " . escape( lang() ) . "" );
	if ( $viso > 0 ) {
		$p      = ( isset( $_GET['p'] ) ? $_GET['p'] : 0 );
		$quests = mysql_query1( "SELECT *, `id` as `qid`, (SELECT count(id) FROM `" . LENTELES_PRIESAGA . "poll_votes` WHERE `question_id`=`qid`) as `votes` FROM `" . LENTELES_PRIESAGA . "poll_questions` WHERE `shown`='1' AND `lang` = " . escape( lang() ) . " ORDER BY `id` DESC LIMIT $p , $limit", 3600 );
		$text   = '<ul>';
		foreach ( $quests as $row ) {
			$text .= "<li><a href =\"" . url( "?id,{$_GET['id']};m,{$row['id']}" ) . "\">" . input( $row['question'] ) . "</a> ({$row['votes']})</li>";
		}
		$text .= '</ul>';
		lentele( $lang['poll']['archive'], $text );
		if ( $viso > $limit ) {
			lentele( $lang['system']['pages'], puslapiai( $p, $limit, $viso, 10 ) );
		}
	} else {
		klaida( $lang['system']['warning'], $lang['system']['no_content'] );
	}
} else {
	$quest = mysql_query1( "SELECT * FROM `" . LENTELES_PRIESAGA . "poll_questions` WHERE `shown`='1' AND `lang` = " . escape( lang() ) . " AND `id`=" . escape( $_GET['m'] ) . " ORDER BY `id` DESC LIMIT 1", 3600 );
	if ( isset( $quest['question'] ) ) {
		$answers = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_answers` WHERE `question_id`=" . escape( $quest['id'] ) . " ORDER BY `id` ASC", 3600 );
		$votes   = mysql_query1( "SELECT * FROM  `" . LENTELES_PRIESAGA . "poll_votes` WHERE `question_id`=" . escape( $quest['id'] ), 3600 );
		$viso    = 0;
		$voted   = array();
		$text    = '';
		foreach ( $votes as $vote ) {
			if ( !isset( $voted[$vote['answer_id']] ) ) {
				$voted[$vote['answer_id']] = 1;
			} else {
				$voted[$vote['answer_id']]++;
			}
			$viso++;
		}
		foreach ( $answers as $row ) {
			$voted[$row['id']] = ( isset( $voted[$row['id']] ) ? $voted[$row['id']] : 0 );
			$text .= input( $row['answer'] ) . " (" . $voted[$row['id']] . ")<br />
        <div style=\"width:" . round( (int)( 100 / $viso * $voted[$row['id']] ) ) . "%;background:url(" . adresas() . "images/balsavimas/center.png) top left repeat-x; height:10px\">
          <div style=\"float:right;height:8px; width:1px; border-right:1px solid black;margin:1px -1px\"></div>
          <div style=\"float:left;height:8px; width:1px; border-right:1px solid black;margin:1px -2px\"></div>
        </div><br />";
		}
		$text .= '<br />	' . $lang['poll']['author'] . ': ' . user( $quest['author_name'], $quest['author_id'] ) . '';
		lentele( input( $quest['question'] ), $text );
		include( "priedai/komentarai.php" );
		komentarai( $url['m'], TRUE );
	} else {
		klaida( $lang['system']['error'], "{$lang['system']['pagenotfounfd']}." );
	}
}
